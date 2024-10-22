<?php
    class DBConnect  
    {  
        private $host = "localhost"; // your host name  
        private $username = "root"; // your user name  
        private $password = ""; // your password  
        private $db = "samelan_db"; // your database name  
        private $conn = false;
        private $mysqli = "";
        private $result = array();

        public function __construct()
        {
            if(!$this->conn){
                $this->mysqli = new mysqli($this->host,$this->username,$this->password,$this->db);
                if($this->mysqli->connect_error){
                    array_push($this->result, $this->mysqli->connect_error);
                    return false;
                }else{
                    return true;
                }
            }
        }
        public function sql($sql){
            $query = $this->mysqli->query($sql);
            if($query){
                $this->result = $query->fetch_all(MYSQLI_ASSOC);
                return true;
            }else{
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        }
        public function getResult(){
            $val = $this->result;
            $this->result = array();
            return $val;
        }
        public function __destruct(){
            if($this->conn){
                if($this->mysqli_close()){
                    $this->conn = false;
                    return true;
                }else{ 
                    return false;
                }
            }
        }
        public function tableExists($table){
            $sql = "SHOW TABLES FROM $this->db LIKE '$table' ";
            $tableInDB = $this->mysqli->query($sql);
            if($tableInDB){
                if($tableInDB->num_rows == 1){
                    return true;
                }else{
                    array_push($this->result, $table." does not exists in the database");
                    return false;
                }
            }
        }
        public function select($table, $rows = "*", $join = null, $where=null,$order=null, $limit = null){
            if($this->tableExists($table)){
                $sql = "SELECT $rows FROM $table ";
                if($join != null){
                    $sql .= " JOIN $join";
                }
                if($where != null){
                    $sql .= " WHERE $where";
                }
                if($order != null){
                    $sql .= " ORDER BY $order";
                }
                if($limit != null){
                    $sql .= " LIMIT 0,$limit";
                }
                return  "$sql";
            }else{
                return false;
            }
        }
        public function printTable($data){

            echo "<table class='table table-striped'>";
            echo "<thead><tr>";
            echo "<th class = 'col'> Registration No. </td>";
            echo "<th class = 'col'> Total Group members </td>";
            echo "<th class = 'col'> Group ID </td>";
            echo "<th class = 'col'> Email id </td>";
            echo "<th class = 'col'> Name of Participant </td>";
            echo "<th class = 'col'> Gender </td>";
            echo "<th class = 'col'> Mobile Number </td>";
            echo "<th class = 'col'> Age </td>";
            echo "<th class = 'col'> State </td>";
            echo "<th class = 'col'> City </td>";
            echo "<th class = 'col'> Mode of travel </td>";
            echo "<th class = 'col'> Travel Details </td>";
            echo "<th class = 'col'> Arrival Date </td>";
            echo "<th class = 'col'> Arrival Time </td>";
            echo "<th class = 'col'> Departure Date </td>";
            echo "<th class = 'col'> Departure Time </td>";
            echo "<th class = 'col'> Food Packets during Departure	  </td>";
            echo "<th class = 'col'> Accommodation </td>";
            echo "<th class = 'col'> Special Requirements </td>";
            echo "<th class = 'col'> Emergency Contact Details </td>";
            echo "</tr></thead>";
            foreach($data as $participant){
                echo "<tr>";
                foreach($participant as $p_detail){
                    echo "<td> $p_detail </td>";
                }
                echo "</tr>";
            }
            echo "</table>";

        }
    }      