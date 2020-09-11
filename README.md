# Book API Rest
A RESTful web service, two resources: Books and Authors

* CRUD operations of the books and authors API
* REST API (Routes, controllers, Eloquent, Relationships)
* Database migrations and Database seeders
* Input validation
* Proper 404 pages
* API versioning **Dingo**
* Rate limits/Throttling
* Transformers/Serializers and Meta information **Fractal**
* Test Driven Development **PHPUnit** **Mockery**

**Requirements**
- [x] PHP 7.4
- [x] MySQL
- [x] Composer

 ### Setup
 Install the dependencies
```
 composer install
```

Create a book and book_testing databases

Change the Database configuration
```
cp .env.examples .env
```
Run the migration and seeders
```
php artisan migrate && php artisan db:seed
```
Run the application
```
php -S localhost:8000 -t public
```
