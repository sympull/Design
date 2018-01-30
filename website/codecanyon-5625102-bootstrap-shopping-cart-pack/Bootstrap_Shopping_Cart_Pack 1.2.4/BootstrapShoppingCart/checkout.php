<form action="" method="post" id="paypalform" name="paypalform">


    <!-- Comments -->
    <table class="ShoppingCartHead" width="100%" height="40" border="0" cellspacing="0" cellpadding="0" >
        <tr>
            <td align="left">&nbsp;&nbsp;Shopping Cart Confirmation</td>        
        </tr>
    </table>
    <div style="margin-top: 30px;" > 
        <label>Have a comment?</label>   
        <textarea class="form-control" rows="3" name="comments"></textarea>   
    </div>



    <!-- Buttons -->
    <div class="ShoppingCartButtons" style="margin-bottom: 20px;">
        <a class="btn btn-default" href="shoppingcart_view.php">Go back</a>  
        <a class="btn btn-success" onclick="$('#paypalform').attr('action', 'shoppingcart_form_cash_on_delivery.php');
                document.paypalform.submit();">Cash on delivery +<?php echo moneyformat(EW_CASHONDELIVERY); ?><i class="icon-chevron-right"></i></a>
        <a class="btn btn-success" onclick="$('#paypalform').attr('action', 'BootstrapShoppingCart/dopayment_paypal_multiple_cart.php');
                document.paypalform.submit();">Pay with Paypal<i class="icon-chevron-right"></i></a>
    </div>

    <!-- Checkout -->    
    <table class="ShoppingCartHead" width="100%" height="40" border="0" cellspacing="0" cellpadding="0" >
        <tr>
            <td width="12%" align="left">&nbsp;&nbsp;Image</td>
            <td width="30%" align="left">Name</td>
            <td width="16%" align="center">Price</td>
            <td width="2%"></td>
            <td width="9%" align="center">Quantity</td>
            <td width="9%" align="center"></td>
            <td width="8%" align="right">Subtotal&nbsp;&nbsp;</td>
        </tr>
    </table>
    <?php
    $total = 0; // ShoppingCart Total
    $bsc = $cart->get_cart(); // get shoppingcart
    $render = new renderchartshop($bsc);  // render shoppingcart
    $shoppingcart = $render->get();   // get render 
    
    foreach ($shoppingcart as $cart) {
        $subtotal = 0; // ShoppingCart subtotal

        ?>
        <table class="table-striped" width="100%" height="80" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="12%" align="left">
                    <?php
                    // image
                    if (isset($cart['img'])) {
                        echo '<img src="assets/' . $cart['img'] . '" width="90" height="90">';
                    }
                    ?>
                </td>
                <td width="30%" align="left"  >
                    <?php
                    // name / product code                                                                                    
                    echo $cart['name']. ' ' . $cart['name_type'];
                    
                    ?></td>

                <td width="16%" align="center"><?php
                    // its a offer?
                    if ($cart['price_offer'] > 0) {
                        $price = $cart['price_offer'];
                        echo '<span class="price_overline">' . moneyformat($cart['price']) . '</span>';
                        echo '<span class="offer"> ' . moneyformat($cart['price_offer']) . '</span>';
                    } else {
                        $price = $cart['price'];
                        echo '<span class="price">' . moneyformat($cart['price']) . '</span>';
                    }
                    ?></td>
                <td width="2%"></td>
                <td width="9%" align="center"><?php echo $cart['quantity']; ?></td>
                <td width="9%" align="center"></td>
                <td width="8%" align="right"><?php
                    // make sum
                    $subtotal = $price * $cart['quantity'];
                    $total += $subtotal;
                    // show total of this row
                    echo '<span class="price">' . moneyformat($subtotal) . '</span>';
                    ?>&nbsp;&nbsp;
                </td>
            </tr>
        </table>
        <?php
    } // foreach product
    ?>
    <!-- Shipping -->
    <?php
// are shipping ?? configure shipping into config.php
    if (EW_SHIPPING > 0) {
        ?>
        <div class="ShoppingCartFoot">
            <table  width="100%" height="40" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="74%" align="left">&nbsp;</td>
                    <td width="14%" align="left"><span class="price">Subtotal</span></td>
                    <td width="12%" align="right"><?php echo '<span class="price">' . (moneyformat($total)) ?></span></td>
                </tr>
            </table>
        </div>
        <div class="ShoppingCartFoot_nodash">
            <table  width="100%" height="40" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="74%" align="left">&nbsp;</td>
                    <td width="14%" align="left"><span class="price">Shipping</span></td>
                    <td width="12%" align="right"><?php echo '<span class="price">' . (moneyformat(EW_SHIPPING)) ?></span></td>
                </tr>
            </table>
        </div>
        <?php
    }   // shipping?
    ?>
    <?php
    $_SESSION['discount'] = 0;
    // discount coupons ? 
    if (isset($_GET['coupon'])) {
        $coupon = $_GET['coupon'];
        $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
        $Db->query("SET NAMES 'utf8'");  // formating to utf8
        $row = $Db->search('bsc_coupons')->
                setColumns('*')->
                addFilter("code=%s", $coupon)->
                addFilter("visible=%d", 1)->
                getRows();

        if (count($row) > 0) { // exist this coupon
            $_SESSION['discount'] = $row[0]['discount'];
            $_SESSION['discount_code'] = $row[0]['code'];
        }
        ?>   
        <!-- Coupon -->
        <div class="ShoppingCartFoot">
            <table  width="100%" height="40" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="74%" align="left"><?php echo ($row[0]['text_promotion']); ?></td>
                    <td width="14%" align="left"><span class="price">Discount </span></td>
                    <td width="12%" align="right"><?php echo '<span class="price">-' . (moneyformat($_SESSION['discount'])) ?></span></td>
                </tr>
            </table>
        </div>
        <?php
    }
    ?>

    <!-- Total -->
    <div class="ShoppingCartFoot_nodash">
        <table  width="100%" height="40" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="74%" align="left">&nbsp;</td>
                <td width="14%" align="left"><span class="price">Total</span></td>
                <td width="12%" align="right"><?php echo '<span class="price">' . (moneyformat(($total + EW_SHIPPING) - $_SESSION['discount'])) ?></span></td>
            </tr>
        </table>
    </div>

    <!-- Buttons -->
    <div class="ShoppingCartButtons">
        <a class="btn btn-default" href="shoppingcart_view.php">Go back</a>  
        <a class="btn btn-success" onclick="$('#paypalform').attr('action', 'shoppingcart_form_cash_on_delivery.php');
                document.paypalform.submit();">Cash on delivery +<?php echo moneyformat(EW_CASHONDELIVERY); ?><i class="icon-chevron-right"></i></a>
        <a class="btn btn-success" onclick="$('#paypalform').attr('action', 'BootstrapShoppingCart/dopayment_paypal_multiple_cart.php');
                document.paypalform.submit();">Pay with Paypal<i class="icon-chevron-right"></i></a>

    </div>
</form>