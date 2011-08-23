DEBUG = True
TEMPLATE_DEBUG = True 

PROJECT_OPTION_ROOT = "/var/www/Django/carpediembanking/"
PROJECT_OPTION_HEAD_URL = "carpediembanking/"

ADMINS = (
    ('Matthias Bock', 'matthias.bock@hu-berlin.de'),
)

MANAGERS = ADMINS

DATABASE_ENGINE = 'mysql'
DATABASE_NAME = 'Django'
DATABASE_USER = 'kafka'
DATABASE_PASSWORD = ''
DATABASE_HOST = 'localhost'
DATABASE_PORT = ''

LANGUAGE_CODE = 'de'
TIME_ZONE = 'Germany/Berlin'
USE_I18N = False

MEDIA_URL = '/carpediembanking/static/'
MEDIA_ROOT = '/var/www/Django/carpediembanking/static/'

SECRET_KEY = ''

MIDDLEWARE_CLASSES = (
    'django.middleware.common.CommonMiddleware',
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
)

TEMPLATE_LOADERS = (
    'django.template.loaders.filesystem.load_template_source',
    'django.template.loaders.app_directories.load_template_source',
)

TEMPLATE_DIRS = (
    '/var/www/Django/carpediembanking/templates',
)

ROOT_URLCONF = 'carpediembanking.urls'

INSTALLED_APPS = (
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.sites',
    'django.contrib.admin',
    'carpediembanking.main',
)

LOGIN_URL = '/carpediembanking/Login'

