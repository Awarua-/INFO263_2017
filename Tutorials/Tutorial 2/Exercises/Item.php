<?php
class Item
{

    public function __construct($name, $price, $quantity)
    {
        return;
    }

    public function display()
    {
        return sprintf("Item: %-6s Price: $%-6.2f Quantity: %-6d Cost: $%-6.2f", $this->name, $this->price, $this->quantity, $this->calculate_cost());
    }

    public function calculate_cost()
    {
        return 0;
    }
    
    public function get_name()
    {
        return $this->name;
    }
}
 ?>
