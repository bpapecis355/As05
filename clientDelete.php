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

if ( !empty($_POST)) { // if user clicks "yes" (sure to delete), delete record

	$id = $_POST['id'];
	
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM client  WHERE client_id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: client.php");
	
} 
else { // otherwise, pre-populate fields to show data to be deleted
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM client where client_id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	Database::disconnect();
}
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
	Couldnt solve issue that occurs when pressing yes for delete. The entry is deleted from the table but this screen stays open. Press no to go back and it is deleted from the table.

    <div class="container">
    	  <?php

			//Functions::logoDisplay();
		?>
		<div class="row">
			<h3>Delete Volunteer</h3>
		</div>
		
		<form class="form-horizontal" action="clientDelete.php" method="post">
			<input type="hidden" name="id" value="<?php echo $id;?>"/>
			<p class="alert alert-error">Are you sure you want to delete ?</p>
			<div class="form-actions">
				<button type="submit" class="btn btn-danger" href="client.php">Yes</button>
				<a class="btn" href="client.php">No</a>
			</div>
		</form>
		
		
		<div class="form-horizontal" >
				
			<div class="control-group col-md-6">
			
				<label class="control-label">First Name</label>
				<div class="controls ">
					<label class="checkbox">
						<?php echo $data['fName'];?> 
					</label>
				</div>
				
				<label class="control-label">Last Name</label>
				<div class="controls ">
					<label class="checkbox">
						<?php echo $data['lName'];?> 
					</label>
				</div>
				
				<label class="control-label">Email</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['email'];?>
					</label>
				</div>
				
				<label class="control-label">Mobile</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['mobile'];?>
					</label>
				</div>     
				
				<label class="control-label">Title</label>
				<div class="controls">
					<label class="checkbox">
						<?php echo $data['client_title'];?>
					</label>
				</div>   
				
				<!-- password omitted on Read/View -->
				
			</div>
			
			<!-- Display photo, if any --> 

			<div class='control-group col-md-6'>
				<div class="controls ">
				<?php 
				if ($data['filesize'] > 0) 
					echo '<img  height=5%; width=15%; src="data:image/jpeg;base64,' . 
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