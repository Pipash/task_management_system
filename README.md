# Task Management System
Preview of tasks and api To manage the task.<br>
<b>Installation</b><br>
Clone the App, go to project directory and run `composer install` in your terminal<br>
Create a database for example `task_management_system`<br>
Create .env file, copy and paste content of .env.example to .env and configure env file with your database configuration<br>
Then do `php artisan migrate`<br>
Run `php artisan serve` and got to link http://127.0.0.1:8000/ <br>
Call `http://127.0.0.1:8000/api/task` api url to crete tasks with required data<br>
Call `http://127.0.0.1:8000/api/task/{task_id}` api url to update tasks with required data<br>

<b>Testing</b><br>
For unit testing first go to project directory and run `php artisan migrate`<br>
Then replace value of line `<server name="DB_DATABASE" value="task_management2"/>` to your database name in phpunit.xml file<br>
Then run `./vendor/bin/phpunit`
