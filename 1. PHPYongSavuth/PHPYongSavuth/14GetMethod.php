<!DOCTYPE html>
<html>
<head>
    <title>PHP GET Method</title>
    <meta charset="UTF-8">
</head>
<body>
<?php
if(isset($_GET['name'])){
    echo "<p>Hello, " . $_GET['name'] . "</p>";
}
?>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="inputName">Name:</label>
    <input type="text" id="inputName" name="name">
    <input type="submit" value="Submit">
</form>
</body>
</html>