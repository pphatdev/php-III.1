<?php
    echo "While Loop: ";
    $i = 1;
    while($i <= 5) {
        echo $i . " ";
        $i++;
    }

    echo "<br> ";
    echo "Do While Loop: ";
    $i = 1;
    do {
        echo $i . " ";
        $i++;
    }while($i <= 5);

    echo "<br> ";
    echo "For Loop: ";
    for($i = 1; $i <= 5; $i++) {
        echo $i . " ";
    }

    echo "<br> ";
    echo "Foreach Loop: ";
    $colors = array("Red", "Green", "Blue");
    foreach($colors as $color) {
        echo $color . " ";
    }   
?>