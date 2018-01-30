<?php
//	
//	Aenea - www.beaenea.com
//	Bootstrap BootstrapShoppingCart for codecanyon.net
//

//
// SET PAGING
//
if (isset($_GET['page'])) {
    $currentpage = $_GET['page'];
} else {
    $currentpage = 1;
}
//
// PRODUCT
//

$db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
$Db->query("SET NAMES 'utf8'");  // formating to utf8
// SELECT * FROM bsc_products WHERE idCategory=1 AND visible=1 ORDER BY ordering ASC  
$rows = $db->search('bsc_products')->
        setColumns('*')->
        addFilter("idCategory=%d", $category)->
        addFilter("visible=%d", 1)->
        sortByOrdering('ASC')->
        setRange(EW_PAGINATION)->
        setPage($currentpage)->
        getRows();

foreach ($rows as $rs) {
    // this product has options/types?
    $db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
    $Db->query("SET NAMES 'utf8'");  // formating to utf8
    // SELECT * FROM bsc_types WHERE idProduct=2 ORDER BY ordering ASC 
    $types = $db->search('bsc_types')->
            setColumns('*')->
            addFilter("idProduct=%d", $rs['id'])->
            sortByOrdering('ASC')->
            getRows();
    $thisrow = array();
    // making a array with products&types ... only for this row
    $id_product = $rs['id'];
    if (count($types) > 0) {
        foreach ($types as $type) {
            $newline = array($id_product . ':::' . $type['id'] => 0);
            $thisrow =array_merge($thisrow,$newline);
        }
    } else {
        $newline = array($id_product . ':::' => 0);
        $thisrow =array_merge($thisrow,$newline);
    }
    
    // take all product & types (variations)
    $render = new renderchartshop($thisrow);  // render shoppingcart
    $thisproduct = $render->get();   // get render 
    //dd($thisproduct );
   
    ?>
    <div class="col-sm-3 col-md-3 product" data-id="<?php echo $thisproduct[0]['id_product']; ?>" data-type="<?php
    if (count($thisproduct)>1) {
        echo 'select'; // are selection/type? 
    }
    ?>" data-quantity="1">
        <div class="thumbnail">
            <img src="assets/<?php echo $thisproduct[0]['img']; ?>" alt="<?php echo $thisproduct[0]['name']; ?>" class="img-responsive">
            <div class="caption">
                <p>
                    <?php
                    echo $thisproduct[0]['name'] . "<br />"; // display name product         
                    $price = new getprice($rs['id'], 0); // first its id->product second id->type
                    $price_array = $price->get(); // getting price [0] = no offer price [1]= offer price
                     
                    if (count($thisproduct)==1) { // its only 1 product or ... have types ?
                        if ($thisproduct[0]['price'] > 0) { // have price ?
                            if ($thisproduct[0]['offer']) { // this a offer ?
                                echo '<span class="price_overline">' . moneyformat($thisproduct[0]['price']) . '</span>';
                                echo '<span class="offer"> ' . moneyformat($thisproduct[0]['price_offer']) . '</span>';
                            } else {
                                echo '<span class="price">' . moneyformat($thisproduct[0]['price']) . '</span>';
                            }
                        } else {
                            echo '<span class="price">&nbsp;</span>'; // are price 0 then 
                        }
                    } else {
                         echo '<span class="offer">&nbsp;</span>';
                    }
                    ?>   

                </p>
                <?php
                if (count($thisproduct)>1) {
                    ?>
                    <select class="form-control combobox_type" style="margin-bottom: 6px;">
                        <option value="select" selected>Select ...</option>
                        <?php foreach ($thisproduct as $type) { 
                            if ($type['id_type']>0) {
                            ?>
                            <option value="<?php echo $type['id_type']; ?>"><?php
                            echo $type['name_type']. ' ';
                            // its type w price ?                                                                   
                            // display price type ... like iphone 16gb/32gb/64/gb
                            if ($type['offer']) { // this a offer ?
                                echo '<span class="price_overline">' . moneyformat($type['price']) . '</span>';
                                echo '<span class="offer"> ' . moneyformat($type['price_offer']) . '</span>';
                            } else {
                                echo '<span class="price">' . moneyformat($type['price']) . '</span>';
                            }
                            ?></option>
                            <?php } // foreach types
                            } // its a type? ?>
                    </select>
                    <?php
                } else { // options 
                    echo '<div style="height: 40px;"></div>';  // i make a offset
                }
                ?>

                <p align="center">
                    <a href="product_sheet.php?id=<?php echo $rs['id']; ?>" class="btn btn-default"><i class="icon-plus"></i>More</a> 
                    <?php if ($shoppingcart_vertical) { ?>
                        <a href="#" class="btn btn-success addproduct"><i class="icon-shopping-cart"></i>Add</a>
                    <?php } else { ?>
                        <a href="#" class="btn btn-success addproduct-horizontal"><i class="icon-shopping-cart"></i>Add</a>
                    <?php } ?>
                </p>
            </div>
        </div>
    </div>
    <?php
} // foreach products
?>
