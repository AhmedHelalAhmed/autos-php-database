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
        header("Location: edit.php?autos_id=".$_GET['autos_id']);
        return;
    }


    if ((!is_numeric($year)||!is_numeric($mileage))) {
        $_SESSION['error'] = "Mileage and year must be numeric";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);

        return;
    }
    
    try {
        $sql = "UPDATE autos SET make = :make, year = :year,
             mileage = :mileage,
             model = :model 
             WHERE autos_id = :autos_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(
            array(
                    ':make' => $make,
                    ':year' => $year,
                    ':mileage' => $mileage,
                    ':model' => $model,
                    ':autos_id' => $_POST['autos_id']
                    )
        );
   
        $_SESSION['success'] = "Record edited";
        header("Location: index.php");
        return;
    } catch (Exception $ex) {
        echo("Internal error, please contact support");
        error_log("SQL error=".$ex->getMessage());
        return;
    }
}
  
if (! isset($_GET['autos_id'])) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
}
  
$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :autos_id");
$stmt->execute(array(":autos_id" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header('Location: index.php') ;
    return;
}

$make=htmlentities($row['make']);
$year=htmlentities($row['year']);
$mileage=htmlentities($row['mileage']);
$model=htmlentities($row['model']);
$autos_id = $row['autos_id'];
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
        <h1>Tracking Autos for <?= $_SESSION['name'] ?></h1>
        <?php
            if (isset($_SESSION['error'])) {
                echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
                unset($_SESSION['error']);
            }
        ?>       
        <form method="post">
            <p>Make:
                <input type="text" name="make" size="40" value="<?= $make ?>">
            </p>
            <p>Model:
            <input type="text" name="model" size="40" value="<?= $model ?>">
            </p>
            <p>Year:
                <input type="text" name="year" size="10" value="<?= $year ?>">
            </p>
            <p>Mileage:
                <input type="text" name="mileage" size="10" value="<?= $mileage ?>">
            </p>
            <input type="hidden" name="autos_id" value="<?= $autos_id ?>">
            <input type="submit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>
