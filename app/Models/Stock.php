<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'stock';
    protected $primaryKey = 'stock_id';
    protected $fillable = [
        'supplier_id',
        'stock_amount'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
