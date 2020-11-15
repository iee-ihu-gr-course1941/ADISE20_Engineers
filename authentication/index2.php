<?php
session_start();
?>

<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8">
    
    <link rel="stylesheet" href="style1.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
  
    <div class="wrapper">
      
      <?php
      /*if(isset($_SESSION["useruid"])){
        echo "<li><a href='profile.php'>Profile page</a></li>";
        echo "<li><a href='logout.php'>Log out</a></li>";
      }else{
        //echo "<li><a href='signup.php'>Sign up</a></li>";
        //echo"<li><a href='login.php'>Log in</a></li>";
      }*/
      ?>
      <div class="title-text">
        <div class="title login">
Login Form</div>
<div class="title signup">
Sign up Form</div>
</div>
<div class="form-container">
        <div class="slide-controls">
          <input type="radio" name="slide" id="login" checked>
          <input type="radio" name="slide" id="signup">
          <label for="login" class="slide login">Login</label>
          <label for="signup" class="slide signup">Sign up</label>
          <div class="slider-tab">
</div>
</div>
<div class="form-inner">
  
          <form action="login.php" class="login" method="POST">
            <div class="field">
               
              <input type="text" placeholder="Username" name="username" required>
            </div>
<div class="field">
    
              <input type="password" placeholder="Password" name="password" required>
            </div>
<div class="pass-link">
<a href="#">Forgot password?</a></div>
<div class="field btn">
              <div class="btn-layer">
</div>
<input type="submit" name="submit2" value="Login">
            </div>
<div class="signup-link">
Not a member? <a href="">Sign up</a></div>
<?php
if(isset($_GET["error"])){
  if($_GET["error"]=="emptyinput"){
    echo "<p>Fill in all fields</p>";
  }else if($_GET["error"]=="wronglogin"){
    echo "<p >Incorrect Data</p>";
  
}}
?>
</form>

<form  action="signup.php" class="signup" method="POST">
    <div class="field">
        <input type="text" placeholder="Username" name="username" required>
      </div>            
<div class="field">
              <input type="text" placeholder="Email Address" name="email" required>
            </div>
<div class="field">
              <input type="password" placeholder="Password" name="password" required>
            </div>
<div class="field">
              <input type="password" placeholder="Confirm password" name="passwordConfirm" required>
            </div>
<div class="field btn">
              <div class="btn-layer">
</div>
<input type="submit" name="submit" value="Sign up">
            </div>
</form>
</div>
<?php
if(isset($_GET["error"])){
  if($_GET["error"]=="emptyinput"){
    echo "<p>Fill in all fields</p>";
  }else if($_GET["error"]=="invaliduid"){
    echo "<p>Choose a proper username</p>";
  }else if($_GET["error"]=="invalidemail"){
    echo "<p>Choose a proper email</p>";
  }else if($_GET["error"]=="passwordsdontmatch"){
    echo "<p>Passwords do not match</p>";
  }else if($_GET["error"]=="stmtfailed"){
    echo "<p>Something went wrong</p>";
  }else if($_GET["error"]=="usernametaken"){
    echo "<p>Choose another username</p>";
  }else if($_GET["error"]=="none"){
    echo "<p>You have sign up!</p>";
  }
}
?>
</div>
</div>

<script>
      const loginText = document.querySelector(".title-text .login");
      const loginForm = document.querySelector("form.login");
      const loginBtn = document.querySelector("label.login");
      const signupBtn = document.querySelector("label.signup");
      const signupLink = document.querySelector("form .signup-link a");
      signupBtn.onclick = (()=>{
        loginForm.style.marginLeft = "-50%";
        loginText.style.marginLeft = "-50%";
      });
      loginBtn.onclick = (()=>{
        loginForm.style.marginLeft = "0%";
        loginText.style.marginLeft = "0%";
      });
      signupLink.onclick = (()=>{
        signupBtn.click();
        return false;
      });
    </script>

  </body>
</html>
