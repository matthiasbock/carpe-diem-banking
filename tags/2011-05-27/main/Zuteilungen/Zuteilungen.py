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
	params["MenuGeldeingaenge"] = True
	B = Betreute.objects.filter( sozialarbeiter=request.user.id )
	if B.count() == 0:
		setsession(request, "Fehler", "Sie m&uuml;ssen zuerst ihre <a href=\"/Kontenverwaltung/Betreute/Neu\">Betreuten eingeben</a>, bevor sie ihnen Geld zuteilen k&ouml;nnen.")
		return HttpResponseRedirect("/Kontenverwaltung/Geldeingaenge/Liste")
	if request.method == "GET":
		try:
			params["Geldeingang"] = request.GET.get("Geldeingang")
			Geldeingaenge.objects.get( id=params["Geldeingang"] )
		except:
			setsession(request, "Fehler", "Bitte wählen sie einen Geldeingang, aus dem sie Geld zuteilen m&ouml;chten!")
			return HttpResponseRedirect("/Kontenverwaltung/Geldeingaenge/Liste")
		try:
			params["Betrag"] = request.session["Betrag"]
		except:
			params["Betrag"] = ""
		if params["Betrag"] == "":
			params["Betrag"] = "0,00"
		params["Betreute"] = B
		params["Fokus"] = "Betrag"
		return render_to_response("Zuteilungen/Neu.html", params)
	else:
		Geldeingang = request.POST.get("Geldeingang")
		Betrag = request.POST.get("Betrag")
		Betreuter = request.POST.get("Betreuter")
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
			return HttpResponseRedirect("/Kontenverwaltung/Zuteilungen/Neu")
		else:
			Zuteilungen.objects.create( geldeingang=Geldeingang, betreuter=Betreuter, betrag=Betrag )
			setsession(request, "Nachricht", "Zuteilung wurde gespeichert.")
			return HttpResponseRedirect("/Kontenverwaltung/Geldeingaenge/Liste")

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def entfernen( request ):
	params = defaults(request)
	if params["Sekretariat"]:
		return HttpResponseRedirect("/")
	params["MenuGeldeingaenge"] = True
	if request.method == "GET":
		try:
			params["ID"] = request.GET.get("ID")
			Z = Zuteilungen.objects.get( id=params["ID"] )
			params["Betrag"] = Z.betrag
			B = Betreute.objects.get( id=Z.betreuter )
			params["Betreuter"] = B.vorname+" "+B.nachname
			return render_to_response("Zuteilungen/Entfernen.html", params)
		except:
			setsession(request, "Fehler", "Fehler beim Abrufen der Zuteilung!")
			return HttpResponseRedirect("/Kontenverwaltung/Geldeingaenge/Liste")
	else:
		try:
			Zuteilungen.objects.get( id=request.POST.get("ID") ).delete()
			setsession(request, "Nachricht", "Zuteilung wurde gel&ouml;scht.")
		except:
			setsession(request, "Fehler", "Fehler beim L&ouml;schen der Zuteilung!")
		return HttpResponseRedirect("/Kontenverwaltung/Geldeingaenge/Liste")


