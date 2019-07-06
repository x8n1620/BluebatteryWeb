#!/bin/bash
DB_HOST=localhost
DB_USER=bluebattery
DB_PASSWORD=P@ssw0rd
DB_NAME=bluebattery
Importverzeichnis="/home/manuel/Cloud/nc.fleder.de/Dokumente/BlueBattery/RAW_Import"

for files in "$Importverzeichnis"/Tag-Daten*; do
	file=$(basename "$files")
	mysql -h $DB_HOST -u$DB_USER -p$DB_PASSWORD -D $DB_NAME -e "CREATE TEMPORARY TABLE import LIKE daystat; 
		ALTER TABLE import DROP PRIMARY KEY; 
		ALTER TABLE import MODIFY datetime varchar(255) NULL; 
		ALTER TABLE import DROP COLUMN datetime;
		LOAD DATA LOCAL INFILE '${Importverzeichnis}/${file}' REPLACE INTO TABLE import FIELDS TERMINATED BY ';' IGNORE 1 ROWS ;
		ALTER TABLE import ADD COLUMN datetime VARCHAR(255) AFTER Modus;
		UPDATE import SET datetime=concat(Datum,Uhrzeit);
		INSERT INTO daystat SELECT * FROM import ON DUPLICATE KEY UPDATE datetime = VALUES(datetime);
		DROP TABLE import;"
	done
