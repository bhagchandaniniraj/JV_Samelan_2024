<?php
    include 'Samelan.class.php';
    print_r($_POST);
    if($_POST){
        if(isset($_POST['mobile'])){
            $myobj = new Samelan();
            $myobj->check($_POST['mobile']);
        }
    }