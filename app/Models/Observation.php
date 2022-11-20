<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $table = 'observations';
    protected $fillable = ['id', 'comment', 'is_active', 'order_id'];
    protected $hidden = ['is_active'];
    protected $casts = ['is_active'=>'boolean'];
    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function images(){
        return $this->hasMany(Image::class, 'observation_id');
    }
}
