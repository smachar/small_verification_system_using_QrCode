<?php
    $code = $_GET['qr'];
    if(!isset($code)){
        die("no code received!!");
    }
    include "connexion.php";
    $query = sprintf("SELECT * FROM participants WHERE email='%s'",
            mysqli_real_escape_string($con, $code));
    $result = mysqli_query($con, $query);
    if(!$result){
        //printf('Invalid query: '.mysqli_error($con).'\n');
        exit;
    }
    else{
        if($rarray = mysqli_fetch_array($result)){
            if(!$rarray['status']){
                echo $rarray['email']."<br>Approved";
                $query = sprintf("UPDATE `participants` SET `status` = '1' WHERE `participants`.`email` = '%s';",
                mysqli_real_escape_string($con, $code));
                $result2 = mysqli_query($con, $query);
                if($result){
                    echo "<br>thank you.";
                }
            }
            else{
                echo $rarray['email']."<br>Already there";
            }
        }
        else{
            echo "Sorry ".$rarray['email'].", <br>you didn't register";
        }
        mysqli_free_result($result);
    }
    mysqli_close($con);



?>