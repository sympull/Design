<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "bsc_orderinfo.php" ?>
<?php include_once "bsc_admininfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$bsc_order_delete = NULL; // Initialize page object first

class cbsc_order_delete extends cbsc_order {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_order';

	// Page object name
	var $PageObjName = 'bsc_order_delete';

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

		// Table object (bsc_order)
		if (!isset($GLOBALS["bsc_order"])) {
			$GLOBALS["bsc_order"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_order"];
		}

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_order', TRUE);

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
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("bsc_orderlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in bsc_order class, bsc_orderinfo.php

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
		$this->dateorder->setDbValue($rs->fields('dateorder'));
		$this->payer_email->setDbValue($rs->fields('payer_email'));
		$this->payment_type->setDbValue($rs->fields('payment_type'));
		$this->custom->setDbValue($rs->fields('custom'));
		$this->invoice->setDbValue($rs->fields('invoice'));
		$this->item_name->setDbValue($rs->fields('item_name'));
		$this->item_number->setDbValue($rs->fields('item_number'));
		$this->quantity->setDbValue($rs->fields('quantity'));
		$this->payment_status->setDbValue($rs->fields('payment_status'));
		$this->payment_amount->setDbValue($rs->fields('payment_amount'));
		$this->payment_currency->setDbValue($rs->fields('payment_currency'));
		$this->first_name->setDbValue($rs->fields('first_name'));
		$this->last_name->setDbValue($rs->fields('last_name'));
		$this->address_name->setDbValue($rs->fields('address_name'));
		$this->address_country->setDbValue($rs->fields('address_country'));
		$this->address_country_code->setDbValue($rs->fields('address_country_code'));
		$this->address_zip->setDbValue($rs->fields('address_zip'));
		$this->address_state->setDbValue($rs->fields('address_state'));
		$this->address_city->setDbValue($rs->fields('address_city'));
		$this->address_street->setDbValue($rs->fields('address_street'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->dateorder->DbValue = $row['dateorder'];
		$this->payer_email->DbValue = $row['payer_email'];
		$this->payment_type->DbValue = $row['payment_type'];
		$this->custom->DbValue = $row['custom'];
		$this->invoice->DbValue = $row['invoice'];
		$this->item_name->DbValue = $row['item_name'];
		$this->item_number->DbValue = $row['item_number'];
		$this->quantity->DbValue = $row['quantity'];
		$this->payment_status->DbValue = $row['payment_status'];
		$this->payment_amount->DbValue = $row['payment_amount'];
		$this->payment_currency->DbValue = $row['payment_currency'];
		$this->first_name->DbValue = $row['first_name'];
		$this->last_name->DbValue = $row['last_name'];
		$this->address_name->DbValue = $row['address_name'];
		$this->address_country->DbValue = $row['address_country'];
		$this->address_country_code->DbValue = $row['address_country_code'];
		$this->address_zip->DbValue = $row['address_zip'];
		$this->address_state->DbValue = $row['address_state'];
		$this->address_city->DbValue = $row['address_city'];
		$this->address_street->DbValue = $row['address_street'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// dateorder
		// payer_email
		// payment_type
		// custom
		// invoice
		// item_name
		// item_number
		// quantity
		// payment_status
		// payment_amount
		// payment_currency
		// first_name
		// last_name
		// address_name
		// address_country
		// address_country_code
		// address_zip
		// address_state
		// address_city
		// address_street

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// dateorder
			$this->dateorder->ViewValue = $this->dateorder->CurrentValue;
			$this->dateorder->ViewValue = ew_FormatDateTime($this->dateorder->ViewValue, 5);
			$this->dateorder->ViewCustomAttributes = "";

			// payer_email
			$this->payer_email->ViewValue = $this->payer_email->CurrentValue;
			$this->payer_email->ViewCustomAttributes = "";

			// payment_type
			$this->payment_type->ViewValue = $this->payment_type->CurrentValue;
			$this->payment_type->ViewCustomAttributes = "";

			// custom
			$this->custom->ViewValue = $this->custom->CurrentValue;
			$this->custom->ViewCustomAttributes = "";

			// invoice
			$this->invoice->ViewValue = $this->invoice->CurrentValue;
			$this->invoice->ViewCustomAttributes = "";

			// item_name
			$this->item_name->ViewValue = $this->item_name->CurrentValue;
			$this->item_name->ViewCustomAttributes = "";

			// item_number
			$this->item_number->ViewValue = $this->item_number->CurrentValue;
			$this->item_number->ViewCustomAttributes = "";

			// quantity
			$this->quantity->ViewValue = $this->quantity->CurrentValue;
			$this->quantity->ViewCustomAttributes = "";

			// payment_status
			$this->payment_status->ViewValue = $this->payment_status->CurrentValue;
			$this->payment_status->ViewCustomAttributes = "";

			// payment_amount
			$this->payment_amount->ViewValue = $this->payment_amount->CurrentValue;
			$this->payment_amount->ViewCustomAttributes = "";

			// payment_currency
			$this->payment_currency->ViewValue = $this->payment_currency->CurrentValue;
			$this->payment_currency->ViewCustomAttributes = "";

			// first_name
			$this->first_name->ViewValue = $this->first_name->CurrentValue;
			$this->first_name->ViewCustomAttributes = "";

			// last_name
			$this->last_name->ViewValue = $this->last_name->CurrentValue;
			$this->last_name->ViewCustomAttributes = "";

			// address_name
			$this->address_name->ViewValue = $this->address_name->CurrentValue;
			$this->address_name->ViewCustomAttributes = "";

			// address_country
			$this->address_country->ViewValue = $this->address_country->CurrentValue;
			$this->address_country->ViewCustomAttributes = "";

			// address_country_code
			$this->address_country_code->ViewValue = $this->address_country_code->CurrentValue;
			$this->address_country_code->ViewCustomAttributes = "";

			// address_zip
			$this->address_zip->ViewValue = $this->address_zip->CurrentValue;
			$this->address_zip->ViewCustomAttributes = "";

			// address_state
			$this->address_state->ViewValue = $this->address_state->CurrentValue;
			$this->address_state->ViewCustomAttributes = "";

			// address_city
			$this->address_city->ViewValue = $this->address_city->CurrentValue;
			$this->address_city->ViewCustomAttributes = "";

			// address_street
			$this->address_street->ViewValue = $this->address_street->CurrentValue;
			$this->address_street->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// dateorder
			$this->dateorder->LinkCustomAttributes = "";
			$this->dateorder->HrefValue = "";
			$this->dateorder->TooltipValue = "";

			// payer_email
			$this->payer_email->LinkCustomAttributes = "";
			$this->payer_email->HrefValue = "";
			$this->payer_email->TooltipValue = "";

			// payment_type
			$this->payment_type->LinkCustomAttributes = "";
			$this->payment_type->HrefValue = "";
			$this->payment_type->TooltipValue = "";

			// custom
			$this->custom->LinkCustomAttributes = "";
			$this->custom->HrefValue = "";
			$this->custom->TooltipValue = "";

			// invoice
			$this->invoice->LinkCustomAttributes = "";
			$this->invoice->HrefValue = "";
			$this->invoice->TooltipValue = "";

			// item_name
			$this->item_name->LinkCustomAttributes = "";
			$this->item_name->HrefValue = "";
			$this->item_name->TooltipValue = "";

			// item_number
			$this->item_number->LinkCustomAttributes = "";
			$this->item_number->HrefValue = "";
			$this->item_number->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// payment_status
			$this->payment_status->LinkCustomAttributes = "";
			$this->payment_status->HrefValue = "";
			$this->payment_status->TooltipValue = "";

			// payment_amount
			$this->payment_amount->LinkCustomAttributes = "";
			$this->payment_amount->HrefValue = "";
			$this->payment_amount->TooltipValue = "";

			// payment_currency
			$this->payment_currency->LinkCustomAttributes = "";
			$this->payment_currency->HrefValue = "";
			$this->payment_currency->TooltipValue = "";

			// first_name
			$this->first_name->LinkCustomAttributes = "";
			$this->first_name->HrefValue = "";
			$this->first_name->TooltipValue = "";

			// last_name
			$this->last_name->LinkCustomAttributes = "";
			$this->last_name->HrefValue = "";
			$this->last_name->TooltipValue = "";

			// address_name
			$this->address_name->LinkCustomAttributes = "";
			$this->address_name->HrefValue = "";
			$this->address_name->TooltipValue = "";

			// address_country
			$this->address_country->LinkCustomAttributes = "";
			$this->address_country->HrefValue = "";
			$this->address_country->TooltipValue = "";

			// address_country_code
			$this->address_country_code->LinkCustomAttributes = "";
			$this->address_country_code->HrefValue = "";
			$this->address_country_code->TooltipValue = "";

			// address_zip
			$this->address_zip->LinkCustomAttributes = "";
			$this->address_zip->HrefValue = "";
			$this->address_zip->TooltipValue = "";

			// address_state
			$this->address_state->LinkCustomAttributes = "";
			$this->address_state->HrefValue = "";
			$this->address_state->TooltipValue = "";

			// address_city
			$this->address_city->LinkCustomAttributes = "";
			$this->address_city->HrefValue = "";
			$this->address_city->TooltipValue = "";

			// address_street
			$this->address_street->LinkCustomAttributes = "";
			$this->address_street->HrefValue = "";
			$this->address_street->TooltipValue = "";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "bsc_orderlist.php", $this->TableVar);
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
if (!isset($bsc_order_delete)) $bsc_order_delete = new cbsc_order_delete();

// Page init
$bsc_order_delete->Page_Init();

// Page main
$bsc_order_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_order_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var bsc_order_delete = new ew_Page("bsc_order_delete");
bsc_order_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = bsc_order_delete.PageID; // For backward compatibility

// Form object
var fbsc_orderdelete = new ew_Form("fbsc_orderdelete");

// Form_CustomValidate event
fbsc_orderdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_orderdelete.ValidateRequired = true;
<?php } else { ?>
fbsc_orderdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($bsc_order_delete->Recordset = $bsc_order_delete->LoadRecordset())
	$bsc_order_deleteTotalRecs = $bsc_order_delete->Recordset->RecordCount(); // Get record count
if ($bsc_order_deleteTotalRecs <= 0) { // No record found, exit
	if ($bsc_order_delete->Recordset)
		$bsc_order_delete->Recordset->Close();
	$bsc_order_delete->Page_Terminate("bsc_orderlist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $bsc_order_delete->ShowPageHeader(); ?>
<?php
$bsc_order_delete->ShowMessage();
?>
<form name="fbsc_orderdelete" id="fbsc_orderdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_order">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($bsc_order_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_bsc_orderdelete" class="ewTable ewTableSeparate">
<?php echo $bsc_order->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_bsc_order_id" class="bsc_order_id"><?php echo $bsc_order->id->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_dateorder" class="bsc_order_dateorder"><?php echo $bsc_order->dateorder->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_payer_email" class="bsc_order_payer_email"><?php echo $bsc_order->payer_email->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_payment_type" class="bsc_order_payment_type"><?php echo $bsc_order->payment_type->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_custom" class="bsc_order_custom"><?php echo $bsc_order->custom->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_invoice" class="bsc_order_invoice"><?php echo $bsc_order->invoice->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_item_name" class="bsc_order_item_name"><?php echo $bsc_order->item_name->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_item_number" class="bsc_order_item_number"><?php echo $bsc_order->item_number->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_quantity" class="bsc_order_quantity"><?php echo $bsc_order->quantity->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_payment_status" class="bsc_order_payment_status"><?php echo $bsc_order->payment_status->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_payment_amount" class="bsc_order_payment_amount"><?php echo $bsc_order->payment_amount->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_payment_currency" class="bsc_order_payment_currency"><?php echo $bsc_order->payment_currency->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_first_name" class="bsc_order_first_name"><?php echo $bsc_order->first_name->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_last_name" class="bsc_order_last_name"><?php echo $bsc_order->last_name->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_address_name" class="bsc_order_address_name"><?php echo $bsc_order->address_name->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_address_country" class="bsc_order_address_country"><?php echo $bsc_order->address_country->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_address_country_code" class="bsc_order_address_country_code"><?php echo $bsc_order->address_country_code->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_address_zip" class="bsc_order_address_zip"><?php echo $bsc_order->address_zip->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_address_state" class="bsc_order_address_state"><?php echo $bsc_order->address_state->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_address_city" class="bsc_order_address_city"><?php echo $bsc_order->address_city->FldCaption() ?></span></td>
		<td><span id="elh_bsc_order_address_street" class="bsc_order_address_street"><?php echo $bsc_order->address_street->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$bsc_order_delete->RecCnt = 0;
$i = 0;
while (!$bsc_order_delete->Recordset->EOF) {
	$bsc_order_delete->RecCnt++;
	$bsc_order_delete->RowCnt++;

	// Set row properties
	$bsc_order->ResetAttrs();
	$bsc_order->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$bsc_order_delete->LoadRowValues($bsc_order_delete->Recordset);

	// Render row
	$bsc_order_delete->RenderRow();
?>
	<tr<?php echo $bsc_order->RowAttributes() ?>>
		<td<?php echo $bsc_order->id->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_id" class="control-group bsc_order_id">
<span<?php echo $bsc_order->id->ViewAttributes() ?>>
<?php echo $bsc_order->id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->dateorder->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_dateorder" class="control-group bsc_order_dateorder">
<span<?php echo $bsc_order->dateorder->ViewAttributes() ?>>
<?php echo $bsc_order->dateorder->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->payer_email->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_payer_email" class="control-group bsc_order_payer_email">
<span<?php echo $bsc_order->payer_email->ViewAttributes() ?>>
<?php echo $bsc_order->payer_email->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->payment_type->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_payment_type" class="control-group bsc_order_payment_type">
<span<?php echo $bsc_order->payment_type->ViewAttributes() ?>>
<?php echo $bsc_order->payment_type->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->custom->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_custom" class="control-group bsc_order_custom">
<span<?php echo $bsc_order->custom->ViewAttributes() ?>>
<?php echo $bsc_order->custom->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->invoice->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_invoice" class="control-group bsc_order_invoice">
<span<?php echo $bsc_order->invoice->ViewAttributes() ?>>
<?php echo $bsc_order->invoice->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->item_name->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_item_name" class="control-group bsc_order_item_name">
<span<?php echo $bsc_order->item_name->ViewAttributes() ?>>
<?php echo $bsc_order->item_name->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->item_number->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_item_number" class="control-group bsc_order_item_number">
<span<?php echo $bsc_order->item_number->ViewAttributes() ?>>
<?php echo $bsc_order->item_number->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->quantity->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_quantity" class="control-group bsc_order_quantity">
<span<?php echo $bsc_order->quantity->ViewAttributes() ?>>
<?php echo $bsc_order->quantity->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->payment_status->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_payment_status" class="control-group bsc_order_payment_status">
<span<?php echo $bsc_order->payment_status->ViewAttributes() ?>>
<?php echo $bsc_order->payment_status->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->payment_amount->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_payment_amount" class="control-group bsc_order_payment_amount">
<span<?php echo $bsc_order->payment_amount->ViewAttributes() ?>>
<?php echo $bsc_order->payment_amount->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->payment_currency->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_payment_currency" class="control-group bsc_order_payment_currency">
<span<?php echo $bsc_order->payment_currency->ViewAttributes() ?>>
<?php echo $bsc_order->payment_currency->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->first_name->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_first_name" class="control-group bsc_order_first_name">
<span<?php echo $bsc_order->first_name->ViewAttributes() ?>>
<?php echo $bsc_order->first_name->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->last_name->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_last_name" class="control-group bsc_order_last_name">
<span<?php echo $bsc_order->last_name->ViewAttributes() ?>>
<?php echo $bsc_order->last_name->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->address_name->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_address_name" class="control-group bsc_order_address_name">
<span<?php echo $bsc_order->address_name->ViewAttributes() ?>>
<?php echo $bsc_order->address_name->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->address_country->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_address_country" class="control-group bsc_order_address_country">
<span<?php echo $bsc_order->address_country->ViewAttributes() ?>>
<?php echo $bsc_order->address_country->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->address_country_code->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_address_country_code" class="control-group bsc_order_address_country_code">
<span<?php echo $bsc_order->address_country_code->ViewAttributes() ?>>
<?php echo $bsc_order->address_country_code->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->address_zip->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_address_zip" class="control-group bsc_order_address_zip">
<span<?php echo $bsc_order->address_zip->ViewAttributes() ?>>
<?php echo $bsc_order->address_zip->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->address_state->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_address_state" class="control-group bsc_order_address_state">
<span<?php echo $bsc_order->address_state->ViewAttributes() ?>>
<?php echo $bsc_order->address_state->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->address_city->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_address_city" class="control-group bsc_order_address_city">
<span<?php echo $bsc_order->address_city->ViewAttributes() ?>>
<?php echo $bsc_order->address_city->ListViewValue() ?></span>
</span></td>
		<td<?php echo $bsc_order->address_street->CellAttributes() ?>><span id="el<?php echo $bsc_order_delete->RowCnt ?>_bsc_order_address_street" class="control-group bsc_order_address_street">
<span<?php echo $bsc_order->address_street->ViewAttributes() ?>>
<?php echo $bsc_order->address_street->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$bsc_order_delete->Recordset->MoveNext();
}
$bsc_order_delete->Recordset->Close();
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
fbsc_orderdelete.Init();
</script>
<?php
$bsc_order_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bsc_order_delete->Page_Terminate();
?>
