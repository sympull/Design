<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "bsc_typesinfo.php" ?>
<?php include_once "bsc_productsinfo.php" ?>
<?php include_once "bsc_admininfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$bsc_types_list = NULL; // Initialize page object first

class cbsc_types_list extends cbsc_types {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_types';

	// Page object name
	var $PageObjName = 'bsc_types_list';

	// Grid form hidden field names
	var $FormName = 'fbsc_typeslist';
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

		// Table object (bsc_types)
		if (!isset($GLOBALS["bsc_types"])) {
			$GLOBALS["bsc_types"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_types"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "bsc_typesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "bsc_typesdelete.php";
		$this->MultiUpdateUrl = "bsc_typesupdate.php";

		// Table object (bsc_products)
		if (!isset($GLOBALS['bsc_products'])) $GLOBALS['bsc_products'] = new cbsc_products();

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_types', TRUE);

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

		// Create form object
		$objForm = new cFormObj();

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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$this->GridUpdate();
						} else {
							$this->setFailureMessage($gsFormError);
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$this->GridInsert();
						} else {
							$this->setFailureMessage($gsFormError);
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "bsc_products") {
			global $bsc_products;
			$rsmaster = $bsc_products->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("bsc_productslist.php"); // Return to master page
			} else {
				$bsc_products->LoadListRowValues($rsmaster);
				$bsc_products->RowType = EW_ROWTYPE_MASTER; // Master row
				$bsc_products->RenderListRow();
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

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("id", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		$bInlineEdit = TRUE;
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("id", $this->id->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue("k_key"));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("id")) <> strval($this->id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		$this->CurrentAction = "add";
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Perform update to grid
	function GridUpdate() {
		global $conn, $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Begin transaction
		$conn->BeginTrans();

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		$sSql = $this->SQL();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
		}
		return $bGridUpdate;
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

	// Perform Grid Add
	function GridInsert() {
		global $conn, $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->id->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "gridadd"; // Stay in gridadd mode
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_name") && $objForm->HasValue("o_name") && $this->name->CurrentValue <> $this->name->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_price") && $objForm->HasValue("o_price") && $this->price->CurrentValue <> $this->price->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_price_offer") && $objForm->HasValue("o_price_offer") && $this->price_offer->CurrentValue <> $this->price_offer->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_ordering") && $objForm->HasValue("o_ordering") && $this->ordering->CurrentValue <> $this->ordering->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->name, $Keyword);
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
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->price); // price
			$this->UpdateSort($this->price_offer); // price_offer
			$this->UpdateSort($this->ordering); // ordering
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->idProduct->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->name->setSort("");
				$this->price->setSort("");
				$this->price_offer->setSort("");
				$this->ordering->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

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

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
			}
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\">";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<span class=\"ewSeparator\">&nbsp;|&nbsp;</span>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
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
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->id->CurrentValue . "\">";
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

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->IsLoggedIn());
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->IsLoggedIn());

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->IsLoggedIn());
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
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fbsc_typeslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->IsLoggedIn();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->IsLoggedIn();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
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

	// Load default values
	function LoadDefaultValues() {
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->price->CurrentValue = NULL;
		$this->price->OldValue = $this->price->CurrentValue;
		$this->price_offer->CurrentValue = NULL;
		$this->price_offer->OldValue = $this->price_offer->CurrentValue;
		$this->ordering->CurrentValue = NULL;
		$this->ordering->OldValue = $this->ordering->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		$this->name->setOldValue($objForm->GetValue("o_name"));
		if (!$this->price->FldIsDetailKey) {
			$this->price->setFormValue($objForm->GetValue("x_price"));
		}
		$this->price->setOldValue($objForm->GetValue("o_price"));
		if (!$this->price_offer->FldIsDetailKey) {
			$this->price_offer->setFormValue($objForm->GetValue("x_price_offer"));
		}
		$this->price_offer->setOldValue($objForm->GetValue("o_price_offer"));
		if (!$this->ordering->FldIsDetailKey) {
			$this->ordering->setFormValue($objForm->GetValue("x_ordering"));
		}
		$this->ordering->setOldValue($objForm->GetValue("o_ordering"));
		if (!$this->id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->CurrentValue = $this->id->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
		$this->price_offer->CurrentValue = $this->price_offer->FormValue;
		$this->ordering->CurrentValue = $this->ordering->FormValue;
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
		$this->idProduct->setDbValue($rs->fields('idProduct'));
		$this->name->setDbValue($rs->fields('name'));
		$this->price->setDbValue($rs->fields('price'));
		$this->price_offer->setDbValue($rs->fields('price_offer'));
		$this->ordering->setDbValue($rs->fields('ordering'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->idProduct->DbValue = $row['idProduct'];
		$this->name->DbValue = $row['name'];
		$this->price->DbValue = $row['price'];
		$this->price_offer->DbValue = $row['price_offer'];
		$this->ordering->DbValue = $row['ordering'];
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

		// idProduct
		$this->idProduct->CellCssStyle = "white-space: nowrap;";

		// name
		// price
		// price_offer
		// ordering

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// price
			$this->price->ViewValue = $this->price->CurrentValue;
			$this->price->ViewCustomAttributes = "";

			// price_offer
			$this->price_offer->ViewValue = $this->price_offer->CurrentValue;
			$this->price_offer->ViewCustomAttributes = "";

			// ordering
			$this->ordering->ViewValue = $this->ordering->CurrentValue;
			$this->ordering->ViewCustomAttributes = "";

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

			// ordering
			$this->ordering->LinkCustomAttributes = "";
			$this->ordering->HrefValue = "";
			$this->ordering->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->name->FldCaption()));

			// price
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->CurrentValue);
			$this->price->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->price->FldCaption()));
			if (strval($this->price->EditValue) <> "" && is_numeric($this->price->EditValue)) {
			$this->price->EditValue = ew_FormatNumber($this->price->EditValue, -2, -1, -2, 0);
			$this->price->OldValue = $this->price->EditValue;
			}

			// price_offer
			$this->price_offer->EditCustomAttributes = "";
			$this->price_offer->EditValue = ew_HtmlEncode($this->price_offer->CurrentValue);
			$this->price_offer->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->price_offer->FldCaption()));
			if (strval($this->price_offer->EditValue) <> "" && is_numeric($this->price_offer->EditValue)) {
			$this->price_offer->EditValue = ew_FormatNumber($this->price_offer->EditValue, -2, -1, -2, 0);
			$this->price_offer->OldValue = $this->price_offer->EditValue;
			}

			// ordering
			$this->ordering->EditCustomAttributes = "";
			$this->ordering->EditValue = ew_HtmlEncode($this->ordering->CurrentValue);
			$this->ordering->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ordering->FldCaption()));

			// Edit refer script
			// name

			$this->name->HrefValue = "";

			// price
			$this->price->HrefValue = "";

			// price_offer
			$this->price_offer->HrefValue = "";

			// ordering
			$this->ordering->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->name->FldCaption()));

			// price
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->CurrentValue);
			$this->price->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->price->FldCaption()));
			if (strval($this->price->EditValue) <> "" && is_numeric($this->price->EditValue)) {
			$this->price->EditValue = ew_FormatNumber($this->price->EditValue, -2, -1, -2, 0);
			$this->price->OldValue = $this->price->EditValue;
			}

			// price_offer
			$this->price_offer->EditCustomAttributes = "";
			$this->price_offer->EditValue = ew_HtmlEncode($this->price_offer->CurrentValue);
			$this->price_offer->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->price_offer->FldCaption()));
			if (strval($this->price_offer->EditValue) <> "" && is_numeric($this->price_offer->EditValue)) {
			$this->price_offer->EditValue = ew_FormatNumber($this->price_offer->EditValue, -2, -1, -2, 0);
			$this->price_offer->OldValue = $this->price_offer->EditValue;
			}

			// ordering
			$this->ordering->EditCustomAttributes = "";
			$this->ordering->EditValue = ew_HtmlEncode($this->ordering->CurrentValue);
			$this->ordering->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ordering->FldCaption()));

			// Edit refer script
			// name

			$this->name->HrefValue = "";

			// price
			$this->price->HrefValue = "";

			// price_offer
			$this->price_offer->HrefValue = "";

			// ordering
			$this->ordering->HrefValue = "";
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
		if (!ew_CheckNumber($this->price->FormValue)) {
			ew_AddMessage($gsFormError, $this->price->FldErrMsg());
		}
		if (!ew_CheckNumber($this->price_offer->FormValue)) {
			ew_AddMessage($gsFormError, $this->price_offer->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ordering->FormValue)) {
			ew_AddMessage($gsFormError, $this->ordering->FldErrMsg());
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
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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

			// name
			$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, $this->name->ReadOnly);

			// price
			$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, NULL, $this->price->ReadOnly);

			// price_offer
			$this->price_offer->SetDbValueDef($rsnew, $this->price_offer->CurrentValue, NULL, $this->price_offer->ReadOnly);

			// ordering
			$this->ordering->SetDbValueDef($rsnew, $this->ordering->CurrentValue, NULL, $this->ordering->ReadOnly);

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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, FALSE);

		// price
		$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, NULL, FALSE);

		// price_offer
		$this->price_offer->SetDbValueDef($rsnew, $this->price_offer->CurrentValue, NULL, FALSE);

		// ordering
		$this->ordering->SetDbValueDef($rsnew, $this->ordering->CurrentValue, NULL, FALSE);

		// idProduct
		if ($this->idProduct->getSessionValue() <> "") {
			$rsnew['idProduct'] = $this->idProduct->getSessionValue();
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
		$item->Body = "<a id=\"emf_bsc_types\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_bsc_types',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fbsc_typeslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "bsc_products") {
			global $bsc_products;
			$rsmaster = $bsc_products->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $ExportDoc->Style;
				$ExportDoc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$bsc_products->ExportDocument($ExportDoc, $rsmaster, 1, 1);
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
			if ($sMasterTblVar == "bsc_products") {
				$bValidMaster = TRUE;
				if (@$_GET["id"] <> "") {
					$GLOBALS["bsc_products"]->id->setQueryStringValue($_GET["id"]);
					$this->idProduct->setQueryStringValue($GLOBALS["bsc_products"]->id->QueryStringValue);
					$this->idProduct->setSessionValue($this->idProduct->QueryStringValue);
					if (!is_numeric($GLOBALS["bsc_products"]->id->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "bsc_products") {
				if ($this->idProduct->QueryStringValue == "") $this->idProduct->setSessionValue("");
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
if (!isset($bsc_types_list)) $bsc_types_list = new cbsc_types_list();

// Page init
$bsc_types_list->Page_Init();

// Page main
$bsc_types_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_types_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($bsc_types->Export == "") { ?>
<script type="text/javascript">

// Page object
var bsc_types_list = new ew_Page("bsc_types_list");
bsc_types_list.PageID = "list"; // Page ID
var EW_PAGE_ID = bsc_types_list.PageID; // For backward compatibility

// Form object
var fbsc_typeslist = new ew_Form("fbsc_typeslist");
fbsc_typeslist.FormKeyCountName = '<?php echo $bsc_types_list->FormKeyCountName ?>';

// Validate form
fbsc_typeslist.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_types->price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_price_offer");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_types->price_offer->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ordering");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_types->ordering->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
fbsc_typeslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "name", false)) return false;
	if (ew_ValueChanged(fobj, infix, "price", false)) return false;
	if (ew_ValueChanged(fobj, infix, "price_offer", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ordering", false)) return false;
	return true;
}

// Form_CustomValidate event
fbsc_typeslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_typeslist.ValidateRequired = true;
<?php } else { ?>
fbsc_typeslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fbsc_typeslistsrch = new ew_Form("fbsc_typeslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($bsc_types->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($bsc_types->getCurrentMasterTable() == "" && $bsc_types_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $bsc_types_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($bsc_types->Export == "") || (EW_EXPORT_MASTER_RECORD && $bsc_types->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "bsc_productslist.php";
if ($bsc_types_list->DbMasterFilter <> "" && $bsc_types->getCurrentMasterTable() == "bsc_products") {
	if ($bsc_types_list->MasterRecordExists) {
		if ($bsc_types->getCurrentMasterTable() == $bsc_types->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($bsc_types_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $bsc_types_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "bsc_productsmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
if ($bsc_types->CurrentAction == "gridadd") {
	$bsc_types->CurrentFilter = "0=1";
	$bsc_types_list->StartRec = 1;
	$bsc_types_list->DisplayRecs = $bsc_types->GridAddRowCount;
	$bsc_types_list->TotalRecs = $bsc_types_list->DisplayRecs;
	$bsc_types_list->StopRec = $bsc_types_list->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$bsc_types_list->TotalRecs = $bsc_types->SelectRecordCount();
	} else {
		if ($bsc_types_list->Recordset = $bsc_types_list->LoadRecordset())
			$bsc_types_list->TotalRecs = $bsc_types_list->Recordset->RecordCount();
	}
	$bsc_types_list->StartRec = 1;
	if ($bsc_types_list->DisplayRecs <= 0 || ($bsc_types->Export <> "" && $bsc_types->ExportAll)) // Display all records
		$bsc_types_list->DisplayRecs = $bsc_types_list->TotalRecs;
	if (!($bsc_types->Export <> "" && $bsc_types->ExportAll))
		$bsc_types_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$bsc_types_list->Recordset = $bsc_types_list->LoadRecordset($bsc_types_list->StartRec-1, $bsc_types_list->DisplayRecs);
}
$bsc_types_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($bsc_types->Export == "" && $bsc_types->CurrentAction == "") { ?>
<form name="fbsc_typeslistsrch" id="fbsc_typeslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fbsc_typeslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fbsc_typeslistsrch_SearchGroup" href="#fbsc_typeslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fbsc_typeslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fbsc_typeslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="bsc_types">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($bsc_types_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $bsc_types_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($bsc_types_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($bsc_types_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($bsc_types_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $bsc_types_list->ShowPageHeader(); ?>
<?php
$bsc_types_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($bsc_types->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($bsc_types->CurrentAction <> "gridadd" && $bsc_types->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($bsc_types_list->Pager)) $bsc_types_list->Pager = new cPrevNextPager($bsc_types_list->StartRec, $bsc_types_list->DisplayRecs, $bsc_types_list->TotalRecs) ?>
<?php if ($bsc_types_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($bsc_types_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_types_list->PageUrl() ?>start=<?php echo $bsc_types_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($bsc_types_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_types_list->PageUrl() ?>start=<?php echo $bsc_types_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $bsc_types_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($bsc_types_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_types_list->PageUrl() ?>start=<?php echo $bsc_types_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($bsc_types_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" type="button" href="<?php echo $bsc_types_list->PageUrl() ?>start=<?php echo $bsc_types_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" type="button" disabled="disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $bsc_types_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $bsc_types_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $bsc_types_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $bsc_types_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($bsc_types_list->SearchWhere == "0=101") { ?>
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
	foreach ($bsc_types_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="fbsc_typeslist" id="fbsc_typeslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_types">
<div id="gmp_bsc_types" class="ewGridMiddlePanel">
<?php if ($bsc_types_list->TotalRecs > 0 || $bsc_types->CurrentAction == "add" || $bsc_types->CurrentAction == "copy") { ?>
<table id="tbl_bsc_typeslist" class="ewTable ewTableSeparate">
<?php echo $bsc_types->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$bsc_types_list->RenderListOptions();

// Render list options (header, left)
$bsc_types_list->ListOptions->Render("header", "left");
?>
<?php if ($bsc_types->name->Visible) { // name ?>
	<?php if ($bsc_types->SortUrl($bsc_types->name) == "") { ?>
		<td><div id="elh_bsc_types_name" class="bsc_types_name"><div class="ewTableHeaderCaption"><?php echo $bsc_types->name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_types->SortUrl($bsc_types->name) ?>',1);"><div id="elh_bsc_types_name" class="bsc_types_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_types->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($bsc_types->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_types->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_types->price->Visible) { // price ?>
	<?php if ($bsc_types->SortUrl($bsc_types->price) == "") { ?>
		<td><div id="elh_bsc_types_price" class="bsc_types_price"><div class="ewTableHeaderCaption"><?php echo $bsc_types->price->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_types->SortUrl($bsc_types->price) ?>',1);"><div id="elh_bsc_types_price" class="bsc_types_price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_types->price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_types->price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_types->price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_types->price_offer->Visible) { // price_offer ?>
	<?php if ($bsc_types->SortUrl($bsc_types->price_offer) == "") { ?>
		<td><div id="elh_bsc_types_price_offer" class="bsc_types_price_offer"><div class="ewTableHeaderCaption"><?php echo $bsc_types->price_offer->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_types->SortUrl($bsc_types->price_offer) ?>',1);"><div id="elh_bsc_types_price_offer" class="bsc_types_price_offer">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_types->price_offer->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_types->price_offer->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_types->price_offer->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($bsc_types->ordering->Visible) { // ordering ?>
	<?php if ($bsc_types->SortUrl($bsc_types->ordering) == "") { ?>
		<td><div id="elh_bsc_types_ordering" class="bsc_types_ordering"><div class="ewTableHeaderCaption"><?php echo $bsc_types->ordering->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $bsc_types->SortUrl($bsc_types->ordering) ?>',1);"><div id="elh_bsc_types_ordering" class="bsc_types_ordering">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $bsc_types->ordering->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($bsc_types->ordering->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($bsc_types->ordering->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$bsc_types_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($bsc_types->CurrentAction == "add" || $bsc_types->CurrentAction == "copy") {
		$bsc_types_list->RowIndex = 0;
		$bsc_types_list->KeyCount = $bsc_types_list->RowIndex;
		if ($bsc_types->CurrentAction == "add")
			$bsc_types_list->LoadDefaultValues();
		if ($bsc_types->EventCancelled) // Insert failed
			$bsc_types_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$bsc_types->ResetAttrs();
		$bsc_types->RowAttrs = array_merge($bsc_types->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_bsc_types', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$bsc_types->RowType = EW_ROWTYPE_ADD;

		// Render row
		$bsc_types_list->RenderRow();

		// Render list options
		$bsc_types_list->RenderListOptions();
		$bsc_types_list->StartRowCnt = 0;
?>
	<tr<?php echo $bsc_types->RowAttributes() ?>>
<?php

// Render list options (body, left)
$bsc_types_list->ListOptions->Render("body", "left", $bsc_types_list->RowCnt);
?>
	<?php if ($bsc_types->name->Visible) { // name ?>
		<td><span id="el<?php echo $bsc_types_list->RowCnt ?>_bsc_types_name" class="control-group bsc_types_name">
<input type="text" data-field="x_name" name="x<?php echo $bsc_types_list->RowIndex ?>_name" id="x<?php echo $bsc_types_list->RowIndex ?>_name" size="200" maxlength="255" placeholder="<?php echo $bsc_types->name->PlaceHolder ?>" value="<?php echo $bsc_types->name->EditValue ?>"<?php echo $bsc_types->name->EditAttributes() ?>>
<input type="hidden" data-field="x_name" name="o<?php echo $bsc_types_list->RowIndex ?>_name" id="o<?php echo $bsc_types_list->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($bsc_types->name->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_types->price->Visible) { // price ?>
		<td><span id="el<?php echo $bsc_types_list->RowCnt ?>_bsc_types_price" class="control-group bsc_types_price">
<input type="text" data-field="x_price" name="x<?php echo $bsc_types_list->RowIndex ?>_price" id="x<?php echo $bsc_types_list->RowIndex ?>_price" size="30" placeholder="<?php echo $bsc_types->price->PlaceHolder ?>" value="<?php echo $bsc_types->price->EditValue ?>"<?php echo $bsc_types->price->EditAttributes() ?>>
<input type="hidden" data-field="x_price" name="o<?php echo $bsc_types_list->RowIndex ?>_price" id="o<?php echo $bsc_types_list->RowIndex ?>_price" value="<?php echo ew_HtmlEncode($bsc_types->price->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_types->price_offer->Visible) { // price_offer ?>
		<td><span id="el<?php echo $bsc_types_list->RowCnt ?>_bsc_types_price_offer" class="control-group bsc_types_price_offer">
<input type="text" data-field="x_price_offer" name="x<?php echo $bsc_types_list->RowIndex ?>_price_offer" id="x<?php echo $bsc_types_list->RowIndex ?>_price_offer" size="30" placeholder="<?php echo $bsc_types->price_offer->PlaceHolder ?>" value="<?php echo $bsc_types->price_offer->EditValue ?>"<?php echo $bsc_types->price_offer->EditAttributes() ?>>
<input type="hidden" data-field="x_price_offer" name="o<?php echo $bsc_types_list->RowIndex ?>_price_offer" id="o<?php echo $bsc_types_list->RowIndex ?>_price_offer" value="<?php echo ew_HtmlEncode($bsc_types->price_offer->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_types->ordering->Visible) { // ordering ?>
		<td><span id="el<?php echo $bsc_types_list->RowCnt ?>_bsc_types_ordering" class="control-group bsc_types_ordering">
<input type="text" data-field="x_ordering" name="x<?php echo $bsc_types_list->RowIndex ?>_ordering" id="x<?php echo $bsc_types_list->RowIndex ?>_ordering" size="30" placeholder="<?php echo $bsc_types->ordering->PlaceHolder ?>" value="<?php echo $bsc_types->ordering->EditValue ?>"<?php echo $bsc_types->ordering->EditAttributes() ?>>
<input type="hidden" data-field="x_ordering" name="o<?php echo $bsc_types_list->RowIndex ?>_ordering" id="o<?php echo $bsc_types_list->RowIndex ?>_ordering" value="<?php echo ew_HtmlEncode($bsc_types->ordering->OldValue) ?>">
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$bsc_types_list->ListOptions->Render("body", "right", $bsc_types_list->RowCnt);
?>
<script type="text/javascript">
fbsc_typeslist.UpdateOpts(<?php echo $bsc_types_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($bsc_types->ExportAll && $bsc_types->Export <> "") {
	$bsc_types_list->StopRec = $bsc_types_list->TotalRecs;
} else {

	// Set the last record to display
	if ($bsc_types_list->TotalRecs > $bsc_types_list->StartRec + $bsc_types_list->DisplayRecs - 1)
		$bsc_types_list->StopRec = $bsc_types_list->StartRec + $bsc_types_list->DisplayRecs - 1;
	else
		$bsc_types_list->StopRec = $bsc_types_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($bsc_types_list->FormKeyCountName) && ($bsc_types->CurrentAction == "gridadd" || $bsc_types->CurrentAction == "gridedit" || $bsc_types->CurrentAction == "F")) {
		$bsc_types_list->KeyCount = $objForm->GetValue($bsc_types_list->FormKeyCountName);
		$bsc_types_list->StopRec = $bsc_types_list->StartRec + $bsc_types_list->KeyCount - 1;
	}
}
$bsc_types_list->RecCnt = $bsc_types_list->StartRec - 1;
if ($bsc_types_list->Recordset && !$bsc_types_list->Recordset->EOF) {
	$bsc_types_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $bsc_types_list->StartRec > 1)
		$bsc_types_list->Recordset->Move($bsc_types_list->StartRec - 1);
} elseif (!$bsc_types->AllowAddDeleteRow && $bsc_types_list->StopRec == 0) {
	$bsc_types_list->StopRec = $bsc_types->GridAddRowCount;
}

// Initialize aggregate
$bsc_types->RowType = EW_ROWTYPE_AGGREGATEINIT;
$bsc_types->ResetAttrs();
$bsc_types_list->RenderRow();
$bsc_types_list->EditRowCnt = 0;
if ($bsc_types->CurrentAction == "edit")
	$bsc_types_list->RowIndex = 1;
if ($bsc_types->CurrentAction == "gridadd")
	$bsc_types_list->RowIndex = 0;
if ($bsc_types->CurrentAction == "gridedit")
	$bsc_types_list->RowIndex = 0;
while ($bsc_types_list->RecCnt < $bsc_types_list->StopRec) {
	$bsc_types_list->RecCnt++;
	if (intval($bsc_types_list->RecCnt) >= intval($bsc_types_list->StartRec)) {
		$bsc_types_list->RowCnt++;
		if ($bsc_types->CurrentAction == "gridadd" || $bsc_types->CurrentAction == "gridedit" || $bsc_types->CurrentAction == "F") {
			$bsc_types_list->RowIndex++;
			$objForm->Index = $bsc_types_list->RowIndex;
			if ($objForm->HasValue($bsc_types_list->FormActionName))
				$bsc_types_list->RowAction = strval($objForm->GetValue($bsc_types_list->FormActionName));
			elseif ($bsc_types->CurrentAction == "gridadd")
				$bsc_types_list->RowAction = "insert";
			else
				$bsc_types_list->RowAction = "";
		}

		// Set up key count
		$bsc_types_list->KeyCount = $bsc_types_list->RowIndex;

		// Init row class and style
		$bsc_types->ResetAttrs();
		$bsc_types->CssClass = "";
		if ($bsc_types->CurrentAction == "gridadd") {
			$bsc_types_list->LoadDefaultValues(); // Load default values
		} else {
			$bsc_types_list->LoadRowValues($bsc_types_list->Recordset); // Load row values
		}
		$bsc_types->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($bsc_types->CurrentAction == "gridadd") // Grid add
			$bsc_types->RowType = EW_ROWTYPE_ADD; // Render add
		if ($bsc_types->CurrentAction == "gridadd" && $bsc_types->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$bsc_types_list->RestoreCurrentRowFormValues($bsc_types_list->RowIndex); // Restore form values
		if ($bsc_types->CurrentAction == "edit") {
			if ($bsc_types_list->CheckInlineEditKey() && $bsc_types_list->EditRowCnt == 0) { // Inline edit
				$bsc_types->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($bsc_types->CurrentAction == "gridedit") { // Grid edit
			if ($bsc_types->EventCancelled) {
				$bsc_types_list->RestoreCurrentRowFormValues($bsc_types_list->RowIndex); // Restore form values
			}
			if ($bsc_types_list->RowAction == "insert")
				$bsc_types->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$bsc_types->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($bsc_types->CurrentAction == "edit" && $bsc_types->RowType == EW_ROWTYPE_EDIT && $bsc_types->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$bsc_types_list->RestoreFormValues(); // Restore form values
		}
		if ($bsc_types->CurrentAction == "gridedit" && ($bsc_types->RowType == EW_ROWTYPE_EDIT || $bsc_types->RowType == EW_ROWTYPE_ADD) && $bsc_types->EventCancelled) // Update failed
			$bsc_types_list->RestoreCurrentRowFormValues($bsc_types_list->RowIndex); // Restore form values
		if ($bsc_types->RowType == EW_ROWTYPE_EDIT) // Edit row
			$bsc_types_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$bsc_types->RowAttrs = array_merge($bsc_types->RowAttrs, array('data-rowindex'=>$bsc_types_list->RowCnt, 'id'=>'r' . $bsc_types_list->RowCnt . '_bsc_types', 'data-rowtype'=>$bsc_types->RowType));

		// Render row
		$bsc_types_list->RenderRow();

		// Render list options
		$bsc_types_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($bsc_types_list->RowAction <> "delete" && $bsc_types_list->RowAction <> "insertdelete" && !($bsc_types_list->RowAction == "insert" && $bsc_types->CurrentAction == "F" && $bsc_types_list->EmptyRow())) {
?>
	<tr<?php echo $bsc_types->RowAttributes() ?>>
<?php

// Render list options (body, left)
$bsc_types_list->ListOptions->Render("body", "left", $bsc_types_list->RowCnt);
?>
	<?php if ($bsc_types->name->Visible) { // name ?>
		<td<?php echo $bsc_types->name->CellAttributes() ?>><span id="el<?php echo $bsc_types_list->RowCnt ?>_bsc_types_name" class="control-group bsc_types_name">
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_name" name="x<?php echo $bsc_types_list->RowIndex ?>_name" id="x<?php echo $bsc_types_list->RowIndex ?>_name" size="200" maxlength="255" placeholder="<?php echo $bsc_types->name->PlaceHolder ?>" value="<?php echo $bsc_types->name->EditValue ?>"<?php echo $bsc_types->name->EditAttributes() ?>>
<input type="hidden" data-field="x_name" name="o<?php echo $bsc_types_list->RowIndex ?>_name" id="o<?php echo $bsc_types_list->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($bsc_types->name->OldValue) ?>">
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_name" name="x<?php echo $bsc_types_list->RowIndex ?>_name" id="x<?php echo $bsc_types_list->RowIndex ?>_name" size="200" maxlength="255" placeholder="<?php echo $bsc_types->name->PlaceHolder ?>" value="<?php echo $bsc_types->name->EditValue ?>"<?php echo $bsc_types->name->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_types->name->ViewAttributes() ?>>
<?php echo $bsc_types->name->ListViewValue() ?></span>
<?php } ?>
</span><a id="<?php echo $bsc_types_list->PageObjName . "_row_" . $bsc_types_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id" name="x<?php echo $bsc_types_list->RowIndex ?>_id" id="x<?php echo $bsc_types_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($bsc_types->id->CurrentValue) ?>">
<input type="hidden" data-field="x_id" name="o<?php echo $bsc_types_list->RowIndex ?>_id" id="o<?php echo $bsc_types_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($bsc_types->id->OldValue) ?>">
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_EDIT || $bsc_types->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_id" name="x<?php echo $bsc_types_list->RowIndex ?>_id" id="x<?php echo $bsc_types_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($bsc_types->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($bsc_types->price->Visible) { // price ?>
		<td<?php echo $bsc_types->price->CellAttributes() ?>><span id="el<?php echo $bsc_types_list->RowCnt ?>_bsc_types_price" class="control-group bsc_types_price">
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_price" name="x<?php echo $bsc_types_list->RowIndex ?>_price" id="x<?php echo $bsc_types_list->RowIndex ?>_price" size="30" placeholder="<?php echo $bsc_types->price->PlaceHolder ?>" value="<?php echo $bsc_types->price->EditValue ?>"<?php echo $bsc_types->price->EditAttributes() ?>>
<input type="hidden" data-field="x_price" name="o<?php echo $bsc_types_list->RowIndex ?>_price" id="o<?php echo $bsc_types_list->RowIndex ?>_price" value="<?php echo ew_HtmlEncode($bsc_types->price->OldValue) ?>">
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_price" name="x<?php echo $bsc_types_list->RowIndex ?>_price" id="x<?php echo $bsc_types_list->RowIndex ?>_price" size="30" placeholder="<?php echo $bsc_types->price->PlaceHolder ?>" value="<?php echo $bsc_types->price->EditValue ?>"<?php echo $bsc_types->price->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_types->price->ViewAttributes() ?>>
<?php echo $bsc_types->price->ListViewValue() ?></span>
<?php } ?>
</span><a id="<?php echo $bsc_types_list->PageObjName . "_row_" . $bsc_types_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_types->price_offer->Visible) { // price_offer ?>
		<td<?php echo $bsc_types->price_offer->CellAttributes() ?>><span id="el<?php echo $bsc_types_list->RowCnt ?>_bsc_types_price_offer" class="control-group bsc_types_price_offer">
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_price_offer" name="x<?php echo $bsc_types_list->RowIndex ?>_price_offer" id="x<?php echo $bsc_types_list->RowIndex ?>_price_offer" size="30" placeholder="<?php echo $bsc_types->price_offer->PlaceHolder ?>" value="<?php echo $bsc_types->price_offer->EditValue ?>"<?php echo $bsc_types->price_offer->EditAttributes() ?>>
<input type="hidden" data-field="x_price_offer" name="o<?php echo $bsc_types_list->RowIndex ?>_price_offer" id="o<?php echo $bsc_types_list->RowIndex ?>_price_offer" value="<?php echo ew_HtmlEncode($bsc_types->price_offer->OldValue) ?>">
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_price_offer" name="x<?php echo $bsc_types_list->RowIndex ?>_price_offer" id="x<?php echo $bsc_types_list->RowIndex ?>_price_offer" size="30" placeholder="<?php echo $bsc_types->price_offer->PlaceHolder ?>" value="<?php echo $bsc_types->price_offer->EditValue ?>"<?php echo $bsc_types->price_offer->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_types->price_offer->ViewAttributes() ?>>
<?php echo $bsc_types->price_offer->ListViewValue() ?></span>
<?php } ?>
</span><a id="<?php echo $bsc_types_list->PageObjName . "_row_" . $bsc_types_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($bsc_types->ordering->Visible) { // ordering ?>
		<td<?php echo $bsc_types->ordering->CellAttributes() ?>><span id="el<?php echo $bsc_types_list->RowCnt ?>_bsc_types_ordering" class="control-group bsc_types_ordering">
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" data-field="x_ordering" name="x<?php echo $bsc_types_list->RowIndex ?>_ordering" id="x<?php echo $bsc_types_list->RowIndex ?>_ordering" size="30" placeholder="<?php echo $bsc_types->ordering->PlaceHolder ?>" value="<?php echo $bsc_types->ordering->EditValue ?>"<?php echo $bsc_types->ordering->EditAttributes() ?>>
<input type="hidden" data-field="x_ordering" name="o<?php echo $bsc_types_list->RowIndex ?>_ordering" id="o<?php echo $bsc_types_list->RowIndex ?>_ordering" value="<?php echo ew_HtmlEncode($bsc_types->ordering->OldValue) ?>">
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" data-field="x_ordering" name="x<?php echo $bsc_types_list->RowIndex ?>_ordering" id="x<?php echo $bsc_types_list->RowIndex ?>_ordering" size="30" placeholder="<?php echo $bsc_types->ordering->PlaceHolder ?>" value="<?php echo $bsc_types->ordering->EditValue ?>"<?php echo $bsc_types->ordering->EditAttributes() ?>>
<?php } ?>
<?php if ($bsc_types->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $bsc_types->ordering->ViewAttributes() ?>>
<?php echo $bsc_types->ordering->ListViewValue() ?></span>
<?php } ?>
</span><a id="<?php echo $bsc_types_list->PageObjName . "_row_" . $bsc_types_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$bsc_types_list->ListOptions->Render("body", "right", $bsc_types_list->RowCnt);
?>
	</tr>
<?php if ($bsc_types->RowType == EW_ROWTYPE_ADD || $bsc_types->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fbsc_typeslist.UpdateOpts(<?php echo $bsc_types_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($bsc_types->CurrentAction <> "gridadd")
		if (!$bsc_types_list->Recordset->EOF) $bsc_types_list->Recordset->MoveNext();
}
?>
<?php
	if ($bsc_types->CurrentAction == "gridadd" || $bsc_types->CurrentAction == "gridedit") {
		$bsc_types_list->RowIndex = '$rowindex$';
		$bsc_types_list->LoadDefaultValues();

		// Set row properties
		$bsc_types->ResetAttrs();
		$bsc_types->RowAttrs = array_merge($bsc_types->RowAttrs, array('data-rowindex'=>$bsc_types_list->RowIndex, 'id'=>'r0_bsc_types', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($bsc_types->RowAttrs["class"], "ewTemplate");
		$bsc_types->RowType = EW_ROWTYPE_ADD;

		// Render row
		$bsc_types_list->RenderRow();

		// Render list options
		$bsc_types_list->RenderListOptions();
		$bsc_types_list->StartRowCnt = 0;
?>
	<tr<?php echo $bsc_types->RowAttributes() ?>>
<?php

// Render list options (body, left)
$bsc_types_list->ListOptions->Render("body", "left", $bsc_types_list->RowIndex);
?>
	<?php if ($bsc_types->name->Visible) { // name ?>
		<td><span id="el$rowindex$_bsc_types_name" class="control-group bsc_types_name">
<input type="text" data-field="x_name" name="x<?php echo $bsc_types_list->RowIndex ?>_name" id="x<?php echo $bsc_types_list->RowIndex ?>_name" size="200" maxlength="255" placeholder="<?php echo $bsc_types->name->PlaceHolder ?>" value="<?php echo $bsc_types->name->EditValue ?>"<?php echo $bsc_types->name->EditAttributes() ?>>
<input type="hidden" data-field="x_name" name="o<?php echo $bsc_types_list->RowIndex ?>_name" id="o<?php echo $bsc_types_list->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($bsc_types->name->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_types->price->Visible) { // price ?>
		<td><span id="el$rowindex$_bsc_types_price" class="control-group bsc_types_price">
<input type="text" data-field="x_price" name="x<?php echo $bsc_types_list->RowIndex ?>_price" id="x<?php echo $bsc_types_list->RowIndex ?>_price" size="30" placeholder="<?php echo $bsc_types->price->PlaceHolder ?>" value="<?php echo $bsc_types->price->EditValue ?>"<?php echo $bsc_types->price->EditAttributes() ?>>
<input type="hidden" data-field="x_price" name="o<?php echo $bsc_types_list->RowIndex ?>_price" id="o<?php echo $bsc_types_list->RowIndex ?>_price" value="<?php echo ew_HtmlEncode($bsc_types->price->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_types->price_offer->Visible) { // price_offer ?>
		<td><span id="el$rowindex$_bsc_types_price_offer" class="control-group bsc_types_price_offer">
<input type="text" data-field="x_price_offer" name="x<?php echo $bsc_types_list->RowIndex ?>_price_offer" id="x<?php echo $bsc_types_list->RowIndex ?>_price_offer" size="30" placeholder="<?php echo $bsc_types->price_offer->PlaceHolder ?>" value="<?php echo $bsc_types->price_offer->EditValue ?>"<?php echo $bsc_types->price_offer->EditAttributes() ?>>
<input type="hidden" data-field="x_price_offer" name="o<?php echo $bsc_types_list->RowIndex ?>_price_offer" id="o<?php echo $bsc_types_list->RowIndex ?>_price_offer" value="<?php echo ew_HtmlEncode($bsc_types->price_offer->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($bsc_types->ordering->Visible) { // ordering ?>
		<td><span id="el$rowindex$_bsc_types_ordering" class="control-group bsc_types_ordering">
<input type="text" data-field="x_ordering" name="x<?php echo $bsc_types_list->RowIndex ?>_ordering" id="x<?php echo $bsc_types_list->RowIndex ?>_ordering" size="30" placeholder="<?php echo $bsc_types->ordering->PlaceHolder ?>" value="<?php echo $bsc_types->ordering->EditValue ?>"<?php echo $bsc_types->ordering->EditAttributes() ?>>
<input type="hidden" data-field="x_ordering" name="o<?php echo $bsc_types_list->RowIndex ?>_ordering" id="o<?php echo $bsc_types_list->RowIndex ?>_ordering" value="<?php echo ew_HtmlEncode($bsc_types->ordering->OldValue) ?>">
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$bsc_types_list->ListOptions->Render("body", "right", $bsc_types_list->RowCnt);
?>
<script type="text/javascript">
fbsc_typeslist.UpdateOpts(<?php echo $bsc_types_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($bsc_types->CurrentAction == "add" || $bsc_types->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $bsc_types_list->FormKeyCountName ?>" id="<?php echo $bsc_types_list->FormKeyCountName ?>" value="<?php echo $bsc_types_list->KeyCount ?>">
<?php } ?>
<?php if ($bsc_types->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $bsc_types_list->FormKeyCountName ?>" id="<?php echo $bsc_types_list->FormKeyCountName ?>" value="<?php echo $bsc_types_list->KeyCount ?>">
<?php echo $bsc_types_list->MultiSelectKey ?>
<?php } ?>
<?php if ($bsc_types->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $bsc_types_list->FormKeyCountName ?>" id="<?php echo $bsc_types_list->FormKeyCountName ?>" value="<?php echo $bsc_types_list->KeyCount ?>">
<?php } ?>
<?php if ($bsc_types->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $bsc_types_list->FormKeyCountName ?>" id="<?php echo $bsc_types_list->FormKeyCountName ?>" value="<?php echo $bsc_types_list->KeyCount ?>">
<?php echo $bsc_types_list->MultiSelectKey ?>
<?php } ?>
<?php if ($bsc_types->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($bsc_types_list->Recordset)
	$bsc_types_list->Recordset->Close();
?>
</td></tr></table>
<?php if ($bsc_types->Export == "") { ?>
<script type="text/javascript">
fbsc_typeslistsrch.Init();
fbsc_typeslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$bsc_types_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($bsc_types->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$bsc_types_list->Page_Terminate();
?>
