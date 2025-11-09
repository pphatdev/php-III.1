<?php

echo "While Loop <br/>";
$i = 1;
while ($i <= 10) {
    echo $i . "<br/>";
    $i++;
}

echo "<br/> <br/>";

$j = 1;
echo "Do While Loop <br/>";
do {
    echo $j . "<br/>";
    $j++;
} while ($j <= 10);

echo "<br/> <br/>";

echo "For Loop <br/>";
for ($k = 1; $k <= 10; $k++) {
    echo $k . "<br/>";
}

echo "<br/> <br/>";

echo "Foreach Loop <br/>";
$colors = ["Red", "Green", "Blue", "Yellow", "Purple"];
foreach ($colors as $key => $color) {
    echo $key . ": " . $color . "<br/>";
}