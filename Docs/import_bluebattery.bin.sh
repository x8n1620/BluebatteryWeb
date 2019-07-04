#!/bin/bash
# Script zum Import von History and Tagesstatistiken
# Getestet mit Exports aus der Android-App

DB_HOST=localhost
DB_NAME=bluebattery
DB_USER=<Benutzername> #// -> bitte anpassen!
DB_PASSWORD=<Password> #// -> bitte anpassen!
Importverzeichnis="<Ablagepfad zum den CSV-Exports>"

for file in "$Importverzeichnis"/History-Daten-BlueBatt-2*.csv; do
	file=$(basename "$file")
	mysql -h $DB_HOST -u$DB_USER -p$DB_PASSWORD -D $DB_NAME -e "CREATE TEMPORARY TABLE importhistory LIKE history; 
		LOAD DATA LOCAL INFILE '${Importverzeichnis}/${file}' REPLACE INTO TABLE importhistory FIELDS TERMINATED BY ';' IGNORE 1 ROWS ;
		INSERT INTO history SELECT * FROM importhistory ON DUPLICATE KEY UPDATE Datum = VALUES(Datum);
		DROP TABLE importhistory;"
	rm -f $Importverzeichnis/$file
	done

for file in "$Importverzeichnis"/Tag-Daten*.csv; do
	file=$(basename "$file")
	mysql -h $DB_HOST -u$DB_USER -p$DB_PASSWORD -D $DB_NAME -e "CREATE TEMPORARY TABLE importdaystat LIKE daystat; 
		ALTER TABLE importdaystat DROP PRIMARY KEY; 
		ALTER TABLE importdaystat MODIFY datetime varchar(255) NULL; 
		ALTER TABLE importdaystat DROP COLUMN datetime;
		LOAD DATA LOCAL INFILE '${Importverzeichnis}/${file}' REPLACE INTO TABLE importdaystat FIELDS TERMINATED BY ';' IGNORE 1 ROWS ;
		ALTER TABLE importdaystat ADD COLUMN datetime VARCHAR(255) AFTER Modus;
		UPDATE importdaystat SET datetime=concat(Datum,Uhrzeit);
		INSERT INTO daystat SELECT * FROM importdaystat ON DUPLICATE KEY UPDATE datetime = VALUES(datetime);
		DROP TABLE importdaystat;"
	rm -f $Importverzeichnis/$file
	done


