<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$prod_nome = "";
$prod_preco = "";
$prod_categ = "";
$prod_estab_id = "";
$prod_estab_datareg = "";
$prod_ultimaalt = "";

$prod_nome_err = "";
$prod_preco_err = "";
$prod_categ_err = "";
$prod_estab_id_err = "";
$prod_estab_datareg_err = "";
$prod_ultimaalt_err = "";


// Processing form data when form is submitted
if(isset($_POST["prod_id"]) && !empty($_POST["prod_id"])){
    // Get hidden input value
    $prod_id = $_POST["prod_id"];

    $prod_nome = trim($_POST["prod_nome"]);
		$prod_preco = trim($_POST["prod_preco"]);
		$prod_categ = trim($_POST["prod_categ"]);
		$prod_estab_id = trim($_POST["prod_estab_id"]);
		$prod_estab_datareg = trim($_POST["prod_estab_datareg"]);
		$prod_ultimaalt = trim($_POST["prod_ultimaalt"]);
		

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

    $vars = parse_columns('produto', $_POST);
    $stmt = $pdo->prepare("UPDATE produto SET prod_nome=?,prod_preco=?,prod_categ=?,prod_estab_id=?,prod_estab_datareg=?,prod_ultimaalt=? WHERE prod_id=?");

    if(!$stmt->execute([ $prod_nome,$prod_preco,$prod_categ,$prod_estab_id,$prod_estab_datareg,$prod_ultimaalt,$prod_id  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: produto-read.php?prod_id=$prod_id");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["prod_id"] = trim($_GET["prod_id"]);
    if(isset($_GET["prod_id"]) && !empty($_GET["prod_id"])){
        // Get URL parameter
        $prod_id =  trim($_GET["prod_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM produto WHERE prod_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $prod_id;

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

                    $prod_nome = $row["prod_nome"];
					$prod_preco = $row["prod_preco"];
					$prod_categ = $row["prod_categ"];
					$prod_estab_id = $row["prod_estab_id"];
					$prod_estab_datareg = $row["prod_estab_datareg"];
					$prod_ultimaalt = $row["prod_ultimaalt"];
					

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
                                <label>prod_nome</label>
                                <input type="text" name="prod_nome" maxlength="50"class="form-control" value="<?php echo $prod_nome; ?>">
                                <span class="form-text"><?php echo $prod_nome_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>prod_preco</label>
                                <input type="number" name="prod_preco" class="form-control" value="<?php echo $prod_preco; ?>" step="any">
                                <span class="form-text"><?php echo $prod_preco_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>prod_categ</label>
                                <input type="text" name="prod_categ" maxlength="50"class="form-control" value="<?php echo $prod_categ; ?>">
                                <span class="form-text"><?php echo $prod_categ_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>prod_estab_id</label>
                                    <select class="form-control" id="prod_estab_id" name="prod_estab_id">
                                    <?php
                                        $sql = "SELECT *,estab_id FROM estabelecimento";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            array_pop($row);
                                            $value = implode(" | ", $row);
                                            if ($row["estab_id"] == $prod_estab_id){
                                            echo '<option value="' . "$row[estab_id]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[estab_id]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $prod_estab_id_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>prod_estab_datareg</label>
                                <input type="text" name="prod_estab_datareg" class="form-control" value="<?php echo $prod_estab_datareg; ?>">
                                <span class="form-text"><?php echo $prod_estab_datareg_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>prod_ultimaalt</label>
                                <input type="text" name="prod_ultimaalt" class="form-control" value="<?php echo $prod_ultimaalt; ?>">
                                <span class="form-text"><?php echo $prod_ultimaalt_err; ?></span>
                            </div>

                        <input type="hidden" name="prod_id" value="<?php echo $prod_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="produto-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
