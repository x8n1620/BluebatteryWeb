#!/bin/bash
# Script zum Import von History and Tagesstatistiken
# Getestet mit Exports aus der Android-App

DB_HOST=localhost
DB_NAME=bluebattery
DB_USER=<Benutzername> #// -> bitte anpassen!
DB_PASSWORD=<Password> #// -> bitte anpassen!
Importverzeichnis="<Ablagepfad zum den CSV-Exports>"

if [ ! -d "$Importverzeichnis/Archiv" ] ### Ueberpruefung ob Archivverzeichnis vorhanden
then
   echo "erstelle Archivordner im Importverzeichnis"
   mkdir $Importverzeichnis/Archiv/
fi

for file in "$Importverzeichnis"/History-Daten-BlueBatt-2*.csv; do
   if [ -f "$file" ] ; then # Ueberpruefe, ob Importdatei vorhanden
      fileimported=true
      md5_soll='f8c12905bbfbc864d53aabfaf4c9221b' # MD5 erste Zeile der History-Daten-BlueBatt
      md5_ist=$(head -1 $file | md5sum | awk '{ print $1 }')
      if [ $md5_ist = $md5_soll ]; then
         echo "$md5_ist Import-Datei korrekt, fahre fort"
         file=$(basename "$file")
         mysql -h $DB_HOST -u$DB_USER -p$DB_PASSWORD -D $DB_NAME -e "CREATE TEMPORARY TABLE importhistory LIKE history; 
            ALTER TABLE importhistory DROP PRIMARY KEY; 
            ALTER TABLE importhistory MODIFY DATUM varchar(255) NULL;
            LOAD DATA LOCAL INFILE '${Importverzeichnis}/${file}'
               REPLACE INTO TABLE importhistory FIELDS TERMINATED BY ';' IGNORE 1 ROWS
               (Geraeteadresse, Geraetename, @var_date, Solarenergie_Wh, Solarladung_mAh, Solarleistung_W, max_Solarstrom_mA, Ladezeit_Minuten,
               Batterieladestand_mAh, maximaler_Batterieladestand_mAh, minimaler_Batterieladestand_mAh, maximaler_Batteriestrom_mA, minimaler_Batteriestrom_mA,
               max_Batteriespannung_mV, min_Batteriespannung_mV, max_Temp, min_Temp, Externe_Ladung_mAh, Entnahme_mAh, Ladung_mAh, Verbrauch_mAh)
               SET Datum = STR_TO_DATE(@var_date, '%d.%m.%Y');
            INSERT INTO history SELECT * FROM importhistory ON DUPLICATE KEY UPDATE Datum = VALUES(Datum);
            DROP TABLE importhistory;"
         mv $Importverzeichnis/$file $Importverzeichnis/Archiv/   
      else
         echo $md5_ist Import-Datei History nicht im korrekten Format, Abbruch
      fi
   else
       echo "Keine Importdatei vorhanden"
   fi
done
for file in "$Importverzeichnis"/Tag-Daten*.csv; do
   if [ -f "$file" ] ; then # Ueberpruefe, ob Importdatei vorhanden
      fileimported=true
      md5_soll='c1f292f42100afafb0583d91c7a4ac23' # MD5 erste Zeile der Tag-Daten
      md5_ist=$(head -1 $file | md5sum | awk '{ print $1 }')
      if [ $md5_ist = $md5_soll ]; then
         echo "$md5_ist Importdatei Format korrekt, fahre fort"
         file=$(basename "$file")
         mysql -h $DB_HOST -u$DB_USER -p$DB_PASSWORD -D $DB_NAME -e "CREATE TEMPORARY TABLE importdaystat LIKE daystat; 
            ALTER TABLE importdaystat DROP PRIMARY KEY; 
            ALTER TABLE importdaystat MODIFY Datum varchar(255) NULL; 
            ALTER TABLE importdaystat DROP COLUMN datetime;
            LOAD DATA LOCAL INFILE '${Importverzeichnis}/${file}' REPLACE INTO TABLE importdaystat FIELDS TERMINATED BY ';' IGNORE 1 ROWS
               (Geraeteadresse, Geraetename, @var_date, Uhrzeit, Batteriespannung_V, Batteriestrom_A, Batterieladung_Ah, Solarstrom_A, Modus)
               SET Datum = STR_TO_DATE(@var_date, '%d.%m.%Y');
            ALTER TABLE importdaystat ADD COLUMN datetime VARCHAR(255) AFTER Modus;
            UPDATE importdaystat SET datetime=concat(Datum,Uhrzeit);
            INSERT INTO daystat SELECT * FROM importdaystat ON DUPLICATE KEY UPDATE datetime = VALUES(datetime);
            DROP TABLE importdaystat;"
         mv $Importverzeichnis/$file $Importverzeichnis/Archiv/
      else
         echo $md5_ist Importdatei Tages-Daten nicht im korrekten Format, Abbruch
      fi
   else
       echo "Keine Importdatei vorhanden"
   fi
done

if  [ $fileimported ]; then # Trigger fuer post-import-scripts (z. B. filescan nextcloud, nachdem geloescht wurde)
   echo es wurden Dateien importiert
else
   echo es wurde nichts importiert
fi
