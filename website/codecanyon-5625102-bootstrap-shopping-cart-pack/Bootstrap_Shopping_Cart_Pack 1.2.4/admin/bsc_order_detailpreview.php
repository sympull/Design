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

$bsc_order_detail_preview = NULL; // Initialize page object first

class cbsc_order_detail_preview extends cbsc_order_detail {

	// Page ID
	var $PageID = 'preview';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_order_detail';

	// Page object name
	var $PageObjName = 'bsc_order_detail_preview';

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
			define("EW_PAGE_ID", 'preview', TRUE);

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
if (!isset($bsc_order_detail_preview)) $bsc_order_detail_preview = new cbsc_order_detail_preview();

// Page init
$bsc_order_detail_preview->Page_Init();

// Page main
$bsc_order_detail_preview->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_order_detail_preview->Page_Render();
?>
<?php $bsc_order_detail_preview->ShowPageHeader(); ?>
<?php if ($bsc_order_detail_preview->TotalRecs > 0) { ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="ewDetailsPreviewTable" class="ewTable ewTableSeparate">
	<thead><!-- Table header -->
		<tr class="ewTableHeader">
<?php if ($bsc_order_detail->item_number->Visible) { // item_number ?>
			<td><?php echo $bsc_order_detail->item_number->FldCaption() ?></td>
<?php } ?>
<?php if ($bsc_order_detail->item_name->Visible) { // item_name ?>
			<td><?php echo $bsc_order_detail->item_name->FldCaption() ?></td>
<?php } ?>
<?php if ($bsc_order_detail->quantity->Visible) { // quantity ?>
			<td><?php echo $bsc_order_detail->quantity->FldCaption() ?></td>
<?php } ?>
<?php if ($bsc_order_detail->mc_gross->Visible) { // mc_gross ?>
			<td><?php echo $bsc_order_detail->mc_gross->FldCaption() ?></td>
<?php } ?>
<?php if ($bsc_order_detail->item_price->Visible) { // item_price ?>
			<td><?php echo $bsc_order_detail->item_price->FldCaption() ?></td>
<?php } ?>
		</tr>
	</thead>
	<tbody><!-- Table body -->
<?php
$bsc_order_detail_preview->RecCount = 0;
$bsc_order_detail_preview->RowCnt = 0;
while ($bsc_order_detail_preview->Recordset && !$bsc_order_detail_preview->Recordset->EOF) {

	// Init row class and style
	$bsc_order_detail_preview->RecCount++;
	$bsc_order_detail_preview->RowCnt++;
	$bsc_order_detail->CssClass = "";
	$bsc_order_detail->CssStyle = "";
	$bsc_order_detail->LoadListRowValues($bsc_order_detail_preview->Recordset);

	// Render row
	$bsc_order_detail->RowType = EW_ROWTYPE_PREVIEW; // Preview record
	$bsc_order_detail->RenderListRow();
?>
	<tr<?php echo $bsc_order_detail->RowAttributes() ?>>
<?php if ($bsc_order_detail->item_number->Visible) { // item_number ?>
		<!-- item_number -->
		<td<?php echo $bsc_order_detail->item_number->CellAttributes() ?>>
<span<?php echo $bsc_order_detail->item_number->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_number->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($bsc_order_detail->item_name->Visible) { // item_name ?>
		<!-- item_name -->
		<td<?php echo $bsc_order_detail->item_name->CellAttributes() ?>>
<span<?php echo $bsc_order_detail->item_name->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_name->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($bsc_order_detail->quantity->Visible) { // quantity ?>
		<!-- quantity -->
		<td<?php echo $bsc_order_detail->quantity->CellAttributes() ?>>
<span<?php echo $bsc_order_detail->quantity->ViewAttributes() ?>>
<?php echo $bsc_order_detail->quantity->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($bsc_order_detail->mc_gross->Visible) { // mc_gross ?>
		<!-- mc_gross -->
		<td<?php echo $bsc_order_detail->mc_gross->CellAttributes() ?>>
<span<?php echo $bsc_order_detail->mc_gross->ViewAttributes() ?>>
<?php echo $bsc_order_detail->mc_gross->ListViewValue() ?></span>
</td>
<?php } ?>
<?php if ($bsc_order_detail->item_price->Visible) { // item_price ?>
		<!-- item_price -->
		<td<?php echo $bsc_order_detail->item_price->CellAttributes() ?>>
<span<?php echo $bsc_order_detail->item_price->ViewAttributes() ?>>
<?php echo $bsc_order_detail->item_price->ListViewValue() ?></span>
</td>
<?php } ?>
	</tr>
<?php
	$bsc_order_detail_preview->Recordset->MoveNext();
}
?>
	</tbody>
</table>
</div>
</td></tr></table>
<?php if ($bsc_order_detail_preview->TotalRecs > 0) { ?>
<div class="ewDetailCount">(<?php echo $bsc_order_detail_preview->TotalRecs ?>&nbsp;<?php echo $Language->Phrase("Record") ?>)</div>
<?php } ?>
<?php
$bsc_order_detail_preview->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
	if ($bsc_order_detail_preview->Recordset)
		$bsc_order_detail_preview->Recordset->Close();
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
$bsc_order_detail_preview->Page_Terminate();
?>
