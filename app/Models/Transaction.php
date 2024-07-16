<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';
    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'transaction_id',
        'transaction_number',
        'customer_id',
        'total_item',
        'discount_id',
        'subtotal',
        'grand_total',
        'user_id',
        'status',
        'is_deleted',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->transaction_number = $model->generateTransactionNumber();
        });
    }


    public function generateTransactionNumber()
    {
        $lastTransaction = static::orderBy('transaction_id', 'desc')->first();
        $lastNumber = $lastTransaction ? $lastTransaction->transaction_number : 'TRN00000000';
        $number = (int)substr($lastNumber, 3) + 1;
        return 'TRN' . str_pad($number, 8, '0', STR_PAD_LEFT);
    }
}