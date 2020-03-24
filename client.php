<?php

session_start();
if(!isset($_SESSION["client_id"])){ // if "user" not set,
	session_destroy();
	header('Location: liblogin.php');     // go to login page
	exit;
}
$sessionid = $_SESSION['client_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body style="background-color: white !important";>
    <div class="container">
		<?php 
			//gets logo
			///require 'functions.php';
			//functions::logoDisplay2();
		?>
		<div class="row">
			<h3>Clients</h3>
		</div>
		<div class="row">

			<p>
				<?php if($_SESSION['client_title']=='Administrator')
					echo '<a href="clientCreate.php" class="btn btn-primary">Add Client</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<a href="client.php">Client</a> &nbsp;
				<a href="material.php">Material</a> &nbsp;
				<a href="assign.php">All rentals</a>&nbsp;
			</p>
				
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Mobile</th>
						<th>Email</th>
						<th>Title</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'libDatabase.php';
						$pdo = Database::connect();
						//$sql = 'SELECT `client`.*, COUNT(`fr_assignments`.assign_per_id) AS countAssigns FROM `fr_persons` LEFT OUTER JOIN `fr_assignments` ON (`fr_persons`.id=`fr_assignments`.assign_per_id) GROUP BY `fr_persons`.id ORDER BY `fr_persons`.lname ASC, `fr_persons`.fname ASC';
						$sql = 'SELECT * FROM client ORDER BY `client`.lName ASC, `client`.fName ASC';
						foreach ($pdo->query($sql) as $row) {

							echo '<tr>';

							echo '<td>'. ($row['fName']) . '</td>';
							echo '<td>'. ($row['lName']) . '</td>';
							echo '<td>'. ($row['mobile']) . '</td>';
							echo '<td>'. ($row['email']) . '</td>';
							echo '<td>'. ($row['client_title']) . '</td>';

							echo '<td width=300>';
							# always allow read
							echo '<a class="btn" href="clientRead.php?id='.$row['client_id'].'">Details</a>&nbsp;';
							# person can update own record
							if ($_SESSION['client_title']=='Administrator'
								|| $_SESSION['client_id']==$row['id'])
								echo '<a class="btn btn-success" href="clientUpdate.php?id='.$row['client_id'].'">Update</a>&nbsp;';
							# only admins can delete
							if ($_SESSION['client_title']=='Administrator')
								echo '<a class="btn btn-danger" href="clientDelete.php?id='.$row['client_id'].'">Delete</a>';
							if($_SESSION["client_id"] == $row['client_id']) 
								echo " &nbsp;&nbsp;Me";
							echo '</td>';
							echo '</tr>';
						}
						Database::disconnect();
					?>
				</tbody>
			</table>
			
    	</div>
    </div> <!-- /container -->
  </body>
</html>