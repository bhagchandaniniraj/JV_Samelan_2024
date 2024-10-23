<?php
include 'db_connect.class.php';
include 'goback.trait.php';

class Samelan{
    use GoBack;
    private $mydb;

    public function __construct(){
        $this->mydb = new DBConnect();
    }

    public function check($mobile){
        $this->mydb->tableExists("registration_details");
        $sql = $this->mydb->select("registration_details", 'group_id' ,null, " MOBILE = '$mobile'");
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
                <a href="/samelan" class="btn btn-primary">Go Back </a>
            GOBACK;
        }
    }
    public function update($table, $params = array(), $keys){
        $sql = $this->mydb->updatePresent($table, $params, $keys);
        echo "<br/>The number of registered marked Present is/are:  ";
        echo $this->mydb->getResult()[0];
        $this->mydb->updateAbsent('registration_details',['registered' => 'NOW()'], $keys);
        echo "<br/>The number of registered marked Absent is/are:  ";
        echo $this->mydb->getResult()[0];
        echo <<<CLCRET
            <p></p>
            <a href="/samelan" class="btn btn-primary"> Click here to Return</a>
        CLCRET;
        //print_r($this->mydb->getResult());
    }
    // public function updateAbsent($table, $params = array(), $keys){
    //     $this->mydb->updateAbsent('registration_details',['registered' => 'NOW()'], $keys);
    // }
}