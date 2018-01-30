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

$bsc_order_detail_list = NULL; // Initialize page object first

class cbsc_order_detail_list extends cbsc_order_detail {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_order_detail';

	// Page object name
	var $PageObjName = 'bsc_order_detail_list';

	// Grid form hidden field names
	var $FormName = 'fbsc_order_detaillist';
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

		// Table object (bsc_order_detail)
		if (!isset($GLOBALS["bsc_order_detail"])) {
			$GLOBALS["bsc_order_detail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_order_detail"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "bsc_order_detailadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "bsc_order_detaildelete.php";
		$this->MultiUpdateUrl = "bsc_order_detailupdate.php";

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Table object (bsc_order_header)
		if (!isset($GLOBALS['bsc_order_header'])) $GLOBALS['bsc_order_header'] = new cbsc_order_header();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_order_detail', TRUE);

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
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}

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

			// Set up master detail parameters
			$this->SetUpMasterParms();

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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "bsc_order_header") {
			global $bsc_order_header;
			$rsmaster = $bsc_order_header->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("bsc_order_headerlist.php"); // Return to master page
			} else {
				$bsc_order_header->LoadListRowValues($rsmaster);
				$bsc_order_header->RowType = EW_ROWTYPE_MASTER; // Master row
				$bsc_order_header->RenderListRow();
				$rsmaster->Close();
			}
		}

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
		$this->BuildBasicSearchSQL($sWhere, $this->item_number, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->item_name, $Keyword);
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
			$this->UpdateSort($this->item_number); // item_number
			$this->UpdateSort($this->item_name); // item_name
			$this->UpdateSort($this->quantity); // quantity
			$this->UpdateSort($this->mc_gross); // mc_gross
			$this->UpdateSort($this->item_price); // item_price
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->invoice->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->item_number->setSort("");
				$this->item_name->setSort("");
				$this->quantity->setSort("");
				$this->mc_gross->setSort("");
				$this->item_price->setSort("");
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
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fbsc_order_detaillist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->item_price->setDbValue($rs->fields('item_price'));
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
		$this->item_price->DbValue = $row['item_price'];
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

		// Convert decimal values if posted back
		if ($this->item_price->FormValue == $this->item_price->CurrentValue && is_numeric(ew_StrToFloat($this->item_price->CurrentValue)))
			$this->item_price->CurrentValue = ew_StrToFloat($this->item_price->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id

		$this->id->CellCssStyle = "white-space: nowrap;";

		// dateorder
		$this->dateorder->CellCssStyle = "white-space: nowrap;";

		// item_number
		// item_name
		// quantity
		// mc_gross
		// payment_status

		$this->payment_status->CellCssStyle = "white-space: nowrap;";

		// payment_amount
		$this->payment_amount->CellCssStyle = "white-space: nowrap;";

		// payment_currency
		$this->payment_currency->CellCssStyle = "white-space: nowrap;";

		// payer_email
		$this->payer_email->CellCssStyle = "white-space: nowrap;";

		// payment_type
		$this->payment_type->CellCssStyle = "white-space: nowrap;";

		// custom
		$this->custom->CellCssStyle = "white-space: nowrap;";

		// invoice
		$this->invoice->CellCssStyle = "white-space: nowrap;";

		// first_name
		$this->first_name->CellCssStyle = "white-space: nowrap;";

		// last_name
		$this->last_name->CellCssStyle = "white-space: nowrap;";

		// address_name
		$this->address_name->CellCssStyle = "white-space: nowrap;";

		// address_country
		$this->address_country->CellCssStyle = "white-space: nowrap;";

		// address_country_code
		$this->address_country_code->CellCssStyle = "white-space: nowrap;";

		// address_zip
		$this->address_zip->CellCssStyle = "white-space: nowrap;";

		// address_state
		$this->address_state->CellCssStyle = "white-space: nowrap;";

		// address_city
		$this->address_city->CellCssStyle = "white-space: nowrap;";

		// address_street
		$this->address_street->CellCssStyle = "white-space: nowrap;";

		// item_price
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

			// item_price
			$this->item_price->ViewValue = $this->item_price->CurrentValue;
			$this->item_price->ViewCustomAttributes = "";

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

			// item_price
			$this->item_price->LinkCustomAttributes = "";
			$this->item_price->HrefValue = "";
			$this->item_price->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_bsc_order_detail\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_bsc_order_detail',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fbsc_order_detaillist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "bsc_order_header") {
			global $bsc_order_header;
			$rsmaster = $bsc_order_header->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $ExportDoc->Style;
				$ExportDoc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$bsc_order_header->ExportDocument($ExportDoc, $rsmaster, 1, 1);
					$ExportDoc->ExportEmptyRow();
				}
				$ExportDoc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
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
if (!isset($bsc_order_detail_list)) $bsc_order_detail_list = new cbsc_order_detail_list();

