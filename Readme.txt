Webanwendung zur Darstellung von Bluebattery-Daten, die mit der AndroidApp exportiert wurden.
Diese Webanwendung wurde privat entwickelt und steht nicht in Verbindung mit den Marken BlueSolar und BlueBattery.

---- Achtung ----
Das Projekt befindet sich noch im Entwicklungsstadium
Aenderungen an generellen Dingen wie der Datenbank sind jederzeit moeglich
Nachtraegliche Aenderungen, die manuelle Anpassungen erfordern, werden im Changelog dokumentiert

Infos zur notwendigen Hardware und App erhalten sie auf der komerziellen Webseite von Kai Scheffer: 
https://www.blue-battery.com/

Changelog:
	04.07.2019
		- Projekt veroeffentlicht
	06.07.2019:
		- Fehlerhafte Einheiten auf der History-Seite korrigiert
		- Anzeite t Solar als hh:mm
		- Projekt unter AGPLv3.0 gestellt 
	18.07.2019
		- Konvertierung Datumsformat beim Import auf SQL-Standard 
		  (Achtung, Format in der DB angepasst. Alte Eintraege manuell konvertieren oder neu importieren)
	19.07.2019
		- DB Views eingebaut als Vorbereitung auf die KW- und Wochen-Ertrags-Ansicht
		- Spalte KW und Wochenertrag in der history.php 
		- Dateiueberpruefung in import_bluebattery.bin.sh
	06.09.2019
		- Import-Format auf die Firmware Version v307 angepasst 
	07.09.2019
		- Suchfeld History auf SQL-Query umgebaut
		- History zeigt im Standard nur noch den letzten Monat an, ueber die Suche ist der Zugriff auf aeltere Daten moeglich
		- Daystat zeigt im Standard nur noch den letzte Woche an, ueber die Suche ist der Zugriff auf aeltere Daten moeglich
		- Daystat.datetime Format zwecks Durchsuchbarkeit angepasst. Bestands-DB muessen angepasst werden:
			UPDATE daystat SET datetime=CONCAT_WS(' ',Datum,Uhrzeit)

Benkannte Probleme / Dinge die noch fehlen:
	- Verzoegerung bei der Eingabe eines Filters
	- Validierung der zu importierenden CSV. Da im Augenblick keine Pruefung erfolgt, 
		kann man sich mit einer fehlerhaften CSV die Datenbank voll muellen
		-> erledigt f. Scriptgesteuerten import. Browserbassierend steht aus.
	- Unterstuetzung von Eier-Fon Exports
	- Fixieren der ersten Zeile, damit die Ueberschrift lesbar bleibt
	- Darstellung Kalenderwoche/Wochenentrag noch nicht schick

Hinweis:
	Getestet wurde mit CentOS 7 und Fedora 30, Apache 2.4, PHP 7.2 und MariaDB 10 sowie der Android App

	Die notwendigen DB-Views bedingen mindestens MariaDB 8. Versionen darunter (5.5) unterstuetzen keine selects in Views, 
	hier muessten mehrere "Zwischenviews" gebaut werden
	CentOS 7 hat standardmaessig MariaDB 5.5 installiert, kann jecoh ueber das Repository von MariaDB aktualisiert werden:
        https://downloads.mariadb.org/mariadb/repositories/#mirror=herrbischoff&distro=CentOS&distro_release=centos7-amd64--centos7&version=10.4

    Dieses Programm ist Freie Software: Sie koennen es unter den Bedingungen
    der GNU Affero General Public License, wie von der Free Software Foundation,
    Version 3 der Lizenz oder (nach Ihrer Wahl) jeder neueren
    veroeffentlichten Version, weiter verteilen und/oder modifizieren.

    Dieses Programm wird in der Hoffnung bereitgestellt, dass es nuetzlich sein wird, jedoch
    OHNE JEDE GEWAEHR,; sogar ohne die implizite
    Gewaehr der MARKTFAEHIGKEIT oder EIGNUNG FUER EINEN BESTIMMTEN ZWECK.
    Siehe die GNU General Public License fuer weitere Einzelheiten.

    Eine Kopie der GNU Affero General Public License erhalten Sie unter
    <https://www.gnu.org/licenses/>.

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    under <http://www.gnu.org/licenses/>.
