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
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="brand">
                        <a  href="index.php"><img src="images/logo_header.png" width="40" height="40" /> BSC</a>
                    </div>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="shoppingcart_left.php">Shoppingcart Left</a></li>
                        <li><a href="shoppingcart_right.php">Shoppingcart Right</a></li>
                        <li><a href="shoppingcart_top.php">Shoppingcart Top</a></li>
                        <li><a href="shoppingcart_bottom.php">Shoppingcart Bottom</a></li>
                        <li><a href="shoppingcart_basics.php">Basics</a></li>
                        <li><a href="admin/">Admin</a></li>
                        <li><a href="http://codecanyon.net/item/bootstrap-shopping-cart-pack/5625102" >Buy it!</a></li>
                        <? // only for smartphone ?>
                        
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>



        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3>Bootstrap Shopping Cart Pack</h3>
                    <p>Ready-to-use Bootstrap Shopping Cart Pack for your php website!<br />
                        It's a perfect solution for medium and small shops, perfect to convert the web site to a webstore<br />
                        Select the format:<br /><br />
                    </p>
                </div>
                <div class="col-md-3" >
                    <a href="shoppingcart_left.php" target="_blank">
                        <h4>ShoppingCart Left</h4>
                        <img src="images/Bootstrap_shoppingcart_left.png" class="img-responsive" />
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="shoppingcart_right.php"  target="_blank">
                        <h4>ShoppingCart Right</h4>
                        <img src="images/Bootstrap_shoppingcart_right.png" class="img-responsive" />
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="shoppingcart_top.php"  target="_blank">
                        <h4>ShoppingCart Top</h4>
                        <img src="images/Bootstrap_shoppingcart_top.png" class="img-responsive" />
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="shoppingcart_bottom.php"  target="_blank">
                        <h4>ShoppingCart Bottom</h4>
                        <img src="images/Bootstrap_shoppingcart_bottom.png" class="img-responsive" />
                    </a>
                </div>
                <div class="col-md-12" style="padding-top:50px;">
                    <h3>More...</h3>

                </div>
                <div class="col-md-3">
                    <a href="shoppingcart_basics.php"  target="_blank">

                        <img src="images/Bootstrap_shoppingcart_basics.png" />
                        <h5>The basics</h5>
                    </a>
                    Learn the basic<br>
                    Install<br>              
                    Add to cart<br>
                    ...<br>

                </div>  
                <div class="col-md-3" style="color: #000;">
                    <a href="admin/"  target="_blank">

                        <img src="images/Bootstrap_shoppingcart_admin.png" />
                        <h5>Admin panel control</h5>
                    </a>
                    User: admin<br>
                    Pass: admin<br>
                    Write limited for this demo version
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
