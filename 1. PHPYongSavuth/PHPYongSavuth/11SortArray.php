<!DOCTYPE html>
<html>
<head>
    <title>isset() Function Example</title> 
    <meta charset="UTF-8">
</head>
<body>
    <?php
        $colors = array("Red", "Green", "Blue", "Yellow");
        print "Befor sort: <br>" ;
        print_r($colors);
        echo "<br> After sort: <br>";
        sort($colors);
        foreach ($colors as $color) {
            echo "$color  ,";
        }

        rsort($colors);
        echo "<br> After reverse sort: <br>";   
        foreach ($colors as $color) {
            echo "$color  ,";
        }
    ?>
</body>
</html>