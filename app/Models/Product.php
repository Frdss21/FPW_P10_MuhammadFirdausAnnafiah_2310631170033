<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['product_name', 'unit', 'type', 'information', 
    'qty', 'producer', 'supplier_id'];

    protected $table = 'products';

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
