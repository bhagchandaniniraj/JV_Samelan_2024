<?php 
class ExportFile{
    public function myfile($data, $status){
        $filename = $status."_" .date('Y-m-d') .".csv";
        $delimiter = ",";

        $f = fopen("$filename", 'w+');
        
        $fields = array('reg','tot_mem','group_id','email','participant','gender','mobile',
                            'age','state','city','travel_mode','travel_number','arrive_date','arrive_time',
                            'dep_date','dep_time','food_packets','acc_status','special_req','emergency_contact',
                            'reg_table','acc_venue','registered','attd');
        
        fputcsv($f, $fields, $delimiter);
       
        // $result = $db->query("SELECT * FROM users ORDER BY id DESC");
        // if ($result->num_rows > 0) {
        //     while ($row = $result->fetch_assoc()) {
        //         $lineData = array($row['id'], $row['name'], $row['email'], $row['phone'], $row['created'], $row['status']);
        //         fputcsv($f, $lineData, $delimiter);
        //     } 
        // }

        fseek($f, 0);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;
        filename="' . $filename . '";
        ');
        fpassthru($f);
        die("I am not going to execute beyond this point");
        exit();
    }
}
?>