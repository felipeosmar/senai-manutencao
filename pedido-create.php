<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$ped_usu_id = "";
$ped_entreg_id = "";
$ped_status = "";
$ped_dataini = "";
$ped_datafim = "";

$ped_usu_id_err = "";
$ped_entreg_id_err = "";
$ped_status_err = "";
$ped_dataini_err = "";
$ped_datafim_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $ped_usu_id = trim($_POST["ped_usu_id"]);
		$ped_entreg_id = trim($_POST["ped_entreg_id"]);
		$ped_status = trim($_POST["ped_status"]);
		$ped_dataini = trim($_POST["ped_dataini"]);
		$ped_datafim = trim($_POST["ped_datafim"]);
		

        $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
        $options = [
          PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
        ];
        try {
          $pdo = new PDO($dsn, $db_user, $db_password, $options);
        } catch (Exception $e) {
          error_log($e->getMessage());
          exit('Something weird happened'); //something a user can understand
        }

        $vars = parse_columns('pedido', $_POST);
        $stmt = $pdo->prepare("INSERT INTO pedido (ped_usu_id,ped_entreg_id,ped_status,ped_dataini,ped_datafim) VALUES (?,?,?,?,?)");

        if($stmt->execute([ $ped_usu_id,$ped_entreg_id,$ped_status,$ped_dataini,$ped_datafim  ])) {
                $stmt = null;
                header("location: pedido-index.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add a record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                                <label>ped_usu_id</label>
                                    <select class="form-control" id="ped_usu_id" name="ped_usu_id">
                                    <?php
                                        $sql = "SELECT *,usu_id FROM usuario";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            array_pop($row);
                                            $value = implode(" | ", $row);
                                            if ($row["usu_id"] == $ped_usu_id){
                                            echo '<option value="' . "$row[usu_id]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[usu_id]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $ped_usu_id_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>ped_entreg_id</label>
                                    <select class="form-control" id="ped_entreg_id" name="ped_entreg_id">
                                    <?php
                                        $sql = "SELECT *,entreg_id FROM entregador";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            array_pop($row);
                                            $value = implode(" | ", $row);
                                            if ($row["entreg_id"] == $ped_entreg_id){
                                            echo '<option value="' . "$row[entreg_id]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[entreg_id]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $ped_entreg_id_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>ped_status</label>
                                <input type="text" name="ped_status" maxlength="10"class="form-control" value="<?php echo $ped_status; ?>">
                                <span class="form-text"><?php echo $ped_status_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>ped_dataini</label>
                                <input type="text" name="ped_dataini" class="form-control" value="<?php echo $ped_dataini; ?>">
                                <span class="form-text"><?php echo $ped_dataini_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>ped_datafim</label>
                                <input type="text" name="ped_datafim" class="form-control" value="<?php echo $ped_datafim; ?>">
                                <span class="form-text"><?php echo $ped_datafim_err; ?></span>
                            </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="pedido-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>