# Exercise 8.1

select customer_name, customer_address, order_date
	from customer, customer_orders.order
	where customer.customer_id = customer_orders.order.customer_id
	order by customer_name;

select customer_name, count(*)
	from customer join customer_orders.order
	on customer.customer_id = customer_orders.order.customer_id
	group by customer_name;

select customer_state, count(*)
	from customer join customer_orders.order using(customer_id)
	group by customer_state;

# Exercise 8.2

select *
	from customer natural join customer_orders.order
	order by customer_id;

# Exercise 8.3

select *
	from customer left join customer_orders.order using(customer_id);

select count(*)
	from customer left join customer_orders.order using(customer_id)
	where order_id is null;

# Exercise 8.4

select *
	from customer full join customer_orders.order using(customer_id);

# Exercise 8.5

select *
	from customer join customer_orders.order using(customer_id)
	join order_line using(order_id)
	join product using(product_id);
