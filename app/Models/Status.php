<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'status';
    protected $fillable = ['id', 'name', 'is_active'];
    protected $hidden = ['is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public $timestamps = false;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
