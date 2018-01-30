<?php
//	
//	Aenea - www.beaenea.com
//	Bootstrap BootstrapShoppingCart for codecanyon.net
//
require_once "config.php";                // load settings file
require_once "BootstrapShoppingCart.php";     // load class BootstrapShoppingCart

$cart=new shoppingcart();
$cart->init('my_shop');
	
$productId  = $_POST['hidProductId'];
$itemQty    = $_POST['txtQty'];
$types     = $_POST['hidType'];
$numItem    = count($itemQty);

//echo $numItem;

for ($i = 0; $i < $numItem; $i++) {
	$qty = (int)$itemQty[$i];
	

	if ($qty > 0) {
		//update
		if (isset($types[$i])) { // update & has type?
			$prod= $productId[$i].":::".$types[$i];
			} else {
			$prod= $productId[$i].":::";
			}		
		
		//$Qty = int($newQty[$i]);
		
		$cart->update_cart($prod,$qty);
					
		} else {
		//delete
		if (isset($types[$i])) { // detele & has type?
			$prod= $productId[$i].":::".$types[$i];
			} else {
			$prod= $productId[$i].":::";
			}
		$cart->remove_cart($prod);	
		}
	}
?>
<style>
body { background-color: #f5f5f5; }
</style>
<script>
window.location.href = "../shoppingcart_view.php"; //return to shoppingcart
</script>