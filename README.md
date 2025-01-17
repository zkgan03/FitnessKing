# Fitness King 

![image](https://github.com/user-attachments/assets/5c599953-387e-4fda-8304-639d2c5b2623)

## Installation / Initialization

### Version 
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

8.  Run `php artisan storage:link`
	- to create the link from public folder to the storage public folder

10.  Run `php artisan migrate --seed` in terminal
	- to create db tables
	- to create dummy data


### Start running the server for development
--------------------------------------------
9. Run `php artisan serve` and `npm run dev` in terminal 
	- to start run laravel server
	- to start run vite server 


### PayPal sandbox
--------------------------------------------
- You may need to Register an Account in [Paypal Sandbox](https://www.sandbox.paypal.com/us/home)
- Update the relevant Paypal API keys in `.env`	files


### Web Services / API
--------------------------------------------------
- All the relevant README files will be inside each of the Project Folder

> NOTE : You need to install `CUDA` to run the chatbot


> Q : **Why Use Different Project?** </br>
> 
> A : The **Fake Payment Gateway** and **Chatbot** were developed as separate projects in ASP.NET Web API to demonstrate the use of **RESTful APIs** and the creation of **Web Services**, simulating real-world scenarios where systems often rely on external services.



## User Interface Overview

Below is a table showcasing the key user interfaces for the Gym Enrollment System, including the **Class Packages Enrollment Module**, **Payment Module**, and **Chatbot**.

| **Page/Component**         | **Description**                                                                 | **Screenshot**                          |
|----------------------------|---------------------------------------------------------------------------------|-----------------------------------------|
| **Class Packages Listing** | Lists available class packages (built using **XML, XSL, and XPath**).          | ![image](https://github.com/user-attachments/assets/822a415e-6935-4a94-a799-18a5c4ebcf04) ![image](https://github.com/user-attachments/assets/da7e765a-5e50-49b7-90e3-cfc9e90f8db3) |
| **Class Package Details**  | Classes Summary of a class package with **Enroll** / **Unenroll** (after enrollment).   | ![image](https://github.com/user-attachments/assets/a7ff9785-6867-4bb3-9a93-c25c25ced75a) ![image](https://github.com/user-attachments/assets/0e298694-ab9d-4a3e-96b4-d397ad9f7409) |
| **Schedule Page**          | Displays class schedules in **Week View** and **Month View**.                  |![image](https://github.com/user-attachments/assets/1f45d569-023e-461c-86ed-aa50f6e6a1de) ![image](https://github.com/user-attachments/assets/e93d8144-3b28-4b0b-a477-1d8a3b56be7b)  |
| **Class Detail Dialog**    | Pop-up dialog showing details of a specific class (time, classroom, etc.).    | ![image](https://github.com/user-attachments/assets/ca4842f7-e9ef-4408-a890-4e4f390063d6) |
| **Payment Page**           | Asks for payment method (PayPal or Fake Card Payment Gateway).                 | ![image](https://github.com/user-attachments/assets/92ef35d4-7d85-4188-bac4-4cff5fc398ca) |
| **PayPal Payment Page**    | Integration with PayPal for secure payments.                                   | ![image](https://github.com/user-attachments/assets/43d3f830-b706-45f6-af75-2ebc5231f726) |
| **Fake Card Payment Page** | Integration with a fake card payment gateway (built with ASP.NET WEB API) for development purposes (to create a web services). | ![image](https://github.com/user-attachments/assets/c3e8993a-d158-4d4a-a6fa-a403bf71ebbc)  |
| **Chatbot**                | AI-powered chatbot. Integrated into ASP.NET Web API project using LlamaSharp and [llama2-7B-chat](https://huggingface.co/TheBloke/Llama-2-7B-Chat-GGUF/tree/main).  | ![image](https://github.com/user-attachments/assets/2e0e1a1a-329f-4b6d-ad38-11b107d8c968)  |




