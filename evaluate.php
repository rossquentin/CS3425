<?php

if (!isset($_COOKIE['account'])) {
	header("LOCATION:login.php");
	return;
}
?>


<html>

<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>
	<nav class="navbar navbar-dark bg-dark navbar-expand-lg fixed-top">
		<div class="container">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="student.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="register.php">Register</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="logout.php">Logout</a>
				</li>
			</ul>
		</div>
	</nav>
	<div class="container" style="padding-top:75">
		<div class='col'>
		</div>
		<?php
		try {
			$config = parse_ini_file("db.ini");
			$dbh = new PDO($config['dsn'], $config['username'], $config['password']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$questionNum = 1;

			echo "<form action='updateEvaluation.php' method='POST'>";
			foreach ($dbh->query("select * from questions") as $question) {
				echo "<table class='table table-bordered'>";
				echo "<thead>";
				echo    "<tr>";
				echo    "<th colspan='11'>" . $question[0] . " - " . $question[2] . "</th>";
				echo    "</tr>";
				echo "</thead>";
				echo "<tbody>";

				if ($question[1] == 1 || $question[1] == 'Multiple Choice') {
					echo "<tr>";
					echo 	"<td style='text-align:center'><input class='col' type='radio' name='q_no_".$questionNum."' value='1' required>Strongly Disagree</input></td>";
					echo 	"<td style='text-align:center'><input class='col' type='radio' name='q_no_".$questionNum."' value='2' required>Disagree</input></td>";
					echo 	"<td style='text-align:center'><input class='col' type='radio' name='q_no_".$questionNum."' value='3' required>Neutral</input></td>";
					echo 	"<td style='text-align:center'><input class='col' type='radio' name='q_no_".$questionNum."' value='4' required>Agree</input></td>";
					echo 	"<td style='text-align:center'><input class='col' type='radio' name='q_no_".$questionNum."' value='5' required>Strongly Agree</input></td>";
					echo "</tr>";
				} else {
					echo "<tr>";
					echo	"<td><textarea class='form-control' name='q_no_".$questionNum."' placeholder='Input your answer' rows='3' required></textarea></td>";
					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
				echo "<br>";
				$questionNum++;
			}
			echo "<input type='hidden' name='id' value='".$_GET['id']."'/>";
			echo "<input class='btn btn-dark' type='submit' name='Submit' value='Submit'/>";
			echo "</form>";
		} catch (PDOException $e) {
			print "Error!" . $e->getMessage() . "<br/>";
			die();
		}
		?>
	</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>