<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'product_name',
        'category_id',
        'product_code',
        'purchase_price',
        'selling_price',
        'uom', // Unit of Measure per item that you want to sell
        'expiration_date',
        'stock_id',
        'supplier_id',
        'product_image',
        'status',
        'is_deleted'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function stock() 
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

}
