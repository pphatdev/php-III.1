<?php
// ប្រកាស Time Zone នៅ Phnom Penh
date_default_timezone_set("Asia/Phnom_Penh");

$time = date("H");
if ($time < "10") {
    print "Good morning! <br/> <br/>";
} elseif ($time < "20") {
    print "Good day! <br/> <br/>";
} else {
    print "Good night! <br/> <br/>";
}