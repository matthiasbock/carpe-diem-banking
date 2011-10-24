# -*- coding: iso-8859-15 -*-

from Django.carpediembanking.models import *
from Django.carpediembanking.includes import *

from django.shortcuts import render_to_response
from django.http import HttpResponse, HttpResponseRedirect
from django.views.decorators.cache import cache_control
from django.contrib.auth.decorators import login_required

import datetime
from operator import itemgetter

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
#login_required...
def Klient( request ):			# Umsätze des Klienten anzeigen
	params = defaults( request )
	params["Anzahl"] = 0
	try:
		Klient = request.GET.get("Klient")
	except:
		Klient = None
	if Klient == None:
		return HttpResponseRedirect("Intranet")
	Klient = Klienten.objects.get( id=Klient )
	params["Startdatum"] = Klient.startdatum
	if Klient.startsaldo != None:
		params["Startsaldo"] = currency( Klient.startsaldo )
		params["Saldo"] = Klient.startsaldo
	
	# alle Salden aufzählen
	params["Anzahl"] = 0
	params["Ergebnisse"] = []
	
	# Zuteilungen
	for E in Zuteilungen.objects.filter( anklient = Klient.id ):
		try:
			B = Betreuer.objects.get( id=E.betreuer )
		except:
			B = Betreuer.objects.get( id=1 )
		params["Ergebnisse"].append( {"typ":"Zuteilung", "id":E.id, "datum":E.datum, "vorgang":"Zuteilung, "+B.vorname+" "+B.nachname, "betrag":currency( E.teilbetrag ) } )
		params["Anzahl"] += 1
		params["Saldo"] += E.betrag
	
	# Einzahlungen
#	for Einzahlung in Klienteneinzahlungen.objects.filter( klient = Klient.id ):
	
	# Auszahlungen
	for E in Klientenauszahlungen.objects.filter( anklient = Klient.id ):
		try:
			B = Betreuer.objects.get( id=E.betreuer )
		except:
			B = Betreuer.objects.get( id=1 )
		K = Klientenkassen.objects.get( id=E.ausklientenkasse )
		params["Ergebnisse"].append( {"typ":"Auszahlung", "id":E.id, "datum":E.datum, "vorgang":"Barauszahlung, "+B.vorname+" "+B.nachname+"<br/><i>"+K.name+"</i>", "betrag":currency( -E.betrag ) } )
		params["Anzahl"] += 1
		params["Saldo"] -= E.betrag
	
	# Überweisungen
	_Ueberweisungen = Klientenueberweisungen.objects.filter( fuerklient = Klient.id )
	
	# nach Datum ordnen
	get = itemgetter('datum')
	params["Ergebnisse"].sort(key = get)

	if Klient.startsaldo != None:
		params["Saldo"] = currency( params["Saldo"] )
	
	params["Forderungsanzahl"] = 0
	params["Forderungen"] = Schulden.objects.filter( klient = Klient.id )
	params["Restschuld"] = currency( 0 )
	return render_to_response("Klient.html", params)

def Forderungen( request ):
	params = defaults( request )
	return render_to_response("Baustelle.html", params)

def Einzahlung( request ):
	params = defaults( request )
	return render_to_response("Baustelle.html", params)

def Auszahlung( request ):
	params = defaults( request )
	if request.method == "GET":		# Seite: Auszahlung anzeigen
		try:
			Klient = int(request.GET.get("Klient"))
		except:
			Klient = None
		if Klient == None or Klient == 0:
			setsession( request, "Fehler", "Klienten-ID wurde nicht an das Auszahlungsformular übergeben ! Bitte Administrator kontaktieren." )
			return HttpResponseRedirect("Intranet")
		return render_to_response("Auszahlung.html", params)
	elif request.method == "POST":		# Auszahlung speichern
		try:
			Klient = int(request.POST.get("Klient"))
		except:				# haben wir einen Parameter Klient bekommen ?
			Klient = None
		if Klient == None or Klient == 0:
			setsession( request, "Fehler", "Klienten-ID wurde nicht an den Auszahlungsprozess übergeben ! Bitte Administrator kontaktieren." )
			return HttpResponseRedirect("Intranet")
		try:
			B = Betreuer.objects.get( auth_user_id=request.user.id )
		except:
			B = Betreuer.objects.get( id=1 )
		try:
			Betrag = request.POST.get("Betrag").replace(",",".")
			if float(Betrag) <= 0:	# ist es ein gültiger Betrag ?
				raise
		except:
			setsession( request, "Fehler", "Das ist kein gültiger Betrag ! Auszahlung nicht gespeichert." )
			return HttpResponseRedirect("Klient?Klient="+str(Klient))
						# Auszahlung speichern !
		Klientenauszahlungen.objects.create( datum=datetime.date.today(), betreuer=B.id, betrag=Betrag, ausklientenkasse=B.klientenkasse, anklient=Klient )
		setsession( request, "Nachricht", "Auszahlung wurde gespeichert." )
		return HttpResponseRedirect("Klient?Klient="+str(Klient))

def Ueberweisung( request ):
	params = defaults( request )
	return render_to_response("Baustelle.html", params)

def NeueSchulden( request ):
	params = defaults( request )
	return render_to_response("Baustelle.html", params)

def Entfernen( request ):
	try:
		returnto = request.GET.get("returnto")
	except:
		returnto = ""
	if returnto == "":
		returnto = "Intranet"
	try:
		Typ = request.GET.get("Typ")
		ID = request.GET.get("ID")
	except:
		return HttpResponseRedirect( returnto )
	if Typ == "Auszahlung":
		try:
			Klientenauszahlungen.objects.get( id=ID ).delete()
			setsession( request, "Nachricht", "Auszahlung wurde gelöscht." )
		except:
			setsession( request, "Fehler", "Auszahlung konnte nicht gelöscht werden!" )
	return HttpResponseRedirect( returnto )
