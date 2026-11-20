<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookRecommendationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        config()->set('book_recommendations.api_key', '');
    }

    private function createBook(string $title, int $quantity = 2, string $categoryName = 'Văn học'): Book
    {
        $author = Author::firstOrCreate(['name' => 'Tác giả kiểm thử']);
        $category = Category::firstOrCreate(['name' => $categoryName]);

        return Book::create([
            'title' => $title,
            'author_id' => $author->id,
            'category_id' => $category->id,
            'quantity' => $quantity,
            'description' => 'Mô tả sách kiểm thử.',
        ]);
    }

    public function test_guest_can_open_recommendation_page(): void
    {
        $this->get(route('recommendations.index'))
            ->assertOk()
            ->assertSee('Hôm nay bạn muốn đọc gì?');
    }

    public function test_missing_key_uses_only_available_database_books(): void
    {
        $this->createBook('Sách Văn Học 1');
        $this->createBook('Sách Văn Học 2');
        $this->createBook('Sách Thiếu Nhi', 1, 'Thiếu nhi');
        $unavailable = $this->createBook('Sách Hết Hàng', 0);

        Http::preventStrayRequests();

        $this->post(route('recommendations.store'), [
            'mood' => 'stressed',
            'goal' => 'relax',
            'note' => 'Đọc thư giãn',
        ])->assertOk()
            ->assertViewHas('source', 'local')
            ->assertViewHas('results', function (array $results) use ($unavailable) {
                return count($results) === 3
                    && collect($results)->every(fn ($item) =>
                        $item['book']->quantity > 0 && $item['book']->id !== $unavailable->id
                    );
            });

        Http::assertNothingSent();
    }

    public function test_gemini_ranks_only_valid_ids_and_local_fallback_fills_the_rest(): void
    {
        $first = $this->createBook('Sách Văn Học 1');
        $second = $this->createBook('Sách Văn Học 2');
        $third = $this->createBook('Sách Kỹ Năng', 3, 'Kỹ năng sống');
        $outOfStock = $this->createBook('Sách Hết Hàng', 0);

        config()->set('book_recommendations.api_key', 'key-test');
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [['content' => ['parts' => [[
                    'text' => json_encode(['recommendations' => [
                        ['id' => $second->id, 'reason' => 'Phù hợp vì sách dễ đọc'],
                        ['id' => 999999, 'reason' => 'Sách không có thật'],
                        ['id' => $outOfStock->id, 'reason' => 'Sách đã hết'],
                        ['id' => $second->id, 'reason' => 'Trùng lặp'],
                    ]], JSON_UNESCAPED_UNICODE),
                ]]]]],
            ], 200),
        ]);

        $response = $this->post(route('recommendations.store'), [
            'mood' => 'sad', 'goal' => 'relax',
        ]);

        $response->assertOk()
            ->assertViewHas('source', 'mixed')
            ->assertViewHas('results', function (array $results) use ($second, $first, $third) {
                $ids = collect($results)->pluck('book.id')->all();
                return $ids[0] === $second->id
                    && count($ids) === 3
                    && count(array_unique($ids)) === 3
                    && in_array($first->id, $ids, true)
                    && in_array($third->id, $ids, true);
            });

        Http::assertSentCount(1);
        Http::assertSent(fn ($request) =>
            str_contains($request->url(), '/models/gemini-3.1-flash-lite:generateContent')
            && $request->hasHeader('x-goog-api-key', 'key-test')
        );
    }

    public function test_api_503_falls_back_without_crashing_the_page(): void
    {
        $this->createBook('Sách A');
        $this->createBook('Sách B');
        config()->set('book_recommendations.api_key', 'key-test');

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'error' => ['code' => 503, 'status' => 'UNAVAILABLE'],
            ], 503),
        ]);

        $this->post(route('recommendations.store'), [
            'mood' => 'curious', 'goal' => 'learn',
        ])->assertOk()
            ->assertViewHas('source', 'local')
            ->assertViewHas('results', fn (array $results) => count($results) === 2);
    }

    public function test_cached_gemini_result_avoids_a_second_api_call(): void
    {
        $book = $this->createBook('Sách Đã Gợi Ý');
        config()->set('book_recommendations.api_key', 'key-test');

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [['content' => ['parts' => [[
                    'text' => json_encode(['recommendations' => [
                        ['id' => $book->id, 'reason' => 'Đúng sở thích'],
                    ]], JSON_UNESCAPED_UNICODE),
                ]]]]],
            ], 200),
        ]);

        $form = ['mood' => 'motivated', 'goal' => 'inspire'];
        $this->post(route('recommendations.store'), $form)->assertOk();
        $this->post(route('recommendations.store'), $form)->assertOk();

        Http::assertSentCount(1);
    }

    public function test_book_becoming_out_of_stock_while_ai_runs_is_removed(): void
    {
        $unavailableDuringCall = $this->createBook('Sách Đang Hết');
        $stillAvailable = $this->createBook('Sách Còn Lại');
        config()->set('book_recommendations.api_key', 'key-test');

        Http::fake(function () use ($unavailableDuringCall) {
            $unavailableDuringCall->update(['quantity' => 0]);
            return Http::response([
                'candidates' => [['content' => ['parts' => [[
                    'text' => json_encode(['recommendations' => [
                        ['id' => $unavailableDuringCall->id, 'reason' => 'Sách này đã hết'],
                        ['id' => 999999, 'reason' => 'Sách bịa'],
                    ]], JSON_UNESCAPED_UNICODE),
                ]]]]],
            ], 200);
        });

        $this->post(route('recommendations.store'), [
            'mood' => 'curious', 'goal' => 'enjoy',
        ])->assertOk()
            ->assertViewHas('results', fn (array $results) =>
                count($results) === 1 && $results[0]['book']->id === $stillAvailable->id
            );
    }

    public function test_invalid_form_values_are_rejected(): void
    {
        $this->from(route('recommendations.index'))
            ->post(route('recommendations.store'), [
                'mood' => 'unknown',
                'goal' => 'wrong',
                'note' => str_repeat('a', 281),
            ])
            ->assertRedirect(route('recommendations.index'))
            ->assertSessionHasErrors(['mood', 'goal', 'note']);
    }
}
