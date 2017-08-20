import json
import csv

f = open("stops.json", "r", encoding="utf-8")

data = json.load(f)

with open('stops_processed.csv', 'w', newline='') as csvfile:
    spamwriter = csv.writer(csvfile, delimiter=',', quotechar='"', quoting=csv.QUOTE_MINIMAL)
    spamwriter.writerow(["stop_id", "stop_name", "stop_desc", "stop_lat", "stop_lon", "zone_id", "stop_url", "stop_code", "stop_street", "stop_city", "stop_region", "stop_postcode", "stop_country", "location_type", "parent_station", "stop_timezone", "wheelchair_boarding", "direction", "position", "the_geom"])

    for item in data:
        spamwriter.writerow([item["stop_id"], item["stop_name"], item["stop_desc"], item["stop_lat"], item["stop_lon"], item["zone_id"], item["stop_url"], item["stop_code"], item["stop_street"], item["stop_city"], item["stop_region"], item["stop_postcode"], item["stop_country"], item["location_type"], item["parent_station"], item["stop_timezone"], item["wheelchair_boarding"], item["direction"], item["position"], item["the_geom"]])


f.close()
