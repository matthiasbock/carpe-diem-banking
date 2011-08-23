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
	params["MenuBetreute"] = True
	if request.method == "GET":
		try:
			params["Vorname"] = request.session["Vorname"]
			params["Nachname"] = request.session["Nachname"]
			params["Geburtstag"] = request.session["Geburtstag"]
			params["Startkontostand"] = request.session["Startkontostand"].replace(".",",")
		except:
			params["Vorname"] = None
			params["Nachname"] = None
			params["Geburtstag"] = None
			params["Startkontostand"] = ""
			pass
		if params["Vorname"] == None:
			params["Vorname"] = ""
		if params["Nachname"] == None:
			params["Nachname"] = ""
		if params["Geburtstag"] == None:
			params["Geburtstag"] = ""
		if params["Startkontostand"] == "":
			params["Startkontostand"] = "0,00"
                try:
                        params["Fokus"] = request.session["Fokus"]
                except:
                        params["Fokus"] = "Vorname"
		return render_to_response("Betreute/Neu.html", params)
	else:
		Vorname = request.POST.get("Vorname")
		Nachname = request.POST.get("Nachname")
		Geburtstag = request.POST.get("Geburtstag")
		Startkontostand = request.POST.get("Startkontostand")
		setsession( request, "Vorname", Vorname )
		setsession( request, "Nachname", Nachname )
		setsession( request, "Geburtstag", Geburtstag )
		setsession( request, "Startkontostand", Startkontostand )
		Fehler = ""
		try:
			Startkontostand = float( Startkontostand.replace(",",".") )
		except:
			Fehler = "Bitte geben sie einen g&uuml;ltigen Kontostand ein"
			Fokus = "Startkontostand"
		try:
			Geburtstag = datetime.strptime( Geburtstag, "%d.%m.%Y" )
		except:
			Fehler = "Bitte geben das Geburtsdatum im Format TT.MM.JJJJ ein"
			Fokus = "Geburtstag"
		if Nachname == "":
			Fehler = "Bitte geben sie einen Nachnamen ein"
			Fokus = "Nachname"
		if Vorname == "":
			Fehler = "Bitte geben sie einen Vornamen ein"
			Fokus = "Vorname"
		if Fehler != "":
			setsession(request, "Fehler", Fehler)
			setsession(request, "Fokus", Fokus)
			return HttpResponseRedirect("/Kontenverwaltung/Betreute/Neu")
		else:
			Betreute.objects.create( vorname=Vorname, nachname=Nachname, geburtstag=Geburtstag, startkontostand=Startkontostand, sozialarbeiter=request.user.id )
#			Betreuter.save()
			setsession(request, "Nachricht", "Neuer Betreuer <i>"+Vorname+" "+Nachname+"</i> wurde gespeichert.")
			return HttpResponseRedirect("/Kontenverwaltung/Betreute/Liste")

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def liste( request ):
	setsession( request, "Vorname", "" )
	setsession( request, "Nachname", "" )
	setsession( request, "Geburtstag", "" )
	setsession( request, "Startkontostand", "" )
	params = defaults(request)
	params["MenuBetreute"] = True
	params["Betreute"] = []
	if params["Sekretariat"]:
		Bs = Betreute.objects.all().order_by("nachname")
	else:
		Bs = Betreute.objects.filter( sozialarbeiter=request.user.id ).order_by("nachname")
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
	params["options"]["Einen neuen Klienten eingeben"] = "Betreute/Neu"
