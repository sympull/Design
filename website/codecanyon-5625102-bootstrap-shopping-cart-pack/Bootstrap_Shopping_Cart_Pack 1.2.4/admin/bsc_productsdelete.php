<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "bsc_productsinfo.php" ?>
<?php include_once "bsc_admininfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$bsc_products_delete = NULL; // Initialize page object first

class cbsc_products_delete extends cbsc_products {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_products';

	// Page object name
	var $PageObjName = 'bsc_products_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (bsc_products)
		if (!isset($GLOBALS["bsc_products"])) {
			$GLOBALS["bsc_products"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_products"];
		}

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_products', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("bsc_productslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in bsc_products class, bsc_productsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->img->Upload->DbValue = $rs->fields('img');
		$this->idCategory->setDbValue($rs->fields('idCategory'));
		$this->productCode->setDbValue($rs->fields('productCode'));
		$this->name->setDbValue($rs->fields('name'));
		$this->description->setDbValue($rs->fields('description'));
		$this->price->setDbValue($rs->fields('price'));
		$this->price_offer->setDbValue($rs->fields('price_offer'));
		$this->img_detail1->Upload->DbValue = $rs->fields('img_detail1');
		$this->img_detail2->Upload->DbValue = $rs->fields('img_detail2');
		$this->img_detail3->Upload->DbValue = $rs->fields('img_detail3');
		$this->download->Upload->DbValue = $rs->fields('download');
		$this->ordering->setDbValue($rs->fields('ordering'));
		$this->visible->setDbValue($rs->fields('visible'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->img->Upload->DbValue = $row['img'];
		$this->idCategory->DbValue = $row['idCategory'];
		$this->productCode->DbValue = $row['productCode'];
		$this->name->DbValue = $row['name'];
		$this->description->DbValue = $row['description'];
		$this->price->DbValue = $row['price'];
		$this->price_offer->DbValue = $row['price_offer'];
		$this->img_detail1->Upload->DbValue = $row['img_detail1'];
		$this->img_detail2->Upload->DbValue = $row['img_detail2'];
		$this->img_detail3->Upload->DbValue = $row['img_detail3'];
		$this->download->Upload->DbValue = $row['download'];
		$this->ordering->DbValue = $row['ordering'];
		$this->visible->DbValue = $row['visible'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->price->FormValue == $this->price->CurrentValue && is_numeric(ew_StrToFloat($this->price->CurrentValue)))
			$this->price->CurrentValue = ew_StrToFloat($this->price->CurrentValue);

		// Convert decimal values if posted back
		if ($this->price_offer->FormValue == $this->price_offer->CurrentValue && is_numeric(ew_StrToFloat($this->price_offer->CurrentValue)))
			$this->price_offer->CurrentValue = ew_StrToFloat($this->price_offer->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// img
		// idCategory
		// productCode
		// name
		// description
		// price
		// price_offer
		// img_detail1
		// img_detail2
		// img_detail3
		// download
		// ordering
		// visible

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// img
			$this->img->UploadPath = "../assets";
			if (!ew_Empty($this->img->Upload->DbValue)) {
				$this->img->ImageWidth = 100;
				$this->img->ImageHeight = 0;
				$this->img->ImageAlt = $this->img->FldAlt();
				$this->img->ViewValue = ew_UploadPathEx(FALSE, $this->img->UploadPath) . $this->img->Upload->DbValue;
			} else {
				$this->img->ViewValue = "";
			}
			$this->img->ViewCustomAttributes = "";

			// idCategory
			if (strval($this->idCategory->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->idCategory->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bsc_category`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idCategory, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->idCategory->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->idCategory->ViewValue = $this->idCategory->CurrentValue;
				}
			} else {
				$this->idCategory->ViewValue = NULL;
			}
			$this->idCategory->ViewCustomAttributes = "";

			// productCode
			$this->productCode->ViewValue = $this->productCode->CurrentValue;
			$this->productCode->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// price
			$this->price->ViewValue = $this->price->CurrentValue;
			$this->price->ViewCustomAttributes = "";

			// price_offer
			$this->price_offer->ViewValue = $this->price_offer->CurrentValue;
			$this->price_offer->ViewCustomAttributes = "";

			// img_detail1
			$this->img_detail1->UploadPath = "../assets";
			if (!ew_Empty($this->img_detail1->Upload->DbValue)) {
				$this->img_detail1->ImageAlt = $this->img_detail1->FldAlt();
				$this->img_detail1->ViewValue = ew_UploadPathEx(FALSE, $this->img_detail1->UploadPath) . $this->img_detail1->Upload->DbValue;
			} else {
				$this->img_detail1->ViewValue = "";
			}
			$this->img_detail1->ViewCustomAttributes = "";

			// img_detail2
			$this->img_detail2->UploadPath = "../assets";
			if (!ew_Empty($this->img_detail2->Upload->DbValue)) {
				$this->img_detail2->ImageAlt = $this->img_detail2->FldAlt();
				$this->img_detail2->ViewValue = ew_UploadPathEx(FALSE, $this->img_detail2->UploadPath) . $this->img_detail2->Upload->DbValue;
			} else {
				$this->img_detail2->ViewValue = "";
			}
			$this->img_detail2->ViewCustomAttributes = "";

			// img_detail3
			$this->img_detail3->UploadPath = "../assets";
			if (!ew_Empty($this->img_detail3->Upload->DbValue)) {
				$this->img_detail3->ImageAlt = $this->img_detail3->FldAlt();
				$this->img_detail3->ViewValue = ew_UploadPathEx(FALSE, $this->img_detail3->UploadPath) . $this->img_detail3->Upload->DbValue;
			} else {
				$this->img_detail3->ViewValue = "";
			}
			$this->img_detail3->ViewCustomAttributes = "";

			// download
			$this->download->UploadPath = "../assets";
			if (!ew_Empty($this->download->Upload->DbValue)) {
				$this->download->ViewValue = $this->download->Upload->DbValue;
			} else {
				$this->download->ViewValue = "";
			}
			$this->download->ViewCustomAttributes = "";

			// ordering
			$this->ordering->ViewValue = $this->ordering->CurrentValue;
			$this->ordering->ViewCustomAttributes = "";

			// visible
			if (strval($this->visible->CurrentValue) <> "") {
				$this->visible->ViewValue = "";
				$arwrk = explode(",", strval($this->visible->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->visible->FldTagValue(1):
							$this->visible->ViewValue .= $this->visible->FldTagCaption(1) <> "" ? $this->visible->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->visible->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->visible->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->visible->ViewValue = NULL;
			}
			$this->visible->ViewCustomAttributes = "";

			// img
			$this->img->LinkCustomAttributes = "";
			$this->img->HrefValue = "";
			$this->img->HrefValue2 = $this->img->UploadPath . $this->img->Upload->DbValue;
			$this->img->TooltipValue = "";

			// idCategory
			$this->idCategory->LinkCustomAttributes = "";
			$this->idCategory->HrefValue = "";
			$this->idCategory->TooltipValue = "";

			// productCode
			$this->productCode->LinkCustomAttributes = "";
			$this->productCode->HrefValue = "";
			$this->productCode->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";
			$this->price->TooltipValue = "";

			// price_offer
			$this->price_offer->LinkCustomAttributes = "";
			$this->price_offer->HrefValue = "";
			$this->price_offer->TooltipValue = "";

			// img_detail1
			$this->img_detail1->LinkCustomAttributes = "";
			$this->img_detail1->HrefValue = "";
			$this->img_detail1->HrefValue2 = $this->img_detail1->UploadPath . $this->img_detail1->Upload->DbValue;
			$this->img_detail1->TooltipValue = "";

			// img_detail2
			$this->img_detail2->LinkCustomAttributes = "";
			$this->img_detail2->HrefValue = "";
			$this->img_detail2->HrefValue2 = $this->img_detail2->UploadPath . $this->img_detail2->Upload->DbValue;
			$this->img_detail2->TooltipValue = "";

			// img_detail3
			$this->img_detail3->LinkCustomAttributes = "";
			$this->img_detail3->HrefValue = "";
			$this->img_detail3->HrefValue2 = $this->img_detail3->UploadPath . $this->img_detail3->Upload->DbValue;
			$this->img_detail3->TooltipValue = "";

			// download
			$this->download->LinkCustomAttributes = "";
			$this->download->HrefValue = "";
			$this->download->HrefValue2 = $this->download->UploadPath . $this->download->Upload->DbValue;
			$this->download->TooltipValue = "";

			// ordering
			$this->ordering->LinkCustomAttributes = "";
			$this->ordering->HrefValue = "";
			$this->ordering->TooltipValue = "";

			// visible
			$this->visible->LinkCustomAttributes = "";
			$this->visible->HrefValue = "";
			$this->visible->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "bsc_productslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($bsc_products_delete)) $bsc_products_delete = new cbsc_products_delete();

// Page init
$bsc_products_delete->Page_Init();

// Page main
$bsc_products_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_products_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var bsc_products_delete = new ew_Page("bsc_products_delete");
bsc_products_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = bsc_products_delete.PageID; // For backward compatibility

// Form object
var fbsc_productsdelete = new ew_Form("fbsc_productsdelete");

// Form_CustomValidate event
fbsc_productsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_productsdelete.ValidateRequired = true;
<?php } else { ?>
fbsc_productsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fbsc_productsdelete.Lists["x_idCategory"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($bsc_products_delete->Recordset = $bsc_products_delete->LoadRecordset())
	$bsc_products_deleteTotalRecs = $bsc_products_delete->Recordset->RecordCount(); // Get record count
if ($bsc_products_deleteTotalRecs <= 0) { // No record found, exit
	if ($bsc_products_delete->Recordset)
		$bsc_products_delete->Recordset->Close();
	$bsc_products_delete->Page_Terminate("bsc_productslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $bsc_products_delete->ShowPageHeader(); ?>
<?php
$bsc_products_delete->ShowMessage();
?>
<form name="fbsc_productsdelete" id="fbsc_productsdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_products">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($bsc_products_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_bsc_productsdelete" class="ewTable ewTableSeparate">
<?php echo $bsc_products->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_bsc_products_img" class="bsc_products_img"><?php echo $bsc_products->img->FldCaption() ?></span></td>
		<td><span id="elh_bsc_products_idCategory" class="bsc_products_idCategory"><?php echo $bsc_products->idCategory->FldCaption() ?></span></td>
		<td><span id="elh_bsc_products_productCode" class="bsc_products_productCode"><?php echo $bsc_products->productCode->FldCaption() ?></span></td>
		<td><span id="elh_bsc_products_name" class="bsc_products_name"><?php echo $bsc_products->name->FldCaption() ?></span></td>
		<td><span id="elh_bsc_products_price" class="bsc_products_price"><?php echo $bsc_products->price->FldCaption() ?></span></td>
		<td><span id="elh_bsc_products_price_offer" class="bsc_products_price_offer"><?php echo $bsc_products->price_offer->FldCaption() ?></span></td>
		<td><span id="elh_bsc_products_img_detail1" class="bsc_products_img_detail1"><?php echo $bsc_products->img_detail1->FldCaption() ?></span></td>
		<td><span id="elh_bsc_products_img_detail2" class="bsc_products_img_detail2"><?php echo $bsc_products->img_detail2->FldCaption() ?></span></td>
		<td><span id="elh_bsc_products_img_detail3" class="bsc_products_img_detail3"><?php echo $bsc_products->img_detail3->FldCaption() ?></span></td>
		<td><span id="elh_bsc_products_download" class="bsc_products_download"><?php echo $bsc_products->download->FldCaption() ?></span></td>
		<td><span id="elh_bsc_products_ordering" class="bsc_products_ordering"><?php echo $bsc_products->ordering->FldCaption() ?></span></td>
		<td><span id="elh_bsc_products_visible" class="bsc_products_visible"><?php echo $bsc_products->visible->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$bsc_products_delete->RecCnt = 0;
$i = 0;
while (!$bsc_products_delete->Recordset->EOF) {
	$bsc_products_delete->RecCnt++;
	$bsc_products_delete->RowCnt++;

	// Set row properties
	$bsc_products->ResetAttrs();
	$bsc_products->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$bsc_products_delete->LoadRowValues($bsc_products_delete->Recordset);

	// Render row
	$bsc_products_delete->RenderRow();
?>
	<tr<?php echo $bsc_products->RowAttributes() ?>>
		<td<?php echo $bsc_products->img->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_img" class="control-group bsc_products_img">
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
		<td<?php echo $bsc_products->idCategory->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_idCategory" class="control-group bsc_products_idCategory">
<span<?php echo $bsc_products->idCategory->ViewAttributes() ?>>
<?php echo $bsc_products->idCategory->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_products->productCode->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_productCode" class="control-group bsc_products_productCode">
<span<?php echo $bsc_products->productCode->ViewAttributes() ?>>
<?php echo $bsc_products->productCode->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_products->name->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_name" class="control-group bsc_products_name">
<span<?php echo $bsc_products->name->ViewAttributes() ?>>
<?php echo $bsc_products->name->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_products->price->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_price" class="control-group bsc_products_price">
<span<?php echo $bsc_products->price->ViewAttributes() ?>>
<?php echo $bsc_products->price->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_products->price_offer->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_price_offer" class="control-group bsc_products_price_offer">
<span<?php echo $bsc_products->price_offer->ViewAttributes() ?>>
<?php echo $bsc_products->price_offer->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_products->img_detail1->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_img_detail1" class="control-group bsc_products_img_detail1">
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
		<td<?php echo $bsc_products->img_detail2->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_img_detail2" class="control-group bsc_products_img_detail2">
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
		<td<?php echo $bsc_products->img_detail3->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_img_detail3" class="control-group bsc_products_img_detail3">
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
		<td<?php echo $bsc_products->download->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_download" class="control-group bsc_products_download">
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
		<td<?php echo $bsc_products->ordering->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_ordering" class="control-group bsc_products_ordering">
<span<?php echo $bsc_products->ordering->ViewAttributes() ?>>
<?php echo $bsc_products->ordering->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_products->visible->CellAttributes() ?>><span id="el<?php echo $bsc_products_delete->RowCnt ?>_bsc_products_visible" class="control-group bsc_products_visible">
<span<?php echo $bsc_products->visible->ViewAttributes() ?>>
<?php echo $bsc_products->visible->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$bsc_products_delete->Recordset->MoveNext();
}
$bsc_products_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fbsc_productsdelete.Init();
</script>
<?php
$bsc_products_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bsc_products_delete->Page_Terminate();
?>
