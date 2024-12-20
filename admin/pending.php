<?php
   // include 'Samelan.class.php';
   session_start();
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <table>
            <tr>
                <td rowspan="2"><img style="max-width: 115px; max-height: 115px" src="../logo/au.png" alt="Atmiya University"></td>
                <td><h1>Atmiya University</h1></td>
            </tr>
            <tr>
                <td><h2>26th National Jeevan Vidhya Samelan</h2></td>
            </tr>
        </table>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    
    <?php 
        include '../db_connect.class.php';
        include_once 'navbar.php';
        $mydb = new DBConnect();
        $string = "<h4> Registration Pending List... </h4>";
        $mydb->list('registration_details',"*", null,"registered is NULL", $string,"Pending");
        
    ?>

    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</body>
</html>