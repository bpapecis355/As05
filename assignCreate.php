<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_assign_create.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program adds/inserts a new assignment (table: fr_assignments)
 * ---------------------------------------------------------------------------
 */
 

//$personid = $_SESSION["client.id"];
//$materialid = $_GET['materials.id'];

session_start();
if(!isset($_SESSION["client_id"])){ // if "user" not set,
	session_destroy();
	header('Location: liblogin.php');     // go to login page
	exit;
}

require 'libDatabase.php';


if ( !empty($_POST)) {

	// initialize user input validation variables
	$clienterror = null;
	$materialError = null;
	
	// initialize $_POST variables
	$client = $_POST['client'];    // same as HTML name= attribute in put box
	$material = $_POST['material'];
	
	// validate user input
	$valid = true;
	if (empty($client)) {
		$clientError = 'Please choose a volunteer';
		$valid = false;
	}
	if (empty($material)) {
		$materialsError = 'Please choose an material';
		$valid = false;
	} 
		
	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO assign 
			(assign_client_id,assign_material_id) 
			values(?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($client,$material));
		Database::disconnect();
		header("Location: assign.php");
	}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    
		<div class="span10 offset1">
			<div class="row">
				<h3>Create new checkout</h3>
			</div>
	
			<form class="form-horizontal" action="assignCreate.php" method="post">
		
				<div class="control-group">
					<label class="control-label">Client</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM client ORDER BY fName ASC';
							echo "<select class='form-control' name='client' id='client_id'>";
							foreach ($pdo->query($sql) as $row) {
								echo "<option value='" . $row['client_id'] . " '> " . $row['fName']  . "</option>";
							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->
			  
				<div class="control-group">
					<label class="control-label">material</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM material ORDER BY name ASC';
							echo "<select class='form-control' name='material' id='id'>";


							foreach ($pdo->query($sql) as $row) {
								echo "<option value='" . $row['id'] . " '> " . $row['name'] . " (" . $row['type'] . "</option>";
							}
								
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
						<a class="btn" href="assign.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->

    </div> <!-- end div: class="container" -->

  </body>
</html>