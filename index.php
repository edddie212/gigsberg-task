<?php

//Include DB file
include './data/db.php';

// Check for the 'page' query string parameter
$page = isset($_GET['page']) ? $_GET['page'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GigBerg Task - User Registration</title>
    <link rel="stylesheet" href="main.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<header>
    <div class="logo">
        <a href="/">
            GigsBerg Task
        </a>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php?page=users">Users</a></li>
        </ul>
    </nav>
</header>



    <div class="content">
        <?php
        switch ($page) {
            case 'users':?>
                <table id="usersTable">
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                    <!-- Rows will be added here by AJAX -->
                </table>
                <?php
                break;

            case 'home':
            default:
                ?>
                    <div class="form-container">
                        <h2>Fill in the form below</h2>
                        <form id="userForm">
                            <input type="hidden" id="action" name="create" value="create">
                            <input type="text" id="username" name="username" placeholder="Username">
                            <input type="email" id="email" name="email" placeholder="Email">
                            <input type="password" id="password" name="password" placeholder="Password">
                            <input type="date" id="birthdate" name="birthdate">
                            <input type="text" id="phone" name="phone" placeholder="Phone Number">
                            <input type="text" id="url" name="url" placeholder="URL">
                            <input type="submit" value="Submit">
                        </form>
                    </div>

                <?php
                break;
        }
        ?>
    </div>

<!-- The Modal -->
<div id="userModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
            <h2>User Details</h2>
        </div>
        <div class="modal-body">
            <p class="user-detail"><strong>Username:</strong> <span id="modal-username"></span></p>
            <p class="user-detail"><strong>Email:</strong> <span id="modal-email"></span></p>
            <p class="user-detail"><strong>Birthdate:</strong> <span id="modal-birthdate"></span></p>
            <p class="user-detail"><strong>Phone:</strong> <span id="modal-phone"></span></p>
            <p class="user-detail"><strong>URL:</strong> <span id="modal-url"></span></p>
        </div>
    </div>
</div>



<script src="main.js"></script>
</body>
</html>
