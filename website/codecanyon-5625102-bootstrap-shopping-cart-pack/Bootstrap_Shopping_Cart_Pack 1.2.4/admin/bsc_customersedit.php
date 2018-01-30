<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "bsc_customersinfo.php" ?>
<?php include_once "bsc_admininfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$bsc_customers_edit = NULL; // Initialize page object first

class cbsc_customers_edit extends cbsc_customers {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_customers';

	// Page object name
	var $PageObjName = 'bsc_customers_edit';

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

		// Table object (bsc_customers)
		if (!isset($GLOBALS["bsc_customers"])) {
			$GLOBALS["bsc_customers"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_customers"];
		}

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_customers', TRUE);

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("bsc_customerslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("bsc_customerslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->datelastorder->FldIsDetailKey) {
			$this->datelastorder->setFormValue($objForm->GetValue("x_datelastorder"));
			$this->datelastorder->CurrentValue = ew_UnFormatDateTime($this->datelastorder->CurrentValue, 5);
		}
		if (!$this->dateregister->FldIsDetailKey) {
			$this->dateregister->setFormValue($objForm->GetValue("x_dateregister"));
			$this->dateregister->CurrentValue = ew_UnFormatDateTime($this->dateregister->CurrentValue, 5);
		}
		if (!$this->payer_email->FldIsDetailKey) {
			$this->payer_email->setFormValue($objForm->GetValue("x_payer_email"));
		}
		if (!$this->first_name->FldIsDetailKey) {
			$this->first_name->setFormValue($objForm->GetValue("x_first_name"));
		}
		if (!$this->last_name->FldIsDetailKey) {
			$this->last_name->setFormValue($objForm->GetValue("x_last_name"));
		}
		if (!$this->address_name->FldIsDetailKey) {
			$this->address_name->setFormValue($objForm->GetValue("x_address_name"));
		}
		if (!$this->address_street->FldIsDetailKey) {
			$this->address_street->setFormValue($objForm->GetValue("x_address_street"));
		}
		if (!$this->address_city->FldIsDetailKey) {
			$this->address_city->setFormValue($objForm->GetValue("x_address_city"));
		}
		if (!$this->address_zip->FldIsDetailKey) {
			$this->address_zip->setFormValue($objForm->GetValue("x_address_zip"));
		}
		if (!$this->address_state->FldIsDetailKey) {
			$this->address_state->setFormValue($objForm->GetValue("x_address_state"));
		}
		if (!$this->address_country->FldIsDetailKey) {
			$this->address_country->setFormValue($objForm->GetValue("x_address_country"));
		}
		if (!$this->address_country_code->FldIsDetailKey) {
			$this->address_country_code->setFormValue($objForm->GetValue("x_address_country_code"));
		}
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->datelastorder->CurrentValue = $this->datelastorder->FormValue;
		$this->datelastorder->CurrentValue = ew_UnFormatDateTime($this->datelastorder->CurrentValue, 5);
		$this->dateregister->CurrentValue = $this->dateregister->FormValue;
		$this->dateregister->CurrentValue = ew_UnFormatDateTime($this->dateregister->CurrentValue, 5);
		$this->payer_email->CurrentValue = $this->payer_email->FormValue;
		$this->first_name->CurrentValue = $this->first_name->FormValue;
		$this->last_name->CurrentValue = $this->last_name->FormValue;
		$this->address_name->CurrentValue = $this->address_name->FormValue;
		$this->address_street->CurrentValue = $this->address_street->FormValue;
		$this->address_city->CurrentValue = $this->address_city->FormValue;
		$this->address_zip->CurrentValue = $this->address_zip->FormValue;
		$this->address_state->CurrentValue = $this->address_state->FormValue;
		$this->address_country->CurrentValue = $this->address_country->FormValue;
		$this->address_country_code->CurrentValue = $this->address_country_code->FormValue;
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
		$this->datelastorder->setDbValue($rs->fields('datelastorder'));
		$this->dateregister->setDbValue($rs->fields('dateregister'));
		$this->payer_email->setDbValue($rs->fields('payer_email'));
		$this->first_name->setDbValue($rs->fields('first_name'));
		$this->last_name->setDbValue($rs->fields('last_name'));
		$this->address_name->setDbValue($rs->fields('address_name'));
		$this->address_street->setDbValue($rs->fields('address_street'));
		$this->address_city->setDbValue($rs->fields('address_city'));
		$this->address_zip->setDbValue($rs->fields('address_zip'));
		$this->address_state->setDbValue($rs->fields('address_state'));
		$this->address_country->setDbValue($rs->fields('address_country'));
		$this->address_country_code->setDbValue($rs->fields('address_country_code'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->datelastorder->DbValue = $row['datelastorder'];
		$this->dateregister->DbValue = $row['dateregister'];
		$this->payer_email->DbValue = $row['payer_email'];
		$this->first_name->DbValue = $row['first_name'];
		$this->last_name->DbValue = $row['last_name'];
		$this->address_name->DbValue = $row['address_name'];
		$this->address_street->DbValue = $row['address_street'];
		$this->address_city->DbValue = $row['address_city'];
		$this->address_zip->DbValue = $row['address_zip'];
		$this->address_state->DbValue = $row['address_state'];
		$this->address_country->DbValue = $row['address_country'];
		$this->address_country_code->DbValue = $row['address_country_code'];
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
		// datelastorder
		// dateregister
		// payer_email
		// first_name
		// last_name
		// address_name
		// address_street
		// address_city
		// address_zip
		// address_state
		// address_country
		// address_country_code

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// datelastorder
			$this->datelastorder->ViewValue = $this->datelastorder->CurrentValue;
			$this->datelastorder->ViewValue = ew_FormatDateTime($this->datelastorder->ViewValue, 5);
			$this->datelastorder->ViewCustomAttributes = "";

			// dateregister
			$this->dateregister->ViewValue = $this->dateregister->CurrentValue;
			$this->dateregister->ViewValue = ew_FormatDateTime($this->dateregister->ViewValue, 5);
			$this->dateregister->ViewCustomAttributes = "";

			// payer_email
			$this->payer_email->ViewValue = $this->payer_email->CurrentValue;
			$this->payer_email->ViewCustomAttributes = "";

			// first_name
			$this->first_name->ViewValue = $this->first_name->CurrentValue;
			$this->first_name->ViewCustomAttributes = "";

			// last_name
			$this->last_name->ViewValue = $this->last_name->CurrentValue;
			$this->last_name->ViewCustomAttributes = "";

			// address_name
			$this->address_name->ViewValue = $this->address_name->CurrentValue;
			$this->address_name->ViewCustomAttributes = "";

			// address_street
			$this->address_street->ViewValue = $this->address_street->CurrentValue;
			$this->address_street->ViewCustomAttributes = "";

			// address_city
			$this->address_city->ViewValue = $this->address_city->CurrentValue;
			$this->address_city->ViewCustomAttributes = "";

			// address_zip
			$this->address_zip->ViewValue = $this->address_zip->CurrentValue;
			$this->address_zip->ViewCustomAttributes = "";

			// address_state
			$this->address_state->ViewValue = $this->address_state->CurrentValue;
			$this->address_state->ViewCustomAttributes = "";

			// address_country
			$this->address_country->ViewValue = $this->address_country->CurrentValue;
			$this->address_country->ViewCustomAttributes = "";

			// address_country_code
			$this->address_country_code->ViewValue = $this->address_country_code->CurrentValue;
			$this->address_country_code->ViewCustomAttributes = "";

			// datelastorder
			$this->datelastorder->LinkCustomAttributes = "";
			$this->datelastorder->HrefValue = "";
			$this->datelastorder->TooltipValue = "";

			// dateregister
			$this->dateregister->LinkCustomAttributes = "";
			$this->dateregister->HrefValue = "";
			$this->dateregister->TooltipValue = "";

			// payer_email
			$this->payer_email->LinkCustomAttributes = "";
			$this->payer_email->HrefValue = "";
			$this->payer_email->TooltipValue = "";

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

			// address_street
			$this->address_street->LinkCustomAttributes = "";
			$this->address_street->HrefValue = "";
			$this->address_street->TooltipValue = "";

			// address_city
			$this->address_city->LinkCustomAttributes = "";
			$this->address_city->HrefValue = "";
			$this->address_city->TooltipValue = "";

			// address_zip
			$this->address_zip->LinkCustomAttributes = "";
			$this->address_zip->HrefValue = "";
			$this->address_zip->TooltipValue = "";

			// address_state
			$this->address_state->LinkCustomAttributes = "";
			$this->address_state->HrefValue = "";
			$this->address_state->TooltipValue = "";

			// address_country
			$this->address_country->LinkCustomAttributes = "";
			$this->address_country->HrefValue = "";
			$this->address_country->TooltipValue = "";

			// address_country_code
			$this->address_country_code->LinkCustomAttributes = "";
			$this->address_country_code->HrefValue = "";
			$this->address_country_code->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// datelastorder
			$this->datelastorder->EditCustomAttributes = "";
			$this->datelastorder->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->datelastorder->CurrentValue, 5));
			$this->datelastorder->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->datelastorder->FldCaption()));

			// dateregister
			$this->dateregister->EditCustomAttributes = "";
			$this->dateregister->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dateregister->CurrentValue, 5));
			$this->dateregister->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dateregister->FldCaption()));

			// payer_email
			$this->payer_email->EditCustomAttributes = "";
			$this->payer_email->EditValue = ew_HtmlEncode($this->payer_email->CurrentValue);
			$this->payer_email->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->payer_email->FldCaption()));

			// first_name
			$this->first_name->EditCustomAttributes = "";
			$this->first_name->EditValue = ew_HtmlEncode($this->first_name->CurrentValue);
			$this->first_name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->first_name->FldCaption()));

			// last_name
			$this->last_name->EditCustomAttributes = "";
			$this->last_name->EditValue = ew_HtmlEncode($this->last_name->CurrentValue);
			$this->last_name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->last_name->FldCaption()));

			// address_name
			$this->address_name->EditCustomAttributes = "";
			$this->address_name->EditValue = ew_HtmlEncode($this->address_name->CurrentValue);
			$this->address_name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->address_name->FldCaption()));

			// address_street
			$this->address_street->EditCustomAttributes = "";
			$this->address_street->EditValue = ew_HtmlEncode($this->address_street->CurrentValue);
			$this->address_street->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->address_street->FldCaption()));

			// address_city
			$this->address_city->EditCustomAttributes = "";
			$this->address_city->EditValue = ew_HtmlEncode($this->address_city->CurrentValue);
			$this->address_city->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->address_city->FldCaption()));

			// address_zip
			$this->address_zip->EditCustomAttributes = "";
			$this->address_zip->EditValue = ew_HtmlEncode($this->address_zip->CurrentValue);
			$this->address_zip->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->address_zip->FldCaption()));

			// address_state
			$this->address_state->EditCustomAttributes = "";
			$this->address_state->EditValue = ew_HtmlEncode($this->address_state->CurrentValue);
			$this->address_state->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->address_state->FldCaption()));

			// address_country
			$this->address_country->EditCustomAttributes = "";
			$this->address_country->EditValue = ew_HtmlEncode($this->address_country->CurrentValue);
			$this->address_country->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->address_country->FldCaption()));

			// address_country_code
			$this->address_country_code->EditCustomAttributes = "";
			$this->address_country_code->EditValue = ew_HtmlEncode($this->address_country_code->CurrentValue);
			$this->address_country_code->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->address_country_code->FldCaption()));

			// Edit refer script
			// datelastorder

			$this->datelastorder->HrefValue = "";

			// dateregister
			$this->dateregister->HrefValue = "";

			// payer_email
			$this->payer_email->HrefValue = "";

			// first_name
			$this->first_name->HrefValue = "";

			// last_name
			$this->last_name->HrefValue = "";

			// address_name
			$this->address_name->HrefValue = "";

			// address_street
			$this->address_street->HrefValue = "";

			// address_city
			$this->address_city->HrefValue = "";

			// address_zip
			$this->address_zip->HrefValue = "";

			// address_state
			$this->address_state->HrefValue = "";

			// address_country
			$this->address_country->HrefValue = "";

			// address_country_code
			$this->address_country_code->HrefValue = "";
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
		if (!ew_CheckDate($this->datelastorder->FormValue)) {
			ew_AddMessage($gsFormError, $this->datelastorder->FldErrMsg());
		}
		if (!ew_CheckDate($this->dateregister->FormValue)) {
			ew_AddMessage($gsFormError, $this->dateregister->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// datelastorder
			$this->datelastorder->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->datelastorder->CurrentValue, 5), NULL, $this->datelastorder->ReadOnly);

			// dateregister
			$this->dateregister->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dateregister->CurrentValue, 5), NULL, $this->dateregister->ReadOnly);

			// payer_email
			$this->payer_email->SetDbValueDef($rsnew, $this->payer_email->CurrentValue, NULL, $this->payer_email->ReadOnly);

			// first_name
			$this->first_name->SetDbValueDef($rsnew, $this->first_name->CurrentValue, NULL, $this->first_name->ReadOnly);

			// last_name
			$this->last_name->SetDbValueDef($rsnew, $this->last_name->CurrentValue, NULL, $this->last_name->ReadOnly);

			// address_name
			$this->address_name->SetDbValueDef($rsnew, $this->address_name->CurrentValue, NULL, $this->address_name->ReadOnly);

			// address_street
			$this->address_street->SetDbValueDef($rsnew, $this->address_street->CurrentValue, NULL, $this->address_street->ReadOnly);

			// address_city
			$this->address_city->SetDbValueDef($rsnew, $this->address_city->CurrentValue, NULL, $this->address_city->ReadOnly);

			// address_zip
			$this->address_zip->SetDbValueDef($rsnew, $this->address_zip->CurrentValue, NULL, $this->address_zip->ReadOnly);

			// address_state
			$this->address_state->SetDbValueDef($rsnew, $this->address_state->CurrentValue, NULL, $this->address_state->ReadOnly);

			// address_country
			$this->address_country->SetDbValueDef($rsnew, $this->address_country->CurrentValue, NULL, $this->address_country->ReadOnly);

			// address_country_code
			$this->address_country_code->SetDbValueDef($rsnew, $this->address_country_code->CurrentValue, NULL, $this->address_country_code->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "bsc_customerslist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($bsc_customers_edit)) $bsc_customers_edit = new cbsc_customers_edit();

// Page init
$bsc_customers_edit->Page_Init();

// Page main
$bsc_customers_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_customers_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var bsc_customers_edit = new ew_Page("bsc_customers_edit");
bsc_customers_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = bsc_customers_edit.PageID; // For backward compatibility

// Form object
var fbsc_customersedit = new ew_Form("fbsc_customersedit");

// Validate form
fbsc_customersedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_datelastorder");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_customers->datelastorder->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dateregister");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_customers->dateregister->FldErrMsg()) ?>");

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
fbsc_customersedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_customersedit.ValidateRequired = true;
<?php } else { ?>
fbsc_customersedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $bsc_customers_edit->ShowPageHeader(); ?>
<?php
$bsc_customers_edit->ShowMessage();
?>
<form name="fbsc_customersedit" id="fbsc_customersedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_customers">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_bsc_customersedit" class="table table-bordered table-striped">
<?php if ($bsc_customers->datelastorder->Visible) { // datelastorder ?>
	<tr id="r_datelastorder"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_datelastorder"><?php echo $bsc_customers->datelastorder->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->datelastorder->CellAttributes() ?>><span id="el_bsc_customers_datelastorder" class="control-group">
<input type="text" data-field="x_datelastorder" name="x_datelastorder" id="x_datelastorder" placeholder="<?php echo $bsc_customers->datelastorder->PlaceHolder ?>" value="<?php echo $bsc_customers->datelastorder->EditValue ?>"<?php echo $bsc_customers->datelastorder->EditAttributes() ?>>
</span><?php echo $bsc_customers->datelastorder->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->dateregister->Visible) { // dateregister ?>
	<tr id="r_dateregister"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_dateregister"><?php echo $bsc_customers->dateregister->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->dateregister->CellAttributes() ?>><span id="el_bsc_customers_dateregister" class="control-group">
<input type="text" data-field="x_dateregister" name="x_dateregister" id="x_dateregister" placeholder="<?php echo $bsc_customers->dateregister->PlaceHolder ?>" value="<?php echo $bsc_customers->dateregister->EditValue ?>"<?php echo $bsc_customers->dateregister->EditAttributes() ?>>
</span><?php echo $bsc_customers->dateregister->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->payer_email->Visible) { // payer_email ?>
	<tr id="r_payer_email"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_payer_email"><?php echo $bsc_customers->payer_email->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->payer_email->CellAttributes() ?>><span id="el_bsc_customers_payer_email" class="control-group">
<input type="text" data-field="x_payer_email" name="x_payer_email" id="x_payer_email" size="50" maxlength="255" placeholder="<?php echo $bsc_customers->payer_email->PlaceHolder ?>" value="<?php echo $bsc_customers->payer_email->EditValue ?>"<?php echo $bsc_customers->payer_email->EditAttributes() ?>>
</span><?php echo $bsc_customers->payer_email->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->first_name->Visible) { // first_name ?>
	<tr id="r_first_name"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_first_name"><?php echo $bsc_customers->first_name->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->first_name->CellAttributes() ?>><span id="el_bsc_customers_first_name" class="control-group">
<input type="text" data-field="x_first_name" name="x_first_name" id="x_first_name" size="100" maxlength="255" placeholder="<?php echo $bsc_customers->first_name->PlaceHolder ?>" value="<?php echo $bsc_customers->first_name->EditValue ?>"<?php echo $bsc_customers->first_name->EditAttributes() ?>>
</span><?php echo $bsc_customers->first_name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->last_name->Visible) { // last_name ?>
	<tr id="r_last_name"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_last_name"><?php echo $bsc_customers->last_name->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->last_name->CellAttributes() ?>><span id="el_bsc_customers_last_name" class="control-group">
<input type="text" data-field="x_last_name" name="x_last_name" id="x_last_name" size="100" maxlength="255" placeholder="<?php echo $bsc_customers->last_name->PlaceHolder ?>" value="<?php echo $bsc_customers->last_name->EditValue ?>"<?php echo $bsc_customers->last_name->EditAttributes() ?>>
</span><?php echo $bsc_customers->last_name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_name->Visible) { // address_name ?>
	<tr id="r_address_name"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_name"><?php echo $bsc_customers->address_name->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_name->CellAttributes() ?>><span id="el_bsc_customers_address_name" class="control-group">
<input type="text" data-field="x_address_name" name="x_address_name" id="x_address_name" size="200" maxlength="255" placeholder="<?php echo $bsc_customers->address_name->PlaceHolder ?>" value="<?php echo $bsc_customers->address_name->EditValue ?>"<?php echo $bsc_customers->address_name->EditAttributes() ?>>
</span><?php echo $bsc_customers->address_name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_street->Visible) { // address_street ?>
	<tr id="r_address_street"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_street"><?php echo $bsc_customers->address_street->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_street->CellAttributes() ?>><span id="el_bsc_customers_address_street" class="control-group">
<input type="text" data-field="x_address_street" name="x_address_street" id="x_address_street" size="200" maxlength="255" placeholder="<?php echo $bsc_customers->address_street->PlaceHolder ?>" value="<?php echo $bsc_customers->address_street->EditValue ?>"<?php echo $bsc_customers->address_street->EditAttributes() ?>>
</span><?php echo $bsc_customers->address_street->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_city->Visible) { // address_city ?>
	<tr id="r_address_city"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_city"><?php echo $bsc_customers->address_city->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_city->CellAttributes() ?>><span id="el_bsc_customers_address_city" class="control-group">
<input type="text" data-field="x_address_city" name="x_address_city" id="x_address_city" size="30" maxlength="255" placeholder="<?php echo $bsc_customers->address_city->PlaceHolder ?>" value="<?php echo $bsc_customers->address_city->EditValue ?>"<?php echo $bsc_customers->address_city->EditAttributes() ?>>
</span><?php echo $bsc_customers->address_city->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_zip->Visible) { // address_zip ?>
	<tr id="r_address_zip"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_zip"><?php echo $bsc_customers->address_zip->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_zip->CellAttributes() ?>><span id="el_bsc_customers_address_zip" class="control-group">
<input type="text" data-field="x_address_zip" name="x_address_zip" id="x_address_zip" size="30" maxlength="255" placeholder="<?php echo $bsc_customers->address_zip->PlaceHolder ?>" value="<?php echo $bsc_customers->address_zip->EditValue ?>"<?php echo $bsc_customers->address_zip->EditAttributes() ?>>
</span><?php echo $bsc_customers->address_zip->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_state->Visible) { // address_state ?>
	<tr id="r_address_state"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_state"><?php echo $bsc_customers->address_state->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_state->CellAttributes() ?>><span id="el_bsc_customers_address_state" class="control-group">
<input type="text" data-field="x_address_state" name="x_address_state" id="x_address_state" size="30" maxlength="255" placeholder="<?php echo $bsc_customers->address_state->PlaceHolder ?>" value="<?php echo $bsc_customers->address_state->EditValue ?>"<?php echo $bsc_customers->address_state->EditAttributes() ?>>
</span><?php echo $bsc_customers->address_state->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_country->Visible) { // address_country ?>
	<tr id="r_address_country"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_country"><?php echo $bsc_customers->address_country->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_country->CellAttributes() ?>><span id="el_bsc_customers_address_country" class="control-group">
<input type="text" data-field="x_address_country" name="x_address_country" id="x_address_country" size="100" maxlength="255" placeholder="<?php echo $bsc_customers->address_country->PlaceHolder ?>" value="<?php echo $bsc_customers->address_country->EditValue ?>"<?php echo $bsc_customers->address_country->EditAttributes() ?>>
</span><?php echo $bsc_customers->address_country->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_country_code->Visible) { // address_country_code ?>
	<tr id="r_address_country_code"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_country_code"><?php echo $bsc_customers->address_country_code->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_country_code->CellAttributes() ?>><span id="el_bsc_customers_address_country_code" class="control-group">
<input type="text" data-field="x_address_country_code" name="x_address_country_code" id="x_address_country_code" size="30" maxlength="255" placeholder="<?php echo $bsc_customers->address_country_code->PlaceHolder ?>" value="<?php echo $bsc_customers->address_country_code->EditValue ?>"<?php echo $bsc_customers->address_country_code->EditAttributes() ?>>
</span><?php echo $bsc_customers->address_country_code->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<input type="hidden" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($bsc_customers->id->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fbsc_customersedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$bsc_customers_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bsc_customers_edit->Page_Terminate();
?>
