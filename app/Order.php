<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    protected $fillable = array('user_id','amount','total','currency','pay_type','token');

    public function orderItems()
    {
        return $this->belongsToMany('Product') ->withPivot('amount','total');
    }
}
