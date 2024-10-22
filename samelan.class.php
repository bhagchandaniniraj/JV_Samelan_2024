<?php
include 'db_connect.class.php';
class Samelan{
    private $mydb;
    public function __construct(){
        $this->mydb = new DBConnect();
    }

    public function check($mobile){
        
        $this->mydb->tableExists("registration_details");
        $sql = $this->mydb->select("registration_details", 'group_id' ,null, " MOBILE = '$mobile' ");
        $this->mydb->sql($sql);
        $res = $this->mydb->getResult();
        $gid = $res[0]['group_id'];
        $sql = $this->mydb->select("registration_details", "*" ,null, " group_id = '$gid'");
        $this->mydb->sql($sql);
        $res = $this->mydb->getResult();
        $this->mydb->printTable($res);
    }
}