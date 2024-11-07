<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samelan Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    
    <div class="row">
        <p></p>
    </div>
    <div class="row">
        <p></p>
    </div>
    <div class="container">
    <div class="text-center">
        <img style="max-width: 350px; max-height: 250px; align: center" src="logo/AU_LOGO.png" alt="Atmiya University">
    </div>
    <div class="row">
        <p></p>
    </div>
    <h3 class="text-center">26th National Jeevan Vidhya Samelan</h3>
        <?php
            include 'samelan.class.php';
            if($_POST){
                $myobj = new Samelan();
                //echo "l_submit: ".isset($_POST['l_submit']);
                if(isset($_POST['uid']) && is_numeric($_POST['uid'])){
                    // $myobj = new Samelan();
                    $myobj->check($_POST['uid']);
                }else if(!isset($_POST['l_submit']) && isset($_POST['uid'])){
                    echo "Please enter 10 Digit UID Number only!!";
                    echo <<<GOBACK
                    <p></p>
                    <p></p>
                        <a href="/" class="btn btn-primary">Go Back</a>
                    GOBACK;
                }
                if(isset($_POST['l_submit'])){
                    if(in_array('Register Here!', $_POST)){
                        $keys = array_keys($_POST);
                        if(count($keys) > 1){
                            $myobj->update('registration_details', ['registered' =>'CURRENT_TIMESTAMP'], $keys);
                           // $myobj->updateAbsent('registration_details',['registered' => 'NOW()'], $keys);
                        }else{
                            echo "Kindly select atleast one participant to register and mark Present in Pre-registration!!";
                            echo <<<GOBACK
                            <p></p>
                            <p></p>
                                <a href="/" class="btn btn-primary">Go Back</a>
                            GOBACK;
                        }
                    }
                }
            }
        ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>