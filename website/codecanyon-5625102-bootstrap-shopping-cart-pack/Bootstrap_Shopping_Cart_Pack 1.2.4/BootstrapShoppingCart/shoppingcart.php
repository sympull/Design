<form action="BootstrapShoppingCart/shoppingcart_update.php" method="post">

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
    // list current shoppingcart
    foreach ($shoppingcart as $cart) {
        $subtotal = 0; // ShoppingCart subtotal
        ?>
        <table class="table-striped" width="100%" height="80" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="12%" align="left"><?php
                    // image
                    if (isset($cart['img'])) {
                        echo '<img src="assets/' . $cart['img'] . '" width="90" height="90">';
                    }
                    ?></td>
                <td width="30%" align="left"  ><?php
                    // name / product code                                                                                    
                    echo $cart['name'] . ' ' . $cart['name_type'];
                    // has product code
                    if (isset($cart['productCode'])) {
                        //echo " - " . $cart['productCode'];
                    }
                    ?></td>

                <td width="16%" align="center"><?php
                    // its a offer?
                    if ($cart['offer'] > 0) {
                        $price = $cart['price_offer'];
                        echo '<span class="price_overline">' . moneyformat($cart['price']) . '</span>';
                        echo '<span class="offer"> ' . moneyformat($cart['price_offer']) . '</span>';
                    } else {
                        $price = $cart['price'];
                        echo '<span class="price">' . moneyformat($cart['price']) . '</span>';
                    }
                    ?></td>
                <td width="2%"></td>
                <td width="9%" align="center"><input class="col-xs-12" name="txtQty[]" type="number" id="txtQty[]"  value="<?php echo $cart['quantity']; ?>" onKeyUp="checkNumber(this);" /></td>
                <td width="9%" align="center"><a href="BootstrapShoppingCart/shoppingcart_delete.php?id=<?php echo $cart['id_product']; ?>&type=<?php
                    if ($cart['id_type'] == 0) {
                        
                    } else {
                        echo $cart['id_type'];
                    }
                    ?>" ><span class="glyphicon glyphicon-trash"></span></a><input name="hidProductId[]" type="hidden" value="<?php echo $cart['id_product']; ?>" /><input name="hidType[]" type="hidden" value="<?php
                                                 if ($cart['id_type'] == 0) {
                                                     
                                                 } else {
                                                     echo $cart['id_type'];
                                                 }
                                                 ?>" /></td>
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
    <!-- Total -->
    <div class="ShoppingCartFoot">
        <table  width="100%" height="40" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="74%" align="left">&nbsp;</td>
                <td width="14%" align="left"><span class="price">Total</span></td>
                <td width="12%" align="right"><?php echo '<span class="price">' . (moneyformat($total + EW_SHIPPING)) . '</span>'; ?></td>
            </tr>
        </table>
    </div>


    <?php
    // Coupons discount - new 1.1 
    $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
    $Db->query("SET NAMES 'utf8'");  // formating to utf8
    $row = $Db->search('bsc_coupons')->
            setColumns('*')->
            addFilter("visible=%d", 1)->
            getRows();
    // i have discount coupons??
    if (count($row) > 0) {
        ?>
        <div style="margin-top: 30px; margin-bottom: 30px; text-align: center; background-color: #f5f5f5;" class="ShoppingCartFoot" > 
            <label class="control-label">Do you have a discount Coupon ? Enter the code</label>
            <input name="coupon" id="coupon_input" />
        </div>
        <?php
    }
    ?>

    <!-- Buttons -->
    <div class="ShoppingCartButtons">
        <a class="btn btn-default" href="<?php echo $_SESSION['LastProductPage']; ?>">Go to products</a>                            
        <button class="btn" type="submit">Update quantities</button>
        <a class="btn btn-success checkout_button" >Checkout</a><?php //call js click id   ?>

    </div>
</form>