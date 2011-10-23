# -*- coding: iso-8859-15 -*-

from django.views.decorators.cache import cache_control
from django.contrib.auth.decorators import login_required

def auth_ip_decorator(the_func):
    """
    Make another a function more beautiful.
    """
    def _decorated(*args, **kwargs):
        return the_func(*args, **kwargs)
    return _decorated

@cache_control( max_age=1, must_revalidate=True, no_cache=True )
@auth_ip_decorator
@login_required
def global_decorator(the_func):
    """
    Make another a function more beautiful.
    """
    def _decorated(*args, **kwargs):
        return the_func(*args, **kwargs)
    return _decorated

