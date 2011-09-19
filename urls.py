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

	(r'^carpediembanking/$',				'carpediembanking.main.views.Index'), # django.contrib.auth.views.login # ruft auf: registration/login.html
	(r'^carpediembanking/login/$',				'carpediembanking.main.views.Login'),
	(r'^carpediembanking/logout/$',				'carpediembanking.main.views.Logout'),
	#    (r'^carpediembanking/IP$',				'carpediembanking.main.views.IP'),
	(r'^carpediembanking/ping$',				'carpediembanking.main.views.ping'),

	#    (r'^carpediembanking/Intranet/$',			'carpediembanking.main.views.Baustelle'),
	(r'^carpediembanking/Intranet/$',			'carpediembanking.main.Intranet.Index'),
	(r'^carpediembanking/Klientenkasse$',			'carpediembanking.main.Intranet.Klientenkasse'),
	(r'^carpediembanking/Zuteilung$',			'carpediembanking.main.Intranet.Zuteilung'),

	(r'^carpediembanking/Klient$',				'carpediembanking.main.Klient.Klient'),
	(r'^carpediembanking/Forderungen$',			'carpediembanking.main.Klient.Forderungen'),
	(r'^carpediembanking/Einzahlung$',			'carpediembanking.main.Klient.Einzahlung'),
	(r'^carpediembanking/Auszahlung$',			'carpediembanking.main.Klient.Auszahlung'),
	(r'^carpediembanking/Ueberweisung$',			'carpediembanking.main.Klient.Ueberweisung'),
	(r'^carpediembanking/NeueSchulden$',			'carpediembanking.main.Klient.NeueSchulden'),
	(r'^carpediembanking/Entfernen$',			'carpediembanking.main.Klient.Entfernen'),

	(r'^carpediembanking/Girokonto/$',			'carpediembanking.main.Girokonto.Index'),
	(r'^carpediembanking/Kontenumsaetze$',			'carpediembanking.main.Girokonto.Kontenumsaetze'),

	# die nachfolgende Aufzählung ist überholt
	# evtl. aber noch hilfreich für die Teile, die wieder eingebunden werden
	(r'^carpediembanking/Start$',				'carpediembanking.main.views.start'),
	(r'^carpediembanking/Betreute/Neu$',			'carpediembanking.main.Betreute.Betreute.neu'),
	(r'^carpediembanking/Betreute/Details$',		'carpediembanking.main.Betreute.Betreute.details'),
	(r'^carpediembanking/Betreute/Liste$',			'carpediembanking.main.Betreute.Betreute.liste'),
	(r'^carpediembanking/Betreute/Entfernen$',		'carpediembanking.main.Betreute.Betreute.entfernen'),
	(r'^carpediembanking/Geldeingaenge/Neu$',		'carpediembanking.main.Geldeingaenge.Geldeingaenge.neu'),
	(r'^carpediembanking/Geldeingaenge/Liste$',		'carpediembanking.main.Geldeingaenge.Geldeingaenge.liste'),
	(r'^carpediembanking/Geldeingaenge/Entfernen$',		'carpediembanking.main.Geldeingaenge.Geldeingaenge.entfernen'),
	(r'^carpediembanking/Zuteilungen/Neu$',			'carpediembanking.main.Zuteilungen.Zuteilungen.neu'),
	(r'^carpediembanking/Zuteilungen/Entfernen$',		'carpediembanking.main.Zuteilungen.Zuteilungen.entfernen'),
	(r'^carpediembanking/Auszahlungen/Neu$',		'carpediembanking.main.Auszahlungen.Auszahlungen.neu'),
	(r'^carpediembanking/Auszahlungen/Quittung$',		'carpediembanking.main.Auszahlungen.Auszahlungen.quittung'),
	(r'^carpediembanking/Auszahlungen/Liste$',		'carpediembanking.main.Auszahlungen.Auszahlungen.liste'),
	(r'^carpediembanking/Auszahlungen/Entfernen$',		'carpediembanking.main.Auszahlungen.Auszahlungen.entfernen'),
	(r'^carpediembanking/Forderungen/Neu$',			'carpediembanking.main.Forderungen.Forderungen.neu'),
	(r'^carpediembanking/Forderungen/Liste$',		'carpediembanking.main.Forderungen.Forderungen.liste'),
	(r'^carpediembanking/Forderungen/Entfernen$',		'carpediembanking.main.Forderungen.Forderungen.entfernen'),
	(r'^carpediembanking/Tilgungen/Neu$',			'carpediembanking.main.Tilgungen.Tilgungen.neu'),
	(r'^carpediembanking/Tilgungen/Entfernen$',		'carpediembanking.main.Tilgungen.Tilgungen.entfernen'),

)

