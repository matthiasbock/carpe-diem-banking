# -*- coding: iso-8859-15 -*-
from django.conf.urls.defaults import *
from django.conf import settings

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
	# Example:
	# (r'^carpediembanking/', include('carpediembanking.foo.urls')),

	# Uncomment the admin/doc line below and add 'django.contrib.admindocs' 
	# to INSTALLED_APPS to enable admin documentation:
	# (r'^admin/doc/', include('django.contrib.admindocs.urls')),

	# Uncomment the next line to enable the admin:
	#(r'^carpediembanking/admin/(.*)',			admin.site.root),

	# media
	(r'^carpediembanking/static/(?P<path>.*)$',		'django.views.static.serve', {'document_root': '/var/www/Django/carpediembanking/static', 'show_indexes': True}), 

	(r'^carpediembanking/$',				'Django.carpediembanking.main.Index'), # django.contrib.auth.views.login # ruft auf: registration/login.html
	(r'^carpediembanking/login/$',				'Django.carpediembanking.main.Login'),
	(r'^carpediembanking/logout/$',				'Django.carpediembanking.main.Logout'),
	#    (r'^carpediembanking/IP$',				'Django.carpediembanking.main.IP'),
	(r'^carpediembanking/ping$',				'Django.carpediembanking.main.ping'),

	#    (r'^carpediembanking/Intranet/$',			'Django.carpediembanking.main.Baustelle'),
	(r'^carpediembanking/Intranet/$',			'Django.carpediembanking.Intranet.Index'),
	(r'^carpediembanking/Klientenkasse$',			'Django.carpediembanking.Intranet.Klientenkasse'),
	(r'^carpediembanking/Zuteilung$',			'Django.carpediembanking.Intranet.Zuteilung'),

	(r'^carpediembanking/Klient$',				'Django.carpediembanking.Klient.Klient'),
	(r'^carpediembanking/Forderungen$',			'Django.carpediembanking.Klient.Forderungen'),
	(r'^carpediembanking/Einzahlung$',			'Django.carpediembanking.Klient.Einzahlung'),
	(r'^carpediembanking/Auszahlung$',			'Django.carpediembanking.Klient.Auszahlung'),
	(r'^carpediembanking/Ueberweisung$',			'Django.carpediembanking.Klient.Ueberweisung'),
	(r'^carpediembanking/NeueSchulden$',			'Django.carpediembanking.Klient.NeueSchulden'),
	(r'^carpediembanking/Entfernen$',			'Django.carpediembanking.Klient.Entfernen'),

	(r'^carpediembanking/Girokonto/$',			'Django.carpediembanking.Girokonto.Index'),
	(r'^carpediembanking/Kontenumsaetze$',			'Django.carpediembanking.Girokonto.Kontenumsaetze'),

	# die nachfolgende Aufzählung ist überholt
	# evtl. aber noch hilfreich für die Teile, die wieder eingebunden werden
	(r'^carpediembanking/Start$',				'Django.carpediembanking.main.start'),
	(r'^carpediembanking/Betreute/Neu$',			'Django.carpediembanking.Betreute.Betreute.neu'),
	(r'^carpediembanking/Betreute/Details$',		'Django.carpediembanking.Betreute.Betreute.details'),
	(r'^carpediembanking/Betreute/Liste$',			'Django.carpediembanking.Betreute.Betreute.liste'),
	(r'^carpediembanking/Betreute/Entfernen$',		'Django.carpediembanking.Betreute.Betreute.entfernen'),
	(r'^carpediembanking/Geldeingaenge/Neu$',		'Django.carpediembanking.Geldeingaenge.Geldeingaenge.neu'),
	(r'^carpediembanking/Geldeingaenge/Liste$',		'Django.carpediembanking.Geldeingaenge.Geldeingaenge.liste'),
	(r'^carpediembanking/Geldeingaenge/Entfernen$',		'Django.carpediembanking.Geldeingaenge.Geldeingaenge.entfernen'),
	(r'^carpediembanking/Zuteilungen/Neu$',			'Django.carpediembanking.Zuteilungen.Zuteilungen.neu'),
	(r'^carpediembanking/Zuteilungen/Entfernen$',		'Django.carpediembanking.Zuteilungen.Zuteilungen.entfernen'),
	(r'^carpediembanking/Auszahlungen/Neu$',		'Django.carpediembanking.Auszahlungen.Auszahlungen.neu'),
	(r'^carpediembanking/Auszahlungen/Quittung$',		'Django.carpediembanking.Auszahlungen.Auszahlungen.quittung'),
	(r'^carpediembanking/Auszahlungen/Liste$',		'Django.carpediembanking.Auszahlungen.Auszahlungen.liste'),
	(r'^carpediembanking/Auszahlungen/Entfernen$',		'Django.carpediembanking.Auszahlungen.Auszahlungen.entfernen'),
	(r'^carpediembanking/Forderungen/Neu$',			'Django.carpediembanking.Forderungen.Forderungen.neu'),
	(r'^carpediembanking/Forderungen/Liste$',		'Django.carpediembanking.Forderungen.Forderungen.liste'),
	(r'^carpediembanking/Forderungen/Entfernen$',		'Django.carpediembanking.Forderungen.Forderungen.entfernen'),
	(r'^carpediembanking/Tilgungen/Neu$',			'Django.carpediembanking.Tilgungen.Tilgungen.neu'),
	(r'^carpediembanking/Tilgungen/Entfernen$',		'Django.carpediembanking.Tilgungen.Tilgungen.entfernen'),

)

