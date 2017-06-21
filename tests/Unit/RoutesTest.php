<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class RoutesTest extends TestCase
{
    public $baseUrl = 'http://localhost:8001';
    public function testRoutes()
    {
        $this->visit('/')
            ->see('Store');

        $this->click('Sign In')
            ->see('Login')
            ->seePage('/login');

        $this->click('Sign Up')
            ->see('Register')
            ->seePage('/register');

        $this->click('My Orders')
            ->see('Your Orders:')
            ->seePage('/orders');

        $this->click('My BitCoin Orders')
            ->see('BTC Orders')
            ->seePage('/BTCorders');

        $this->click('Logout')
            ->see('Store')
            ->seePage('/');

        $this->click('Add to Cart')
            ->see('Checkout')
            ->seePage('/');

        $this->click('Empty cart')
            ->see('Store')
            ->seePage('/');

        $this->click('Checkout')
            ->see('Payment method')
            ->seePage('/checkout');

        $this->click('/product')
            ->see('Store')
            ->seePage('/');

        $this->visit('/cart/add')
            ->see('Store');
        $this->visit('/cart/remove')
            ->see('Store');
        $this->visit('/cart/delete')
            ->see('Store');
        $this->visit('/order')
            ->see('Order');
    }
}
