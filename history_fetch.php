<!doctype html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<meta name="description" content="BlueBattery History">
	<title>BlueBatteryWeb History</title>
	<link rel="stylesheet" href="/css/stylesheet.css">
</head>
<body>

<?php
function FunctMintoStd($input) // Funktion zum umformatieren <Min> in <hh:mm> //https://www.pchocker.de/php.php?id=4
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

	require 'inc/db.php';
	$output = '';

if (stripos(($_POST["query"]), 'K') === 0){ // Abfrage KW
	$queryKW = substr(($_POST["query"]), 2); 
	$search = mysqli_real_escape_string($db, $queryKW);
	$query = "
		SELECT * FROM view_history 
		WHERE week_no LIKE '%".$search."%' 
		";
}
elseif (isset($_POST["query"])) { // Abfrage Datum
 $search = mysqli_real_escape_string($db, $_POST["query"]);
 $query = "
  SELECT * FROM view_history 
  WHERE Datum LIKE '%".$search."%'
 ";
//  OR Solarenergie_Wh LIKE '%".$search."%' 
}
else { // Abfrage, wen nichts eingegeben wurde
 $query = "SELECT * FROM view_history WHERE Datum > date_sub(now(), interval 1 month) ORDER BY Datum";
}

$result = mysqli_query($db, $query);
if(mysqli_num_rows($result) > 0)
{
 $output .= '
  <div class="table-responsive">
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
				<th>QΣ KW</th>
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
				<th>ext. Ladung</th>
				<th>Verbrauch</th>
			</tr>
		</thead>
		<tbody>
 ';
 while($row = mysqli_fetch_array($result))
 {
  $output .= '
   <tr>
	 <td align="right">'.date('W', strtotime($row['Datum'])).'</td>
    <td align="right">'.$row['SUM(Solarenergie_Wh)']."Wh".'</td>
    <td align="right">'.date('d.m.Y', strtotime($row['Datum'])).'</td>
    <td>'." ".'</td>
    <td align="right">'.round($row["max_Batteriespannung_mV"]/1000, 1)."V".'</td>
    <td align="right">'.round($row["min_Batteriespannung_mV"]/1000, 1)."V".'</td>
    <td align="right">'.round($row["maximaler_Batterieladestand_mAh"]/1000, 0)."Ah".'</td>
    <td align="right">'.round($row["minimaler_Batterieladestand_mAh"]/1000, 0)."Ah".'</td>
    <td align="right">'.round(100/160000*$row["maximaler_Batterieladestand_mAh"], 0)."%".'</td>
    <td align="right">'.round(100/160000*$row["minimaler_Batterieladestand_mAh"], 0)."%".'</td>
    <td align="right">'.round($row["maximaler_Batteriestrom_mA"]/1000, 0)."A".'</td>
    <td align="right">'.round($row["minimaler_Batteriestrom_mA"]/1000, 0)."A".'</td>
    <td>'." ".'</td>
    <td align="right">'.round($row["Solarladung_mAh"]/1000, 0)."Ah".'</td>
    <td align="right">'.round($row["Solarenergie_Wh"], 0)."Wh".'</td>
    <td align="right">'.round($row["max_Solarstrom_mA"]/1000, 0)."A".'</td>
    <td align="right">'.round($row["Solarleistung_W"], 0)."W".'</td>
    <td align="right">'.FunctMintoStd($row["Ladezeit_Minuten"])."h".'</td>
    <td>'."".'</td>
    <td align="right">'.round($row["max_Temp"]/100+6, 1)."°C".'</td>
    <td align="right">'.round($row["min_Temp"]/100+6, 1)."°C".'</td>
    <td>'."".'</td>
    <td align="right">'.round($row["Entnahme_mAh"]/1000, 0)."Ah".'</td>
    <td align="right">'.round($row["Ladung_mAh"]/1000, 0)."Ah".'</td>
    <td align="right">'.round($row["Externe_Ladung_mAh"]/1000, 0)."Ah".'</td>
    <td align="right">'.round($row["Verbrauch_mAh"]/1000, 0)."Ah".'</td>
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
