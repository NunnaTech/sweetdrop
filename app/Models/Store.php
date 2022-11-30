<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';
    protected $fillable = ['id', 'name', 'phone', 'address', 'zipcode', 'owner', 'is_active'];
    protected $hidden = ['is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public $timestamps = false;


    public function dealers()
    {
        return $this->belongsToMany(
            User::class,
            'dealers',
            'store_id',
            'user_id',
        )->where('is_active', true)->with('totalVisits', 'totalOrders');
    }
}
