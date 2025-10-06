<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POST Method</title>
</head>
<body>
    <h1>POST Method Example</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>
        </div>
        <button type="submit" value="Submit">Submit</button>
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = htmlspecialchars($_POST["name"] ?? 'N/A');
            $age = htmlspecialchars($_POST["age"] ?? '00');

            echo "<h2>Your Input:</h2>";
            echo "Name: $name <br>";
            echo "Age: $age <br>";
        }
    ?>
</body>
</html>