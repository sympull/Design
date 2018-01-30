<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "bsc_order_headerinfo.php" ?>
<?php include_once "bsc_admininfo.php" ?>
<?php include_once "bsc_order_detailgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$bsc_order_header_view = NULL; // Initialize page object first

class cbsc_order_header_view extends cbsc_order_header {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_order_header';

	// Page object name
	var $PageObjName = 'bsc_order_header_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (bsc_order_header)
		if (!isset($GLOBALS["bsc_order_header"])) {
			$GLOBALS["bsc_order_header"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_order_header"];
		}
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_order_header', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} else {
				$sReturnUrl = "bsc_order_headerlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "bsc_order_headerlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "bsc_order_headerlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];
		$DetailTableLink = "";
		$option = &$options["detail"];

		// Detail table 'bsc_order_detail'
		$body = $Language->TablePhrase("bsc_order_detail", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("bsc_order_detaillist.php?" . EW_TABLE_SHOW_MASTER . "=bsc_order_header&invoice=" . strval($this->invoice->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_bsc_order_detail");
		$item->Body = $body;
		$item->Visible = $Security->IsLoggedIn();
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "bsc_order_detail";
		}

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<a class=\"ewAction ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink)) . "\">" . $body . "</a>";
			$item = &$option->Add("details");
			$item->Body = $body;
			$item->Visible = ($DetailTableLink <> "");

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detail_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
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
		$this->invoice->setDbValue($rs->fields('invoice'));
		$this->payer_email->setDbValue($rs->fields('payer_email'));
		$this->first_name->setDbValue($rs->fields('first_name'));
		$this->last_name->setDbValue($rs->fields('last_name'));
		$this->address_name->setDbValue($rs->fields('address_name'));
		$this->address_country->setDbValue($rs->fields('address_country'));
		$this->address_country_code->setDbValue($rs->fields('address_country_code'));
		$this->address_zip->setDbValue($rs->fields('address_zip'));
		$this->address_state->setDbValue($rs->fields('address_state'));
		$this->address_city->setDbValue($rs->fields('address_city'));
		$this->address_street->setDbValue($rs->fields('address_street'));
		$this->payment_type->setDbValue($rs->fields('payment_type'));
		$this->payment_status->setDbValue($rs->fields('payment_status'));
		$this->payment_currency->setDbValue($rs->fields('payment_currency'));
		$this->payment_amount->setDbValue($rs->fields('payment_amount'));
		$this->custom->setDbValue($rs->fields('custom'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->dateorder->DbValue = $row['dateorder'];
		$this->invoice->DbValue = $row['invoice'];
		$this->payer_email->DbValue = $row['payer_email'];
		$this->first_name->DbValue = $row['first_name'];
		$this->last_name->DbValue = $row['last_name'];
		$this->address_name->DbValue = $row['address_name'];
		$this->address_country->DbValue = $row['address_country'];
		$this->address_country_code->DbValue = $row['address_country_code'];
		$this->address_zip->DbValue = $row['address_zip'];
		$this->address_state->DbValue = $row['address_state'];
		$this->address_city->DbValue = $row['address_city'];
		$this->address_street->DbValue = $row['address_street'];
		$this->payment_type->DbValue = $row['payment_type'];
		$this->payment_status->DbValue = $row['payment_status'];
		$this->payment_currency->DbValue = $row['payment_currency'];
		$this->payment_amount->DbValue = $row['payment_amount'];
		$this->custom->DbValue = $row['custom'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// dateorder
		// invoice
		// payer_email
		// first_name
		// last_name
		// address_name
		// address_country
		// address_country_code
		// address_zip
		// address_state
		// address_city
		// address_street
		// payment_type
		// payment_status
		// payment_currency
		// payment_amount
		// custom

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// dateorder
			$this->dateorder->ViewValue = $this->dateorder->CurrentValue;
			$this->dateorder->ViewValue = ew_FormatDateTime($this->dateorder->ViewValue, 5);
			$this->dateorder->ViewCustomAttributes = "";

			// invoice
			$this->invoice->ViewValue = $this->invoice->CurrentValue;
			$this->invoice->ViewCustomAttributes = "";

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

			// payment_type
			$this->payment_type->ViewValue = $this->payment_type->CurrentValue;
			$this->payment_type->ViewCustomAttributes = "";

			// payment_status
			$this->payment_status->ViewValue = $this->payment_status->CurrentValue;
			$this->payment_status->ViewCustomAttributes = "";

			// payment_currency
			$this->payment_currency->ViewValue = $this->payment_currency->CurrentValue;
			$this->payment_currency->ViewCustomAttributes = "";

			// payment_amount
			$this->payment_amount->ViewValue = $this->payment_amount->CurrentValue;
			$this->payment_amount->ViewCustomAttributes = "";

			// custom
			$this->custom->ViewValue = $this->custom->CurrentValue;
			$this->custom->ViewCustomAttributes = "";

			// dateorder
			$this->dateorder->LinkCustomAttributes = "";
			$this->dateorder->HrefValue = "";
			$this->dateorder->TooltipValue = "";

			// invoice
			$this->invoice->LinkCustomAttributes = "";
			$this->invoice->HrefValue = "";
			$this->invoice->TooltipValue = "";

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

			// payment_type
			$this->payment_type->LinkCustomAttributes = "";
			$this->payment_type->HrefValue = "";
			$this->payment_type->TooltipValue = "";

			// payment_status
			$this->payment_status->LinkCustomAttributes = "";
			$this->payment_status->HrefValue = "";
			$this->payment_status->TooltipValue = "";

			// payment_currency
			$this->payment_currency->LinkCustomAttributes = "";
			$this->payment_currency->HrefValue = "";
			$this->payment_currency->TooltipValue = "";

			// payment_amount
			$this->payment_amount->LinkCustomAttributes = "";
			$this->payment_amount->HrefValue = "";
			$this->payment_amount->TooltipValue = "";

			// custom
			$this->custom->LinkCustomAttributes = "";
			$this->custom->HrefValue = "";
			$this->custom->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("bsc_order_detail", $DetailTblVar)) {
				if (!isset($GLOBALS["bsc_order_detail_grid"]))
					$GLOBALS["bsc_order_detail_grid"] = new cbsc_order_detail_grid;
				if ($GLOBALS["bsc_order_detail_grid"]->DetailView) {
					$GLOBALS["bsc_order_detail_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["bsc_order_detail_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["bsc_order_detail_grid"]->setStartRecordNumber(1);
					$GLOBALS["bsc_order_detail_grid"]->invoice->FldIsDetailKey = TRUE;
					$GLOBALS["bsc_order_detail_grid"]->invoice->CurrentValue = $this->invoice->CurrentValue;
					$GLOBALS["bsc_order_detail_grid"]->invoice->setSessionValue($GLOBALS["bsc_order_detail_grid"]->invoice->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "bsc_order_headerlist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("view");
		$Breadcrumb->Add("view", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($bsc_order_header_view)) $bsc_order_header_view = new cbsc_order_header_view();

// Page init
$bsc_order_header_view->Page_Init();

// Page main
$bsc_order_header_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_order_header_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var bsc_order_header_view = new ew_Page("bsc_order_header_view");
bsc_order_header_view.PageID = "view"; // Page ID
var EW_PAGE_ID = bsc_order_header_view.PageID; // For backward compatibility

// Form object
var fbsc_order_headerview = new ew_Form("fbsc_order_headerview");

// Form_CustomValidate event
fbsc_order_headerview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_order_headerview.ValidateRequired = true;
<?php } else { ?>
fbsc_order_headerview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $bsc_order_header_view->ExportOptions->Render("body") ?>
<?php if (!$bsc_order_header_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($bsc_order_header_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $bsc_order_header_view->ShowPageHeader(); ?>
<?php
$bsc_order_header_view->ShowMessage();
?>
<form name="fbsc_order_headerview" id="fbsc_order_headerview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_order_header">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_bsc_order_headerview" class="table table-bordered table-striped">
<?php if ($bsc_order_header->dateorder->Visible) { // dateorder ?>
	<tr id="r_dateorder"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_dateorder"><?php echo $bsc_order_header->dateorder->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->dateorder->CellAttributes() ?>><span id="el_bsc_order_header_dateorder" class="control-group">
<span<?php echo $bsc_order_header->dateorder->ViewAttributes() ?>>
<?php echo $bsc_order_header->dateorder->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->invoice->Visible) { // invoice ?>
	<tr id="r_invoice"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_invoice"><?php echo $bsc_order_header->invoice->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->invoice->CellAttributes() ?>><span id="el_bsc_order_header_invoice" class="control-group">
<span<?php echo $bsc_order_header->invoice->ViewAttributes() ?>>
<?php echo $bsc_order_header->invoice->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->payer_email->Visible) { // payer_email ?>
	<tr id="r_payer_email"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_payer_email"><?php echo $bsc_order_header->payer_email->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->payer_email->CellAttributes() ?>><span id="el_bsc_order_header_payer_email" class="control-group">
<span<?php echo $bsc_order_header->payer_email->ViewAttributes() ?>>
<?php echo $bsc_order_header->payer_email->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->first_name->Visible) { // first_name ?>
	<tr id="r_first_name"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_first_name"><?php echo $bsc_order_header->first_name->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->first_name->CellAttributes() ?>><span id="el_bsc_order_header_first_name" class="control-group">
<span<?php echo $bsc_order_header->first_name->ViewAttributes() ?>>
<?php echo $bsc_order_header->first_name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->last_name->Visible) { // last_name ?>
	<tr id="r_last_name"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_last_name"><?php echo $bsc_order_header->last_name->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->last_name->CellAttributes() ?>><span id="el_bsc_order_header_last_name" class="control-group">
<span<?php echo $bsc_order_header->last_name->ViewAttributes() ?>>
<?php echo $bsc_order_header->last_name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->address_name->Visible) { // address_name ?>
	<tr id="r_address_name"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_address_name"><?php echo $bsc_order_header->address_name->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->address_name->CellAttributes() ?>><span id="el_bsc_order_header_address_name" class="control-group">
<span<?php echo $bsc_order_header->address_name->ViewAttributes() ?>>
<?php echo $bsc_order_header->address_name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->address_country->Visible) { // address_country ?>
	<tr id="r_address_country"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_address_country"><?php echo $bsc_order_header->address_country->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->address_country->CellAttributes() ?>><span id="el_bsc_order_header_address_country" class="control-group">
<span<?php echo $bsc_order_header->address_country->ViewAttributes() ?>>
<?php echo $bsc_order_header->address_country->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->address_country_code->Visible) { // address_country_code ?>
	<tr id="r_address_country_code"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_address_country_code"><?php echo $bsc_order_header->address_country_code->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->address_country_code->CellAttributes() ?>><span id="el_bsc_order_header_address_country_code" class="control-group">
<span<?php echo $bsc_order_header->address_country_code->ViewAttributes() ?>>
<?php echo $bsc_order_header->address_country_code->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->address_zip->Visible) { // address_zip ?>
	<tr id="r_address_zip"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_address_zip"><?php echo $bsc_order_header->address_zip->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->address_zip->CellAttributes() ?>><span id="el_bsc_order_header_address_zip" class="control-group">
<span<?php echo $bsc_order_header->address_zip->ViewAttributes() ?>>
<?php echo $bsc_order_header->address_zip->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->address_state->Visible) { // address_state ?>
	<tr id="r_address_state"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_address_state"><?php echo $bsc_order_header->address_state->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->address_state->CellAttributes() ?>><span id="el_bsc_order_header_address_state" class="control-group">
<span<?php echo $bsc_order_header->address_state->ViewAttributes() ?>>
<?php echo $bsc_order_header->address_state->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->address_city->Visible) { // address_city ?>
	<tr id="r_address_city"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_address_city"><?php echo $bsc_order_header->address_city->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->address_city->CellAttributes() ?>><span id="el_bsc_order_header_address_city" class="control-group">
<span<?php echo $bsc_order_header->address_city->ViewAttributes() ?>>
<?php echo $bsc_order_header->address_city->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->address_street->Visible) { // address_street ?>
	<tr id="r_address_street"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_address_street"><?php echo $bsc_order_header->address_street->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->address_street->CellAttributes() ?>><span id="el_bsc_order_header_address_street" class="control-group">
<span<?php echo $bsc_order_header->address_street->ViewAttributes() ?>>
<?php echo $bsc_order_header->address_street->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->payment_type->Visible) { // payment_type ?>
	<tr id="r_payment_type"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_payment_type"><?php echo $bsc_order_header->payment_type->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->payment_type->CellAttributes() ?>><span id="el_bsc_order_header_payment_type" class="control-group">
<span<?php echo $bsc_order_header->payment_type->ViewAttributes() ?>>
<?php echo $bsc_order_header->payment_type->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->payment_status->Visible) { // payment_status ?>
	<tr id="r_payment_status"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_payment_status"><?php echo $bsc_order_header->payment_status->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->payment_status->CellAttributes() ?>><span id="el_bsc_order_header_payment_status" class="control-group">
<span<?php echo $bsc_order_header->payment_status->ViewAttributes() ?>>
<?php echo $bsc_order_header->payment_status->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->payment_currency->Visible) { // payment_currency ?>
	<tr id="r_payment_currency"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_payment_currency"><?php echo $bsc_order_header->payment_currency->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->payment_currency->CellAttributes() ?>><span id="el_bsc_order_header_payment_currency" class="control-group">
<span<?php echo $bsc_order_header->payment_currency->ViewAttributes() ?>>
<?php echo $bsc_order_header->payment_currency->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->payment_amount->Visible) { // payment_amount ?>
	<tr id="r_payment_amount"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_payment_amount"><?php echo $bsc_order_header->payment_amount->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->payment_amount->CellAttributes() ?>><span id="el_bsc_order_header_payment_amount" class="control-group">
<span<?php echo $bsc_order_header->payment_amount->ViewAttributes() ?>>
<?php echo $bsc_order_header->payment_amount->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_order_header->custom->Visible) { // custom ?>
	<tr id="r_custom"<?php echo $bsc_order_header->RowAttributes() ?>>
		<td><span id="elh_bsc_order_header_custom"><?php echo $bsc_order_header->custom->FldCaption() ?></span></td>
		<td<?php echo $bsc_order_header->custom->CellAttributes() ?>><span id="el_bsc_order_header_custom" class="control-group">
<span<?php echo $bsc_order_header->custom->ViewAttributes() ?>>
<?php echo $bsc_order_header->custom->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("bsc_order_detail", explode(",", $bsc_order_header->getCurrentDetailTable())) && $bsc_order_detail->DetailView) {
?>
<?php include_once "bsc_order_detailgrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fbsc_order_headerview.Init();
</script>
<?php
$bsc_order_header_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bsc_order_header_view->Page_Terminate();
?>
