<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_id',
        'first_name',
        'middle_name',
        'last_name',
        'gender_id',
        'contact_no',
        'address',
        'is_deleted',
    ];

    public function gender() 
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

}
