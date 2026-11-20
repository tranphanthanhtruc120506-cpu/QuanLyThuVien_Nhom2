<?php

namespace Tests\Feature\Admin;

use App\Models\Author;
use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryAndReaderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_from_admin_modules(): void
    {
        $this->get(route('admin.categories.index'))->assertRedirect(route('login'));
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
    }

    public function test_regular_readers_cannot_access_or_modify_admin_modules(): void
    {
        $reader = User::factory()->create(['role' => 'user']);
        $category = Category::create(['name' => 'Lịch sử']);

        $this->actingAs($reader)->get(route('admin.categories.index'))->assertForbidden();
        $this->actingAs($reader)->post(route('admin.categories.store'), ['name' => 'Truyện'])->assertForbidden();
        $this->actingAs($reader)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($reader)->patch(route('admin.users.toggle', $reader))->assertForbidden();
        $this->actingAs($reader)->delete(route('admin.categories.destroy', $category))->assertForbidden();
    }

    public function test_admin_can_search_create_update_and_delete_categories(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->post(route('admin.categories.store'), ['name' => 'Văn học mới'])
            ->assertRedirect(route('admin.categories.index'));
        $category = Category::where('name', 'Văn học mới')->firstOrFail();

        $this->get(route('admin.categories.index', ['q' => 'Văn học']))
            ->assertOk()->assertSee('Văn học mới');
        $this->get(route('admin.categories.edit', $category))->assertOk();

        $this->put(route('admin.categories.update', $category), ['name' => 'Văn học Việt Nam'])
            ->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Văn học Việt Nam']);

        $this->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_categories_cannot_be_duplicated_or_deleted_while_in_use(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Thiếu nhi']);
        $author = Author::create(['name' => 'Tác giả mẫu']);
        Book::create([
            'title' => 'Sách mẫu',
            'author_id' => $author->id,
            'category_id' => $category->id,
            'quantity' => 1,
        ]);

        $this->actingAs($admin)->post(route('admin.categories.store'), ['name' => 'Thiếu nhi'])
            ->assertSessionHasErrors('name');
        $this->actingAs($admin)->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_can_search_and_toggle_reader_active_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reader = User::factory()->create([
            'name' => 'Doc Gia Demo',
            'email' => 'reader-demonstration@example.test',
            'role' => 'user',
            'is_active' => true,
        ]);
        $this->actingAs($admin);

        $this->get(route('admin.users.index', ['q' => 'Doc Gia Demo']))
            ->assertOk()->assertSee('reader-demonstration@example.test');

        $this->patch(route('admin.users.toggle', $reader))
            ->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $reader->id, 'is_active' => 0]);

        $this->patch(route('admin.users.toggle', $reader))
            ->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $reader->id, 'is_active' => 1]);
    }

    public function test_admin_cannot_lock_an_admin_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->patch(route('admin.users.toggle', $otherAdmin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $otherAdmin->id, 'is_active' => 1]);
    }

    public function test_existing_book_author_and_borrow_routes_remain_available_to_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->get(route('books.index'))->assertOk();
        $this->get(route('admin.books.index'))->assertOk();
        $this->get(route('admin.authors.index'))->assertOk();
        $this->get(route('admin.borrows'))->assertOk();
    }

    public function test_existing_borrow_approve_return_flow_keeps_stock_consistent(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $reader = User::factory()->create(['role' => 'user', 'is_active' => true]);
        $author = Author::create(['name' => 'Tác giả kiểm thử']);
        $category = Category::create(['name' => 'Sách kiểm thử']);
        $book = Book::create([
            'title' => 'Quy trình mượn trả',
            'author_id' => $author->id,
            'category_id' => $category->id,
            'quantity' => 2,
        ]);

        $this->actingAs($reader)
            ->post(route('books.borrow', $book))
            ->assertRedirect(route('borrows.index'));

        $record = BorrowRecord::where('user_id', $reader->id)->where('book_id', $book->id)->firstOrFail();
        $this->assertSame(BorrowRecord::PENDING, $record->status);
        $this->assertSame(2, $book->fresh()->quantity);

        $this->actingAs($admin)->post(route('admin.borrows.approve', $record))->assertRedirect();
        $this->assertSame(BorrowRecord::BORROWING, $record->fresh()->status);
        $this->assertSame(1, $book->fresh()->quantity);

        $this->actingAs($admin)->post(route('admin.borrows.return', $record))->assertRedirect();
        $this->assertSame(BorrowRecord::RETURNED, $record->fresh()->status);
        $this->assertSame(2, $book->fresh()->quantity);
    }
}
