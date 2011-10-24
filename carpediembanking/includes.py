# -*- coding: iso-8859-15 -*-

from Django.carpediembanking.models import *

import datetime
from operator import itemgetter

def setsession(request, key, value):
	if request.session.get(key, False):
		del request.session[key]
	request.session[key] = value

def Saldo(request):
	result = 0
#	...
	return result

def currency( value ):
	if float(value) == 0.:
		return "<font color=black><b>0,00 &euro;</b></font>"
	elif float(value) < 0:
		return str("<font color=red><b>- %.2f &euro;</b></font>" % float(-value)).replace(".",",")
	else:
		return str("<font color='#007406'><b>+ %.2f &euro;</b></font>" % float(value)).replace(".",",")

def defaults( request ):
	params = {}
	params["Heute"] = datetime.date.today()
	params["Anrede"] = "Gast"
	params["Klientenkasse"] = False
	params["Klienten"] = []
	params["WeitereKlienten"] = []
	if request.user.id != None:

		Ich = AuthUser.objects.get( id=request.user.id )				# Das bin ich
		try:
			MeineKasse = Betreuer.objects.get( auth_user_id=request.user.id ).klientenkasse
		except:
			MeineKasse = 1
		params["Anrede"] = Ich.first_name+" "+Ich.last_name
		params["Saldo"] = currency( Saldo(request) )

		if request.method == "GET":							# welcher Klient wird gerade angezeigt ?
			try:
				Klient = int(request.GET.get("Klient"))
			except:
				Klient = None
		elif request.method == "POST":
			try:
				Klient = int(request.POST.get("Klient"))
			except:
				Klient = None
		if Klient != None:
			K = Klienten.objects.get( id=Klient )
			params["Klientenname"] = K.vorname+" "+K.nachname
			params["KlientenID"] = K.id

		for K in Klienten.objects.filter( betreuer=Ich.id ):				# das sind meine Klienten
			selected = Klient != None and Klient == K.id
			params["Klienten"].append( {"selected":selected, "id":K.id, "vorname":K.vorname, "nachname":K.nachname} )
		get = itemgetter('nachname')
		params["Klienten"].sort(key = get)

		for Kollege in Betreuer.objects.filter( klientenkasse=MeineKasse ):	# das sind die Klienten meiner Kollegen
			if Kollege.id != Ich.id:					# aber nicht nochmal meine Klienten auflisten
				for K in Klienten.objects.filter( betreuer=Kollege.id ):
					selected = Klient != None and Klient == K.id
					params["WeitereKlienten"].append( {"selected":selected, "id":K.id, "vorname":K.vorname, "nachname":K.nachname} )
		get = itemgetter('nachname')
		params["WeitereKlienten"].sort(key = get)

	try:								# Fehlerbox
		params["Fehler"] = request.session["Fehler"]
	except:
		params["Fehler"] = ""
	params["Fehlerbox"] = ( params["Fehler"] != "" )
	setsession(request, "Fehler", "")

	try:								# Nachrichtenbox
		params["Nachricht"] = request.session["Nachricht"]
	except:
		params["Nachricht"] = ""
	params["Nachrichtenbox"] = ( params["Nachricht"] != "" )
	setsession(request, "Nachricht", "")

	return params


