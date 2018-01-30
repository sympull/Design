<?php
//	
//	Aenea - www.beaenea.com
//	Bootstrap BootstrapShoppingCart 
//
//      Send an email to cash on delivery


require_once "config.php";                // load settings file
require_once "BootstrapShoppingCart.php";     // load class BootstrapShoppingCart
//
// First register user to my bsc_customers
// Vars received by web method Post // all are here 
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$receiver_email = $_POST['payer_email'];
$payer_email = $_POST['payer_email'];
$txn_type = 0;
$pending_reason = "Cash on Delivery";
$payment_type = "Cash on Delivery";
$custom = $_POST['custom'];
$invoice = $_POST['invoice'];

// Buyer information
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$address_name = $_POST['first_name'] . " " . $_POST['last_name'];
$address_country = $_POST['address_country'];
$address_country_code = $_POST['address_country_code'];
$address_zip = $_POST['address_zip'];
$address_state = $_POST['address_state'];
$address_city = $_POST['address_city'];
$address_street = $_POST['address_street'];

//////////////////////////////////////////////////////////////////////////////////////
// This User/email exist?
//////////////////////////////////////////////////////////////////////////////////////
CheckUser($payer_email, $first_name, $last_name, $address_name, $address_country, $address_country_code, $address_zip, $address_state, $address_city, $address_street);

//////////////////////////////////////////////////////////////////////////////////////
// Create a new order header in bsc_order_header      
//////////////////////////////////////////////////////////////////////////////////////
$Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
$Db->query("SET NAMES 'utf8'");  // formating to utf8
$settings = array('dateorder' => date('Y-m-d H:i:s'),
   
    'first_name' => $first_name,
    'last_name' => $last_name,
    'address_name' => $address_name,
    'address_country' => $address_country,
    'address_country_code' => $address_country_code,
    'address_zip' => $address_zip,
    'address_state' => $address_state,
    'address_city' => $address_city,
    'address_street' => $address_street,
    'payment_status' => $payment_status,
    'payer_email' => $payer_email,
    'payment_type' => $payment_type,
    'payment_status' => $payment_status,
    'payment_currency' => $payment_currency,
    'payment_amount' => $payment_amount,
    'custom' => $custom,
    'invoice' => $invoice
);
$Db->insertRow('bsc_order_header', $settings);

//////////////////////////////////////////////////////////////////////////////////////
// Register the order detail
//////////////////////////////////////////////////////////////////////////////////////
$cart = new shoppingcart(); // init shoppingcart
$cart->init('my_shop');
$subtotal = 0; // ShoppingCart Total
$bsc = $cart->get_cart(); // get shoppingcart

$render = new renderchartshop($bsc);  // render shoppingcart
$shoppingcart = $render->get();   // get render 
foreach ($shoppingcart as $cart) {
    $subtotalrow = 0; // ShoppingCart subtotal

    if ($cart['price_offer'] > 0) {
        $price = $cart['offer'];
    } else {
        $price = $cart['price'];
    }
    $mc_gross = $price * $cart['quantity'];
    // Add new row! 'dateorder' => CURRENT_TIMESTAMP , 
    $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
    $Db->query("SET NAMES 'utf8'");  // formating to utf8
    $settings = array(
        'dateorder' => date('Y-m-d H:i:s'),
        'item_name' => $cart['name'] . ' ' . $cart['name_type'],
        'item_number' => $cart['productCode'],
        'quantity' => $cart['quantity'],
        'mc_gross' => $mc_gross,
        'item_price' => $price,
        'payment_status' => $payment_status,
        'payment_amount' => $payment_amount,
        'payment_currency' => $payment_currency,
        'payer_email' => $payer_email,
        'payment_type' => $payment_type,
        'custom' => $custom,
        'invoice' => $invoice,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'address_name' => $address_name,
        'address_country' => $address_country,
        'address_country_code' => $address_country_code,
        'address_zip' => $address_zip,
        'address_state' => $address_state,
        'address_city' => $address_city,
        'address_street' => $address_street
    );
    $Db->insertRow('bsc_order_detail', $settings); // inserts row into 'bsc_order' table
}


