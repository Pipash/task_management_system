# Task Management System
Preview of tasks and api To manage the task.<br>
<b>Installation</b><br>
Clone the App and do `composer install` <br>
Create a database for example `task_management_system`<br>
Create .env file, copy and paste content of .env.example to .env and configure env file with your database configuration<br>
Then do `php artisan migrate`<br>
Call `/api/task` uri to crete tasks<br>
There is a `view/global.js` file inside project directory which contains usersEndpoint link. Configure the link as your server configuration<br>
Run `/view` link like `http://localhost/task_management_system/view/` to see list of users and associated tasks
