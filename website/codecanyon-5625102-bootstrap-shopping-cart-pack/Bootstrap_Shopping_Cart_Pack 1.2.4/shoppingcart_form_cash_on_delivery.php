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
        <!-- validate form -->
        <link rel="stylesheet" href="vendor/jQuery-Validation-Engine-master/css/validationEngine.jquery.css" type="text/css"/>
        <script src="vendor/jQuery-Validation-Engine-master/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
        <script src="vendor/jQuery-Validation-Engine-master/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

    </head>
    <body>
        <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]--> 

        <!-- header menu -->
        <?php include("BootstrapShoppingCart/inc.menu.php"); ?>

        <div class="container">
            <div class="row">
                <!-- Shopping cart -->
                <div class="col-md-2 hidden-sm hidden-xs">
                    <div class="nav-list affix">  
                        <div class="ShoppingCartHead"><div style="padding:10px;" >Cash on delivery</div></div><br />
                        <?php
                        $total = 0; // ShoppingCart Total
                        $subtotal=0;
                        $cart = new shoppingcart(); // init shoppingcart
                        $cart->init('my_shop');
                        $bsc = $cart->get_cart(); // get shoppingcart
                        $render = new renderchartshop($bsc);  // render shoppingcart
                        $shoppingcart = $render->get();   // get render 
                        // list current shoppingcart 

                        foreach ($shoppingcart as $cart) {
                            $subtotalrow = 0; // ShoppingCart subtotal

                            if ($cart['price_offer'] > 0) {
                                $price = $cart['price_offer'];
                            } else {
                                $price = $cart['price'];
                            }
                            $subtotalrow = $price * $cart['quantity'];
                            $subtotal += $subtotalrow;
                        } // End count



                        if (EW_CASHONDELIVERY > 0) {
                            echo "Cash on delivery has an<br />extra charge of +" . moneyformat(EW_CASHONDELIVERY);
                        } else {
                            echo "Cash on delivery has<br />no extra charge";
                        }
                        echo "<br /><br />Subtotal: " . moneyformat($subtotal);
                        if (EW_CASHONDELIVERY > 0) {
                            echo "<br />Extra charge: +" . moneyformat(EW_CASHONDELIVERY);
                            $subtotal = $subtotal + EW_CASHONDELIVERY;
                        } else {
                            echo "<br />";
                        }
                        if (isset($_SESSION['discount']) && ($_SESSION['discount'] > 0)) {
                            echo "<br />Coupon: -" . moneyformat($_SESSION['discount']);
                            $subtotal = $subtotal - $_SESSION['discount'];
                        }
                        if (EW_SHIPPING > 0) {
                            echo "<br />Shipping: " . moneyformat(EW_SHIPPING);
                            $subtotal = $subtotal + EW_SHIPPING;
                        }
                        echo "<br /><span class='price'>Total: " . moneyformat($subtotal) . "</span>";
                        ?>
                        <br /><br />
                        <a class="btn btn-default" href="<?php echo $_SESSION['LastProductPage']; ?>">Go products<i class="icon-chevron-right"></i></a><br /><br />
                        <a class="btn btn-default" href="shoppingcart_checkout.php">Go back</a><br /><br />

                    </div> 
                </div>

                <div class="col-md-10">
                    <!--form-->
                    <div class="container">
                        <div class="col-md-10">

                            <table class="ShoppingCartHead" width="100%" height="40" border="0" cellspacing="0" cellpadding="0" >
                                <tr>
                                    <td align="left">&nbsp;&nbsp;Fill the form</td>        
                                </tr>
                            </table><div style="margin-top: 20px;">
                                Cash on delivery form
                            </div>
                            <div style="margin-top: 20px;">
                                <form action="BootstrapShoppingCart/cash_on_delivery_send.php" method="post" id="form" name="form">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><span class="control-label">First name*</span></label>
                                            <input type="text" value="" class="form-control validate[required]" name="first_name"  id="first_name"   placeholder="First name">
                                            <div style="color:#999; font-size:10px; line-height: 10px; padding-bottom: 10px;">Your first name</div>                                    
                                            <label><span class="control-label">Last name*</span></label>
                                            <input type="text" value="" class="form-control validate[required]" name="last_name"  id="last_name"   placeholder="Last name">
                                            <div style="color:#999; font-size:10px; line-height: 10px; padding-bottom: 10px;">Your last name</div>
                                            <label><span class="control-label">Email*</span></label>
                                            <input type="text" value="" class="form-control validate[required,custom[email]]" name="payer_email"  id="payer_email"   placeholder="Email">
                                            <div style="color:#999; font-size:10px; line-height: 10px; padding-bottom: 10px;">Your email</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label><span class="control-label">Street Address*</span></label>
                                            <input type="text" value="" class="form-control validate[required]" name="address_street"  id="address_street"   placeholder="Address">
                                            <div style="color:#999; font-size:10px; line-height: 10px; padding-bottom: 10px;">Street Name and/or apartment number</div>
                                            <label><span class="control-label">Zip Code*</span></label>
                                            <input type="text" value="" class="form-control validate[required]" name="address_zip" id="address_zip" placeholder="Zip Code">
                                            <div style="color:#999; font-size:10px; line-height: 10px; padding-bottom: 10px;">Please provide your zip code</div>
                                            <label><span class="control-label">City*</span></label>
                                            <input type="text" value="" class="form-control validate[required]" name="address_city" id="address_city" placeholder="City">
                                            <div style="color:#999; font-size:10px; line-height: 10px; padding-bottom: 10px;">Your city or town</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label><span class="control-label">State*</span></label>
                                            <input type="text" value="" class="form-control validate[required]" name="address_state" id="address_state" placeholder="State">
                                            <div style="color:#999; font-size:10px; line-height: 10px; padding-bottom: 10px;">Your state/county</div>
                                            <label>Country*</label>
                                            <select name="address_country_code" id="address_country_code" class="form-control validate[required]" onchange="$('#address_country').val($('#address_country_code').find('option:selected').text());">
                                                <option value="" selected="selected">Select</option>
                                                <option value="AR">Argentina</option>
                                                <option value="AU">Australia</option>
                                                <option value="AT">Austria</option>
                                                <option value="BY">Belarus</option>
                                                <option value="BE">Belgium</option>
                                                <option value="BA">Bosnia and Herzegovina</option>
                                                <option value="BR">Brazil</option>
                                                <option value="BG">Bulgaria</option>
                                                <option value="CA">Canada</option>
                                                <option value="CL">Chile</option>
                                                <option value="CN">China</option>
                                                <option value="CO">Colombia</option>
                                                <option value="CR">Costa Rica</option>
                                                <option value="HR">Croatia</option>
                                                <option value="CU">Cuba</option>
                                                <option value="CY">Cyprus</option>
                                                <option value="CZ">Czech Republic</option>
                                                <option value="DK">Denmark</option>
                                                <option value="DO">Dominican Republic</option>
                                                <option value="EG">Egypt</option>
                                                <option value="EE">Estonia</option>
                                                <option value="FI">Finland</option>
                                                <option value="FR">France</option>
                                                <option value="GE">Georgia</option>
                                                <option value="DE">Germany</option>
                                                <option value="GI">Gibraltar</option>
                                                <option value="GR">Greece</option>
                                                <option value="HK">Hong Kong S.A.R., China</option>
                                                <option value="HU">Hungary</option>
                                                <option value="IS">Iceland</option>
                                                <option value="IN">India</option>
                                                <option value="ID">Indonesia</option>
                                                <option value="IR">Iran</option>
                                                <option value="IQ">Iraq</option>
                                                <option value="IE">Ireland</option>
                                                <option value="IL">Israel</option>
                                                <option value="IT">Italy</option>
                                                <option value="JM">Jamaica</option>
                                                <option value="JP">Japan</option>
                                                <option value="KZ">Kazakhstan</option>
                                                <option value="KW">Kuwait</option>
                                                <option value="KG">Kyrgyzstan</option>
                                                <option value="LA">Laos</option>
                                                <option value="LV">Latvia</option>
                                                <option value="LB">Lebanon</option>
                                                <option value="LT">Lithuania</option>
                                                <option value="LU">Luxembourg</option>
                                                <option value="MK">Macedonia</option>
                                                <option value="MY">Malaysia</option>
                                                <option value="MT">Malta</option>
                                                <option value="MX">Mexico</option>
                                                <option value="MD">Moldova</option>
                                                <option value="MC">Monaco</option>
                                                <option value="ME">Montenegro</option>
                                                <option value="MA">Morocco</option>
                                                <option value="NL">Netherlands</option>
                                                <option value="NZ">New Zealand</option>
                                                <option value="NI">Nicaragua</option>
                                                <option value="KP">North Korea</option>
                                                <option value="NO">Norway</option>
                                                <option value="PK">Pakistan</option>
                                                <option value="PS">Palestinian Territory</option>
                                                <option value="PE">Peru</option>
                                                <option value="PH">Philippines</option>
                                                <option value="PL">Poland</option>
                                                <option value="PT">Portugal</option>
                                                <option value="PR">Puerto Rico</option>
                                                <option value="QA">Qatar</option>
                                                <option value="RO">Romania</option>
                                                <option value="RU">Russia</option>
                                                <option value="SA">Saudi Arabia</option>
                                                <option value="RS">Serbia</option>
                                                <option value="SG">Singapore</option>
                                                <option value="SK">Slovakia</option>
                                                <option value="SI">Slovenia</option>
                                                <option value="ZA">South Africa</option>
                                                <option value="KR">South Korea</option>
                                                <option value="ES">Spain</option>
                                                <option value="LK">Sri Lanka</option>
                                                <option value="SE">Sweden</option>
                                                <option value="CH">Switzerland</option>
                                                <option value="TW">Taiwan</option>
                                                <option value="TH">Thailand</option>
                                                <option value="TN">Tunisia</option>
                                                <option value="TR">Turkey</option>
                                                <option value="UA">Ukraine</option>
                                                <option value="AE">United Arab Emirates</option>
                                                <option value="GB">United Kingdom</option>
                                                <option value="US">USA</option>
                                                <option value="UZ">Uzbekistan</option>
                                                <option value="VN">Vietnam</option>
                                            </select>
                                            <div style="color:#999; font-size:10px; line-height: 10px; padding-bottom: 10px;">Select your country</div>

                                            <div style="margin-top: 25px; text-align: right;">
                                                <button class="btn btn-success" type="submit" >Confirm purchase Cash on delivery</button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="business" value="<?php echo (EW_PAYPAL_REGISTERED_EMAIL); ?>"> <!-- Your paypal email account --> 
                                    <input type="hidden" name="return" value="<?php echo (EW_WEB_PATH); ?>shoppingcart_thanks_purchase.php"> <!-- if payment complete then show "Thanks !" -->
                                    <input type="hidden" name="notify_url" value="<?php echo (EW_WEB_PATH); ?>BootstrapShoppingCart/paypal_paymentpaypalipn.php"> <!-- The URL to which PayPal posts information about the payment, notification messages.  -->  
                                    <input type="hidden" name="mc_currency" value="<?php echo (EW_CURRENCY_CODE) ?>"> <!-- Currency code / setup config.php -->
                                    <input type="hidden" name="invoice" value="<?php echo date("YmdHis"); ?>"> <!-- My num invoice its inverted date -->        
                                    <input type="hidden" name="payment_status" value="Cash on delivery">

                                    <input type="hidden" name="mc_gross" value="<?php echo $subtotal; ?>"> 
                                    <input type="hidden" name="custom" value="<?php echo $_POST['comments'] ?>">
                                    <input type="hidden" name="address_country" id="address_country" value="">

                                </form>
                            </div>
                        </div>
                    </div> 
                </div> 
                <!--/form-->
            </div>
        </div>
    </div>


    <!-- /container --> 

    <script src="vendor/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script> 
    <!-- BootsrapBootstrapShoppingCart.js -->
    <script src="BootstrapShoppingCart/BootstrapShoppingCart.js"></script>  


    <script>
                                                $(document).ready(function () {
                                                    // show current shoppingcart
                                                    $("#BSCart").load("BootstrapShoppingCart/addproduct_vertical.php");

                                                    $("#form").validationEngine(); // init form validator
                                                });
    </script>



</body>
</html>
