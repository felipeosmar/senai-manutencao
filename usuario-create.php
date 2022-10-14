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
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $usu_nome = trim($_POST["usu_nome"]);
		$usu_cpf = trim($_POST["usu_cpf"]);
		$usu_datanasc = trim($_POST["usu_datanasc"]);
		$usu_telefone = trim($_POST["usu_telefone"]);
		$usu_email = trim($_POST["usu_email"]);
		$usu_senha = trim($_POST["usu_senha"]);
		$usu_ender = trim($_POST["usu_ender"]);
		$usu_datareg = trim($_POST["usu_datareg"]);
		$usu_ultimaalt = trim($_POST["usu_ultimaalt"]);
		

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

        $vars = parse_columns('usuario', $_POST);
        $stmt = $pdo->prepare("INSERT INTO usuario (usu_nome,usu_cpf,usu_datanasc,usu_telefone,usu_email,usu_senha,usu_ender,usu_datareg,usu_ultimaalt) VALUES (?,?,?,?,?,?,?,?,?)");

        if($stmt->execute([ $usu_nome,$usu_cpf,$usu_datanasc,$usu_telefone,$usu_email,$usu_senha,$usu_ender,$usu_datareg,$usu_ultimaalt  ])) {
                $stmt = null;
                header("location: usuario-index.php");
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

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="usuario-index.php" class="btn btn-secondary">Cancel</a>
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