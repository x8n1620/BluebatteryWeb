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
