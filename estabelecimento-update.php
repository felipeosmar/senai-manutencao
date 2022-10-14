<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$estab_nome = "";
$estab_cnpj = "";
$estab_end = "";
$estab_tel = "";
$estab_aval = "";
$estab_datareg = "";
$estab_ultimaalt = "";

$estab_nome_err = "";
$estab_cnpj_err = "";
$estab_end_err = "";
$estab_tel_err = "";
$estab_aval_err = "";
$estab_datareg_err = "";
$estab_ultimaalt_err = "";


// Processing form data when form is submitted
if(isset($_POST["estab_id"]) && !empty($_POST["estab_id"])){
    // Get hidden input value
    $estab_id = $_POST["estab_id"];

    $estab_nome = trim($_POST["estab_nome"]);
		$estab_cnpj = trim($_POST["estab_cnpj"]);
		$estab_end = trim($_POST["estab_end"]);
		$estab_tel = trim($_POST["estab_tel"]);
		$estab_aval = trim($_POST["estab_aval"]);
		$estab_datareg = trim($_POST["estab_datareg"]);
		$estab_ultimaalt = trim($_POST["estab_ultimaalt"]);
		

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

    $vars = parse_columns('estabelecimento', $_POST);
    $stmt = $pdo->prepare("UPDATE estabelecimento SET estab_nome=?,estab_cnpj=?,estab_end=?,estab_tel=?,estab_aval=?,estab_datareg=?,estab_ultimaalt=? WHERE estab_id=?");

    if(!$stmt->execute([ $estab_nome,$estab_cnpj,$estab_end,$estab_tel,$estab_aval,$estab_datareg,$estab_ultimaalt,$estab_id  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: estabelecimento-read.php?estab_id=$estab_id");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["estab_id"] = trim($_GET["estab_id"]);
    if(isset($_GET["estab_id"]) && !empty($_GET["estab_id"])){
        // Get URL parameter
        $estab_id =  trim($_GET["estab_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM estabelecimento WHERE estab_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $estab_id;

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

                    $estab_nome = $row["estab_nome"];
					$estab_cnpj = $row["estab_cnpj"];
					$estab_end = $row["estab_end"];
					$estab_tel = $row["estab_tel"];
					$estab_aval = $row["estab_aval"];
					$estab_datareg = $row["estab_datareg"];
					$estab_ultimaalt = $row["estab_ultimaalt"];
					

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
                                <label>estab_nome</label>
                                <input type="text" name="estab_nome" maxlength="50"class="form-control" value="<?php echo $estab_nome; ?>">
                                <span class="form-text"><?php echo $estab_nome_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>estab_cnpj</label>
                                <input type="number" name="estab_cnpj" class="form-control" value="<?php echo $estab_cnpj; ?>">
                                <span class="form-text"><?php echo $estab_cnpj_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>estab_end</label>
                                <input type="text" name="estab_end" maxlength="50"class="form-control" value="<?php echo $estab_end; ?>">
                                <span class="form-text"><?php echo $estab_end_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>estab_tel</label>
                                <input type="text" name="estab_tel" maxlength="50"class="form-control" value="<?php echo $estab_tel; ?>">
                                <span class="form-text"><?php echo $estab_tel_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>estab_aval</label>
                                <input type="number" name="estab_aval" class="form-control" value="<?php echo $estab_aval; ?>">
                                <span class="form-text"><?php echo $estab_aval_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>estab_datareg</label>
                                <input type="text" name="estab_datareg" class="form-control" value="<?php echo $estab_datareg; ?>">
                                <span class="form-text"><?php echo $estab_datareg_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>estab_ultimaalt</label>
                                <input type="text" name="estab_ultimaalt" class="form-control" value="<?php echo $estab_ultimaalt; ?>">
                                <span class="form-text"><?php echo $estab_ultimaalt_err; ?></span>
                            </div>

                        <input type="hidden" name="estab_id" value="<?php echo $estab_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="estabelecimento-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
