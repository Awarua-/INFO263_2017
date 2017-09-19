from load_data import perform_API_call, connection_to_db, close_connection

URL = "https://api.at.govt.nz/v2/gtfs/routes"


def run():
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

    stmt = "INSERT INTO routes (route_id, route_short_name, route_long_name, \
    route_type, agency_id, route_desc, route_url, route_color, \
    route_text_color) VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s)"

    values = []

    for item in data:
        values.append((item["route_id"], item["route_short_name"],
                       item["route_long_name"], item["route_type"],
                       item["agency_id"], item["route_desc"],
                       item["route_url"], item["route_color"],
                       item["route_text_color"]))

    cursor.executemany(stmt, values)

    cnx.commit()

    close_connection()


if __name__ == "__main__":
    run()
