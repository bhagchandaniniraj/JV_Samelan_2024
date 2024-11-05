<?php
include 'db_connect.class.php';

class Samelan{
    private $mydb;
    public function __construct(){
        $this->mydb = new DBConnect();
    }

    public function check($uid){
        $this->mydb->tableExists("registration_details");
        $sql = $this->mydb->select("registration_details", 'group_id' ,null, " uid = '$uid'");
        $this->mydb->sql($sql);
        $res = $this->mydb->getResult();
        if(count($res) >0 ){
            $gid = $res[0]['group_id'];
            $sql = $this->mydb->select("registration_details", "*" ,null, " group_id = '$gid'");
            $this->mydb->sql($sql);
            $res = $this->mydb->getResult();
            $this->mydb->printTable($res);
        }else{
            echo "No Data Found!!!";
            echo <<<GOBACK
                <a href="/" class="btn btn-primary">Go Back </a>
            GOBACK;
        }
    }
    public function update($table, $params = array(), $keys){
        $sql = $this->mydb->updatePresent($table, $params, $keys);
        //echo "<br/>The number of registered marked Present is/are:  ";
        //echo $this->mydb->getResult()[0];
        $this->mydb->updateAbsent('registration_details',['registered' => 'NOW()'], $keys);
        //echo "<br/>The number of registered marked Absent is/are:  ";
        //echo $this->mydb->getResult()[0];
        $params = ['reg', 'group_id', 'participant', 'gender', 'age', 'reg_table' , 'acc_venue', 'attd'];
        $str = implode(", ", $params);
        echo "<br/>";
        $gid = $this->mydb->fetchGID($keys[0]);
        $sql = $this->mydb->select('registration_details',$str, null, "group_id = '$gid[group_id]'");
        $this->mydb->sql($sql);
        $data = $this->mydb->getResult();
        $this->mydb->printAcc($data);
        echo <<<CLCRET
            <p></p>
            <a href="/" class="btn btn-primary"> Click here to Return</a>
        CLCRET;
    }
}