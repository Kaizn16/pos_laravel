<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    use HasFactory;

    protected $table = 'purchases';
    protected $primaryKey = 'purchase_id';
    protected $fillable = [
        'stock_id',
        'product_id',
        'supplier_id',
        'purchase_price',
        'stock_amount',
        'invoice_number',
        'notes',
        'purchase_date',
        'is_return',
        'is_deleted',
    ];    

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
