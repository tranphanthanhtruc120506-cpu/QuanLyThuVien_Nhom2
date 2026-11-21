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
        /*
         * Dữ liệu tác giả: firstOrCreate giúp không tạo tác giả trùng khi
         * chạy Seeder nhiều lần. Bio chỉ được đặt khi tác giả chưa tồn tại.
         */
        $authorData = [
            'Nguyễn Nhật Ánh' => 'Nhà văn nổi tiếng với các tác phẩm về tuổi thơ và tuổi học trò.',
            'Nam Cao' => 'Nhà văn hiện thực xuất sắc của văn học Việt Nam.',
            'Tô Hoài' => 'Nhà văn Việt Nam, tác giả của Dế Mèn phiêu lưu ký.',
            'Dale Carnegie' => 'Tác giả sách kỹ năng sống, giao tiếp và phát triển bản thân.',
            'Robert C. Martin' => 'Kỹ sư phần mềm, tác giả các sách về thiết kế và chất lượng phần mềm.',
            'Paulo Coelho' => 'Nhà văn Brazil, nổi tiếng với các tiểu thuyết giàu tính triết lý.',
            'Hector Malot' => 'Nhà văn Pháp, tác giả tiểu thuyết Không gia đình.',
            'Mario Puzo' => 'Nhà văn Mỹ, nổi tiếng với tiểu thuyết Bố già.',
            'Jeffrey Archer' => 'Nhà văn Anh, tác giả nhiều tiểu thuyết và truyện trinh thám.',
            'James Clear' => 'Tác giả chuyên viết về thói quen, hành vi và phát triển cá nhân.',
            'Daniel Kahneman' => 'Nhà tâm lý học, nghiên cứu về tư duy và quá trình ra quyết định.',
            'George S. Clason' => 'Tác giả sách tài chính cá nhân Người giàu có nhất thành Babylon.',
            'Stephen R. Covey' => 'Tác giả và chuyên gia về lãnh đạo, hiệu quả cá nhân.',
            'J. K. Rowling' => 'Nhà văn Anh, tác giả bộ truyện Harry Potter.',
            'Antoine de Saint-Exupéry' => 'Nhà văn và phi công người Pháp, tác giả Hoàng tử bé.',
            'Lewis Carroll' => 'Nhà văn Anh, tác giả Alice ở xứ sở thần tiên.',
            'Andrew Hunt' => 'Nhà phát triển phần mềm, đồng tác giả The Pragmatic Programmer.',
            'David Thomas' => 'Nhà phát triển phần mềm, đồng tác giả The Pragmatic Programmer.',
            'Martin Fowler' => 'Tác giả và chuyên gia về kiến trúc, tái cấu trúc phần mềm.',
            'Erich Gamma' => 'Kỹ sư phần mềm, đồng tác giả Design Patterns.',
            'Richard Helm' => 'Kỹ sư phần mềm, đồng tác giả Design Patterns.',
            'Ralph Johnson' => 'Giáo sư khoa học máy tính, đồng tác giả Design Patterns.',
            'John Vlissides' => 'Nhà khoa học máy tính, đồng tác giả Design Patterns.',
            'Thomas H. Cormen' => 'Giáo sư khoa học máy tính, đồng tác giả Introduction to Algorithms.',
            'Charles Leiserson' => 'Nhà khoa học máy tính, đồng tác giả Introduction to Algorithms.',
            'Ronald Rivest' => 'Nhà khoa học máy tính, đồng tác giả Introduction to Algorithms.',
            'Clifford Stein' => 'Giáo sư khoa học máy tính, đồng tác giả Introduction to Algorithms.',
            'Phùng Quán' => 'Nhà văn Việt Nam, tác giả Tuổi thơ dữ dội.',
            'Vũ Trọng Phụng' => 'Nhà văn, nhà báo Việt Nam, nổi bật với các tác phẩm trào phúng và hiện thực.',
            'Kim Lân' => 'Nhà văn Việt Nam, thường viết về nông thôn và người nông dân.',
            'Ngô Tất Tố' => 'Nhà văn, nhà báo Việt Nam, tác giả Tắt đèn.',
            'Nguyễn Trung Thành' => 'Nhà văn Việt Nam, bút danh của Nguyên Ngọc.',
            'Nguyễn Minh Châu' => 'Nhà văn Việt Nam, cây bút tiêu biểu của văn học thời kỳ đổi mới.',
            'Ichiro Kishimi' => 'Nhà triết học và tác giả Nhật Bản, viết về tâm lý học Adler.',
            'Robin Sharma' => 'Tác giả và diễn giả về lãnh đạo, thói quen và phát triển cá nhân.',
            'Viktor E. Frankl' => 'Bác sĩ tâm thần và tác giả, nghiên cứu về ý nghĩa cuộc sống.',
            'Vãn Tình' => 'Tác giả sách tản văn và phát triển bản thân.',
            'Luis Sepúlveda' => 'Nhà văn Chile, tác giả Chuyện con mèo dạy hải âu bay.',
            'Astrid Lindgren' => 'Nhà văn Thụy Điển, tác giả các tác phẩm thiếu nhi.',
            'Frederick P. Brooks Jr.' => 'Nhà khoa học máy tính, tác giả The Mythical Man-Month.',
            'Kyle Simpson' => 'Tác giả và chuyên gia JavaScript, viết bộ You Don’t Know JS.',
            'James F. Kurose' => 'Giáo sư khoa học máy tính, đồng tác giả giáo trình mạng máy tính.',
        ];

        $authors = collect($authorData)->mapWithKeys(function ($bio, $name) {
            $author = Author::firstOrCreate(
                ['name' => $name],
                ['bio' => $bio]
            );

            return [$name => $author];
        });

        // Giữ bốn thể loại đang có trong dự án, không tạo bản ghi trùng.
        $categoryNames = [
            'Văn học',
            'Thiếu nhi',
            'Kỹ năng sống',
            'Công nghệ thông tin',
        ];

        $cats = collect($categoryNames)->mapWithKeys(function ($name) {
            return [$name => Category::firstOrCreate(['name' => $name])];
        });

        $books = [
            // [tiêu đề, tác giả, thể loại, số lượng, mô tả]
            // QUAN TRỌNG: giữ nguyên thứ tự 12 dòng đầu vì BorrowRecordSeeder
            // đang tham chiếu sách theo thứ tự ID trong bảng books.
            ['Mắt biếc', 'Nguyễn Nhật Ánh', 'Văn học', 5, 'Câu chuyện tình cảm trong trẻo, day dứt về tuổi học trò, tình yêu đơn phương và những ký ức khó quên.'],
            ['Cho tôi xin một vé đi tuổi thơ', 'Nguyễn Nhật Ánh', 'Thiếu nhi', 4, 'Tác phẩm nhìn thế giới qua góc nhìn trẻ thơ, gợi nhớ những trò chơi, tình bạn và ký ức tuổi nhỏ.'],
            ['Tôi thấy hoa vàng trên cỏ xanh', 'Nguyễn Nhật Ánh', 'Văn học', 6, 'Câu chuyện về tuổi thơ ở miền quê, tình anh em, tình bạn và những rung động đầu đời.'],
            ['Chí Phèo', 'Nam Cao', 'Văn học', 3, 'Truyện ngắn phản ánh bi kịch của người nông dân bị tha hóa và khát vọng được công nhận quyền làm người.'],
            ['Lão Hạc', 'Nam Cao', 'Văn học', 3, 'Tác phẩm khắc họa số phận người nông dân nghèo, tình phụ tử và lòng tự trọng trong hoàn cảnh khốn khó.'],
            ['Dế Mèn phiêu lưu ký', 'Tô Hoài', 'Thiếu nhi', 5, 'Cuộc phiêu lưu của Dế Mèn qua nhiều vùng đất, gửi gắm bài học về tình bạn, lòng dũng cảm và sự trưởng thành.'],
            ['Đắc nhân tâm', 'Dale Carnegie', 'Kỹ năng sống', 8, 'Cuốn sách chia sẻ các nguyên tắc ứng xử, lắng nghe, thấu hiểu và xây dựng mối quan hệ tích cực.'],
            ['Quẳng gánh lo đi và vui sống', 'Dale Carnegie', 'Kỹ năng sống', 4, 'Sách hướng dẫn cách nhìn nhận lo lắng, quản lý áp lực và tập trung giải quyết vấn đề trong cuộc sống.'],
            ['Clean Code', 'Robert C. Martin', 'Công nghệ thông tin', 2, 'Tài liệu về cách viết mã nguồn rõ ràng, dễ đọc, dễ bảo trì và các nguyên tắc lập trình tốt.'],
            ['The Clean Coder', 'Robert C. Martin', 'Công nghệ thông tin', 1, 'Sách bàn về tác phong chuyên nghiệp, trách nhiệm và đạo đức nghề nghiệp của lập trình viên.'],
            ['Clean Architecture', 'Robert C. Martin', 'Công nghệ thông tin', 3, 'Trình bày các nguyên tắc thiết kế kiến trúc phần mềm linh hoạt, dễ kiểm thử và ít phụ thuộc framework.'],
            ['Sách hết hàng để test', 'Tô Hoài', 'Thiếu nhi', 0, 'Đầu sách dữ liệu dùng để kiểm thử trạng thái hết hàng của chức năng quản lý thư viện.'],
            // 8 sách cũ còn lại: giữ nguyên dữ liệu và vị trí tương đối.
            ['Cô gái đến từ hôm qua', 'Nguyễn Nhật Ánh', 'Văn học', 4, 'Câu chuyện đan xen giữa ký ức tuổi thơ và tình cảm tuổi học trò, nhẹ nhàng và giàu hoài niệm.'],
            ['Kính vạn hoa', 'Nguyễn Nhật Ánh', 'Thiếu nhi', 5, 'Tập truyện về những tình huống đời thường, tình bạn và các cuộc phiêu lưu của nhóm học trò.'],
            ['Ngồi khóc trên cây', 'Nguyễn Nhật Ánh', 'Văn học', 3, 'Câu chuyện giàu cảm xúc về tình yêu, thiên nhiên và những rung động trong sáng của tuổi trẻ.'],
            ['Đời thừa', 'Nam Cao', 'Văn học', 2, 'Tác phẩm khai thác bi kịch tinh thần của người trí thức nghèo và khát vọng sống có ích.'],
            ['Sống mòn', 'Nam Cao', 'Văn học', 2, 'Tiểu thuyết phản ánh cuộc sống tù túng, những day dứt và ước mơ của người trí thức nghèo.'],
            ['Truyện Tây Bắc', 'Tô Hoài', 'Văn học', 3, 'Tập truyện phản ánh cuộc sống và sức sống của con người vùng Tây Bắc trong hoàn cảnh xã hội nhiều biến động.'],
            ['Clean Agile', 'Robert C. Martin', 'Công nghệ thông tin', 2, 'Giới thiệu các giá trị và nguyên tắc Agile, nhấn mạnh sự hợp tác, phản hồi và cải tiến liên tục.'],
            ['Nghệ thuật nói trước công chúng', 'Dale Carnegie', 'Kỹ năng sống', 4, 'Sách cung cấp phương pháp chuẩn bị nội dung, trình bày tự tin và giao tiếp hiệu quả trước đám đông.'],

            // ==================== 30 SÁCH BỔ SUNG ====================
            // Văn học: 10 cuốn
            ['Nhà giả kim', 'Paulo Coelho', 'Văn học', 4, 'Tiểu thuyết kể về hành trình theo đuổi ước mơ, khám phá bản thân và học cách lắng nghe những điều có ý nghĩa trong cuộc sống.'],
            ['Không gia đình', 'Hector Malot', 'Văn học', 3, 'Câu chuyện phiêu lưu của cậu bé Rémi, đề cao nghị lực, lòng nhân ái và tình cảm giữa con người.'],
            ['Bố già', 'Mario Puzo', 'Văn học', 3, 'Tiểu thuyết về gia đình Corleone, quyền lực, lòng trung thành và những lựa chọn đạo đức trong thế giới tội phạm.'],
            ['Hai số phận', 'Jeffrey Archer', 'Văn học', 3, 'Tiểu thuyết theo dõi hai cuộc đời có xuất phát điểm khác nhau, xoay quanh tham vọng, biến cố và số phận.'],
            ['Tuổi thơ dữ dội', 'Phùng Quán', 'Văn học', 4, 'Tác phẩm viết về những thiếu niên tham gia kháng chiến, làm nổi bật lòng dũng cảm, tình bạn và sự hy sinh.'],
            ['Số đỏ', 'Vũ Trọng Phụng', 'Văn học', 3, 'Tiểu thuyết trào phúng phê phán thói chạy theo lối sống trưởng giả và những nghịch cảnh xã hội.'],
            ['Vợ nhặt', 'Kim Lân', 'Văn học', 4, 'Truyện ngắn về tình người và khát vọng sống của những người nghèo trong nạn đói năm 1945.'],
            ['Tắt đèn', 'Ngô Tất Tố', 'Văn học', 3, 'Tác phẩm hiện thực phản ánh cuộc sống cơ cực của người nông dân và sự bất công trong xã hội cũ.'],
            ['Rừng xà nu', 'Nguyễn Trung Thành', 'Văn học', 3, 'Truyện ngắn khắc họa sức sống cộng đồng và tinh thần đấu tranh kiên cường của người dân Tây Nguyên.'],
            ['Chiếc thuyền ngoài xa', 'Nguyễn Minh Châu', 'Văn học', 3, 'Tác phẩm gợi suy ngẫm về cách nhìn đa chiều đối với con người, cuộc sống và những nghịch lý phía sau vẻ đẹp.'],

            // Kỹ năng sống: 8 cuốn
            ['Atomic Habits', 'James Clear', 'Kỹ năng sống', 5, 'Giải thích cách hình thành thói quen nhỏ, xây dựng hệ thống hành vi tích cực và duy trì thay đổi lâu dài.'],
            ['Tư duy nhanh và chậm', 'Daniel Kahneman', 'Kỹ năng sống', 3, 'Khám phá hai hệ thống tư duy, các thiên kiến nhận thức và cách con người đưa ra quyết định.'],
            ['Người giàu có nhất thành Babylon', 'George S. Clason', 'Kỹ năng sống', 4, 'Các câu chuyện ngụ ngôn tài chính truyền tải bài học về tiết kiệm, quản lý tiền và tích lũy tài sản.'],
            ['7 thói quen hiệu quả', 'Stephen R. Covey', 'Kỹ năng sống', 4, 'Trình bày bảy thói quen giúp chủ động, xác định mục tiêu, hợp tác và nâng cao hiệu quả cá nhân.'],
            ['Dám bị ghét', 'Ichiro Kishimi', 'Kỹ năng sống', 3, 'Cuộc đối thoại về tâm lý học Adler, sự tự do cá nhân, trách nhiệm và cách nhìn nhận các mối quan hệ.'],
            ['Đời ngắn đừng ngủ dài', 'Robin Sharma', 'Kỹ năng sống', 4, 'Tập hợp những suy ngẫm và gợi ý thực hành về quản lý thời gian, kỷ luật và sống có mục tiêu.'],
            ['Đi tìm lẽ sống', 'Viktor E. Frankl', 'Kỹ năng sống', 3, 'Tác phẩm suy ngẫm về ý nghĩa cuộc đời, khả năng lựa chọn thái độ và sức mạnh tinh thần trước nghịch cảnh.'],
            ['Bạn đắt giá bao nhiêu?', 'Vãn Tình', 'Kỹ năng sống', 3, 'Những câu chuyện và góc nhìn về sự tự chủ, giá trị bản thân và lựa chọn trong cuộc sống hiện đại.'],

            // Thiếu nhi: 5 cuốn
            ['Harry Potter và Hòn đá Phù thủy', 'J. K. Rowling', 'Thiếu nhi', 4, 'Mở đầu hành trình của Harry Potter tại trường Hogwarts, nơi cậu khám phá tình bạn, phép thuật và thân thế của mình.'],
            ['Hoàng tử bé', 'Antoine de Saint-Exupéry', 'Thiếu nhi', 5, 'Câu chuyện giàu tính tưởng tượng về tình yêu, tình bạn và cách nhìn thế giới bằng sự hồn nhiên.'],
            ['Alice ở xứ sở thần tiên', 'Lewis Carroll', 'Thiếu nhi', 3, 'Cuộc phiêu lưu kỳ ảo của Alice qua thế giới kỳ lạ, khuyến khích trí tưởng tượng và óc tò mò.'],
            ['Chuyện con mèo dạy hải âu bay', 'Luis Sepúlveda', 'Thiếu nhi', 4, 'Câu chuyện cảm động về lời hứa, lòng bao dung và sự khác biệt qua hành trình của chú mèo và chim hải âu.'],
            ['Pippi Tất Dài', 'Astrid Lindgren', 'Thiếu nhi', 3, 'Những cuộc phiêu lưu vui nhộn của cô bé Pippi mạnh mẽ, độc lập và có trí tưởng tượng phong phú.'],

            // Công nghệ thông tin: 7 cuốn
            ['The Pragmatic Programmer', 'Andrew Hunt', 'Công nghệ thông tin', 3, 'Chia sẻ tư duy thực hành, kỹ thuật làm việc và nguyên tắc giúp lập trình viên xây dựng phần mềm tốt hơn.'],
            ['Refactoring', 'Martin Fowler', 'Công nghệ thông tin', 3, 'Hướng dẫn cải thiện cấu trúc mã nguồn từng bước mà vẫn giữ nguyên hành vi bên ngoài của chương trình.'],
            ['Design Patterns', 'Erich Gamma', 'Công nghệ thông tin', 3, 'Giới thiệu các mẫu thiết kế hướng đối tượng phổ biến và cách áp dụng để giải quyết vấn đề thiết kế phần mềm.'],
            ['Introduction to Algorithms', 'Thomas H. Cormen', 'Công nghệ thông tin', 2, 'Giáo trình trình bày các thuật toán nền tảng, cấu trúc dữ liệu, phân tích độ phức tạp và phương pháp thiết kế thuật toán.'],
            ['The Mythical Man-Month', 'Frederick P. Brooks Jr.', 'Công nghệ thông tin', 2, 'Phân tích những thách thức trong quản lý dự án phần mềm, giao tiếp nhóm và tiến độ phát triển hệ thống.'],
            ['You Don’t Know JS Yet', 'Kyle Simpson', 'Công nghệ thông tin', 3, 'Bộ sách đi sâu vào nền tảng JavaScript, giúp người học hiểu cơ chế ngôn ngữ thay vì chỉ ghi nhớ cú pháp.'],
            ['Computer Networking: A Top-Down Approach', 'James F. Kurose', 'Công nghệ thông tin', 2, 'Giáo trình mạng máy tính tiếp cận từ ứng dụng xuống các tầng giao thức, phù hợp cho học tập và nghiên cứu.'],
        ];

        foreach ($books as [$title, $authorName, $categoryName, $quantity, $description]) {
            // Chỉ tạo sách nếu tiêu đề chưa tồn tại. Không cập nhật quantity,
            // author_id, category_id hay mô tả của sách đã có trong database.
            Book::firstOrCreate(
                ['title' => $title],
                [
                    'author_id' => $authors[$authorName]->id,
                    'category_id' => $cats[$categoryName]->id,
                    'quantity' => $quantity,
                    'description' => $description,
                ]
            );
        }

        // Giữ nguyên ánh xạ ảnh bìa cho 20 sách cũ.
        // Không thay đổi ảnh hoặc dữ liệu của các sách mới.
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

            // 30 ảnh bìa bổ sung
            'Nhà giả kim' => 'nha-gia-kim.png',
            'Không gia đình' => 'khong-gia-dinh.png',
            'Bố già' => 'bo-gia.png',
            'Hai số phận' => 'hai-so-phan.png',
            'Tuổi thơ dữ dội' => 'tuoi-tho-du-doi.png',
            'Số đỏ' => 'so-do.png',
            'Vợ nhặt' => 'vo-nhat.png',
            'Tắt đèn' => 'tat-den.png',
            'Rừng xà nu' => 'rung-xa-nu.png',
            'Chiếc thuyền ngoài xa' => 'chiec-thuyen-ngoai-xa.png',
            'Atomic Habits' => 'atomic-habits.png',
            'Tư duy nhanh và chậm' => 'tu-duy-nhanh-cham.png',
            'Người giàu có nhất thành Babylon' => 'nguoi-giau-co-nhat-thanh-babylon.png',
            '7 thói quen hiệu quả' => '7-thoi-quen-hieu-qua.png',
            'Dám bị ghét' => 'dam-bị-ghet.png',
            'Đời ngắn đừng ngủ dài' => 'doi-ngan-dung-ngu-dai.png',
            'Đi tìm lẽ sống' => 'di-tim-le-song.png',
            'Bạn đắt giá bao nhiêu?' => 'ban-dat-gia-bao-nhieu.png',
            'Harry Potter và Hòn đá Phù thủy' => 'harry-potter-va-hon-da-phu-thuy.png',
            'Hoàng tử bé' => 'hoang-tu-be.png',
            'Alice ở xứ sở thần tiên' => 'alice-o-xu-so-than-tien.png',
            'Chuyện con mèo dạy hải âu bay' => 'chuyen-con-meo-day-hai-au-bay.png',
            'Pippi Tất Dài' => 'pippi-tat-dai.png',
            'The Pragmatic Programmer' => 'the-pragmatic-programmer.png',
            'Refactoring' => 'refactoring.png',
            'Design Patterns' => 'design-patterns.png',
            'Introduction to Algorithms' => 'introduction-to-algorithms.png',
            'The Mythical Man-Month' => 'the-mythical-man-month.png',
            'You Don’t Know JS Yet' => 'you-dont-know-js-jet.png',
            'Computer Networking: A Top-Down Approach' => 'computer-networking-a-top-down-approach.png',
        ];

        foreach ($covers as $title => $filename) {
            $relativePath = 'images/books/' . $filename;

            // Chỉ cập nhật ảnh khi file thực sự tồn tại trong public/images/books.
            if (file_exists(public_path($relativePath))) {
                Book::where('title', $title)->update([
                    'image' => $relativePath,
                ]);
            }
        }
    }
}
