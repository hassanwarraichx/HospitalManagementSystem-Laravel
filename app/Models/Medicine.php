<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'brand',
        'stock',
        'expiry_date',
        'price',
    ];

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }
}
