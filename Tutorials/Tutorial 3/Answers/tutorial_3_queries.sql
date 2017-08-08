## 5.2
select * from customer where customer_state = "Utah";
## 5.4
select count(*) from customer where customer_state = "California";
## 5.5
select count(*), customer_state from customer group by customer_state;
