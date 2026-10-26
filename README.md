# API ban nong san sach

Du an Laravel 10, PHP 8.1 va MySQL; API CRUD san pham su dung Eloquent SoftDeletes.

## Cai dat

```powershell
composer install
if (!(Test-Path .env)) { Copy-Item .env.example .env }
php artisan key:generate
```

Cap nhat cac bien `DB_*` trong `.env` theo MySQL cua ban, tao database, sau do chay:

```powershell
php artisan migrate
php artisan serve
```

Chay test:

```powershell
php artisan test
```

## API san pham

Tat ca endpoint bat dau bang `/api/products`. Gui va nhan JSON.

| Method | Endpoint | Mo ta |
| --- | --- | --- |
| GET | `/api/products?page=1&limit=20` | Danh sach co phan trang |
| POST | `/api/products` | Tao san pham |
| GET | `/api/products/{id}` | Xem chi tiet |
| PUT/PATCH | `/api/products/{id}` | Cap nhat san pham |
| DELETE | `/api/products/{id}` | Xoa mem san pham |

Du lieu tao/cap nhat gom `name`, `price`, `unit`, `stock`. `price` phai lon hon 0; `stock` phai la so nguyen khong am. `limit` mac dinh 20 va toi da 100. Xoa mem ghi thoi diem vao `deleted_at`; san pham da xoa khong xuat hien trong API.

> Laravel 10 duoc chon de tuong thich PHP 8.1. Composer security-advisory blocking dang duoc tat trong `composer.json` de cho phep cai dependency theo lua chon cua nguoi dung. Nen nang PHP va Laravel, sau do bat lai security blocking truoc khi dua len production.
