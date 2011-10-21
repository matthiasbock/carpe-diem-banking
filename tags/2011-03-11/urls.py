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

    (r'^Kontenverwaltung/$',				'Kontenverwaltung.main.views.Login'),	# django.contrib.auth.views.login # ruft auf: registration/login.html
    (r'^Kontenverwaltung/login/$',			'Kontenverwaltung.main.views.Login'),
    (r'^Kontenverwaltung/logout/$',			'Kontenverwaltung.main.views.Logout'),

    (r'^Kontenverwaltung/Start$',			'Kontenverwaltung.main.start.start'),
    (r'^Kontenverwaltung/Betreute/Neu$',		'Kontenverwaltung.main.betreute.neu'),
#    (r'^Kontenverwaltung/Betreute/Editieren$',		'Kontenverwaltung.main.betreute.editieren'),
    (r'^Kontenverwaltung/Betreute/Liste$',		'Kontenverwaltung.main.betreute.liste'),
    (r'^Kontenverwaltung/Betreute/Entfernen$',		'Kontenverwaltung.main.betreute.entfernen'),
    (r'^Kontenverwaltung/Buchungen/Letzte$',		'Kontenverwaltung.main.buchungen.letzte'),
    (r'^Kontenverwaltung/Geldeingaenge/Neu$',		'Kontenverwaltung.main.geldeingaenge.neu'),
    (r'^Kontenverwaltung/Geldeingaenge/Liste$',		'Kontenverwaltung.main.geldeingaenge.liste'),
    (r'^Kontenverwaltung/Geldeingaenge/Entfernen$',	'Kontenverwaltung.main.geldeingaenge.entfernen'),
    (r'^Kontenverwaltung/Zuteilungen/Neu$',		'Kontenverwaltung.main.zuteilungen.neu'),
    (r'^Kontenverwaltung/Zuteilungen/Entfernen$',	'Kontenverwaltung.main.zuteilungen.entfernen'),
    (r'^Kontenverwaltung/Auszahlungen/Neu$',		'Kontenverwaltung.main.auszahlungen.neu'),
    (r'^Kontenverwaltung/Auszahlungen/Liste$',		'Kontenverwaltung.main.auszahlungen.liste'),
    (r'^Kontenverwaltung/Auszahlungen/Entfernen$',	'Kontenverwaltung.main.auszahlungen.entfernen'),
    (r'^Kontenverwaltung/Forderungen/Neu$',		'Kontenverwaltung.main.forderungen.neu'),
    (r'^Kontenverwaltung/Forderungen/Liste$',		'Kontenverwaltung.main.forderungen.liste'),
    (r'^Kontenverwaltung/Forderungen/Tilgen$',		'Kontenverwaltung.main.forderungen.tilgen'),
    (r'^Kontenverwaltung/Forderungen/Entfernen$',	'Kontenverwaltung.main.forderungen.entfernen'),
    (r'^Kontenverwaltung/Tilgungen/Neu$',		'Kontenverwaltung.main.tilgungen.neu'),
    (r'^Kontenverwaltung/Tilgungen/Entfernen$',		'Kontenverwaltung.main.tilgungen.entfernen'),
)

