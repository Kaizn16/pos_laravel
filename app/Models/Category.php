<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Category extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'category';
    protected $primaryKey = 'category_id';
    protected $fillable = [
        'category_name',
        'description',
        'status',
        'is_deleted'
    ];
}
