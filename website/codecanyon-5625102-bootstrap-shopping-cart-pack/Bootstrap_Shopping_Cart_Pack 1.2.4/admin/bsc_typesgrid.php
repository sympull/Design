<?php include_once "bsc_admininfo.php" ?>
<?php

// Create page object
if (!isset($bsc_types_grid)) $bsc_types_grid = new cbsc_types_grid();

// Page init
$bsc_types_grid->Page_Init();

// Page main
$bsc_types_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_types_grid->Page_Render();
?>
<?php if ($bsc_types->Export == "") { ?>
<script type="text/javascript">

// Page object
var bsc_types_grid = new ew_Page("bsc_types_grid");
bsc_types_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = bsc_types_grid.PageID; // For backward compatibility

// Form object
var fbsc_typesgrid = new ew_Form("fbsc_typesgrid");
fbsc_typesgrid.FormKeyCountName = '<?php echo $bsc_types_grid->FormKeyCountName ?>';

// Validate form
fbsc_typesgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_types->price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_price_offer");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_types->price_offer->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ordering");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_types->ordering->FldErrMsg()) ?>");

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
fbsc_typesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "name", false)) return false;
	if (ew_ValueChanged(fobj, infix, "price", false)) return false;
	if (ew_ValueChanged(fobj, infix, "price_offer", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ordering", false)) return false;
	return true;
}

// Form_CustomValidate event
fbsc_typesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_typesgrid.ValidateRequired = true;
<?php } else { ?>
fbsc_typesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($bsc_types->getCurrentMasterTable() == "" && $bsc_types_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $bsc_types_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($bsc_types->CurrentAction == "gridadd") {
	if ($bsc_types->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$bsc_types_grid->TotalRecs = $bsc_types->SelectRecordCount();
			$bsc_types_grid->Recordset = $bsc_types_grid->LoadRecordset($bsc_types_grid->StartRec-1, $bsc_types_grid->DisplayRecs);
		} else {
			if ($bsc_types_grid->Recordset = $bsc_types_grid->LoadRecordset())
				$bsc_types_grid->TotalRecs = $bsc_types_grid->Recordset->RecordCount();
		}
		$bsc_types_grid->StartRec = 1;
		$bsc_types_grid->DisplayRecs = $bsc_types_grid->TotalRecs;
	} else {
		$bsc_types->CurrentFilter = "0=1";
		$bsc_types_grid->StartRec = 1;
		$bsc_types_grid->DisplayRecs = $bsc_types->GridAddRowCount;
	}
	$bsc_types_grid->TotalRecs = $bsc_types_grid->DisplayRecs;
	$bsc_types_grid->StopRec = $bsc_types_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$bsc_types_grid->TotalRecs = $bsc_types->SelectRecordCount();
	} else {
		if ($bsc_types_grid->Recordset = $bsc_types_grid->LoadRecordset())
			$bsc_types_grid->TotalRecs = $bsc_types_grid->Recordset->RecordCount();
	}
	$bsc_types_grid->StartRec = 1;
	$bsc_types_grid->DisplayRecs = $bsc_types_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$bsc_types_grid->Recordset = $bsc_types_grid->LoadRecordset($bsc_types_grid->StartRec-1, $bsc_types_grid->DisplayRecs);
}
$bsc_types_grid->RenderOtherOptions();
?>
<?php $bsc_types_grid->ShowPageHeader(); ?>
<?php
$bsc_types_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fbsc_typesgrid" class="ewForm form-horizontal">
<?php if ($bsc_types_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel ewListOtherOptions">
<?php
	foreach ($bsc_types_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<div id="gmp_bsc_types" class="ewGridMiddlePanel">
<table id="tbl_bsc_typesgrid" class="ewTable ewTableSeparate">
<?php echo $bsc_types->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$bsc_types_grid->RenderListOptions();

// Render list options (header, left)
$bsc_types_grid->ListOptions->Render("header", "left");
?>
<?php if ($bsc_types->name->Visible) { // name ?>
	<?php if ($bsc_types->SortUrl($bsc_types->name) == "") { ?>
		<td><div id="elh_bsc_types_name" class="bsc_types_name"><div class="ewTableHeaderCaption"><?php echo $bsc_types->name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_bsc_types_name" class="bsc_types_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_types->name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_types->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_types->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_types->price->Visible) { // price ?>
	<?php if ($bsc_types->SortUrl($bsc_types->price) == "") { ?>
		<td><div id="elh_bsc_types_price" class="bsc_types_price"><div class="ewTableHeaderCaption"><?php echo $bsc_types->price->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_bsc_types_price" class="bsc_types_price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_types->price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_types->price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_types->price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_types->price_offer->Visible) { // price_offer ?>
	<?php if ($bsc_types->SortUrl($bsc_types->price_offer) == "") { ?>
		<td><div id="elh_bsc_types_price_offer" class="bsc_types_price_offer"><div class="ewTableHeaderCaption"><?php echo $bsc_types->price_offer->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_bsc_types_price_offer" class="bsc_types_price_offer">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_types->price_offer->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_types->price_offer->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_types->price_offer->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_types->ordering->Visible) { // ordering ?>
	<?php if ($bsc_types->SortUrl($bsc_types->ordering) == "") { ?>
		<td><div id="elh_bsc_types_ordering" class="bsc_types_ordering"><div class="ewTableHeaderCaption"><?php echo $bsc_types->ordering->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_bsc_types_ordering" class="bsc_types_ordering">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_types->ordering->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_types->ordering->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_types->ordering->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$bsc_types_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$bsc_types_grid->StartRec = 1;
$bsc_types_grid->StopRec = $bsc_types_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($bsc_types_grid->FormKeyCountName) && ($bsc_types->CurrentAction == "gridadd" || $bsc_types->CurrentAction == "gridedit" || $bsc_types->CurrentAction == "F")) {
		$bsc_types_grid->KeyCount = $objForm->GetValue($bsc_types_grid->FormKeyCountName);
		$bsc_types_grid->StopRec = $bsc_types_grid->StartRec + $bsc_types_grid->KeyCount - 1;
	}
}
$bsc_types_grid->RecCnt = $bsc_types_grid->StartRec - 1;
if ($bsc_types_grid->Recordset && !$bsc_types_grid->Recordset->EOF) {
	$bsc_types_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $bsc_types_grid->StartRec > 1)
		$bsc_types_grid->Recordset->Move($bsc_types_grid->StartRec - 1);
} elseif (!$bsc_types->AllowAddDeleteRow && $bsc_types_grid->StopRec == 0) {
	$bsc_types_grid->StopRec = $bsc_types->GridAddRowCount;
}

// Initialize aggregate
$bsc_types->RowType = EW_ROWTYPE_AGGREGATEINIT;
$bsc_types->ResetAttrs();
$bsc_types_grid->RenderRow();
if ($bsc_types->CurrentAction == "gridadd")
	$bsc_types_grid->RowIndex = 0;
if ($bsc_types->CurrentAction == "gridedit")
	$bsc_types_grid->RowIndex = 0;
while ($bsc_types_grid->RecCnt < $bsc_types_grid->StopRec) {
	$bsc_types_grid->RecCnt++;
	if (intval($bsc_types_grid->RecCnt) >= intval($bsc_types_grid->StartRec)) {
		$bsc_types_grid->RowCnt++;
		if ($bsc_types->CurrentAction == "gridadd" || $bsc_types->CurrentAction == "gridedit" || $bsc_types->CurrentAction == "F") {
			$bsc_types_grid->RowIndex++;
			$objForm->Index = $bsc_types_grid->RowIndex;
			if ($objForm->HasValue($bsc_types_grid->FormActionName))
				$bsc_types_grid->RowAction = strval($objForm->GetValue($bsc_types_grid->FormActionName));
			elseif ($bsc_types->CurrentAction == "gridadd")
				$bsc_types_grid->RowAction = "insert";
			else
				$bsc_types_grid->RowAction = "";
		}

		// Set up key count
		$bsc_types_grid->KeyCount = $bsc_types_grid->RowIndex;

		// Init row class and style
		$bsc_types->ResetAttrs();
		$bsc_types->CssClass = "";
		if ($bsc_types->CurrentAction == "gridadd") {
			if ($bsc_types->CurrentMode == "copy") {
				$bsc_types_grid->LoadRowValues($bsc_types_grid->Recordset); // Load row values
				$bsc_types_grid->SetRecordKey($bsc_types_grid->RowOldKey, $bsc_types_grid->Recordset); // Set old record key
			} else {
				$bsc_types_grid->LoadDefaultValues(); // Load default values
				$bsc_types_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$bsc_types_grid->LoadRowValues($bsc_types_grid->Recordset); // Load row values
		}
		$bsc_types->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($bsc_types->CurrentAction == "gridadd") // Grid add
			$bsc_types->RowType = EW_ROWTYPE_ADD; // Render add
		if ($bsc_types->CurrentAction == "gridadd" && $bsc_types->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$bsc_types_grid->RestoreCurrentRowFormValues($bsc_types_grid->RowIndex); // Restore form values
		if ($bsc_types->CurrentAction == "gridedit") { // Grid edit
			if ($bsc_types->EventCancelled) {
				$bsc_types_grid->RestoreCurrentRowFormValues($bsc_types_grid->RowIndex); // Restore form values
			}
			if ($bsc_types_grid->RowAction == "insert")
				$bsc_types->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$bsc_types->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($bsc_types->CurrentAction == "gridedit" && ($bsc_types->RowType == EW_ROWTYPE_EDIT || $bsc_types->RowType == EW_ROWTYPE_ADD) && $bsc_types->EventCancelled) // Update failed
			$bsc_types_grid->RestoreCurrentRowFormValues($bsc_types_grid->RowIndex); // Restore form values
		if ($bsc_types->RowType == EW_ROWTYPE_EDIT) // Edit row
			$bsc_types_grid->EditRowCnt++;
		if ($bsc_types->CurrentAction == "F") // Confirm row
			$bsc_types_grid->RestoreCurrentRowFormValues($bsc_types_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$bsc_types->RowAttrs = array_merge($bsc_types->RowAttrs, array('data-rowindex'=>$bsc_types_grid->RowCnt, 'id'=>'r' . $bsc_types_grid->RowCnt . '_bsc_types', 'data-rowtype'=>$bsc_types->RowType));

		// Render row
		$bsc_types_grid->RenderRow();

		// Render list options
		$bsc_types_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($bsc_types_grid->RowAction <> "delete" && $bsc_types_grid->RowAction <> "insertdelete" && !($bsc_types_grid->RowAction == "insert" && $bsc_types->CurrentAction == "F" && $bsc_types_grid->EmptyRow())) {
?>
	<tr<?php echo $bsc_types->RowAttributes() ?>>
<?php

// Render list options (body, left)
$bsc_types_grid->ListOptions->Render("body", "left", $bsc_types_grid->RowCnt);
?>
	<?php if ($bsc_types->name->Visible) { // name ?>
		<td<?php echo $bsc_types->name->CellAttributes() ?>><span id="el<?php echo $bsc_types_grid->RowCnt ?>_bsc_types_name" class="control-group bsc_types_name">
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_name" name="x<?php echo $bsc_types_grid->RowIndex ?>_name" id="x<?php echo $bsc_types_grid->RowIndex ?>_name" size="200" maxlength="255" placeholder="<?php echo $bsc_types->name->PlaceHolder ?>" value="<?php echo $bsc_types->name->EditValue ?>"<?php echo $bsc_types->name->EditAttributes() ?>>
<input type="hidden" data-field="x_name" name="o<?php echo $bsc_types_grid->RowIndex ?>_name" id="o<?php echo $bsc_types_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($bsc_types->name->OldValue) ?>">
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_name" name="x<?php echo $bsc_types_grid->RowIndex ?>_name" id="x<?php echo $bsc_types_grid->RowIndex ?>_name" size="200" maxlength="255" placeholder="<?php echo $bsc_types->name->PlaceHolder ?>" value="<?php echo $bsc_types->name->EditValue ?>"<?php echo $bsc_types->name->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_types->name->ViewAttributes() ?>>
<?php echo $bsc_types->name->ListViewValue() ?></span>
<input type="hidden" data-field="x_name" name="x<?php echo $bsc_types_grid->RowIndex ?>_name" id="x<?php echo $bsc_types_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($bsc_types->name->FormValue) ?>">
<input type="hidden" data-field="x_name" name="o<?php echo $bsc_types_grid->RowIndex ?>_name" id="o<?php echo $bsc_types_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($bsc_types->name->OldValue) ?>">
<?php } ?>
</span><a id="<?php echo $bsc_types_grid->PageObjName . "_row_" . $bsc_types_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id" name="x<?php echo $bsc_types_grid->RowIndex ?>_id" id="x<?php echo $bsc_types_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($bsc_types->id->CurrentValue) ?>">
<input type="hidden" data-field="x_id" name="o<?php echo $bsc_types_grid->RowIndex ?>_id" id="o<?php echo $bsc_types_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($bsc_types->id->OldValue) ?>">
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_EDIT || $bsc_types->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_id" name="x<?php echo $bsc_types_grid->RowIndex ?>_id" id="x<?php echo $bsc_types_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($bsc_types->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($bsc_types->price->Visible) { // price ?>
		<td<?php echo $bsc_types->price->CellAttributes() ?>><span id="el<?php echo $bsc_types_grid->RowCnt ?>_bsc_types_price" class="control-group bsc_types_price">
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_price" name="x<?php echo $bsc_types_grid->RowIndex ?>_price" id="x<?php echo $bsc_types_grid->RowIndex ?>_price" size="30" placeholder="<?php echo $bsc_types->price->PlaceHolder ?>" value="<?php echo $bsc_types->price->EditValue ?>"<?php echo $bsc_types->price->EditAttributes() ?>>
<input type="hidden" data-field="x_price" name="o<?php echo $bsc_types_grid->RowIndex ?>_price" id="o<?php echo $bsc_types_grid->RowIndex ?>_price" value="<?php echo ew_HtmlEncode($bsc_types->price->OldValue) ?>">
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_price" name="x<?php echo $bsc_types_grid->RowIndex ?>_price" id="x<?php echo $bsc_types_grid->RowIndex ?>_price" size="30" placeholder="<?php echo $bsc_types->price->PlaceHolder ?>" value="<?php echo $bsc_types->price->EditValue ?>"<?php echo $bsc_types->price->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_types->price->ViewAttributes() ?>>
<?php echo $bsc_types->price->ListViewValue() ?></span>
<input type="hidden" data-field="x_price" name="x<?php echo $bsc_types_grid->RowIndex ?>_price" id="x<?php echo $bsc_types_grid->RowIndex ?>_price" value="<?php echo ew_HtmlEncode($bsc_types->price->FormValue) ?>">
<input type="hidden" data-field="x_price" name="o<?php echo $bsc_types_grid->RowIndex ?>_price" id="o<?php echo $bsc_types_grid->RowIndex ?>_price" value="<?php echo ew_HtmlEncode($bsc_types->price->OldValue) ?>">
<?php } ?>
</span><a id="<?php echo $bsc_types_grid->PageObjName . "_row_" . $bsc_types_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_types->price_offer->Visible) { // price_offer ?>
		<td<?php echo $bsc_types->price_offer->CellAttributes() ?>><span id="el<?php echo $bsc_types_grid->RowCnt ?>_bsc_types_price_offer" class="control-group bsc_types_price_offer">
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_price_offer" name="x<?php echo $bsc_types_grid->RowIndex ?>_price_offer" id="x<?php echo $bsc_types_grid->RowIndex ?>_price_offer" size="30" placeholder="<?php echo $bsc_types->price_offer->PlaceHolder ?>" value="<?php echo $bsc_types->price_offer->EditValue ?>"<?php echo $bsc_types->price_offer->EditAttributes() ?>>
<input type="hidden" data-field="x_price_offer" name="o<?php echo $bsc_types_grid->RowIndex ?>_price_offer" id="o<?php echo $bsc_types_grid->RowIndex ?>_price_offer" value="<?php echo ew_HtmlEncode($bsc_types->price_offer->OldValue) ?>">
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_price_offer" name="x<?php echo $bsc_types_grid->RowIndex ?>_price_offer" id="x<?php echo $bsc_types_grid->RowIndex ?>_price_offer" size="30" placeholder="<?php echo $bsc_types->price_offer->PlaceHolder ?>" value="<?php echo $bsc_types->price_offer->EditValue ?>"<?php echo $bsc_types->price_offer->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_types->price_offer->ViewAttributes() ?>>
<?php echo $bsc_types->price_offer->ListViewValue() ?></span>
<input type="hidden" data-field="x_price_offer" name="x<?php echo $bsc_types_grid->RowIndex ?>_price_offer" id="x<?php echo $bsc_types_grid->RowIndex ?>_price_offer" value="<?php echo ew_HtmlEncode($bsc_types->price_offer->FormValue) ?>">
<input type="hidden" data-field="x_price_offer" name="o<?php echo $bsc_types_grid->RowIndex ?>_price_offer" id="o<?php echo $bsc_types_grid->RowIndex ?>_price_offer" value="<?php echo ew_HtmlEncode($bsc_types->price_offer->OldValue) ?>">
<?php } ?>
</span><a id="<?php echo $bsc_types_grid->PageObjName . "_row_" . $bsc_types_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_types->ordering->Visible) { // ordering ?>
		<td<?php echo $bsc_types->ordering->CellAttributes() ?>><span id="el<?php echo $bsc_types_grid->RowCnt ?>_bsc_types_ordering" class="control-group bsc_types_ordering">
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_ordering" name="x<?php echo $bsc_types_grid->RowIndex ?>_ordering" id="x<?php echo $bsc_types_grid->RowIndex ?>_ordering" size="30" placeholder="<?php echo $bsc_types->ordering->PlaceHolder ?>" value="<?php echo $bsc_types->ordering->EditValue ?>"<?php echo $bsc_types->ordering->EditAttributes() ?>>
<input type="hidden" data-field="x_ordering" name="o<?php echo $bsc_types_grid->RowIndex ?>_ordering" id="o<?php echo $bsc_types_grid->RowIndex ?>_ordering" value="<?php echo ew_HtmlEncode($bsc_types->ordering->OldValue) ?>">
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_ordering" name="x<?php echo $bsc_types_grid->RowIndex ?>_ordering" id="x<?php echo $bsc_types_grid->RowIndex ?>_ordering" size="30" placeholder="<?php echo $bsc_types->ordering->PlaceHolder ?>" value="<?php echo $bsc_types->ordering->EditValue ?>"<?php echo $bsc_types->ordering->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_types->ordering->ViewAttributes() ?>>
<?php echo $bsc_types->ordering->ListViewValue() ?></span>
<input type="hidden" data-field="x_ordering" name="x<?php echo $bsc_types_grid->RowIndex ?>_ordering" id="x<?php echo $bsc_types_grid->RowIndex ?>_ordering" value="<?php echo ew_HtmlEncode($bsc_types->ordering->FormValue) ?>">
<input type="hidden" data-field="x_ordering" name="o<?php echo $bsc_types_grid->RowIndex ?>_ordering" id="o<?php echo $bsc_types_grid->RowIndex ?>_ordering" value="<?php echo ew_HtmlEncode($bsc_types->ordering->OldValue) ?>">
<?php } ?>
</span><a id="<?php echo $bsc_types_grid->PageObjName . "_row_" . $bsc_types_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$bsc_types_grid->ListOptions->Render("body", "right", $bsc_types_grid->RowCnt);
?>
	</tr>
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD || $bsc_types->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fbsc_typesgrid.UpdateOpts(<?php echo $bsc_types_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($bsc_types->CurrentAction <> "gridadd" || $bsc_types->CurrentMode == "copy")
		if (!$bsc_types_grid->Recordset->EOF) $bsc_types_grid->Recordset->MoveNext();
}
?>
<?php
	if ($bsc_types->CurrentMode == "add" || $bsc_types->CurrentMode == "copy" || $bsc_types->CurrentMode == "edit") {
		$bsc_types_grid->RowIndex = '$rowindex$';
		$bsc_types_grid->LoadDefaultValues();

		// Set row properties
		$bsc_types->ResetAttrs();
		$bsc_types->RowAttrs = array_merge($bsc_types->RowAttrs, array('data-rowindex'=>$bsc_types_grid->RowIndex, 'id'=>'r0_bsc_types', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($bsc_types->RowAttrs["class"], "ewTemplate");
		$bsc_types->RowType = EW_ROWTYPE_ADD;

		// Render row
		$bsc_types_grid->RenderRow();

		// Render list options
		$bsc_types_grid->RenderListOptions();
		$bsc_types_grid->StartRowCnt = 0;
?>
	<tr<?php echo $bsc_types->RowAttributes() ?>>
<?php

// Render list options (body, left)
$bsc_types_grid->ListOptions->Render("body", "left", $bsc_types_grid->RowIndex);
?>
	<?php if ($bsc_types->name->Visible) { // name ?>
		<td><span id="el$rowindex$_bsc_types_name" class="control-group bsc_types_name">
<?php if ($bsc_types->CurrentAction <> "F") { ?>
<input type="text" data-field="x_name" name="x<?php echo $bsc_types_grid->RowIndex ?>_name" id="x<?php echo $bsc_types_grid->RowIndex ?>_name" size="200" maxlength="255" placeholder="<?php echo $bsc_types->name->PlaceHolder ?>" value="<?php echo $bsc_types->name->EditValue ?>"<?php echo $bsc_types->name->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $bsc_types->name->ViewAttributes() ?>>
<?php echo $bsc_types->name->ViewValue ?></span>
<input type="hidden" data-field="x_name" name="x<?php echo $bsc_types_grid->RowIndex ?>_name" id="x<?php echo $bsc_types_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($bsc_types->name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_name" name="o<?php echo $bsc_types_grid->RowIndex ?>_name" id="o<?php echo $bsc_types_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($bsc_types->name->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_types->price->Visible) { // price ?>
		<td><span id="el$rowindex$_bsc_types_price" class="control-group bsc_types_price">
<?php if ($bsc_types->CurrentAction <> "F") { ?>
<input type="text" data-field="x_price" name="x<?php echo $bsc_types_grid->RowIndex ?>_price" id="x<?php echo $bsc_types_grid->RowIndex ?>_price" size="30" placeholder="<?php echo $bsc_types->price->PlaceHolder ?>" value="<?php echo $bsc_types->price->EditValue ?>"<?php echo $bsc_types->price->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $bsc_types->price->ViewAttributes() ?>>
<?php echo $bsc_types->price->ViewValue ?></span>
<input type="hidden" data-field="x_price" name="x<?php echo $bsc_types_grid->RowIndex ?>_price" id="x<?php echo $bsc_types_grid->RowIndex ?>_price" value="<?php echo ew_HtmlEncode($bsc_types->price->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_price" name="o<?php echo $bsc_types_grid->RowIndex ?>_price" id="o<?php echo $bsc_types_grid->RowIndex ?>_price" value="<?php echo ew_HtmlEncode($bsc_types->price->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_types->price_offer->Visible) { // price_offer ?>
		<td><span id="el$rowindex$_bsc_types_price_offer" class="control-group bsc_types_price_offer">
<?php if ($bsc_types->CurrentAction <> "F") { ?>
<input type="text" data-field="x_price_offer" name="x<?php echo $bsc_types_grid->RowIndex ?>_price_offer" id="x<?php echo $bsc_types_grid->RowIndex ?>_price_offer" size="30" placeholder="<?php echo $bsc_types->price_offer->PlaceHolder ?>" value="<?php echo $bsc_types->price_offer->EditValue ?>"<?php echo $bsc_types->price_offer->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $bsc_types->price_offer->ViewAttributes() ?>>
<?php echo $bsc_types->price_offer->ViewValue ?></span>
<input type="hidden" data-field="x_price_offer" name="x<?php echo $bsc_types_grid->RowIndex ?>_price_offer" id="x<?php echo $bsc_types_grid->RowIndex ?>_price_offer" value="<?php echo ew_HtmlEncode($bsc_types->price_offer->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_price_offer" name="o<?php echo $bsc_types_grid->RowIndex ?>_price_offer" id="o<?php echo $bsc_types_grid->RowIndex ?>_price_offer" value="<?php echo ew_HtmlEncode($bsc_types->price_offer->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_types->ordering->Visible) { // ordering ?>
		<td><span id="el$rowindex$_bsc_types_ordering" class="control-group bsc_types_ordering">
<?php if ($bsc_types->CurrentAction <> "F") { ?>
<input type="text" data-field="x_ordering" name="x<?php echo $bsc_types_grid->RowIndex ?>_ordering" id="x<?php echo $bsc_types_grid->RowIndex ?>_ordering" size="30" placeholder="<?php echo $bsc_types->ordering->PlaceHolder ?>" value="<?php echo $bsc_types->ordering->EditValue ?>"<?php echo $bsc_types->ordering->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $bsc_types->ordering->ViewAttributes() ?>>
<?php echo $bsc_types->ordering->ViewValue ?></span>
<input type="hidden" data-field="x_ordering" name="x<?php echo $bsc_types_grid->RowIndex ?>_ordering" id="x<?php echo $bsc_types_grid->RowIndex ?>_ordering" value="<?php echo ew_HtmlEncode($bsc_types->ordering->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ordering" name="o<?php echo $bsc_types_grid->RowIndex ?>_ordering" id="o<?php echo $bsc_types_grid->RowIndex ?>_ordering" value="<?php echo ew_HtmlEncode($bsc_types->ordering->OldValue) ?>">
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$bsc_types_grid->ListOptions->Render("body", "right", $bsc_types_grid->RowCnt);
?>
<script type="text/javascript">
fbsc_typesgrid.UpdateOpts(<?php echo $bsc_types_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($bsc_types->CurrentMode == "add" || $bsc_types->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $bsc_types_grid->FormKeyCountName ?>" id="<?php echo $bsc_types_grid->FormKeyCountName ?>" value="<?php echo $bsc_types_grid->KeyCount ?>">
<?php echo $bsc_types_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($bsc_types->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $bsc_types_grid->FormKeyCountName ?>" id="<?php echo $bsc_types_grid->FormKeyCountName ?>" value="<?php echo $bsc_types_grid->KeyCount ?>">
<?php echo $bsc_types_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($bsc_types->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fbsc_typesgrid">
</div>
<?php

// Close recordset
if ($bsc_types_grid->Recordset)
	$bsc_types_grid->Recordset->Close();
?>
</div>
</td></tr></table>
<?php if ($bsc_types->Export == "") { ?>
<script type="text/javascript">
fbsc_typesgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$bsc_types_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$bsc_types_grid->Page_Terminate();
?>
