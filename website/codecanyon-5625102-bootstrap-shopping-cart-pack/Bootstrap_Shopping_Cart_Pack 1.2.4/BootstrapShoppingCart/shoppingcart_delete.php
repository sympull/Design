<?php
//	
//	Aenea - www.beaenea.com
//	Bootstrap BootstrapShoppingCart for codecanyon.net
//
require_once "config.php";                // load settings file
require_once "BootstrapShoppingCart.php";     // load class BootstrapShoppingCart

$cart=new shoppingcart();
$cart->init('my_shop');
	
$productId  = $_GET['id'];
if (strlen($_GET['type'])) { // any type?
        $prod= $productId.":::".$type=$_GET['type'];
        } else {
        $prod= $productId.":::";
        }

$cart->remove_cart($prod);

?>
<style>
body{
background-color: #f5f5f5;
}
</style>
<script>
window.location.href = "../shoppingcart_view.php"; // go back to shoppingcart
</script>


