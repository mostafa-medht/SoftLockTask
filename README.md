# SoftLockTask
Soft Lock Task 

# Here's what I have done

- Application has simple authentication system with middlware 
- User can browse to any file and upload it 
- User can click on load button on main window to get file name, file size in kB and extension 
- I provide 256 key base key for each user when register for first time 
- Case of 256 key base key for each user to provide level of security if any security issues happens, Only one user affected by attack not all of content when using public key 
- There is a Function to take plain text and encrypt it to get chirper based on (AES-256-CBC) with user logged key 
- There is a function to take chirper version and decrpyt to plain text based on  (AES-256-CBC) with user logged key
- User can download file (Encrypted or Decrypted) after process process 

# How To Use As User
- First, Login (or register as new user if you don't have account).
- After Login you will be redirected to home page. 
- Second Browse to your computer to select your file to process. 
- Click On load button to get info about file you get (File name, File Size, File Extension).
- Third, Choose what you whant to do (encrpt or decrypt) then click convert button.
- Fourth, Click Download button to get the result file after converting process.

## Run the project
1. Clone repository

    ```
        1.1- git clone https://github.com/mostafa-medht/SoftLockTask.git
        1.2- cd project-directory 
        1.3- composer install
        1.4- npm install
        1.5- cp .env.example .env
        1.6- php artisan key:generate
    ```
2. Database 
    2.1 Create database in DBMS via this query
    ``` sql
        create database `softlocktask`;
    ```
    2.2 Database Configuration in .env file in application root
    ```     
        DB_DATABASE=softlocktask
        DB_USERNAME=
        DB_PASSWORD=
        Put your database user after DB_USERNAME, and your user password after DB_PASSWORD
    ```
    2.3 Migrate & seed
    ``` 
        php artisan migrate
        php artisan db:seed
        
        or
        
        php artisan migrate --seed
    ```
    2.4 Run the project
    ```
        php artisan serve
    ```
---
## Technologies and Packages Used For This App 
* Technoligies 
    - BootStrap 
    - JavaScript
    - Jquery 
    - PHP 
    - Laravel
* Packages and Libiraries
    - Admin LTE (https://github.com/InfyOmLabs/laravel-ui-adminlte)
    - Open SSL Library Of PHP (https://www.php.net/manual/en/function.openssl-encrypt.php)
    - Open SSL Library Of PHP (https://www.php.net/manual/en/function.openssl-decrypt.php)
---
## Contributing
* [Mostafa Medhat] (https://github.com/mostafa-medht)

When contributing to this repository, please first discuss the change you wish to make via issue.
---
## Contributing Guidelines

1. **Create** a new issue discussing what changes you are going to make.
2. **Fork** the repository to your own Github account.
3. **Clone** the project to your own machine.
4. **Create** a branch locally with a succinct but descriptive name.
5. **Commit** Changes to the branch.
6. **Push** changes to your fork.
7. **Open** a Pull Request in 
---
## License

  project Copyright © 2021 Mostafa Medht. It is a open source software and redistributed under under the [MTI license].

