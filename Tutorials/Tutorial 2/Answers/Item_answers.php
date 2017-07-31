<?php
class Item
{
    protected $name = "";
    protected $price = 1;
    protected $quantity = 1;

    public function __construct($name, $price, $quantity)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function display()
    {
        return sprintf("Item: %-6s Price: $%-6.2f Quantity: %-6d Cost: $%-6.2f", $this->name, $this->price, $this->quantity, $this->calculate_cost());
    }

    public function calculate_cost()
    {
        return $this->price * $this->quantity;
    }

    public function get_name()
    {
        return $this->name;
    }
}
 ?>
