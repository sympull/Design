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

$bsc_customers_view = NULL; // Initialize page object first

class cbsc_customers_view extends cbsc_customers {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_customers';

	// Page object name
	var $PageObjName = 'bsc_customers_view';

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

		// Table object (bsc_customers)
		if (!isset($GLOBALS["bsc_customers"])) {
			$GLOBALS["bsc_customers"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_customers"];
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
			define("EW_TABLE_NAME", 'bsc_customers', TRUE);

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
				$sReturnUrl = "bsc_customerslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "bsc_customerslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "bsc_customerslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->IsLoggedIn());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->IsLoggedIn());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->IsLoggedIn());

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "bsc_customerslist.php", $this->TableVar);
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
if (!isset($bsc_customers_view)) $bsc_customers_view = new cbsc_customers_view();

// Page init
$bsc_customers_view->Page_Init();

// Page main
$bsc_customers_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_customers_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var bsc_customers_view = new ew_Page("bsc_customers_view");
bsc_customers_view.PageID = "view"; // Page ID
var EW_PAGE_ID = bsc_customers_view.PageID; // For backward compatibility

// Form object
var fbsc_customersview = new ew_Form("fbsc_customersview");

// Form_CustomValidate event
fbsc_customersview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_customersview.ValidateRequired = true;
<?php } else { ?>
fbsc_customersview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $bsc_customers_view->ExportOptions->Render("body") ?>
<?php if (!$bsc_customers_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($bsc_customers_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $bsc_customers_view->ShowPageHeader(); ?>
<?php
$bsc_customers_view->ShowMessage();
?>
<form name="fbsc_customersview" id="fbsc_customersview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_customers">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_bsc_customersview" class="table table-bordered table-striped">
<?php if ($bsc_customers->datelastorder->Visible) { // datelastorder ?>
	<tr id="r_datelastorder"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_datelastorder"><?php echo $bsc_customers->datelastorder->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->datelastorder->CellAttributes() ?>><span id="el_bsc_customers_datelastorder" class="control-group">
<span<?php echo $bsc_customers->datelastorder->ViewAttributes() ?>>
<?php echo $bsc_customers->datelastorder->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->dateregister->Visible) { // dateregister ?>
	<tr id="r_dateregister"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_dateregister"><?php echo $bsc_customers->dateregister->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->dateregister->CellAttributes() ?>><span id="el_bsc_customers_dateregister" class="control-group">
<span<?php echo $bsc_customers->dateregister->ViewAttributes() ?>>
<?php echo $bsc_customers->dateregister->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->payer_email->Visible) { // payer_email ?>
	<tr id="r_payer_email"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_payer_email"><?php echo $bsc_customers->payer_email->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->payer_email->CellAttributes() ?>><span id="el_bsc_customers_payer_email" class="control-group">
<span<?php echo $bsc_customers->payer_email->ViewAttributes() ?>>
<?php echo $bsc_customers->payer_email->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->first_name->Visible) { // first_name ?>
	<tr id="r_first_name"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_first_name"><?php echo $bsc_customers->first_name->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->first_name->CellAttributes() ?>><span id="el_bsc_customers_first_name" class="control-group">
<span<?php echo $bsc_customers->first_name->ViewAttributes() ?>>
<?php echo $bsc_customers->first_name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->last_name->Visible) { // last_name ?>
	<tr id="r_last_name"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_last_name"><?php echo $bsc_customers->last_name->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->last_name->CellAttributes() ?>><span id="el_bsc_customers_last_name" class="control-group">
<span<?php echo $bsc_customers->last_name->ViewAttributes() ?>>
<?php echo $bsc_customers->last_name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_name->Visible) { // address_name ?>
	<tr id="r_address_name"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_name"><?php echo $bsc_customers->address_name->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_name->CellAttributes() ?>><span id="el_bsc_customers_address_name" class="control-group">
<span<?php echo $bsc_customers->address_name->ViewAttributes() ?>>
<?php echo $bsc_customers->address_name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_street->Visible) { // address_street ?>
	<tr id="r_address_street"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_street"><?php echo $bsc_customers->address_street->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_street->CellAttributes() ?>><span id="el_bsc_customers_address_street" class="control-group">
<span<?php echo $bsc_customers->address_street->ViewAttributes() ?>>
<?php echo $bsc_customers->address_street->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_city->Visible) { // address_city ?>
	<tr id="r_address_city"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_city"><?php echo $bsc_customers->address_city->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_city->CellAttributes() ?>><span id="el_bsc_customers_address_city" class="control-group">
<span<?php echo $bsc_customers->address_city->ViewAttributes() ?>>
<?php echo $bsc_customers->address_city->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_zip->Visible) { // address_zip ?>
	<tr id="r_address_zip"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_zip"><?php echo $bsc_customers->address_zip->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_zip->CellAttributes() ?>><span id="el_bsc_customers_address_zip" class="control-group">
<span<?php echo $bsc_customers->address_zip->ViewAttributes() ?>>
<?php echo $bsc_customers->address_zip->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_state->Visible) { // address_state ?>
	<tr id="r_address_state"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_state"><?php echo $bsc_customers->address_state->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_state->CellAttributes() ?>><span id="el_bsc_customers_address_state" class="control-group">
<span<?php echo $bsc_customers->address_state->ViewAttributes() ?>>
<?php echo $bsc_customers->address_state->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_country->Visible) { // address_country ?>
	<tr id="r_address_country"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_country"><?php echo $bsc_customers->address_country->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_country->CellAttributes() ?>><span id="el_bsc_customers_address_country" class="control-group">
<span<?php echo $bsc_customers->address_country->ViewAttributes() ?>>
<?php echo $bsc_customers->address_country->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($bsc_customers->address_country_code->Visible) { // address_country_code ?>
	<tr id="r_address_country_code"<?php echo $bsc_customers->RowAttributes() ?>>
		<td><span id="elh_bsc_customers_address_country_code"><?php echo $bsc_customers->address_country_code->FldCaption() ?></span></td>
		<td<?php echo $bsc_customers->address_country_code->CellAttributes() ?>><span id="el_bsc_customers_address_country_code" class="control-group">
<span<?php echo $bsc_customers->address_country_code->ViewAttributes() ?>>
<?php echo $bsc_customers->address_country_code->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fbsc_customersview.Init();
</script>
<?php
$bsc_customers_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bsc_customers_view->Page_Terminate();
?>
