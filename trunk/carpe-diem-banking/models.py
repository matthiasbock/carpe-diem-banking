# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#     * Rearrange models' order
#     * Make sure each model has one field with primary_key=True
# Feel free to rename the models, but don't rename db_table values or field names.
#
# Also note: You'll have to insert the output of 'django-admin.py sqlcustom [appname]'
# into your database.

from django.db import models

class Autorisierteips(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    name = models.CharField(max_length=60, db_column='Name') # Field name made lowercase.
    ip = models.CharField(max_length=45, db_column='IP') # Field name made lowercase.
    class Meta:
        db_table = u'AutorisierteIPs'

class Betreuer(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    vorname = models.CharField(max_length=150, db_column='Vorname') # Field name made lowercase.
    nachname = models.CharField(max_length=150, db_column='Nachname') # Field name made lowercase.
    email = models.CharField(max_length=150, db_column='Email') # Field name made lowercase.
    farbe = models.CharField(max_length=45, db_column='Farbe') # Field name made lowercase.
    klientenkasse = models.IntegerField(null=True, db_column='Klientenkasse', blank=True) # Field name made lowercase.
    auth_user_id = models.IntegerField()
    administrator = models.IntegerField(db_column='Administrator') # Field name made lowercase.
    nurlesen = models.IntegerField(db_column='NurLesen') # Field name made lowercase.
    class Meta:
        db_table = u'Betreuer'

class Girokonten(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    kontoname = models.CharField(max_length=150, db_column='Kontoname') # Field name made lowercase.
    kontonummer = models.IntegerField(db_column='Kontonummer') # Field name made lowercase.
    blz = models.IntegerField(db_column='BLZ') # Field name made lowercase.
    kreditinstitut = models.CharField(max_length=150, db_column='Kreditinstitut') # Field name made lowercase.
    synchronisieren = models.IntegerField(db_column='Synchronisieren') # Field name made lowercase.
    hbci_pin = models.IntegerField(null=True, db_column='HBCI_PIN', blank=True) # Field name made lowercase.
    synchronisationsstartdatum = models.DateField(null=True, db_column='SynchronisationsStartdatum', blank=True) # Field name made lowercase.
    synchronisationsstartsaldo = models.DecimalField(decimal_places=2, null=True, max_digits=10, db_column='SynchronisationsStartsaldo', blank=True) # Field name made lowercase.
    class Meta:
        db_table = u'Girokonten'

class Girokontenumsaetze(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    girokonto = models.IntegerField(db_column='Girokonto') # Field name made lowercase.
    datum = models.DateField(db_column='Datum') # Field name made lowercase.
    betrag = models.DecimalField(decimal_places=2, max_digits=10, db_column='Betrag') # Field name made lowercase.
    verwendungszweck = models.CharField(max_length=900, db_column='Verwendungszweck') # Field name made lowercase.
    betreuer = models.IntegerField(null=True, db_column='Betreuer', blank=True) # Field name made lowercase.
    klientenkasse = models.IntegerField(null=True, db_column='Klientenkasse', blank=True) # Field name made lowercase.
    istgeldeingang = models.IntegerField(db_column='IstGeldeingang') # Field name made lowercase.
    istabhebung = models.IntegerField(db_column='IstAbhebung') # Field name made lowercase.
    isteinzahlung = models.IntegerField(db_column='IstEinzahlung') # Field name made lowercase.
    istklientenueberweisung = models.IntegerField(db_column='IstKlientenueberweisung') # Field name made lowercase.
    klientenueberweisung = models.IntegerField(null=True, db_column='Klientenueberweisung', blank=True) # Field name made lowercase.
    class Meta:
        db_table = u'Girokontenumsaetze'

class Klienten(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    vorname = models.CharField(max_length=150, db_column='Vorname') # Field name made lowercase.
    nachname = models.CharField(max_length=150, db_column='Nachname') # Field name made lowercase.
    betreuer = models.IntegerField(null=True, db_column='Betreuer', blank=True) # Field name made lowercase.
    startdatum = models.DateField(null=True, db_column='Startdatum', blank=True) # Field name made lowercase.
    startsaldo = models.DecimalField(decimal_places=2, null=True, max_digits=10, db_column='Startsaldo', blank=True) # Field name made lowercase.
    zugeordnetesgirokonto = models.IntegerField(null=True, db_column='ZugeordnetesGirokonto', blank=True) # Field name made lowercase.
    eigenesgirokonto = models.IntegerField(null=True, db_column='EigenesGirokonto', blank=True) # Field name made lowercase.
    class Meta:
        db_table = u'Klienten'

class Klientenauszahlungen(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    datum = models.DateField(db_column='Datum') # Field name made lowercase.
    betreuer = models.IntegerField(db_column='Betreuer') # Field name made lowercase.
    betrag = models.DecimalField(decimal_places=2, max_digits=10, db_column='Betrag') # Field name made lowercase.
    ausklientenkasse = models.IntegerField(db_column='AusKlientenkasse') # Field name made lowercase.
    anklient = models.IntegerField(db_column='AnKlient') # Field name made lowercase.
    class Meta:
        db_table = u'Klientenauszahlungen'

class Klienteneinzahlungen(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    datum = models.DateField(db_column='Datum') # Field name made lowercase.
    klient = models.IntegerField(db_column='Klient') # Field name made lowercase.
    betrag = models.DecimalField(decimal_places=2, max_digits=10, db_column='Betrag') # Field name made lowercase.
    anbetreuer = models.IntegerField(db_column='AnBetreuer') # Field name made lowercase.
    intresor = models.IntegerField(db_column='InTresor') # Field name made lowercase.
    class Meta:
        db_table = u'Klienteneinzahlungen'

class Klientenkassen(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    name = models.CharField(max_length=300, db_column='Name') # Field name made lowercase.
    startdatum = models.DateField(null=True, db_column='Startdatum', blank=True) # Field name made lowercase.
    startsaldo = models.DecimalField(decimal_places=2, null=True, max_digits=10, db_column='Startsaldo', blank=True) # Field name made lowercase.
    class Meta:
        db_table = u'Klientenkassen'

class Klientenueberweisungen(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    datum = models.DateField(db_column='Datum') # Field name made lowercase.
    betreuter = models.IntegerField(db_column='Betreuter') # Field name made lowercase.
    fuerklient = models.IntegerField(db_column='FuerKlient') # Field name made lowercase.
    betrag = models.DecimalField(decimal_places=2, max_digits=10, db_column='Betrag') # Field name made lowercase.
    an = models.CharField(max_length=300, db_column='An') # Field name made lowercase.
    kontonummer = models.IntegerField(db_column='Kontonummer') # Field name made lowercase.
    blz = models.IntegerField(db_column='BLZ') # Field name made lowercase.
    kreditinstitut = models.CharField(max_length=300, db_column='Kreditinstitut') # Field name made lowercase.
    verwendungszweck = models.CharField(max_length=900, db_column='Verwendungszweck') # Field name made lowercase.
    tilgtforderung = models.IntegerField(null=True, db_column='TilgtForderung', blank=True) # Field name made lowercase.
    auftragausgefuehrt = models.IntegerField(db_column='AuftragAusgefuehrt') # Field name made lowercase.
    ausfuehrungsdatum = models.DateTimeField(null=True, db_column='Ausfuehrungsdatum', blank=True) # Field name made lowercase.
    class Meta:
        db_table = u'Klientenueberweisungen'

class Schulden(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    datum = models.DateField(db_column='Datum') # Field name made lowercase.
    klient = models.IntegerField(db_column='Klient') # Field name made lowercase.
    betrag = models.DecimalField(decimal_places=2, max_digits=10, db_column='Betrag') # Field name made lowercase.
    details = models.CharField(max_length=900, db_column='Details') # Field name made lowercase.
    class Meta:
        db_table = u'Schulden'

class Zuteilungen(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    datum = models.DateField(db_column='Datum') # Field name made lowercase.
    betreuer = models.IntegerField(db_column='Betreuer') # Field name made lowercase.
    teilbetrag = models.DecimalField(decimal_places=2, max_digits=10, db_column='Teilbetrag') # Field name made lowercase.
    anklient = models.IntegerField(db_column='AnKlient') # Field name made lowercase.
    class Meta:
        db_table = u'Zuteilungen'

class AuthGroup(models.Model):
    id = models.IntegerField(primary_key=True)
    name = models.CharField(unique=True, max_length=240)
    class Meta:
        db_table = u'auth_group'

class AuthGroupPermissions(models.Model):
    id = models.IntegerField(primary_key=True)
    group_id = models.IntegerField(unique=True)
    permission_id = models.IntegerField()
    class Meta:
        db_table = u'auth_group_permissions'

class AuthMessage(models.Model):
    id = models.IntegerField(primary_key=True)
    user_id = models.IntegerField()
    message = models.TextField()
    class Meta:
        db_table = u'auth_message'

class AuthPermission(models.Model):
    id = models.IntegerField(primary_key=True)
    name = models.CharField(max_length=150)
    content_type_id = models.IntegerField()
    codename = models.CharField(unique=True, max_length=300)
    class Meta:
        db_table = u'auth_permission'

class AuthUser(models.Model):
    id = models.IntegerField(primary_key=True)
    username = models.CharField(unique=True, max_length=90)
    first_name = models.CharField(max_length=90)
    last_name = models.CharField(max_length=90)
    email = models.CharField(max_length=225)
    password = models.CharField(max_length=384)
    is_staff = models.IntegerField()
    is_active = models.IntegerField()
    is_superuser = models.IntegerField()
    last_login = models.DateTimeField()
    date_joined = models.DateTimeField()
    class Meta:
        db_table = u'auth_user'

class AuthUserGroups(models.Model):
    id = models.IntegerField(primary_key=True)
    user_id = models.IntegerField(unique=True)
    group_id = models.IntegerField()
    class Meta:
        db_table = u'auth_user_groups'

class AuthUserUserPermissions(models.Model):
    id = models.IntegerField(primary_key=True)
    user_id = models.IntegerField(unique=True)
    permission_id = models.IntegerField()
    class Meta:
        db_table = u'auth_user_user_permissions'

class DjangoAdminLog(models.Model):
    id = models.IntegerField(primary_key=True)
    action_time = models.DateTimeField()
    user_id = models.IntegerField()
    content_type_id = models.IntegerField(null=True, blank=True)
    object_id = models.TextField(blank=True)
    object_repr = models.CharField(max_length=600)
    action_flag = models.IntegerField()
    change_message = models.TextField()
    class Meta:
        db_table = u'django_admin_log'

class DjangoContentType(models.Model):
    id = models.IntegerField(primary_key=True)
    name = models.CharField(max_length=300)
    app_label = models.CharField(unique=True, max_length=300)
    model = models.CharField(unique=True, max_length=300)
    class Meta:
        db_table = u'django_content_type'

class DjangoSession(models.Model):
    session_key = models.CharField(max_length=120, primary_key=True)
    session_data = models.TextField()
    expire_date = models.DateTimeField()
    class Meta:
        db_table = u'django_session'

class DjangoSite(models.Model):
    id = models.IntegerField(primary_key=True)
    domain = models.CharField(max_length=300)
    name = models.CharField(max_length=150)
    class Meta:
        db_table = u'django_site'

class RegistrationRegistrationprofile(models.Model):
    id = models.IntegerField(primary_key=True)
    user_id = models.IntegerField(unique=True)
    activation_key = models.CharField(max_length=120)
    class Meta:
        db_table = u'registration_registrationprofile'

