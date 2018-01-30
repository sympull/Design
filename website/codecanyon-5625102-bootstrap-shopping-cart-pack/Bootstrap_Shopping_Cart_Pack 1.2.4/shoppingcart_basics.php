<?php
//	
//	Aenea - www.beaenea.com
//	Bootstrap BootstrapShoppingCart for codecanyon.net
//
require_once "BootstrapShoppingCart/config.php";                // load settings file
require_once "BootstrapShoppingCart/BootstrapShoppingCart.php";     // load class BootstrapShoppingCart
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="vendor/bootstrap-3.1.1-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="BootstrapShoppingCart/BootstrapShoppingCart.css">
        
    </head>
    <body>
        <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]--> 

        <!-- header menu -->
        <?php include("BootstrapShoppingCart/inc.menu.php"); ?>

        <div class="container">
            <div class="row">
                <div class="span12">
                    <h3>Bootstrap Shopping Cart Pack: the basics</h3>
                    <br>
                    <h3>Basic usage</a> 
                        <h4>Show product grid</h4>

                        <pre>
$category=1; // list category where the id=1
$shoppingcart_vertical=0; // shoppingcart_vertical=1 / shoppingcart_vertical=0
include "BootstrapShoppingCart/products_grid.php";  // list products</pre>
                        <br />
                        <h4>Show Shoppingcart</h4>
                        To show the Vertical Shoppingcart:
                        <pre>
&lt;div id="BSCart"&gt;
&lt;/div&gt;</pre>
                        To show the Horizontal Shoppingcart:
                        <pre>
&lt;div id="BSCart-Horizontal"&gt;
&lt;/div&gt;</pre>
                        <br />
                        <h3>Installation</a> 
                            <h4>Install: Includes top in your pages</h4>
                            <pre>&lt;?php 
require_once "BootstrapShoppingCart/config.php"; // load settings file
require_once "BootstrapShoppingCart/BootstrapShoppingCart.php"; // load class shoppingcart
?&gt;</pre><br />
                            <h4>Install: Shopping cart Styles</h4>
                            <pre>&lt;link rel="stylesheet" href="BootstrapShoppingCart/BootstrapShoppingCart.css"&gt;
&lt;link rel="stylesheet" href="BootstrapShoppingCart/animation.css"&gt;</pre><br />
                            <h4>Install: Load JQUERY Shopping Cart</h4>
                            <pre>&lt;script&gt;
$(document).ready(function() {                
	$("#BSCart-Horizontal").load("BootstrapShoppingCart/addproduct_horizontal.php");
});            
&lt;/script&gt;</pre><br />
                            <h3>Use shoppingcart class</a> 
                                <h4>Init Shoppingcart</h4>
                                Just specify which session name do you want to supply for the Shopping cart, in this example we use 'my_shop'
                                <pre>$cart=new shoppingcart();
$cart->init('my_shop');</pre> 
                                <br />
                                <h4>Add product</h4>
                                Add 3 quantity of the product with the id of 100, always add the ':::' after the id, you'll see why in the next example
                                <pre>$cart->add_cart("100:::",3);</pre>
                                <br />
                                <h4>Add product with extra</h4>
                                Add 5 quantity of the product with the id of 1 , and color black, seperated by ::: , 
                                why? because sometimes you have the same product but differnt colors or different shapes, therefor the id stays the same, we just add the extra info after ::: , 
                                it can be repeated with other things too , for ex: $cart->add_cart("1:::black:::big",5) , 
                                here we added 5 of the product with the id having black color and medium shaped. Later on you can explode the :::'s and get the valyes in an array as u like
                                <pre>$cart->add_cart("1:::black",5);</pre>
                                <br />
                                <h4>Update product cart</h4>
                                Update the cart when the buyer wishes to buy 4 more of these (id 3)                    
                                <pre>$cart->update_cart("3:::white",5);</pre>
                                <br />
                                <h4>Delete a product</h4>
                                Remove all the orders having product id of 1 and color black                    
                                <pre>$cart->remove_cart("1:::black");</pre>
                                <pre>$cart->remove_cart("1:::"); //here we remove all the orders having product id of 100</pre>
                                <br />
                                <h4>Empty the basket</h4>
                                Remove all the orders having product id of 1 and color black                    
                                <pre>$cart->removeall_cart();</pre>
                                <br />
                                <h4>Is empty?</h4>
                                Checking if the cart is empty or not                    
                                <pre>if(!$cart->get_cart()){  
    echo "no basket found";
    }else{
    print_r($cart->get_cart());  //this returns the values stored in a array , you can iterate and get the values after that
}</pre>
                                <br />
                                <h4>Count items</h4>
                                Count all items (int)                   
                                <pre>echo $cart->countall_cart();</pre>

                                </div>

                                </div>


                                </div>


                                <!-- /container --> 
                                <script src="vendor/jquery-2.1.0.min.js"></script>
                                <script src="vendor/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script> 
                                <!-- BootsrapBootstrapShoppingCart.js -->
                                <script src="BootstrapShoppingCart/BootstrapShoppingCart.js"></script>  
                                <script>
                                    $(document).ready(function() {
                                        // show current shoppingcart
                                        $("#BSCart").load("BootstrapShoppingCart/addproduct_vertical.php");
                                    });            
                                </script>
                                </body>
                                </html>
