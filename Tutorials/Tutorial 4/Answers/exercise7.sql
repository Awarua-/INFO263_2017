# Exercise 7.1
select *
	from nz_street_address_beta
	where town_city = "Wellington" and (suburb_locality = "Melrose" or suburb_locality = "Makara");

select count(*)
	from nz_street_address_beta
	where town_city = "Christchurch" and suburb_locality = "Woolston";

# Exercise 7.2

select *
	from nz_street_address_beta
	where full_road_name = "Ponsonby Road" and address_number < 30
	order by address_number;

# Exercise 7.3

select count(distinct suburb_locality, town_city)
	from nz_street_address_beta;

select count(distinct suburb_locality, town_city)
	from nz_street_address_beta
	where town_city = "Wellington";

# Exercise 7.4

select full_address
	from nz_street_address_beta
	where town_city = "Christchurch" and full_road_name = "Ilam Road"
	order by address_number DESC;

select distinct town_city, suburb_locality
	from nz_street_address_beta
	order by town_city, suburb_locality;

select distinct town_city, suburb_locality
	from nz_street_address_beta
	order by town_city, suburb_locality DESC;

# Exercise 7.5

select town_city, count(*)
	from nz_street_address_beta
	group by town_city;
