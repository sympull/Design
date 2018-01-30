<?php 
//	
//	Aenea - www.beaenea.com
//	Bootstrap BootstrapShoppingCart for codecanyon.net
//
/*
 * 
Usage Bootstrap CartShop Example:
$cart=new cartshop();
$cart->init('my_shop');              //initialize the shoppingcart
$cart->add_cart("100:::",3);        //adding values
$cart->add_cart("1:::black",5);     //adding values
$cart->add_cart("3:::white",1);     //adding values
$cart->update_cart("3:::white",5);  //update cart
$cart->remove_cart("3:::white");    //remove cart
$cart->removeall_cart();            //remove all cat
if(!$cart->get_cart()){             //checking if the cart is empty or not
    echo "no cart found";
}else{
    print_r($cart->get_cart());      this returns the values stored in a array , you can iterate and get the values after that
    }
   
echo "<BR>";
echo $cart->count_cart()." items found";   
echo "<BR>";
echo $cart->countall_cart()." total items found"; 
 * 
 */


class shoppingcart
{
private $cart=array();
public $sessionname;

function shoppingcart(){
    if(session_id()==""){
		session_start();
	}
}

function init($sessionname){
	$this->sessionname=$sessionname;
        //initializing cart and getting value from session if it exists
	$this->cart=(isset($_SESSION[$this->sessionname]))?$_SESSION[$this->sessionname]:array();
	$this->writesession($this->cart);
}

function add_cart($id,$quantity){

	if(!isset($this->cart[$id])){
		$this->cart["$id"]=$quantity;
	}else{
		 $this->cart["$id"]=$this->cart["$id"]+$quantity;
	}
	$this->writesession($this->cart);
	return true;
}

function writesession($cart){
	$_SESSION[$this->sessionname]=$cart;
	return true;
}

function get_cart(){
	if(count($_SESSION[$this->sessionname])==0){
	   return false;
	}else{
	   return $_SESSION[$this->sessionname];
	}
	

}

function update_cart($id,$quantity){
	$this->cart["$id"]=$quantity;
	$this->writesession($this->cart);
}

function remove_cart($id){
	unset($this->cart["$id"]);
	//no items left in the cart, initialize cart again
	if(count($this->cart)==0){
		$this->cart=array();
	}
	$this->writesession($this->cart);
}

function removeall_cart(){
	unset($this->cart);
	$this->cart=array();
	$this->writesession($this->cart);
}

function count_cart(){
	return count($this->cart);
}

function countall_cart(){
	$count=0;
	foreach($this->cart as $a=>$b){
		$count+=$b;
	}
	return $count;
}
}

//--------------------------------------------------------
// currency 
//--------------------------------------------------------
function moneyformat ( $number ) {
  return EW_CURRENCY_SYMBOL.number_format($number, 2, '.', '');   
}

//--------------------------------------------------------
// debug
//--------------------------------------------------------
function dd ( $s ){
	echo "<pre>";
	print_r($s);
	echo "</pre>";
}

//--------------------------------------------------------
// exract id & type of 1 product
//--------------------------------------------------------
function extractproduct( $s )
{ 
    $productcart = explode(":::", $s);  
    return $productcart;
}

//--------------------------------------------------------
// getprice
//--------------------------------------------------------
//
// Gets the price, considering the product variations and discount
//
class getprice{
    
    private $price=array();
    private $id;
    private $idtype;

    function __construct( $id , $idtype  ) {
        $this->id=$id;
        $this->idtype = $idtype;
         
    }
    
    function get(){
        // get product
        $db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
        $db->query("SET NAMES 'utf8'");  // formating to utf8
        $rows = $db->search('bsc_products')->
            setColumns('*')->
            addFilter("id=%d", $this->id)->
            addFilter("visible=%d", 1)->
            sortByOrdering('ASC')->
            getRows();
            // have types?
        if ($rows[0]['price_offer'] > 0) {
            $this->price= array( $rows[0]['price'] , $rows[0]['price_offer'] );                        
            } else {
            $this->price=array(  $rows[0]['price'] , 0 );                      
            }
        // has type? 
        if ($this->idtype > 0){    
            $db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
            $db->query("SET NAMES 'utf8'");  // formating to utf8
            // SELECT * FROM bsc_types WHERE idProduct=2 ORDER BY ordering ASC 
            $types = $db->search('bsc_types')->
                setColumns('*')->
                addFilter("id=%d", $this->idtype )->
                sortByOrdering('ASC')->
                getRows();  
        
            if ($types[0]['price_offer'] > 0) {
                $this->price= array( $types[0]['price'] , $types[0]['price_offer'] );                        
                } else {
                $this->price=array(  $types[0]['price'] , 0 );                      
                }
            }
        return ($this->price);
    }
}

