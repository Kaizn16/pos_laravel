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
        'uom_id', // Unit of Measure per item that you want to sell
        'product_image',
        'status',
        'is_deleted'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function uom()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchases::class, 'product_id', 'product_id');
    }
}
