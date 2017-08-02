<?php
class Item
{
    // Add missing properties.

    public function __construct($name, $price, $quantity)
    {
        // Complete the constructor.
    }

    public function display()
    {
        return sprintf("Item: %-6s Price: $%-6.2f Quantity: %-6d Cost: $%-6.2f", $this->name, $this->price, $this->quantity, $this->calculate_cost());
    }

    public function calculate_cost()
    {
        // Complete the function so that it returns the cost for the number of items.
        return 0;
    }

    public function get_name()
    {
        return $this->name;
    }
}

if (!debug_backtrace()) {
    // Debugging output.
    $item = new Item("milk", 2.50, 2);
    echo $item->display();
}
 ?>
