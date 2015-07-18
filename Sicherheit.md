# Sicherheit #

Nur von der Verwaltung autorisierte Personen und Computer können die Klientenverwaltung benutzen. Die Authentifizierung ist so einfach, wie möglich gestaltet.

  * Login:
    * nur authorisierte Personen: Benutzername, Passwort
    * optional IP-Filter: Zugriff nur von befugten IP-Adressen möglich
    * optional: [SSL Client Authentication](ClientAuthentication.md), d.h. an einzelnen, befugten PCs werden Benutzer automatisch eingeloggt
    * optional: OpenID-Login
    * optional: Login mit dem Carpe Diem Email-Account-Passwort
  * verschiedene Typen von Benutzern:
    * Sozialarbeiter: sehen ihre eigenen Klienten ganz oben, aber auch die ihrer Kollegen
    * Verwaltung: sehen alle Konten, haben aber nur Lese-Zugriff
  * verschlüsselte Datenübertragung mit SSL über HTTPS
  * Internet-Banking über HBCI

![http://carpe-diem-banking.googlecode.com/svn/doc/Sicherheit.png](http://carpe-diem-banking.googlecode.com/svn/doc/Sicherheit.png)