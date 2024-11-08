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
        $gid = $res[0]['group_id'];
        if(count($res) >0 ){
            $sql = "select case when attd is null then 'false' else 'true' end as flag from registration_details where group_id = '$gid'";
            $this->mydb->sql($sql);
            $result = $this->mydb->getResult();
            $flag =  $result[0]['flag'];
            if(strcmp($flag, "false")){
                $table = 'registration_details as r';
                $sql = $this->mydb->selectSp($table, "r.*, i.username, i.password" ,' internet_ids i on r.reg = i.reg ', " group_id = '$gid'");
                $this->mydb->sql($sql);
                $res = $this->mydb->getResult();
                $this->mydb->printSp($res);
            }else{
                $table = 'registration_details';
                $sql = $this->mydb->select($table, "*" ,null, " group_id = '$gid'");
                $this->mydb->sql($sql);
                $res = $this->mydb->getResult();
                $this->mydb->printTable($res);
            }
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
        $params = ['reg', 'group_id', 'participant', 'gender', 'age' , 'acc_venue', 'attd'];
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