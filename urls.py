# -*- coding: iso-8859-15 -*-
from django.conf.urls.defaults import *
from django.conf import settings

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
    # Uncomment the next line to enable the admin:
    (r'^Kontenverwaltung/admin/(.*)',			admin.site.root),

    (r'^Kontenverwaltung/media/(?P<path>.*)$',		'django.views.static.serve', {'document_root': settings.MEDIA_ROOT, 'show_indexes': True}), 

    (r'^Kontenverwaltung/$',				'Django.carpediembanking.views.Index'), # django.contrib.auth.views.login # ruft auf: registration/login.html
    (r'^Kontenverwaltung/login/$',			'Django.carpediembanking.views.Login'),
    (r'^Kontenverwaltung/logout/$',			'Django.carpediembanking.views.Logout'),

#    (r'^Kontenverwaltung/Intranet/$',			'Django.carpediembanking.views.Baustelle'),
    (r'^Kontenverwaltung/Intranet/$',			'Django.carpediembanking.Intranet.Index'),
    (r'^Kontenverwaltung/Klientenkasse$',		'Django.carpediembanking.Intranet.Klientenkasse'),
    (r'^Kontenverwaltung/Zuteilung$',			'Django.carpediembanking.Intranet.Zuteilung'),

    (r'^Kontenverwaltung/Klient$',			'Django.carpediembanking.Klient.Klient'),
    (r'^Kontenverwaltung/Forderungen$',			'Django.carpediembanking.Klient.Forderungen'),
    (r'^Kontenverwaltung/Einzahlung$',			'Django.carpediembanking.Klient.Einzahlung'),
    (r'^Kontenverwaltung/Auszahlung$',			'Django.carpediembanking.Klient.Auszahlung'),
    (r'^Kontenverwaltung/Ueberweisung$',		'Django.carpediembanking.Klient.Ueberweisung'),
    (r'^Kontenverwaltung/NeueSchulden$',		'Django.carpediembanking.Klient.NeueSchulden'),
    (r'^Kontenverwaltung/Entfernen$',			'Django.carpediembanking.Klient.Entfernen'),

    (r'^Kontenverwaltung/Girokonto/$',			'Django.carpediembanking.Girokonto.Index'),
    (r'^Kontenverwaltung/Kontenumsaetze$',		'Django.carpediembanking.Girokonto.Kontenumsaetze'),
)

