# -*- coding: iso-8859-15 -*-

from Kontenverwaltung.main.models import *
from Kontenverwaltung.main.views import defaults, setsession

from django.http import HttpResponse, HttpResponseRedirect
from django.views.decorators.cache import cache_control
from django.contrib.auth.decorators import login_required
from django.shortcuts import render_to_response

from datetime import datetime
from urllib import urlencode

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def neu( request ):
	params = defaults(request)
	if params["Sekretariat"]:
		return HttpResponseRedirect("/")
	params["MenuForderungen"] = True
	if request.method == "GET":
		try:
			params["Forderung"] = request.GET.get("Forderung")
			Forderungen.objects.get( id=params["Forderung"] )
		except:
			setsession(request, "Fehler", "Bitte wählen sie die Forderung, die getilgt werden soll")
			return HttpResponseRedirect("/Kontenverwaltung/Forderungen/Liste")
		try:
			params["Betrag"] = request.session["Betrag"]
		except:
			params["Betrag"] = ""
		if params["Betrag"] == "":
			params["Betrag"] = "0,00"
		params["Fokus"] = "Betrag"
		return render_to_response("Tilgungen/Neu.html", params)
	else:
		Forderung = request.POST.get("Forderung")
		Betrag = request.POST.get("Betrag")
		setsession( request, "Betrag", Betrag )
		Fehler = ""
		try:
			Betrag = float( Betrag.replace(",",".") )
			if Betrag == 0:
				0/0.
		except:
			Fehler = "Bitte geben einen g&uuml;ltigen Betrag ein"
		if Fehler != "":
			setsession(request, "Fehler", Fehler)
			return HttpResponseRedirect("/Kontenverwaltung/Tilgungen/Neu")
		else:
			Tilgungen.objects.create( datum=datetime.today(), forderung=Forderung, betrag=Betrag )
			setsession(request, "Nachricht", "Tilgung wurde gespeichert.")
			return HttpResponseRedirect("/Kontenverwaltung/Forderungen/Liste")

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def entfernen( request ):
	params = defaults(request)
	if params["Sekretariat"]:
		return HttpResponseRedirect("/")
	params["MenuForderungen"] = True
	if request.method == "GET":
		try:
			params["ID"] = request.GET.get("ID")
			params["Betrag"] = Tilgungen.objects.get( id=params["ID"] ).betrag
			return render_to_response("Tilgungen/Entfernen.html", params)
		except:
			setsession(request, "Fehler", "Fehler beim Abrufen der Tilgung")
			return HttpResponseRedirect("/Kontenverwaltung/Forderungen/Liste")
	else:
		try:
			Tilgungen.objects.get( id=request.POST.get("ID") ).delete()
			setsession(request, "Nachricht", "Tilgung wurde gel&ouml;scht.")
		except:
			setsession(request, "Fehler", "Fehler beim L&ouml;schen der Tilgung")
		return HttpResponseRedirect("/Kontenverwaltung/Forderungen/Liste")


