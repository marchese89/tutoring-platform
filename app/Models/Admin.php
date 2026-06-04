<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'user_id',
        'last_login_at',
        'tax_code',
        'photo_path',
        'street',
        'house_number',
        'city',
        'province',
        'postal_code',
        'vat_number',
        'stripe_secret_key',
    ];
}
