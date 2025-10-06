<?php
    echo "Function in PHP <br>";
    function testToday(){
         echo "Today is " . date("Y/m/d") . "<br>";       
    }
    testToday();

    function addNumbers($a, $b){
        $sum = $a + $b;
        echo "Sum of $a and $b is: $sum <br>";
    }
    addNumbers(5, 10);
?>