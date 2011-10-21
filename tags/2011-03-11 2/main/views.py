# -*- coding: iso-8859-15 -*-

from Kontenverwaltung.main.models import *

from django.shortcuts import render_to_response
from django.template import RequestContext
from django.http import HttpResponse, HttpResponseRedirect
from django.core.paginator import Paginator, InvalidPage, EmptyPage
from django.views.decorators.cache import cache_control

# Login

from django.contrib.auth import authenticate, login, logout
from django.contrib.auth.decorators import login_required
from django.contrib.auth.models import User
from django.contrib.auth.forms import AuthenticationForm
#from django.forms.models import model_to_dict

from urllib import urlencode
from datetime import datetime

def setsession(request, key, value):
	if request.session.get(key, False):
		del request.session[key]
	request.session[key] = value

def Login(request):
	user = None
	users = User.objects.all()
	if request.method == "GET":
		return render_to_response('registration/login.html', {'fehlgeschlagen':False, 'Benutzer':users})
	else:
		username = request.POST['username']
		password = request.POST['password']
		user = authenticate(username=username, password=password)
		if user != None and user.is_active:
			login(request, user)
			setsession(request, 'login', 'successfull')
			return HttpResponseRedirect('/Kontenverwaltung/Betreute/Liste')
		else:
			return render_to_response('registration/login.html', {'fehlgeschlagen':True, 'Benutzer':users})

def Logout(request):
	logout(request)
	return HttpResponseRedirect('/Kontenverwaltung/')

# Exports

def Kontostand(request):
	Saldo = 0
	geldeingaenge = []
	for g in Geldeingaenge.objects.filter( benutzer=request.user.id ):
		Saldo = Saldo + g.betrag
		geldeingaenge.append( g.id )
	for z in Zuteilungen.objects.filter( geldeingang__in=geldeingaenge ):
		Saldo = Saldo - z.betrag
	return Saldo		

def currency( value ):
	if float(value) == 0.:
		return "<font color=black><b>0,00 &euro;</b></font>"
	elif float(value) < 0:
		return str("<font color=red><b>- %.2f &euro;</b></font>" % float(-value)).replace(".",",")
	else:
		return str("<font color=green><b>+ %.2f &euro;</b></font>" % float(value)).replace(".",",")

def defaults( request ):
	params = {}
	params["Anrede"] = request.user.first_name+" "+request.user.last_name
	params["Kontostand"] = currency( Kontostand(request) )
	params["options"] = {}
	params["showoptions"] = False
	try:
		params["Fehler"] = request.session["Fehler"]
	except:
		params["Fehler"] = ""
	params["Fehlerbox"] = ( params["Fehler"] != "" )
	setsession(request, "Fehler", "")
	try:
		params["Nachricht"] = request.session["Nachricht"]
	except:
		params["Nachricht"] = ""
	params["Nachrichtenbox"] = ( params["Nachricht"] != "" )
	setsession(request, "Nachricht", "")
	return params

