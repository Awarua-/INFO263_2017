import requests
import MySQLdb

API_KEY = "862db80e8ba6459ab8be38d7459404a2"
cnx = None


def perform_API_call(url):
    headers = {"Ocp-Apim-Subscription-Key": API_KEY}

    r = requests.get(url, headers=headers)

    return r.json()['response']


def connection_to_db():
    config = {
        'user': 'admin',
        'passwd': '1enQIVdNMsa7il@sJA!5rH8yqNs%Y0',
        'host': 'csse-info263.canterbury.ac.nz',
        'db': 'akl_transport'
    }

    try:
        cnx = MySQLdb.connect(**config)
        return cnx
    except Exception as err:
        print(err)
    else:
        cnx.close()


def close_connection():
    if cnx is not None:
        cnx.close()
