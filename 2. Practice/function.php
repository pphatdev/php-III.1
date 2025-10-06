<?php
function add($a, $b) {
    return $a + $b;
}

function subtract($a, $b) {
    return $a - $b;
}

function multiply($a, $b) {
    return $a * $b;
}

function divide($a, $b) {
    if ($b == 0) {
        return "Division by zero error!";
    }
    return $a / $b;
}


echo "Example of functions.php<br/>";
$addResult = add(10, 5);
echo "Add: 10 + 5 = {$addResult} <br/>";

$subtractResult = subtract(10, 5);
echo "Subtract: 10 - 5 = {$subtractResult} <br/>";

$multiplyResult = multiply(10, 5);
echo "Multiply: 10 * 5 = {$multiplyResult} <br/>";

$divideResult = divide(10, 5);
echo "Divide: 10 / 5 = {$divideResult} <br/>";