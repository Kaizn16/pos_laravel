<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UnitOfMeasure extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'uom';
    protected $primaryKey = 'uom_id';
    protected $fillable = [
        'uom_name',
        'description',
        'status',
        'is_deleted'
    ];
}
