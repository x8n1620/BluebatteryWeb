#!/bin/bash
Importverzeichnis="/home/manuel/Cloud/nc.fleder.de/Dokumente/BlueBattery/RAW_Import"

for files in "$Importverzeichnis"/Tag-Daten*; do
	file=$(basename "$files")
	echo "durchgang $file"
	ls -l $Importverzeichnis/$file
done

