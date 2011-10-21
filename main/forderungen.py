# -*- coding: iso-8859-15 -*-

from Kontenverwaltung.main.models import *
from Kontenverwaltung.main.views import defaults, setsession, currency

from django.http import HttpResponse, HttpResponseRedirect
from django.views.decorators.cache import cache_control
from django.contrib.auth.decorators import login_required
from django.shortcuts import render_to_response

from datetime import datetime
from time import time
from urllib import urlencode

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def neu( request ):
	params = defaults(request)
	params["MenuForderungen"] = True
	B = Betreute.objects.filter( sozialarbeiter=request.user.id )
	if B.count() == 0:
		setsession(request, "Fehler", "Sie m&uuml;ssen <a href=\"/Kontenverwaltung/Betreute/Neu\">Betreute eingeben</a>, bevor sie Forderungen eingeben k&ouml;nnen.")
		return HttpResponseRedirect("/Kontenverwaltung/Forderungen/Liste")
	if request.method == "GET":
		params["Betreute"] = B
		try:
			params["Schuldner"] = request.session["Schuldner"]
			if params["Schuldner"] == None:
				params["Schuldner"] = ""
			params["Kreditor"] = request.session["Kreditor"]
			if params["Kreditor"] == None:
				params["Kreditor"] = ""
			params["Gesamtforderung"] = request.session["Gesamtforderung"]
			if params["Gesamtforderung"] == "" or params["Gesamtforderung"] == "":
				params["Gesamtforderung"] = "0,00"
			params["Erhebungsdatum"] = request.session["Erhebungsdatum"]
			if params["Erhebungsdatum"] == None: #or params["Erhebungsdatum"] == "":
				params["Erhebungsdatum"] = ""
			params["Fokus"] = request.session["Fokus"]
		except:
			pass
		return render_to_response("Forderungen/Neu.html", params)
	else:
		Schuldner = request.POST.get("Schuldner")
		Kreditor = request.POST.get("Kreditor")
		Gesamtforderung = request.POST.get("Gesamtforderung")
		Erhebungsdatum = request.POST.get("Erhebungsdatum")
		setsession( request, "Schuldner", Schuldner )
		setsession( request, "Kreditor", Kreditor )
		setsession( request, "Gesamtforderung", Gesamtforderung )
		setsession( request, "Erhebungsdatum", Erhebungsdatum )
		setsession( request, "Fokus", "" )
		Fehler = ""
		if Erhebungsdatum == "":				# check Erhebungsdatum
			Erhebungsdatum = datetime.today()
		else:
			try:
				Erhebungsdatum = time.strptime( Erhebungsdatum, "%d.%m.%Y" )
			except:
				Fehler = "Bitte geben sie ein g&uuml;ltiges oder kein Datum ein"
				Fokus = "Erhebungsdatum"
		try:							# check Gesamtforderung
			Gesamtforderung = float( Gesamtforderung.replace(",",".") )
			if Gesamtforderung == 0:
				0/0.
		except:
			Fehler = "Bitte geben einen g&uuml;ltigen Forderungsbetrag ein"
			Fokus = "Gesamtforderung"
		if Kreditor == "":					# check Kreditor
			Fehler = "Bitte geben sie den Kreditor ein"
			Fokus = "Kreditor"
		if Fehler != "":
			setsession(request, "Fehler", Fehler)
			setsession( request, "Fokus", Fokus )
			return HttpResponseRedirect("/Kontenverwaltung/Forderungen/Neu")
		else:
			Forderungen.objects.create( schuldner=Schuldner, kreditor=Kreditor, gesamtforderung=Gesamtforderung, erhebungsdatum=Erhebungsdatum )
			setsession(request, "Nachricht", "Forderung wurde gespeichert.")
			return HttpResponseRedirect("/Kontenverwaltung/Forderungen/Liste")

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def liste( request ):
	params = defaults(request)
	params["MenuForderungen"] = True
	Bs = Betreute.objects.filter( sozialarbeiter=request.user.id )
	BetreuteIDs = []
	for b in Bs:
		BetreuteIDs.append( b.id )
	Fs = Forderungen.objects.filter( schuldner__in=BetreuteIDs )
	params["Forderungen"] = []
	for F in Fs:
		Ts = Tilgungen.objects.filter( forderung=F.id )
		verbleibend = F.gesamtforderung
		for T in Ts:
			verbleibend = verbleibend - T.betrag
		if verbleibend < 0:
			params["Fehler"] = "Achtung: Es sind &uuml;bertilgte Forderungen vorhanden"
			params["Fehlerbox"] = True
		B = Betreute.objects.get( id=F.schuldner )
		params["Forderungen"].append( { "id":F.id, "schuldner":F.schuldner, "schuldnerName":B.vorname+" "+B.nachname, "kreditor":F.kreditor, "gesamtforderung":currency(float(-F.gesamtforderung)), "offeneforderung":currency(float(-verbleibend)), "erhebungsdatum":F.erhebungsdatum, "Notiz":F.notiz, "tilgungen":Ts} )
	params["Anzahl"] = Fs.count()
	params["showoptions"] = True
	params["options"] = {}
	params["options"]["Eine neue Forderung eingeben"] = "Forderungen/Neu"
	return render_to_response("Forderungen/Liste.html", params)

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def entfernen( request ):
	params = defaults(request)
	params["MenuForderungen"] = True
	if request.method == "GET":
		try:
			params["ID"] = request.GET.get("ID")
			F = Forderungen.objects.get( id=params["ID"] )
			S = Betreute.objects.get( id=F.schuldner )
			params["Schuldner"] = S.vorname+" "+S.nachname
			params["Kreditor"] = F.kreditor
			params["Betrag"] = F.gesamtforderung
			return render_to_response("Forderungen/Entfernen.html", params)
		except:
			setsession(request, "Fehler", "Fehler beim Abrufen der Forderung")
			return HttpResponseRedirect("/Kontenverwaltung/Forderungen/Liste")
	else:
		try:
			Forderungen.objects.get( id=request.POST.get("ID") ).delete()
			setsession(request, "Nachricht", "Forderung wurde gel&ouml;scht.")
		except:
			setsession(request, "Fehler", "Fehler beim L&ouml;schen der Forderung!")
		return HttpResponseRedirect("/Kontenverwaltung/Forderungen/Liste")


