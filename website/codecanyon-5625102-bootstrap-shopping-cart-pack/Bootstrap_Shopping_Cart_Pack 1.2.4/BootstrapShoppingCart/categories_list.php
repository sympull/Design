<?php
//	
//	Aenea - www.beaenea.com
//	Bootstrap Shop Cart for codecanyon.net
//


//
// List categories
//
$Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
$Db->query("SET NAMES 'utf8'");  // formating to utf8
$rows = $Db->search('bsc_category')->
        setColumns('*')->
        addFilter("visible=%d", 1)->
        sortByOrdering('ASC')->
        getRows();

foreach ($rows as $rs) {

    $cur_page = $_SERVER['PHP_SELF'];
    $path = pathinfo($cur_page); // take current page                   
    echo "<a href='" . $path['basename'] . "?category=" . $rs['id'] . "' class='btn btn-default' style='margin-bottom:20px; margin-right:20px;'>" . $rs['name'] . "</a>";
}

//
// View shopping cart only for smartphone
//
echo '<div class="visible-xs visible-sm" style="padding-bottom: 30px;"><a href="shoppingcart_view.php" class="btn btn-success" >View shoppingcart</a></div>';
?>

