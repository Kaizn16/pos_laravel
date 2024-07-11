<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Gender extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'gender';
    protected $primaryKey = 'gender_id';
    protected $fillable = ['gender_type'];
}
