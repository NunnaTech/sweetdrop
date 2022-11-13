<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = ['id', 'email', 'password', 'name', 'first_surname', 'second_surname', 'phone', 'is_active', 'role_id'];
    protected $hidden = ['is_active', 'password', 'tokens'];
    protected $casts = ['is_active' => 'boolean'];
    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function orders()
    {
        return $this->belongsTo(Order::class, 'delivered_by');
    }

    public function dealers()
    {
        return $this->belongsToMany(
            Store::class,
            'dealers',
            'user_id',
            'store_id',
        );
    }
}
