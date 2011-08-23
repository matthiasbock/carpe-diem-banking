# -*- coding: iso-8859-15 -*-
from django.conf.urls.defaults import *
from django.conf import settings

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
    # Example:
    # (r'^banking/', include('banking.foo.urls')),

    # Uncomment the admin/doc line below and add 'django.contrib.admindocs' 
    # to INSTALLED_APPS to enable admin documentation:
    # (r'^admin/doc/', include('django.contrib.admindocs.urls')),

    # Uncomment the next line to enable the admin:
    (r'^banking/admin/(.*)',			admin.site.root),

    # media
    (r'^static/(?P<path>.*)$',			'django.views.static.serve', {'document_root': settings.MEDIA_ROOT, 'show_indexes': True}), 

    (r'^banking/$',				'banking.main.views.Index'), # django.contrib.auth.views.login # ruft auf: registration/login.html
    (r'^banking/login/$',			'banking.main.views.Login'),
    (r'^banking/logout/$',			'banking.main.views.Logout'),
#    (r'^banking/IP$',				'banking.main.views.IP'),
    (r'^banking/ping$',			'banking.main.views.ping'),

#    (r'^banking/Intranet/$',			'banking.main.views.Baustelle'),
    (r'^banking/Intranet/$',			'banking.main.Intranet.Index'),
    (r'^banking/Klientenkasse$',		'banking.main.Intranet.Klientenkasse'),
    (r'^banking/Zuteilung$',			'banking.main.Intranet.Zuteilung'),

    (r'^banking/Klient$',			'banking.main.Klient.Klient'),
    (r'^banking/Forderungen$',			'banking.main.Klient.Forderungen'),
    (r'^banking/Einzahlung$',			'banking.main.Klient.Einzahlung'),
    (r'^banking/Auszahlung$',			'banking.main.Klient.Auszahlung'),
    (r'^banking/Ueberweisung$',		'banking.main.Klient.Ueberweisung'),
    (r'^banking/NeueSchulden$',		'banking.main.Klient.NeueSchulden'),
    (r'^banking/Entfernen$',			'banking.main.Klient.Entfernen'),

    (r'^banking/Girokonto/$',			'banking.main.Girokonto.Index'),
    (r'^banking/Kontenumsaetze$',		'banking.main.Girokonto.Kontenumsaetze'),

# die nachfolgende Aufzählung ist überholt
# evtl. aber noch hilfreich für die Teile, die wieder eingebunden werden
    (r'^banking/Start$',			'banking.main.views.start'),
    (r'^banking/Betreute/Neu$',		'banking.main.Betreute.Betreute.neu'),
    (r'^banking/Betreute/Details$',		'banking.main.Betreute.Betreute.details'),
    (r'^banking/Betreute/Liste$',		'banking.main.Betreute.Betreute.liste'),
    (r'^banking/Betreute/Entfernen$',		'banking.main.Betreute.Betreute.entfernen'),
    (r'^banking/Geldeingaenge/Neu$',		'banking.main.Geldeingaenge.Geldeingaenge.neu'),
    (r'^banking/Geldeingaenge/Liste$',		'banking.main.Geldeingaenge.Geldeingaenge.liste'),
    (r'^banking/Geldeingaenge/Entfernen$',	'banking.main.Geldeingaenge.Geldeingaenge.entfernen'),
    (r'^banking/Zuteilungen/Neu$',		'banking.main.Zuteilungen.Zuteilungen.neu'),
    (r'^banking/Zuteilungen/Entfernen$',	'banking.main.Zuteilungen.Zuteilungen.entfernen'),
    (r'^banking/Auszahlungen/Neu$',		'banking.main.Auszahlungen.Auszahlungen.neu'),
    (r'^banking/Auszahlungen/Quittung$',	'banking.main.Auszahlungen.Auszahlungen.quittung'),
    (r'^banking/Auszahlungen/Liste$',		'banking.main.Auszahlungen.Auszahlungen.liste'),
    (r'^banking/Auszahlungen/Entfernen$',	'banking.main.Auszahlungen.Auszahlungen.entfernen'),
    (r'^banking/Forderungen/Neu$',		'banking.main.Forderungen.Forderungen.neu'),
    (r'^banking/Forderungen/Liste$',		'banking.main.Forderungen.Forderungen.liste'),
    (r'^banking/Forderungen/Entfernen$',	'banking.main.Forderungen.Forderungen.entfernen'),
    (r'^banking/Tilgungen/Neu$',		'banking.main.Tilgungen.Tilgungen.neu'),
    (r'^banking/Tilgungen/Entfernen$',		'banking.main.Tilgungen.Tilgungen.entfernen'),
)

