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
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $estab_nome = trim($_POST["estab_nome"]);
		$estab_cnpj = trim($_POST["estab_cnpj"]);
		$estab_end = trim($_POST["estab_end"]);
		$estab_tel = trim($_POST["estab_tel"]);
		$estab_aval = trim($_POST["estab_aval"]);
		$estab_datareg = trim($_POST["estab_datareg"]);
		$estab_ultimaalt = trim($_POST["estab_ultimaalt"]);
		

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

        $vars = parse_columns('estabelecimento', $_POST);
        $stmt = $pdo->prepare("INSERT INTO estabelecimento (estab_nome,estab_cnpj,estab_end,estab_tel,estab_aval,estab_datareg,estab_ultimaalt) VALUES (?,?,?,?,?,?,?)");

        if($stmt->execute([ $estab_nome,$estab_cnpj,$estab_end,$estab_tel,$estab_aval,$estab_datareg,$estab_ultimaalt  ])) {
                $stmt = null;
                header("location: estabelecimento-index.php");
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

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="estabelecimento-index.php" class="btn btn-secondary">Cancel</a>
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