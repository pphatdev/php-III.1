<!DOCTYPE html>
<html>
<head>
    <title>PHP POST Method</title>
    <meta charset="UTF-8">
</head>
<body>
<?php
if(isset($_POST['name'])){
    echo "<p>Hello, " . $_POST['name'] . "</p>";
}
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="inputName">Name:</label>
    <input type="text" id="inputName" name="name">
    <input type="submit" value="Submit">
</form>
</body>
</html>