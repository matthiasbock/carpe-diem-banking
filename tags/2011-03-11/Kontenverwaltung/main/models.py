# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#     * Rearrange models' order
#     * Make sure each model has one field with primary_key=True
# Feel free to rename the models, but don't rename db_table values or field names.
#
# Also note: You'll have to insert the output of 'django-admin.py sqlcustom [appname]'
# into your database.

from django.db import models

class AuthGroup(models.Model):
    id = models.IntegerField(primary_key=True)
    name = models.CharField(unique=True, max_length=240)
    class Meta:
        db_table = u'auth_group'

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

class DjangoSession(models.Model):
    session_key = models.CharField(max_length=120, primary_key=True)
    session_data = models.TextField()
    expire_date = models.DateTimeField()
    class Meta:
        db_table = u'django_session'

class Betreute(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    vorname = models.CharField(max_length=180, db_column='Vorname') # Field name made lowercase.
    nachname = models.CharField(max_length=180, db_column='Nachname') # Field name made lowercase.
    geburtstag = models.DateField(db_column='Geburtstag') # Field name made lowercase.
    startkontostand = models.FloatField(db_column='Startkontostand') # lowercase
    sozialarbeiter = models.IntegerField(db_column='Sozialarbeiter') # Field name made lowercase.
    class Meta:
        db_table = u'models_betreute'

class Geldeingaenge(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    datum = models.DateField(db_column='Datum') # Field name made lowercase.
    geldgeber = models.CharField(max_length=60, db_column='Geldgeber') # Field name made lowercase.
    benutzer = models.CharField(max_length=60, db_column='Benutzer') # Field name made lowercase.
    betrag = models.FloatField(db_column='Betrag') # Field name made lowercase.
    verwendungszweck = models.CharField(max_length=1800, db_column='Verwendungszweck') # Field name made lowercase.
    class Meta:
        db_table = u'models_geldeingaenge'

class Zuteilungen(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    geldeingang = models.IntegerField(db_column='Geldeingang') # Field name made lowercase.
    betreuter = models.IntegerField(db_column='Betreuter') # Field name made lowercase.
    betrag = models.FloatField(db_column='Betrag') # Field name made lowercase.
    class Meta:
        db_table = u'models_zuteilungen'

class Auszahlungen(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    datum = models.DateField(db_column='Datum') # Field name made lowercase.
    betreuter = models.IntegerField(db_column='Betreuter') # Field name made lowercase.
    betrag = models.FloatField(db_column='Betrag') # Field name made lowercase.
    class Meta:
        db_table = u'models_auszahlungen'

class Forderungen(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    schuldner = models.IntegerField(db_column='Schuldner') # Field name made lowercase.
    kreditor = models.CharField(max_length=60, db_column='Kreditor') # Field name made lowercase.
    gesamtforderung = models.FloatField(db_column='Gesamtforderung') # Field name made lowercase.
    erhebungsdatum = models.DateField(db_column='Erhebungsdatum') # Field name made lowercase.
    notiz = models.CharField(max_length=1800, db_column='Notiz') # Field name made lowercase.
    class Meta:
        db_table = u'models_forderungen'

class Tilgungen(models.Model):
    id = models.IntegerField(primary_key=True, db_column='ID') # Field name made lowercase.
    datum = models.DateField(db_column='Datum') # Field name made lowercase.
    forderung = models.IntegerField(db_column='Forderung') # Field name made lowercase.
    betrag = models.FloatField(db_column='Betrag') # Field name made lowercase.
    class Meta:
        db_table = u'models_tilgungen'