//--------------------------------------------------------
// renderchartshop
//--------------------------------------------------------
//
// Take data from the database and creates an array
//
class renderchartshop{
    private $render=array();
    private $cartline=array();  // only a line
    private $cart=array(); // all cart
    
    function __construct( $shoppingcart ) {
        $this->cart = $shoppingcart; // load the shoppingcart
    }
    
    function get(){
        foreach ($this->cart as $productcart => $value) {
            
            $extractproduct = extractproduct($productcart);   
            $id = $extractproduct[0];
            $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
            $Db->query("SET NAMES 'utf8'");  // formating to utf8
            $row = $Db->search('bsc_products')->
                setColumns('*')->
                addFilter("id=%d", $id)->
                addFilter("visible=%d", 1)->
                getRows();
                // read all row
                $name=$row[0]['name'];
                $id_product=$row[0]['id'];
                $img=$row[0]['img'];
                $productCode=$row[0]['productCode'];
                $id_type=0;
                $name_type='';
                $price=0;
                $price_offer=0;
            
            // Get price ////////////////////////////
            if ($row[0]['price_offer'] > 0) {
                $price = $row[0]['price'];
                $price_offer = $row[0]['price_offer']; $offer=1;
            } else {
                $price = $row[0]['price']; $price_offer=0;$offer=0;
            }
            
            // has type? //////////////////////////// 
            if (strlen($extractproduct[1]) > 0) {
                $db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
                $db->query("SET NAMES 'utf8'");  // formating to utf8
                // SELECT * FROM bsc_types WHERE idProduct=2 ORDER BY ordering ASC 
                $types = $db->search('bsc_types')->setColumns('*')->addFilter("id=%d", $extractproduct[1])->sortByOrdering('ASC')->getRows();
                // Get price ////////////////////////////
                if ($types[0]['price_offer'] > 0) {
                    $price = $types[0]['price'];
                    $price_offer = $types[0]['price_offer']; $offer=1;
                } else {
                    $price = $types[0]['price']; $price_offer=0;$offer=0;
                }
                $id_type=$types[0]['id']; // take type id
                $name_type = $types[0]['name'];
            }       
                    
            $newline = array ('name' => $name, 
                              'name_type' => $name_type,
                              'quantity' =>$value,
                              'price' => $price,
                              'price_offer' => $price_offer,
                              'offer' =>$offer,
                              'id_product' =>$id_product,
                              'id_type' =>$id_type,
                              'type_id' => 0,
                              'img' =>$img,
                              'productCode' =>$productCode
                              );
            
            $this->render[] = $newline;
        }
        return ($this->render); 
    }
}

//--------------------------------------------------------
// sendmail
//--------------------------------------------------------
/*
 * use:
$sendmail = new sendmail($name, $to, $subject, $txt, $email);
if($sendmail->send()=='true'){
    echo 'Email sent';
}else{
  echo 'Could not send';
}
*/
class sendmail{
  function __construct( $to, $subject, $message, $sendername, $senderemail ) {
    $this->address = $to;
    $this->subject = $subject;
    $this->message = $message;
    $this->headers = $headers = "MIME-Version: 1.0" . "\r\n";
    $this->headers .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
    $this->headers .= "From: $sendername <$senderemail>". "\r\n";
    $this->headers .= "Reply-To: $sendername <$senderemail>". "\r\n";
    $this->headers .= "X-Mailer: PHP/".phpversion(). "\r\n";
 }

 function send() {
    if(mail($this->address, $this->subject, $this->message, $this->headers)){
      return true;
    }else{
    return false;
    }
  }

}


//--------------------------------------------------------
// Check User
//--------------------------------------------------------

function CheckUser($payer_email, $first_name,$last_name,$address_name, $address_country, $address_country_code,$address_zip,$address_state,$address_city,$address_street) {
// Its a new user ?
    $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
    $Db->query("SET NAMES 'utf8'");  // formating to utf8
    $row = $Db->getRow('bsc_customers', 'payer_email', $payer_email); // returns the row from 'bsc_order_detail' table where 'payer_email' equals $payer_email
    if (count($row)) { // this user already exist ... then update
        $settings = array('datelastorder' => date('Y-m-d H:i:s'),
            'payer_email' => $payer_email,
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
        $filter[] = array('payer_email=%s', $payer_email);
        $Db->updateRows('bsc_customers', $settings, $filter);
    } else { // this user dont exist .. then create
        $settings = array('datelastorder' => date('Y-m-d H:i:s'),
            'dateregister' => date('Y-m-d H:i:s'),
            'payer_email' => $payer_email,
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
        $Db->insertRow('bsc_customers', $settings);
    }
}