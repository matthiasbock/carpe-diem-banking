# -*- coding: iso-8859-15 -*-
from django.conf.urls.defaults import *
from django.conf import settings

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
    # Example:
    # (r'^Kontenverwaltung/', include('Kontenverwaltung.foo.urls')),

    # Uncomment the admin/doc line below and add 'django.contrib.admindocs' 
    # to INSTALLED_APPS to enable admin documentation:
    # (r'^admin/doc/', include('django.contrib.admindocs.urls')),

    # Uncomment the next line to enable the admin:
    (r'^Kontenverwaltung/admin/(.*)',			admin.site.root),

    # media
    #(r'^Kontenverwaltung/media/(?P<path>.*)$',		'django.views.static.serve', {'document_root': settings.MEDIA_ROOT, 'show_indexes': True}), 

    (r'^Kontenverwaltung/$',				'Kontenverwaltung.main.views.Index'), # django.contrib.auth.views.login # ruft auf: registration/login.html
    (r'^Kontenverwaltung/login/$',			'Kontenverwaltung.main.views.Login'),
    (r'^Kontenverwaltung/logout/$',			'Kontenverwaltung.main.views.Logout'),
#    (r'^Kontenverwaltung/IP$',				'Kontenverwaltung.main.views.IP'),
    (r'^Kontenverwaltung/ping$',			'Kontenverwaltung.main.views.ping'),

#    (r'^Kontenverwaltung/Intranet/$',			'Kontenverwaltung.main.views.Baustelle'),
    (r'^Kontenverwaltung/Intranet/$',			'Kontenverwaltung.main.Intranet.Index'),
    (r'^Kontenverwaltung/Klientenkasse$',		'Kontenverwaltung.main.Intranet.Klientenkasse'),
    (r'^Kontenverwaltung/Zuteilung$',			'Kontenverwaltung.main.Intranet.Zuteilung'),

    (r'^Kontenverwaltung/Klient$',			'Kontenverwaltung.main.Klient.Klient'),
    (r'^Kontenverwaltung/Forderungen$',			'Kontenverwaltung.main.Klient.Forderungen'),
    (r'^Kontenverwaltung/Einzahlung$',			'Kontenverwaltung.main.Klient.Einzahlung'),
    (r'^Kontenverwaltung/Auszahlung$',			'Kontenverwaltung.main.Klient.Auszahlung'),
    (r'^Kontenverwaltung/Ueberweisung$',		'Kontenverwaltung.main.Klient.Ueberweisung'),
    (r'^Kontenverwaltung/NeueSchulden$',		'Kontenverwaltung.main.Klient.NeueSchulden'),
    (r'^Kontenverwaltung/Entfernen$',			'Kontenverwaltung.main.Klient.Entfernen'),

    (r'^Kontenverwaltung/Girokonto/$',			'Kontenverwaltung.main.Girokonto.Index'),
    (r'^Kontenverwaltung/Kontenumsaetze$',		'Kontenverwaltung.main.Girokonto.Kontenumsaetze'),

# die nachfolgende Aufzählung ist überholt
# evtl. aber noch hilfreich für die Teile, die wieder eingebunden werden
    (r'^Kontenverwaltung/Start$',			'Kontenverwaltung.main.views.start'),
    (r'^Kontenverwaltung/Betreute/Neu$',		'Kontenverwaltung.main.Betreute.Betreute.neu'),
    (r'^Kontenverwaltung/Betreute/Details$',		'Kontenverwaltung.main.Betreute.Betreute.details'),
    (r'^Kontenverwaltung/Betreute/Liste$',		'Kontenverwaltung.main.Betreute.Betreute.liste'),
    (r'^Kontenverwaltung/Betreute/Entfernen$',		'Kontenverwaltung.main.Betreute.Betreute.entfernen'),
    (r'^Kontenverwaltung/Geldeingaenge/Neu$',		'Kontenverwaltung.main.Geldeingaenge.Geldeingaenge.neu'),
    (r'^Kontenverwaltung/Geldeingaenge/Liste$',		'Kontenverwaltung.main.Geldeingaenge.Geldeingaenge.liste'),
    (r'^Kontenverwaltung/Geldeingaenge/Entfernen$',	'Kontenverwaltung.main.Geldeingaenge.Geldeingaenge.entfernen'),
    (r'^Kontenverwaltung/Zuteilungen/Neu$',		'Kontenverwaltung.main.Zuteilungen.Zuteilungen.neu'),
    (r'^Kontenverwaltung/Zuteilungen/Entfernen$',	'Kontenverwaltung.main.Zuteilungen.Zuteilungen.entfernen'),
    (r'^Kontenverwaltung/Auszahlungen/Neu$',		'Kontenverwaltung.main.Auszahlungen.Auszahlungen.neu'),
    (r'^Kontenverwaltung/Auszahlungen/Quittung$',	'Kontenverwaltung.main.Auszahlungen.Auszahlungen.quittung'),
    (r'^Kontenverwaltung/Auszahlungen/Liste$',		'Kontenverwaltung.main.Auszahlungen.Auszahlungen.liste'),
    (r'^Kontenverwaltung/Auszahlungen/Entfernen$',	'Kontenverwaltung.main.Auszahlungen.Auszahlungen.entfernen'),
    (r'^Kontenverwaltung/Forderungen/Neu$',		'Kontenverwaltung.main.Forderungen.Forderungen.neu'),
    (r'^Kontenverwaltung/Forderungen/Liste$',		'Kontenverwaltung.main.Forderungen.Forderungen.liste'),
    (r'^Kontenverwaltung/Forderungen/Entfernen$',	'Kontenverwaltung.main.Forderungen.Forderungen.entfernen'),
    (r'^Kontenverwaltung/Tilgungen/Neu$',		'Kontenverwaltung.main.Tilgungen.Tilgungen.neu'),
    (r'^Kontenverwaltung/Tilgungen/Entfernen$',		'Kontenverwaltung.main.Tilgungen.Tilgungen.entfernen'),
)

