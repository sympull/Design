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

$bsc_types_preview = NULL; // Initialize page object first

class cbsc_types_preview extends cbsc_types {

	// Page ID
	var $PageID = 'preview';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_types';

	// Page object name
	var $PageObjName = 'bsc_types_preview';

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

		// Table object (bsc_types)
		if (!isset($GLOBALS["bsc_types"])) {
			$GLOBALS["bsc_types"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_types"];
		}

		// Table object (bsc_products)
		if (!isset($GLOBALS['bsc_products'])) $GLOBALS['bsc_products'] = new cbsc_products();

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'preview', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_types', TRUE);

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
		if (is_null($Security)) $Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			echo $Language->Phrase("NoPermission");
			exit();
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
	var $Recordset;
	var $TotalRecs;
	var $RowCnt;
	var $RecCount;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load filter
		$filter = @$_GET["f"];
		$filter = ew_Decrypt($filter);
		if ($filter == "") $filter = "0=1";

		// Call Recordset Selecting event
		$this->Recordset_Selecting($filter);

		// Load recordset
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset = $this->LoadRs($filter);
		$this->TotalRecs = ($this->Recordset) ? $this->Recordset->RecordCount() : 0;

		// Call Recordset Selected event
		$this->Recordset_Selected($this->Recordset);
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
<?php ew_Header(FALSE, 'utf-8') ?>
<?php

// Create page object
if (!isset($bsc_types_preview)) $bsc_types_preview = new cbsc_types_preview();

// Page init
$bsc_types_preview->Page_Init();

// Page main
$bsc_types_preview->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_types_preview->Page_Render();
?>
<?php $bsc_types_preview->ShowPageHeader(); ?>
<?php if ($bsc_types_preview->TotalRecs > 0) { ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="ewDetailsPreviewTable" class="ewTable ewTableSeparate">
	<thead><!-- Table header -->
		<tr class="ewTableHeader">
<?php if ($bsc_types->name->Visible) { // name ?>
			<td><?php echo $bsc_types->name->FldCaption() ?></td>
<?php } ?>
<?php if ($bsc_types->price->Visible) { // price ?>
			<td><?php echo $bsc_types->price->FldCaption() ?></td>
<?php } ?>
<?php if ($bsc_types->price_offer->Visible) { // price_offer ?>
			<td><?php echo $bsc_types->price_offer->FldCaption() ?></td>
<?php } ?>
<?php if ($bsc_types->ordering->Visible) { // ordering ?>
			<td><?php echo $bsc_types->ordering->FldCaption() ?></td>
<?php } ?>
		</tr>
	</thead>
	<tbody><!-- Table body -->
<?php
$bsc_types_preview->RecCount = 0;
$bsc_types_preview->RowCnt = 0;
while ($bsc_types_preview->Recordset && !$bsc_types_preview->Recordset->EOF) {

	// Init row class and style
	$bsc_types_preview->RecCount++;
	$bsc_types_preview->RowCnt++;
	$bsc_types->CssClass = "";
	$bsc_types->CssStyle = "";
	$bsc_types->LoadListRowValues($bsc_types_preview->Recordset);

	// Render row
	$bsc_types->RowType = EW_ROWTYPE_PREVIEW; // Preview record
	$bsc_types->RenderListRow();
?>
	<tr<?php echo $bsc_types->RowAttributes() ?>>
<?php if ($bsc_types->name->Visible) { // name ?>
		<!-- name -->
		<td<?php echo $bsc_types->name->CellAttributes() ?>>
<span<?php echo $bsc_types->name->ViewAttributes() ?>>
<?php echo $bsc_types->name->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($bsc_types->price->Visible) { // price ?>
		<!-- price -->
		<td<?php echo $bsc_types->price->CellAttributes() ?>>
<span<?php echo $bsc_types->price->ViewAttributes() ?>>
<?php echo $bsc_types->price->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($bsc_types->price_offer->Visible) { // price_offer ?>
		<!-- price_offer -->
		<td<?php echo $bsc_types->price_offer->CellAttributes() ?>>
<span<?php echo $bsc_types->price_offer->ViewAttributes() ?>>
<?php echo $bsc_types->price_offer->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($bsc_types->ordering->Visible) { // ordering ?>
		<!-- ordering -->
		<td<?php echo $bsc_types->ordering->CellAttributes() ?>>
<span<?php echo $bsc_types->ordering->ViewAttributes() ?>>
<?php echo $bsc_types->ordering->ListViewValue() ?></span>
</td>
<?php } ?>
	</tr>
<?php
	$bsc_types_preview->Recordset->MoveNext();
}
?>
	</tbody>
</table>
</div>
</td></tr></table>
<?php if ($bsc_types_preview->TotalRecs > 0) { ?>
<div class="ewDetailCount">(<?php echo $bsc_types_preview->TotalRecs ?>&nbsp;<?php echo $Language->Phrase("Record") ?>)</div>
<?php } ?>
<?php
$bsc_types_preview->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
	if ($bsc_types_preview->Recordset)
		$bsc_types_preview->Recordset->Close();
} else { ?>
<div class="ewDetailCount">(<?php echo $Language->Phrase("NoRecord") ?>)</div>
<?php
}

// Output
$content = ob_get_contents();
ob_end_clean();
echo ew_ConvertToUtf8($content);
?>
<?php
$bsc_types_preview->Page_Terminate();
?>
