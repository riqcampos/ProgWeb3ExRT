<?php

$user = "adminprogweb";
$pass = "ProgWeb3";
$db = "progweb3";
$conn = mysqli_connect("localhost", $user, $pass, $db);
if ($conn->connect_errno){
    die("Erro de conexão" . $conn->connect_error);
} 

?>