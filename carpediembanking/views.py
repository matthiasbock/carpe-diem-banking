# -*- coding: iso-8859-15 -*-

from Django.carpediembanking.models import *
from Django.carpediembanking.includes import *

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

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
def Index( request ):
	if request.user.id != None:
		return HttpResponseRedirect('/Kontenverwaltung/Intranet/')
	else:
		return HttpResponseRedirect('/Kontenverwaltung/login/')

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
def Baustelle( request ):
	params = defaults( request )
	return render_to_response("Baustelle.html", params)

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
def Login(request):
	user = None
	users = User.objects.all().order_by('last_name')
	if request.method == "GET":
		return render_to_response('login.html', {'fehlgeschlagen':False, 'Benutzer':users})
	else:
		username = request.POST['username']
		password = request.POST['password']
		user = authenticate(username=username, password=password)
		if user != None and user.is_active:
			login(request, user)
			setsession(request, 'login', 'successfull')
			return HttpResponseRedirect('/Kontenverwaltung/Intranet/')
		else:
			return render_to_response('login.html', {'fehlgeschlagen':True, 'Benutzer':users})

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
def Logout(request):
	logout(request)
	return HttpResponseRedirect('/Kontenverwaltung/login/')

def ping(request):
	a = Autorisierteips.objects.get( name=request.POST.get("Name") )
	a.ip = request.META['REMOTE_ADDR']
	return HttpResponse("reply")

