

 <?php
$row1 = array("Wert1.1","Wert1.2");
$row2 = array("Wert2.1","Wert2.2");
$row3 = array("Wert3.1","Wert3.2");

$rows = array($row1,$row2,$row3);

 ?>


<div class="container">
	<table class="table">
		<thead class="thead-dark">
			<tr>
				<th scope="col">#</th>
				<th scope="col">Spalte 1</th>
				<th scope="col">Spalte 2</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$a = 1;
			foreach ($rows as $row) {
				echo '<th scope="row">'. $a . '</th>';
				echo '<td>' . $row[0] . '</td>';
				echo '<td>' . $row[1] . '</td>';
			}
		?>
		</tbody>
	</table>
</div>
