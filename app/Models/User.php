<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Laravel timestamps are NOT present in our SQL table
    public $timestamps = false;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'birth_date',
        'profile_picture',
        'description',
        'is_public',
        'is_admin',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'date',
        'is_public' => 'boolean',
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

}
