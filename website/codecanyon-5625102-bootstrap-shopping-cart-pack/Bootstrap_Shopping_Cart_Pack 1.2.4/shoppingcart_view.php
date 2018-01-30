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
        <script src="vendor/jquery-2.1.0.min.js"></script>
        <script src="vendor/jquery-ui-1.10.4.custom.min.js"></script>

    </head>
    <body>
        <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]--> 

        <!-- header menu -->
        <?php include("BootstrapShoppingCart/inc.menu.php"); ?>

        <div class="container">
            <div class="row">
                <div class="col-md-2 hidden-xs">
                    <div class="nav-list affix">

                        <div class="ShoppingCartHead"><div style="padding:10px;" >Shopping Cart</div></div><br />
                        This is all right?<br /><br />
                        <a class="btn btn-success checkout_button" >Proceed to checkout<i class="icon-chevron-right"></i></a>
                    </div> 
                </div>

                <div class="col-md-10">
                    <!--Products-->
                    <div class="container"> 
                        <div class="col-md-10">
                            <?php
                            //
                            // Show Shopping Cart 
                            //
                            $cart = new shoppingcart(); // init shoppingcart
                            $cart->init('my_shop');
                            if (!$cart->get_cart()) {  //checking if the cart is empty or not
                                echo "<h3>BootstrapShoppingCart is empty</h3>";
                                echo "<a href='" . $_SESSION['LastProductPage'] . "'>Go back</a>"; // return to last product page
                            } else { // the shoppingcart has content
                                include("BootstrapShoppingCart/shoppingcart.php"); //show shoppingcart                            
                            }  // the shoppingcart has content 
                            //
                        // Show Shopping Cart 
                            //
                        ?>
                        </div>
                    </div> 
                </div>
            </div>


        </div>


        <!-- /container --> 
        
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
