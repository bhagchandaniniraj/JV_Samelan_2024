<?php
    class DBConnect  
    {  
        // private $host = "sql208.infinityfree.com"; // your host name  
        // private $username = "if0_37010531"; // your user name  
        // private $password = "ca5Z0pLOWXhiUq"; // your password  
        // private $db = "if0_37010531_jv";  // your database name  
        private $host = "localhost"; // your host name  
        private $username = "root"; // your user name  
        private $password = ""; // your password  
        private $db = "samelan_db";  // your database name  
        private $conn = false;
        private $mysqli = "";
        private $result = array();
        private $rows;

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
        public function update($table, $params=array(), $where = null){
            $args = array();
            if($this->tableExists($table)){
                $sql = "UPDATE $table SET registered = NOW(), attd = 'P' ". implode(",", $args);
                if($where != null){
                    $sql .= " WHERE ";
                    foreach($where as $key => $value){
                        if($value === "l_submit") continue;
                        $args[] = " reg = $value ";
                    }
                    $sql .= implode(" OR ", $args);
                }
                if($this->mysqli->query($sql)){
                    array_push($this->result, $this->mysqli->affected_rows);
                }else{
                    array_push($this->result, $this->mysqli->error);
                }
                return $sql;
            }else{
                return false;
            }
        }
        public function printTable($data){
            $i = 1;
            $allowed = array ( 'reg', 'participant', 'gender', 'age', 'reg_table' , 'acc_venue' );
            //$skipped = array ( 'tot_mem','group_id','email','state','city','travel_mode','travel_number','arrive_date','arrive_time','dep_date','dep_time','food_packets','acc_status','special_req','emergency_contact' );
            $final_array = array_intersect_key($data[0], array_flip($allowed));
            $form = <<<EOT
                <form action="submit_me.php" method="post">
                    <input type="checkbox" name="tickme" value="<?=final_array['reg']" ?>
                </form>
            EOT;
            echo <<<STRT
            <form action="submit_me.php" method="post">
            STRT;

            echo "<table class='table table-striped'>";
            echo "<thead><tr>";
            echo "<th class = 'cols' > S No. </td>";
            echo "<th class = 'cols' > Registration No. </td>";
            echo "<th class = 'cols' > Name of Participant </td>";
            echo "<th class = 'cols' > Gender </td>";
            echo "<th class = 'cols' > Age </td>";
            echo "<th class = 'cols' > Registration Table </td>";
            echo "<th class = 'cols' > Accomodation Venue </td>";
            echo "<th class = 'cols' > Register Yourself </td>";
            echo "</tr></thead>";
            
            foreach($data as $participant){
                $final_array = array_intersect_key($participant, array_flip($allowed));
                echo "<tr><td>".$i++." </td>";
                foreach($final_array as $key => $value){
                    echo "<td> $value </td>";
                }
                echo "<td>";
                echo <<<EOT
                    <input type="checkbox" name="$final_array[reg]" value="1" >
                EOT;
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo <<<FIN
                <input type="submit" name="l_submit" class = "btn btn-primary" value="Register Here!">
                </form>

            FIN;

        }
    }

//For Future Use if needed....
            //echo "<th class = 'cols' > Total Group members </td>";
            //echo "<th class = 'cols' > Group ID </td>";
            //echo "<th class = 'cols' > Email id </td>";
            //echo "<th class = 'cols' > Mobile Number </td>";
            //echo "<th class = 'cols' > State </td>";
            //echo "<th class = 'cols' > City </td>";
            //echo "<th class = 'cols' > Mode of travel </td>";
            //echo "<th class = 'cols' > Travel Details </td>";
            //echo "<th class = 'cols' > Arrival Date </td>";
            //echo "<th class = 'cols' > Arrival Time </td>";
            //echo "<th class = 'cols' > Departure Date </td>";
            //echo "<th class = 'cols' > Departure Time </td>";
            //echo "<th class = 'cols' > Food Packets during Departure	  </td>";
            //echo "<th class = 'cols' > Accommodation </td>";
            //echo "<th class = 'cols' > Special Requirements </td>";
            //echo "<th class = 'cols' > Emergency Contact Details </td>";