<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    protected $table = 'orderlines';
    //protected $fillable = array('user_id','product_id','amount','total','currency');
    protected $fillable = array('order_id','product_id','amount','price','total','currency');

    public function Order()
    {
        return $this->belongsTo('Order','order_id');
    }
}
