<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Discount extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'discount';
    protected $primaryKey = 'discount_id';
    protected $fillable = [
        'discount_type',
        'discount_percentage',
        'is_deleted'
    ];
}
