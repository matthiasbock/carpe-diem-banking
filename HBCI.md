# HBCI #

Die Klientenverwaltung enthält u.a. Skripte, die einen automatischen Abgleich mit Girokonten bei einer HBCI-fähigen Bank ermöglicht.

## Getestete HBCI-Banken ##
### DKB, Deutsche Kreditbank ###

## Workflow ##
Die Skripte werden von der Kommandozeile oder z.B. als täglicher cronjob ausgeführt. Sie stellen eine gesicherte HBCI-Verbindung zur Bank her und laden alle verfügbaren Umsatz-Daten herunter. Diese werden dann mit der Datenbank abgeglichen und ggf. in letztere eingetragen.

### Synchronisierung ###
Umsätze, die noch nicht in der Datenbank vorhanden sind, können ein Ereignis auslösen, z.B. den Versand einer Mail mit den Details, wie etwa _"Neue Lastschrift: EUR 33,00 an ALDI"_.

## Software ##
Die Skripte sind in **Python** verfasst und nutzen [AqBanking](http://www.aquamaniac.de/sites/aqbanking/index.php).