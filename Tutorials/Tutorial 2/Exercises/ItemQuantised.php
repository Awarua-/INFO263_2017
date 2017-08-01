<?php
require_once("Item.php");

class ItemQuantised extends Item
{
    const GRAM = "g";
    const KILOGRAM = "Kg";
    const LITRE = "L";
    const MILLILITER = "ml";

    protected $units = self::GRAM;

    public function __construct($name, $price, $quantity, $units)
    {
        // Complete the constructor
        return; //remove
    }

    public function display()
    {
        return sprintf("Item: %-6s Price per %s: $%-6.2f Quantity: %d %ss Cost: $%-6.2f", $this->name, ucfirst(strtolower($this->units)), $this->price, $this->quantity, ucfirst(strtolower($this->units)), $this->calculate_cost());
    }
}
 ?>
