# -*- coding: iso-8859-15 -*-
from django.conf.urls.defaults import *
from django.conf import settings

# Uncomment the next two lines to enable the admin:
#from django.contrib import admin
#admin.autodiscover()

urlpatterns = patterns('',
	# Uncomment the next line to enable the admin:
	#(r'^Django/carpediembanking/admin/(.*)',			admin.site.root),

	# media
	(r'^Django/carpediembanking/static/(?P<path>.*)$',		'django.views.static.serve', {'document_root': '/var/www/Django/carpediembanking/static', 'show_indexes': True}), 

	(r'^Django/carpediembanking/$',				'Django.carpediembanking.views.Index'), # django.contrib.auth.views.login # ruft auf: registration/login.html
	(r'^Django/carpediembanking/login/$',				'Django.carpediembanking.views.Login'),
	(r'^Django/carpediembanking/logout/$',				'Django.carpediembanking.views.Logout'),
	#    (r'^Django/carpediembanking/IP$',				'Django.carpediembanking.views.IP'),
	(r'^Django/carpediembanking/ping$',				'Django.carpediembanking.views.ping'),

	#    (r'^Django/carpediembanking/Intranet/$',			'Django.carpediembanking.views.Baustelle'),
	(r'^Django/carpediembanking/Intranet/$',			'Django.carpediembanking.Intranet.Index'),
	(r'^Django/carpediembanking/Klientenkasse$',			'Django.carpediembanking.Intranet.Klientenkasse'),
	(r'^Django/carpediembanking/Zuteilung$',			'Django.carpediembanking.Intranet.Zuteilung'),

	(r'^Django/carpediembanking/Klient$',				'Django.carpediembanking.Klient.Klient'),
	(r'^Django/carpediembanking/Forderungen$',			'Django.carpediembanking.Klient.Forderungen'),
	(r'^Django/carpediembanking/Einzahlung$',			'Django.carpediembanking.Klient.Einzahlung'),
	(r'^Django/carpediembanking/Auszahlung$',			'Django.carpediembanking.Klient.Auszahlung'),
	(r'^Django/carpediembanking/Ueberweisung$',			'Django.carpediembanking.Klient.Ueberweisung'),
	(r'^Django/carpediembanking/NeueSchulden$',			'Django.carpediembanking.Klient.NeueSchulden'),
	(r'^Django/carpediembanking/Entfernen$',			'Django.carpediembanking.Klient.Entfernen'),

	(r'^Django/carpediembanking/Girokonto/$',			'Django.carpediembanking.Girokonto.Index'),
	(r'^Django/carpediembanking/Kontenumsaetze$',			'Django.carpediembanking.Girokonto.Kontenumsaetze'),

	# die nachfolgende Aufzählung ist überholt
	# evtl. aber noch hilfreich für die Teile, die wieder eingebunden werden
	(r'^Django/carpediembanking/Start$',				'Django.carpediembanking.views.start'),
	(r'^Django/carpediembanking/Betreute/Neu$',			'Django.carpediembanking.Betreute.Betreute.neu'),
	(r'^Django/carpediembanking/Betreute/Details$',		'Django.carpediembanking.Betreute.Betreute.details'),
	(r'^Django/carpediembanking/Betreute/Liste$',			'Django.carpediembanking.Betreute.Betreute.liste'),
	(r'^Django/carpediembanking/Betreute/Entfernen$',		'Django.carpediembanking.Betreute.Betreute.entfernen'),
	(r'^Django/carpediembanking/Geldeingaenge/Neu$',		'Django.carpediembanking.Geldeingaenge.Geldeingaenge.neu'),
	(r'^Django/carpediembanking/Geldeingaenge/Liste$',		'Django.carpediembanking.Geldeingaenge.Geldeingaenge.liste'),
	(r'^Django/carpediembanking/Geldeingaenge/Entfernen$',		'Django.carpediembanking.Geldeingaenge.Geldeingaenge.entfernen'),
	(r'^Django/carpediembanking/Zuteilungen/Neu$',			'Django.carpediembanking.Zuteilungen.Zuteilungen.neu'),
	(r'^Django/carpediembanking/Zuteilungen/Entfernen$',		'Django.carpediembanking.Zuteilungen.Zuteilungen.entfernen'),
	(r'^Django/carpediembanking/Auszahlungen/Neu$',		'Django.carpediembanking.Auszahlungen.Auszahlungen.neu'),
	(r'^Django/carpediembanking/Auszahlungen/Quittung$',		'Django.carpediembanking.Auszahlungen.Auszahlungen.quittung'),
	(r'^Django/carpediembanking/Auszahlungen/Liste$',		'Django.carpediembanking.Auszahlungen.Auszahlungen.liste'),
	(r'^Django/carpediembanking/Auszahlungen/Entfernen$',		'Django.carpediembanking.Auszahlungen.Auszahlungen.entfernen'),
	(r'^Django/carpediembanking/Forderungen/Neu$',			'Django.carpediembanking.Forderungen.Forderungen.neu'),
	(r'^Django/carpediembanking/Forderungen/Liste$',		'Django.carpediembanking.Forderungen.Forderungen.liste'),
	(r'^Django/carpediembanking/Forderungen/Entfernen$',		'Django.carpediembanking.Forderungen.Forderungen.entfernen'),
	(r'^Django/carpediembanking/Tilgungen/Neu$',			'Django.carpediembanking.Tilgungen.Tilgungen.neu'),
	(r'^Django/carpediembanking/Tilgungen/Entfernen$',		'Django.carpediembanking.Tilgungen.Tilgungen.entfernen'),

)

