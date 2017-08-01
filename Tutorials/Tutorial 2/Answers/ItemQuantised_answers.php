<?php
require_once("Item_answers.php");

class ItemQuantised extends Item
{
    const GRAM = "g";
    const KILOGRAM = "Kg";
    const LITRE = "L";
    const MILLILITER = "ml";

    protected $units = self::GRAM;

    public function __construct($name, $price, $quantity, $units)
    {
        parent::__construct($name, $price, $quantity);
        $this->units = $units;
    }

    public function display()
    {
        return sprintf("Item: %-6s Price per %s: $%-6.2f Quantity: %d %ss Cost: $%-6.2f", $this->name, ucfirst(strtolower($this->units)), $this->price, $this->quantity, ucfirst(strtolower($this->units)), $this->calculate_cost());
    }
}

if (!debug_backtrace()) {
    // do useful stuff
    $item = new ItemQuantised("oranges", 4.30, 2.2, ItemQuantised::KILOGRAM);
    echo $item->display();
}
 ?>
