<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_method';
    protected $primaryKey = 'payment_method_id';
    protected $fillable = [
        'payment_method_type', 
        'is_deleted'
    ];
}
