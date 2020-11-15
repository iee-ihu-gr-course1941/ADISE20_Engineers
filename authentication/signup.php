<?php
if(isset($_POST["submit"])){
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $passwordConfirm=$_POST['passwordConfirm'];

    require_once 'dbconnect.php';
    require_once 'functions.php';

    if(emptyInputSignup($username, $email,$password, $passwordConfirm)!==false){
        header("location: http://localhost/authentication/index2.php?error=emptyinput");
        exit();
    }
    if(invalidUid($username)!==false){
        header("location: http://localhost/authentication/index2.php?error=invaliduid");
        exit();
    }
    if(invalidEmail($email)!==false){
        header("location: http://localhost/authentication/index2.php?error=invalidemail");
        exit();
    }
    if(pwdMatch($password, $passwordConfirm)!==false){
        header("location: http://localhost/authentication/index2.php?error=passwordsdontmatch");
        exit();
    }
    if(uidExists($conn, $username, $email)!==false){
        header("location: http://localhost/authentication/index2.php?error=usernametaken");
        exit();
    }

    createUser($conn, $username, $email, $password);
}else{
    header("location:  http://localhost/authentication/index2.php");
    exit();
}