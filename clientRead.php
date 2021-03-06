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
$sql = "SELECT * FROM client where client_id = ?";
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
			<?php
				//Functions::logoDisplay2();
			?>
			<div class="row">
				<h3>View Volunteer Details</h3>
			</div>
			 
			<div class="form-horizontal" >
				
				<div class="control-group col-md-6">
				
					<label class="control-label"><strong>First Name</strong></label>
					<div class="controls ">
						<label class="checkbox">
							<?php echo $data['fName'];?> 
						</label>
					</div>
					
					<label class="control-label"><strong>Last Name</strong></label>
					<div class="controls ">
						<label class="checkbox">
							<?php echo $data['lName'];?> 
						</label>
					</div>
					
					<label class="control-label"><strong>Email</strong></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['email'];?>
						</label>
					</div>
					
					<label class="control-label"><strong>Mobile</strong></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['mobile'];?>
						</label>
					</div>     
					
					<label class="control-label"><strong>Title</strong></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['client_title'];?>
						</label>
					</div>   
					
					<!-- password omitted on Read/View -->
					
					<div class="form-actions">
						<a class="btn btn-dark" href="client.php">Back</a>
					</div>
					
				</div>
				
				<!-- Display photo, if any --> 

				<div class='control-group col-md-6'>
				<br>
					<div class="controls ">
					<?php 
					if ($data['filesize'] > 0) 
						echo '<img  height=25%; width=25%; src="data:image/jpeg;base64,' . 
							base64_encode( $data['filecontent'] ) . '" />'; 
					else 
						echo 'No photo on file.';
					?><!-- converts to base 64 due to the need to read the binary files code and display img -->
					</div>
				</div>
				

				
			</div>  <!-- end div: class="form-horizontal" -->

		</div> <!-- end div: class="container" -->
		
	</body> 
	
</html>