<?php

session_start();

if (isset($_SESSION['name'])) {
    require_once "pdo.php";

    try {
        $stmt = $pdo->query("SELECT * FROM autos");
        $autos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        echo("Internal error, please contact support");
        error_log("SQL error=" . $ex->getMessage());
        return;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ahmed Helal Ahmed</title>
</head>
<body>
<h1>Welcome to the Automobiles Database</h1>


<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">'. $_SESSION['error'] ."</p>\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo('<p style="color: green;">' . $_SESSION['success'] . "</p>\n");
    unset($_SESSION['success']);
}
?>



<?php
if (isset($_SESSION['name'])) {
    if (count($autos)) {
        echo('<table border="1">' . "\n");
        echo('
        <thead>
        <tr>
        <th>Make</th>
        <th>Model</th>
        <th>Year</th>
        <th>Mileage</th>
        <th>Action</th>
        </tr>
        </thead>
        <tbody>
       ');
        foreach ($autos as $auto) {
            echo('<tr><td>' . htmlentities($auto['make']) . '</td>');
            echo('<td>' . htmlentities($auto['model']) . '</td>');
            echo('<td>' . $auto['year'] . '</td>');
            echo('<td>' . $auto['mileage']. '</td>');
            echo('<td>');
            echo('<a href="edit.php?autos_id=' . $auto['autos_id'] . '">Edit</a>');
            echo(' / ');
            echo('<a href="delete.php?autos_id=' . $auto['autos_id'] . '">Delete</a>');
            echo('</td> </tr>');
        }
        echo('</tbody>');
        echo('</table>');
    } else {
        echo("No rows found");
    }
}
?>

<?php
if (isset($_SESSION['name'])) {
    ?>
        <p>
            <a href="add.php">Add New Entry</a>
        </p>
        <p>
            <a href="logout.php">Logout</a>
        </p>
        <?php
}?>


<?php
if (!isset($_SESSION['name'])) {
        ?>
<a href="login.php">Please log in</a>

<p>
Attempt to go to
<a href="autos.php">autos.php</a> without logging in - it should fail with an error message.
</p>
<?php
    } ?>
</body>
</html>