<?php
$password = "0987654321dakKFDS";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password; 
