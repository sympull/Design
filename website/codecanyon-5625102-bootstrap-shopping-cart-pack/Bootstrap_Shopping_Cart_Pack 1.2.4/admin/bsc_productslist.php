<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "bsc_productsinfo.php" ?>
<?php include_once "bsc_admininfo.php" ?>
<?php include_once "bsc_typesgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$bsc_products_list = NULL; // Initialize page object first

class cbsc_products_list extends cbsc_products {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_products';

	// Page object name
	var $PageObjName = 'bsc_products_list';

	// Grid form hidden field names
	var $FormName = 'fbsc_productslist';
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

		// Table object (bsc_products)
		if (!isset($GLOBALS["bsc_products"])) {
			$GLOBALS["bsc_products"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_products"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "bsc_productsadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "bsc_productsdelete.php";
		$this->MultiUpdateUrl = "bsc_productsupdate.php";

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_products', TRUE);

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
	var $bsc_types_Count;
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

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

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

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
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

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
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

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		$this->BuildSearchSql($sWhere, $this->id, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->img, FALSE); // img
		$this->BuildSearchSql($sWhere, $this->idCategory, FALSE); // idCategory
		$this->BuildSearchSql($sWhere, $this->productCode, FALSE); // productCode
		$this->BuildSearchSql($sWhere, $this->name, FALSE); // name
		$this->BuildSearchSql($sWhere, $this->description, FALSE); // description
		$this->BuildSearchSql($sWhere, $this->price, FALSE); // price
		$this->BuildSearchSql($sWhere, $this->price_offer, FALSE); // price_offer
		$this->BuildSearchSql($sWhere, $this->img_detail1, FALSE); // img_detail1
		$this->BuildSearchSql($sWhere, $this->img_detail2, FALSE); // img_detail2
		$this->BuildSearchSql($sWhere, $this->img_detail3, FALSE); // img_detail3
		$this->BuildSearchSql($sWhere, $this->download, FALSE); // download
		$this->BuildSearchSql($sWhere, $this->ordering, FALSE); // ordering
		$this->BuildSearchSql($sWhere, $this->visible, FALSE); // visible

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->img->AdvancedSearch->Save(); // img
			$this->idCategory->AdvancedSearch->Save(); // idCategory
			$this->productCode->AdvancedSearch->Save(); // productCode
			$this->name->AdvancedSearch->Save(); // name
			$this->description->AdvancedSearch->Save(); // description
			$this->price->AdvancedSearch->Save(); // price
			$this->price_offer->AdvancedSearch->Save(); // price_offer
			$this->img_detail1->AdvancedSearch->Save(); // img_detail1
			$this->img_detail2->AdvancedSearch->Save(); // img_detail2
			$this->img_detail3->AdvancedSearch->Save(); // img_detail3
			$this->download->AdvancedSearch->Save(); // download
			$this->ordering->AdvancedSearch->Save(); // ordering
			$this->visible->AdvancedSearch->Save(); // visible
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->img, $Keyword);
		if (is_numeric($Keyword)) $this->BuildBasicSearchSQL($sWhere, $this->idCategory, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->productCode, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->description, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->img_detail1, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->img_detail2, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->img_detail3, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->download, $Keyword);
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
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->img->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->idCategory->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->productCode->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->description->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->price->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->price_offer->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->img_detail1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->img_detail2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->img_detail3->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->download->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ordering->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->visible->AdvancedSearch->IssetSession())
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

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->id->AdvancedSearch->UnsetSession();
		$this->img->AdvancedSearch->UnsetSession();
		$this->idCategory->AdvancedSearch->UnsetSession();
		$this->productCode->AdvancedSearch->UnsetSession();
		$this->name->AdvancedSearch->UnsetSession();
		$this->description->AdvancedSearch->UnsetSession();
		$this->price->AdvancedSearch->UnsetSession();
		$this->price_offer->AdvancedSearch->UnsetSession();
		$this->img_detail1->AdvancedSearch->UnsetSession();
		$this->img_detail2->AdvancedSearch->UnsetSession();
		$this->img_detail3->AdvancedSearch->UnsetSession();
		$this->download->AdvancedSearch->UnsetSession();
		$this->ordering->AdvancedSearch->UnsetSession();
		$this->visible->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->img->AdvancedSearch->Load();
		$this->idCategory->AdvancedSearch->Load();
		$this->productCode->AdvancedSearch->Load();
		$this->name->AdvancedSearch->Load();
		$this->description->AdvancedSearch->Load();
		$this->price->AdvancedSearch->Load();
		$this->price_offer->AdvancedSearch->Load();
		$this->img_detail1->AdvancedSearch->Load();
		$this->img_detail2->AdvancedSearch->Load();
		$this->img_detail3->AdvancedSearch->Load();
		$this->download->AdvancedSearch->Load();
		$this->ordering->AdvancedSearch->Load();
		$this->visible->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->img); // img
			$this->UpdateSort($this->idCategory); // idCategory
			$this->UpdateSort($this->productCode); // productCode
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->price); // price
			$this->UpdateSort($this->price_offer); // price_offer
			$this->UpdateSort($this->img_detail1); // img_detail1
			$this->UpdateSort($this->img_detail2); // img_detail2
			$this->UpdateSort($this->img_detail3); // img_detail3
			$this->UpdateSort($this->download); // download
			$this->UpdateSort($this->ordering); // ordering
			$this->UpdateSort($this->visible); // visible
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
				$this->idCategory->setSort("ASC");
				$this->ordering->setSort("ASC");
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
				$this->img->setSort("");
				$this->idCategory->setSort("");
				$this->productCode->setSort("");
				$this->name->setSort("");
				$this->price->setSort("");
				$this->price_offer->setSort("");
				$this->img_detail1->setSort("");
				$this->img_detail2->setSort("");
				$this->img_detail3->setSort("");
				$this->download->setSort("");
				$this->ordering->setSort("");
				$this->visible->setSort("");
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

		// "detail_bsc_types"
		$item = &$this->ListOptions->Add("detail_bsc_types");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn() && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["bsc_types_grid"])) $GLOBALS["bsc_types_grid"] = new cbsc_types_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

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
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_bsc_types"
		$oListOpt = &$this->ListOptions->Items["detail_bsc_types"];
		if ($Security->IsLoggedIn()) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("bsc_types", "TblCaption");
			$body .= str_replace("%c", $this->bsc_types_Count, $Language->Phrase("DetailCount"));
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("bsc_typeslist.php?" . EW_TABLE_SHOW_MASTER . "=bsc_products&id=" . strval($this->id->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["bsc_types_grid"]->DetailEdit && $Security->IsLoggedIn() && $Security->IsLoggedIn()) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=bsc_types")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "bsc_types";
			}
			if ($GLOBALS["bsc_types_grid"]->DetailAdd && $Security->IsLoggedIn() && $Security->IsLoggedIn()) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=bsc_types")) . "\">" . $Language->Phrase("MasterDetailCopyLink") . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "bsc_types";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">" .
				"<a class=\"btn btn-small ewRowLink ewDetailView\" data-action=\"list\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . $body . "</a>";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . $Language->Phrase("MasterDetailCopyLink") . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\">&nbsp;<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}
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
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_bsc_types");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=bsc_types") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["bsc_types"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["bsc_types"]->DetailAdd && $Security->IsLoggedIn() && $Security->IsLoggedIn());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "bsc_types";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->IsLoggedIn());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fbsc_productslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id"]);
		if ($this->id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// img
		$this->img->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_img"]);
		if ($this->img->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->img->AdvancedSearch->SearchOperator = @$_GET["z_img"];

		// idCategory
		$this->idCategory->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_idCategory"]);
		if ($this->idCategory->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->idCategory->AdvancedSearch->SearchOperator = @$_GET["z_idCategory"];

		// productCode
		$this->productCode->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_productCode"]);
		if ($this->productCode->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->productCode->AdvancedSearch->SearchOperator = @$_GET["z_productCode"];

		// name
		$this->name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_name"]);
		if ($this->name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->name->AdvancedSearch->SearchOperator = @$_GET["z_name"];

		// description
		$this->description->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_description"]);
		if ($this->description->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->description->AdvancedSearch->SearchOperator = @$_GET["z_description"];

		// price
		$this->price->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_price"]);
		if ($this->price->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->price->AdvancedSearch->SearchOperator = @$_GET["z_price"];

		// price_offer
		$this->price_offer->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_price_offer"]);
		if ($this->price_offer->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->price_offer->AdvancedSearch->SearchOperator = @$_GET["z_price_offer"];

		// img_detail1
		$this->img_detail1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_img_detail1"]);
		if ($this->img_detail1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->img_detail1->AdvancedSearch->SearchOperator = @$_GET["z_img_detail1"];

		// img_detail2
		$this->img_detail2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_img_detail2"]);
		if ($this->img_detail2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->img_detail2->AdvancedSearch->SearchOperator = @$_GET["z_img_detail2"];

		// img_detail3
		$this->img_detail3->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_img_detail3"]);
		if ($this->img_detail3->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->img_detail3->AdvancedSearch->SearchOperator = @$_GET["z_img_detail3"];

		// download
		$this->download->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_download"]);
		if ($this->download->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->download->AdvancedSearch->SearchOperator = @$_GET["z_download"];

		// ordering
		$this->ordering->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ordering"]);
		if ($this->ordering->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ordering->AdvancedSearch->SearchOperator = @$_GET["z_ordering"];

		// visible
		$this->visible->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_visible"]);
		if ($this->visible->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->visible->AdvancedSearch->SearchOperator = @$_GET["z_visible"];
		if (is_array($this->visible->AdvancedSearch->SearchValue)) $this->visible->AdvancedSearch->SearchValue = implode(",", $this->visible->AdvancedSearch->SearchValue);
		if (is_array($this->visible->AdvancedSearch->SearchValue2)) $this->visible->AdvancedSearch->SearchValue2 = implode(",", $this->visible->AdvancedSearch->SearchValue2);
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
		if (!isset($GLOBALS["bsc_types_grid"])) $GLOBALS["bsc_types_grid"] = new cbsc_types_grid;
		$sDetailFilter = $GLOBALS["bsc_types"]->SqlDetailFilter_bsc_products();
		$sDetailFilter = str_replace("@idProduct@", ew_AdjustSql($this->id->DbValue), $sDetailFilter);
		$GLOBALS["bsc_types"]->setCurrentMasterTable("bsc_products");
		$sDetailFilter = $GLOBALS["bsc_types"]->ApplyUserIDFilters($sDetailFilter);
		$this->bsc_types_Count = $GLOBALS["bsc_types"]->LoadRecordCount($sDetailFilter);
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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// img
			$this->img->EditCustomAttributes = "";
			$this->img->EditValue = ew_HtmlEncode($this->img->AdvancedSearch->SearchValue);
			$this->img->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->img->FldCaption()));

			// idCategory
			$this->idCategory->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `bsc_category`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->idCategory, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->idCategory->EditValue = $arwrk;

			// productCode
			$this->productCode->EditCustomAttributes = "";
			$this->productCode->EditValue = ew_HtmlEncode($this->productCode->AdvancedSearch->SearchValue);
			$this->productCode->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->productCode->FldCaption()));

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->AdvancedSearch->SearchValue);
			$this->name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->name->FldCaption()));

			// price
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->AdvancedSearch->SearchValue);
			$this->price->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->price->FldCaption()));

			// price_offer
			$this->price_offer->EditCustomAttributes = "";
			$this->price_offer->EditValue = ew_HtmlEncode($this->price_offer->AdvancedSearch->SearchValue);
			$this->price_offer->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->price_offer->FldCaption()));

			// img_detail1
			$this->img_detail1->EditCustomAttributes = "";
			$this->img_detail1->EditValue = ew_HtmlEncode($this->img_detail1->AdvancedSearch->SearchValue);
			$this->img_detail1->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->img_detail1->FldCaption()));

			// img_detail2
			$this->img_detail2->EditCustomAttributes = "";
			$this->img_detail2->EditValue = ew_HtmlEncode($this->img_detail2->AdvancedSearch->SearchValue);
			$this->img_detail2->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->img_detail2->FldCaption()));

			// img_detail3
			$this->img_detail3->EditCustomAttributes = "";
			$this->img_detail3->EditValue = ew_HtmlEncode($this->img_detail3->AdvancedSearch->SearchValue);
			$this->img_detail3->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->img_detail3->FldCaption()));

			// download
			$this->download->EditCustomAttributes = "";
			$this->download->EditValue = ew_HtmlEncode($this->download->AdvancedSearch->SearchValue);
			$this->download->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->download->FldCaption()));

			// ordering
			$this->ordering->EditCustomAttributes = "";
			$this->ordering->EditValue = ew_HtmlEncode($this->ordering->AdvancedSearch->SearchValue);
			$this->ordering->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ordering->FldCaption()));

			// visible
			$this->visible->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->visible->FldTagValue(1), $this->visible->FldTagCaption(1) <> "" ? $this->visible->FldTagCaption(1) : $this->visible->FldTagValue(1));
			$this->visible->EditValue = $arwrk;
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id->AdvancedSearch->Load();
		$this->img->AdvancedSearch->Load();
		$this->idCategory->AdvancedSearch->Load();
		$this->productCode->AdvancedSearch->Load();
		$this->name->AdvancedSearch->Load();
		$this->description->AdvancedSearch->Load();
		$this->price->AdvancedSearch->Load();
		$this->price_offer->AdvancedSearch->Load();
		$this->img_detail1->AdvancedSearch->Load();
		$this->img_detail2->AdvancedSearch->Load();
		$this->img_detail3->AdvancedSearch->Load();
		$this->download->AdvancedSearch->Load();
		$this->ordering->AdvancedSearch->Load();
		$this->visible->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_bsc_products\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_bsc_products',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fbsc_productslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($bsc_products_list)) $bsc_products_list = new cbsc_products_list();

