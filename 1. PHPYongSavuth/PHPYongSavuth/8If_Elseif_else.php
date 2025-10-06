<?php
    $date = date("D");
    if($date == "Sat") {
        echo "Have a nice Saturday!";
    } elseif($date == "Sun ") {
        echo "Have a nice Sunday!";
    } else {
        echo "Have a nice day!";
    }
?>