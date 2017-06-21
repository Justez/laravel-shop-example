<?php

namespace Tests\Unit;

use DB;
use App\Product;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\ProductsController;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDelete()
    {
        $id = Product::where('deleted','=','0')->first()->id;
        $productObj = new ProductsController;
        $productObj->delete($id);
        $this->assertEquals(Product::find($id)->deleted,1);
        Product::where('id',$id)->update(['deleted' => 0]);
    }

    public function testRenew()
    {
        $id = Product::where('deleted','=','0')->first()->id;
        Product::where('id',$id)->update(['deleted' => 1]);
        $productObj = new ProductsController;
        $productObj->renew($id);
        $this->assertEquals(Product::find($id)->deleted,0);
    }

    public function testGetIndex()
    {
        $productObj = new ProductsController;
        $this->assertEquals(
            count(Product::where('deleted','=','0')->get()),
            count($productObj->getIndex()->products)
        );
    }
}
