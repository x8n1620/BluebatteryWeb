#!/bin/bash
# Script zum erstellen der mySQL-Datenbank fuer die BluebatteryWeb-Anwendung

DB_HOST=localhost
DB_ROOT=root
DB_ROOTPW=<Password> #// root-Passwort -> bitte anpassen!

DB_NAME=bluebattery
DB_USER=<Benutzername> #// -> bitte anpassen!
DB_PASSWORD=<Password> #// -> bitte anpassen!

mysql -h $DB_HOST -u$DB_ROOT -p$DB_ROOTPW -e "CREATE DATABASE ${DB_NAME};
	CREATE USER '${DB_USER}'@'${DB_HOST}' IDENTIFIED BY '${DB_PASSWORD}';
	GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'${DB_HOST}' IDENTIFIED BY '${DB_PASSWORD}' WITH GRANT OPTION;
	FLUSH PRIVILEGES;"

echo 'Table history anlegen';
mysql -h $DB_HOST -u$DB_USER -p$DB_PASSWORD -D $DB_NAME -e "CREATE TABLE history (
	Geraeteadresse VARCHAR(255), 
	Geraetename VARCHAR(255), 
	Datum VARCHAR(255) NOT NULL, 
	Solarenergie_Wh VARCHAR(255), 
	Solarladung_mAh VARCHAR(255), 
	Solarleistung_W VARCHAR(255),
	max_Solarstrom_mA VARCHAR(255),
	Ladezeit_Minuten VARCHAR(255),
	Batterieladestand_mAh VARCHAR(255),
	maximaler_Batterieladestand_mAh VARCHAR(255),
	minimaler_Batterieladestand_mAh VARCHAR(255),
	maximaler_Batteriestrom_mA VARCHAR(255),
	minimaler_Batteriestrom_mA VARCHAR(255),
	max_Batteriespannung_mV VARCHAR(255),
	min_Batteriespannung_mV VARCHAR(255),
	max_Temp VARCHAR(255),
	min_Temp VARCHAR(255),
	Externe_Ladung_mAh VARCHAR(255),
	Entnahme_mAh VARCHAR(255),
	Ladung_mAh VARCHAR(255),
	Verbrauch_mAh VARCHAR(255),
	PRIMARY KEY (datum)
	);"

echo 'Table daystat anlegen';
mysql -h $DB_HOST -u$DB_USER -p$DB_PASSWORD -D $DB_NAME -e "CREATE TABLE daystat ( 
	Geraeteadresse VARCHAR(255),
	Geraetename VARCHAR(255),
	Datum VARCHAR(255) NOT NULL,
	Uhrzeit VARCHAR(255) NOT NULL,
	Batteriespannung_V VARCHAR(255),
	Batteriestrom_A VARCHAR(255),
	Batterieladung_Ah VARCHAR(255),
	Solarstrom_A VARCHAR(255),
	Modus VARCHAR(255),
	datetime VARCHAR(255),
	PRIMARY KEY (datetime)
	);"

echo "	Die Views bedingen mindestens MariaDB 8. Versionen darunter (5.5) unterstützen keine selects in Views, hier müssen mehrere \"Zwischenviews\" gebaut werden \n 
	CentOS 7.7 hat standardmaessig 5.5 installiert, hier kann ueber das Repository von MariaDB aktualisiert werden: \n
        https://downloads.mariadb.org/mariadb/repositories/#mirror=herrbischoff&distro=CentOS&distro_release=centos7-amd64--centos7&version=10.4"
echo "view_daycount bauen aus der history"
mysql -h $DB_HOST -u$DB_USER -p$DB_PASSWORD -D $DB_NAME -e "CREATE VIEW view_daycount AS
	SELECT * FROM history JOIN (SELECT WEEK(Datum, 3) week_no, COUNT(*) daycount FROM history GROUP BY WEEK(Datum, 3)) AS daycount 
	WHERE WEEK (Datum, 3) = week_no ORDER BY Datum ;"
echo "view_firstweekday bauen"
mysql -h $DB_HOST -u$DB_USER -p$DB_PASSWORD -D $DB_NAME -e 'CREATE VIEW view_firstweekday AS
	SELECT Datum AS FirstWeekDay from view_daycount INNER JOIN  ( SELECT MIN(Datum) AS first_id, "week_no"
	AS week_no2 FROM view_daycount  GROUP BY `week_no` ) AS filter ON filter.first_id = view_daycount.Datum ;'
echo "view_History bauen aus Table history mit daycount (view_daycount), view_firstweekday dazu, Summe der Solarleistung dazu"
mysql -h $DB_HOST -u$DB_USER -p$DB_PASSWORD -D $DB_NAME -e "CREATE VIEW view_history AS
	SELECT * FROM view_daycount 
	LEFT JOIN view_firstweekday ON view_daycount.Datum = view_firstweekday.FirstWeekDay 
	LEFT JOIN ( SELECT Datum AS SolSumDatum, SUM(Solarenergie_Wh) FROM history GROUP BY WEEK(Datum, 3)) as SolSum ON view_daycount.Datum = SolSumDatum ;"
