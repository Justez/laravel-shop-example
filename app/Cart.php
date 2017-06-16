<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    //protected $fillable = array('user_id','product_id','amount','total','currency');
    protected $fillable = array('product_id','amount','total','currency');

    public function Product()
    {

        return $this->belongsTo('Product','product_id');

    }
}
