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
        'discount_amount',
        'subtotal',
        'grand_total',
        'pay_amount',
        'change_amount',
        'user_id',
        'status',
        'payment_method_id',
        'transaction_date',
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
    
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'transaction_id');
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