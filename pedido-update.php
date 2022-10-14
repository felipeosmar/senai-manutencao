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
if(isset($_POST["ped_id"]) && !empty($_POST["ped_id"])){
    // Get hidden input value
    $ped_id = $_POST["ped_id"];

    $ped_usu_id = trim($_POST["ped_usu_id"]);
		$ped_entreg_id = trim($_POST["ped_entreg_id"]);
		$ped_status = trim($_POST["ped_status"]);
		$ped_dataini = trim($_POST["ped_dataini"]);
		$ped_datafim = trim($_POST["ped_datafim"]);
		

    // Prepare an update statement
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
        exit('Something weird happened');
    }

    $vars = parse_columns('pedido', $_POST);
    $stmt = $pdo->prepare("UPDATE pedido SET ped_usu_id=?,ped_entreg_id=?,ped_status=?,ped_dataini=?,ped_datafim=? WHERE ped_id=?");

    if(!$stmt->execute([ $ped_usu_id,$ped_entreg_id,$ped_status,$ped_dataini,$ped_datafim,$ped_id  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: pedido-read.php?ped_id=$ped_id");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ped_id"] = trim($_GET["ped_id"]);
    if(isset($_GET["ped_id"]) && !empty($_GET["ped_id"])){
        // Get URL parameter
        $ped_id =  trim($_GET["ped_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM pedido WHERE ped_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ped_id;

            // Bind variables to the prepared statement as parameters
			if (is_int($param_id)) $__vartype = "i";
			elseif (is_string($param_id)) $__vartype = "s";
			elseif (is_numeric($param_id)) $__vartype = "d";
			else $__vartype = "b"; // blob
			mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $ped_usu_id = $row["ped_usu_id"];
					$ped_entreg_id = $row["ped_entreg_id"];
					$ped_status = $row["ped_status"];
					$ped_dataini = $row["ped_dataini"];
					$ped_datafim = $row["ped_datafim"];
					

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.<br>".$stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

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

                        <input type="hidden" name="ped_id" value="<?php echo $ped_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="pedido-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
