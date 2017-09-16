from load_data import perform_API_call, connection_to_db, close_connection

URL = "https://api.at.govt.nz/v2/gtfs/stops"

data = perform_API_call(URL)

cnx = connection_to_db()

cursor = cnx.cursor()

# Truncate existing table
disable_f_key_checks = ("SET FOREIGN_KEY_CHECKS = 0")
enable_f_key_checks = ("SET FOREIGN_KEY_CHECKS = 1")

truncate = ("TRUNCATE stops")

cursor.execute(disable_f_key_checks)
cursor.execute(truncate)
cursor.execute(enable_f_key_checks)

cnx.commit()

stmt = "INSERT INTO stops (stop_id, stop_name, stop_desc, stop_lat, stop_lon, zone_id, stop_url, stop_code, stop_street, stop_city, stop_region, stop_postcode, stop_country, location_type, parent_station, stop_timezone, wheelchair_boarding, direction, position, the_geom) VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"

values = []

for item in data:
        try:
            values = (item["stop_id"], item["stop_name"], item["stop_desc"], item["stop_lat"], item["stop_lon"], item["zone_id"], item["stop_url"], item["stop_code"], item["stop_street"], item["stop_city"], item["stop_region"], item["stop_postcode"], item["stop_country"], item["location_type"], item["parent_station"], item["stop_timezone"], item["wheelchair_boarding"], item["direction"], item["position"], item["the_geom"])

            cursor.execute(stmt, values)
        except Exception as e:
            for key, value in item.items():
                if value is not None and type(value) is str:
                    item[key] = value.encode("ascii", errors="ignore").decode()

            values = (item["stop_id"], item["stop_name"], item["stop_desc"], item["stop_lat"], item["stop_lon"], item["zone_id"], item["stop_url"], item["stop_code"], item["stop_street"], item["stop_city"], item["stop_region"], item["stop_postcode"], item["stop_country"], item["location_type"], item["parent_station"], item["stop_timezone"], item["wheelchair_boarding"], item["direction"], item["position"], item["the_geom"])

            cursor.execute(stmt, values)

cnx.commit()

close_connection()
