<?php
$db = eden('mysql', EW_CONN_HOST, EW_CONN_DB, EW_CONN_USER, EW_CONN_PASS);    //instantiate
$Db->query("SET NAMES 'utf8'");  // formating to utf8
$rows = $db->search('bsc_products')->
        setColumns('*')->
        addFilter("idCategory=%d", $category)->
        addFilter("visible=%d", 1)->
        getRows();

$total_pages = ceil(count($rows) / EW_PAGINATION);
?>
<div class="container" >
    <div class="row">
        <div class="col-md-offset-2 col-md-10">
            <ul class="pagination">
                <?php
                echo '<li><a href="'.$_SERVER['PHP_SELF'].'?category='.$category.'&page=1">&laquo;</a></li>'; // first page
                
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<li><a href="'.$_SERVER['PHP_SELF'].'?category='.$category.'&page='.$i.'">'.$i.'</a></li>';
                    }
                    
                echo '<li><a href="'.$_SERVER['PHP_SELF'].'?category='.$category.'&page='.$total_pages.'">&raquo;</a></li>'; // last page
                ?>                

                
            </ul>
        </div>
    </div>
</div>