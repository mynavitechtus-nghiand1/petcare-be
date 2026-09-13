# Evidence — PetCare+ Backend

Repo: https://github.com/mynavitechtus-nghiand1/petcare-be

---

## Data & Database

### Schema + Migration + Rollback

Toàn bộ migrations nằm ở:
https://github.com/mynavitechtus-nghiand1/petcare-be/tree/main/database/migrations

Mỗi file đều có `up()` để tạo/alter bảng và `down()` để rollback.
Ví dụ file tạo bảng products với đầy đủ cột, FK, index:
https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/database/migrations/2026_09_03_094236_create_products_table.php

### Thêm tính năng mới vào DB — Voucher (migration & rollback)

Tạo bảng `vouchers` (code unique, discount_amount, expires_at, is_active):
https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/database/migrations/2026_09_04_042250_create_vouchers_table.php

Thêm cột `voucher_id` FK nullable vào bảng `orders` (nullOnDelete để không mất đơn hàng khi voucher bị xóa):
https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/database/migrations/2026_09_04_042625_add_voucher_id_to_orders_table.php

Model Voucher với method `isValid()` kiểm tra hạn sử dụng:
https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/app/Models/Voucher.php

### Index Migration

Thêm index trên `products(status)`, `products(brand_id, status)`, `orders(user_id, status)` để tăng tốc query filter:
https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/database/migrations/2026_09_04_025555_add_indexes_to_products_orders.php

### Benchmark Index (TODO — cần chụp màn hình)

Cần chạy lệnh dưới trong container và chụp kết quả:

```sql
-- Trước khi có index (drop index tạm thời)
EXPLAIN ANALYZE SELECT * FROM products WHERE status = 'published' AND brand_id = 1;

-- Sau khi có index
EXPLAIN ANALYZE SELECT * FROM products WHERE status = 'published' AND brand_id = 1;
```

So sánh số `cost=` và `actual time=` trong 2 kết quả.

---

## API Design & Integration

### API sản phẩm — REST, pagination, cache, filter

ProductController với Redis cache (TTL 5 phút), pagination, filter theo brand/category/tên:
https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/app/Http/Controllers/ProductController.php

Dòng quan trọng:
- L22–L25: cache key strategy (bỏ qua cache khi có filter)
- L37–39: `Cache::remember` — đọc Redis trước, nếu miss mới query DB

### API giỏ hàng

https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/app/Http/Controllers/CartController.php

### API checkout — xử lý concurrent update

OrderController dùng 2 lớp lock:
- **Pessimistic lock** dòng 68: `lockForUpdate()` — khóa row cart khi đọc, ngăn 2 request cùng checkout 1 giỏ
- **Optimistic lock** dòng 97–101: cập nhật inventory kèm `WHERE version = ?`, nếu version đã đổi thì fail và rollback

https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/app/Http/Controllers/OrderController.php#L68

### Routes tổng hợp

Toàn bộ endpoints (auth, products, cart, orders, admin):
https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/routes/api.php

---

## Auth & Backend

### Register / Login / Refresh Token

- `POST /auth/register` → trả access token (15 phút) + refresh token (30 ngày)
- `POST /auth/login` → tương tự
- `POST /auth/refresh` → rotate refresh token, ability `auth:refresh`

https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/app/Http/Controllers/AuthController.php

### Google OAuth (stateless)

Dùng Laravel Socialite ở chế độ stateless cho API (không cần session):
https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/app/Http/Controllers/Auth/GoogleController.php

### Rate Limit — chống brute force

5 lần login/phút theo IP, trả 429 khi vượt:
https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/app/Providers/AppServiceProvider.php#L29

### Admin Middleware — phân quyền theo role

Chặn request từ user không phải admin, trả 403:
https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/app/Http/Middleware/EnsureAdmin.php

---

## Testing

### Pest Feature Tests

Chạy test: `make test` (hoặc `docker exec petcare-learning_app php artisan test`)

| File | Test case |
|---|---|
| AuthTest | login thành công, login sai password, register trả role customer |
| ProductTest | chỉ trả sản phẩm published, filter theo brand_id, tìm kiếm theo tên |
| CheckoutTest | checkout thành công (inventory giảm, giỏ bị xóa), checkout thất bại khi giỏ trống |

- https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/tests/Feature/AuthTest.php
- https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/tests/Feature/ProductTest.php
- https://github.com/mynavitechtus-nghiand1/petcare-be/blob/main/tests/Feature/CheckoutTest.php

### Screenshot test pass (TODO — cần chụp màn hình)

Chạy `make test` rồi chụp terminal thấy toàn bộ test xanh.

---

## Còn thiếu

| Việc | Ghi chú |
|---|---|
| Screenshot `make test` pass | Chạy rồi chụp terminal |
| Benchmark EXPLAIN ANALYZE | Chạy SQL trước/sau index, chụp kết quả |
| UI/UX (NextJS) | Chưa làm — phần FE |
