<!DOCTYPE html>
<html>
<head>
    <title>isset() Function Example</title> 
    <meta charset="UTF-8">    
</head>
<body>
    <?php
        $name = isset($_GET['name']) ? $_GET['name'] : 'Guest';
        echo $name;
    ?>
</body>
</html>