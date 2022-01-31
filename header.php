<?php
  include('helpers.php');
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<?php 
  if(isset($_SESSION['user'])){
    include('navbar.php');
  }
?>

<div class="container-fluid" dir="rtl">



<?php

  if(isset($_SESSION['error'])){
    echo "
      <div class='row'>
        <div class='col-12 alert alert-danger text-center'>
          ".$_SESSION['error']."
        </div>
      </div>
    ";

    unset($_SESSION['error']);
  }


  if(isset($_SESSION['success'])){
    echo "
      <div class='row'>
        <div class='col-12 alert alert-success text-center'>
          ".$_SESSION['success']."
        </div>
      </div>
    ";

    unset($_SESSION['success']);
  }

?>