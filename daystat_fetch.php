<!doctype html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<meta name="description" content="BlueBattery Daystat">
	<title>BlueBatteryWeb Daystat</title>
	<link rel="stylesheet" href="/css/stylesheet.css">
</head>
<body>

<?php
	require 'inc/db.php';
	$output = '';
if (isset($_POST["query"])) { // Abfrage Datum
 $search = mysqli_real_escape_string($db, $_POST["query"]);
 $query = "
  SELECT * FROM daystat 
  WHERE datetime LIKE '%".$search."%'
  OR Uhrzeit LIKE '%".$search."%' 
  ORDER BY Datum, Uhrzeit
 ";
//  OR Solarenergie_Wh LIKE '%".$search."%' 
}
else { // Abfrage, wen nichts eingegeben wurde
 $query = "SELECT * FROM daystat WHERE Datum > date_sub(now(), interval 1 week) ORDER BY Datum, Uhrzeit";
}

$result = mysqli_query($db, $query);

if(mysqli_num_rows($result) > 0)
{
  $output .= '
  <div class="table-responsive">
	<table border="0" id="historyTable">
		<thead>
			<tr>
				<th colspan="2">Datum</th>
				<th></th>
				<th colspan="4">Batterie</th>
				<th></th>
				<th>Solar</th>
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
 ';
 while($row = mysqli_fetch_array($result))
 {
  $output .= '
   <tr>
	 <td align="right">'.date('d.m.Y', strtotime($row['Datum'])).'</td>
   <td align="right">'.$row["Uhrzeit"].'</td>
    <td>'." ".'</td>
    <td align="right">'.$row["Batteriespannung_V"]."V".'</td>
    <td align="right">'.$row["Batteriestrom_A"]."A".'</td>
    <td align="right">'.round($row["Batterieladung_Ah"], 0)."Ah".'</td>
    <td align="right">'.round(100/160*$row["Batterieladung_Ah"], 0)."%".'</td>
    <td>'." ".'</td>
    <td align="right">'.$row["Solarstrom_A"]."A".'</td>
   </tr>
  ';
 }
  $output .='
	</table>
	</div>
  ';
 echo $output;
}
else
{
 echo 'Data Not Found';
}

?>
</body>
</html>