// Page init
$bsc_products_list->Page_Init();

// Page main
$bsc_products_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_products_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($bsc_products->Export == "") { ?>
<script type="text/javascript">

// Page object
var bsc_products_list = new ew_Page("bsc_products_list");
bsc_products_list.PageID = "list"; // Page ID
var EW_PAGE_ID = bsc_products_list.PageID; // For backward compatibility

// Form object
var fbsc_productslist = new ew_Form("fbsc_productslist");
fbsc_productslist.FormKeyCountName = '<?php echo $bsc_products_list->FormKeyCountName ?>';

// Form_CustomValidate event
fbsc_productslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_productslist.ValidateRequired = true;
<?php } else { ?>
fbsc_productslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fbsc_productslist.Lists["x_idCategory"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fbsc_productslistsrch = new ew_Form("fbsc_productslistsrch");

// Validate function for search
fbsc_productslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fbsc_productslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_productslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fbsc_productslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fbsc_productslistsrch.Lists["x_idCategory"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($bsc_products->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($bsc_products_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $bsc_products_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$bsc_products_list->TotalRecs = $bsc_products->SelectRecordCount();
	} else {
		if ($bsc_products_list->Recordset = $bsc_products_list->LoadRecordset())
			$bsc_products_list->TotalRecs = $bsc_products_list->Recordset->RecordCount();
	}
	$bsc_products_list->StartRec = 1;
	if ($bsc_products_list->DisplayRecs <= 0 || ($bsc_products->Export <> "" && $bsc_products->ExportAll)) // Display all records
		$bsc_products_list->DisplayRecs = $bsc_products_list->TotalRecs;
	if (!($bsc_products->Export <> "" && $bsc_products->ExportAll))
		$bsc_products_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$bsc_products_list->Recordset = $bsc_products_list->LoadRecordset($bsc_products_list->StartRec-1, $bsc_products_list->DisplayRecs);
$bsc_products_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($bsc_products->Export == "" && $bsc_products->CurrentAction == "") { ?>
<form name="fbsc_productslistsrch" id="fbsc_productslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fbsc_productslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fbsc_productslistsrch_SearchGroup" href="#fbsc_productslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fbsc_productslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fbsc_productslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="bsc_products">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$bsc_products_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$bsc_products->RowType = EW_ROWTYPE_SEARCH;

// Render row
$bsc_products->ResetAttrs();
$bsc_products_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($bsc_products->idCategory->Visible) { // idCategory ?>
	<span id="xsc_idCategory" class="ewCell">
		<span class="ewSearchCaption"><?php echo $bsc_products->idCategory->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_idCategory" id="z_idCategory" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_idCategory" id="x_idCategory" name="x_idCategory"<?php echo $bsc_products->idCategory->EditAttributes() ?>>
<?php
if (is_array($bsc_products->idCategory->EditValue)) {
	$arwrk = $bsc_products->idCategory->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($bsc_products->idCategory->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fbsc_productslistsrch.Lists["x_idCategory"].Options = <?php echo (is_array($bsc_products->idCategory->EditValue)) ? ew_ArrayToJson($bsc_products->idCategory->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($bsc_products_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $bsc_products_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_3" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($bsc_products_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($bsc_products_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($bsc_products_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $bsc_products_list->ShowPageHeader(); ?>
<?php
$bsc_products_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($bsc_products->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($bsc_products->CurrentAction <> "gridadd" && $bsc_products->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($bsc_products_list->Pager)) $bsc_products_list->Pager = new cPrevNextPager($bsc_products_list->StartRec, $bsc_products_list->DisplayRecs, $bsc_products_list->TotalRecs) ?>
<?php if ($bsc_products_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($bsc_products_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_products_list->PageUrl() ?>start=<?php echo $bsc_products_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($bsc_products_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_products_list->PageUrl() ?>start=<?php echo $bsc_products_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $bsc_products_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($bsc_products_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_products_list->PageUrl() ?>start=<?php echo $bsc_products_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($bsc_products_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_products_list->PageUrl() ?>start=<?php echo $bsc_products_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $bsc_products_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $bsc_products_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $bsc_products_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $bsc_products_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($bsc_products_list->SearchWhere == "0=101") { ?>
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
	foreach ($bsc_products_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="fbsc_productslist" id="fbsc_productslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_products">
<div id="gmp_bsc_products" class="ewGridMiddlePanel">
<?php if ($bsc_products_list->TotalRecs > 0) { ?>
<table id="tbl_bsc_productslist" class="ewTable ewTableSeparate">
<?php echo $bsc_products->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$bsc_products_list->RenderListOptions();

// Render list options (header, left)
$bsc_products_list->ListOptions->Render("header", "left");
?>
<?php if ($bsc_products->img->Visible) { // img ?>
	<?php if ($bsc_products->SortUrl($bsc_products->img) == "") { ?>
		<td><div id="elh_bsc_products_img" class="bsc_products_img"><div class="ewTableHeaderCaption"><?php echo $bsc_products->img->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->img) ?>',1);"><div id="elh_bsc_products_img" class="bsc_products_img">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->img->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->img->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->img->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_products->idCategory->Visible) { // idCategory ?>
	<?php if ($bsc_products->SortUrl($bsc_products->idCategory) == "") { ?>
		<td><div id="elh_bsc_products_idCategory" class="bsc_products_idCategory"><div class="ewTableHeaderCaption"><?php echo $bsc_products->idCategory->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->idCategory) ?>',1);"><div id="elh_bsc_products_idCategory" class="bsc_products_idCategory">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->idCategory->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->idCategory->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->idCategory->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_products->productCode->Visible) { // productCode ?>
	<?php if ($bsc_products->SortUrl($bsc_products->productCode) == "") { ?>
		<td><div id="elh_bsc_products_productCode" class="bsc_products_productCode"><div class="ewTableHeaderCaption"><?php echo $bsc_products->productCode->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->productCode) ?>',1);"><div id="elh_bsc_products_productCode" class="bsc_products_productCode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->productCode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->productCode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->productCode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_products->name->Visible) { // name ?>
	<?php if ($bsc_products->SortUrl($bsc_products->name) == "") { ?>
		<td><div id="elh_bsc_products_name" class="bsc_products_name"><div class="ewTableHeaderCaption"><?php echo $bsc_products->name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->name) ?>',1);"><div id="elh_bsc_products_name" class="bsc_products_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_products->price->Visible) { // price ?>
	<?php if ($bsc_products->SortUrl($bsc_products->price) == "") { ?>
		<td><div id="elh_bsc_products_price" class="bsc_products_price"><div class="ewTableHeaderCaption"><?php echo $bsc_products->price->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->price) ?>',1);"><div id="elh_bsc_products_price" class="bsc_products_price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_products->price_offer->Visible) { // price_offer ?>
	<?php if ($bsc_products->SortUrl($bsc_products->price_offer) == "") { ?>
		<td><div id="elh_bsc_products_price_offer" class="bsc_products_price_offer"><div class="ewTableHeaderCaption"><?php echo $bsc_products->price_offer->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->price_offer) ?>',1);"><div id="elh_bsc_products_price_offer" class="bsc_products_price_offer">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->price_offer->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->price_offer->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->price_offer->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_products->img_detail1->Visible) { // img_detail1 ?>
	<?php if ($bsc_products->SortUrl($bsc_products->img_detail1) == "") { ?>
		<td><div id="elh_bsc_products_img_detail1" class="bsc_products_img_detail1"><div class="ewTableHeaderCaption"><?php echo $bsc_products->img_detail1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->img_detail1) ?>',1);"><div id="elh_bsc_products_img_detail1" class="bsc_products_img_detail1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->img_detail1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->img_detail1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->img_detail1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_products->img_detail2->Visible) { // img_detail2 ?>
	<?php if ($bsc_products->SortUrl($bsc_products->img_detail2) == "") { ?>
		<td><div id="elh_bsc_products_img_detail2" class="bsc_products_img_detail2"><div class="ewTableHeaderCaption"><?php echo $bsc_products->img_detail2->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->img_detail2) ?>',1);"><div id="elh_bsc_products_img_detail2" class="bsc_products_img_detail2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->img_detail2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->img_detail2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->img_detail2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_products->img_detail3->Visible) { // img_detail3 ?>
	<?php if ($bsc_products->SortUrl($bsc_products->img_detail3) == "") { ?>
		<td><div id="elh_bsc_products_img_detail3" class="bsc_products_img_detail3"><div class="ewTableHeaderCaption"><?php echo $bsc_products->img_detail3->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->img_detail3) ?>',1);"><div id="elh_bsc_products_img_detail3" class="bsc_products_img_detail3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->img_detail3->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->img_detail3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->img_detail3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_products->download->Visible) { // download ?>
	<?php if ($bsc_products->SortUrl($bsc_products->download) == "") { ?>
		<td><div id="elh_bsc_products_download" class="bsc_products_download"><div class="ewTableHeaderCaption"><?php echo $bsc_products->download->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->download) ?>',1);"><div id="elh_bsc_products_download" class="bsc_products_download">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->download->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->download->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->download->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_products->ordering->Visible) { // ordering ?>
	<?php if ($bsc_products->SortUrl($bsc_products->ordering) == "") { ?>
		<td><div id="elh_bsc_products_ordering" class="bsc_products_ordering"><div class="ewTableHeaderCaption"><?php echo $bsc_products->ordering->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->ordering) ?>',1);"><div id="elh_bsc_products_ordering" class="bsc_products_ordering">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->ordering->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->ordering->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->ordering->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_products->visible->Visible) { // visible ?>
	<?php if ($bsc_products->SortUrl($bsc_products->visible) == "") { ?>
		<td><div id="elh_bsc_products_visible" class="bsc_products_visible"><div class="ewTableHeaderCaption"><?php echo $bsc_products->visible->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_products->SortUrl($bsc_products->visible) ?>',1);"><div id="elh_bsc_products_visible" class="bsc_products_visible">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_products->visible->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_products->visible->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_products->visible->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$bsc_products_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($bsc_products->ExportAll && $bsc_products->Export <> "") {
	$bsc_products_list->StopRec = $bsc_products_list->TotalRecs;
} else {

	// Set the last record to display
	if ($bsc_products_list->TotalRecs > $bsc_products_list->StartRec + $bsc_products_list->DisplayRecs - 1)
		$bsc_products_list->StopRec = $bsc_products_list->StartRec + $bsc_products_list->DisplayRecs - 1;
	else
		$bsc_products_list->StopRec = $bsc_products_list->TotalRecs;
}
$bsc_products_list->RecCnt = $bsc_products_list->StartRec - 1;
if ($bsc_products_list->Recordset && !$bsc_products_list->Recordset->EOF) {
	$bsc_products_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $bsc_products_list->StartRec > 1)
		$bsc_products_list->Recordset->Move($bsc_products_list->StartRec - 1);
} elseif (!$bsc_products->AllowAddDeleteRow && $bsc_products_list->StopRec == 0) {
	$bsc_products_list->StopRec = $bsc_products->GridAddRowCount;
}

// Initialize aggregate
$bsc_products->RowType = EW_ROWTYPE_AGGREGATEINIT;
$bsc_products->ResetAttrs();
$bsc_products_list->RenderRow();
while ($bsc_products_list->RecCnt < $bsc_products_list->StopRec) {
	$bsc_products_list->RecCnt++;
	if (intval($bsc_products_list->RecCnt) >= intval($bsc_products_list->StartRec)) {
		$bsc_products_list->RowCnt++;

		// Set up key count
		$bsc_products_list->KeyCount = $bsc_products_list->RowIndex;

		// Init row class and style
		$bsc_products->ResetAttrs();
		$bsc_products->CssClass = "";
		if ($bsc_products->CurrentAction == "gridadd") {
		} else {
			$bsc_products_list->LoadRowValues($bsc_products_list->Recordset); // Load row values
		}
		$bsc_products->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$bsc_products->RowAttrs = array_merge($bsc_products->RowAttrs, array('data-rowindex'=>$bsc_products_list->RowCnt, 'id'=>'r' . $bsc_products_list->RowCnt . '_bsc_products', 'data-rowtype'=>$bsc_products->RowType));

		// Render row
		$bsc_products_list->RenderRow();

		// Render list options
		$bsc_products_list->RenderListOptions();
?>
	<tr<?php echo $bsc_products->RowAttributes() ?>>
<?php

// Render list options (body, left)
$bsc_products_list->ListOptions->Render("body", "left", $bsc_products_list->RowCnt);
?>
	<?php if ($bsc_products->img->Visible) { // img ?>
		<td<?php echo $bsc_products->img->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_img" class="control-group bsc_products_img">
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
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_products->idCategory->Visible) { // idCategory ?>
		<td<?php echo $bsc_products->idCategory->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_idCategory" class="control-group bsc_products_idCategory">
<span<?php echo $bsc_products->idCategory->ViewAttributes() ?>>
<?php echo $bsc_products->idCategory->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_products->productCode->Visible) { // productCode ?>
		<td<?php echo $bsc_products->productCode->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_productCode" class="control-group bsc_products_productCode">
<span<?php echo $bsc_products->productCode->ViewAttributes() ?>>
<?php echo $bsc_products->productCode->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_products->name->Visible) { // name ?>
		<td<?php echo $bsc_products->name->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_name" class="control-group bsc_products_name">
<span<?php echo $bsc_products->name->ViewAttributes() ?>>
<?php echo $bsc_products->name->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_products->price->Visible) { // price ?>
		<td<?php echo $bsc_products->price->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_price" class="control-group bsc_products_price">
<span<?php echo $bsc_products->price->ViewAttributes() ?>>
<?php echo $bsc_products->price->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_products->price_offer->Visible) { // price_offer ?>
		<td<?php echo $bsc_products->price_offer->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_price_offer" class="control-group bsc_products_price_offer">
<span<?php echo $bsc_products->price_offer->ViewAttributes() ?>>
<?php echo $bsc_products->price_offer->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_products->img_detail1->Visible) { // img_detail1 ?>
		<td<?php echo $bsc_products->img_detail1->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_img_detail1" class="control-group bsc_products_img_detail1">
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
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_products->img_detail2->Visible) { // img_detail2 ?>
		<td<?php echo $bsc_products->img_detail2->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_img_detail2" class="control-group bsc_products_img_detail2">
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
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_products->img_detail3->Visible) { // img_detail3 ?>
		<td<?php echo $bsc_products->img_detail3->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_img_detail3" class="control-group bsc_products_img_detail3">
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
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_products->download->Visible) { // download ?>
		<td<?php echo $bsc_products->download->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_download" class="control-group bsc_products_download">
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
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_products->ordering->Visible) { // ordering ?>
		<td<?php echo $bsc_products->ordering->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_ordering" class="control-group bsc_products_ordering">
<span<?php echo $bsc_products->ordering->ViewAttributes() ?>>
<?php echo $bsc_products->ordering->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_products->visible->Visible) { // visible ?>
		<td<?php echo $bsc_products->visible->CellAttributes() ?>><span id="el<?php echo $bsc_products_list->RowCnt ?>_bsc_products_visible" class="control-group bsc_products_visible">
<span<?php echo $bsc_products->visible->ViewAttributes() ?>>
<?php echo $bsc_products->visible->ListViewValue() ?></span>
</span><a id="<?php echo $bsc_products_list->PageObjName . "_row_" . $bsc_products_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$bsc_products_list->ListOptions->Render("body", "right", $bsc_products_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($bsc_products->CurrentAction <> "gridadd")
		$bsc_products_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($bsc_products->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($bsc_products_list->Recordset)
	$bsc_products_list->Recordset->Close();
?>
</td></tr></table>
<?php if ($bsc_products->Export == "") { ?>
<script type="text/javascript">
fbsc_productslistsrch.Init();
fbsc_productslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$bsc_products_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($bsc_products->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$bsc_products_list->Page_Terminate();
?>
