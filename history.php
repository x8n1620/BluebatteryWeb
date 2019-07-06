<!doctype html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<meta name="description" content="BlueBattery History">
	<title>BlueBattery History</title>
	<link rel="stylesheet" href="/css/stylesheet.css">

</head>
<body>
<?php
//https://www.pchocker.de/php.php?id=4
function FunctMintoStd($input)
{
    $stunden = floor($input / 60);
    $minuten = floor($input - ($stunden * 60));

    if ($stunden <= 9) {
        $strStunden = "0" . $stunden;
    } else {
        $strStunden = $stunden;
    }

    if ($minuten <= 9) {
        $strMinuten = "0" . $minuten;
    } else {
        $strMinuten = $minuten;
    }

    return "$strStunden:$strMinuten";
}
?>

   <table><tbody><tr>
      <td> <input type="text" id="searchdateInput" onkeyup="searchdateFunction()" placeholder="Filter Datum" title="Type in a date"> </td>

      <td> <form class="form-horizontal" action="history_import.php" method="post" name="upload_excel" enctype="multipart/form-data" id="ImportForm">
	 <fieldset>
	    <label class="col-md-4 control-label" for="filebutton">Select File for import</label>
	    <input type="file" name="file" id="file" class="input-large">
	    <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
	 </fieldset>
	</form> </td>
   </tr></tbody></table>

	<table border="0" id="historyTable">
		<thead>
			<tr>
				<th colspan="3">Datum</th>
				<th></th>
				<th colspan="8">Batterie</th>
				<th></th>
				<th colspan="5">Solar</th>
				<th></th>
				<th colspan="2">Temp</th>
				<th></th>
				<th colspan="4">Berechnete Werte</th>
			</tr>
			<tr>
				<th>KW</th>
				<th>Q Ø</th>
				<th>Datum</th>
				<th></th>
				<th>Umax</th>
				<th>Umin</th>
				<th>Qmax</th>
				<th>Qmin</th>
				<th>SOC max</th>
				<th>SOC min</th>
				<th>Imax+</th>
				<th>Imax-</th>
				<th></th>
				<th>QΣ</th>
				<th>QΣ</th>
				<th>Imax</th>
				<th>Pmax</th>
				<th>t</th>
				<th></th>
				<th>ϑmax</th>
				<th>ϑmin</th>
				<th></th>
				<th>Entnahme</th>
				<th>Ladung</th>
				<th>externe Ladung</th>
				<th>Verbrauch</th>
			</tr>
		</thead>
		<tbody>

<?php
	require 'inc/db.php';
	echo "<br />";

//	$sql = "SELECT * FROM history WHERE MONTH( Datum ) = '04' ORDER BY STR_TO_DATE(Datum, '%d.%m.%Y')";
//	$sql = "SELECT * FROM history WHERE Datum = '31.03.2019' ORDER BY STR_TO_DATE(Datum, '%d.%m.%Y')";
	$sql = "SELECT * FROM history ORDER BY STR_TO_DATE(Datum, '%d.%m.%Y')";
	$result = $db->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
		?><tr>
		<td>"KW"</td>
		<td>"Ø Wh"</td>
		<td align="right"><?php echo $row["Datum"]; ?></td>
		<td align="right"></td>
		<td align="right"><?php echo round($row["max_Batteriespannung_mV"]/1000, 1); ?>V</td>
		<td align="right"><?php echo round($row["min_Batteriespannung_mV"]/1000, 1); ?>V</td>
		<td align="right"><?php echo round($row["maximaler_Batterieladestand_mAh"]/1000, 0); ?>Ah</td>
		<td align="right"><?php echo round($row["minimaler_Batterieladestand_mAh"]/1000, 0); ?>Ah</td>
		<td align="right"><?php echo round(100/160000*$row["maximaler_Batterieladestand_mAh"], 0); ?>%</td>
		<td align="right"><?php echo round(100/160000*$row["minimaler_Batterieladestand_mAh"], 0); ?>%</td>
		<td align="right"><?php echo round($row["maximaler_Batteriestrom_mA"]/1000, 0); ?>A</td>
		<td align="right"><?php echo round($row["minimaler_Batteriestrom_mA"]/1000, 0); ?>A</td>
		<td align="right"></td>
		<td align="right"><?php echo round($row["Solarladung_mAh"]/1000, 0); ?>Ah</td>
		<td align="right"><?php echo round($row["Solarenergie_Wh"], 0); ?>Wh</td>
		<td align="right"><?php echo round($row["max_Solarstrom_mA"]/1000, 0); ?>A</td>
		<td align="right"><?php echo round($row["Solarleistung_W"], 0); ?>W</td>
		<td align="right"><?php echo FunctMintoStd($row["Ladezeit_Minuten"]); ?>h</td> 
		<td align="right"></td>
		<td align="right"><?php echo round($row["max_Temp"]/100+6, 1); ?>°C</td>
		<td align="right"><?php echo round($row["min_Temp"]/100+6, 1); ?>°C</td>
		<td></td>
		<td align="right"><?php echo round($row["Entnahme_mAh"]/1000, 0); ?>Ah</td>
		<td align="right"><?php echo round($row["Ladung_mAh"]/1000, 0); ?>Ah</td>
		<td align="right"><?php echo round($row["Externe_Ladung_mAh"]/1000, 0); ?>Ah</td>
		<td align="right"><?php echo round($row["Verbrauch_mAh"]/1000, 0); ?>Ah</td>
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
		  table = document.getElementById("historyTable");
		  tr = table.getElementsByTagName("tr");
		  for (i = 0; i < tr.length; i++) {
		    td = tr[i].getElementsByTagName("td")[2];
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
