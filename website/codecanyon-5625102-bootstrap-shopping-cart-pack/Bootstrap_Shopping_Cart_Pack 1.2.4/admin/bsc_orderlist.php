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

$bsc_order_list = NULL; // Initialize page object first

class cbsc_order_list extends cbsc_order {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_order';

	// Page object name
	var $PageObjName = 'bsc_order_list';

	// Grid form hidden field names
	var $FormName = 'fbsc_orderlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Table object (bsc_order)
		if (!isset($GLOBALS["bsc_order"])) {
			$GLOBALS["bsc_order"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_order"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "bsc_orderadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "bsc_orderdelete.php";
		$this->MultiUpdateUrl = "bsc_orderupdate.php";

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_order', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "span";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();

		// Update url if printer friendly for Pdf
		if ($this->PrinterFriendlyForPdf)
			$this->ExportOptions->Items["pdf"]->Body = str_replace($this->ExportPdfUrl, $this->ExportPrintUrl . "&pdf=1", $this->ExportOptions->Items["pdf"]->Body);
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();
		if ($this->Export == "print" && @$_GET["pdf"] == "1") { // Printer friendly version and with pdf=1 in URL parameters
			$pdf = new cExportPdf($GLOBALS["Table"]);
			$pdf->Text = ob_get_contents(); // Set the content as the HTML of current page (printer friendly version)
			ob_end_clean();
			$pdf->Export();
		}

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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 40;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 40; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->payer_email, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->payment_type, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->custom, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->invoice, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->item_name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->item_number, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->payment_status, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->payment_amount, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->payment_currency, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->first_name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->last_name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->address_name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->address_country, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->address_country_code, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->address_zip, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->address_state, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->address_city, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->address_street, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->dateorder); // dateorder
			$this->UpdateSort($this->payer_email); // payer_email
			$this->UpdateSort($this->payment_type); // payment_type
			$this->UpdateSort($this->custom); // custom
			$this->UpdateSort($this->invoice); // invoice
			$this->UpdateSort($this->item_name); // item_name
			$this->UpdateSort($this->item_number); // item_number
			$this->UpdateSort($this->quantity); // quantity
			$this->UpdateSort($this->payment_status); // payment_status
			$this->UpdateSort($this->payment_amount); // payment_amount
			$this->UpdateSort($this->payment_currency); // payment_currency
			$this->UpdateSort($this->first_name); // first_name
			$this->UpdateSort($this->last_name); // last_name
			$this->UpdateSort($this->address_name); // address_name
			$this->UpdateSort($this->address_country); // address_country
			$this->UpdateSort($this->address_country_code); // address_country_code
			$this->UpdateSort($this->address_zip); // address_zip
			$this->UpdateSort($this->address_state); // address_state
			$this->UpdateSort($this->address_city); // address_city
			$this->UpdateSort($this->address_street); // address_street
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
				$this->dateorder->setSort("ASC");
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->dateorder->setSort("");
				$this->payer_email->setSort("");
				$this->payment_type->setSort("");
				$this->custom->setSort("");
				$this->invoice->setSort("");
				$this->item_name->setSort("");
				$this->item_number->setSort("");
				$this->quantity->setSort("");
				$this->payment_status->setSort("");
				$this->payment_amount->setSort("");
				$this->payment_currency->setSort("");
				$this->first_name->setSort("");
				$this->last_name->setSort("");
				$this->address_name->setSort("");
				$this->address_country->setSort("");
				$this->address_country_code->setSort("");
				$this->address_zip->setSort("");
				$this->address_state->setSort("");
				$this->address_city->setSort("");
				$this->address_street->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		$item->MoveTo(0);
		if (count($this->CustomActions) > 0) $item->Visible = TRUE;
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fbsc_orderlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_bsc_order\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_bsc_order',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fbsc_orderlist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "h");
		$ParentTable = "";
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$ExportDoc->Text .= $sHeader;
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$ExportDoc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$ExportDoc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$ExportDoc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", $url, $this->TableVar);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($bsc_order_list)) $bsc_order_list = new cbsc_order_list();

// Page init
$bsc_order_list->Page_Init();

// Page main
$bsc_order_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_order_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($bsc_order->Export == "") { ?>
<script type="text/javascript">

// Page object
var bsc_order_list = new ew_Page("bsc_order_list");
bsc_order_list.PageID = "list"; // Page ID
var EW_PAGE_ID = bsc_order_list.PageID; // For backward compatibility

// Form object
var fbsc_orderlist = new ew_Form("fbsc_orderlist");
fbsc_orderlist.FormKeyCountName = '<?php echo $bsc_order_list->FormKeyCountName ?>';

// Form_CustomValidate event
fbsc_orderlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_orderlist.ValidateRequired = true;
<?php } else { ?>
fbsc_orderlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fbsc_orderlistsrch = new ew_Form("fbsc_orderlistsrch");
</script>
<style type="text/css">

/* main table preview row color */
.ewTablePreviewRow {
	background-color: #FFFFFF; /* preview row color */
}
.ewPreviewRowImage {
    min-width: 9px; /* for Chrome */
}
</style>
<div id="ewPreview" class="hide"><ul class="nav nav-tabs"></ul><div class="tab-content"><div class="tab-pane fade"></div></div></div>
<script type="text/javascript" src="phpjs/ewpreview.min.js"></script>
<script type="text/javascript">
var EW_PREVIEW_PLACEMENT = "right";
var EW_PREVIEW_SINGLE_ROW = false;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($bsc_order->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($bsc_order_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $bsc_order_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$bsc_order_list->TotalRecs = $bsc_order->SelectRecordCount();
	} else {
		if ($bsc_order_list->Recordset = $bsc_order_list->LoadRecordset())
			$bsc_order_list->TotalRecs = $bsc_order_list->Recordset->RecordCount();
	}
	$bsc_order_list->StartRec = 1;
	if ($bsc_order_list->DisplayRecs <= 0 || ($bsc_order->Export <> "" && $bsc_order->ExportAll)) // Display all records
		$bsc_order_list->DisplayRecs = $bsc_order_list->TotalRecs;
	if (!($bsc_order->Export <> "" && $bsc_order->ExportAll))
		$bsc_order_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$bsc_order_list->Recordset = $bsc_order_list->LoadRecordset($bsc_order_list->StartRec-1, $bsc_order_list->DisplayRecs);
$bsc_order_list->RenderOtherOptions();
?>
<?php if ($bsc_order->Export == "" && $bsc_order->CurrentAction == "") { ?>
<form name="fbsc_orderlistsrch" id="fbsc_orderlistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fbsc_orderlistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fbsc_orderlistsrch_SearchGroup" href="#fbsc_orderlistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fbsc_orderlistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fbsc_orderlistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="bsc_order">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($bsc_order_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $bsc_order_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($bsc_order_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($bsc_order_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($bsc_order_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</td></tr></table>
</form>
<?php } ?>
<?php $bsc_order_list->ShowPageHeader(); ?>
<?php
$bsc_order_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($bsc_order->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($bsc_order->CurrentAction <> "gridadd" && $bsc_order->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($bsc_order_list->Pager)) $bsc_order_list->Pager = new cPrevNextPager($bsc_order_list->StartRec, $bsc_order_list->DisplayRecs, $bsc_order_list->TotalRecs) ?>
<?php if ($bsc_order_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($bsc_order_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_order_list->PageUrl() ?>start=<?php echo $bsc_order_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($bsc_order_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_order_list->PageUrl() ?>start=<?php echo $bsc_order_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $bsc_order_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($bsc_order_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_order_list->PageUrl() ?>start=<?php echo $bsc_order_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($bsc_order_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_order_list->PageUrl() ?>start=<?php echo $bsc_order_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $bsc_order_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $bsc_order_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $bsc_order_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $bsc_order_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($bsc_order_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($bsc_order_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="fbsc_orderlist" id="fbsc_orderlist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_order">
<div id="gmp_bsc_order" class="ewGridMiddlePanel">
<?php if ($bsc_order_list->TotalRecs > 0) { ?>
<table id="tbl_bsc_orderlist" class="ewTable ewTableSeparate">
<?php echo $bsc_order->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$bsc_order_list->RenderListOptions();

// Render list options (header, left)
$bsc_order_list->ListOptions->Render("header", "left");
?>
<?php if ($bsc_order->id->Visible) { // id ?>
	<?php if ($bsc_order->SortUrl($bsc_order->id) == "") { ?>
		<td><div id="elh_bsc_order_id" class="bsc_order_id"><div class="ewTableHeaderCaption"><?php echo $bsc_order->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->id) ?>',1);"><div id="elh_bsc_order_id" class="bsc_order_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->dateorder->Visible) { // dateorder ?>
	<?php if ($bsc_order->SortUrl($bsc_order->dateorder) == "") { ?>
		<td><div id="elh_bsc_order_dateorder" class="bsc_order_dateorder"><div class="ewTableHeaderCaption"><?php echo $bsc_order->dateorder->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->dateorder) ?>',1);"><div id="elh_bsc_order_dateorder" class="bsc_order_dateorder">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->dateorder->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->dateorder->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->dateorder->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->payer_email->Visible) { // payer_email ?>
	<?php if ($bsc_order->SortUrl($bsc_order->payer_email) == "") { ?>
		<td><div id="elh_bsc_order_payer_email" class="bsc_order_payer_email"><div class="ewTableHeaderCaption"><?php echo $bsc_order->payer_email->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->payer_email) ?>',1);"><div id="elh_bsc_order_payer_email" class="bsc_order_payer_email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->payer_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->payer_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->payer_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->payment_type->Visible) { // payment_type ?>
	<?php if ($bsc_order->SortUrl($bsc_order->payment_type) == "") { ?>
		<td><div id="elh_bsc_order_payment_type" class="bsc_order_payment_type"><div class="ewTableHeaderCaption"><?php echo $bsc_order->payment_type->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->payment_type) ?>',1);"><div id="elh_bsc_order_payment_type" class="bsc_order_payment_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->payment_type->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->payment_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->payment_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->custom->Visible) { // custom ?>
	<?php if ($bsc_order->SortUrl($bsc_order->custom) == "") { ?>
		<td><div id="elh_bsc_order_custom" class="bsc_order_custom"><div class="ewTableHeaderCaption"><?php echo $bsc_order->custom->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->custom) ?>',1);"><div id="elh_bsc_order_custom" class="bsc_order_custom">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->custom->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->custom->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->custom->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->invoice->Visible) { // invoice ?>
	<?php if ($bsc_order->SortUrl($bsc_order->invoice) == "") { ?>
		<td><div id="elh_bsc_order_invoice" class="bsc_order_invoice"><div class="ewTableHeaderCaption"><?php echo $bsc_order->invoice->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->invoice) ?>',1);"><div id="elh_bsc_order_invoice" class="bsc_order_invoice">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->invoice->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->invoice->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->invoice->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->item_name->Visible) { // item_name ?>
	<?php if ($bsc_order->SortUrl($bsc_order->item_name) == "") { ?>
		<td><div id="elh_bsc_order_item_name" class="bsc_order_item_name"><div class="ewTableHeaderCaption"><?php echo $bsc_order->item_name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->item_name) ?>',1);"><div id="elh_bsc_order_item_name" class="bsc_order_item_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->item_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->item_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->item_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->item_number->Visible) { // item_number ?>
	<?php if ($bsc_order->SortUrl($bsc_order->item_number) == "") { ?>
		<td><div id="elh_bsc_order_item_number" class="bsc_order_item_number"><div class="ewTableHeaderCaption"><?php echo $bsc_order->item_number->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->item_number) ?>',1);"><div id="elh_bsc_order_item_number" class="bsc_order_item_number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->item_number->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->item_number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->item_number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->quantity->Visible) { // quantity ?>
	<?php if ($bsc_order->SortUrl($bsc_order->quantity) == "") { ?>
		<td><div id="elh_bsc_order_quantity" class="bsc_order_quantity"><div class="ewTableHeaderCaption"><?php echo $bsc_order->quantity->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->quantity) ?>',1);"><div id="elh_bsc_order_quantity" class="bsc_order_quantity">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->quantity->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->quantity->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->quantity->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->payment_status->Visible) { // payment_status ?>
	<?php if ($bsc_order->SortUrl($bsc_order->payment_status) == "") { ?>
		<td><div id="elh_bsc_order_payment_status" class="bsc_order_payment_status"><div class="ewTableHeaderCaption"><?php echo $bsc_order->payment_status->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->payment_status) ?>',1);"><div id="elh_bsc_order_payment_status" class="bsc_order_payment_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->payment_status->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->payment_status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->payment_status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->payment_amount->Visible) { // payment_amount ?>
	<?php if ($bsc_order->SortUrl($bsc_order->payment_amount) == "") { ?>
		<td><div id="elh_bsc_order_payment_amount" class="bsc_order_payment_amount"><div class="ewTableHeaderCaption"><?php echo $bsc_order->payment_amount->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->payment_amount) ?>',1);"><div id="elh_bsc_order_payment_amount" class="bsc_order_payment_amount">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->payment_amount->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->payment_amount->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->payment_amount->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->payment_currency->Visible) { // payment_currency ?>
	<?php if ($bsc_order->SortUrl($bsc_order->payment_currency) == "") { ?>
		<td><div id="elh_bsc_order_payment_currency" class="bsc_order_payment_currency"><div class="ewTableHeaderCaption"><?php echo $bsc_order->payment_currency->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->payment_currency) ?>',1);"><div id="elh_bsc_order_payment_currency" class="bsc_order_payment_currency">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->payment_currency->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->payment_currency->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->payment_currency->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->first_name->Visible) { // first_name ?>
	<?php if ($bsc_order->SortUrl($bsc_order->first_name) == "") { ?>
		<td><div id="elh_bsc_order_first_name" class="bsc_order_first_name"><div class="ewTableHeaderCaption"><?php echo $bsc_order->first_name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->first_name) ?>',1);"><div id="elh_bsc_order_first_name" class="bsc_order_first_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->first_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->first_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->first_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->last_name->Visible) { // last_name ?>
	<?php if ($bsc_order->SortUrl($bsc_order->last_name) == "") { ?>
		<td><div id="elh_bsc_order_last_name" class="bsc_order_last_name"><div class="ewTableHeaderCaption"><?php echo $bsc_order->last_name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->last_name) ?>',1);"><div id="elh_bsc_order_last_name" class="bsc_order_last_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->last_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->last_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->last_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->address_name->Visible) { // address_name ?>
	<?php if ($bsc_order->SortUrl($bsc_order->address_name) == "") { ?>
		<td><div id="elh_bsc_order_address_name" class="bsc_order_address_name"><div class="ewTableHeaderCaption"><?php echo $bsc_order->address_name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->address_name) ?>',1);"><div id="elh_bsc_order_address_name" class="bsc_order_address_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->address_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->address_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->address_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->address_country->Visible) { // address_country ?>
	<?php if ($bsc_order->SortUrl($bsc_order->address_country) == "") { ?>
		<td><div id="elh_bsc_order_address_country" class="bsc_order_address_country"><div class="ewTableHeaderCaption"><?php echo $bsc_order->address_country->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->address_country) ?>',1);"><div id="elh_bsc_order_address_country" class="bsc_order_address_country">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->address_country->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->address_country->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->address_country->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->address_country_code->Visible) { // address_country_code ?>
	<?php if ($bsc_order->SortUrl($bsc_order->address_country_code) == "") { ?>
		<td><div id="elh_bsc_order_address_country_code" class="bsc_order_address_country_code"><div class="ewTableHeaderCaption"><?php echo $bsc_order->address_country_code->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->address_country_code) ?>',1);"><div id="elh_bsc_order_address_country_code" class="bsc_order_address_country_code">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->address_country_code->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->address_country_code->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->address_country_code->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->address_zip->Visible) { // address_zip ?>
	<?php if ($bsc_order->SortUrl($bsc_order->address_zip) == "") { ?>
		<td><div id="elh_bsc_order_address_zip" class="bsc_order_address_zip"><div class="ewTableHeaderCaption"><?php echo $bsc_order->address_zip->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->address_zip) ?>',1);"><div id="elh_bsc_order_address_zip" class="bsc_order_address_zip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->address_zip->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->address_zip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->address_zip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->address_state->Visible) { // address_state ?>
	<?php if ($bsc_order->SortUrl($bsc_order->address_state) == "") { ?>
		<td><div id="elh_bsc_order_address_state" class="bsc_order_address_state"><div class="ewTableHeaderCaption"><?php echo $bsc_order->address_state->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->address_state) ?>',1);"><div id="elh_bsc_order_address_state" class="bsc_order_address_state">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->address_state->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->address_state->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->address_state->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->address_city->Visible) { // address_city ?>
	<?php if ($bsc_order->SortUrl($bsc_order->address_city) == "") { ?>
		<td><div id="elh_bsc_order_address_city" class="bsc_order_address_city"><div class="ewTableHeaderCaption"><?php echo $bsc_order->address_city->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->address_city) ?>',1);"><div id="elh_bsc_order_address_city" class="bsc_order_address_city">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->address_city->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->address_city->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->address_city->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order->address_street->Visible) { // address_street ?>
	<?php if ($bsc_order->SortUrl($bsc_order->address_street) == "") { ?>
		<td><div id="elh_bsc_order_address_street" class="bsc_order_address_street"><div class="ewTableHeaderCaption"><?php echo $bsc_order->address_street->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order->SortUrl($bsc_order->address_street) ?>',1);"><div id="elh_bsc_order_address_street" class="bsc_order_address_street">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order->address_street->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order->address_street->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order->address_street->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$bsc_order_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($bsc_order->ExportAll && $bsc_order->Export <> "") {
	$bsc_order_list->StopRec = $bsc_order_list->TotalRecs;
} else {

	// Set the last record to display
	if ($bsc_order_list->TotalRecs > $bsc_order_list->StartRec + $bsc_order_list->DisplayRecs - 1)
		$bsc_order_list->StopRec = $bsc_order_list->StartRec + $bsc_order_list->DisplayRecs - 1;
	else
		$bsc_order_list->StopRec = $bsc_order_list->TotalRecs;
}
$bsc_order_list->RecCnt = $bsc_order_list->StartRec - 1;
if ($bsc_order_list->Recordset && !$bsc_order_list->Recordset->EOF) {
	$bsc_order_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $bsc_order_list->StartRec > 1)
		$bsc_order_list->Recordset->Move($bsc_order_list->StartRec - 1);
} elseif (!$bsc_order->AllowAddDeleteRow && $bsc_order_list->StopRec == 0) {
	$bsc_order_list->StopRec = $bsc_order->GridAddRowCount;
}

// Initialize aggregate
$bsc_order->RowType = EW_ROWTYPE_AGGREGATEINIT;
$bsc_order->ResetAttrs();
$bsc_order_list->RenderRow();
while ($bsc_order_list->RecCnt < $bsc_order_list->StopRec) {
	$bsc_order_list->RecCnt++;
	if (intval($bsc_order_list->RecCnt) >= intval($bsc_order_list->StartRec)) {
		$bsc_order_list->RowCnt++;

		// Set up key count
		$bsc_order_list->KeyCount = $bsc_order_list->RowIndex;

		// Init row class and style
		$bsc_order->ResetAttrs();
		$bsc_order->CssClass = "";
		if ($bsc_order->CurrentAction == "gridadd") {
		} else {
			$bsc_order_list->LoadRowValues($bsc_order_list->Recordset); // Load row values
		}
		$bsc_order->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$bsc_order->RowAttrs = array_merge($bsc_order->RowAttrs, array('data-rowindex'=>$bsc_order_list->RowCnt, 'id'=>'r' . $bsc_order_list->RowCnt . '_bsc_order', 'data-rowtype'=>$bsc_order->RowType));

		// Render row
		$bsc_order_list->RenderRow();

		// Render list options
		$bsc_order_list->RenderListOptions();
?>
	<tr<?php echo $bsc_order->RowAttributes() ?>>
<?php

// Render list options (body, left)
$bsc_order_list->ListOptions->Render("body", "left", $bsc_order_list->RowCnt);
?>
	<?php if ($bsc_order->id->Visible) { // id ?>
		<td<?php echo $bsc_order->id->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_id" class="control-group bsc_order_id">
<span<?php echo $bsc_order->id->ViewAttributes() ?>>
<?php echo $bsc_order->id->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->dateorder->Visible) { // dateorder ?>
		<td<?php echo $bsc_order->dateorder->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_dateorder" class="control-group bsc_order_dateorder">
<span<?php echo $bsc_order->dateorder->ViewAttributes() ?>>
<?php echo $bsc_order->dateorder->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->payer_email->Visible) { // payer_email ?>
		<td<?php echo $bsc_order->payer_email->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_payer_email" class="control-group bsc_order_payer_email">
<span<?php echo $bsc_order->payer_email->ViewAttributes() ?>>
<?php echo $bsc_order->payer_email->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->payment_type->Visible) { // payment_type ?>
		<td<?php echo $bsc_order->payment_type->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_payment_type" class="control-group bsc_order_payment_type">
<span<?php echo $bsc_order->payment_type->ViewAttributes() ?>>
<?php echo $bsc_order->payment_type->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->custom->Visible) { // custom ?>
		<td<?php echo $bsc_order->custom->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_custom" class="control-group bsc_order_custom">
<span<?php echo $bsc_order->custom->ViewAttributes() ?>>
<?php echo $bsc_order->custom->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->invoice->Visible) { // invoice ?>
		<td<?php echo $bsc_order->invoice->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_invoice" class="control-group bsc_order_invoice">
<span<?php echo $bsc_order->invoice->ViewAttributes() ?>>
<?php echo $bsc_order->invoice->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->item_name->Visible) { // item_name ?>
		<td<?php echo $bsc_order->item_name->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_item_name" class="control-group bsc_order_item_name">
<span<?php echo $bsc_order->item_name->ViewAttributes() ?>>
<?php echo $bsc_order->item_name->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->item_number->Visible) { // item_number ?>
		<td<?php echo $bsc_order->item_number->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_item_number" class="control-group bsc_order_item_number">
<span<?php echo $bsc_order->item_number->ViewAttributes() ?>>
<?php echo $bsc_order->item_number->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->quantity->Visible) { // quantity ?>
		<td<?php echo $bsc_order->quantity->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_quantity" class="control-group bsc_order_quantity">
<span<?php echo $bsc_order->quantity->ViewAttributes() ?>>
<?php echo $bsc_order->quantity->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->payment_status->Visible) { // payment_status ?>
		<td<?php echo $bsc_order->payment_status->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_payment_status" class="control-group bsc_order_payment_status">
<span<?php echo $bsc_order->payment_status->ViewAttributes() ?>>
<?php echo $bsc_order->payment_status->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->payment_amount->Visible) { // payment_amount ?>
		<td<?php echo $bsc_order->payment_amount->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_payment_amount" class="control-group bsc_order_payment_amount">
<span<?php echo $bsc_order->payment_amount->ViewAttributes() ?>>
<?php echo $bsc_order->payment_amount->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->payment_currency->Visible) { // payment_currency ?>
		<td<?php echo $bsc_order->payment_currency->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_payment_currency" class="control-group bsc_order_payment_currency">
<span<?php echo $bsc_order->payment_currency->ViewAttributes() ?>>
<?php echo $bsc_order->payment_currency->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->first_name->Visible) { // first_name ?>
		<td<?php echo $bsc_order->first_name->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_first_name" class="control-group bsc_order_first_name">
<span<?php echo $bsc_order->first_name->ViewAttributes() ?>>
<?php echo $bsc_order->first_name->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->last_name->Visible) { // last_name ?>
		<td<?php echo $bsc_order->last_name->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_last_name" class="control-group bsc_order_last_name">
<span<?php echo $bsc_order->last_name->ViewAttributes() ?>>
<?php echo $bsc_order->last_name->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->address_name->Visible) { // address_name ?>
		<td<?php echo $bsc_order->address_name->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_address_name" class="control-group bsc_order_address_name">
<span<?php echo $bsc_order->address_name->ViewAttributes() ?>>
<?php echo $bsc_order->address_name->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->address_country->Visible) { // address_country ?>
		<td<?php echo $bsc_order->address_country->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_address_country" class="control-group bsc_order_address_country">
<span<?php echo $bsc_order->address_country->ViewAttributes() ?>>
<?php echo $bsc_order->address_country->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->address_country_code->Visible) { // address_country_code ?>
		<td<?php echo $bsc_order->address_country_code->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_address_country_code" class="control-group bsc_order_address_country_code">
<span<?php echo $bsc_order->address_country_code->ViewAttributes() ?>>
<?php echo $bsc_order->address_country_code->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->address_zip->Visible) { // address_zip ?>
		<td<?php echo $bsc_order->address_zip->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_address_zip" class="control-group bsc_order_address_zip">
<span<?php echo $bsc_order->address_zip->ViewAttributes() ?>>
<?php echo $bsc_order->address_zip->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->address_state->Visible) { // address_state ?>
		<td<?php echo $bsc_order->address_state->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_address_state" class="control-group bsc_order_address_state">
<span<?php echo $bsc_order->address_state->ViewAttributes() ?>>
<?php echo $bsc_order->address_state->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->address_city->Visible) { // address_city ?>
		<td<?php echo $bsc_order->address_city->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_address_city" class="control-group bsc_order_address_city">
<span<?php echo $bsc_order->address_city->ViewAttributes() ?>>
<?php echo $bsc_order->address_city->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order->address_street->Visible) { // address_street ?>
		<td<?php echo $bsc_order->address_street->CellAttributes() ?>><span id="el<?php echo $bsc_order_list->RowCnt ?>_bsc_order_address_street" class="control-group bsc_order_address_street">
<span<?php echo $bsc_order->address_street->ViewAttributes() ?>>
<?php echo $bsc_order->address_street->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_list->PageObjName . "_row_" . $bsc_order_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$bsc_order_list->ListOptions->Render("body", "right", $bsc_order_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($bsc_order->CurrentAction <> "gridadd")
		$bsc_order_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($bsc_order->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($bsc_order_list->Recordset)
	$bsc_order_list->Recordset->Close();
?>
</td></tr></table>
<?php if ($bsc_order->Export == "") { ?>
<script type="text/javascript">
fbsc_orderlistsrch.Init();
fbsc_orderlist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$bsc_order_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($bsc_order->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$bsc_order_list->Page_Terminate();
?>
