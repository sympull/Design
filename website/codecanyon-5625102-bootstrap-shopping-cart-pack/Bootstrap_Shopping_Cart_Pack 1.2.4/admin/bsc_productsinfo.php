<?php

// Global variable for table object
$bsc_products = NULL;

//
// Table class for bsc_products
//
class cbsc_products extends cTable {
	var $id;
	var $img;
	var $idCategory;
	var $productCode;
	var $name;
	var $description;
	var $price;
	var $price_offer;
	var $img_detail1;
	var $img_detail2;
	var $img_detail3;
	var $download;
	var $ordering;
	var $visible;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'bsc_products';
		$this->TableName = 'bsc_products';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('bsc_products', 'bsc_products', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// img
		$this->img = new cField('bsc_products', 'bsc_products', 'x_img', 'img', '`img`', '`img`', 200, -1, TRUE, '`img`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['img'] = &$this->img;

		// idCategory
		$this->idCategory = new cField('bsc_products', 'bsc_products', 'x_idCategory', 'idCategory', '`idCategory`', '`idCategory`', 3, -1, FALSE, '`idCategory`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idCategory->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idCategory'] = &$this->idCategory;

		// productCode
		$this->productCode = new cField('bsc_products', 'bsc_products', 'x_productCode', 'productCode', '`productCode`', '`productCode`', 200, -1, FALSE, '`productCode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['productCode'] = &$this->productCode;

		// name
		$this->name = new cField('bsc_products', 'bsc_products', 'x_name', 'name', '`name`', '`name`', 200, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['name'] = &$this->name;

		// description
		$this->description = new cField('bsc_products', 'bsc_products', 'x_description', 'description', '`description`', '`description`', 201, -1, FALSE, '`description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['description'] = &$this->description;

		// price
		$this->price = new cField('bsc_products', 'bsc_products', 'x_price', 'price', '`price`', '`price`', 131, -1, FALSE, '`price`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->price->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['price'] = &$this->price;

		// price_offer
		$this->price_offer = new cField('bsc_products', 'bsc_products', 'x_price_offer', 'price_offer', '`price_offer`', '`price_offer`', 131, -1, FALSE, '`price_offer`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->price_offer->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['price_offer'] = &$this->price_offer;

		// img_detail1
		$this->img_detail1 = new cField('bsc_products', 'bsc_products', 'x_img_detail1', 'img_detail1', '`img_detail1`', '`img_detail1`', 200, -1, TRUE, '`img_detail1`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['img_detail1'] = &$this->img_detail1;

		// img_detail2
		$this->img_detail2 = new cField('bsc_products', 'bsc_products', 'x_img_detail2', 'img_detail2', '`img_detail2`', '`img_detail2`', 200, -1, TRUE, '`img_detail2`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['img_detail2'] = &$this->img_detail2;

		// img_detail3
		$this->img_detail3 = new cField('bsc_products', 'bsc_products', 'x_img_detail3', 'img_detail3', '`img_detail3`', '`img_detail3`', 200, -1, TRUE, '`img_detail3`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['img_detail3'] = &$this->img_detail3;

		// download
		$this->download = new cField('bsc_products', 'bsc_products', 'x_download', 'download', '`download`', '`download`', 200, -1, TRUE, '`download`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['download'] = &$this->download;

		// ordering
		$this->ordering = new cField('bsc_products', 'bsc_products', 'x_ordering', 'ordering', '`ordering`', '`ordering`', 3, -1, FALSE, '`ordering`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ordering->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ordering'] = &$this->ordering;

		// visible
		$this->visible = new cField('bsc_products', 'bsc_products', 'x_visible', 'visible', '`visible`', '`visible`', 3, -1, FALSE, '`visible`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->visible->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['visible'] = &$this->visible;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "bsc_types") {
			$sDetailUrl = $GLOBALS["bsc_types"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&idProduct=" . $this->id->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "bsc_productslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`bsc_products`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "`idCategory` ASC,`ordering` ASC";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`bsc_products`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "bsc_productslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "bsc_productslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("bsc_productsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("bsc_productsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "bsc_productsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("bsc_productsedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("bsc_productsedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("bsc_productsadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("bsc_productsadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("bsc_productsdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id"]; // id

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

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

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->img->Exportable) $Doc->ExportCaption($this->img);
				if ($this->idCategory->Exportable) $Doc->ExportCaption($this->idCategory);
				if ($this->productCode->Exportable) $Doc->ExportCaption($this->productCode);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->description->Exportable) $Doc->ExportCaption($this->description);
				if ($this->price->Exportable) $Doc->ExportCaption($this->price);
				if ($this->price_offer->Exportable) $Doc->ExportCaption($this->price_offer);
				if ($this->img_detail1->Exportable) $Doc->ExportCaption($this->img_detail1);
				if ($this->img_detail2->Exportable) $Doc->ExportCaption($this->img_detail2);
				if ($this->img_detail3->Exportable) $Doc->ExportCaption($this->img_detail3);
				if ($this->download->Exportable) $Doc->ExportCaption($this->download);
				if ($this->ordering->Exportable) $Doc->ExportCaption($this->ordering);
				if ($this->visible->Exportable) $Doc->ExportCaption($this->visible);
			} else {
				if ($this->img->Exportable) $Doc->ExportCaption($this->img);
				if ($this->idCategory->Exportable) $Doc->ExportCaption($this->idCategory);
				if ($this->productCode->Exportable) $Doc->ExportCaption($this->productCode);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->price->Exportable) $Doc->ExportCaption($this->price);
				if ($this->price_offer->Exportable) $Doc->ExportCaption($this->price_offer);
				if ($this->img_detail1->Exportable) $Doc->ExportCaption($this->img_detail1);
				if ($this->img_detail2->Exportable) $Doc->ExportCaption($this->img_detail2);
				if ($this->img_detail3->Exportable) $Doc->ExportCaption($this->img_detail3);
				if ($this->download->Exportable) $Doc->ExportCaption($this->download);
				if ($this->ordering->Exportable) $Doc->ExportCaption($this->ordering);
				if ($this->visible->Exportable) $Doc->ExportCaption($this->visible);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->img->Exportable) $Doc->ExportField($this->img);
					if ($this->idCategory->Exportable) $Doc->ExportField($this->idCategory);
					if ($this->productCode->Exportable) $Doc->ExportField($this->productCode);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->description->Exportable) $Doc->ExportField($this->description);
					if ($this->price->Exportable) $Doc->ExportField($this->price);
					if ($this->price_offer->Exportable) $Doc->ExportField($this->price_offer);
					if ($this->img_detail1->Exportable) $Doc->ExportField($this->img_detail1);
					if ($this->img_detail2->Exportable) $Doc->ExportField($this->img_detail2);
					if ($this->img_detail3->Exportable) $Doc->ExportField($this->img_detail3);
					if ($this->download->Exportable) $Doc->ExportField($this->download);
					if ($this->ordering->Exportable) $Doc->ExportField($this->ordering);
					if ($this->visible->Exportable) $Doc->ExportField($this->visible);
				} else {
					if ($this->img->Exportable) $Doc->ExportField($this->img);
					if ($this->idCategory->Exportable) $Doc->ExportField($this->idCategory);
					if ($this->productCode->Exportable) $Doc->ExportField($this->productCode);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->price->Exportable) $Doc->ExportField($this->price);
					if ($this->price_offer->Exportable) $Doc->ExportField($this->price_offer);
					if ($this->img_detail1->Exportable) $Doc->ExportField($this->img_detail1);
					if ($this->img_detail2->Exportable) $Doc->ExportField($this->img_detail2);
					if ($this->img_detail3->Exportable) $Doc->ExportField($this->img_detail3);
					if ($this->download->Exportable) $Doc->ExportField($this->download);
					if ($this->ordering->Exportable) $Doc->ExportField($this->ordering);
					if ($this->visible->Exportable) $Doc->ExportField($this->visible);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
