## SPA-CRM

A simple SPA-CRM to get skilled in Inertia and Vuejs using Laravel.

## Software Requirements

This project was built with Laravel 9.26.1, PHP 8.1.1 and mysql Ver 15.1 Distrib 10.4.22-MariaDB

## What's implemented?

* [x] User Auth with Breeze: log in only as administrator.
* [x] Use database seeds to create first user with email admin@admin.com and password "password".
* [x] CRUD functionality (Create / Read / Update / Delete) for menu items: Companies and Employees.
* [x] Companies DB table with these fields: Name (required), email, logo (minimum 100×100), website.
* [x] Employees DB table with these fields: First Name (required), last name (required), Company (foreign key to Companies), email, phone.
* [x] Use database migrations to create those schemas above.
* [ ] Store companies logos in storage/app/public/images folder and make them accessible from public.
* [x] Route:groups and Route::resorces implementation.
* [x] Use Laravel's validation function, using Request classes.
* [x] Use Laravel's pagination for showing Companies/Employees list, 10 entries per page.
* [x] I have used [Breeze](https://laravel.com/docs/9.x/starter-kits#laravel-breeze) & Vuejs.
* [x] Preserve States on the search box.
* [ ] PHPUnit Tests with a Trait to keep the Code [DRY](https://en.wikipedia.org/wiki/Don%27t_repeat_yourself).

## How to Install

1. Clone the repo : `git clone https://github.com/maqndon/spa-crm.git`
2. `$ cd spa-crm`
3. `$ composer install`
4. `$ cp .env.example .env`
5. `$ php artisan key:generate`
6. `mysql -u USER -p`
7. Set database credentials on the `.env` file
8. `$ php artisan migrate --seed`
9. `$ php artisan storage:link`
10. `$ php artisan serve`
11. Login with :
    - email : `admin@admin.com`
    - password : `password`

## License

This software is open-sourced software licensed under the [MIT license](LICENSE).
