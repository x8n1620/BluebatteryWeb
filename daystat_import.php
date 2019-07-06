<?php
require 'inc/db.php';
if(isset($_POST["Import"])) {
   $filename=$_FILES["file"]["tmp_name"];
      if($_FILES["file"]["size"] > 0) {
/*         mysqli_query($db, "CREATE TEMPORARY TABLE importweb LIKE daystat"); */
         mysqli_query($db, "CREATE TABLE importweb LIKE daystat");
         mysqli_query($db, "ALTER TABLE importweb DROP PRIMARY KEY");
         mysqli_query($db, "ALTER TABLE importweb MODIFY datetime varchar(255) NULL");

         $file = fopen($filename, "r");
         $flag = true; // Skip First Line
         while (($getData = fgetcsv($file, 10000, ";")) !== FALSE) {
            if($flag) { $flag = false; continue; } // Skip First Line
            $sql = "INSERT into importweb (Geraeteadresse, Geraetename, Datum, Uhrzeit, Batteriespannung_V, Batteriestrom_A, Batterieladung_Ah, Solarstrom_A, Modus)
               values ('".$getData[0]."','".$getData[1]."','".$getData[2]."','".$getData[3]."','".$getData[4]."','".$getData[5]."','".$getData[6]."',
               '".$getData[7]."','".$getData[8]."')";

            $result = mysqli_query($db, $sql);
               if(!isset($result)) {
                  echo "<script type=\"text/javascript\">
                  alert(\"Invalid File:Please Upload CSV File.\");
                  window.location = \"daystat.php\"
                  </script>";    
               }
               else {
                  echo "<script type=\"text/javascript\">
                  alert(\"CSV File has been successfully Imported.\");
                  window.location = \"daystat.php\"
                  </script>";
               }
         }
         fclose($file);  

		 mysqli_query($db, "update importweb set datetime=concat(Datum,Uhrzeit)");
		 mysqli_query($db, "INSERT INTO daystat
		    SELECT * FROM importweb
		    ON DUPLICATE KEY UPDATE
		    datetime = VALUES(datetime)
		    ");
		 mysqli_query($db, "drop table importweb");

      }
}   
?>
