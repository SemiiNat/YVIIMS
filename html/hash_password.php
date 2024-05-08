<?php
$password = "admin1234";  // Replace this with the password you want to use
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;
?>
