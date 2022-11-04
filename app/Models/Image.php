<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';
    protected $fillable = ['id', 'image', 'observation_id'];
    protected $hidden = [];
    protected $casts = [];
    public $timestamps = false;

    public function observation()
    {
        return $this->belongsTo(Observation::class, 'observation_id');
    }
}
