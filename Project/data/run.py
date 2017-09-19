import decode_routes
import decode_trips
import decode_stops


def main():
    decode_stops.run()
    decode_trips.run()
    decode_routes.run()
    

if __name__ == "__main__":
    main()
