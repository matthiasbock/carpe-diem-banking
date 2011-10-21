# -*- coding: iso-8859-15 -*-

from Django.carpediembanking.models import *
from Django.carpediembanking.includes import *

from django.shortcuts import render_to_response
from django.http import HttpResponse, HttpResponseRedirect
from django.views.decorators.cache import cache_control
from django.contrib.auth.decorators import login_required

def Index( request ):
	return HttpResponseRedirect("/Django/carpediembanking/Kontenumsaetze")

def Kontenumsaetze( request ):
	params = defaults( request )
	myGirokonto = 1 #request.user....
	params["Umsaetze"] = []
	for Umsatz in Girokontenumsaetze.objects.using(DB).filter( girokonto=myGirokonto ):
		params["Umsaetze"].append( { "id":Umsatz.id, "datum":Umsatz.datum, "verwendungszweck":Umsatz.verwendungszweck, "betrag":currency(Umsatz.betrag) } )
	return render_to_response("Kontenumsaetze.html", params)