//////////////////////////////////////////////////////////////////////////////////////
// Coupon discount ? then write the coupon discount
//////////////////////////////////////////////////////////////////////////////////////
if ((isset($_SESSION['discount'])) && ($_SESSION['discount'] > 0)) {
    $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
    $Db->query("SET NAMES 'utf8'");  // formating to utf8
    $settings = array(
        'dateorder' => date('Y-m-d H:i:s'),
        'item_name' => 'Discount coupon',
        'item_number' => $_SESSION['discount_code'],
        'quantity' => 1,
        'mc_gross' => $_SESSION['discount'],
        'payment_status' => $payment_status,
        'payment_amount' => $payment_amount,
        'payment_currency' => $payment_currency,
        'payer_email' => $payer_email,
        'payment_type' => $payment_type,
        'custom' => $custom,
        'invoice' => $invoice,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'address_name' => $address_name,
        'address_country' => $address_country,
        'address_country_code' => $address_country_code,
        'address_zip' => $address_zip,
        'address_state' => $address_state,
        'address_city' => $address_city,
        'address_street' => $address_street
    );
    $Db->insertRow('bsc_order_detail', $settings); // inserts row into 'bsc_order' table
}

//////////////////////////////////////////////////////////////////////////////////////
// Add ROW Cash on delivery? then write
//////////////////////////////////////////////////////////////////////////////////////
if (EW_CASHONDELIVERY > 0) {
    $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
    $Db->query("SET NAMES 'utf8'");  // formating to utf8
    $settings = array(
        'dateorder' => date('Y-m-d H:i:s'),
        'item_name' => 'Cash on delivery',
        'item_number' => "",
        'quantity' => 1,
        'mc_gross' => EW_CASHONDELIVERY,
        'payment_status' => $payment_status,
        'payment_amount' => $payment_amount,
        'payment_currency' => $payment_currency,
        'payer_email' => $payer_email,
        'payment_type' => $payment_type,
        'custom' => $custom,
        'invoice' => $invoice,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'address_name' => $address_name,
        'address_country' => $address_country,
        'address_country_code' => $address_country_code,
        'address_zip' => $address_zip,
        'address_state' => $address_state,
        'address_city' => $address_city,
        'address_street' => $address_street
    );
    $Db->insertRow('bsc_order_detail', $settings); // inserts row into 'bsc_order' table
}

//////////////////////////////////////////////////////////////////////////////////////
// ADD ROW Shipping 
//////////////////////////////////////////////////////////////////////////////////////
if (EW_SHIPPING > 0) {
    $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
    $Db->query("SET NAMES 'utf8'");  // formating to utf8
    $settings = array(
        'dateorder' => date('Y-m-d H:i:s'),
        'item_name' => 'Shipping',
        'item_number' => "",
        'quantity' => 1,
        'mc_gross' => EW_SHIPPING,
        'payment_status' => $payment_status,
        'payment_amount' => $payment_amount,
        'payment_currency' => $payment_currency,
        'payer_email' => $payer_email,
        'payment_type' => $payment_type,
        'custom' => $custom,
        'invoice' => $invoice,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'address_name' => $address_name,
        'address_country' => $address_country,
        'address_country_code' => $address_country_code,
        'address_zip' => $address_zip,
        'address_state' => $address_state,
        'address_city' => $address_city,
        'address_street' => $address_street
    );
    $Db->insertRow('bsc_order_detail', $settings); // inserts row into 'bsc_order' table
}

//////////////////////////////////////////////////////////////////////////////////////
// sendemail to shop !    
//////////////////////////////////////////////////////////////////////////////////////
//
    // header mail
