# Laravel Framework 10  Starter Site

## Starter Site based on on Laravel 10 and Boostrap 5
* [Features](#feature1)
* [Requirements](#feature2)
* [How to install](#feature3)
* [Application Structure](#feature4)
* [Troubleshooting](#feature5)

<a name="feature1"></a>
## Starter Site Features:
* Laravel 10
* Bootstrap 5.x
* Back-end
	* User management.
    * DataTables dynamic table sorting and filtering.
    * Roles Permission With Spatie.

-----
<a name="feature2"></a>
## Requirements

	PHP >= 8.0.0^
	OpenSSL PHP Extension
	Mbstring PHP Extension
	Tokenizer PHP Extension
	SQL server(for example MySQL)
	Composer
	Node JS

-----
<a name="feature3"></a>
## How to install:
* [Step 1: Get the code](#step1)
* [Step 2: Use Composer to install dependencies](#step2)
* [Step 3: Create database](#step3)
* [Step 4: Install](#step4)
* [Step 5: Start Page](#step5)

-----
<a name="step1"></a>
### Step 1: Get the code - Download the repository

    https://github.com/yh2bae/starter-laravel.git

Extract it in www(or htdocs if you using XAMPP) folder and put it for example in starter-laravel folder.

-----
<a name="step2"></a>
### Step 2: Use Composer to install dependencies

Laravel utilizes [Composer](http://getcomposer.org/) to manage its dependencies. First, download a copy of the composer.phar.
Once you have the PHAR archive, you can either keep it in your local project directory or move to
usr/local/bin to use it globally on your system.
On Windows, you can use the Composer [Windows installer](https://getcomposer.org/Composer-Setup.exe).

Then run:

    composer install

-----
<a name="step3"></a>
### Step 3: Create database

If you finished first three steps, now you can create database on your database server(MySQL). You must create database
with utf-8 collation(uft8_general_ci), to install and application work perfectly.
After that, copy .env.example and rename it as .env and put connection and change default database connection name, only database connection, put name database, database username and password.

-----
<a name="step4"></a>
### Step 4: Install

Now that you have the environment configured, you need to create a database configuration for it. For create database tables use this command:

    php artisan migrate

And to initial populate database use this:

    php artisan db:seed

-----
<a name="step5"></a>
### Step 5: Start Page

You can now login to super admin, admina and user part of Laravel Framework 10  Bootstrap 5 Starter Site:

    username: superadmin@app.com
    password: rahasia!

OR admin

    username: admin@app.com
    password: rahasia!
OR user

    username: user@app.com
    password: rahasia!

-----
<a name="feature5"></a>
## Troubleshooting

### RuntimeException : No supported encrypter found. The cipher and / or key length are invalid.

    php artisan key:generate

### Site loading very slow

	composer dump-autoload --optimize
OR

    php artisan dump-autoload
