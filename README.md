<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://www.monotech.group/_nuxt/img/logo.d66fd6e.svg" width="200"></a></p>


## About this project

This project developed for Monotech Backend Developing Challenge. [Click here](https://www.monotech.group/)  to more about MonoTech

## How to run project

### Dependencies
- PHP 8.0.9
- PostgreSql
- Postman (To make requests)

### Installation

- Firstly you should create a database manually with named "MonoTech" on PostgreSql
- Edit required fields on [.env](/.env) depending on your database information
- Run database migrations
````
php artisan migrate
````
- Run database seeders
````
php artisan db:seed user_seeder
php artisan db:seed wallet_seeder
php artisan db:seed promotion_code_seeder
````
- Run project
````
php artisan serve
````

You are ready to make request.

**Note this project work with JWT Token. You might need to update secret keys. Also do not forget to add Bearer token in your request for some URL's.**

````
php artisan jwt:secret
````

Additionally project Postman Collection already shared in main directory as named [MonoTech Collection.postman_collection](/MonoTech%20Collection.postman_collection.json)

## License

This project is open-sourced software licensed under the [GNU General Public License](https://www.gnu.org/licenses/gpl-3.0.en.html)
