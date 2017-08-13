import json
import csv

f = open("trips.json", "r", encoding="utf-8")

data = json.load(f)

with open('tips_processed.csv', 'w', newline='') as csvfile:
    spamwriter = csv.writer(csvfile, delimiter=',', quotechar='"', quoting=csv.QUOTE_MINIMAL)
    spamwriter.writerow(["route_id", "service_id", "trip_id", "trip_headsign", "direction_id", "block_id", "shape_id","trip_short_name", "id"])
    id = 1
    for item in data:
        spamwriter.writerow([item["route_id"], item["service_id"], item["trip_id"], item["trip_headsign"], item["direction_id"], item["block_id"], item["shape_id"], item["trip_short_name"], id])
        id += 1


f.close()
