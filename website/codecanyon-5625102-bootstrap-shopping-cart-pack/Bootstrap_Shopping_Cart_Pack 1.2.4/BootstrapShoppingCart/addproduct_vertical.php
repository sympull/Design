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



// delete product
if ((isset($_GET['action'])) && ($_GET['action'] == 'delete')) {
    $productId = $_GET['id'];
    if ((isset($_GET['type'])) && ($_GET['type'] > 0)) { // any type?
        $prod = $productId . ":::" . $type = $_GET['type'];
    } else {
        $prod = $productId . ":::";
    }
    $cart->remove_cart($prod);
}

// add product
if (isset($_GET['quantity'])) {
    // Quantity 
    if (isset($_GET['quantity'])) {
        $quantity = (int) $_GET['quantity'];
    }

    if ((isset($_GET['id'])) && ($_GET['type'] <> 'select') && ($quantity > 0)) { // if set id product ?
        // product with type?  
        if (strlen($_GET['type']) > 0) {
            $product = $_GET['id'] . ':::' . $_GET['type'];
            $cart->add_cart($product, $quantity); // adding 1 product
            echo '<script>$("#BSC_animation_cart").addClass("slideUp");$("#BSC_animation_count").addClass("slideDown");</script>'; // Make ccs animation shoppingcart     
        // no are type! then add ...         
        } else {
            $product = $_GET['id'] . ':::';
            $cart->add_cart($product, $quantity);              // adding 1 product
            echo '<script>$("#BSC_animation_cart").addClass("slideUp");$("#BSC_animation_count").addClass("slideDown");</script>'; // Make ccs animation shoppingcart
        }
    } else { // if $_GET['select'] then i need a selection! make alert
        if (isset($_GET['type']) && ($_GET['type'] == 'select')) {
            ?><script>alert("After add to cart\nSelect the attribute color, size ...");</script><?php
            }
            }
} // adding product 

?>

<div class="ShoppingCartHead" style="width: 170px">
    <img  id="BSC_animation_cart" src="images/shoppingcart.png" />Shopping
</div>

            <?php
            // if empty?
            if (!$cart->get_cart()) {
                echo "<div class='ShoppingCartFoot'>";
                echo "Empty!<br/>";
                echo "Drag or Add products";
                echo "</div>";
            } else {
                $bsc = $cart->get_cart(); // get shoppingcart
                $render = new renderchartshop($bsc);  // render shoppingcart
                $shoppingcart =$render->get();   // get render 
                
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
                    // display row
                    echo '<div class="ShoppingCart">'; // open row product in cart
                    echo $cart['name'] . ' ' . $cart['name_type'];
                    if ($cart['offer']) {
                        echo "<br/>" . $cart['quantity'] . " x " . moneyformat($cart['price_offer']);
                    } else {
                        echo "<br/>" . $cart['quantity'] . " x " . moneyformat($cart['price']);
                    }
                    // delete img 
                    if (isset($types[0]['id'])) {
                        $type = $types[0]['id'];
                    } else {
                        $type = "";
                    }
                    echo '<div class="delete" id="delete_button" data-id="' . $cart['id_product'] . '" data-type="' . $cart['id_type'] . '" onmouseout="this.style.cursor = \'pointer\';" onmouseover="this.style.cursor = \'pointer\';"><span class="glyphicon glyphicon-remove" style="top: -5px;" ></span></div>';
                    echo "</div>"; //close row product in cart
                } // foreach
                //
                // echo totals shoppingcart
                echo "<div class='ShoppingCartFoot'>";
                echo "<div id='BSC_animation_count' >Total " . $total_products . " products<br/>";
                echo moneyformat($total_price);
                if (EW_SHIPPING > 0) {
                    echo " + shipping " . moneyformat(EW_SHIPPING);
                } //shipping? 
                echo "</div><br/><br/>";
                echo "<a href='shoppingcart_view.php'>View shopping cart / Buy</a>";
                echo "</div>";
            }
            /*
            $cart = new shoppingcart();
            $cart->init('my_shop');
            dd($cart->get_cart());                // debug cartshop  
            */
            
            ?>

<script>
  // Delete button in shoppingcart (onlyvertical)
  //#delete_button
    $(".delete").click(function(e) {
        id=$(this).attr("data-id");
        type=$(this).attr("data-type");
        $("#BSCart").load("BootstrapShoppingCart/addproduct_vertical.php?action=delete&id=" + id + "&type="+type);
        e.preventDefault();
    });
</script>