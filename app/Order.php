<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    //protected $fillable = array('user_id','user_name','address','total','currency');
    protected $fillable = array('address','total','currency');
    public function orderItems()
    {
        return $this->belongsToMany('Product') ->withPivot('amount','total');
    }
}
