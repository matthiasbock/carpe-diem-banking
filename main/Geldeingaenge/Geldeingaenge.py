# -*- coding: iso-8859-15 -*-

from Kontenverwaltung.main.models import *
from Kontenverwaltung.main.views import defaults, setsession, currency

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
	if request.method == "GET":
		try:
			params["Geldgeber"] = request.session["Geldgeber"]
			params["Betrag"] = request.session["Betrag"]
			params["Verwendungszweck"] = request.session["Verwendungszweck"]
		except:
			params["Geldgeber"] = ""
			params["Betrag"] = ""
			params["Verwendungszweck"] = ""
		if params["Betrag"] == "":
			params["Betrag"] = "0,00"
		try:
                        params["Fokus"] = request.session["Fokus"]
                except:
                        params["Fokus"] = "Geldgeber"
		return render_to_response("Geldeingaenge/Neu.html", params)
	else:
		Geldgeber = request.POST.get("Geldgeber")
		Verwendungszweck = request.POST.get("Verwendungszweck")
		setsession( request, "Geldgeber", Geldgeber )
		setsession( request, "Verwendungszweck", Verwendungszweck )
		Fehler = ""
		Betrag = request.POST.get("Betrag")
		try:
			Betrag = float( Betrag.replace(",",".") )
			if Betrag == 0:
				0/0.
		except:
			Fehler = "Bitte geben einen g&uuml;ltigen Betrag ein"
			Fokus = "Betrag"
		setsession( request, "Betrag", Betrag )
		if Geldgeber == "":
			Fehler = "Bitte geben sie den Geldgeber ein"
			Fokus = "Geldgeber"
		setsession( request, "Geldgeber", Geldgeber )
		if Fehler != "":
			setsession(request, "Fehler", Fehler)
			setsession(request, "Fokus", Fokus)
			return HttpResponseRedirect("/Kontenverwaltung/Geldeingaenge/Neu")
		else:
			Geldeingaenge.objects.create( datum=datetime.today(), geldgeber=Geldgeber, benutzer=request.user.id, betrag=Betrag, verwendungszweck=Verwendungszweck )
			setsession(request, "Nachricht", "Geldeingang wurde gespeichert.")
			return HttpResponseRedirect("/Kontenverwaltung/Geldeingaenge/Liste")

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def liste( request ):
	setsession( request, "Geldgeber", "" )
	setsession( request, "Betrag", "" )
	setsession( request, "Verwendungszweck", "" )
	params = defaults(request)
	params["MenuGeldeingaenge"] = True
	if params["Sekretariat"]:
		Gs = Geldeingaenge.objects.all().order_by("-datum")
	else:
		Gs = Geldeingaenge.objects.filter( benutzer=request.user.id ).order_by("-datum")
	params["Geldeingaenge"] = []
	for G in Gs:
		Zs = Zuteilungen.objects.filter( geldeingang=G.id ).order_by("id")
		zs = []
		verbleibend = G.betrag
		for Z in Zs:
			verbleibend = verbleibend - Z.betrag
			B = Betreute.objects.get( id=Z.betreuter )
			zs.append( { "id":Z.id, "betreuter":Z.betreuter, "betreuterName":B.vorname+" "+B.nachname, "betrag":currency(float(-Z.betrag)) } )
		params["Geldeingaenge"].append( { "id":G.id, "datum":G.datum, "geldgeber":G.geldgeber, "verwendungszweck":G.verwendungszweck, "betrag":currency(float(G.betrag)), "verbleibend":currency(float(verbleibend)), "zuteilungen":zs} )
	params["Anzahl"] = Gs.count()
	params["showoptions"] = True
	params["options"] = {}
	params["options"]["Einen neuen Geldeingang verbuchen"] = "Geldeingaenge/Neu"
	return render_to_response("Geldeingaenge/Liste.html", params)

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
			G = Geldeingaenge.objects.get( id=params["ID"] )
			params["Datum"] = G.datum
			params["Betrag"] = G.betrag
			return render_to_response("Geldeingaenge/Entfernen.html", params)
		except:
			setsession(request, "Fehler", "Fehler beim Abrufen des ausgew&auml;hlten Geldeingangs!")
			return HttpResponseRedirect("/Kontenverwaltung/Geldeingaenge/Liste")
	else:
		try:
			Zuteilungen.objects.filter( geldeingang=request.POST.get("ID") ).delete()
		except:
			pass
		try:
			Geldeingaenge.objects.get( id=request.POST.get("ID") ).delete()
			setsession(request, "Nachricht", "Geldeingang wurde gel&ouml;scht.")
		except:
			setsession(request, "Fehler", "Fehler beim L&ouml;schen des Geldeingangs!")
		return HttpResponseRedirect("/Kontenverwaltung/Geldeingaenge/Liste")


