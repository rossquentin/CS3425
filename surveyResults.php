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
					<a class="nav-link" href="instructor.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="logout.php">Logout</a>
				</li>
			</ul>
		</div>
	</nav>
	<div class="container" style="padding-top:75">
		<br>
		<?php
		try {
			$config = parse_ini_file("db.ini");
			$dbh = new PDO($config['dsn'], $config['username'], $config['password']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$instructorAccount = $dbh->prepare("select account from teaches where id=:id");
			$instructorAccount->execute(array('id' => $_GET['id']));
			$instructorName = $dbh->query("select name from instructors where account='" . $instructorAccount->fetchColumn(0) . "'")->fetchColumn(0);
			$numStudents = $dbh->query("select count(account) from takes where id='" . $_GET['id'] . "'")->fetchColumn(0);
			$questionNum = 1;

			foreach ($dbh->query("select * from questions") as $questions) {
				
				$answers = $dbh->prepare("select account, q_no, response from evaluates where id=:id and q_no=:q_no");
				$answers->execute(array('id' => $_GET['id'], 'q_no' => $questionNum));
				$total = $answers->rowCount();
				$questionNum++;

				echo "<table class='table table-hover table-bordered'>";
				echo "<thead>";
				echo    "<tr>";
				echo    "<th colspan='11'>" . $questions[0] . " - " . $questions[2] . "</th>";
				echo    "</tr>";
				echo    "<tr>";
				echo    "<th colspan='11'>" . $instructorName . "</th>";
				echo    "</tr>";

				if ($questions[1] == 1 || $questions[1] == 'Multiple Choice') {
					$i = 1;
					$one = 0;
					$two = 0;
					$three = 0;
					$four = 0;
					$five = 0;

					foreach ($answers->fetchAll(PDO::FETCH_ASSOC) as $indvAnswer) {
						switch ($indvAnswer['response']) {
							case '1':
								$one++;
								break;
							case '2':
								$two++;
								break;
							case '3':
								$three++;
								break;
							case '4':
								$four++;
								break;
							case '5':
								$five++;
								break;
						}
					}
					$mean = ($total > 0) ? number_format((float)($one + $two*2 + $three*3 + $four*4 + $five*5)/$total, 2, '.', '') : 0.00; 
					$std =  ($total > 1) ? number_format((float)standard_deviation(array($one, $two*2, $three*3, $four*4, $five*5)), 2, '.', '') : 0.00;
					$median = array();
					$median = array_pad($median, $one, 1);
					$median = array_pad($median, $one + $two, 2);
					$median = array_pad($median, $one + $two + $three, 3);
					$median = array_pad($median, $one + $two + $three + $four, 4);
					$median = array_pad($median, $one + $two + $three + $four + $five, 5);
					$median =  number_format((float)getMedian($median), 2, '.', '');

					echo    "<tr>";
					echo    "<th> Response Option </th>";
					echo    "<th style='text-align:center'> Weight </th>";
					echo    "<th style='text-align:center'> Frequency </th>";
					echo    "<th style='text-align:center'> Percent </th>";
					echo    "</tr>";
					echo "</thead>";
					echo "<tbody>";

					for ($i = 1; $i <= 5; $i++) {
						echo    "<tr>";
						switch ($i) {
							case 1:
								echo    "<td> Strongly Disagree </td>";
								echo    "<td style='text-align:center'> (" . $i . ") </td>";
								echo    "<td style='text-align:center'> " . $one . " </td>";
								if ($total == 0) {
									echo    "<td style='text-align:center'> 0.00% </td>";
								} else {
									echo    "<td style='text-align:center'> " . number_format((float)($one / $total * 100), 2, '.', '') . "% </td>";
								}
								break;
							case 2:
								echo    "<td> Disagree </td>";
								echo    "<td style='text-align:center'> (" . $i . ") </td>";
								echo    "<td style='text-align:center'> " . $two . " </td>";
								if ($total == 0) {
									echo    "<td style='text-align:center'> 0.00% </td>";
								} else {
									echo    "<td style='text-align:center'> " . number_format((float)($two / $total * 100), 2, '.', '') . "% </td>";
								}
								break;
							case 3:
								echo    "<td> Neutral </td>";
								echo    "<td style='text-align:center'> (" . $i . ") </td>";
								echo    "<td style='text-align:center'> " . $three . " </td>";
								if ($total == 0) {
									echo    "<td style='text-align:center'> 0.00% </td>";
								} else {
									echo    "<td style='text-align:center'> " . number_format((float)($three / $total * 100), 2, '.', '') . "% </td>";
								}
								break;
							case 4:
								echo    "<td> Agree </td>";
								echo    "<td style='text-align:center'> (" . $i . ") </td>";
								echo    "<td style='text-align:center'> " . $four . " </td>";
								if ($total == 0) {
									echo    "<td style='text-align:center'> 0.00% </td>";
								} else {
									echo    "<td style='text-align:center'> " . number_format((float)($four / $total * 100), 2, '.', '') . "% </td>";
								}
								break;
							case 5:
								echo    "<td> Strongly Agree </td>";
								echo    "<td style='text-align:center'> (" . $i . ") </td>";
								echo    "<td style='text-align:center'> " . $five . " </td>";
								if ($total == 0) {
									echo    "<td style='text-align:center'> 0.00% </td>";
								} else {
									echo    "<td style='text-align:center'> " . number_format((float)($five / $total * 100), 2, '.', '') . "% </td>";
								}
								break;
						}
					}
					echo "</tr>";
						echo "</tbody>";
						echo "<thead>";
						echo "<tr>";
						echo    "<th style='text-align:center'> Response Rate </th>";
						echo 	"<th style='text-align:center'> Mean </td>";
						echo	"<th style='text-align:center'> STD </td>";
						echo	"<th style='text-align:center'> Median </td>";
						echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						echo "<tr>";
						echo    "<td style='text-align:center'>" . number_format((float)($total / $numStudents * 100), 2, '.', '') . "% (" . $total . "/" . $numStudents . ") </td>";
						echo	"<td style='text-align:center'> ".$mean." </td>";
						echo	"<td style='text-align:center'> ".$std." </td>";
						echo	"<td style='text-align:center'> ".$median." </td>";
				} else {
					echo    "<tr>";
					echo    "<th> Response Rate </th>";
					echo    "<td colspan='4'>" . number_format((float)($total / $numStudents * 100), 2, '.', '') . "% (" . $total . "/" . $numStudents . ") </td>";
					echo    "</tr>";
					echo "</thead>";
					echo "<tbody>";
					echo "<tr>";
					echo "<td colspan='2'>";
					foreach ($answers->fetchAll(PDO::FETCH_ASSOC) as $indvAnswer) {
						echo "<p>&#8226; ".$indvAnswer['response']."</p>";
					}
					echo "</td></tr>";
				}
				echo "</tbody>";
				echo "</table>";
				echo "<br>";
			}
		} catch (PDOException $e) {
			print "Error!" . $e->getMessage() . "<br/>";
			die();
		}
		?>

	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>

<?php

function standard_deviation($aValues, $bSample = false)
{
    $fMean = array_sum($aValues) / count($aValues);
    $fVariance = 0.0;
    foreach ($aValues as $i)
    {
        $fVariance += pow($i - $fMean, 2);
    }
    $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
    return (float) sqrt($fVariance);
}

function getMedian($arr) {
    return (count($arr) > 0) ? array_sum($arr) / count($arr) : 0;
}

?>