# -*- coding: iso-8859-15 -*-

from Kontenverwaltung.main.models import *
from Kontenverwaltung.main.includes import *

from django.shortcuts import render_to_response
from django.http import HttpResponse, HttpResponseRedirect
from django.views.decorators.cache import cache_control
from django.contrib.auth.decorators import login_required

def Index( request ):
	return HttpResponseRedirect("/Kontenverwaltung/Kontenumsaetze")

def Kontenumsaetze( request ):
	params = defaults( request )
	myGirokonto = 1 #request.user....
	params["Umsaetze"] = []
	for Umsatz in Girokontenumsaetze.objects.filter( girokonto=myGirokonto ):
		params["Umsaetze"].append( { "id":Umsatz.id, "datum":Umsatz.datum, "verwendungszweck":Umsatz.verwendungszweck, "betrag":currency(Umsatz.betrag) } )
	return render_to_response("Kontenumsaetze.html", params)
