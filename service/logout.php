<?php 

session_start();
session_destroy();

unset($_SESSION['id_user']);
unset($_SESSION['user_id']);
unset($_SESSION['errorMessage']);
unset($_SESSION['successMessage']);
header('Location: ../index.php');