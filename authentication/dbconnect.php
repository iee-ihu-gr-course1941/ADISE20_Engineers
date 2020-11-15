<?php
 $serverName="localhost";
 $dbUsername="root";
 $dbPassword="";
 $dbname="score4";

 $conn=mysqli_connect($serverName, $dbUsername, $dbPassword, $dbname);
 if(!$conn){
 die("Connection failed: ".mysqli_connect_error());
 }