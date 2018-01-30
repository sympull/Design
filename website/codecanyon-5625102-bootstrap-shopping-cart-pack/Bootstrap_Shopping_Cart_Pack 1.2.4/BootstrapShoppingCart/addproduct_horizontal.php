<?php
//	
//	Aenea - www.beaenea.com
//	Bootstrap BootstrapShoppingCart for codecanyon.net
//
require_once "config.php";                // load settings file
require_once "BootstrapShoppingCart.php";     // load class BootstrapShoppingCart


//echo " id-".$_GET['id']." type-".$_GET['type']; // debug ajax params
//
// initialize the shoppingcart
$cart = new shoppingcart();
$cart->init('my_shop');

// add product
if (isset($_GET['quantity'])) {
    $quantity = (int) $_GET['quantity'];
}

if ((isset($_GET['id'])) && ($_GET['type'] <> 'select') && ($quantity > 0)) { // if set id product ?
    // product with type?  
    if (strlen($_GET['type']) > 0) {
        $product = $_GET['id'] . ':::' . $_GET['type'];
        $cart->add_cart($product, $quantity); // adding 1 product
        echo '<script>$("#BSC_animation_cart").addClass("slideDown");</script>'; // Make ccs animation shoppingcart    
        // no are type! then add ...         
    } else {
        $product = $_GET['id'] . ':::';
        $cart->add_cart($product, $quantity); // adding 1 product
        echo '<script>$("#BSC_animation_cart").addClass("slideDown");</script>'; // Make ccs animation shoppingcart
    }
} else { // if $_GET['select'] then i need a selection! make alert
    if (isset($_GET['type']) && ($_GET['type'] == 'select')) {
        ?><script>alert("After add to cart\nSelect the attribute color, size ...");</script><?php
    }
}

// if empty?
if (!$cart->get_cart()) {
    
} else {
    $bsc = $cart->get_cart(); // get shoppingcart
    $render = new renderchartshop($bsc);  // render shoppingcart
    $shoppingcart = $render->get();   // get render
    // totals
    $total_price = 0;
    $total_products = 0;
    // list current shoppingcart
    foreach ($shoppingcart as $cart) {
        // sum totals 
        $total_products+=$cart['quantity']; // quantity
        if ($cart['offer']) {
            $total_price+=$cart['price_offer'] * $cart['quantity']; // subtotal price
        } else {
            $total_price+=$cart['price'] * $cart['quantity']; // subtotal price
        }
    } // foreach
}
?>
<div class="ShoppingCartHead Horizontal" style="height: 90px">
    <div id="BSC_animation_cart" >
        <img src="images/shoppingcart.png" /> Shopping
        <?php
        echo '<span  style="font-weight: normal;" >';
        $cart = new shoppingcart();
        $cart->init('my_shop');

        if (!$cart->get_cart()) {
            echo " Empty! Drag or Add products";
        } else {

            echo '<div  >Total ' . $total_products . " products ";
            echo moneyformat($total_price);
            if (EW_SHIPPING > 0) {
                echo " + shipping " . moneyformat(EW_SHIPPING);
            } //shipping?
            echo "</div>";
            echo " <a href='shoppingcart_view.php'>View shopping cart / Buy</a>";
        }
        echo "</span>";
        ?>
    </div>
</div>