// Page init
$bsc_order_detail_list->Page_Init();

// Page main
$bsc_order_detail_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_order_detail_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($bsc_order_detail->Export == "") { ?>
<script type="text/javascript">

// Page object
var bsc_order_detail_list = new ew_Page("bsc_order_detail_list");
bsc_order_detail_list.PageID = "list"; // Page ID
var EW_PAGE_ID = bsc_order_detail_list.PageID; // For backward compatibility

// Form object
var fbsc_order_detaillist = new ew_Form("fbsc_order_detaillist");
fbsc_order_detaillist.FormKeyCountName = '<?php echo $bsc_order_detail_list->FormKeyCountName ?>';

// Form_CustomValidate event
fbsc_order_detaillist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_order_detaillist.ValidateRequired = true;
<?php } else { ?>
fbsc_order_detaillist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fbsc_order_detaillistsrch = new ew_Form("fbsc_order_detaillistsrch");
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
<?php if ($bsc_order_detail->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($bsc_order_detail->getCurrentMasterTable() == "" && $bsc_order_detail_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $bsc_order_detail_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($bsc_order_detail->Export == "") || (EW_EXPORT_MASTER_RECORD && $bsc_order_detail->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "bsc_order_headerlist.php";
if ($bsc_order_detail_list->DbMasterFilter <> "" && $bsc_order_detail->getCurrentMasterTable() == "bsc_order_header") {
	if ($bsc_order_detail_list->MasterRecordExists) {
		if ($bsc_order_detail->getCurrentMasterTable() == $bsc_order_detail->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($bsc_order_detail_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $bsc_order_detail_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "bsc_order_headermaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$bsc_order_detail_list->TotalRecs = $bsc_order_detail->SelectRecordCount();
	} else {
		if ($bsc_order_detail_list->Recordset = $bsc_order_detail_list->LoadRecordset())
			$bsc_order_detail_list->TotalRecs = $bsc_order_detail_list->Recordset->RecordCount();
	}
	$bsc_order_detail_list->StartRec = 1;
	if ($bsc_order_detail_list->DisplayRecs <= 0 || ($bsc_order_detail->Export <> "" && $bsc_order_detail->ExportAll)) // Display all records
		$bsc_order_detail_list->DisplayRecs = $bsc_order_detail_list->TotalRecs;
	if (!($bsc_order_detail->Export <> "" && $bsc_order_detail->ExportAll))
		$bsc_order_detail_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$bsc_order_detail_list->Recordset = $bsc_order_detail_list->LoadRecordset($bsc_order_detail_list->StartRec-1, $bsc_order_detail_list->DisplayRecs);
$bsc_order_detail_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($bsc_order_detail->Export == "" && $bsc_order_detail->CurrentAction == "") { ?>
<form name="fbsc_order_detaillistsrch" id="fbsc_order_detaillistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fbsc_order_detaillistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fbsc_order_detaillistsrch_SearchGroup" href="#fbsc_order_detaillistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fbsc_order_detaillistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fbsc_order_detaillistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="bsc_order_detail">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($bsc_order_detail_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $bsc_order_detail_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($bsc_order_detail_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($bsc_order_detail_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($bsc_order_detail_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php } ?>
<?php $bsc_order_detail_list->ShowPageHeader(); ?>
<?php
$bsc_order_detail_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($bsc_order_detail->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($bsc_order_detail->CurrentAction <> "gridadd" && $bsc_order_detail->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($bsc_order_detail_list->Pager)) $bsc_order_detail_list->Pager = new cPrevNextPager($bsc_order_detail_list->StartRec, $bsc_order_detail_list->DisplayRecs, $bsc_order_detail_list->TotalRecs) ?>
<?php if ($bsc_order_detail_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($bsc_order_detail_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_order_detail_list->PageUrl() ?>start=<?php echo $bsc_order_detail_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($bsc_order_detail_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_order_detail_list->PageUrl() ?>start=<?php echo $bsc_order_detail_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $bsc_order_detail_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($bsc_order_detail_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_order_detail_list->PageUrl() ?>start=<?php echo $bsc_order_detail_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($bsc_order_detail_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_order_detail_list->PageUrl() ?>start=<?php echo $bsc_order_detail_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $bsc_order_detail_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $bsc_order_detail_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $bsc_order_detail_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $bsc_order_detail_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($bsc_order_detail_list->SearchWhere == "0=101") { ?>
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
	foreach ($bsc_order_detail_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="fbsc_order_detaillist" id="fbsc_order_detaillist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_order_detail">
<div id="gmp_bsc_order_detail" class="ewGridMiddlePanel">
<?php if ($bsc_order_detail_list->TotalRecs > 0) { ?>
<table id="tbl_bsc_order_detaillist" class="ewTable ewTableSeparate">
<?php echo $bsc_order_detail->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$bsc_order_detail_list->RenderListOptions();

// Render list options (header, left)
$bsc_order_detail_list->ListOptions->Render("header", "left");
?>
<?php if ($bsc_order_detail->item_number->Visible) { // item_number ?>
	<?php if ($bsc_order_detail->SortUrl($bsc_order_detail->item_number) == "") { ?>
		<td><div id="elh_bsc_order_detail_item_number" class="bsc_order_detail_item_number"><div class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_number->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order_detail->SortUrl($bsc_order_detail->item_number) ?>',1);"><div id="elh_bsc_order_detail_item_number" class="bsc_order_detail_item_number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_number->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order_detail->item_number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order_detail->item_number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order_detail->item_name->Visible) { // item_name ?>
	<?php if ($bsc_order_detail->SortUrl($bsc_order_detail->item_name) == "") { ?>
		<td><div id="elh_bsc_order_detail_item_name" class="bsc_order_detail_item_name"><div class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order_detail->SortUrl($bsc_order_detail->item_name) ?>',1);"><div id="elh_bsc_order_detail_item_name" class="bsc_order_detail_item_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order_detail->item_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order_detail->item_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order_detail->quantity->Visible) { // quantity ?>
	<?php if ($bsc_order_detail->SortUrl($bsc_order_detail->quantity) == "") { ?>
		<td><div id="elh_bsc_order_detail_quantity" class="bsc_order_detail_quantity"><div class="ewTableHeaderCaption"><?php echo $bsc_order_detail->quantity->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order_detail->SortUrl($bsc_order_detail->quantity) ?>',1);"><div id="elh_bsc_order_detail_quantity" class="bsc_order_detail_quantity">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order_detail->quantity->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order_detail->quantity->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order_detail->quantity->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order_detail->mc_gross->Visible) { // mc_gross ?>
	<?php if ($bsc_order_detail->SortUrl($bsc_order_detail->mc_gross) == "") { ?>
		<td><div id="elh_bsc_order_detail_mc_gross" class="bsc_order_detail_mc_gross"><div class="ewTableHeaderCaption"><?php echo $bsc_order_detail->mc_gross->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order_detail->SortUrl($bsc_order_detail->mc_gross) ?>',1);"><div id="elh_bsc_order_detail_mc_gross" class="bsc_order_detail_mc_gross">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order_detail->mc_gross->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order_detail->mc_gross->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order_detail->mc_gross->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_order_detail->item_price->Visible) { // item_price ?>
	<?php if ($bsc_order_detail->SortUrl($bsc_order_detail->item_price) == "") { ?>
		<td><div id="elh_bsc_order_detail_item_price" class="bsc_order_detail_item_price"><div class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_price->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_order_detail->SortUrl($bsc_order_detail->item_price) ?>',1);"><div id="elh_bsc_order_detail_item_price" class="bsc_order_detail_item_price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_order_detail->item_price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_order_detail->item_price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_order_detail->item_price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$bsc_order_detail_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($bsc_order_detail->ExportAll && $bsc_order_detail->Export <> "") {
	$bsc_order_detail_list->StopRec = $bsc_order_detail_list->TotalRecs;
} else {

	// Set the last record to display
	if ($bsc_order_detail_list->TotalRecs > $bsc_order_detail_list->StartRec + $bsc_order_detail_list->DisplayRecs - 1)
		$bsc_order_detail_list->StopRec = $bsc_order_detail_list->StartRec + $bsc_order_detail_list->DisplayRecs - 1;
	else
		$bsc_order_detail_list->StopRec = $bsc_order_detail_list->TotalRecs;
}
$bsc_order_detail_list->RecCnt = $bsc_order_detail_list->StartRec - 1;
if ($bsc_order_detail_list->Recordset && !$bsc_order_detail_list->Recordset->EOF) {
	$bsc_order_detail_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $bsc_order_detail_list->StartRec > 1)
		$bsc_order_detail_list->Recordset->Move($bsc_order_detail_list->StartRec - 1);
} elseif (!$bsc_order_detail->AllowAddDeleteRow && $bsc_order_detail_list->StopRec == 0) {
	$bsc_order_detail_list->StopRec = $bsc_order_detail->GridAddRowCount;
}

// Initialize aggregate
$bsc_order_detail->RowType = EW_ROWTYPE_AGGREGATEINIT;
$bsc_order_detail->ResetAttrs();
$bsc_order_detail_list->RenderRow();
while ($bsc_order_detail_list->RecCnt < $bsc_order_detail_list->StopRec) {
	$bsc_order_detail_list->RecCnt++;
	if (intval($bsc_order_detail_list->RecCnt) >= intval($bsc_order_detail_list->StartRec)) {
		$bsc_order_detail_list->RowCnt++;

		// Set up key count
		$bsc_order_detail_list->KeyCount = $bsc_order_detail_list->RowIndex;

		// Init row class and style
		$bsc_order_detail->ResetAttrs();
		$bsc_order_detail->CssClass = "";
		if ($bsc_order_detail->CurrentAction == "gridadd") {
		} else {
			$bsc_order_detail_list->LoadRowValues($bsc_order_detail_list->Recordset); // Load row values
		}
		$bsc_order_detail->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$bsc_order_detail->RowAttrs = array_merge($bsc_order_detail->RowAttrs, array('data-rowindex'=>$bsc_order_detail_list->RowCnt, 'id'=>'r' . $bsc_order_detail_list->RowCnt . '_bsc_order_detail', 'data-rowtype'=>$bsc_order_detail->RowType));

		// Render row
		$bsc_order_detail_list->RenderRow();

		// Render list options
		$bsc_order_detail_list->RenderListOptions();
?>
	<tr<?php echo $bsc_order_detail->RowAttributes() ?>>
<?php

// Render list options (body, left)
$bsc_order_detail_list->ListOptions->Render("body", "left", $bsc_order_detail_list->RowCnt);
?>
	<?php if ($bsc_order_detail->item_number->Visible) { // item_number ?>
		<td<?php echo $bsc_order_detail->item_number->CellAttributes() ?>><span id="el<?php echo $bsc_order_detail_list->RowCnt ?>_bsc_order_detail_item_number" class="control-group bsc_order_detail_item_number">
<span<?php echo $bsc_order_detail->item_number->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_number->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_detail_list->PageObjName . "_row_" . $bsc_order_detail_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order_detail->item_name->Visible) { // item_name ?>
		<td<?php echo $bsc_order_detail->item_name->CellAttributes() ?>><span id="el<?php echo $bsc_order_detail_list->RowCnt ?>_bsc_order_detail_item_name" class="control-group bsc_order_detail_item_name">
<span<?php echo $bsc_order_detail->item_name->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_name->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_detail_list->PageObjName . "_row_" . $bsc_order_detail_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order_detail->quantity->Visible) { // quantity ?>
		<td<?php echo $bsc_order_detail->quantity->CellAttributes() ?>><span id="el<?php echo $bsc_order_detail_list->RowCnt ?>_bsc_order_detail_quantity" class="control-group bsc_order_detail_quantity">
<span<?php echo $bsc_order_detail->quantity->ViewAttributes() ?>>
<?php echo $bsc_order_detail->quantity->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_detail_list->PageObjName . "_row_" . $bsc_order_detail_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order_detail->mc_gross->Visible) { // mc_gross ?>
		<td<?php echo $bsc_order_detail->mc_gross->CellAttributes() ?>><span id="el<?php echo $bsc_order_detail_list->RowCnt ?>_bsc_order_detail_mc_gross" class="control-group bsc_order_detail_mc_gross">
<span<?php echo $bsc_order_detail->mc_gross->ViewAttributes() ?>>
<?php echo $bsc_order_detail->mc_gross->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_detail_list->PageObjName . "_row_" . $bsc_order_detail_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_order_detail->item_price->Visible) { // item_price ?>
		<td<?php echo $bsc_order_detail->item_price->CellAttributes() ?>><span id="el<?php echo $bsc_order_detail_list->RowCnt ?>_bsc_order_detail_item_price" class="control-group bsc_order_detail_item_price">
<span<?php echo $bsc_order_detail->item_price->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_price->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_order_detail_list->PageObjName . "_row_" . $bsc_order_detail_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$bsc_order_detail_list->ListOptions->Render("body", "right", $bsc_order_detail_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($bsc_order_detail->CurrentAction <> "gridadd")
		$bsc_order_detail_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($bsc_order_detail->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($bsc_order_detail_list->Recordset)
	$bsc_order_detail_list->Recordset->Close();
?>
</td></tr></table>
<?php if ($bsc_order_detail->Export == "") { ?>
<script type="text/javascript">
fbsc_order_detaillistsrch.Init();
fbsc_order_detaillist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$bsc_order_detail_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($bsc_order_detail->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$bsc_order_detail_list->Page_Terminate();
?>
