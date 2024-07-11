<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Supplier extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';
    protected $fillable = [
        'supplier_name',
        'address',
        'email',
        'contact_number',
        'status',
        'is_deleted'
    ];

}
