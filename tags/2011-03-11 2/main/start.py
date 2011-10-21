# -*- coding: iso-8859-15 -*-

from django.http import HttpResponse, HttpResponseRedirect
from django.shortcuts import render_to_response
from django.views.decorators.cache import cache_control
from django.contrib.auth.decorators import login_required

from Kontenverwaltung.main.views import defaults

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@login_required
def start( request ):
	params = defaults(request)
	params["MenuStart"] = True
	options = {}
	options["Meine Betreuten auflisten"]		= "Betreute/Liste"
	options["Einen neuen Betreuten eingeben"]	= "Betreute/Neu"
	options["Meine letzten Buchungen anzeigen"]	= "Buchungen/Letzte"
	options["Einen Geldeingang verbuchen"]		= "Geldeingaenge/Neu"
	options["Einen Geldeingang zuteilen"]		= "Zuteilungen/Neu"
	options["Bargeld auszahlen"]			= "Auszahlungen/Neu"
	options["Eine &Uuml;berweisung t&auml;tigen"]	= "Ueberweisungen/Neu"
	options["Eine neue Forderung er&ouml;ffnen"]	= "Forderungen/Neu"
	options["Eine Forderung tilgen"]		= "Forderungen/Tilgen"
	options["Offene Forderungen auflisten"]		= "Forderungen/Liste"
	params["options"] = options
	params["showoptions"] = True
	return render_to_response("start.html", params)