#	params["options"]["Einen Betreuten aus der Liste l&ouml;schen"] = "javascript:alert('Betreuten l&ouml;schen','Um einen Betreuten zu l&ouml;schen, klicken sie in der &Uuml;bersicht auf das rote Symbol neben dem entsprechenden Betreuten');"
	return render_to_response("Betreute/Liste.html", params)

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def details( request ):
	params = defaults(request)
	params["MenuBetreute"] = True
	params["Betreute"] = []
	try:
		params["ID"] = request.GET.get("ID")
		B = Betreute.objects.get( id=params["ID"] )
		Sozialarbeiter = AuthUser.objects.get( id=B.sozialarbeiter )
	except:
		setsession(request, "Fehler", "Fehler beim Abrufen des Klienten")
		return HttpResponseRedirect("/Kontenverwaltung/Betreute/Liste")
	params["vorname"] = B.vorname
	params["nachname"] = B.nachname
	params["geburtstag"] = B.geburtstag
	params["startkontostand"] = currency(float(B.startkontostand))
	params["sozialarbeiter"] = Sozialarbeiter.first_name+" "+Sozialarbeiter.last_name
	Vorgaenge = []
	kontostand = float(B.startkontostand)
	Zs = Zuteilungen.objects.filter( betreuter=B.id )
	for Z in Zs:
		G = Geldeingaenge.objects.get( id=Z.geldeingang )
		Vorgaenge.append( { "datum":G.datum, "vorgang":"Zuteilung", "details":G.geldgeber, "betrag":currency(float(Z.betrag)), "link":"/Kontenverwaltung/Zuteilungen/Entfernen?ID="+str(Z.id) } )
		kontostand = kontostand + Z.betrag
	As = Auszahlungen.objects.filter( betreuter=B.id )
	for A in As:
		Vorgaenge.append( { "datum":A.datum, "vorgang":"Auszahlung", "details":"", "betrag":currency(-float(A.betrag)), "link":"/Kontenverwaltung/Auszahlungen/Entfernen?ID="+str(A.id) } )
		kontostand = kontostand - A.betrag
	Fs = Forderungen.objects.filter( schuldner=B.id )
	for F in Fs:
		Ts = Tilgungen.objects.filter( forderung=F.id )
		for T in Ts:
			Vorgaenge.append( { "datum":T.datum, "vorgang":"Tilgung", "details":F.kreditor, "betrag":currency(-float(T.betrag)), "link":"/Kontenverwaltung/Tilgungen/Entfernen?ID="+str(T.id) } )
			kontostand = kontostand - T.betrag
	params["Vorgaenge"] = sorted( Vorgaenge, key=lambda x: x['datum'] ) 
	params["Anzahl"] = len( Vorgaenge )
	params["kontostand"] = currency(kontostand)
	return render_to_response("Betreute/Details.html", params)

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def entfernen( request ):
	params = defaults(request)
	if params["Sekretariat"]:
		return HttpResponseRedirect("/")
	params["MenuBetreute"] = True
	if request.method == "GET":
		try:
			params["ID"] = request.GET.get("ID")
			B = Betreute.objects.get( id=params["ID"] )
			params["Betreuter"] = B.vorname+" "+B.nachname+", geb. am "+B.geburtstag.strftime("%d.%m.%Y")
			return render_to_response("Betreute/Entfernen.html", params)
		except:
			setsession(request, "Fehler", "Fehler beim Abrufen des ausgew&auml;hlten Betreuten")
			return HttpResponseRedirect("/Kontenverwaltung/Betreute/Liste")
	else:
		try:
			ID = request.POST.get("ID")
			Zuteilungen.objects.filter( betreuter=ID ).delete()
			Auszahlungen.objects.filter( betreuter=ID ).delete()
			for F in Forderungen.objects.filter( schuldner=ID ):
				Tilgungen.objects.filter( forderung=F.id ).delete()
			Forderungen.objects.filter( schuldner=ID ).delete()
			B = Betreute.objects.get( id=ID )
			Name = B.vorname+" "+B.nachname
			Betreute.objects.get( id=request.POST.get("ID") ).delete()
			setsession(request, "Nachricht", "Betreuter <i>"+Name+"</i> wurde gel&ouml;scht.")
		except:
			setsession(request, "Fehler", "Fehler beim L&ouml;schen des ausgew&auml;hlten Betreuten")
		return HttpResponseRedirect("/Kontenverwaltung/Betreute/Liste")