$message = "<img src='" . EW_WEB_PATH . "images/logo_header.png' />";
$message.="<h2>ticket purchase " . $_POST['invoice'] . "</h2>";
$message.="<br>";
$message.= $address_name . "<br>";
$message.= $address_street . "<br>";
$message.= $address_city . " " . $address_zip . "<br>";
$message.= $address_state . "<br>";
$message.= $address_country;
$message.="<br><br>";

$message.="<table border='0'>";

// order detail
$shopcart = new shoppingcart(); // init shoppingcart
$shopcart->init('my_shop');
$subtotal = 0; // ShoppingCart Total


$bsc = $shopcart->get_cart(); // get shoppingcart

$render = new renderchartshop($bsc);  // render shoppingcart
$shoppingcart = $render->get();   // get render 
foreach ($shoppingcart as $cart) {

    if ($cart['price_offer'] > 0) {
        $price = $cart['offer'];
    } else {
        $price = $cart['price'];
    }
    $subtotalrow = $price * $cart['quantity'];
    $subtotal += $subtotalrow;

    $message.="<tr>";
    if (isset($cart['productCode'])) {
        $item_number = $cart['productCode'];
    } else {
        $item_number = "";
    }
    $message.="<td width='250'>" . $item_number . " - " . $cart['name'] . " " . $cart['name_type'] . "</td>"; // display product name
    $message.="<td width='50'>" . $cart['quantity'] . "</td>"; // Quantity
    $message.="<td width='100'><b>" . $subtotalrow . "</b></td>"; // gross        
    $message.="</tr>";
}

// Cash on delivery //
$message.="<tr>";
$message.="<td width='250'>" . "Cash on Delivary Extra charge" . "</td>"; // display product name
$message.="<td width='50'>1</td>"; // Quantity
$message.="<td width='100'><b>" . EW_CASHONDELIVERY . "</b></td>"; // gross        
$message.="</tr>";
// Coupon?//
$discount = 0;
if ((isset($_SESSION['discount'])) && ($_SESSION['discount'] > 0)) {
    $discount = $_SESSION['discount'];
    $message.="<td width='250'>" . "Discount coupon" . "</td>"; // display product name
    $message.="<td width='50'>1</td>"; // Quantity
    $message.="<td width='100'><b>-" . $discount . "</b></td>"; // gross        
    $message.="</tr>";
}
// Shipping //
if (EW_SHIPPING > 0) {
    $message.="<td width='250'>" . "Shipping" . "</td>"; // display product name
    $message.="<td width='50'>1</td>"; // Quantity
    $message.="<td width='100'><b>" . EW_SHIPPING . "</b></td>"; // gross        
    $message.="</tr>";
}

$message.="</table>";
$message.="<h2>Total: " . (($subtotal + EW_SHIPPING + EW_CASHONDELIVERY) - $discount) . " " . $payment_currency . "</h2>";
$message.="<br>";
$message.=$_POST['custom'];

$msg = "<html><body>" . $message . "</body></html>";

// email to Buyer //////////////////////////////////////////////////////////////
$subject = "Ticket " . $_POST['invoice'];

$mail = new PHPMailer; // init obj
$mail->IsHTML(true); // Set email format to HTML
$mail->SetFrom(EW_EMAIL, "Shop");
$mail->Subject = $subject;
$mail->Body = $msg;
$mail->AddAddress($payer_email);
if (!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    
}


// email to Shop //////////////////////////////////////////////////////////////
$message.="<br><br>";

$msg = "<html><body>" . $message . "</body></html>";
$subject = "New payment was successfully recieved from " . $payer_email;

$mail = new PHPMailer; // init obj
$mail->IsHTML(true); // Set email format to HTML
$mail->SetFrom($payer_email, $address_name);
$mail->Subject = $subject;
$mail->Body = $msg;
$mail->AddAddress(EW_EMAIL);
if (!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    
}

// clear shopping cart
$shopcart->removeall_cart();
$_SESSION['discount'] = 0;
?>
<script type="text/javascript">
    window.location = "../shoppingcart_thanks_purchase.php";
</script>