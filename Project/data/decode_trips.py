
from load_data import perform_API_call, connection_to_db, close_connection

URL = "https://api.at.govt.nz/v2/gtfs/trips"

data = perform_API_call(URL)

cnx = connection_to_db()

cursor = cnx.cursor()

# Truncate existing table
disable_f_key_checks = ("SET FOREIGN_KEY_CHECKS = 0")
enable_f_key_checks = ("SET FOREIGN_KEY_CHECKS = 1")

truncate = ("TRUNCATE routes")

cursor.execute(disable_f_key_checks)
cursor.execute(truncate)
cursor.execute(enable_f_key_checks)

cnx.commit()

stmt = "INSERT INTO trips (route_id, service_id, trip_id, trip_headsign, direction_id, block_id, shape_id, trip_short_name) VALUES(%s, %s, %s, %s, %s, %s, %s, %s)"

values = []

for item in data:
    values.append((item["route_id"], item["service_id"], item["trip_id"], item["trip_headsign"], item["direction_id"], item["block_id"], item["shape_id"], item["trip_short_name"]))

cursor.execute(disable_f_key_checks)
cursor.executemany(stmt, values)
cursor.execute(enable_f_key_checks)

cnx.commit()

close_connection()
