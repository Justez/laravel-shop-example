<?php

namespace Tests\Unit;

use Auth;
use Session;
use App\User;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\CartsController;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testEmptyCart()
    {
        Session::push('cart','itemName');
        $this->assertContains('itemName', Session::get('cart'),true);
        $cartObj = new CartsController;
        $cartObj->emptyCart();
        $this->assertEmpty(Session::get('cart'));
    }

    public function testCheckout()
    {
        $cartItems = (!null==Session::get('cart')) ? count(array_sum(array_column(Session::get('cart'),'amount'))) : 0;
        $user = factory('App\User')
            ->create();
        Auth::login($user);
        $data = array(
            'id'     => 2,
            'amount' => 4,
            'total'  => 1,
        );
        Session::push('cart',$data);
        $cartTotal = array_sum(
            array_column(
                Session::get('cart'),'total'));
        $cartObj = new CartsController;
        $this->assertEquals($cartObj->checkout()->cart_products,Session::get('cart'));
        $this->assertEquals($cartObj->checkout()->cart_total,$cartTotal);
        User::where('id',Auth::user()->id)
            ->delete();
    }
}
