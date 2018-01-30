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
    </head>
    <body>
        <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]--> 

        <!-- header menu -->
        <?php include("BootstrapShoppingCart/inc.menu.php"); ?>

        <div class="container">
            <div class="row">
                <!-- ShoppingCart -->
                <div class="col-md-2">
                    <div class="nav-list affix">                        
                        <div id="BSCart" class="collections">
                            <div class="BSCart_list">                               

                            </div>
                        </div>
                    </div> 
                </div>

                <div class="col-md-10">
                    <!--Products-->
                    <div class="container"> 
                        <?php
                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];
                        } else {
                            $id = 0;
                        }
                                             
                                             
                        $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
                        $Db->query("SET NAMES 'utf8'");  // formating to utf8
                        $row = $Db->search('bsc_products')->
                                setColumns('*')->
                                addFilter("id=%d", $id)->
                                addFilter("visible=%d", 1)->
                                getRows();
                        if (!count($row)) {

                            echo "<div class='span5'>";
                            echo "<h3>Product not found!</h3>";
                            echo "<a href='" . $_SESSION['LastProductPage'] . "'>Go back</a>";
                            echo "</div>";
                        } else {
                            ?>                
                            <div class="col-md-4">
                                <img src="assets/<?php echo $row[0]['img'] ?>" />
                                <?php
                                // more images?                                 
                                if (isset($row[0]['img_detail1'])) {
                                    echo "<br>" . "<img src='assets/" . $row[0]['img_detail1'] . "' />";
                                }
                                if (isset($row[0]['img_detail2'])) {
                                    echo "<br>" . "<img src='assets/" . $row[0]['img_detail2'] . "' />";
                                }
                                if (isset($row[0]['img_detail3'])) {
                                    echo "<br>" . "<img src='assets/" . $row[0]['img_detail3'] . "' />";
                                }
                                ?>


                            </div>
                            <div class="col-md-5">
                                <span class="productTitle"><?php echo $row[0]['name'] ?></span>
                                <p>
                                    <?php
                                    // its a offer? //
                                    if ($row[0]['price_offer'] > 0) {
                                        $price = $row[0]['price_offer'];
                                        echo '<span class="price_overline">' . moneyformat($row[0]['price']) . '</span>';
                                        echo ' <span class="offer">' . moneyformat($row[0]['price_offer']) . '</span>';
                                    } else {
                                        $price = $row[0]['price'];
                                        echo '<span class="price">' . moneyformat($row[0]['price']) . '</span>';
                                    }
                                    ?>   
                                </p><br/>
                                <p>
                                    <?php echo  nl2br($row[0]['description']);  ?>
                                </p>
                                <?php
                                // Any download ? has documentation pdf, docx, ...  ////////////////////
                                if (isset($row[0]['download'])) {
                                    echo "<a class='btn btn-default' href='assets/" . $row[0]['download'] . "' target='_blank'><i class='icon-arrow-down'></i>Download documentation</a><br />";
                                }


                                // types? //////////////////////////////////////////////////////////////
                                $db2 = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
                                // SELECT * FROM bsc_types WHERE idProduct=2 ORDER BY ordering ASC 
                                $types = $db2->search('bsc_types')->
                                        setColumns('*')->
                                        addFilter("idProduct=%d", $row[0]['id'])->
                                        sortByOrdering('ASC')->
                                        getRows();

                                if (count($types)) {
                                    ?>
                                    <br/><br/>
                                    <select class="form-control" onchange="$('.addproduct-sheet').attr('data-type',$(this).val())">
                                        <option value="select" selected>Select ...</option>
                                        <?php foreach ($types as $type) { ?>
                                            <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
                                        <?php } // foreach types ?>
                                    </select>
                                    <?php
                                } else { // options 
                                    echo '<div style="height: 40px;"></div>';  // i make a offset
                                }
                                ?>
                                <br/>
                                <?php
                                // Quantity inputbox ///////////////////////////////////////////////
                                ?>
                                <input class="col-md-1 form-control" name="input_quantity" type="number" id="input_quantity" value="1" onchange="$('.addproduct-sheet').attr('data-quantity',$(this).val())" class="img-responsive" >
                                <br/>
                                <br/>
                                <?php
                                // button add product ///////////////////////////////////////////////
                                ?>
                                <a href="#" class="btn btn-success addproduct-sheet" data-id="<?php echo $row[0]['id']; ?>" data-type="<?php
                            if (count($types)) {
                                echo 'select'; // are selection/type? 
                            }
                                ?>" data-quantity="1"><i class="icon-shopping-cart"></i>Add to Cart</a>
                                <br/><br/>
                                <?php
                                // Go to the list/View shopping cart ////////////////////////////////
                                ?>
                                <p align="left">
                                <a class="btn btn-default" href="<?php echo $_SESSION['LastProductPage']; ?>"><i class="icon-arrow-left"></i>Product list</a>
                                <a class="btn btn-default" href="shoppingcart_view.php">View shopping cart</a>
                                <?php
                                // buttons next / previous product /////////////////////////////////////
                                $Db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS); //instantiate
                                $Db->query("SET NAMES 'utf8'");  // formating to utf8
                                $rs = $Db->getRow('bsc_products', 'id', $id);
                                // previous
                                $database = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS); //instantiate
                                $Db->query("SET NAMES 'utf8'");  // formating to utf8
                                $sql = "SELECT id FROM bsc_products WHERE  ordering > '" . $rs['ordering'] . "' and visible=1 and idCategory=" . $rs['idCategory'] . " ORDER BY ordering ASC LIMIT 1";
                                $prev = $database->query($sql);
                                // next
                                $database = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS); //instantiate
                                $Db->query("SET NAMES 'utf8'");  // formating to utf8
                                $sql = "SELECT id FROM bsc_products WHERE  ordering < '" . $rs['ordering'] . "' and visible=1 and idCategory=" . $rs['idCategory'] . " ORDER BY ordering DESC LIMIT 1";
                                $next = $database->query($sql);
                                // display buttons
                                echo "<br/><br/>";
                                if (isset($next[0]['id']) > 0) {
                                     echo '<a class="btn btn-default" href="product_sheet.php?id=' . $next[0]['id'] . '"><i class="icon-chevron-left"></i>Previous product</a> ';
                                }
                                if (isset($prev[0]['id']) > 0) {
                                     echo '<a class="btn btn-default" href="product_sheet.php?id=' . $prev[0]['id'] . '">Next product<i class="icon-chevron-right"></i></a>';
                                }
                                ?>
                                </p>
                            </div>
                            <?php
                        } // are a recordset?
                        ?>
                    </div>

                </div>
            </div>
        </div>


        <!-- /container --> 
        <script src="vendor/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script> 

        <!-- BootsrapBootstrapShoppingCart.js -->
        <script src="BootstrapShoppingCart/BootstrapShoppingCart.js"></script>  
        <script>
            $(document).ready(function() {
                // Handler for .ready() called.
                $("#BSCart").load("BootstrapShoppingCart/addproduct_vertical.php");
                // anim buttons categories 
                $('#animation_slideDown').addClass("slideDown");
            });            
        </script>
    </body>
</html>
