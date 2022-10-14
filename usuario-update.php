<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$usu_nome = "";
$usu_cpf = "";
$usu_datanasc = "";
$usu_telefone = "";
$usu_email = "";
$usu_senha = "";
$usu_ender = "";
$usu_datareg = "";
$usu_ultimaalt = "";

$usu_nome_err = "";
$usu_cpf_err = "";
$usu_datanasc_err = "";
$usu_telefone_err = "";
$usu_email_err = "";
$usu_senha_err = "";
$usu_ender_err = "";
$usu_datareg_err = "";
$usu_ultimaalt_err = "";


// Processing form data when form is submitted
if(isset($_POST["usu_id"]) && !empty($_POST["usu_id"])){
    // Get hidden input value
    $usu_id = $_POST["usu_id"];

    $usu_nome = trim($_POST["usu_nome"]);
		$usu_cpf = trim($_POST["usu_cpf"]);
		$usu_datanasc = trim($_POST["usu_datanasc"]);
		$usu_telefone = trim($_POST["usu_telefone"]);
		$usu_email = trim($_POST["usu_email"]);
		$usu_senha = trim($_POST["usu_senha"]);
		$usu_ender = trim($_POST["usu_ender"]);
		$usu_datareg = trim($_POST["usu_datareg"]);
		$usu_ultimaalt = trim($_POST["usu_ultimaalt"]);
		

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

    $vars = parse_columns('usuario', $_POST);
    $stmt = $pdo->prepare("UPDATE usuario SET usu_nome=?,usu_cpf=?,usu_datanasc=?,usu_telefone=?,usu_email=?,usu_senha=?,usu_ender=?,usu_datareg=?,usu_ultimaalt=? WHERE usu_id=?");

    if(!$stmt->execute([ $usu_nome,$usu_cpf,$usu_datanasc,$usu_telefone,$usu_email,$usu_senha,$usu_ender,$usu_datareg,$usu_ultimaalt,$usu_id  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: usuario-read.php?usu_id=$usu_id");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["usu_id"] = trim($_GET["usu_id"]);
    if(isset($_GET["usu_id"]) && !empty($_GET["usu_id"])){
        // Get URL parameter
        $usu_id =  trim($_GET["usu_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM usuario WHERE usu_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $usu_id;

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

                    $usu_nome = $row["usu_nome"];
					$usu_cpf = $row["usu_cpf"];
					$usu_datanasc = $row["usu_datanasc"];
					$usu_telefone = $row["usu_telefone"];
					$usu_email = $row["usu_email"];
					$usu_senha = $row["usu_senha"];
					$usu_ender = $row["usu_ender"];
					$usu_datareg = $row["usu_datareg"];
					$usu_ultimaalt = $row["usu_ultimaalt"];
					

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
                                <label>usu_nome</label>
                                <input type="text" name="usu_nome" maxlength="50"class="form-control" value="<?php echo $usu_nome; ?>">
                                <span class="form-text"><?php echo $usu_nome_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>usu_cpf</label>
                                <input type="number" name="usu_cpf" class="form-control" value="<?php echo $usu_cpf; ?>">
                                <span class="form-text"><?php echo $usu_cpf_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>usu_datanasc</label>
                                <input type="text" name="usu_datanasc" class="form-control" value="<?php echo $usu_datanasc; ?>">
                                <span class="form-text"><?php echo $usu_datanasc_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>usu_telefone</label>
                                <input type="text" name="usu_telefone" maxlength="50"class="form-control" value="<?php echo $usu_telefone; ?>">
                                <span class="form-text"><?php echo $usu_telefone_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>usu_email</label>
                                <input type="text" name="usu_email" maxlength="50"class="form-control" value="<?php echo $usu_email; ?>">
                                <span class="form-text"><?php echo $usu_email_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>usu_senha</label>
                                <input type="text" name="usu_senha" maxlength="50"class="form-control" value="<?php echo $usu_senha; ?>">
                                <span class="form-text"><?php echo $usu_senha_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>usu_ender</label>
                                <input type="text" name="usu_ender" maxlength="50"class="form-control" value="<?php echo $usu_ender; ?>">
                                <span class="form-text"><?php echo $usu_ender_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>usu_datareg</label>
                                <input type="text" name="usu_datareg" class="form-control" value="<?php echo $usu_datareg; ?>">
                                <span class="form-text"><?php echo $usu_datareg_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>usu_ultimaalt</label>
                                <input type="text" name="usu_ultimaalt" class="form-control" value="<?php echo $usu_ultimaalt; ?>">
                                <span class="form-text"><?php echo $usu_ultimaalt_err; ?></span>
                            </div>

                        <input type="hidden" name="usu_id" value="<?php echo $usu_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="usuario-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
