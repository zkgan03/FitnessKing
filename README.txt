# Integrative Programming

## Installation / Initialization

### version 
-----------
- xampp == 8.2.12 / PHP 8.2.12
- Node == 20.15.1 (LTS)
- Composer == 2.7.7
- Laravel == 11.15.0 (PHP 8.2.12) (This normally build with Composer)



### After pull project
-----------------------

1. In project directory (`./core`), run `composer install` in terminal
	- create and init php package (defined in `composer.json`)

2. Run `npm install` in terminal
	- create and init frontend package (defined in package.json)
	- such as tailwind, vite server

3. copy `.env.example` file and rename as `.env`

4. Set the database connection in `.env` to mysql, as below :
```
	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1		
	DB_PORT=3306			# use the port that host the mysql db
	DB_DATABASE=fitness_db 	# change to ur database name, must same in xampp mysql
	DB_USERNAME=root		# ur mysql username	(default is root)
	DB_PASSWORD=			# ur mysql password (default is empty)
```
5. Run `php artisan key:generate` in terminal
	- to generate app key in .env file
	
6. open xampp and run apache and mysql

7. Open mysql server. create the database, name same as the DB_DATABASE in `.env` 

8. Run `php artisan migrate` in terminal
	- to create db tables 



### Start running the server for development
--------------------------------------------
9. Run `php artisan serve` and `npm run dev` in terminal 
	- to start run laravel server
	- to start run vite server 



### PayPal sandbox user credential 
--------------------------------------------

Email : sb-e564c31712441@personal.example.com
Password :  payment123



### Web Services / API
--------------------------------------------------

- All the relevant README files will be inside each of the Project Folder

