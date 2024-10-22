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
            //print_r(array_keys($data[0]));
            $i = 1;
            $allowed = array ('reg', 'participant', 'gender', 'age', 'reg_table' , 'acc_venue' );
            $skipped = array ( 'tot_mem','group_id','email','state','city','travel_mode','travel_number','arrive_date','arrive_time','dep_date','dep_time','food_packets','acc_status','special_req','emergency_contact' );
            $final_array = array_intersect_key($data[0], array_flip($allowed));
            //print_r($final_array);
            echo "<table class='table table-striped'>";
            echo "<thead><tr>";
            echo "<th class = 'col'> S No. </th>";
            echo "<th class = 'col'> Registration No. </th>";
            //echo "<th class = 'col'> Total Group members </th>";
            //echo "<th class = 'col'> Group ID </th>";
            //echo "<th class = 'col'> Email id </th>";
            echo "<th class = 'col'> Name of Participant </th>";
            echo "<th class = 'col'> Gender </th>";
            //echo "<th class = 'col'> Mobile Number </th>";
            echo "<th class = 'col'> Age </th>";
            //echo "<th class = 'col'> State </th>";
            //echo "<th class = 'col'> City </th>";
            //echo "<th class = 'col'> Mode of travel </th>";
           // echo "<th class = 'col'> Travel Details </th>";
            //echo "<th class = 'col'> Arrival Date </th>";
            //echo "<th class = 'col'> Arrival Time </th>";
           // echo "<th class = 'col'> Departure Date </th>";
            //echo "<th class = 'col'> Departure Time </th>";
            //echo "<th class = 'col'> Food Packets during Departure	  </th>";
           // echo "<th class = 'col'> Accommodation </th>";
           // echo "<th class = 'col'> Special Requirements </th>";
           // echo "<th class = 'col'> Emergency Contact Details </th>";
            echo "<th class = 'col'> Registration Table </th>";
            echo "<th class = 'col'> Accomodation Venue </th>";
            echo "</tr></thead>";
            
            foreach($data as $participant){
                $final_array = array_intersect_key($participant, array_flip($allowed));
                echo "<tr><td>".$i++." </td>";
                foreach($final_array as $key => $value){
                    echo "<td> $value </td>";
                }
                echo "</tr>";
            }
            echo "</table>";

        }
    }      