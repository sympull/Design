<?php

// img
// idCategory
// productCode
// name
// price
// price_offer
// img_detail1
// img_detail2
// img_detail3
// download
// ordering
// visible

?>
<?php if ($bsc_products->Visible) { ?>
<table cellspacing="0" id="t_bsc_products" class="ewGrid"><tr><td>
<table id="tbl_bsc_productsmaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($bsc_products->img->Visible) { // img ?>
		<tr id="r_img">
			<td><?php echo $bsc_products->img->FldCaption() ?></td>
			<td<?php echo $bsc_products->img->CellAttributes() ?>><span id="el_bsc_products_img" class="control-group">
<span>
<?php if ($bsc_products->img->LinkAttributes() <> "") { ?>
<?php if (!empty($bsc_products->img->Upload->DbValue)) { ?>
<img src="<?php echo $bsc_products->img->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $bsc_products->img->ViewAttributes() ?>>
<?php } elseif (!in_array($bsc_products->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($bsc_products->img->Upload->DbValue)) { ?>
<img src="<?php echo $bsc_products->img->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $bsc_products->img->ViewAttributes() ?>>
<?php } elseif (!in_array($bsc_products->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_products->idCategory->Visible) { // idCategory ?>
		<tr id="r_idCategory">
			<td><?php echo $bsc_products->idCategory->FldCaption() ?></td>
			<td<?php echo $bsc_products->idCategory->CellAttributes() ?>><span id="el_bsc_products_idCategory" class="control-group">
<span<?php echo $bsc_products->idCategory->ViewAttributes() ?>>
<?php echo $bsc_products->idCategory->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_products->productCode->Visible) { // productCode ?>
		<tr id="r_productCode">
			<td><?php echo $bsc_products->productCode->FldCaption() ?></td>
			<td<?php echo $bsc_products->productCode->CellAttributes() ?>><span id="el_bsc_products_productCode" class="control-group">
<span<?php echo $bsc_products->productCode->ViewAttributes() ?>>
<?php echo $bsc_products->productCode->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_products->name->Visible) { // name ?>
		<tr id="r_name">
			<td><?php echo $bsc_products->name->FldCaption() ?></td>
			<td<?php echo $bsc_products->name->CellAttributes() ?>><span id="el_bsc_products_name" class="control-group">
<span<?php echo $bsc_products->name->ViewAttributes() ?>>
<?php echo $bsc_products->name->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_products->price->Visible) { // price ?>
		<tr id="r_price">
			<td><?php echo $bsc_products->price->FldCaption() ?></td>
			<td<?php echo $bsc_products->price->CellAttributes() ?>><span id="el_bsc_products_price" class="control-group">
<span<?php echo $bsc_products->price->ViewAttributes() ?>>
<?php echo $bsc_products->price->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_products->price_offer->Visible) { // price_offer ?>
		<tr id="r_price_offer">
			<td><?php echo $bsc_products->price_offer->FldCaption() ?></td>
			<td<?php echo $bsc_products->price_offer->CellAttributes() ?>><span id="el_bsc_products_price_offer" class="control-group">
<span<?php echo $bsc_products->price_offer->ViewAttributes() ?>>
<?php echo $bsc_products->price_offer->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_products->img_detail1->Visible) { // img_detail1 ?>
		<tr id="r_img_detail1">
			<td><?php echo $bsc_products->img_detail1->FldCaption() ?></td>
			<td<?php echo $bsc_products->img_detail1->CellAttributes() ?>><span id="el_bsc_products_img_detail1" class="control-group">
<span>
<?php if ($bsc_products->img_detail1->LinkAttributes() <> "") { ?>
<?php if (!empty($bsc_products->img_detail1->Upload->DbValue)) { ?>
<img src="<?php echo $bsc_products->img_detail1->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $bsc_products->img_detail1->ViewAttributes() ?>>
<?php } elseif (!in_array($bsc_products->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($bsc_products->img_detail1->Upload->DbValue)) { ?>
<img src="<?php echo $bsc_products->img_detail1->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $bsc_products->img_detail1->ViewAttributes() ?>>
<?php } elseif (!in_array($bsc_products->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_products->img_detail2->Visible) { // img_detail2 ?>
		<tr id="r_img_detail2">
			<td><?php echo $bsc_products->img_detail2->FldCaption() ?></td>
			<td<?php echo $bsc_products->img_detail2->CellAttributes() ?>><span id="el_bsc_products_img_detail2" class="control-group">
<span>
<?php if ($bsc_products->img_detail2->LinkAttributes() <> "") { ?>
<?php if (!empty($bsc_products->img_detail2->Upload->DbValue)) { ?>
<img src="<?php echo $bsc_products->img_detail2->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $bsc_products->img_detail2->ViewAttributes() ?>>
<?php } elseif (!in_array($bsc_products->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($bsc_products->img_detail2->Upload->DbValue)) { ?>
<img src="<?php echo $bsc_products->img_detail2->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $bsc_products->img_detail2->ViewAttributes() ?>>
<?php } elseif (!in_array($bsc_products->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_products->img_detail3->Visible) { // img_detail3 ?>
		<tr id="r_img_detail3">
			<td><?php echo $bsc_products->img_detail3->FldCaption() ?></td>
			<td<?php echo $bsc_products->img_detail3->CellAttributes() ?>><span id="el_bsc_products_img_detail3" class="control-group">
<span>
<?php if ($bsc_products->img_detail3->LinkAttributes() <> "") { ?>
<?php if (!empty($bsc_products->img_detail3->Upload->DbValue)) { ?>
<img src="<?php echo $bsc_products->img_detail3->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $bsc_products->img_detail3->ViewAttributes() ?>>
<?php } elseif (!in_array($bsc_products->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($bsc_products->img_detail3->Upload->DbValue)) { ?>
<img src="<?php echo $bsc_products->img_detail3->ListViewValue() ?>" alt="" style="border: 0;"<?php echo $bsc_products->img_detail3->ViewAttributes() ?>>
<?php } elseif (!in_array($bsc_products->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_products->download->Visible) { // download ?>
		<tr id="r_download">
			<td><?php echo $bsc_products->download->FldCaption() ?></td>
			<td<?php echo $bsc_products->download->CellAttributes() ?>><span id="el_bsc_products_download" class="control-group">
<span<?php echo $bsc_products->download->ViewAttributes() ?>>
<?php if ($bsc_products->download->LinkAttributes() <> "") { ?>
<?php if (!empty($bsc_products->download->Upload->DbValue)) { ?>
<?php echo $bsc_products->download->ListViewValue() ?>
<?php } elseif (!in_array($bsc_products->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($bsc_products->download->Upload->DbValue)) { ?>
<?php echo $bsc_products->download->ListViewValue() ?>
<?php } elseif (!in_array($bsc_products->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_products->ordering->Visible) { // ordering ?>
		<tr id="r_ordering">
			<td><?php echo $bsc_products->ordering->FldCaption() ?></td>
			<td<?php echo $bsc_products->ordering->CellAttributes() ?>><span id="el_bsc_products_ordering" class="control-group">
<span<?php echo $bsc_products->ordering->ViewAttributes() ?>>
<?php echo $bsc_products->ordering->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_products->visible->Visible) { // visible ?>
		<tr id="r_visible">
			<td><?php echo $bsc_products->visible->FldCaption() ?></td>
			<td<?php echo $bsc_products->visible->CellAttributes() ?>><span id="el_bsc_products_visible" class="control-group">
<span<?php echo $bsc_products->visible->ViewAttributes() ?>>
<?php echo $bsc_products->visible->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
