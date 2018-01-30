<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "bsc_order_detailinfo.php" ?>
<?php include_once "bsc_admininfo.php" ?>
<?php include_once "bsc_order_headerinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$bsc_order_detail_add = NULL; // Initialize page object first

class cbsc_order_detail_add extends cbsc_order_detail {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_order_detail';

	// Page object name
	var $PageObjName = 'bsc_order_detail_add';

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

		// Table object (bsc_order_detail)
		if (!isset($GLOBALS["bsc_order_detail"])) {
			$GLOBALS["bsc_order_detail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_order_detail"];
		}

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Table object (bsc_order_header)
		if (!isset($GLOBALS['bsc_order_header'])) $GLOBALS['bsc_order_header'] = new cbsc_order_header();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_order_detail', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("bsc_order_detaillist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "bsc_order_detailview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->item_number->CurrentValue = NULL;
		$this->item_number->OldValue = $this->item_number->CurrentValue;
		$this->item_name->CurrentValue = NULL;
		$this->item_name->OldValue = $this->item_name->CurrentValue;
		$this->quantity->CurrentValue = NULL;
		$this->quantity->OldValue = $this->quantity->CurrentValue;
		$this->mc_gross->CurrentValue = NULL;
		$this->mc_gross->OldValue = $this->mc_gross->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->item_number->FldIsDetailKey) {
			$this->item_number->setFormValue($objForm->GetValue("x_item_number"));
		}
		if (!$this->item_name->FldIsDetailKey) {
			$this->item_name->setFormValue($objForm->GetValue("x_item_name"));
		}
		if (!$this->quantity->FldIsDetailKey) {
			$this->quantity->setFormValue($objForm->GetValue("x_quantity"));
		}
		if (!$this->mc_gross->FldIsDetailKey) {
			$this->mc_gross->setFormValue($objForm->GetValue("x_mc_gross"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->item_number->CurrentValue = $this->item_number->FormValue;
		$this->item_name->CurrentValue = $this->item_name->FormValue;
		$this->quantity->CurrentValue = $this->quantity->FormValue;
		$this->mc_gross->CurrentValue = $this->mc_gross->FormValue;
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
		$this->item_number->setDbValue($rs->fields('item_number'));
		$this->item_name->setDbValue($rs->fields('item_name'));
		$this->quantity->setDbValue($rs->fields('quantity'));
		$this->mc_gross->setDbValue($rs->fields('mc_gross'));
		$this->payment_status->setDbValue($rs->fields('payment_status'));
		$this->payment_amount->setDbValue($rs->fields('payment_amount'));
		$this->payment_currency->setDbValue($rs->fields('payment_currency'));
		$this->payer_email->setDbValue($rs->fields('payer_email'));
		$this->payment_type->setDbValue($rs->fields('payment_type'));
		$this->custom->setDbValue($rs->fields('custom'));
		$this->invoice->setDbValue($rs->fields('invoice'));
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
		$this->item_number->DbValue = $row['item_number'];
		$this->item_name->DbValue = $row['item_name'];
		$this->quantity->DbValue = $row['quantity'];
		$this->mc_gross->DbValue = $row['mc_gross'];
		$this->payment_status->DbValue = $row['payment_status'];
		$this->payment_amount->DbValue = $row['payment_amount'];
		$this->payment_currency->DbValue = $row['payment_currency'];
		$this->payer_email->DbValue = $row['payer_email'];
		$this->payment_type->DbValue = $row['payment_type'];
		$this->custom->DbValue = $row['custom'];
		$this->invoice->DbValue = $row['invoice'];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->mc_gross->FormValue == $this->mc_gross->CurrentValue && is_numeric(ew_StrToFloat($this->mc_gross->CurrentValue)))
			$this->mc_gross->CurrentValue = ew_StrToFloat($this->mc_gross->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// dateorder
		// item_number
		// item_name
		// quantity
		// mc_gross
		// payment_status
		// payment_amount
		// payment_currency
		// payer_email
		// payment_type
		// custom
		// invoice
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

			// item_number
			$this->item_number->ViewValue = $this->item_number->CurrentValue;
			$this->item_number->ViewCustomAttributes = "";

			// item_name
			$this->item_name->ViewValue = $this->item_name->CurrentValue;
			$this->item_name->ViewCustomAttributes = "";

			// quantity
			$this->quantity->ViewValue = $this->quantity->CurrentValue;
			$this->quantity->ViewCustomAttributes = "";

			// mc_gross
			$this->mc_gross->ViewValue = $this->mc_gross->CurrentValue;
			$this->mc_gross->ViewCustomAttributes = "";

			// item_number
			$this->item_number->LinkCustomAttributes = "";
			$this->item_number->HrefValue = "";
			$this->item_number->TooltipValue = "";

			// item_name
			$this->item_name->LinkCustomAttributes = "";
			$this->item_name->HrefValue = "";
			$this->item_name->TooltipValue = "";

			// quantity
			$this->quantity->LinkCustomAttributes = "";
			$this->quantity->HrefValue = "";
			$this->quantity->TooltipValue = "";

			// mc_gross
			$this->mc_gross->LinkCustomAttributes = "";
			$this->mc_gross->HrefValue = "";
			$this->mc_gross->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// item_number
			$this->item_number->EditCustomAttributes = "";
			$this->item_number->EditValue = ew_HtmlEncode($this->item_number->CurrentValue);
			$this->item_number->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->item_number->FldCaption()));

			// item_name
			$this->item_name->EditCustomAttributes = "";
			$this->item_name->EditValue = ew_HtmlEncode($this->item_name->CurrentValue);
			$this->item_name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->item_name->FldCaption()));

			// quantity
			$this->quantity->EditCustomAttributes = "";
			$this->quantity->EditValue = ew_HtmlEncode($this->quantity->CurrentValue);
			$this->quantity->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->quantity->FldCaption()));

			// mc_gross
			$this->mc_gross->EditCustomAttributes = "";
			$this->mc_gross->EditValue = ew_HtmlEncode($this->mc_gross->CurrentValue);
			$this->mc_gross->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->mc_gross->FldCaption()));
			if (strval($this->mc_gross->EditValue) <> "" && is_numeric($this->mc_gross->EditValue)) $this->mc_gross->EditValue = ew_FormatNumber($this->mc_gross->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// item_number

			$this->item_number->HrefValue = "";

			// item_name
			$this->item_name->HrefValue = "";

			// quantity
			$this->quantity->HrefValue = "";

			// mc_gross
			$this->mc_gross->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->quantity->FormValue)) {
			ew_AddMessage($gsFormError, $this->quantity->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mc_gross->FormValue)) {
			ew_AddMessage($gsFormError, $this->mc_gross->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// item_number
		$this->item_number->SetDbValueDef($rsnew, $this->item_number->CurrentValue, NULL, FALSE);

		// item_name
		$this->item_name->SetDbValueDef($rsnew, $this->item_name->CurrentValue, NULL, FALSE);

		// quantity
		$this->quantity->SetDbValueDef($rsnew, $this->quantity->CurrentValue, NULL, FALSE);

		// mc_gross
		$this->mc_gross->SetDbValueDef($rsnew, $this->mc_gross->CurrentValue, NULL, FALSE);

		// invoice
		if ($this->invoice->getSessionValue() <> "") {
			$rsnew['invoice'] = $this->invoice->getSessionValue();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "bsc_order_header") {
				$bValidMaster = TRUE;
				if (@$_GET["invoice"] <> "") {
					$GLOBALS["bsc_order_header"]->invoice->setQueryStringValue($_GET["invoice"]);
					$this->invoice->setQueryStringValue($GLOBALS["bsc_order_header"]->invoice->QueryStringValue);
					$this->invoice->setSessionValue($this->invoice->QueryStringValue);
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "bsc_order_header") {
				if ($this->invoice->QueryStringValue == "") $this->invoice->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "bsc_order_detaillist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($bsc_order_detail_add)) $bsc_order_detail_add = new cbsc_order_detail_add();

// Page init
$bsc_order_detail_add->Page_Init();

// Page main
$bsc_order_detail_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_order_detail_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var bsc_order_detail_add = new ew_Page("bsc_order_detail_add");
bsc_order_detail_add.PageID = "add"; // Page ID
var EW_PAGE_ID = bsc_order_detail_add.PageID; // For backward compatibility

// Form object
var fbsc_order_detailadd = new ew_Form("fbsc_order_detailadd");

// Validate form
fbsc_order_detailadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_quantity");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_order_detail->quantity->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mc_gross");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_order_detail->mc_gross->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fbsc_order_detailadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_order_detailadd.ValidateRequired = true;
<?php } else { ?>
fbsc_order_detailadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $bsc_order_detail_add->ShowPageHeader(); ?>
<?php
$bsc_order_detail_add->ShowMessage();
?>
<form name="fbsc_order_detailadd" id="fbsc_order_detailadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_order_detail">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_bsc_order_detailadd" class="table table-bordered table-striped">
<?php if ($bsc_order_detail->item_number->Visible) { // item_number ?>
	<tr id="r_item_number"<?php echo $bsc_order_detail->RowAttributes() ?>>
		<td><span id="elh_bsc_order_detail_item_number"><?php echo $bsc_order_detail->item_number->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_detail->item_number->CellAttributes() ?>><span id="el_bsc_order_detail_item_number" class="control-group">
<input type="text" data-field="x_item_number" name="x_item_number" id="x_item_number" size="30" maxlength="255" placeholder="<?php echo $bsc_order_detail->item_number->PlaceHolder ?>" value="<?php echo $bsc_order_detail->item_number->EditValue ?>"<?php echo $bsc_order_detail->item_number->EditAttributes() ?>>
</span><?php echo $bsc_order_detail->item_number->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_detail->item_name->Visible) { // item_name ?>
	<tr id="r_item_name"<?php echo $bsc_order_detail->RowAttributes() ?>>
		<td><span id="elh_bsc_order_detail_item_name"><?php echo $bsc_order_detail->item_name->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_detail->item_name->CellAttributes() ?>><span id="el_bsc_order_detail_item_name" class="control-group">
<input type="text" data-field="x_item_name" name="x_item_name" id="x_item_name" size="30" maxlength="255" placeholder="<?php echo $bsc_order_detail->item_name->PlaceHolder ?>" value="<?php echo $bsc_order_detail->item_name->EditValue ?>"<?php echo $bsc_order_detail->item_name->EditAttributes() ?>>
</span><?php echo $bsc_order_detail->item_name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_detail->quantity->Visible) { // quantity ?>
	<tr id="r_quantity"<?php echo $bsc_order_detail->RowAttributes() ?>>
		<td><span id="elh_bsc_order_detail_quantity"><?php echo $bsc_order_detail->quantity->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_detail->quantity->CellAttributes() ?>><span id="el_bsc_order_detail_quantity" class="control-group">
<input type="text" data-field="x_quantity" name="x_quantity" id="x_quantity" size="30" placeholder="<?php echo $bsc_order_detail->quantity->PlaceHolder ?>" value="<?php echo $bsc_order_detail->quantity->EditValue ?>"<?php echo $bsc_order_detail->quantity->EditAttributes() ?>>
</span><?php echo $bsc_order_detail->quantity->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_detail->mc_gross->Visible) { // mc_gross ?>
	<tr id="r_mc_gross"<?php echo $bsc_order_detail->RowAttributes() ?>>
		<td><span id="elh_bsc_order_detail_mc_gross"><?php echo $bsc_order_detail->mc_gross->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_detail->mc_gross->CellAttributes() ?>><span id="el_bsc_order_detail_mc_gross" class="control-group">
<input type="text" data-field="x_mc_gross" name="x_mc_gross" id="x_mc_gross" size="30" placeholder="<?php echo $bsc_order_detail->mc_gross->PlaceHolder ?>" value="<?php echo $bsc_order_detail->mc_gross->EditValue ?>"<?php echo $bsc_order_detail->mc_gross->EditAttributes() ?>>
</span><?php echo $bsc_order_detail->mc_gross->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if (strval($bsc_order_detail->invoice->getSessionValue()) <> "") { ?>
<input type="hidden" name="x_invoice" id="x_invoice" value="<?php echo ew_HtmlEncode(strval($bsc_order_detail->invoice->getSessionValue())) ?>">
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fbsc_order_detailadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$bsc_order_detail_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bsc_order_detail_add->Page_Terminate();
?>
