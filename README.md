## Backend Example for [react-crud-generator](https://github.com/starmoozie/react-crud-generator)

Build by Laravel

### Install

-   `cp .env.example .env`
-   Sesuaikan koneksi database. Silahkan merujuk ke dokumentasi [Laravel](https://laravel.com/docs/10.x/database)
-   `php artisan migrate --seed`
-   `php artisan passport:install`. Jika mengaktifkan authentikasi dengan passport. Bisa uncomment middleware pada `route/api`
