<?php

// ប្រកាស Time Zone នៅ Phnom Penh
date_default_timezone_set("Asia/Phnom_Penh");

$time = date("H");
switch (true) {
    case ($time < "10"):
        print "Good morning! <br/> <br/>";
        break;
    case ($time < "20"):
        print "Good day! <br/> <br/>";
        break;
    default:
        print "Good night! <br/> <br/>";
}