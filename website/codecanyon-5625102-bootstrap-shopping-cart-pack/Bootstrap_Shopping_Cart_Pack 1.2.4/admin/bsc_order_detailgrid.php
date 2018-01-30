<?php include_once "bsc_admininfo.php" ?>
<?php

// Create page object
if (!isset($bsc_order_detail_grid)) $bsc_order_detail_grid = new cbsc_order_detail_grid();

// Page init
$bsc_order_detail_grid->Page_Init();

// Page main
$bsc_order_detail_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_order_detail_grid->Page_Render();
?>
<?php if ($bsc_order_detail->Export == "") { ?>
<script type="text/javascript">

// Page object
var bsc_order_detail_grid = new ew_Page("bsc_order_detail_grid");
bsc_order_detail_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = bsc_order_detail_grid.PageID; // For backward compatibility

// Form object
var fbsc_order_detailgrid = new ew_Form("fbsc_order_detailgrid");
fbsc_order_detailgrid.FormKeyCountName = '<?php echo $bsc_order_detail_grid->FormKeyCountName ?>';

// Validate form
fbsc_order_detailgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_quantity");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_order_detail->quantity->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mc_gross");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_order_detail->mc_gross->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_item_price");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_order_detail->item_price->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fbsc_order_detailgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "item_number", false)) return false;
	if (ew_ValueChanged(fobj, infix, "item_name", false)) return false;
	if (ew_ValueChanged(fobj, infix, "quantity", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mc_gross", false)) return false;
	if (ew_ValueChanged(fobj, infix, "item_price", false)) return false;
	return true;
}

// Form_CustomValidate event
fbsc_order_detailgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_order_detailgrid.ValidateRequired = true;
<?php } else { ?>
fbsc_order_detailgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($bsc_order_detail->getCurrentMasterTable() == "" && $bsc_order_detail_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $bsc_order_detail_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($bsc_order_detail->CurrentAction == "gridadd") {
	if ($bsc_order_detail->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$bsc_order_detail_grid->TotalRecs = $bsc_order_detail->SelectRecordCount();
			$bsc_order_detail_grid->Recordset = $bsc_order_detail_grid->LoadRecordset($bsc_order_detail_grid->StartRec-1, $bsc_order_detail_grid->DisplayRecs);
		} else {
			if ($bsc_order_detail_grid->Recordset = $bsc_order_detail_grid->LoadRecordset())
				$bsc_order_detail_grid->TotalRecs = $bsc_order_detail_grid->Recordset->RecordCount();
		}
		$bsc_order_detail_grid->StartRec = 1;
		$bsc_order_detail_grid->DisplayRecs = $bsc_order_detail_grid->TotalRecs;
	} else {
		$bsc_order_detail->CurrentFilter = "0=1";
		$bsc_order_detail_grid->StartRec = 1;
		$bsc_order_detail_grid->DisplayRecs = $bsc_order_detail->GridAddRowCount;
	}
	$bsc_order_detail_grid->TotalRecs = $bsc_order_detail_grid->DisplayRecs;
	$bsc_order_detail_grid->StopRec = $bsc_order_detail_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$bsc_order_detail_grid->TotalRecs = $bsc_order_detail->SelectRecordCount();
	} else {
		if ($bsc_order_detail_grid->Recordset = $bsc_order_detail_grid->LoadRecordset())
			$bsc_order_detail_grid->TotalRecs = $bsc_order_detail_grid->Recordset->RecordCount();
	}
	$bsc_order_detail_grid->StartRec = 1;
	$bsc_order_detail_grid->DisplayRecs = $bsc_order_detail_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$bsc_order_detail_grid->Recordset = $bsc_order_detail_grid->LoadRecordset($bsc_order_detail_grid->StartRec-1, $bsc_order_detail_grid->DisplayRecs);
}
$bsc_order_detail_grid->RenderOtherOptions();
?>
<?php $bsc_order_detail_grid->ShowPageHeader(); ?>
<?php
$bsc_order_detail_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fbsc_order_detailgrid" class="ewForm form-horizontal">
<?php if ($bsc_order_detail_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel ewListOtherOptions">
<?php
	foreach ($bsc_order_detail_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<div id="gmp_bsc_order_detail" class="ewGridMiddlePanel">
<table id="tbl_bsc_order_detailgrid" class="ewTable ewTableSeparate">
<?php echo $bsc_order_detail->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$bsc_order_detail_grid->RenderListOptions();

// Render list options (header, left)
$bsc_order_detail_grid->ListOptions->Render("header", "left");
?>
<?php if ($bsc_order_detail->item_number->Visible) { // item_number ?>
	<?php if ($bsc_order_detail->SortUrl($bsc_order_detail->item_number) == "") { ?>
		<td><div id="elh_bsc_order_detail_item_number" class="bsc_order_detail_item_number"><div class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_number->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_bsc_order_detail_item_number" class="bsc_order_detail_item_number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_number->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order_detail->item_number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order_detail->item_number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order_detail->item_name->Visible) { // item_name ?>
	<?php if ($bsc_order_detail->SortUrl($bsc_order_detail->item_name) == "") { ?>
		<td><div id="elh_bsc_order_detail_item_name" class="bsc_order_detail_item_name"><div class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_bsc_order_detail_item_name" class="bsc_order_detail_item_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order_detail->item_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order_detail->item_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order_detail->quantity->Visible) { // quantity ?>
	<?php if ($bsc_order_detail->SortUrl($bsc_order_detail->quantity) == "") { ?>
		<td><div id="elh_bsc_order_detail_quantity" class="bsc_order_detail_quantity"><div class="ewTableHeaderCaption"><?php echo $bsc_order_detail->quantity->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_bsc_order_detail_quantity" class="bsc_order_detail_quantity">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order_detail->quantity->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order_detail->quantity->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order_detail->quantity->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order_detail->mc_gross->Visible) { // mc_gross ?>
	<?php if ($bsc_order_detail->SortUrl($bsc_order_detail->mc_gross) == "") { ?>
		<td><div id="elh_bsc_order_detail_mc_gross" class="bsc_order_detail_mc_gross"><div class="ewTableHeaderCaption"><?php echo $bsc_order_detail->mc_gross->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_bsc_order_detail_mc_gross" class="bsc_order_detail_mc_gross">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order_detail->mc_gross->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order_detail->mc_gross->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order_detail->mc_gross->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order_detail->item_price->Visible) { // item_price ?>
	<?php if ($bsc_order_detail->SortUrl($bsc_order_detail->item_price) == "") { ?>
		<td><div id="elh_bsc_order_detail_item_price" class="bsc_order_detail_item_price"><div class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_price->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_bsc_order_detail_item_price" class="bsc_order_detail_item_price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order_detail->item_price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order_detail->item_price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$bsc_order_detail_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$bsc_order_detail_grid->StartRec = 1;
$bsc_order_detail_grid->StopRec = $bsc_order_detail_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($bsc_order_detail_grid->FormKeyCountName) && ($bsc_order_detail->CurrentAction == "gridadd" || $bsc_order_detail->CurrentAction == "gridedit" || $bsc_order_detail->CurrentAction == "F")) {
		$bsc_order_detail_grid->KeyCount = $objForm->GetValue($bsc_order_detail_grid->FormKeyCountName);
		$bsc_order_detail_grid->StopRec = $bsc_order_detail_grid->StartRec + $bsc_order_detail_grid->KeyCount - 1;
	}
}
$bsc_order_detail_grid->RecCnt = $bsc_order_detail_grid->StartRec - 1;
if ($bsc_order_detail_grid->Recordset && !$bsc_order_detail_grid->Recordset->EOF) {
	$bsc_order_detail_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $bsc_order_detail_grid->StartRec > 1)
		$bsc_order_detail_grid->Recordset->Move($bsc_order_detail_grid->StartRec - 1);
} elseif (!$bsc_order_detail->AllowAddDeleteRow && $bsc_order_detail_grid->StopRec == 0) {
	$bsc_order_detail_grid->StopRec = $bsc_order_detail->GridAddRowCount;
}

// Initialize aggregate
$bsc_order_detail->RowType = EW_ROWTYPE_AGGREGATEINIT;
$bsc_order_detail->ResetAttrs();
$bsc_order_detail_grid->RenderRow();
if ($bsc_order_detail->CurrentAction == "gridadd")
	$bsc_order_detail_grid->RowIndex = 0;
if ($bsc_order_detail->CurrentAction == "gridedit")
	$bsc_order_detail_grid->RowIndex = 0;
while ($bsc_order_detail_grid->RecCnt < $bsc_order_detail_grid->StopRec) {
	$bsc_order_detail_grid->RecCnt++;
	if (intval($bsc_order_detail_grid->RecCnt) >= intval($bsc_order_detail_grid->StartRec)) {
		$bsc_order_detail_grid->RowCnt++;
		if ($bsc_order_detail->CurrentAction == "gridadd" || $bsc_order_detail->CurrentAction == "gridedit" || $bsc_order_detail->CurrentAction == "F") {
			$bsc_order_detail_grid->RowIndex++;
			$objForm->Index = $bsc_order_detail_grid->RowIndex;
			if ($objForm->HasValue($bsc_order_detail_grid->FormActionName))
				$bsc_order_detail_grid->RowAction = strval($objForm->GetValue($bsc_order_detail_grid->FormActionName));
			elseif ($bsc_order_detail->CurrentAction == "gridadd")
				$bsc_order_detail_grid->RowAction = "insert";
			else
				$bsc_order_detail_grid->RowAction = "";
		}

		// Set up key count
		$bsc_order_detail_grid->KeyCount = $bsc_order_detail_grid->RowIndex;

		// Init row class and style
		$bsc_order_detail->ResetAttrs();
		$bsc_order_detail->CssClass = "";
		if ($bsc_order_detail->CurrentAction == "gridadd") {
			if ($bsc_order_detail->CurrentMode == "copy") {
				$bsc_order_detail_grid->LoadRowValues($bsc_order_detail_grid->Recordset); // Load row values
				$bsc_order_detail_grid->SetRecordKey($bsc_order_detail_grid->RowOldKey, $bsc_order_detail_grid->Recordset); // Set old record key
			} else {
				$bsc_order_detail_grid->LoadDefaultValues(); // Load default values
				$bsc_order_detail_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$bsc_order_detail_grid->LoadRowValues($bsc_order_detail_grid->Recordset); // Load row values
		}
		$bsc_order_detail->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($bsc_order_detail->CurrentAction == "gridadd") // Grid add
			$bsc_order_detail->RowType = EW_ROWTYPE_ADD; // Render add
		if ($bsc_order_detail->CurrentAction == "gridadd" && $bsc_order_detail->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$bsc_order_detail_grid->RestoreCurrentRowFormValues($bsc_order_detail_grid->RowIndex); // Restore form values
		if ($bsc_order_detail->CurrentAction == "gridedit") { // Grid edit
			if ($bsc_order_detail->EventCancelled) {
				$bsc_order_detail_grid->RestoreCurrentRowFormValues($bsc_order_detail_grid->RowIndex); // Restore form values
			}
			if ($bsc_order_detail_grid->RowAction == "insert")
				$bsc_order_detail->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$bsc_order_detail->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($bsc_order_detail->CurrentAction == "gridedit" && ($bsc_order_detail->RowType == EW_ROWTYPE_EDIT || $bsc_order_detail->RowType == EW_ROWTYPE_ADD) && $bsc_order_detail->EventCancelled) // Update failed
			$bsc_order_detail_grid->RestoreCurrentRowFormValues($bsc_order_detail_grid->RowIndex); // Restore form values
		if ($bsc_order_detail->RowType == EW_ROWTYPE_EDIT) // Edit row
			$bsc_order_detail_grid->EditRowCnt++;
		if ($bsc_order_detail->CurrentAction == "F") // Confirm row
			$bsc_order_detail_grid->RestoreCurrentRowFormValues($bsc_order_detail_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$bsc_order_detail->RowAttrs = array_merge($bsc_order_detail->RowAttrs, array('data-rowindex'=>$bsc_order_detail_grid->RowCnt, 'id'=>'r' . $bsc_order_detail_grid->RowCnt . '_bsc_order_detail', 'data-rowtype'=>$bsc_order_detail->RowType));

		// Render row
		$bsc_order_detail_grid->RenderRow();

		// Render list options
		$bsc_order_detail_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($bsc_order_detail_grid->RowAction <> "delete" && $bsc_order_detail_grid->RowAction <> "insertdelete" && !($bsc_order_detail_grid->RowAction == "insert" && $bsc_order_detail->CurrentAction == "F" && $bsc_order_detail_grid->EmptyRow())) {
?>
	<tr<?php echo $bsc_order_detail->RowAttributes() ?>>
<?php

// Render list options (body, left)
$bsc_order_detail_grid->ListOptions->Render("body", "left", $bsc_order_detail_grid->RowCnt);
?>
	<?php if ($bsc_order_detail->item_number->Visible) { // item_number ?>
		<td<?php echo $bsc_order_detail->item_number->CellAttributes() ?>><span id="el<?php echo $bsc_order_detail_grid->RowCnt ?>_bsc_order_detail_item_number" class="control-group bsc_order_detail_item_number">
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_item_number" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" size="30" maxlength="255" placeholder="<?php echo $bsc_order_detail->item_number->PlaceHolder ?>" value="<?php echo $bsc_order_detail->item_number->EditValue ?>"<?php echo $bsc_order_detail->item_number->EditAttributes() ?>>
<input type="hidden" data-field="x_item_number" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_number->OldValue) ?>">
<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_item_number" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" size="30" maxlength="255" placeholder="<?php echo $bsc_order_detail->item_number->PlaceHolder ?>" value="<?php echo $bsc_order_detail->item_number->EditValue ?>"<?php echo $bsc_order_detail->item_number->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_order_detail->item_number->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_number->ListViewValue() ?></span>
<input type="hidden" data-field="x_item_number" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_number->FormValue) ?>">
<input type="hidden" data-field="x_item_number" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_number->OldValue) ?>">
<?php } ?>
</span><a id="<?php echo $bsc_order_detail_grid->PageObjName . "_row_" . $bsc_order_detail_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_id" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($bsc_order_detail->id->CurrentValue) ?>">
<input type="hidden" data-field="x_id" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_id" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($bsc_order_detail->id->OldValue) ?>">
<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_EDIT || $bsc_order_detail->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_id" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_id" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($bsc_order_detail->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($bsc_order_detail->item_name->Visible) { // item_name ?>
		<td<?php echo $bsc_order_detail->item_name->CellAttributes() ?>><span id="el<?php echo $bsc_order_detail_grid->RowCnt ?>_bsc_order_detail_item_name" class="control-group bsc_order_detail_item_name">
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_item_name" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" size="30" maxlength="255" placeholder="<?php echo $bsc_order_detail->item_name->PlaceHolder ?>" value="<?php echo $bsc_order_detail->item_name->EditValue ?>"<?php echo $bsc_order_detail->item_name->EditAttributes() ?>>
<input type="hidden" data-field="x_item_name" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_name->OldValue) ?>">
<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_item_name" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" size="30" maxlength="255" placeholder="<?php echo $bsc_order_detail->item_name->PlaceHolder ?>" value="<?php echo $bsc_order_detail->item_name->EditValue ?>"<?php echo $bsc_order_detail->item_name->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_order_detail->item_name->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_name->ListViewValue() ?></span>
<input type="hidden" data-field="x_item_name" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_name->FormValue) ?>">
<input type="hidden" data-field="x_item_name" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_name->OldValue) ?>">
<?php } ?>
</span><a id="<?php echo $bsc_order_detail_grid->PageObjName . "_row_" . $bsc_order_detail_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order_detail->quantity->Visible) { // quantity ?>
		<td<?php echo $bsc_order_detail->quantity->CellAttributes() ?>><span id="el<?php echo $bsc_order_detail_grid->RowCnt ?>_bsc_order_detail_quantity" class="control-group bsc_order_detail_quantity">
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_quantity" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" size="30" placeholder="<?php echo $bsc_order_detail->quantity->PlaceHolder ?>" value="<?php echo $bsc_order_detail->quantity->EditValue ?>"<?php echo $bsc_order_detail->quantity->EditAttributes() ?>>
<input type="hidden" data-field="x_quantity" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" value="<?php echo ew_HtmlEncode($bsc_order_detail->quantity->OldValue) ?>">
<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_quantity" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" size="30" placeholder="<?php echo $bsc_order_detail->quantity->PlaceHolder ?>" value="<?php echo $bsc_order_detail->quantity->EditValue ?>"<?php echo $bsc_order_detail->quantity->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_order_detail->quantity->ViewAttributes() ?>>
<?php echo $bsc_order_detail->quantity->ListViewValue() ?></span>
<input type="hidden" data-field="x_quantity" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" value="<?php echo ew_HtmlEncode($bsc_order_detail->quantity->FormValue) ?>">
<input type="hidden" data-field="x_quantity" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" value="<?php echo ew_HtmlEncode($bsc_order_detail->quantity->OldValue) ?>">
<?php } ?>
</span><a id="<?php echo $bsc_order_detail_grid->PageObjName . "_row_" . $bsc_order_detail_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order_detail->mc_gross->Visible) { // mc_gross ?>
		<td<?php echo $bsc_order_detail->mc_gross->CellAttributes() ?>><span id="el<?php echo $bsc_order_detail_grid->RowCnt ?>_bsc_order_detail_mc_gross" class="control-group bsc_order_detail_mc_gross">
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_mc_gross" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" size="30" maxlength="255" placeholder="<?php echo $bsc_order_detail->mc_gross->PlaceHolder ?>" value="<?php echo $bsc_order_detail->mc_gross->EditValue ?>"<?php echo $bsc_order_detail->mc_gross->EditAttributes() ?>>
<input type="hidden" data-field="x_mc_gross" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" value="<?php echo ew_HtmlEncode($bsc_order_detail->mc_gross->OldValue) ?>">
<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_mc_gross" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" size="30" maxlength="255" placeholder="<?php echo $bsc_order_detail->mc_gross->PlaceHolder ?>" value="<?php echo $bsc_order_detail->mc_gross->EditValue ?>"<?php echo $bsc_order_detail->mc_gross->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_order_detail->mc_gross->ViewAttributes() ?>>
<?php echo $bsc_order_detail->mc_gross->ListViewValue() ?></span>
<input type="hidden" data-field="x_mc_gross" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" value="<?php echo ew_HtmlEncode($bsc_order_detail->mc_gross->FormValue) ?>">
<input type="hidden" data-field="x_mc_gross" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" value="<?php echo ew_HtmlEncode($bsc_order_detail->mc_gross->OldValue) ?>">
<?php } ?>
</span><a id="<?php echo $bsc_order_detail_grid->PageObjName . "_row_" . $bsc_order_detail_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order_detail->item_price->Visible) { // item_price ?>
		<td<?php echo $bsc_order_detail->item_price->CellAttributes() ?>><span id="el<?php echo $bsc_order_detail_grid->RowCnt ?>_bsc_order_detail_item_price" class="control-group bsc_order_detail_item_price">
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_item_price" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" size="30" placeholder="<?php echo $bsc_order_detail->item_price->PlaceHolder ?>" value="<?php echo $bsc_order_detail->item_price->EditValue ?>"<?php echo $bsc_order_detail->item_price->EditAttributes() ?>>
<input type="hidden" data-field="x_item_price" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_price->OldValue) ?>">
<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_item_price" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" size="30" placeholder="<?php echo $bsc_order_detail->item_price->PlaceHolder ?>" value="<?php echo $bsc_order_detail->item_price->EditValue ?>"<?php echo $bsc_order_detail->item_price->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_order_detail->item_price->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_price->ListViewValue() ?></span>
<input type="hidden" data-field="x_item_price" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_price->FormValue) ?>">
<input type="hidden" data-field="x_item_price" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_price->OldValue) ?>">
<?php } ?>
</span><a id="<?php echo $bsc_order_detail_grid->PageObjName . "_row_" . $bsc_order_detail_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$bsc_order_detail_grid->ListOptions->Render("body", "right", $bsc_order_detail_grid->RowCnt);
?>
	</tr>
<?php if ($bsc_order_detail->RowType == EW_ROWTYPE_ADD || $bsc_order_detail->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fbsc_order_detailgrid.UpdateOpts(<?php echo $bsc_order_detail_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($bsc_order_detail->CurrentAction <> "gridadd" || $bsc_order_detail->CurrentMode == "copy")
		if (!$bsc_order_detail_grid->Recordset->EOF) $bsc_order_detail_grid->Recordset->MoveNext();
}
?>
<?php
	if ($bsc_order_detail->CurrentMode == "add" || $bsc_order_detail->CurrentMode == "copy" || $bsc_order_detail->CurrentMode == "edit") {
		$bsc_order_detail_grid->RowIndex = '$rowindex$';
		$bsc_order_detail_grid->LoadDefaultValues();

		// Set row properties
		$bsc_order_detail->ResetAttrs();
		$bsc_order_detail->RowAttrs = array_merge($bsc_order_detail->RowAttrs, array('data-rowindex'=>$bsc_order_detail_grid->RowIndex, 'id'=>'r0_bsc_order_detail', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($bsc_order_detail->RowAttrs["class"], "ewTemplate");
		$bsc_order_detail->RowType = EW_ROWTYPE_ADD;

		// Render row
		$bsc_order_detail_grid->RenderRow();

		// Render list options
		$bsc_order_detail_grid->RenderListOptions();
		$bsc_order_detail_grid->StartRowCnt = 0;
?>
	<tr<?php echo $bsc_order_detail->RowAttributes() ?>>
<?php

// Render list options (body, left)
$bsc_order_detail_grid->ListOptions->Render("body", "left", $bsc_order_detail_grid->RowIndex);
?>
	<?php if ($bsc_order_detail->item_number->Visible) { // item_number ?>
		<td><span id="el$rowindex$_bsc_order_detail_item_number" class="control-group bsc_order_detail_item_number">
<?php if ($bsc_order_detail->CurrentAction <> "F") { ?>
<input type="text" data-field="x_item_number" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" size="30" maxlength="255" placeholder="<?php echo $bsc_order_detail->item_number->PlaceHolder ?>" value="<?php echo $bsc_order_detail->item_number->EditValue ?>"<?php echo $bsc_order_detail->item_number->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $bsc_order_detail->item_number->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_number->ViewValue ?></span>
<input type="hidden" data-field="x_item_number" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_number->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_item_number" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_number" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_number->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_order_detail->item_name->Visible) { // item_name ?>
		<td><span id="el$rowindex$_bsc_order_detail_item_name" class="control-group bsc_order_detail_item_name">
<?php if ($bsc_order_detail->CurrentAction <> "F") { ?>
<input type="text" data-field="x_item_name" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" size="30" maxlength="255" placeholder="<?php echo $bsc_order_detail->item_name->PlaceHolder ?>" value="<?php echo $bsc_order_detail->item_name->EditValue ?>"<?php echo $bsc_order_detail->item_name->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $bsc_order_detail->item_name->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_name->ViewValue ?></span>
<input type="hidden" data-field="x_item_name" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_item_name" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_name" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_name->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_order_detail->quantity->Visible) { // quantity ?>
		<td><span id="el$rowindex$_bsc_order_detail_quantity" class="control-group bsc_order_detail_quantity">
<?php if ($bsc_order_detail->CurrentAction <> "F") { ?>
<input type="text" data-field="x_quantity" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" size="30" placeholder="<?php echo $bsc_order_detail->quantity->PlaceHolder ?>" value="<?php echo $bsc_order_detail->quantity->EditValue ?>"<?php echo $bsc_order_detail->quantity->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $bsc_order_detail->quantity->ViewAttributes() ?>>
<?php echo $bsc_order_detail->quantity->ViewValue ?></span>
<input type="hidden" data-field="x_quantity" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" value="<?php echo ew_HtmlEncode($bsc_order_detail->quantity->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_quantity" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_quantity" value="<?php echo ew_HtmlEncode($bsc_order_detail->quantity->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_order_detail->mc_gross->Visible) { // mc_gross ?>
		<td><span id="el$rowindex$_bsc_order_detail_mc_gross" class="control-group bsc_order_detail_mc_gross">
<?php if ($bsc_order_detail->CurrentAction <> "F") { ?>
<input type="text" data-field="x_mc_gross" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" size="30" maxlength="255" placeholder="<?php echo $bsc_order_detail->mc_gross->PlaceHolder ?>" value="<?php echo $bsc_order_detail->mc_gross->EditValue ?>"<?php echo $bsc_order_detail->mc_gross->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $bsc_order_detail->mc_gross->ViewAttributes() ?>>
<?php echo $bsc_order_detail->mc_gross->ViewValue ?></span>
<input type="hidden" data-field="x_mc_gross" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" value="<?php echo ew_HtmlEncode($bsc_order_detail->mc_gross->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_mc_gross" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_mc_gross" value="<?php echo ew_HtmlEncode($bsc_order_detail->mc_gross->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_order_detail->item_price->Visible) { // item_price ?>
		<td><span id="el$rowindex$_bsc_order_detail_item_price" class="control-group bsc_order_detail_item_price">
<?php if ($bsc_order_detail->CurrentAction <> "F") { ?>
<input type="text" data-field="x_item_price" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" size="30" placeholder="<?php echo $bsc_order_detail->item_price->PlaceHolder ?>" value="<?php echo $bsc_order_detail->item_price->EditValue ?>"<?php echo $bsc_order_detail->item_price->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $bsc_order_detail->item_price->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_price->ViewValue ?></span>
<input type="hidden" data-field="x_item_price" name="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" id="x<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_price->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_item_price" name="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" id="o<?php echo $bsc_order_detail_grid->RowIndex ?>_item_price" value="<?php echo ew_HtmlEncode($bsc_order_detail->item_price->OldValue) ?>">
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$bsc_order_detail_grid->ListOptions->Render("body", "right", $bsc_order_detail_grid->RowCnt);
?>
<script type="text/javascript">
fbsc_order_detailgrid.UpdateOpts(<?php echo $bsc_order_detail_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($bsc_order_detail->CurrentMode == "add" || $bsc_order_detail->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $bsc_order_detail_grid->FormKeyCountName ?>" id="<?php echo $bsc_order_detail_grid->FormKeyCountName ?>" value="<?php echo $bsc_order_detail_grid->KeyCount ?>">
<?php echo $bsc_order_detail_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($bsc_order_detail->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $bsc_order_detail_grid->FormKeyCountName ?>" id="<?php echo $bsc_order_detail_grid->FormKeyCountName ?>" value="<?php echo $bsc_order_detail_grid->KeyCount ?>">
<?php echo $bsc_order_detail_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($bsc_order_detail->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fbsc_order_detailgrid">
</div>
<?php

// Close recordset
if ($bsc_order_detail_grid->Recordset)
	$bsc_order_detail_grid->Recordset->Close();
?>
</div>
</td></tr></table>
<?php if ($bsc_order_detail->Export == "") { ?>
<script type="text/javascript">
fbsc_order_detailgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$bsc_order_detail_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$bsc_order_detail_grid->Page_Terminate();
?>
