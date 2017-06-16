<?php
use App\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
       //patikrinti ar jau code yra įrašytas
         Product::updateOrCreate(array(
           'code'=>'latte1',
           'title'=>'Latte',
           'description'=>'Plain latte',
           'imgurl'=>'https://s3-us-west-2.amazonaws.com/beachbody-blog/uploads/2017/04/Beachbody-Blog-Pumpkin-Spice-Latte.jpg',
           'price'=>'2',
           'salesprice'=>'2',
           'currency'=>'EUR',
           'deleted'=>0
          ));
         Product::updateOrCreate(array(
           'code'=>'irish1',
           'title'=>'Irish strawberry',
           'description'=>'Irish coffee with a strawberry liqueur and whipped cream',
           'imgurl'=>'http://www.seriouseats.com/recipes/assets_c/2017/01/20161221-irish-coffee-variations-vicky-wasik-1-thumb-1500xauto-436100.jpg',
           'price'=>'3.40',
           'salesprice'=>'2',
           'currency'=>'EUR',
           'deleted'=>0
         ));
         Product::updateOrCreate(array(
           'code'=>'tea_mint1',
           'title'=>'Mint tea',
           'description'=>'Natural mint tea',
           'imgurl'=>'http://www.elitehandicrafts.com/blog/wp-content/uploads/2016/05/mint-tea1.jpg',
           'price'=>'1.20',
           'salesprice'=>'1.2',
           'currency'=>'EUR',
           'deleted'=>0
         ));
         Product::updateOrCreate(array(
           'code'=>'latte2',
           'title'=>'Cookie Latte',
           'description'=>'Latte with cookie crumbs',
           'imgurl'=>'http://foodthinkers.tplsandbox.com/assets/cookielatte.jpg',
           'price'=>'2.5',
           'salesprice'=>'2.5',
           'currency'=>'EUR',
           'deleted'=>0
          ));
         Product::updateOrCreate(array(
           'code'=>'irish2',
           'title'=>'Irish vanilla',
           'description'=>'Irish coffee with a vanilla liqueur and whipped cream',
           'imgurl'=>'http://2.bp.blogspot.com/-2082dviwU3c/VlSTWYUlnxI/AAAAAAAABPU/mXExJzUBWhI/s1600/Burly%2BBrown%2BSugar%2BIrish%2BCoffee.jpg',
           'price'=>'3.40',
           'salesprice'=>'2',
           'currency'=>'EUR',
           'deleted'=>0
         ));
         Product::updateOrCreate(array(
           'code'=>'tea_camomile1',
           'title'=>'Chamomile tea',
           'description'=>'Natural chamomile tea',
           'imgurl'=>'http://img.aws.livestrongcdn.com/ls-article-image-673/cpi.studiod.com/www_livestrong_com/photos.demandstudios.com/getty/article/139/18/491869724_XS.jpg',
           'price'=>'1.20',
           'salesprice'=>'1.2',
           'currency'=>'EUR',
           'deleted'=>0
         ));
         Product::updateOrCreate(array(
           'code'=>'mocha',
           'title'=>'Chocolate mocha',
           'description'=>'Chocolate mocha',
           'imgurl'=>'http://cookdiary.net/wp-content/uploads/images/Mocha-Coffee_6838.jpg',
           'price'=>'2.5',
           'salesprice'=>'2',
           'currency'=>'EUR',
           'deleted'=>0
          ));
         Product::updateOrCreate(array(
           'code'=>'irish3',
           'title'=>'Irish Original',
           'description'=>'Irish coffee with a brandy and whipped cream',
           'imgurl'=>'http://food.fnr.sndimg.com/content/dam/images/food/fullset/2012/2/23/0/02_FW-1B16_000_04_63956_s4x3.jpg.rend.hgtvcom.616.462.jpeg',
           'price'=>'3.40',
           'salesprice'=>'2',
           'currency'=>'EUR',
           'deleted'=>0
         ));
         Product::updateOrCreate(array(
           'code'=>'tea_black',
           'title'=>'Black tea',
           'description'=>'Black tea',
           'imgurl'=>'http://www.rivertea.com/blog/wp-content/uploads/2013/01/black-tea-cup-e1359634422907.jpg',
           'price'=>'1.20',
           'salesprice'=>'1.2',
           'currency'=>'EUR',
           'deleted'=>0
         ));

     }
}
