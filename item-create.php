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
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $item_prod_id = trim($_POST["item_prod_id"]);
		$item_ped_id = trim($_POST["item_ped_id"]);
		$preco_dia = trim($_POST["preco_dia"]);
		$item_quant = trim($_POST["item_quant"]);
		

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

        $vars = parse_columns('item', $_POST);
        $stmt = $pdo->prepare("INSERT INTO item (item_prod_id,item_ped_id,preco_dia,item_quant) VALUES (?,?,?,?)");

        if($stmt->execute([ $item_prod_id,$item_ped_id,$preco_dia,$item_quant  ])) {
                $stmt = null;
                header("location: item-index.php");
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

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="item-index.php" class="btn btn-secondary">Cancel</a>
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