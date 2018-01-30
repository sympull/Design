<?php

// Global variable for table object
$bsc_order_detail = NULL;

//
// Table class for bsc_order_detail
//
class cbsc_order_detail extends cTable {
	var $id;
	var $dateorder;
	var $item_number;
	var $item_name;
	var $quantity;
	var $mc_gross;
	var $payment_status;
	var $payment_amount;
	var $payment_currency;
	var $payer_email;
	var $payment_type;
	var $custom;
	var $invoice;
	var $first_name;
	var $last_name;
	var $address_name;
	var $address_country;
	var $address_country_code;
	var $address_zip;
	var $address_state;
	var $address_city;
	var $address_street;
	var $item_price;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'bsc_order_detail';
		$this->TableName = 'bsc_order_detail';
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
		$this->id = new cField('bsc_order_detail', 'bsc_order_detail', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// dateorder
		$this->dateorder = new cField('bsc_order_detail', 'bsc_order_detail', 'x_dateorder', 'dateorder', '`dateorder`', 'DATE_FORMAT(`dateorder`, \'%Y/%m/%d\')', 135, 5, FALSE, '`dateorder`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->dateorder->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['dateorder'] = &$this->dateorder;

		// item_number
		$this->item_number = new cField('bsc_order_detail', 'bsc_order_detail', 'x_item_number', 'item_number', '`item_number`', '`item_number`', 200, -1, FALSE, '`item_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['item_number'] = &$this->item_number;

		// item_name
		$this->item_name = new cField('bsc_order_detail', 'bsc_order_detail', 'x_item_name', 'item_name', '`item_name`', '`item_name`', 200, -1, FALSE, '`item_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['item_name'] = &$this->item_name;

		// quantity
		$this->quantity = new cField('bsc_order_detail', 'bsc_order_detail', 'x_quantity', 'quantity', '`quantity`', '`quantity`', 3, -1, FALSE, '`quantity`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->quantity->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['quantity'] = &$this->quantity;

		// mc_gross
		$this->mc_gross = new cField('bsc_order_detail', 'bsc_order_detail', 'x_mc_gross', 'mc_gross', '`mc_gross`', '`mc_gross`', 200, -1, FALSE, '`mc_gross`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->mc_gross->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['mc_gross'] = &$this->mc_gross;

		// payment_status
		$this->payment_status = new cField('bsc_order_detail', 'bsc_order_detail', 'x_payment_status', 'payment_status', '`payment_status`', '`payment_status`', 200, -1, FALSE, '`payment_status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['payment_status'] = &$this->payment_status;

		// payment_amount
		$this->payment_amount = new cField('bsc_order_detail', 'bsc_order_detail', 'x_payment_amount', 'payment_amount', '`payment_amount`', '`payment_amount`', 200, -1, FALSE, '`payment_amount`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['payment_amount'] = &$this->payment_amount;

		// payment_currency
		$this->payment_currency = new cField('bsc_order_detail', 'bsc_order_detail', 'x_payment_currency', 'payment_currency', '`payment_currency`', '`payment_currency`', 200, -1, FALSE, '`payment_currency`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['payment_currency'] = &$this->payment_currency;

		// payer_email
		$this->payer_email = new cField('bsc_order_detail', 'bsc_order_detail', 'x_payer_email', 'payer_email', '`payer_email`', '`payer_email`', 200, -1, FALSE, '`payer_email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['payer_email'] = &$this->payer_email;

		// payment_type
		$this->payment_type = new cField('bsc_order_detail', 'bsc_order_detail', 'x_payment_type', 'payment_type', '`payment_type`', '`payment_type`', 200, -1, FALSE, '`payment_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['payment_type'] = &$this->payment_type;

		// custom
		$this->custom = new cField('bsc_order_detail', 'bsc_order_detail', 'x_custom', 'custom', '`custom`', '`custom`', 200, -1, FALSE, '`custom`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['custom'] = &$this->custom;

		// invoice
		$this->invoice = new cField('bsc_order_detail', 'bsc_order_detail', 'x_invoice', 'invoice', '`invoice`', '`invoice`', 200, -1, FALSE, '`invoice`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['invoice'] = &$this->invoice;

		// first_name
		$this->first_name = new cField('bsc_order_detail', 'bsc_order_detail', 'x_first_name', 'first_name', '`first_name`', '`first_name`', 200, -1, FALSE, '`first_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['first_name'] = &$this->first_name;

		// last_name
		$this->last_name = new cField('bsc_order_detail', 'bsc_order_detail', 'x_last_name', 'last_name', '`last_name`', '`last_name`', 200, -1, FALSE, '`last_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['last_name'] = &$this->last_name;

		// address_name
		$this->address_name = new cField('bsc_order_detail', 'bsc_order_detail', 'x_address_name', 'address_name', '`address_name`', '`address_name`', 200, -1, FALSE, '`address_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['address_name'] = &$this->address_name;

		// address_country
		$this->address_country = new cField('bsc_order_detail', 'bsc_order_detail', 'x_address_country', 'address_country', '`address_country`', '`address_country`', 200, -1, FALSE, '`address_country`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['address_country'] = &$this->address_country;

		// address_country_code
		$this->address_country_code = new cField('bsc_order_detail', 'bsc_order_detail', 'x_address_country_code', 'address_country_code', '`address_country_code`', '`address_country_code`', 200, -1, FALSE, '`address_country_code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['address_country_code'] = &$this->address_country_code;

		// address_zip
		$this->address_zip = new cField('bsc_order_detail', 'bsc_order_detail', 'x_address_zip', 'address_zip', '`address_zip`', '`address_zip`', 200, -1, FALSE, '`address_zip`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['address_zip'] = &$this->address_zip;

		// address_state
		$this->address_state = new cField('bsc_order_detail', 'bsc_order_detail', 'x_address_state', 'address_state', '`address_state`', '`address_state`', 200, -1, FALSE, '`address_state`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['address_state'] = &$this->address_state;

		// address_city
		$this->address_city = new cField('bsc_order_detail', 'bsc_order_detail', 'x_address_city', 'address_city', '`address_city`', '`address_city`', 200, -1, FALSE, '`address_city`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['address_city'] = &$this->address_city;

		// address_street
		$this->address_street = new cField('bsc_order_detail', 'bsc_order_detail', 'x_address_street', 'address_street', '`address_street`', '`address_street`', 200, -1, FALSE, '`address_street`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['address_street'] = &$this->address_street;

		// item_price
		$this->item_price = new cField('bsc_order_detail', 'bsc_order_detail', 'x_item_price', 'item_price', '`item_price`', '`item_price`', 131, -1, FALSE, '`item_price`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->item_price->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['item_price'] = &$this->item_price;
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

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "bsc_order_header") {
			if ($this->invoice->getSessionValue() <> "")
				$sMasterFilter .= "`invoice`=" . ew_QuotedValue($this->invoice->getSessionValue(), EW_DATATYPE_STRING);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "bsc_order_header") {
			if ($this->invoice->getSessionValue() <> "")
				$sDetailFilter .= "`invoice`=" . ew_QuotedValue($this->invoice->getSessionValue(), EW_DATATYPE_STRING);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_bsc_order_header() {
		return "`invoice`='@invoice@'";
	}

	// Detail filter
	function SqlDetailFilter_bsc_order_header() {
		return "`invoice`='@invoice@'";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`bsc_order_detail`";
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
		return "";
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
	var $UpdateTable = "`bsc_order_detail`";

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
			return "bsc_order_detaillist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "bsc_order_detaillist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("bsc_order_detailview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("bsc_order_detailview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "bsc_order_detailadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("bsc_order_detailedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("bsc_order_detailadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("bsc_order_detaildelete.php", $this->UrlParm());
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// dateorder
		$this->dateorder->ViewValue = $this->dateorder->CurrentValue;
		$this->dateorder->ViewValue = ew_FormatDateTime($this->dateorder->ViewValue, 5);
		$this->dateorder->ViewCustomAttributes = "";

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

		// payment_status
		$this->payment_status->ViewValue = $this->payment_status->CurrentValue;
		$this->payment_status->ViewCustomAttributes = "";

		// payment_amount
		$this->payment_amount->ViewValue = $this->payment_amount->CurrentValue;
		$this->payment_amount->ViewCustomAttributes = "";

		// payment_currency
		$this->payment_currency->ViewValue = $this->payment_currency->CurrentValue;
		$this->payment_currency->ViewCustomAttributes = "";

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

		// item_price
		$this->item_price->ViewValue = $this->item_price->CurrentValue;
		$this->item_price->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// dateorder
		$this->dateorder->LinkCustomAttributes = "";
		$this->dateorder->HrefValue = "";
		$this->dateorder->TooltipValue = "";

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

		// item_price
		$this->item_price->LinkCustomAttributes = "";
		$this->item_price->HrefValue = "";
		$this->item_price->TooltipValue = "";

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
				if ($this->item_number->Exportable) $Doc->ExportCaption($this->item_number);
				if ($this->item_name->Exportable) $Doc->ExportCaption($this->item_name);
				if ($this->quantity->Exportable) $Doc->ExportCaption($this->quantity);
				if ($this->mc_gross->Exportable) $Doc->ExportCaption($this->mc_gross);
				if ($this->item_price->Exportable) $Doc->ExportCaption($this->item_price);
			} else {
				if ($this->item_number->Exportable) $Doc->ExportCaption($this->item_number);
				if ($this->item_name->Exportable) $Doc->ExportCaption($this->item_name);
				if ($this->quantity->Exportable) $Doc->ExportCaption($this->quantity);
				if ($this->mc_gross->Exportable) $Doc->ExportCaption($this->mc_gross);
				if ($this->item_price->Exportable) $Doc->ExportCaption($this->item_price);
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
					if ($this->item_number->Exportable) $Doc->ExportField($this->item_number);
					if ($this->item_name->Exportable) $Doc->ExportField($this->item_name);
					if ($this->quantity->Exportable) $Doc->ExportField($this->quantity);
					if ($this->mc_gross->Exportable) $Doc->ExportField($this->mc_gross);
					if ($this->item_price->Exportable) $Doc->ExportField($this->item_price);
				} else {
					if ($this->item_number->Exportable) $Doc->ExportField($this->item_number);
					if ($this->item_name->Exportable) $Doc->ExportField($this->item_name);
					if ($this->quantity->Exportable) $Doc->ExportField($this->quantity);
					if ($this->mc_gross->Exportable) $Doc->ExportField($this->mc_gross);
					if ($this->item_price->Exportable) $Doc->ExportField($this->item_price);
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
