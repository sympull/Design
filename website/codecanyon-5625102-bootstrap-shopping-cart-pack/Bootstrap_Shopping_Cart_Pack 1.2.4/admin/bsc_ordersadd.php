<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "bsc_ordersinfo.php" ?>
<?php include_once "bsc_admininfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$bsc_orders_add = NULL; // Initialize page object first

class cbsc_orders_add extends cbsc_orders {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_orders';

	// Page object name
	var $PageObjName = 'bsc_orders_add';

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

		// Table object (bsc_orders)
		if (!isset($GLOBALS["bsc_orders"])) {
			$GLOBALS["bsc_orders"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_orders"];
		}

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_orders', TRUE);

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
					$this->Page_Terminate("bsc_orderslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "bsc_ordersview.php")
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
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->idCategory->CurrentValue = NULL;
		$this->idCategory->OldValue = $this->idCategory->CurrentValue;
		$this->img->CurrentValue = NULL;
		$this->img->OldValue = $this->img->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->type->CurrentValue = NULL;
		$this->type->OldValue = $this->type->CurrentValue;
		$this->price->CurrentValue = 0.00;
		$this->price_offer->CurrentValue = 0.00;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->idCategory->FldIsDetailKey) {
			$this->idCategory->setFormValue($objForm->GetValue("x_idCategory"));
		}
		if (!$this->img->FldIsDetailKey) {
			$this->img->setFormValue($objForm->GetValue("x_img"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->price->FldIsDetailKey) {
			$this->price->setFormValue($objForm->GetValue("x_price"));
		}
		if (!$this->price_offer->FldIsDetailKey) {
			$this->price_offer->setFormValue($objForm->GetValue("x_price_offer"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->idCategory->CurrentValue = $this->idCategory->FormValue;
		$this->img->CurrentValue = $this->img->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
		$this->price_offer->CurrentValue = $this->price_offer->FormValue;
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
		$this->idCategory->setDbValue($rs->fields('idCategory'));
		$this->img->setDbValue($rs->fields('img'));
		$this->name->setDbValue($rs->fields('name'));
		$this->description->setDbValue($rs->fields('description'));
		$this->type->setDbValue($rs->fields('type'));
		$this->price->setDbValue($rs->fields('price'));
		$this->price_offer->setDbValue($rs->fields('price_offer'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idCategory->DbValue = $row['idCategory'];
		$this->img->DbValue = $row['img'];
		$this->name->DbValue = $row['name'];
		$this->description->DbValue = $row['description'];
		$this->type->DbValue = $row['type'];
		$this->price->DbValue = $row['price'];
		$this->price_offer->DbValue = $row['price_offer'];
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

		if ($this->price->FormValue == $this->price->CurrentValue && is_numeric(ew_StrToFloat($this->price->CurrentValue)))
			$this->price->CurrentValue = ew_StrToFloat($this->price->CurrentValue);

		// Convert decimal values if posted back
		if ($this->price_offer->FormValue == $this->price_offer->CurrentValue && is_numeric(ew_StrToFloat($this->price_offer->CurrentValue)))
			$this->price_offer->CurrentValue = ew_StrToFloat($this->price_offer->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// idCategory
		// img
		// name
		// description
		// type
		// price
		// price_offer

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// idCategory
			$this->idCategory->ViewValue = $this->idCategory->CurrentValue;
			$this->idCategory->ViewCustomAttributes = "";

			// img
			$this->img->ViewValue = $this->img->CurrentValue;
			$this->img->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// description
			$this->description->ViewValue = $this->description->CurrentValue;
			$this->description->ViewCustomAttributes = "";

			// type
			if (strval($this->type->CurrentValue) <> "") {
				switch ($this->type->CurrentValue) {
					case $this->type->FldTagValue(1):
						$this->type->ViewValue = $this->type->FldTagCaption(1) <> "" ? $this->type->FldTagCaption(1) : $this->type->CurrentValue;
						break;
					default:
						$this->type->ViewValue = $this->type->CurrentValue;
				}
			} else {
				$this->type->ViewValue = NULL;
			}
			$this->type->ViewCustomAttributes = "";

			// price
			$this->price->ViewValue = $this->price->CurrentValue;
			$this->price->ViewCustomAttributes = "";

			// price_offer
			$this->price_offer->ViewValue = $this->price_offer->CurrentValue;
			$this->price_offer->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// idCategory
			$this->idCategory->LinkCustomAttributes = "";
			$this->idCategory->HrefValue = "";
			$this->idCategory->TooltipValue = "";

			// img
			$this->img->LinkCustomAttributes = "";
			$this->img->HrefValue = "";
			$this->img->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";
			$this->price->TooltipValue = "";

			// price_offer
			$this->price_offer->LinkCustomAttributes = "";
			$this->price_offer->HrefValue = "";
			$this->price_offer->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldCaption()));

			// idCategory
			$this->idCategory->EditCustomAttributes = "";
			$this->idCategory->EditValue = ew_HtmlEncode($this->idCategory->CurrentValue);
			$this->idCategory->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->idCategory->FldCaption()));

			// img
			$this->img->EditCustomAttributes = "";
			$this->img->EditValue = ew_HtmlEncode($this->img->CurrentValue);
			$this->img->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->img->FldCaption()));

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->name->FldCaption()));

			// description
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = $this->description->CurrentValue;
			$this->description->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->description->FldCaption()));

			// type
			$this->type->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->type->FldTagValue(1), $this->type->FldTagCaption(1) <> "" ? $this->type->FldTagCaption(1) : $this->type->FldTagValue(1));
			$this->type->EditValue = $arwrk;

			// price
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->CurrentValue);
			$this->price->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->price->FldCaption()));
			if (strval($this->price->EditValue) <> "" && is_numeric($this->price->EditValue)) $this->price->EditValue = ew_FormatNumber($this->price->EditValue, -2, -1, -2, 0);

			// price_offer
			$this->price_offer->EditCustomAttributes = "";
			$this->price_offer->EditValue = ew_HtmlEncode($this->price_offer->CurrentValue);
			$this->price_offer->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->price_offer->FldCaption()));
			if (strval($this->price_offer->EditValue) <> "" && is_numeric($this->price_offer->EditValue)) $this->price_offer->EditValue = ew_FormatNumber($this->price_offer->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// idCategory
			$this->idCategory->HrefValue = "";

			// img
			$this->img->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// description
			$this->description->HrefValue = "";

			// type
			$this->type->HrefValue = "";

			// price
			$this->price->HrefValue = "";

			// price_offer
			$this->price_offer->HrefValue = "";
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
		if (!$this->id->FldIsDetailKey && !is_null($this->id->FormValue) && $this->id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id->FldCaption());
		}
		if (!ew_CheckInteger($this->id->FormValue)) {
			ew_AddMessage($gsFormError, $this->id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->idCategory->FormValue)) {
			ew_AddMessage($gsFormError, $this->idCategory->FldErrMsg());
		}
		if (!ew_CheckNumber($this->price->FormValue)) {
			ew_AddMessage($gsFormError, $this->price->FldErrMsg());
		}
		if (!ew_CheckNumber($this->price_offer->FormValue)) {
			ew_AddMessage($gsFormError, $this->price_offer->FldErrMsg());
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

		// id
		$this->id->SetDbValueDef($rsnew, $this->id->CurrentValue, 0, FALSE);

		// idCategory
		$this->idCategory->SetDbValueDef($rsnew, $this->idCategory->CurrentValue, NULL, FALSE);

		// img
		$this->img->SetDbValueDef($rsnew, $this->img->CurrentValue, NULL, FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// type
		$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, NULL, FALSE);

		// price
		$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, NULL, strval($this->price->CurrentValue) == "");

		// price_offer
		$this->price_offer->SetDbValueDef($rsnew, $this->price_offer->CurrentValue, NULL, strval($this->price_offer->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->id->CurrentValue == "" && $this->id->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
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
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "bsc_orderslist.php", $this->TableVar);
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
if (!isset($bsc_orders_add)) $bsc_orders_add = new cbsc_orders_add();

// Page init
$bsc_orders_add->Page_Init();

// Page main
$bsc_orders_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_orders_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var bsc_orders_add = new ew_Page("bsc_orders_add");
bsc_orders_add.PageID = "add"; // Page ID
var EW_PAGE_ID = bsc_orders_add.PageID; // For backward compatibility

// Form object
var fbsc_ordersadd = new ew_Form("fbsc_ordersadd");

// Validate form
fbsc_ordersadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($bsc_orders->id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_orders->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_idCategory");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_orders->idCategory->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_orders->price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_price_offer");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_orders->price_offer->FldErrMsg()) ?>");

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
fbsc_ordersadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_ordersadd.ValidateRequired = true;
<?php } else { ?>
fbsc_ordersadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $bsc_orders_add->ShowPageHeader(); ?>
<?php
$bsc_orders_add->ShowMessage();
?>
<form name="fbsc_ordersadd" id="fbsc_ordersadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_orders">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_bsc_ordersadd" class="table table-bordered table-striped">
<?php if ($bsc_orders->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $bsc_orders->RowAttributes() ?>>
		<td><span id="elh_bsc_orders_id"><?php echo $bsc_orders->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $bsc_orders->id->CellAttributes() ?>><span id="el_bsc_orders_id" class="control-group">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $bsc_orders->id->PlaceHolder ?>" value="<?php echo $bsc_orders->id->EditValue ?>"<?php echo $bsc_orders->id->EditAttributes() ?>>
</span><?php echo $bsc_orders->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_orders->idCategory->Visible) { // idCategory ?>
	<tr id="r_idCategory"<?php echo $bsc_orders->RowAttributes() ?>>
		<td><span id="elh_bsc_orders_idCategory"><?php echo $bsc_orders->idCategory->FldCaption() ?></span></td>
		<td<?php echo $bsc_orders->idCategory->CellAttributes() ?>><span id="el_bsc_orders_idCategory" class="control-group">
<input type="text" data-field="x_idCategory" name="x_idCategory" id="x_idCategory" size="30" placeholder="<?php echo $bsc_orders->idCategory->PlaceHolder ?>" value="<?php echo $bsc_orders->idCategory->EditValue ?>"<?php echo $bsc_orders->idCategory->EditAttributes() ?>>
</span><?php echo $bsc_orders->idCategory->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_orders->img->Visible) { // img ?>
	<tr id="r_img"<?php echo $bsc_orders->RowAttributes() ?>>
		<td><span id="elh_bsc_orders_img"><?php echo $bsc_orders->img->FldCaption() ?></span></td>
		<td<?php echo $bsc_orders->img->CellAttributes() ?>><span id="el_bsc_orders_img" class="control-group">
<input type="text" data-field="x_img" name="x_img" id="x_img" size="30" maxlength="255" placeholder="<?php echo $bsc_orders->img->PlaceHolder ?>" value="<?php echo $bsc_orders->img->EditValue ?>"<?php echo $bsc_orders->img->EditAttributes() ?>>
</span><?php echo $bsc_orders->img->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_orders->name->Visible) { // name ?>
	<tr id="r_name"<?php echo $bsc_orders->RowAttributes() ?>>
		<td><span id="elh_bsc_orders_name"><?php echo $bsc_orders->name->FldCaption() ?></span></td>
		<td<?php echo $bsc_orders->name->CellAttributes() ?>><span id="el_bsc_orders_name" class="control-group">
<input type="text" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="255" placeholder="<?php echo $bsc_orders->name->PlaceHolder ?>" value="<?php echo $bsc_orders->name->EditValue ?>"<?php echo $bsc_orders->name->EditAttributes() ?>>
</span><?php echo $bsc_orders->name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_orders->description->Visible) { // description ?>
	<tr id="r_description"<?php echo $bsc_orders->RowAttributes() ?>>
		<td><span id="elh_bsc_orders_description"><?php echo $bsc_orders->description->FldCaption() ?></span></td>
		<td<?php echo $bsc_orders->description->CellAttributes() ?>><span id="el_bsc_orders_description" class="control-group">
<textarea data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo $bsc_orders->description->PlaceHolder ?>"<?php echo $bsc_orders->description->EditAttributes() ?>><?php echo $bsc_orders->description->EditValue ?></textarea>
</span><?php echo $bsc_orders->description->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_orders->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $bsc_orders->RowAttributes() ?>>
		<td><span id="elh_bsc_orders_type"><?php echo $bsc_orders->type->FldCaption() ?></span></td>
		<td<?php echo $bsc_orders->type->CellAttributes() ?>><span id="el_bsc_orders_type" class="control-group">
<div id="tp_x_type" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_type" id="x_type" value="{value}"<?php echo $bsc_orders->type->EditAttributes() ?>></div>
<div id="dsl_x_type" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $bsc_orders->type->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($bsc_orders->type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_type" name="x_type" id="x_type_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $bsc_orders->type->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span><?php echo $bsc_orders->type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_orders->price->Visible) { // price ?>
	<tr id="r_price"<?php echo $bsc_orders->RowAttributes() ?>>
		<td><span id="elh_bsc_orders_price"><?php echo $bsc_orders->price->FldCaption() ?></span></td>
		<td<?php echo $bsc_orders->price->CellAttributes() ?>><span id="el_bsc_orders_price" class="control-group">
<input type="text" data-field="x_price" name="x_price" id="x_price" size="30" placeholder="<?php echo $bsc_orders->price->PlaceHolder ?>" value="<?php echo $bsc_orders->price->EditValue ?>"<?php echo $bsc_orders->price->EditAttributes() ?>>
</span><?php echo $bsc_orders->price->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_orders->price_offer->Visible) { // price_offer ?>
	<tr id="r_price_offer"<?php echo $bsc_orders->RowAttributes() ?>>
		<td><span id="elh_bsc_orders_price_offer"><?php echo $bsc_orders->price_offer->FldCaption() ?></span></td>
		<td<?php echo $bsc_orders->price_offer->CellAttributes() ?>><span id="el_bsc_orders_price_offer" class="control-group">
<input type="text" data-field="x_price_offer" name="x_price_offer" id="x_price_offer" size="30" placeholder="<?php echo $bsc_orders->price_offer->PlaceHolder ?>" value="<?php echo $bsc_orders->price_offer->EditValue ?>"<?php echo $bsc_orders->price_offer->EditAttributes() ?>>
</span><?php echo $bsc_orders->price_offer->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fbsc_ordersadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$bsc_orders_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bsc_orders_add->Page_Terminate();
?>
