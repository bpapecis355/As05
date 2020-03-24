<?php 

session_start();
if(!isset($_SESSION["client_id"])){ // if "user" not set,
	session_destroy();
	header('Location: liblogin.php');     // go to login page
	exit;
}

require 'libDatabase.php';
//require 'functions.php';

$id = $_GET['id'];

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM material where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body>
    <div class="container">
    
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Book Details</h3>
			</div>
			
			<div class="form-horizontal" >
			
				<div class="control-group">
					<label class="control-label"><strong>Name</strong></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo ($data['name']);?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label"><strong>author</strong></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo ($data['author']);?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label"><strong>Type</strong></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['type'];?>
						</label>
					</div>
				</div>
				
				<?php 
					echo '<a class="btn btn-dark" href="material.php">Back</a>';
				 ?>
					 
					 


			<?php
				/*$pdo = Database::connect();
				$sql = "SELECT * FROM assign, client WHERE assign_id = id AND assign_material_id = " . $data['id'] . ' ORDER BY lName ASC, fName ASC';
				$countrows = 0;
				if($_SESSION['client_title']=='Administrator') {
					foreach ($pdo->query($sql) as $row) {
						echo $row['lName'] . ', ' . $row['fName'] . ' - ' . $row['mobile'] . '<br />';
					$countrows++;
					}
				}
				else {
					foreach ($pdo->query($sql) as $row) {
						echo $row['lName'] . ', ' . $row['fName'] . ' - ' . '<br />';
					$countrows++;
					}
				}
				if ($countrows == 0) echo 'none.';      */
			?>
			
			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
	
</body>
</html>