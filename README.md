# PetCare+ Learning Backend

Boilerplate Laravel 12 (Docker + Postgres + Redis) để học và phát triển PetCare+.

## Có gì lúc đầu?

| Có | Học sau / chưa cần |
|---|---|
| Laravel 12, PHP 8.4, Docker | Auth OTP, AWS, Firebase, MinIO |
| Postgres + Redis + Nginx + MailHog | — |
| `Modules/Core` (BaseModel, BaseService…) | — |
| API middleware + `ApiResponse` | Feature nghiệp vụ đầy đủ |
| `GET /api/v1/health` | Identity, Catalog, Cart, Order (bạn tự thêm) |
| Migration tối thiểu: users, cache, jobs, tokens | Bảng catalog / order |

## Chạy lần đầu

1. Mở **Docker Desktop** (Engine Running).
2. Trong terminal:

```bash
cd /Users/macbook_239/Desktop/Project/ITFS/Backend/BaseSource/petcare-be
make start
```

3. Kiểm tra:

```bash
curl -i http://localhost:8080/api/v1/health
curl -i http://localhost:8080/up
```

Kỳ vọng: JSON `success: true`, `database: ok`, `redis: ok`.

## Lệnh thường dùng

| Lệnh | Việc |
|---|---|
| `make start` | Up Docker + composer + migrate |
| `make stop` | Dừng |
| `make shell` | Vào container app |
| `make logs` | Xem log |
| `make migrate` | Chạy migration |
| `make seed` | Tạo user `learner@example.com` / `password` |
| `make test` | Pest |

## Cấu trúc học (nhìn trước)

```text
app/
  Http/Controllers/HealthController.php   ← API mẫu đầu tiên
  Http/Resources/ApiResponse.php          ← format JSON chuẩn
  Models/User.php                         ← user tối giản
routes/api.php                            ← đăng ký route API
Modules/Core/                             ← nền tảng (đọc dần)
database/migrations/                      ← schema version
```

## Học tiếp ở đâu?

1. Lộ trình tổng: `../../Learning/README.md`
2. Bài thực hành trên source này: `../../Learning/lessons/01-chay-petcare-be.md`
3. Business (sau khi stack chạy): `../../Docs/content/business/`

## Thêm tính năng sau này (ý tưởng)

Khi đã quen health endpoint, thêm dần theo Docs:

1. Identity — register/login  
2. Catalog — products  
3. Cart → Order → Inventory lock  

Mỗi feature = migration (nếu cần) + route + controller + service.
