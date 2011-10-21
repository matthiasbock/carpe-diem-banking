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
	params["MenuBetreute"] = True
	if request.method == "GET":
		try:
			params["Vorname"] = request.session["Vorname"]
			if params["Vorname"] == None:
				params["Vorname"] = ""
			params["Nachname"] = request.session["Nachname"]
			if params["Nachname"] == None:
				params["Nachname"] = ""
			params["Geburtstag"] = request.session["Geburtstag"]
			if params["Geburtstag"] == None:
				params["Geburtstag"] = ""
			params["Kontostand"] = request.session["Kontostand"].replace(".",",")
			if params["Kontostand"] == "":
				params["Kontostand"] = "0,00"
		except:
			pass
		return render_to_response("Betreute/Neu.html", params)
	else:
		Vorname = request.POST.get("Vorname")
		Nachname = request.POST.get("Nachname")
		Geburtstag = request.POST.get("Geburtstag")
		Kontostand = request.POST.get("Kontostand")
		setsession( request, "Vorname", Vorname )
		setsession( request, "Nachname", Nachname )
		setsession( request, "Geburtstag", Geburtstag )
		setsession( request, "Kontostand", Kontostand )
		Fehler = ""
		if Vorname == "":
			Fehler = "Bitte geben sie einen Vornamen ein"
		if Nachname == "":
			Fehler = "Bitte geben sie einen Nachnamen ein"
		try:
			Geburtstag = datetime.strptime( Geburtstag, "%d.%m.%Y" )
		except:
			Fehler = "Bitte geben das Geburtsdatum im Format TT.MM.JJJJ ein"
		try:
			float( Kontostand.replace(",",".") )
		except:
			Fehler = "Bitte geben sie einen g&uuml;ltigen Kontostand ein"
		if Fehler != "":
			setsession(request, "Fehler", Fehler)
			return HttpResponseRedirect("/Kontenverwaltung/Betreute/Neu")
		else:
			Betreute.objects.create( vorname=Vorname, nachname=Nachname, geburtstag=Geburtstag, startkontostand=Kontostand, sozialarbeiter=request.user.id )
#			Betreuter.save()
			setsession(request, "Nachricht", "Neuer Betreuer <i>"+Vorname+" "+Nachname+"</i> wurde gespeichert.")
			return HttpResponseRedirect("/Kontenverwaltung/Betreute/Liste")

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def liste( request ):
	params = defaults(request)
	params["MenuBetreute"] = True
	params["Betreute"] = []
	Bs = Betreute.objects.filter( sozialarbeiter=request.user.id )
	for B in Bs:
		kontostand = float(B.startkontostand)
		Zs = Zuteilungen.objects.filter( betreuter=B.id )
		for Z in Zs:
			kontostand = kontostand + Z.betrag
		As = Auszahlungen.objects.filter( betreuter=B.id )
		for A in As:
			kontostand = kontostand - A.betrag
		Fs = Forderungen.objects.filter( schuldner=B.id )
		for F in Fs:
			Ts = Tilgungen.objects.filter( forderung=F.id )
			for T in Ts:
				kontostand = kontostand - T.betrag
		params["Betreute"].append( { "id":B.id, "vorname":B.vorname, "nachname":B.nachname, "geburtstag":B.geburtstag, "kontostand":currency(float(kontostand)) } )
	params["Anzahl"] = Bs.count()
#	params["Sozialarbeiter"] = AuthUser.objects.all()
	params["showoptions"] = True
	params["options"] = {}
	params["options"]["Einen neuen Betreuten eingeben"] = "Betreute/Neu"
#	params["options"]["Einen Betreuten aus der Liste l&ouml;schen"] = "javascript:alert('Betreuten l&ouml;schen','Um einen Betreuten zu l&ouml;schen, klicken sie in der &Uuml;bersicht auf das rote Symbol neben dem entsprechenden Betreuten');"
	return render_to_response("Betreute/Liste.html", params)

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def liste( request ):
	params = defaults(request)
	params["MenuBetreute"] = True
	params["Betreute"] = []
	return render_to_response("Betreute/Details.html", params)

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def entfernen( request ):
	params = defaults(request)
	params["MenuBetreute"] = True
	if request.method == "GET":
		try:
			params["ID"] = request.GET.get("ID")
			B = Betreute.objects.get( id=params["ID"] )
			params["Betreuter"] = B.vorname+" "+B.nachname+", geb. am "+B.geburtstag.strftime("%d.%m.%Y")
			return render_to_response("Betreute/Entfernen.html", params)
		except:
			setsession(request, "Fehler", "Fehler beim Abrufen des ausgew&auml;hlten Betreuten!")
			return HttpResponseRedirect("/Kontenverwaltung/Betreute/Liste")
	else:
		try:
			ID = request.POST.get("ID")
			B = Betreute.objects.get( id=ID )
			Name = B.vorname+" "+B.nachname
			Betreute.objects.get( id=request.POST.get("ID") ).delete()
			Zuteilungen.objects.filter( betreuter=ID ).delete()
			Auszahlungen.objects.filter( betreuter=ID ).delete()
			for F in Forderungen.objects.filter( schuldner=ID ):
				Tilgungen.objects.filter( forderung=F.id ).delete()
			Forderungen.objects.filter( schuldner=ID ).delete()
			setsession(request, "Nachricht", "Betreuter <i>"+Name+"</i> wurde gel&ouml;scht.")
		except:
			setsession(request, "Fehler", "Fehler beim L&ouml;schen des ausgew&auml;hlten Betreuten!")
		return HttpResponseRedirect("/Kontenverwaltung/Betreute/Liste")


