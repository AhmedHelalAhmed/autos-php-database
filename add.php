<?php

session_start();
if (! isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}

require_once "pdo.php";

if ($_POST) {
    if (isset($_POST['cancel'])) {
        header('Location: index.php');
        return;
    }

    $make=$_POST['make'];
    $year=$_POST['year'];
    $mileage=$_POST['mileage'];
    $model=$_POST['model'];

    if (
        strlen($make)<1
        ||
        strlen($year)<1
        ||
        strlen($mileage)<1
        ||
        strlen($model)<1
        ) {
        $_SESSION['error'] = "All fields are required";
        header("Location: add.php");
        return;
    }

    if (!is_numeric($year)||!is_numeric($mileage)) {
        $_SESSION['error'] = "Mileage and year must be numeric";
        header("Location: add.php");
        return;
    }
    
    try {
        $stmt = $pdo->prepare('INSERT INTO autos(make, year, mileage,model) VALUES ( :mk, :yr, :mi,:ml)');
        $stmt->execute(array(':mk' => $make,':yr' => $year,':mi' => $mileage,':ml'=>$model));
        $_SESSION['success'] = "Record added";
        header("Location: index.php");
        return;
    } catch (Exception $ex) {
        echo("Internal error, please contact support");
        error_log("SQL error=".$ex->getMessage());
        return;
    }
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
        <h1>Tracking Automobiles for <?= $_SESSION['name'] ?></h1>
        <?php
            if (isset($_SESSION['error'])) {
                echo('<p style="color: red;">'.$_SESSION['error']."</p>\n");
                unset($_SESSION['error']);
            }
        ?>       
        <form method="post">
            <p>Make:
                <input type="text" name="make" size="40">
            </p>
            <p>Model:
            <input type="text" name="model" size="40">
            </p>
            <p>Year:
                <input type="text" name="year" size="10">
            </p>
            <p>Mileage:
                <input type="text" name="mileage" size="10">
            </p>
            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>
