DEBUG = True
TEMPLATE_DEBUG = True 

PROJECT_OPTION_ROOT = "/var/www/Django/carpediembanking/"
PROJECT_OPTION_HEAD_URL = "carpediembanking/"

ADMINS = (
	('webmaster', 'webmaster@localhost'),
)

MANAGERS = ADMINS

DATABASES = {
	'default': {
		'ENGINE':	'django.db.backends.mysql',
		'NAME':		'carpediembanking',
		'USER':		'Django',
		'PASSWORD':	'Django-PW',
		'HOST':		'',
		'PORT':		'',
		}
	}

TEMPLATE_DIRS = (
	'/var/www/Django/carpediembanking',
	'/var/www/Django/carpediembanking/templates',
)

LANGUAGE_CODE = 'de'
TIME_ZONE = 'Europe/Berlin'
USE_I18N = False

MEDIA_URL = '/Django/carpediembanking/static/'
MEDIA_ROOT = '/var/www/Django/carpediembanking/static/'

SECRET_KEY = ''

MIDDLEWARE_CLASSES = (
	'django.middleware.common.CommonMiddleware',
	'django.contrib.sessions.middleware.SessionMiddleware',
#	'django.contrib.auth.middleware.AuthenticationMiddleware',
)

TEMPLATE_LOADERS = (
    'django.template.loaders.filesystem.load_template_source',
    'django.template.loaders.app_directories.load_template_source',
)

ROOT_URLCONF = 'carpediembanking.urls'

INSTALLED_APPS = (
#	'django.contrib.auth',
	'django.contrib.contenttypes',
	'django.contrib.sessions',
	'django.contrib.sites',
	'django.contrib.admin',
	'carpediembanking',
)

LOGIN_URL = '/Django/carpediembanking/Login'

