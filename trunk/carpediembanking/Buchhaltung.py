# -*- coding: iso-8859-15 -*-

from models import *
from includes import *
from decorators import global_decorator

from django.shortcuts import render_to_response
from django.http import HttpResponse, HttpResponseRedirect

@global_decorator
def Index( request ):
	return HttpResponseRedirect("/Django/carpediembanking/Klientenkasse")

@global_decorator
def Klientenkasse( request ):
	params = defaults( request )
	params["Klientenkasse"] = True
	try:
		Kasse = Klientenkassen.objects.using(DB).get( id=Betreuer.objects.using(DB).get( auth_user_id=request.user.id ).klientenkasse )
	except:
		return HttpResponseRedirect("Girokonto")
	params["AktuellesSaldo"] = currency( Kasse.startsaldo )
	params["Startdatum"] = Kasse.startdatum
	params["Startsaldo"] = currency( Kasse.startsaldo )
	params["Anzahl"] = 0
	params["Ergebnisse"] = []
	# Girokontenumsätze
	## eingegangene Überweisungen
	## Automateneinzahlungen
	## Automatenauszahlungen
	# Zuteilungen
	# Überweisungen (Überweisung.FuerKlient==None)
	return render_to_response("Klientenkasse.html", params)

@global_decorator
def Zuteilung( request ):
	params = defaults( request )
	return render_to_response("Baustelle.html", params)

