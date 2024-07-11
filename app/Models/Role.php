<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Role extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'role';
    protected $primaryKey = 'role_id';
    protected $fillable = [
        'role_type', 
        'is_deleted'
    ];

}
