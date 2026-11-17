<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class LibrarySeeder extends Seeder
{
    public function run(): void
    {
        $authors = collect([
            'Nguyễn Nhật Ánh'  => 'Nhà văn nổi tiếng với các tác phẩm về tuổi thơ và tuổi học trò.',
            'Nam Cao'          => 'Nhà văn hiện thực xuất sắc của văn học Việt Nam.',
            'Tô Hoài'          => 'Tác giả của Dế Mèn phiêu lưu ký.',
            'Dale Carnegie'    => 'Tác giả sách kỹ năng sống và giao tiếp.',
            'Robert C. Martin' => 'Kỹ sư phần mềm, tác giả Clean Code.',
        ])->map(fn ($bio, $name) => Author::create(['name' => $name, 'bio' => $bio]));

        $cats = collect(['Văn học', 'Thiếu nhi', 'Kỹ năng sống', 'Công nghệ thông tin'])
            ->mapWithKeys(fn ($n) => [$n => Category::create(['name' => $n])]);

        $books = [
            // [tiêu đề, tác giả, thể loại, số lượng]
            // LƯU Ý: giữ nguyên thứ tự 12 dòng đầu, BorrowRecordSeeder dùng theo thứ tự này
            ['Mắt biếc',                        'Nguyễn Nhật Ánh',  'Văn học',             5],
            ['Cho tôi xin một vé đi tuổi thơ',  'Nguyễn Nhật Ánh',  'Thiếu nhi',           4],
            ['Tôi thấy hoa vàng trên cỏ xanh',  'Nguyễn Nhật Ánh',  'Văn học',             6],
            ['Chí Phèo',                        'Nam Cao',          'Văn học',             3],
            ['Lão Hạc',                         'Nam Cao',          'Văn học',             3],
            ['Dế Mèn phiêu lưu ký',             'Tô Hoài',          'Thiếu nhi',           5],
            ['Đắc nhân tâm',                    'Dale Carnegie',    'Kỹ năng sống',        8],
            ['Quẳng gánh lo đi và vui sống',    'Dale Carnegie',    'Kỹ năng sống',        4],
            ['Clean Code',                      'Robert C. Martin', 'Công nghệ thông tin', 2],
            ['The Clean Coder',                 'Robert C. Martin', 'Công nghệ thông tin', 1],
            ['Clean Architecture',              'Robert C. Martin', 'Công nghệ thông tin', 3],
            ['Sách hết hàng để test',           'Tô Hoài',          'Thiếu nhi',           0],
            // Thêm cho đủ ~20 cuốn để thấy phân trang
            ['Cô gái đến từ hôm qua',           'Nguyễn Nhật Ánh',  'Văn học',             4],
            ['Kính vạn hoa',                    'Nguyễn Nhật Ánh',  'Thiếu nhi',           5],
            ['Ngồi khóc trên cây',              'Nguyễn Nhật Ánh',  'Văn học',             3],
            ['Đời thừa',                        'Nam Cao',          'Văn học',             2],
            ['Sống mòn',                        'Nam Cao',          'Văn học',             2],
            ['Truyện Tây Bắc',                  'Tô Hoài',          'Văn học',             3],
            ['Clean Agile',                     'Robert C. Martin', 'Công nghệ thông tin', 2],
            ['Nghệ thuật nói trước công chúng', 'Dale Carnegie',    'Kỹ năng sống',        4],
        ];

        foreach ($books as [$title, $author, $cat, $qty]) {
            Book::create([
                'title'       => $title,
                'author_id'   => $authors[$author]->id,
                'category_id' => $cats[$cat]->id,
                'quantity'    => $qty,
                'description' => "Mô tả mẫu cho cuốn \"$title\".",
            ]);
        }

        // Gán ảnh bìa (ảnh nằm trong public/images/books)
        $covers = [
            'Chí Phèo' => 'chi-pheo.png',
            'Cho tôi xin một vé đi tuổi thơ' => 'cho-toi-xin-mot-ve-di-tuoi-tho.png',
            'Clean Agile' => 'clean-agile.png',
            'Clean Architecture' => 'clean-architecture.png',
            'Clean Code' => 'clean-code.png',
            'Cô gái đến từ hôm qua' => 'co-gai-den-tu-hom-qua.png',
            'Đắc nhân tâm' => 'dac-nhan-tam.png',
            'Dế Mèn phiêu lưu ký' => 'de-men-phieu-luu-ky.png',
            'Đời thừa' => 'doi-thua.png',
            'Kính vạn hoa' => 'kinh-van-hoa.png',
            'Lão Hạc' => 'lao-hac.png',
            'Mắt biếc' => 'mat-biec.png',
            'Nghệ thuật nói trước công chúng' => 'nghe-thuat-noi-truoc-cong-chung.png',
            'Ngồi khóc trên cây' => 'ngoi-khoc-tren-cay.png',
            'Quẳng gánh lo đi và vui sống' => 'quang-ganh-lo-di-va-vui-song.png',
            'Sống mòn' => 'song-mon.png',
            'The Clean Coder' => 'the-clean-coder.png',
            'Tôi thấy hoa vàng trên cỏ xanh' => 'toi-thay-hoa-vang-tren-co-xanh.png',
            'Truyện Tây Bắc' => 'truyen-tay-bac.png',
        ];

        foreach ($covers as $title => $filename) {
            Book::where('title', $title)->update([
                'image' => 'images/books/' . $filename,
            ]);
        }
    }
}