<?php   
    $conn = new mysqli("localhost","root","","liuconnectmobile",3306);
    if($conn->connect_error){
        die("Connection failed".$conn->connect_error);
    }
    echo " ";
    
    ?>