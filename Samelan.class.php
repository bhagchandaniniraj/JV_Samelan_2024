<?php
    include 'db_connect.class.php';
    class Samelan{
        public function __construct(){
            echo "<br/>Hello I am Samelan";
        }
        public function check($mobile){
            echo "<br/>Mobile Validation is complete";
            $mydb = new DBConnect();
            //$mydb->tableExists("registration_details");
        }
    }