<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$entreg_nome = "";
$entreg_cpf = "";
$entreg_datanasc = "";
$entreg_email = "";
$entreg_telefone = "";
$entreg_senha = "";
$entreg_datareg = "";
$entreg_ultimaalt = "";

$entreg_nome_err = "";
$entreg_cpf_err = "";
$entreg_datanasc_err = "";
$entreg_email_err = "";
$entreg_telefone_err = "";
$entreg_senha_err = "";
$entreg_datareg_err = "";
$entreg_ultimaalt_err = "";


// Processing form data when form is submitted
if(isset($_POST["entreg_id"]) && !empty($_POST["entreg_id"])){
    // Get hidden input value
    $entreg_id = $_POST["entreg_id"];

    $entreg_nome = trim($_POST["entreg_nome"]);
		$entreg_cpf = trim($_POST["entreg_cpf"]);
		$entreg_datanasc = trim($_POST["entreg_datanasc"]);
		$entreg_email = trim($_POST["entreg_email"]);
		$entreg_telefone = trim($_POST["entreg_telefone"]);
		$entreg_senha = trim($_POST["entreg_senha"]);
		$entreg_datareg = trim($_POST["entreg_datareg"]);
		$entreg_ultimaalt = trim($_POST["entreg_ultimaalt"]);
		

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

    $vars = parse_columns('entregador', $_POST);
    $stmt = $pdo->prepare("UPDATE entregador SET entreg_nome=?,entreg_cpf=?,entreg_datanasc=?,entreg_email=?,entreg_telefone=?,entreg_senha=?,entreg_datareg=?,entreg_ultimaalt=? WHERE entreg_id=?");

    if(!$stmt->execute([ $entreg_nome,$entreg_cpf,$entreg_datanasc,$entreg_email,$entreg_telefone,$entreg_senha,$entreg_datareg,$entreg_ultimaalt,$entreg_id  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: entregador-read.php?entreg_id=$entreg_id");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["entreg_id"] = trim($_GET["entreg_id"]);
    if(isset($_GET["entreg_id"]) && !empty($_GET["entreg_id"])){
        // Get URL parameter
        $entreg_id =  trim($_GET["entreg_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM entregador WHERE entreg_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $entreg_id;

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

                    $entreg_nome = $row["entreg_nome"];
					$entreg_cpf = $row["entreg_cpf"];
					$entreg_datanasc = $row["entreg_datanasc"];
					$entreg_email = $row["entreg_email"];
					$entreg_telefone = $row["entreg_telefone"];
					$entreg_senha = $row["entreg_senha"];
					$entreg_datareg = $row["entreg_datareg"];
					$entreg_ultimaalt = $row["entreg_ultimaalt"];
					

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
                                <label>entreg_nome</label>
                                <input type="text" name="entreg_nome" maxlength="50"class="form-control" value="<?php echo $entreg_nome; ?>">
                                <span class="form-text"><?php echo $entreg_nome_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>entreg_cpf</label>
                                <input type="number" name="entreg_cpf" class="form-control" value="<?php echo $entreg_cpf; ?>">
                                <span class="form-text"><?php echo $entreg_cpf_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>entreg_datanasc</label>
                                <input type="text" name="entreg_datanasc" class="form-control" value="<?php echo $entreg_datanasc; ?>">
                                <span class="form-text"><?php echo $entreg_datanasc_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>entreg_email</label>
                                <input type="text" name="entreg_email" maxlength="50"class="form-control" value="<?php echo $entreg_email; ?>">
                                <span class="form-text"><?php echo $entreg_email_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>entreg_telefone</label>
                                <input type="number" name="entreg_telefone" class="form-control" value="<?php echo $entreg_telefone; ?>">
                                <span class="form-text"><?php echo $entreg_telefone_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>entreg_senha</label>
                                <input type="text" name="entreg_senha" maxlength="50"class="form-control" value="<?php echo $entreg_senha; ?>">
                                <span class="form-text"><?php echo $entreg_senha_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>entreg_datareg</label>
                                <input type="text" name="entreg_datareg" class="form-control" value="<?php echo $entreg_datareg; ?>">
                                <span class="form-text"><?php echo $entreg_datareg_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>entreg_ultimaalt</label>
                                <input type="text" name="entreg_ultimaalt" class="form-control" value="<?php echo $entreg_ultimaalt; ?>">
                                <span class="form-text"><?php echo $entreg_ultimaalt_err; ?></span>
                            </div>

                        <input type="hidden" name="entreg_id" value="<?php echo $entreg_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="entregador-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
