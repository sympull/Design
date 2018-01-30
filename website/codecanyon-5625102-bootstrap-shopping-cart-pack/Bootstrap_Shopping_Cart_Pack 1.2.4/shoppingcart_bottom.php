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
        <link rel="stylesheet" href="BootstrapShoppingCart/animation.css">
        <script src="vendor/jquery-2.1.0.min.js"></script>
        <script src="vendor/jquery-ui-1.10.4.custom.min.js"></script>
    </head>
    
    <body>
        <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]--> 

        <!-- header menu -->
        <?php include("BootstrapShoppingCart/inc.menu.php"); ?>

        <!-- Product categories -->
        <div class="container">
            <div class="row" id="animation_slideDown">
                <div class="span12">
                    <?php
                    //
                    // List categories
                    //
            include "BootstrapShoppingCart/categories_list.php";
                    //
                    // List categories
                    //
            ?>
                </div>
            </div>
        </div>

        <!-- Products --> 
        <div class="container">
            <div class="row">
                <div class="span12">
                    <div class="row">                    
                        <section class="products">
                            <?php
                            //
                            // Show Product Grid 
                            //
                            if (isset($_GET['category'])) {
                                $category = $_GET['category'];
                            } else {
                                $category = 1;
                            } // list category from table bsc_category
                            $shoppingcart_vertical = 0; // if the is vertical shoppingcart_vertical=1 / is top or bottom are horitzontal -> shoppingcart_vertical=0
                            $_SESSION['LastProductPage'] = $_SERVER["REQUEST_URI"]; // take the name of this page for return
                            include "BootstrapShoppingCart/products_grid.php";  // list products 
                            //
                            // Show Product Grid 
                            //
                            ?>                   
                        </section>   
                    </div>
                </div>
            </div>
        </div>

        <!-- Shopping cart -->
        <div class="navbar-nav navbar-fixed-bottom">
            <?php
            //
            // Show Shopping Cart
            //
            ?>
            <div id="BSCart-Horizontal">
            </div>
            <?php
            //
            // Show Shopping Cart
            //
            ?>
        </div>


        <!-- /container --> 
        <script src="vendor/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script> 

        <!-- BootsrapBootstrapShoppingCart.js -->
        <script src="BootstrapShoppingCart/BootstrapShoppingCart.js"></script>  
        <script>
            $(document).ready(function() {
                // show current shoppingcart
                $("#BSCart-Horizontal").load("BootstrapShoppingCart/addproduct_horizontal.php");
                // anim buttons categories 
                $('#animation_slideDown').addClass("slideDown");
            });            
        </script>
    </body>
</html>
