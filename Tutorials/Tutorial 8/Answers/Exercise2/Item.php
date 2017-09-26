<?php
class Item implements JsonSerializable
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
        return sprintf("Item: %-6s Price: $%-6.2f Quantity: %-6d Cost: $%-6.2f", $this->name, $this->price, $this->quantity, $this->calculateCost());
    }

    public function calculateCost()
    {
        return $this->price * $this->quantity;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function JsonSerialize()
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity
        ];
    }
}

if (!debug_backtrace()) {
    // Debugginng output.
    $item = new Item("milk", 2.50, 2);
    echo $item->display();
}
 ?>
