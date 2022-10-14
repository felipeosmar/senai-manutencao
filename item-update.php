<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$item_prod_id = "";
$item_ped_id = "";
$preco_dia = "";
$item_quant = "";

$item_prod_id_err = "";
$item_ped_id_err = "";
$preco_dia_err = "";
$item_quant_err = "";


// Processing form data when form is submitted
if(isset($_POST["item_id"]) && !empty($_POST["item_id"])){
    // Get hidden input value
    $item_id = $_POST["item_id"];

    $item_prod_id = trim($_POST["item_prod_id"]);
		$item_ped_id = trim($_POST["item_ped_id"]);
		$preco_dia = trim($_POST["preco_dia"]);
		$item_quant = trim($_POST["item_quant"]);
		

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

    $vars = parse_columns('item', $_POST);
    $stmt = $pdo->prepare("UPDATE item SET item_prod_id=?,item_ped_id=?,preco_dia=?,item_quant=? WHERE item_id=?");

    if(!$stmt->execute([ $item_prod_id,$item_ped_id,$preco_dia,$item_quant,$item_id  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: item-read.php?item_id=$item_id");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["item_id"] = trim($_GET["item_id"]);
    if(isset($_GET["item_id"]) && !empty($_GET["item_id"])){
        // Get URL parameter
        $item_id =  trim($_GET["item_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM item WHERE item_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $item_id;

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

                    $item_prod_id = $row["item_prod_id"];
					$item_ped_id = $row["item_ped_id"];
					$preco_dia = $row["preco_dia"];
					$item_quant = $row["item_quant"];
					

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
                                <label>item_prod_id</label>
                                    <select class="form-control" id="item_prod_id" name="item_prod_id">
                                    <?php
                                        $sql = "SELECT *,prod_id FROM produto";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            array_pop($row);
                                            $value = implode(" | ", $row);
                                            if ($row["prod_id"] == $item_prod_id){
                                            echo '<option value="' . "$row[prod_id]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[prod_id]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $item_prod_id_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>item_ped_id</label>
                                    <select class="form-control" id="item_ped_id" name="item_ped_id">
                                    <?php
                                        $sql = "SELECT *,ped_id FROM pedido";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            array_pop($row);
                                            $value = implode(" | ", $row);
                                            if ($row["ped_id"] == $item_ped_id){
                                            echo '<option value="' . "$row[ped_id]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ped_id]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $item_ped_id_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>preco_dia</label>
                                <input type="number" name="preco_dia" class="form-control" value="<?php echo $preco_dia; ?>" step="any">
                                <span class="form-text"><?php echo $preco_dia_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>item_quant</label>
                                <input type="number" name="item_quant" class="form-control" value="<?php echo $item_quant; ?>">
                                <span class="form-text"><?php echo $item_quant_err; ?></span>
                            </div>

                        <input type="hidden" name="item_id" value="<?php echo $item_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="item-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
