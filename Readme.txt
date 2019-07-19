Webanwendung zur Darstellung von Bluebattery-Daten, die mit der AndroidApp exportiert wurden.
Diese Webanwendung wurde privat entwickelt und steht nicht in Verbindung mit den Marken BlueSolar und BlueBattery.

Infos zur notwendigen Hardware und App erhalten sie auf der komerziellen Webseite von Kai Scheffer: 
https://www.blue-battery.com/

Changelog:
	04.07.2019
		- Projekt veröffentlicht
	06.07.2019:
		- Fehlerhafte Einheiten auf der History-Seite korrigiert
		- Anzeite t Solar als hh:mm
		- Projekt unter AGPLv3.0 gestellt 
	18.07.2019
		- Konvertierung Datumsformat beim Import auf SQL-Standard (Achtung, Format in der DB angepasst. Alte Eintraege manuell konvertieren oder neu importieren)
	19.07.2019
		- DB Views eingebaut als Vorbereitung auf die KW- und Wochen-Ertrags-Ansicht
		- Spalte KW und Wochenertrag in der history.php 
		- Dateiueberpruefung in import_bluebattery.bin.sh

Benkannte Probleme / Dinge die noch fehlen:
	- Validierung der zu importierenden CSV. Da im Augenblick keine Prüfung erfolgt, 
		kann man sich mit einer fehlerhaften CSV die Datenbank voll muellen
		-> erledigt f. Scriptgesteuerten import. Browserbassierend steht aus.
	- Filter der Tagesstatistik noch nicht optimal, zwei Filter (Datum + Uhrzeit) gleichzeitig noch nicht möglich
	- Unterstützung von Eier-Fon Exports

Hinweis:
	Getestet wurde mit CentOS 7 und Fedora 30, Apache 2.4, PHP 7.2 und MariaDB 10 sowie der Android App

	Die notwendigen DB-Views bedingen mindestens MariaDB 8. Versionen darunter (5.5) unterstützen keine selects in Views, 
	hier müssten mehrere "Zwischenviews" gebaut werden
	CentOS 7.7 hat standardmaessig MariaDB 5.5 installiert, hier kann ueber das Repository von MariaDB aktualisiert werden:
        https://downloads.mariadb.org/mariadb/repositories/#mirror=herrbischoff&distro=CentOS&distro_release=centos7-amd64--centos7&version=10.4

    Dieses Programm ist Freie Software: Sie können es unter den Bedingungen
    der GNU Affero General Public License, wie von der Free Software Foundation,
    Version 3 der Lizenz oder (nach Ihrer Wahl) jeder neueren
    veröffentlichten Version, weiter verteilen und/oder modifizieren.

    Dieses Programm wird in der Hoffnung bereitgestellt, dass es nützlich sein wird, jedoch
    OHNE JEDE GEWÄHR,; sogar ohne die implizite
    Gewähr der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
    Siehe die GNU General Public License für weitere Einzelheiten.

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
