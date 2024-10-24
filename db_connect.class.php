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
        public function updateAbsent($table, $params=array(), $where = null){
            $sql = $this->select($table, 'group_id', null, "reg = $where[0]");
            $this->sql($sql);
            $gid = $this->getResult()[0]['group_id'];
            if($this->tableExists($table)){
               // UPDATE `registration_details` SET `registered` = NOW(), `attd` = 'A' WHERE group_id='Group-3' and attd = "A";
                 $sql = "UPDATE `registration_details` SET `registered` = NOW(), `attd` = 'A'";
                 $sql .=" WHERE group_id='$gid'";
                 $sql .=" and attd IS NULL";
            }
            //echo $sql;
            if($this->mysqli->query($sql)){
                array_push($this->result, $this->mysqli->affected_rows);
            }else{
                array_push($this->result, $this->mysqli->error);
            }
        }
        public function updatePresent($table, $params=array(), $where = null){
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
            $allowed = array ( 'reg', 'participant', 'gender', 'age', 'reg_table' , 'acc_venue', 'attd' );
            //$skipped = array ( 'tot_mem','group_id','email','state','city','travel_mode','travel_number','arrive_date','arrive_time','dep_date','dep_time','food_packets','acc_status','special_req','emergency_contact' , 'acc_venue');
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
            echo $this->header("participant");
            
            foreach($data as $participant){
                $final_array = array_intersect_key($participant, array_flip($allowed));
                echo "<tr><td>".$i++." </td>";
                if($participant['attd'] != NULL){
                    if($final_array['attd'] == "A"){
                        $final_array['acc_venue'] = "---";
                    }
                    foreach($final_array as $key => $value){
                        if($key != 'attd'){
                            echo "<td>$value</td>";
                        }
                    }
                }else{
                    foreach($final_array as $key => $value){
                        if($key != 'acc_venue'){
                            echo "<td> $value </td>";
                        }
                    }
                }
                
                echo "<td>";
                if($final_array['attd'] <> NULL){
                    if($final_array['attd'] =='A') { echo 'Marked Absent'; }
                    else{ echo 'Marked Present'; }
                }else{
                    echo <<<EOT
                    <input type="checkbox" name="$final_array[reg]" value="1" >
                EOT;
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo <<<FIN
                <input type="submit" name="l_submit" class = "btn btn-primary" value="Register Here!">
                </form>
            FIN;

        }
        public function printAcc($data){
            $display = "<table class='table'>";
            $display .= $this->header("Acc");
            foreach($data as $datum){
                $display .= "<tr>";
                if($datum['attd'] == "A"){ continue; }
                foreach($datum as  $value){
                    $display .= "<td>$value</td>";
                }
                $display .= "</tr>";
            }
            $display .= "</table>";
            echo $display;
        }
        public function list($table, $cols="*", $join=null, $where=null,$string, $status){
            $i = 1;
            $sql =  $this->select($table, $cols, $join, $where);
            $this->sql($sql);
            $data = $this->getResult();
            echo "$string";
            if ( count($data) > 0) {
                $this->print_report($data, $status);
                echo "<hr><p></p><p></p><p></p>";
            }else{
                echo "No one has marked Attendance Yet!!!";
                echo "<hr><p></p><p></p><p></p>";
            }
            return $data;
        }
        public function fetchGID($key){
            $sql = "SELECT group_id from registration_details WHERE reg = '$key'";
            $this->sql($sql);
            return $this->getResult()[0];
        }
        public function print_report($data, $status){
            $i = 1;
            $allowed = array ( 'reg', 'group_id', 'participant', 'gender', 'age', 'uid', 'reg_table' ,  'attd' );
            //$skipped = array ( 'tot_mem','group_id','email','state','city','travel_mode','travel_number','arrive_date','arrive_time','dep_date','dep_time','food_packets','acc_status','special_req','emergency_contact');
            $final_array = array_intersect_key($data[0], array_flip($allowed));
            echo "<table class='table table-striped'>";
            echo $this->header("admin");
            foreach($data as $participant){
                $final_array = array_intersect_key($participant, array_flip($allowed));
                echo "<tr><td>".$i++." </td>";
                foreach($final_array as $key => $value){
                    if($key == "attd" && ($value == "P" || $value == "A")){
                        $value = "$status";
                    }else if ($key == "attd"){
                        $value = "Online Pending";
                    }
                    echo "<td> $value </td>";
                }
                echo "<td></tr>";
            }
            echo "</table>";
        }
        public function header($status){
            if($status == "admin"){
                $t_head = "<thead><tr>";
                $t_head .= "<th class = 'cols' > S No. </td>";
                $t_head .= "<th class = 'cols' > Reg No. </td>";
                $t_head .= "<th class = 'cols' > Group ID </td>";
                $t_head .= "<th class = 'cols' > Name of Participant </td>";
                $t_head .= "<th class = 'cols' > Gender </td>";
                $t_head .= "<th class = 'cols' > Mobile Number </td>";
                $t_head .= "<th class = 'cols' > Age </td>";
                $t_head .= "<th class = 'cols' > Registration Table </td>";
                //$t_head .= "<th class = 'cols' > Accomodation Venue </td>";
                $t_head .= "<th class = 'cols' > Register Yourself </td>";
                $t_head .= "</tr></thead>";
                return $t_head;
            }
            else if($status == "Acc"){
                $t_head = "<thead><tr>";
                $t_head .= "<th class = 'cols' > S No. </td>";
                $t_head .= "<th class = 'cols' > Registration No. </td>";
                $t_head .= "<th class = 'cols' > Name of Participant </td>";
                $t_head .= "<th class = 'cols' > Gender </td>";
                //$t_head .= "<th class = 'cols' > Mobile Number </td>";
                $t_head .= "<th class = 'cols' > Age </td>";
                $t_head .= "<th class = 'cols' > Registration Table </td>";
                $t_head .= "<th class = 'cols' > Accomodation Venue </td>";
                $t_head .= "<th class = 'cols' > Register Yourself </td>";
                $t_head .= "</tr></thead>";
                return $t_head;
            }else{
                $t_head = "<thead><tr>";
                $t_head .= "<th class = 'cols' > S No. </td>";
                $t_head .= "<th class = 'cols' > Registration No. </td>";
                $t_head .= "<th class = 'cols' > Name of Participant </td>";
                $t_head .= "<th class = 'cols' > Gender </td>";
                //$t_head .= "<th class = 'cols' > Mobile Number </td>";
                $t_head .= "<th class = 'cols' > Age </td>";
                $t_head .= "<th class = 'cols' > Registration Table </td>";
                $t_head .= "<th class = 'cols' > Accomodation Venue </td>";
                $t_head .= "<th class = 'cols' > Register Yourself </td>";
                $t_head .= "</tr></thead>";
                return $t_head;
        }
    }
}

//For Future Use if needed....
            //echo "<th class = 'cols' > Total Group members </td>";
            //echo "<th class = 'cols' > Group ID </td>";
            //echo "<th class = 'cols' > Email id </td>";
            //echo "<th class = 'cols' > uid Number </td>";
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