<?php
    $date = date("D");
    if($date == "Sun") {
        echo "Have a nice Saturday!";
    } else {
        echo "Have a nice day!";
    }

    $age = 18;
    if($age <= 18) {
        echo "You are not old enough to vote.";
    } else {
        echo "You are old enough to vote.";
    }
?>