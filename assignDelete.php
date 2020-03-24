<?php 

  session_start();
if(!isset($_SESSION["client_id"])){ // if "user" not set,
	session_destroy();
	header('Location: liblogin.php');     // go to login page
	exit;
}
require 'libDatabase.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if user clicks "yes" (sure to delete), delete record

	$id = $_POST['id'];
	
	// delete data
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM assign  WHERE assign_id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: assign.php");
} 
else { // otherwise, pre-populate fields to show data to be deleted

	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
# get assignment details
$sql = "SELECT * FROM assign where assign_id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);

# get volunteer details
$sql = "SELECT * FROM client where client_id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($data['assign_client_id']));
$perdata = $q->fetch(PDO::FETCH_ASSOC);

# get material details
$sql = "SELECT * FROM material where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($data['assign_material_id']));
$materialdata = $q->fetch(PDO::FETCH_ASSOC);

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

		<div class="span10 offset1">
		
			<div class="row">
				<h3>Delete Assignment</h3>
			</div>
			
			<form class="form-horizontal" action="assignDelete.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id;?>"/>
				<p class="alert alert-error">Are you sure you want to delete ?</p>
				<div class="form-actions">
					<button type="submit" class="btn btn-danger">Yes</button>
					<a class="btn" href="assign.php">No</a>
				</div>
			</form>
			
			
			<div class="form-horizontal" >
			
				<div class="control-group">
					<label class="control-label"><strong>Client</strong></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $perdata['fName'] ;?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label"><strong>Book</strong></label>
					<div class="controls">
						<label class="checkbox">
							<?php echo trim($materialdata['name']) . " (" . trim($materialdata['author']) . ") ";?>
						</label>
					</div>
				</div>
				

				
				<div class="form-actions">
					<a class="btn btn-dark" href="assign.php">Back</a>
				</div>
			
			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
		
    </div> <!-- end div: class="container" -->
	
</body>
</html>