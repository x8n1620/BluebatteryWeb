<!doctype html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<meta name="description" content="BlueBattery Day Statistics">
	<title>BlueBattery Day Statistics</title>
	<link rel="stylesheet" href="/css/stylesheet.css">

</head>
<body>
   <table><tbody><tr>
      <td> <input type="text" id="searchdateInput" onkeyup="searchdateFunction()" placeholder="Filter Datum" title="Type in a date"> </td>
      <td> <input type="text" id="searchtimeInput" onkeyup="searchtimeFunction()" placeholder="Filter Uhrzeit" title="Type in a time"> </td>

      <td> <form action="daystat_import.php" method="post" name="upload_excel" enctype="multipart/form-data" id="ImportForm";>
	   <fieldset>
	      <label class="col-md-4 control-label" for="filebutton">Select File:</label>
	      <input type="file" name="file" id="file" class="input-large">
	      <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
	   </fieldset>
	</form> </td>
   </tr></tbody></table>

	<table border="0" id="daystatTable">
		<thead>
			<tr>
				<th colspan="2">Datum</th>
				<th></th>
				<th colspan="4">Batterie</th>
				<th></th>
				<th colspan="1">Solar</th>
			</tr>
			<tr>
				<th>Datum</th>
				<th>Uhrzeit</th>
				<th></th>
				<th>Batteriespannung V</th>
				<th>Batteriestrom A</th>
				<th>Batterieladung Ah</th>
				<th>SOC</th>
				<th></th>
				<th>Solarstrom A</th>
			</tr>
		</thead>
		<tbody>

<?php
	require 'inc/db.php';
	echo "<br />";

	$sql = "SELECT * FROM daystat ORDER BY STR_TO_DATE(Datum, '%d.%m.%Y'), Uhrzeit";
	$result = $db->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
		?><tr>
		<td><?php echo $row["Datum"]; ?></td>
		<td> <?php echo $row["Uhrzeit"]; ?></td>
		<td></td>
		<td><?php echo $row["Batteriespannung_V"]; ?> V</td>
		<td><?php echo $row["Batteriestrom_A"]; ?> A</td>
		<td><?php echo round($row["Batterieladung_Ah"], 0); ?> Ah</td>
		<td><?php echo round(100/160*$row["Batterieladung_Ah"], 0); ?> %</td>
		<td></td>
		<td><?php echo $row["Solarstrom_A"]; ?> A</td>
		</tr>
		<?php
	    }
	} else {
	    echo "0 results";
	}
	$db->close();
	?>
		</tbody>
	</table>
	<script>
		function searchdateFunction() {
		  var input, filter, table, tr, td, i, txtValue;
		  input = document.getElementById("searchdateInput");
		  filter = input.value.toUpperCase();
		  table = document.getElementById("daystatTable");
		  tr = table.getElementsByTagName("tr");
		  for (i = 0; i < tr.length; i++) {
		    td = tr[i].getElementsByTagName("td")[0];
		    if (td) {
		      txtValue = td.textContent || td.innerText;
		      if (txtValue.toUpperCase().indexOf(filter) > -1) {
			tr[i].style.display = "";
		      } else {
			tr[i].style.display = "none";
		      }
		    }       
		  }
		}
	</script>
	<script>
		function searchtimeFunction() {
		  var input, filter, table, tr, td, i, txtValue;
		  input = document.getElementById("searchtimeInput");
		  filter = input.value.toUpperCase();
		  table = document.getElementById("daystatTable");
		  tr = table.getElementsByTagName("tr");
		  for (i = 0; i < tr.length; i++) {
		    td = tr[i].getElementsByTagName("td")[1];
		    if (td) {
		      txtValue = td.textContent || td.innerText;
		      if (txtValue.toUpperCase().indexOf(filter) > -1) {
			tr[i].style.display = "";
		      } else {
			tr[i].style.display = "none";
		      }
		    }       
		  }
		}
	</script>
</body>
</html>
