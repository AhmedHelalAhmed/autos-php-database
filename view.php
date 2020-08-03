<?php

if (! isset($_SESSION['name'])) {
    die('Not logged in');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahmed Helal Ahmed</title>
</head>
<body>
    <div>
    <?php
        if (isset($_SESSION['success'])) {
            echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
            unset($_SESSION['success']);
        }
    ?>    
        <h2>Automobiles</h2>
        <ul>
            <?php
                foreach ($autos as $auto) {
                    echo('<li><p>'.$auto['year']. " " .$auto['make'].' / '.$auto['mileage'].'</p></li>');
                }
            
            ?>
        </ul>
        <p>
            <a href="add.php">Add New</a> |
            <a href="logout.php">Logout</a>
        </p>
    </div>
</body>
</html>
