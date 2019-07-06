<?php
require 'inc/db.php';
if(isset($_POST["Import"])) {
	$filename=$_FILES["file"]["tmp_name"];
		if($_FILES["file"]["size"] > 0) {
			$file = fopen($filename, "r");
			$flag = true; // Skip First Line
			while (($getData = fgetcsv($file, 10000, ";")) !== FALSE) {
			   if($flag) { $flag = false; continue; } // Skip First Line
			   $sql = "INSERT into history (Geraeteadresse,Geraetename,Datum,Solarenergie_Wh,Solarladung_mAh,Solarleistung_W,max_Solarstrom_mA,
					Ladezeit_Minuten,Batterieladestand_mAh,maximaler_Batterieladestand_mAh,minimaler_Batterieladestand_mAh,
					maximaler_Batteriestrom_mA,minimaler_Batteriestrom_mA,max_Batteriespannung_mV,min_Batteriespannung_mV,
					max_Temperatur,min_Temp,Externe_Ladung_mAh,Entnahme_mAh,Ladung_mAh,Verbrauch_mAh)
				values ('".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."','".$getData[4]."','".$getData[5]."','".$getData[6]."',
					'".$getData[7]."','".$getData[8]."','".$getData[9]."','".$getData[10]."','".$getData[11]."','".$getData[12]."','".$getData[13]."',
					'".$getData[14]."','".$getData[15]."','".$getData[16]."','".$getData[17]."','".$getData[18]."','".$getData[19]."','".$getData[20]."')";

			$result = mysqli_query($db, $sql);
				if(!isset($result)) {
					echo "<script type=\"text/javascript\">
					alert(\"Invalid File:Please Upload CSV File.\");
					window.location = \"history.php\"
					</script>";    
				}
				else {
					echo "<script type=\"text/javascript\">
					alert(\"CSV File has been successfully Imported.\");
					window.location = \"history.php\"
					</script>";
				}
			}
			fclose($file);  
	}
}   
?>
