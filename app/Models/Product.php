<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = ['id', 'sku', 'name', 'description', 'price', 'image', 'is_active'];
    protected $hidden = ['is_active'];
    protected $casts = ['is_active'=>'boolean'];
    public $timestamps = false;
}
