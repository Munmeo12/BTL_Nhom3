# BÀI TẬP LỚN MÔN LẬP TRÌNH WEB - NHÓM 3
## ĐỀ TÀI: XÂY DỰNG WEBSITE TIN TỨC / BLOG CÓ TRANG QUẢN TRỊ (ADMIN PORTAL)

---

## 1. Danh sách thành viên và phân công công việc:
1. **Trần Thị Hà Anh** - MSSV: 224001713 (Trưởng nhóm)
   - **Phân công**: Quản lý tiến độ nhóm; Thiết kế và xây dựng Form Đăng ký Admin (`register.php`); Viết xử lý Server-side Validation; Mã hóa dữ liệu chống tấn công XSS và cài đặt chức năng Upload ảnh đại diện an toàn.
2. **Nguyễn Hải Vân** - MSSV: 224001759
   - **Phân công**: Phân tích yêu cầu bài tập lớn; Thiết kế giao diện HTML/CSS, bố cục Layout Responsive cho Form và các trang quản trị.
3. **Đào Khánh Trung** - MSSV: 224001757
   - **Phân công**: Xây dựng sơ đồ Wireframe cho các màn hình/form chính; Tổng hợp bảng quy tắc Server-side Validation và viết tài liệu `README.md` dự án nhóm.
4. **Nguyễn Trung Vũ Học** - MSSV: 224001719
   - **Phân công**: Thiết kế cấu trúc thư mục dự án; Định nghĩa danh sách Route/Trang chức năng dự kiến và hỗ trợ kiểm thử (Testing) các kịch bản dữ liệu Form.

---

## 2. Danh sách Route / Trang chức năng dự kiến của Hệ thống
- `/index.php`: Trang chủ hiển thị danh sách tin tức/bài viết mới nhất.
- `/detail.php`: Trang chi tiết bài viết blog/tin tức.
- `/admin/login.php`: Trang đăng nhập dành cho Quản trị viên.
- `/admin/register.php`: Form đăng ký/tạo tài khoản Quản trị viên mới (Đã tích hợp Server-side Validation).
- `/admin/posts.php`: Màn hình quản lý danh sách bài viết tin tức.
- `/uploads/`: Thư mục hệ thống lưu trữ hình ảnh tải lên an toàn (Avatar Admin, Ảnh bài viết).

---

## 3. Bảng Quy tắc Validation phía Server (Server-side Validation Rules)
| Trường dữ liệu | Quy tắc Validation | Thông báo lỗi khi nhập sai |
| :--- | :--- | :--- |
| **Họ và tên** | Bắt buộc (`NotEmpty`), độ dài từ 3 đến 50 ký tự | "Họ và tên phải từ 3 đến 50 ký tự." |
| **Username** | Bắt buộc (`NotEmpty`), từ 4-20 ký tự, chỉ gồm chữ cái, số và `_` (`^a-zA-Z0-9_`) | "Tên đăng nhập từ 4-20 ký tự, không chứa ký tự đặc biệt." |
| **Email** | Bắt buộc (`NotEmpty`), chuẩn định dạng email (`FILTER_VALIDATE_EMAIL`) | "Định dạng Email không hợp lệ." |
| **Mật khẩu** | Bắt buộc (`NotEmpty`), độ dài tối thiểu 6 ký tự | "Mật khẩu phải có ít nhất 6 ký tự." |
| **Xác nhận Mật khẩu**| Khớp hoàn toàn với trường Mật khẩu | "Mật khẩu xác nhận không khớp." |
| **Ảnh đại diện Avatar**| Dung lượng ≤ 2 MB, đuôi mở rộng `.jpg, .jpeg, .png, .gif`, kiểm tra MIME Type `image/*` | "File tải lên không hợp lệ hoặc vượt quá 2MB." |

---

## 4. Các giải pháp Bảo mật đã tích hợp trong Form
1. **Chống tấn công XSS (Cross-Site Scripting):** Toàn bộ dữ liệu đầu ra được lọc và mã hóa bằng hàm `htmlspecialchars($str, ENT_QUOTES, 'UTF-8')`.
2. **Xử lý Upload File An Toàn:**
   - Kiểm tra lỗi Upload và dung lượng file (≤ 2 MB).
   - Kiểm tra cả Đuôi mở rộng lẫn MIME Type thực tế của file tải lên bằng `finfo_file()`.
   - Đổi tên file ngẫu nhiên tự động bằng `uniqid('admin_avatar_', true)` để chống ghi đè file và chặn tên file chứa mã độc.