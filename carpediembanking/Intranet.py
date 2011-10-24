# -*- coding: iso-8859-15 -*-

from Django.carpediembanking.models import *
from Django.carpediembanking.includes import *

from django.shortcuts import render_to_response
from django.http import HttpResponse, HttpResponseRedirect
from django.views.decorators.cache import cache_control
from django.contrib.auth.decorators import login_required

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
#@login_required
def Index( request ):
	return HttpResponseRedirect("/Kontenverwaltung/Klientenkasse")

def Klientenkasse( request ):
	params = defaults( request )
	params["Klientenkasse"] = True
	try:
		Kasse = Klientenkassen.objects.get( id=Betreuer.objects.get( auth_user_id=request.user.id ).klientenkasse )
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

def Zuteilung( request ):
	params = defaults( request )
	return render_to_response("Baustelle.html", params)
