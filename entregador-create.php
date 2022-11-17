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
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $entreg_nome = trim($_POST["entreg_nome"]);
		$entreg_cpf = trim($_POST["entreg_cpf"]);
		$entreg_datanasc = trim($_POST["entreg_datanasc"]);
		$entreg_email = trim($_POST["entreg_email"]);
		$entreg_telefone = trim($_POST["entreg_telefone"]);
		$entreg_senha = trim($_POST["entreg_senha"]);
		$entreg_datareg = trim($_POST["entreg_datareg"]);
		$entreg_ultimaalt = trim($_POST["entreg_ultimaalt"]);
		

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

        $vars = parse_columns('entregador', $_POST);
        $stmt = $pdo->prepare("INSERT INTO entregador (entreg_nome,entreg_cpf,entreg_datanasc,entreg_email,entreg_telefone,entreg_senha,entreg_datareg,entreg_ultimaalt) VALUES (?,?,?,?,?,?,?,?)");

        if($stmt->execute([ $entreg_nome,$entreg_cpf,$entreg_datanasc,$entreg_email,$entreg_telefone,$entreg_senha,$entreg_datareg,$entreg_ultimaalt  ])) {
                $stmt = null;
                header("location: entregador-index.php");
            } else{
                echo "Something went wrong. Please try again later. (Algo deu errado. Por favor, tente novamente mais tarde.)";
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

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="entregador-index.php" class="btn btn-secondary">Cancel</a>
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
