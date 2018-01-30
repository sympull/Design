<?php

//	
//	Aenea - www.beaenea.com
//	Bootstrap BootstrapShoppingCart &  
//	Paypal Payment Pack for codecanyon.net
//
//      The way to inform the user about the status of your payment
//      for all $_POST -> https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/

require_once "config.php";                // load settings file
require_once "BootstrapShoppingCart.php";     // load class BootstrapShoppingCart
// Examines all the IPN and turns it into a string

function Array2Str($kvsep, $entrysep, $a) {
    $str = "";
    foreach ($a as $k => $v) {
        $str .= "{$k}{$kvsep}{$v}{$entrysep}";
    }
    return $str;
}

//
// Verifying paypal message - Using POST vars rm=2 in html form 
//

$req = 'cmd=_notify-validate';
$fullipnA = array();

foreach ($_POST as $key => $value) {
    $fullipnA[$key] = $value;
    $encodedvalue = urlencode(stripslashes($value));
    $req .= "&$key=$encodedvalue";
}

$fullipn = Array2Str(" : ", "\n", $fullipnA);


if (!$use_paypal_sandbox) {
    $url = 'https://www.paypal.com/cgi-bin/webscr';
} else {
    $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
}




$curl_result = $curl_err = '';
$fp = curl_init();
curl_setopt($fp, CURLOPT_URL, $url);
curl_setopt($fp, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($fp, CURLOPT_POST, 1);
curl_setopt($fp, CURLOPT_POSTFIELDS, $req);
curl_setopt($fp, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));
curl_setopt($fp, CURLOPT_HEADER, 0);
curl_setopt($fp, CURLOPT_VERBOSE, 1);
curl_setopt($fp, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($fp, CURLOPT_TIMEOUT, 30);

$response = curl_exec($fp);
$curl_err = curl_error($fp);
curl_close($fp);

// Vars received by Paypal

if (strcmp ($response, "VERIFIED") == 0) {
    // Check the status of the order
    $payment_status = $_POST['payment_status'];
    if ($payment_status != "Completed" ) {
        echo "Invalid payment";
        $subject = "Invalid payment";
        $msg = "Invalid payment " . $payment_status;
        $payer_email = $_POST['payer_email'];
        $address_name = $_POST['address_name'];
        
        //$sendmail = new sendmail(EW_EMAIL, $subject, $msg, $payer_email, $address_name);
        //$sendmail->send();
        
        $mail = new PHPMailer; // init obj
        $mail->IsHTML(true); // Set email format to HTML
        $mail->SetFrom($payer_email, $address_name);
        $mail->Subject = $subject;
        $mail->Body = $msg;
        $mail->AddAddress(EW_EMAIL);
        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
        exit;
    }

    // First register user to my bsc_customers
    // Vars received by Paypal // all are here 

    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];
    $txn_type = $_POST['txn_type'];
    $pending_reason = $_POST['pending_reason'];
    $payment_type = $_POST['payment_type'];
    $custom = $_POST['custom'];
    $invoice = $_POST['invoice'];

    // Buyer information
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address_name = $_POST['address_name'];
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
    $pass=1;
    // Create a new order header in bsc_order_header 
    $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
    $Db->query("SET NAMES 'utf8'");  // formating to utf8
    $settings = array('dateorder' => date('Y-m-d H:i:s'),
        'payer_email' => $payer_email,
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

    // now register the order detail
    for ($i = 1; $i <= $_POST['num_cart_items']; $i++) {
    
        // Vars received by Paypal
        $item_name = $_POST['item_name' . $i];
        $item_number = $_POST['item_number' . $i];
        $quantity = $_POST['quantity' . $i];
       
        $mc_gross = $_POST['$mc_gross_' . $i];
        $item_price = $mc_gross / $quantity;
        
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        $txn_type = $_POST['txn_type'];
        $pending_reason = $_POST['pending_reason'];
        $payment_type = $_POST['payment_type'];
        $custom = $_POST['custom'];
        $invoice = $_POST['invoice'];

        // Buyer information
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address_name = $_POST['address_name'];
        $address_country = $_POST['address_country'];
        $address_country_code = $_POST['address_country_code'];
        $address_zip = $_POST['address_zip'];
        $address_state = $_POST['address_state'];
        $address_city = $_POST['address_city'];
        $address_street = $_POST['address_street'];

        /* $sql = "INSERT INTO  " . $paypal_mysqltable_name . 
          " (datenow, item_name,   item_number, quantity,  payment_status,    payment_amount,   payment_currency,  payer_email,     payment_type,   custom,
          invoice, first_name, last_name, address_name, address_country, address_country_code, address_zip, address_state, address_city, address_street)
          VALUES (CURRENT_TIMESTAMP,'$item_name','$item_number','$quantity','$payment_status', '$payment_amount','$payment_currency','$payer_email', '$payment_type','$custom' ,'$invoice','$first_name','$last_name','$address_name','$address_country','$address_country_code','$address_zip','$address_state','$address_city','$address_street')";
          $result = mysql_query($sql, $link);
         */
        // Add new row! 'dateorder' => CURRENT_TIMESTAMP , 
        $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
        $Db->query("SET NAMES 'utf8'");  // formating to utf8
        $settings = array(
            'dateorder' => date('Y-m-d H:i:s'),
            'item_name'  => $item_name,
            'item_number'  => $item_number,
            'quantity'  => $quantity,
            'mc_gross' => $mc_gross,
            'item_price'=> $item_price,
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
    } // cart
    
    // Add new Shipping
    if (EW_SHIPPING>0) {
        $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
        $Db->query("SET NAMES 'utf8'");  // formating to utf8
        $settings = array(
            'dateorder' => date('Y-m-d H:i:s'),
            'item_name'  => 'Shipping',
            'item_number'  => "",
            'quantity'  => 1,
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
    
    // sendemail to shop !    
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
    for ($i = 1; $i <= $_POST['num_cart_items']; $i++) {
        $message.="<tr>";
        $message.="<td width='250'>" . $_POST['item_number' . $i] . " - " . $_POST['item_name' . $i] . "</td>"; // display product name
        $message.="<td width='50'>" . $_POST['quantity' . $i] . "</td>"; // Quantity
        $message.="<td width='100'><b>" . $_POST['mc_gross_' . $i] . "</b></td>"; // gross        
        $message.="</tr>";
    }
    
     // Shipping //
    if (EW_SHIPPING>0) {       
        $message.="<td width='250'>" . "Shipping" . "</td>"; // display product name
        $message.="<td width='50'>1</td>"; // Quantity
        $message.="<td width='100'><b>" . EW_SHIPPING . "</b></td>"; // gross        
        $message.="</tr>";
    }
    
    $message.="</table>";
    $message.="<h2>Total: " . $_POST['mc_gross'] . " ".$payment_currency. "</h2>";
    $message.="<br>";
    $message.=$_POST['custom'];

    $msg = "<html><body>" . $message . "</body></html>";

    // email to Buyer //////////////////////////////////////////////////////////////
    $subject = "Ticket " . $_POST['invoice'];
    
    $mail = new PHPMailer; // init obj
    $mail->IsHTML(true); // Set email format to HTML
    $mail->SetFrom($payer_email, "Shop");
    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->AddAddress($payer_email);
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
    
    // email to Shop //////////////////////////////////////////////////////////////
    $message.="<br><br>";
    // i take all $_POST parameters email notification
    foreach ($_POST as $name => $value) {
        $message .= $name . ": " . $value . "<br>";
    }
    $msg = "<html><body>" . $message . "</body></html>";
    $subject = "New payment was successfully recieved from " . $payer_email;
    //$sendmail = new sendmail(EW_EMAIL, $subject, $msg, $payer_email, $address_name);
    //$sendmail->send();
    
    /*$mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = 'login';
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = 'example@gmail.com';
    $mail->Password = 'somepassword';
    * 
    */
    $mail = new PHPMailer; // init obj
    $mail->IsHTML(true); // Set email format to HTML
    $mail->SetFrom($payer_email, $address_name);
    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->AddAddress(EW_EMAIL);
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }

 } else {

    //the transaction is invalid I can NOT charge the client. 
     foreach ($_POST as $name => $value) {
        $message .= $name . ": " . $value . "<br>";
    }
    $msg = "<html><body>" . $message . "</body></html>";
    
    $subject = "Invalid payment ";
    $message = "Invalid payment from " . $payer_email ." Status:"  . $payment_status;;
    //$sendmail = new sendmail(EW_EMAIL, $subject, $msg, $payer_email, $address_name);
    //$sendmail->send();
    
    /*$mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = 'login';
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = 'example@gmail.com';
    $mail->Password = 'somepassword';
    * 
    */
    $mail = new PHPMailer; // init obj
    $mail->IsHTML(true); // Set email format to HTML
    $mail->SetFrom($payer_email, $address_name);
    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->AddAddress(EW_EMAIL);
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}

