# -*- coding: iso-8859-15 -*-

from Django.carpediembanking.models import *
from Django.carpediembanking.includes import *
from decorators import global_decorator

from django.shortcuts import render_to_response
from django.http import HttpResponse, HttpResponseRedirect

@global_decorator
def Index( request ):
	return HttpResponseRedirect("/Django/carpediembanking/Kontenumsaetze")

@global_decorator
def Kontenumsaetze( request ):
	params = defaults( request )
	myGirokonto = 1 #request.user....
	params["Umsaetze"] = []
	for Umsatz in Girokontenumsaetze.objects.using(DB).filter( girokonto=myGirokonto ):
		params["Umsaetze"].append( { "id":Umsatz.id, "datum":Umsatz.datum, "verwendungszweck":Umsatz.verwendungszweck, "betrag":currency(Umsatz.betrag) } )
	return render_to_response("Kontenumsaetze.html", params)

