<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = ['id', 'folio', 'request_date', 'is_completed', 'deliver_date',
        'total', 'received_by', 'is_active', 'delivered_by', 'store_id','status_id'];
    protected $hidden = ['is_active'];
    protected $casts = ['is_active' => 'boolean', 'is_completed' => 'boolean'];
    public $timestamps = false;


    public function delivered()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function sales(){
        return $this->hasMany(Sale::class, 'order_id')->with('product');
    }

    public function observations(){
        return $this->hasMany(Observation::class, 'order_id')->with('images');
    }


}
