<?php

if(isset($_POST["submit2"])){
    $username=$_POST["username"];
    $password=$_POST["password"];
    require_once 'dbconnect.php';
    require_once 'functions.php';

    if(emptyInputLogin($username, $password)!==false){
        header("location: http://localhost/authentication/index2.php?error=emptyinput");
        exit();
    }
    loginUser($conn, $username, $password);
}
else{
    header("location: http://localhost/authentication/index2.php");
    exit();
}