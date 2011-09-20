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
	(r'^static/(?P<path>.*)$',				'django.views.static.serve', {'document_root': '/var/www/Django/carpediembanking/static', 'show_indexes': True}), 

	(r'^carpediembanking/$',				'Django.carpediembanking.main.views.Index'), # django.contrib.auth.views.login # ruft auf: registration/login.html
	(r'^carpediembanking/login/$',				'Django.carpediembanking.main.views.Login'),
	(r'^carpediembanking/logout/$',				'Django.carpediembanking.main.views.Logout'),
	#    (r'^carpediembanking/IP$',				'Django.carpediembanking.main.views.IP'),
	(r'^carpediembanking/ping$',				'Django.carpediembanking.main.views.ping'),

	#    (r'^carpediembanking/Intranet/$',			'Django.carpediembanking.main.views.Baustelle'),
	(r'^carpediembanking/Intranet/$',			'Django.carpediembanking.main.Intranet.Index'),
	(r'^carpediembanking/Klientenkasse$',			'Django.carpediembanking.main.Intranet.Klientenkasse'),
	(r'^carpediembanking/Zuteilung$',			'Django.carpediembanking.main.Intranet.Zuteilung'),

	(r'^carpediembanking/Klient$',				'Django.carpediembanking.main.Klient.Klient'),
	(r'^carpediembanking/Forderungen$',			'Django.carpediembanking.main.Klient.Forderungen'),
	(r'^carpediembanking/Einzahlung$',			'Django.carpediembanking.main.Klient.Einzahlung'),
	(r'^carpediembanking/Auszahlung$',			'Django.carpediembanking.main.Klient.Auszahlung'),
	(r'^carpediembanking/Ueberweisung$',			'Django.carpediembanking.main.Klient.Ueberweisung'),
	(r'^carpediembanking/NeueSchulden$',			'Django.carpediembanking.main.Klient.NeueSchulden'),
	(r'^carpediembanking/Entfernen$',			'Django.carpediembanking.main.Klient.Entfernen'),

	(r'^carpediembanking/Girokonto/$',			'Django.carpediembanking.main.Girokonto.Index'),
	(r'^carpediembanking/Kontenumsaetze$',			'Django.carpediembanking.main.Girokonto.Kontenumsaetze'),

	# die nachfolgende Aufzählung ist überholt
	# evtl. aber noch hilfreich für die Teile, die wieder eingebunden werden
	(r'^carpediembanking/Start$',				'Django.carpediembanking.main.views.start'),
	(r'^carpediembanking/Betreute/Neu$',			'Django.carpediembanking.main.Betreute.Betreute.neu'),
	(r'^carpediembanking/Betreute/Details$',		'Django.carpediembanking.main.Betreute.Betreute.details'),
	(r'^carpediembanking/Betreute/Liste$',			'Django.carpediembanking.main.Betreute.Betreute.liste'),
	(r'^carpediembanking/Betreute/Entfernen$',		'Django.carpediembanking.main.Betreute.Betreute.entfernen'),
	(r'^carpediembanking/Geldeingaenge/Neu$',		'Django.carpediembanking.main.Geldeingaenge.Geldeingaenge.neu'),
	(r'^carpediembanking/Geldeingaenge/Liste$',		'Django.carpediembanking.main.Geldeingaenge.Geldeingaenge.liste'),
	(r'^carpediembanking/Geldeingaenge/Entfernen$',		'Django.carpediembanking.main.Geldeingaenge.Geldeingaenge.entfernen'),
	(r'^carpediembanking/Zuteilungen/Neu$',			'Django.carpediembanking.main.Zuteilungen.Zuteilungen.neu'),
	(r'^carpediembanking/Zuteilungen/Entfernen$',		'Django.carpediembanking.main.Zuteilungen.Zuteilungen.entfernen'),
	(r'^carpediembanking/Auszahlungen/Neu$',		'Django.carpediembanking.main.Auszahlungen.Auszahlungen.neu'),
	(r'^carpediembanking/Auszahlungen/Quittung$',		'Django.carpediembanking.main.Auszahlungen.Auszahlungen.quittung'),
	(r'^carpediembanking/Auszahlungen/Liste$',		'Django.carpediembanking.main.Auszahlungen.Auszahlungen.liste'),
	(r'^carpediembanking/Auszahlungen/Entfernen$',		'Django.carpediembanking.main.Auszahlungen.Auszahlungen.entfernen'),
	(r'^carpediembanking/Forderungen/Neu$',			'Django.carpediembanking.main.Forderungen.Forderungen.neu'),
	(r'^carpediembanking/Forderungen/Liste$',		'Django.carpediembanking.main.Forderungen.Forderungen.liste'),
	(r'^carpediembanking/Forderungen/Entfernen$',		'Django.carpediembanking.main.Forderungen.Forderungen.entfernen'),
	(r'^carpediembanking/Tilgungen/Neu$',			'Django.carpediembanking.main.Tilgungen.Tilgungen.neu'),
	(r'^carpediembanking/Tilgungen/Entfernen$',		'Django.carpediembanking.main.Tilgungen.Tilgungen.entfernen'),

)

