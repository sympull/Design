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

$bsc_products_add = NULL; // Initialize page object first

class cbsc_products_add extends cbsc_products {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{FCDE03AD-398F-498B-9AE7-440F035B7773}";

	// Table name
	var $TableName = 'bsc_products';

	// Page object name
	var $PageObjName = 'bsc_products_add';

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

		// Table object (bsc_products)
		if (!isset($GLOBALS["bsc_products"])) {
			$GLOBALS["bsc_products"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bsc_products"];
		}

		// Table object (bsc_admin)
		if (!isset($GLOBALS['bsc_admin'])) $GLOBALS['bsc_admin'] = new cbsc_admin();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bsc_products', TRUE);

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

		// Set up detail parameters
		$this->SetUpDetailParms();

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
					$this->Page_Terminate("bsc_productslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "bsc_productsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->img->Upload->Index = $objForm->Index;
		if ($this->img->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->img->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->img->CurrentValue = $this->img->Upload->FileName;
		$this->img_detail1->Upload->Index = $objForm->Index;
		if ($this->img_detail1->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->img_detail1->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->img_detail1->CurrentValue = $this->img_detail1->Upload->FileName;
		$this->img_detail2->Upload->Index = $objForm->Index;
		if ($this->img_detail2->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->img_detail2->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->img_detail2->CurrentValue = $this->img_detail2->Upload->FileName;
		$this->img_detail3->Upload->Index = $objForm->Index;
		if ($this->img_detail3->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->img_detail3->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->img_detail3->CurrentValue = $this->img_detail3->Upload->FileName;
		$this->download->Upload->Index = $objForm->Index;
		if ($this->download->Upload->UploadFile()) {

			// No action required
		} else {
			echo $this->download->Upload->Message;
			$this->Page_Terminate();
			exit();
		}
		$this->download->CurrentValue = $this->download->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->img->Upload->DbValue = NULL;
		$this->img->OldValue = $this->img->Upload->DbValue;
		$this->img->CurrentValue = NULL; // Clear file related field
		$this->idCategory->CurrentValue = NULL;
		$this->idCategory->OldValue = $this->idCategory->CurrentValue;
		$this->productCode->CurrentValue = NULL;
		$this->productCode->OldValue = $this->productCode->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->price->CurrentValue = 0.00;
		$this->price_offer->CurrentValue = 0.00;
		$this->img_detail1->Upload->DbValue = NULL;
		$this->img_detail1->OldValue = $this->img_detail1->Upload->DbValue;
		$this->img_detail1->CurrentValue = NULL; // Clear file related field
		$this->img_detail2->Upload->DbValue = NULL;
		$this->img_detail2->OldValue = $this->img_detail2->Upload->DbValue;
		$this->img_detail2->CurrentValue = NULL; // Clear file related field
		$this->img_detail3->Upload->DbValue = NULL;
		$this->img_detail3->OldValue = $this->img_detail3->Upload->DbValue;
		$this->img_detail3->CurrentValue = NULL; // Clear file related field
		$this->download->Upload->DbValue = NULL;
		$this->download->OldValue = $this->download->Upload->DbValue;
		$this->download->CurrentValue = NULL; // Clear file related field
		$this->ordering->CurrentValue = NULL;
		$this->ordering->OldValue = $this->ordering->CurrentValue;
		$this->visible->CurrentValue = NULL;
		$this->visible->OldValue = $this->visible->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->idCategory->FldIsDetailKey) {
			$this->idCategory->setFormValue($objForm->GetValue("x_idCategory"));
		}
		if (!$this->productCode->FldIsDetailKey) {
			$this->productCode->setFormValue($objForm->GetValue("x_productCode"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->price->FldIsDetailKey) {
			$this->price->setFormValue($objForm->GetValue("x_price"));
		}
		if (!$this->price_offer->FldIsDetailKey) {
			$this->price_offer->setFormValue($objForm->GetValue("x_price_offer"));
		}
		if (!$this->ordering->FldIsDetailKey) {
			$this->ordering->setFormValue($objForm->GetValue("x_ordering"));
		}
		if (!$this->visible->FldIsDetailKey) {
			$this->visible->setFormValue($objForm->GetValue("x_visible"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->idCategory->CurrentValue = $this->idCategory->FormValue;
		$this->productCode->CurrentValue = $this->productCode->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
		$this->price_offer->CurrentValue = $this->price_offer->FormValue;
		$this->ordering->CurrentValue = $this->ordering->FormValue;
		$this->visible->CurrentValue = $this->visible->FormValue;
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

			// description
			$this->description->ViewValue = $this->description->CurrentValue;
			$this->description->ViewCustomAttributes = "";

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

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// img
			$this->img->EditCustomAttributes = "";
			$this->img->UploadPath = "../assets";
			if (!ew_Empty($this->img->Upload->DbValue)) {
				$this->img->ImageWidth = 100;
				$this->img->ImageHeight = 0;
				$this->img->ImageAlt = $this->img->FldAlt();
				$this->img->EditValue = ew_UploadPathEx(FALSE, $this->img->UploadPath) . $this->img->Upload->DbValue;
			} else {
				$this->img->EditValue = "";
			}
			if ($this->CurrentAction == "I" || $this->CurrentAction == "C") ew_RenderUploadField($this->img);

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
			$this->productCode->EditValue = ew_HtmlEncode($this->productCode->CurrentValue);
			$this->productCode->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->productCode->FldCaption()));

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->name->FldCaption()));

			// description
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = $this->description->CurrentValue;
			$this->description->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->description->FldCaption()));

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

			// img_detail1
			$this->img_detail1->EditCustomAttributes = "";
			$this->img_detail1->UploadPath = "../assets";
			if (!ew_Empty($this->img_detail1->Upload->DbValue)) {
				$this->img_detail1->ImageAlt = $this->img_detail1->FldAlt();
				$this->img_detail1->EditValue = ew_UploadPathEx(FALSE, $this->img_detail1->UploadPath) . $this->img_detail1->Upload->DbValue;
			} else {
				$this->img_detail1->EditValue = "";
			}
			if ($this->CurrentAction == "I" || $this->CurrentAction == "C") ew_RenderUploadField($this->img_detail1);

			// img_detail2
			$this->img_detail2->EditCustomAttributes = "";
			$this->img_detail2->UploadPath = "../assets";
			if (!ew_Empty($this->img_detail2->Upload->DbValue)) {
				$this->img_detail2->ImageAlt = $this->img_detail2->FldAlt();
				$this->img_detail2->EditValue = ew_UploadPathEx(FALSE, $this->img_detail2->UploadPath) . $this->img_detail2->Upload->DbValue;
			} else {
				$this->img_detail2->EditValue = "";
			}
			if ($this->CurrentAction == "I" || $this->CurrentAction == "C") ew_RenderUploadField($this->img_detail2);

			// img_detail3
			$this->img_detail3->EditCustomAttributes = "";
			$this->img_detail3->UploadPath = "../assets";
			if (!ew_Empty($this->img_detail3->Upload->DbValue)) {
				$this->img_detail3->ImageAlt = $this->img_detail3->FldAlt();
				$this->img_detail3->EditValue = ew_UploadPathEx(FALSE, $this->img_detail3->UploadPath) . $this->img_detail3->Upload->DbValue;
			} else {
				$this->img_detail3->EditValue = "";
			}
			if ($this->CurrentAction == "I" || $this->CurrentAction == "C") ew_RenderUploadField($this->img_detail3);

			// download
			$this->download->EditCustomAttributes = "";
			$this->download->UploadPath = "../assets";
			if (!ew_Empty($this->download->Upload->DbValue)) {
				$this->download->EditValue = $this->download->Upload->DbValue;
			} else {
				$this->download->EditValue = "";
			}
			if ($this->CurrentAction == "I" || $this->CurrentAction == "C") ew_RenderUploadField($this->download);

			// ordering
			$this->ordering->EditCustomAttributes = "";
			$this->ordering->EditValue = ew_HtmlEncode($this->ordering->CurrentValue);
			$this->ordering->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ordering->FldCaption()));

			// visible
			$this->visible->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->visible->FldTagValue(1), $this->visible->FldTagCaption(1) <> "" ? $this->visible->FldTagCaption(1) : $this->visible->FldTagValue(1));
			$this->visible->EditValue = $arwrk;

			// Edit refer script
			// img

			$this->img->HrefValue = "";
			$this->img->HrefValue2 = $this->img->UploadPath . $this->img->Upload->DbValue;

			// idCategory
			$this->idCategory->HrefValue = "";

			// productCode
			$this->productCode->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// description
			$this->description->HrefValue = "";

			// price
			$this->price->HrefValue = "";

			// price_offer
			$this->price_offer->HrefValue = "";

			// img_detail1
			$this->img_detail1->HrefValue = "";
			$this->img_detail1->HrefValue2 = $this->img_detail1->UploadPath . $this->img_detail1->Upload->DbValue;

			// img_detail2
			$this->img_detail2->HrefValue = "";
			$this->img_detail2->HrefValue2 = $this->img_detail2->UploadPath . $this->img_detail2->Upload->DbValue;

			// img_detail3
			$this->img_detail3->HrefValue = "";
			$this->img_detail3->HrefValue2 = $this->img_detail3->UploadPath . $this->img_detail3->Upload->DbValue;

			// download
			$this->download->HrefValue = "";
			$this->download->HrefValue2 = $this->download->UploadPath . $this->download->Upload->DbValue;

			// ordering
			$this->ordering->HrefValue = "";

			// visible
			$this->visible->HrefValue = "";
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

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("bsc_types", $DetailTblVar) && $GLOBALS["bsc_types"]->DetailAdd) {
			if (!isset($GLOBALS["bsc_types_grid"])) $GLOBALS["bsc_types_grid"] = new cbsc_types_grid(); // get detail page object
			$GLOBALS["bsc_types_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->img->OldUploadPath = "../assets";
			$this->img->UploadPath = $this->img->OldUploadPath;
			$this->img_detail1->OldUploadPath = "../assets";
			$this->img_detail1->UploadPath = $this->img_detail1->OldUploadPath;
			$this->img_detail2->OldUploadPath = "../assets";
			$this->img_detail2->UploadPath = $this->img_detail2->OldUploadPath;
			$this->img_detail3->OldUploadPath = "../assets";
			$this->img_detail3->UploadPath = $this->img_detail3->OldUploadPath;
			$this->download->OldUploadPath = "../assets";
			$this->download->UploadPath = $this->download->OldUploadPath;
		}
		$rsnew = array();

		// img
		if (!$this->img->Upload->KeepFile) {
			if ($this->img->Upload->FileName == "") {
				$rsnew['img'] = NULL;
			} else {
				$rsnew['img'] = $this->img->Upload->FileName;
			}
		}

		// idCategory
		$this->idCategory->SetDbValueDef($rsnew, $this->idCategory->CurrentValue, NULL, FALSE);

		// productCode
		$this->productCode->SetDbValueDef($rsnew, $this->productCode->CurrentValue, NULL, FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// price
		$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, NULL, strval($this->price->CurrentValue) == "");

		// price_offer
		$this->price_offer->SetDbValueDef($rsnew, $this->price_offer->CurrentValue, NULL, strval($this->price_offer->CurrentValue) == "");

		// img_detail1
		if (!$this->img_detail1->Upload->KeepFile) {
			if ($this->img_detail1->Upload->FileName == "") {
				$rsnew['img_detail1'] = NULL;
			} else {
				$rsnew['img_detail1'] = $this->img_detail1->Upload->FileName;
			}
		}

		// img_detail2
		if (!$this->img_detail2->Upload->KeepFile) {
			if ($this->img_detail2->Upload->FileName == "") {
				$rsnew['img_detail2'] = NULL;
			} else {
				$rsnew['img_detail2'] = $this->img_detail2->Upload->FileName;
			}
		}

		// img_detail3
		if (!$this->img_detail3->Upload->KeepFile) {
			if ($this->img_detail3->Upload->FileName == "") {
				$rsnew['img_detail3'] = NULL;
			} else {
				$rsnew['img_detail3'] = $this->img_detail3->Upload->FileName;
			}
		}

		// download
		if (!$this->download->Upload->KeepFile) {
			if ($this->download->Upload->FileName == "") {
				$rsnew['download'] = NULL;
			} else {
				$rsnew['download'] = $this->download->Upload->FileName;
			}
		}

		// ordering
		$this->ordering->SetDbValueDef($rsnew, $this->ordering->CurrentValue, NULL, FALSE);

		// visible
		$this->visible->SetDbValueDef($rsnew, $this->visible->CurrentValue, NULL, FALSE);
		if (!$this->img->Upload->KeepFile) {
			$this->img->UploadPath = "../assets";
			if (!ew_Empty($this->img->Upload->Value)) {
				$rsnew['img'] = ew_UploadFileNameEx($this->img->UploadPath, $rsnew['img']); // Get new file name
			}
		}
		if (!$this->img_detail1->Upload->KeepFile) {
			$this->img_detail1->UploadPath = "../assets";
			if (!ew_Empty($this->img_detail1->Upload->Value)) {
				$rsnew['img_detail1'] = ew_UploadFileNameEx($this->img_detail1->UploadPath, $rsnew['img_detail1']); // Get new file name
			}
		}
		if (!$this->img_detail2->Upload->KeepFile) {
			$this->img_detail2->UploadPath = "../assets";
			if (!ew_Empty($this->img_detail2->Upload->Value)) {
				$rsnew['img_detail2'] = ew_UploadFileNameEx($this->img_detail2->UploadPath, $rsnew['img_detail2']); // Get new file name
			}
		}
		if (!$this->img_detail3->Upload->KeepFile) {
			$this->img_detail3->UploadPath = "../assets";
			if (!ew_Empty($this->img_detail3->Upload->Value)) {
				$rsnew['img_detail3'] = ew_UploadFileNameEx($this->img_detail3->UploadPath, $rsnew['img_detail3']); // Get new file name
			}
		}
		if (!$this->download->Upload->KeepFile) {
			$this->download->UploadPath = "../assets";
			if (!ew_Empty($this->download->Upload->Value)) {
				$rsnew['download'] = ew_UploadFileNameEx($this->download->UploadPath, $rsnew['download']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->img->Upload->KeepFile) {
					if (!ew_Empty($this->img->Upload->Value)) {
						$this->img->Upload->SaveToFile($this->img->UploadPath, $rsnew['img'], TRUE);
					}
				}
				if (!$this->img_detail1->Upload->KeepFile) {
					if (!ew_Empty($this->img_detail1->Upload->Value)) {
						$this->img_detail1->Upload->SaveToFile($this->img_detail1->UploadPath, $rsnew['img_detail1'], TRUE);
					}
				}
				if (!$this->img_detail2->Upload->KeepFile) {
					if (!ew_Empty($this->img_detail2->Upload->Value)) {
						$this->img_detail2->Upload->SaveToFile($this->img_detail2->UploadPath, $rsnew['img_detail2'], TRUE);
					}
				}
				if (!$this->img_detail3->Upload->KeepFile) {
					if (!ew_Empty($this->img_detail3->Upload->Value)) {
						$this->img_detail3->Upload->SaveToFile($this->img_detail3->UploadPath, $rsnew['img_detail3'], TRUE);
					}
				}
				if (!$this->download->Upload->KeepFile) {
					if (!ew_Empty($this->download->Upload->Value)) {
						$this->download->Upload->SaveToFile($this->download->UploadPath, $rsnew['download'], TRUE);
					}
				}
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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("bsc_types", $DetailTblVar) && $GLOBALS["bsc_types"]->DetailAdd) {
				$GLOBALS["bsc_types"]->idProduct->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["bsc_types_grid"])) $GLOBALS["bsc_types_grid"] = new cbsc_types_grid(); // Get detail page object
				$AddRow = $GLOBALS["bsc_types_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["bsc_types"]->idProduct->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// img
		ew_CleanUploadTempPath($this->img, $this->img->Upload->Index);

		// img_detail1
		ew_CleanUploadTempPath($this->img_detail1, $this->img_detail1->Upload->Index);

		// img_detail2
		ew_CleanUploadTempPath($this->img_detail2, $this->img_detail2->Upload->Index);

		// img_detail3
		ew_CleanUploadTempPath($this->img_detail3, $this->img_detail3->Upload->Index);

		// download
		ew_CleanUploadTempPath($this->download, $this->download->Upload->Index);
		return $AddRow;
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
			if (in_array("bsc_types", $DetailTblVar)) {
				if (!isset($GLOBALS["bsc_types_grid"]))
					$GLOBALS["bsc_types_grid"] = new cbsc_types_grid;
				if ($GLOBALS["bsc_types_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["bsc_types_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["bsc_types_grid"]->CurrentMode = "add";
					$GLOBALS["bsc_types_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["bsc_types_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["bsc_types_grid"]->setStartRecordNumber(1);
					$GLOBALS["bsc_types_grid"]->idProduct->FldIsDetailKey = TRUE;
					$GLOBALS["bsc_types_grid"]->idProduct->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["bsc_types_grid"]->idProduct->setSessionValue($GLOBALS["bsc_types_grid"]->idProduct->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "bsc_productslist.php", $this->TableVar);
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
if (!isset($bsc_products_add)) $bsc_products_add = new cbsc_products_add();

// Page init
$bsc_products_add->Page_Init();

// Page main
$bsc_products_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bsc_products_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var bsc_products_add = new ew_Page("bsc_products_add");
bsc_products_add.PageID = "add"; // Page ID
var EW_PAGE_ID = bsc_products_add.PageID; // For backward compatibility

// Form object
var fbsc_productsadd = new ew_Form("fbsc_productsadd");

// Validate form
fbsc_productsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_products->price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_price_offer");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_products->price_offer->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ordering");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bsc_products->ordering->FldErrMsg()) ?>");

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
fbsc_productsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbsc_productsadd.ValidateRequired = true;
<?php } else { ?>
fbsc_productsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fbsc_productsadd.Lists["x_idCategory"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $bsc_products_add->ShowPageHeader(); ?>
<?php
$bsc_products_add->ShowMessage();
?>
<form name="fbsc_productsadd" id="fbsc_productsadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="bsc_products">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_bsc_productsadd" class="table table-bordered table-striped">
<?php if ($bsc_products->img->Visible) { // img ?>
	<tr id="r_img"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_img"><?php echo $bsc_products->img->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->img->CellAttributes() ?>><span id="el_bsc_products_img" class="control-group">
<span id="fd_x_img">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_img" name="x_img" id="x_img">
</span>
<input type="hidden" name="fn_x_img" id= "fn_x_img" value="<?php echo $bsc_products->img->Upload->FileName ?>">
<input type="hidden" name="fa_x_img" id= "fa_x_img" value="0">
<input type="hidden" name="fs_x_img" id= "fs_x_img" value="255">
</span>
<table id="ft_x_img" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span><?php echo $bsc_products->img->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->idCategory->Visible) { // idCategory ?>
	<tr id="r_idCategory"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_idCategory"><?php echo $bsc_products->idCategory->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->idCategory->CellAttributes() ?>><span id="el_bsc_products_idCategory" class="control-group">
<select data-field="x_idCategory" id="x_idCategory" name="x_idCategory"<?php echo $bsc_products->idCategory->EditAttributes() ?>>
<?php
if (is_array($bsc_products->idCategory->EditValue)) {
	$arwrk = $bsc_products->idCategory->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($bsc_products->idCategory->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fbsc_productsadd.Lists["x_idCategory"].Options = <?php echo (is_array($bsc_products->idCategory->EditValue)) ? ew_ArrayToJson($bsc_products->idCategory->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $bsc_products->idCategory->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->productCode->Visible) { // productCode ?>
	<tr id="r_productCode"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_productCode"><?php echo $bsc_products->productCode->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->productCode->CellAttributes() ?>><span id="el_bsc_products_productCode" class="control-group">
<input type="text" data-field="x_productCode" name="x_productCode" id="x_productCode" size="30" maxlength="20" placeholder="<?php echo $bsc_products->productCode->PlaceHolder ?>" value="<?php echo $bsc_products->productCode->EditValue ?>"<?php echo $bsc_products->productCode->EditAttributes() ?>>
</span><?php echo $bsc_products->productCode->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->name->Visible) { // name ?>
	<tr id="r_name"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_name"><?php echo $bsc_products->name->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->name->CellAttributes() ?>><span id="el_bsc_products_name" class="control-group">
<input type="text" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="255" placeholder="<?php echo $bsc_products->name->PlaceHolder ?>" value="<?php echo $bsc_products->name->EditValue ?>"<?php echo $bsc_products->name->EditAttributes() ?>>
</span><?php echo $bsc_products->name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->description->Visible) { // description ?>
	<tr id="r_description"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_description"><?php echo $bsc_products->description->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->description->CellAttributes() ?>><span id="el_bsc_products_description" class="control-group">
<textarea data-field="x_description" name="x_description" id="x_description" cols="100" rows="8" placeholder="<?php echo $bsc_products->description->PlaceHolder ?>"<?php echo $bsc_products->description->EditAttributes() ?>><?php echo $bsc_products->description->EditValue ?></textarea>
</span><?php echo $bsc_products->description->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->price->Visible) { // price ?>
	<tr id="r_price"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_price"><?php echo $bsc_products->price->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->price->CellAttributes() ?>><span id="el_bsc_products_price" class="control-group">
<input type="text" data-field="x_price" name="x_price" id="x_price" size="30" placeholder="<?php echo $bsc_products->price->PlaceHolder ?>" value="<?php echo $bsc_products->price->EditValue ?>"<?php echo $bsc_products->price->EditAttributes() ?>>
</span><?php echo $bsc_products->price->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->price_offer->Visible) { // price_offer ?>
	<tr id="r_price_offer"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_price_offer"><?php echo $bsc_products->price_offer->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->price_offer->CellAttributes() ?>><span id="el_bsc_products_price_offer" class="control-group">
<input type="text" data-field="x_price_offer" name="x_price_offer" id="x_price_offer" size="30" placeholder="<?php echo $bsc_products->price_offer->PlaceHolder ?>" value="<?php echo $bsc_products->price_offer->EditValue ?>"<?php echo $bsc_products->price_offer->EditAttributes() ?>>
</span><?php echo $bsc_products->price_offer->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->img_detail1->Visible) { // img_detail1 ?>
	<tr id="r_img_detail1"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_img_detail1"><?php echo $bsc_products->img_detail1->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->img_detail1->CellAttributes() ?>><span id="el_bsc_products_img_detail1" class="control-group">
<span id="fd_x_img_detail1">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_img_detail1" name="x_img_detail1" id="x_img_detail1">
</span>
<input type="hidden" name="fn_x_img_detail1" id= "fn_x_img_detail1" value="<?php echo $bsc_products->img_detail1->Upload->FileName ?>">
<input type="hidden" name="fa_x_img_detail1" id= "fa_x_img_detail1" value="0">
<input type="hidden" name="fs_x_img_detail1" id= "fs_x_img_detail1" value="255">
</span>
<table id="ft_x_img_detail1" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span><?php echo $bsc_products->img_detail1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->img_detail2->Visible) { // img_detail2 ?>
	<tr id="r_img_detail2"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_img_detail2"><?php echo $bsc_products->img_detail2->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->img_detail2->CellAttributes() ?>><span id="el_bsc_products_img_detail2" class="control-group">
<span id="fd_x_img_detail2">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_img_detail2" name="x_img_detail2" id="x_img_detail2">
</span>
<input type="hidden" name="fn_x_img_detail2" id= "fn_x_img_detail2" value="<?php echo $bsc_products->img_detail2->Upload->FileName ?>">
<input type="hidden" name="fa_x_img_detail2" id= "fa_x_img_detail2" value="0">
<input type="hidden" name="fs_x_img_detail2" id= "fs_x_img_detail2" value="255">
</span>
<table id="ft_x_img_detail2" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span><?php echo $bsc_products->img_detail2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->img_detail3->Visible) { // img_detail3 ?>
	<tr id="r_img_detail3"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_img_detail3"><?php echo $bsc_products->img_detail3->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->img_detail3->CellAttributes() ?>><span id="el_bsc_products_img_detail3" class="control-group">
<span id="fd_x_img_detail3">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_img_detail3" name="x_img_detail3" id="x_img_detail3">
</span>
<input type="hidden" name="fn_x_img_detail3" id= "fn_x_img_detail3" value="<?php echo $bsc_products->img_detail3->Upload->FileName ?>">
<input type="hidden" name="fa_x_img_detail3" id= "fa_x_img_detail3" value="0">
<input type="hidden" name="fs_x_img_detail3" id= "fs_x_img_detail3" value="255">
</span>
<table id="ft_x_img_detail3" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span><?php echo $bsc_products->img_detail3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->download->Visible) { // download ?>
	<tr id="r_download"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_download"><?php echo $bsc_products->download->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->download->CellAttributes() ?>><span id="el_bsc_products_download" class="control-group">
<span id="fd_x_download">
<span class="btn btn-small fileinput-button">
	<span><?php echo $Language->Phrase("ChooseFile") ?></span>
	<input type="file" data-field="x_download" name="x_download" id="x_download">
</span>
<input type="hidden" name="fn_x_download" id= "fn_x_download" value="<?php echo $bsc_products->download->Upload->FileName ?>">
<input type="hidden" name="fa_x_download" id= "fa_x_download" value="0">
<input type="hidden" name="fs_x_download" id= "fs_x_download" value="255">
</span>
<table id="ft_x_download" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span><?php echo $bsc_products->download->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->ordering->Visible) { // ordering ?>
	<tr id="r_ordering"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_ordering"><?php echo $bsc_products->ordering->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->ordering->CellAttributes() ?>><span id="el_bsc_products_ordering" class="control-group">
<input type="text" data-field="x_ordering" name="x_ordering" id="x_ordering" size="30" placeholder="<?php echo $bsc_products->ordering->PlaceHolder ?>" value="<?php echo $bsc_products->ordering->EditValue ?>"<?php echo $bsc_products->ordering->EditAttributes() ?>>
</span><?php echo $bsc_products->ordering->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($bsc_products->visible->Visible) { // visible ?>
	<tr id="r_visible"<?php echo $bsc_products->RowAttributes() ?>>
		<td><span id="elh_bsc_products_visible"><?php echo $bsc_products->visible->FldCaption() ?></span></td>
		<td<?php echo $bsc_products->visible->CellAttributes() ?>><span id="el_bsc_products_visible" class="control-group">
<div id="tp_x_visible" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_visible[]" id="x_visible[]" value="{value}"<?php echo $bsc_products->visible->EditAttributes() ?>></div>
<div id="dsl_x_visible" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $bsc_products->visible->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($bsc_products->visible->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_visible" name="x_visible[]" id="x_visible_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $bsc_products->visible->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span><?php echo $bsc_products->visible->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("bsc_types", explode(",", $bsc_products->getCurrentDetailTable())) && $bsc_types->DetailAdd) {
?>
<?php include_once "bsc_typesgrid.php" ?>
<?php } ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fbsc_productsadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$bsc_products_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bsc_products_add->Page_Terminate();
?>
