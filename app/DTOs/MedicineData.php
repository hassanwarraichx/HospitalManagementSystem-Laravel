<?php

namespace App\DTOs;

class MedicineData
{
    public string $name;
    public string $brand;
    public int $stock;
    public string $expiry_date;
    public float $price;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->brand = $data['brand'];
        $this->stock = $data['stock'];
        $this->expiry_date = $data['expiry_date'];
        $this->price = $data['price'];
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'brand' => $this->brand,
            'stock' => $this->stock,
            'price' => $this->price,
            'expiry_date' => $this->expiry_date,
        ];
    }
}
