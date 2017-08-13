import json

f = open("vehicle instance data, 10_08_2017__21_30.json", "r", encoding="utf-8")
n = open("vehicles processed.json", "w", encoding="utf-8")

data = json.load(f)
vehicles = set()
for item in data["response"]["entity"]:
    if "trip_update" in item and "vehicle" in item["trip_update"]:
        vehicles.add(item["trip_update"]["vehicle"]["id"])

import csv
with open('vehicles processed.csv', 'w', newline='') as csvfile:
    spamwriter = csv.writer(csvfile)
    spamwriter.writerow(["id"])
    for id in list(vehicles):
        spamwriter.writerow([id])

output = json.dumps({"id": list(vehicles)})
n.write(output)


f.close()
n.close()
