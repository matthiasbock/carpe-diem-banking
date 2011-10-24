DEBUG = True
TEMPLATE_DEBUG = True 

PROJECT_OPTION_ROOT = "/var/www/Django/"
PROJECT_OPTION_HEAD_URL = "Django/"

ADMINS = (
    ('Matthias Bock', 'matthias.bock@hu-berlin.de'),
)

MANAGERS = ADMINS

from secrets import *

DATABASE_ENGINE = 'mysql'
DATABASE_NAME = 'Django'
DATABASE_USER = 'kafka'
DATABASE_HOST = ''
DATABASE_PORT = ''

TIME_ZONE = 'Germany/Berlin'
LANGUAGE_CODE = 'de'
USE_I18N = True

CACHE_MIDDLEWARE_SECONDS = 1

SITE_ID = 1

MEDIA_ROOT = '/var/www/media/'
MEDIA_URL = '/media/'
ADMIN_MEDIA_PREFIX = '/media/'


TEMPLATE_LOADERS = (
    'django.template.loaders.filesystem.load_template_source',
    'django.template.loaders.app_directories.load_template_source',
#     'django.template.loaders.eggs.load_template_source',
)

MIDDLEWARE_CLASSES = (
    'django.middleware.common.CommonMiddleware',
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
)

LOGIN_URL = '/Django/Login'

ROOT_URLCONF = 'Django.urls'

TEMPLATE_DIRS = (
    '/var/www/Django/carpediembanking/registration',
    '/var/www/Django/carpediembanking/templates',
)

INSTALLED_APPS = (
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.sites',
    'django.contrib.admin',
    'Django.carpediembanking',
)

