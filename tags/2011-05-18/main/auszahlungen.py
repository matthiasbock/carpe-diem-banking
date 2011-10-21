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
	params["MenuAuszahlungen"] = True
	B = Betreute.objects.filter( sozialarbeiter=request.user.id )
	if B.count() == 0:
		setsession(request, "Fehler", "Sie m&uuml;ssen zuerst ihre <a href=\"/Kontenverwaltung/Betreute/Neu\">Betreuten eingeben</a>, bevor sie ihnen Geld auszahlen k&ouml;nnen.")
		return HttpResponseRedirect("/Kontenverwaltung/Auszahlungen/Liste")
	if request.method == "GET":
		try:
			params["Betrag"] = request.session["Betrag"]
		except:
			params["Betrag"] = ""
		if params["Betrag"] == None or params["Betrag"] == "":
			params["Betrag"] = "0,00"
		params["Betreute"] = B
		params["Fokus"] = "Betrag"
		return render_to_response("Auszahlungen/Neu.html", params)
	else:
		Fehler = ""
		Betrag = request.POST.get("Betrag")
		try:
			Betrag = float( Betrag.replace(",",".") )
			if Betrag == 0:
				0/0.
		except:
			Fehler = "Bitte geben einen g&uuml;ltigen Betrag ein"		
		setsession( request, "Betrag", Betrag )
		Betreuter = request.POST.get("Betreuter")
		if Fehler != "":
			setsession(request, "Fehler", Fehler)
			return HttpResponseRedirect("/Kontenverwaltung/Auszahlungen/Neu")
		else:
			Auszahlungen.objects.create( datum=datetime.today(), betreuter=Betreuter, betrag=Betrag )
			setsession(request, "Nachricht", "Auszahlung wurde gespeichert.")
			setsession(request, "Datum", datetime.today() )
			setsession(request, "Betreuter", Betreuter)
			setsession(request, "Betrag", Betrag)
			return HttpResponseRedirect("/Kontenverwaltung/Auszahlungen/Quittung")

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def quittung( request ):
	params = defaults(request)
	params["MenuAuszahlungen"] = True
	try:
		params["Datum"] = request.session["Datum"]
		params["Betreuter"] = Betreute.objects.get( id=request.session["Betreuter"] )
		params["Betrag"] = request.session["Betrag"]
		params["Benutzer"] = request.user
		return render_to_response("Auszahlungen/Quittung.html", params)
	except:
		setsession(request, "Fehler", "Die Buchung wurde gespeichert, aber es konnte keine Quittung erstellt werden")
		return HttpResponseRedirect("/Kontenverwaltung/Auszahlungen/Liste")

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def liste( request ):
	params = defaults(request)
	params["MenuAuszahlungen"] = True
	if params["Sekretariat"]:
		B = Betreute.objects.all()
	else:
		B = Betreute.objects.filter( sozialarbeiter=request.user.id )
	BetreuteIDs = []
	for b in B:
		BetreuteIDs.append( b.id )
	As = Auszahlungen.objects.filter( betreuter__in=BetreuteIDs ).order_by("-datum")
	params["Auszahlungen"] = []
	for A in As:
		B = Betreute.objects.get( id=A.betreuter )
		params["Auszahlungen"].append( { "id":A.id, "datum":A.datum, "betrag":currency(float(-A.betrag)), "betreuterID":A.betreuter, "betreuterName":B.vorname+" "+B.nachname } )
	params["Anzahl"] = As.count()
	params["showoptions"] = True
	params["options"] = {}
	params["options"]["Eine neue Auszahlung verbuchen"] = "Auszahlungen/Neu"
	return render_to_response("Auszahlungen/Liste.html", params)

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def entfernen( request ):
	params = defaults(request)
	if params["Sekretariat"]:
		return HttpResponseRedirect("/")
	params["MenuAuszahlungen"] = True
	if request.method == "GET":
		try:
			params["ID"] = request.GET.get("ID")
			A = Auszahlungen.objects.get( id=params["ID"] )
			B = Betreute.objects.get( id=A.betreuter )
			params["Betreuter"] = B.vorname+" "+B.nachname
			params["Betrag"] = A.betrag
			return render_to_response("Auszahlungen/Entfernen.html", params)
		except:
			setsession(request, "Fehler", "Fehler beim Abrufen der Auszahlung")
			return HttpResponseRedirect("/Kontenverwaltung/Auszahlungen/Liste")
	else:
		try:
			Auszahlungen.objects.get( id=request.POST.get("ID") ).delete()
			setsession(request, "Nachricht", "Auszahlung wurde gel&ouml;scht.")
		except:
			setsession(request, "Fehler", "Fehler beim L&ouml;schen der Auszahlung")
		return HttpResponseRedirect("/Kontenverwaltung/Auszahlungen/Liste")


