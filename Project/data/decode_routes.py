import json
import csv

f = open("route_data.json", "r", encoding="utf-8")

data = json.load(f)

with open('routes_processed.csv', 'w', newline='') as csvfile:
    spamwriter = csv.writer(csvfile, delimiter=',', quotechar='"', quoting=csv.QUOTE_MINIMAL)
    spamwriter.writerow(["route_id", "route_short_name", "route_long_name", "route_type", "agency_id", "route_desc", "route_url", "route_color", "route_text_color", "id"])
    id = 1
    for item in data:
        spamwriter.writerow([item["route_id"], item["route_short_name"], item["route_long_name"], item["route_type"], item["agency_id"], item["route_desc"], item["route_url"], item["route_color"], item["route_text_color"], id])
        id += 1


f.close()
