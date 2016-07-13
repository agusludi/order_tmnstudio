<?php

// Global variable for table object
$desain = NULL;

//
// Table class for desain
//
class cdesain extends cTable {
	var $OrderID;
	var $_UserID;
	var $PCBName;
	var $MakeFrom;
	var $AmountOfComponents;
	var $Program;
	var $TotalI2FO;
	var $TotalFunction;
	var $File;
	var $DaysOfWorks;
	var $TotalDesignCost;
	var $StatusOrderID;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'desain';
		$this->TableName = 'desain';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`desain`";
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// OrderID
		$this->OrderID = new cField('desain', 'desain', 'x_OrderID', 'OrderID', '`OrderID`', '`OrderID`', 3, -1, FALSE, '`OrderID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->OrderID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['OrderID'] = &$this->OrderID;

		// UserID
		$this->_UserID = new cField('desain', 'desain', 'x__UserID', 'UserID', '`UserID`', '`UserID`', 3, -1, FALSE, '`UserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->_UserID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['UserID'] = &$this->_UserID;

		// PCBName
		$this->PCBName = new cField('desain', 'desain', 'x_PCBName', 'PCBName', '`PCBName`', '`PCBName`', 200, -1, FALSE, '`PCBName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['PCBName'] = &$this->PCBName;

		// MakeFrom
		$this->MakeFrom = new cField('desain', 'desain', 'x_MakeFrom', 'MakeFrom', '`MakeFrom`', '`MakeFrom`', 202, -1, FALSE, '`MakeFrom`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->MakeFrom->OptionCount = 2;
		$this->fields['MakeFrom'] = &$this->MakeFrom;

		// AmountOfComponents
		$this->AmountOfComponents = new cField('desain', 'desain', 'x_AmountOfComponents', 'AmountOfComponents', '`AmountOfComponents`', '`AmountOfComponents`', 3, -1, FALSE, '`AmountOfComponents`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->AmountOfComponents->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['AmountOfComponents'] = &$this->AmountOfComponents;

		// Program
		$this->Program = new cField('desain', 'desain', 'x_Program', 'Program', '`Program`', '`Program`', 202, -1, FALSE, '`Program`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->Program->OptionCount = 2;
		$this->fields['Program'] = &$this->Program;

		// TotalI/O
		$this->TotalI2FO = new cField('desain', 'desain', 'x_TotalI2FO', 'TotalI/O', '`TotalI/O`', '`TotalI/O`', 3, -1, FALSE, '`TotalI/O`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->TotalI2FO->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['TotalI/O'] = &$this->TotalI2FO;

		// TotalFunction
		$this->TotalFunction = new cField('desain', 'desain', 'x_TotalFunction', 'TotalFunction', '`TotalFunction`', '`TotalFunction`', 3, -1, FALSE, '`TotalFunction`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->TotalFunction->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['TotalFunction'] = &$this->TotalFunction;

		// File
		$this->File = new cField('desain', 'desain', 'x_File', 'File', '`File`', '`File`', 205, -1, TRUE, '`File`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->File->UploadAllowedFileExt = "zip,rar,pdf,jpg,jpeg,png,brd,sch";
		$this->File->UploadMaxFileSize = 2000000;
		$this->fields['File'] = &$this->File;

		// DaysOfWorks
		$this->DaysOfWorks = new cField('desain', 'desain', 'x_DaysOfWorks', 'DaysOfWorks', '`DaysOfWorks`', 'DATE_FORMAT(`DaysOfWorks`, \'%Y/%m/%d\')', 135, 7, FALSE, '`DaysOfWorks`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->DaysOfWorks->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['DaysOfWorks'] = &$this->DaysOfWorks;

		// TotalDesignCost
		$this->TotalDesignCost = new cField('desain', 'desain', 'x_TotalDesignCost', 'TotalDesignCost', '`TotalDesignCost`', '`TotalDesignCost`', 4, -1, FALSE, '`TotalDesignCost`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->TotalDesignCost->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['TotalDesignCost'] = &$this->TotalDesignCost;

		// StatusOrderID
		$this->StatusOrderID = new cField('desain', 'desain', 'x_StatusOrderID', 'StatusOrderID', '`StatusOrderID`', '`StatusOrderID`', 3, -1, FALSE, '`StatusOrderID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->StatusOrderID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['StatusOrderID'] = &$this->StatusOrderID;
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

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`desain`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		global $Security;

		// Add User ID filter
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
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
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
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
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('OrderID', $rs))
				ew_AddFilter($where, ew_QuotedName('OrderID', $this->DBID) . '=' . ew_QuotedValue($rs['OrderID'], $this->OrderID->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`OrderID` = @OrderID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->OrderID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@OrderID@", ew_AdjustSql($this->OrderID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "desainlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "desainlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("desainview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("desainview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "desainadd.php?" . $this->UrlParm($parm);
		else
			$url = "desainadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("desainedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("desainadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("desaindelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "OrderID:" . ew_VarToJson($this->OrderID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->OrderID->CurrentValue)) {
			$sUrl .= "OrderID=" . urlencode($this->OrderID->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
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
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["OrderID"]))
				$arKeys[] = ew_StripSlashes($_POST["OrderID"]);
			elseif (isset($_GET["OrderID"]))
				$arKeys[] = ew_StripSlashes($_GET["OrderID"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->OrderID->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->OrderID->setDbValue($rs->fields('OrderID'));
		$this->_UserID->setDbValue($rs->fields('UserID'));
		$this->PCBName->setDbValue($rs->fields('PCBName'));
		$this->MakeFrom->setDbValue($rs->fields('MakeFrom'));
		$this->AmountOfComponents->setDbValue($rs->fields('AmountOfComponents'));
		$this->Program->setDbValue($rs->fields('Program'));
		$this->TotalI2FO->setDbValue($rs->fields('TotalI/O'));
		$this->TotalFunction->setDbValue($rs->fields('TotalFunction'));
		$this->File->Upload->DbValue = $rs->fields('File');
		$this->DaysOfWorks->setDbValue($rs->fields('DaysOfWorks'));
		$this->TotalDesignCost->setDbValue($rs->fields('TotalDesignCost'));
		$this->StatusOrderID->setDbValue($rs->fields('StatusOrderID'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// OrderID
		// UserID
		// PCBName
		// MakeFrom
		// AmountOfComponents
		// Program
		// TotalI/O
		// TotalFunction
		// File
		// DaysOfWorks
		// TotalDesignCost
		// StatusOrderID
		// OrderID

		$this->OrderID->ViewValue = $this->OrderID->CurrentValue;
		$this->OrderID->ViewCustomAttributes = "";

		// UserID
		if (strval($this->_UserID->CurrentValue) <> "") {
			$sFilterWrk = "`UserID`" . ew_SearchString("=", $this->_UserID->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `UserID`, `NamaLengkap` AS `DispFld`, `NomorHP` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->_UserID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `UserID` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->_UserID->ViewValue = $this->_UserID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->_UserID->ViewValue = $this->_UserID->CurrentValue;
			}
		} else {
			$this->_UserID->ViewValue = NULL;
		}
		$this->_UserID->ViewCustomAttributes = "";

		// PCBName
		$this->PCBName->ViewValue = $this->PCBName->CurrentValue;
		$this->PCBName->ViewCustomAttributes = "";

		// MakeFrom
		if (strval($this->MakeFrom->CurrentValue) <> "") {
			$this->MakeFrom->ViewValue = $this->MakeFrom->OptionCaption($this->MakeFrom->CurrentValue);
		} else {
			$this->MakeFrom->ViewValue = NULL;
		}
		$this->MakeFrom->ViewCustomAttributes = "";

		// AmountOfComponents
		$this->AmountOfComponents->ViewValue = $this->AmountOfComponents->CurrentValue;
		$this->AmountOfComponents->ViewCustomAttributes = "";

		// Program
		if (strval($this->Program->CurrentValue) <> "") {
			$this->Program->ViewValue = $this->Program->OptionCaption($this->Program->CurrentValue);
		} else {
			$this->Program->ViewValue = NULL;
		}
		$this->Program->ViewCustomAttributes = "";

		// TotalI/O
		$this->TotalI2FO->ViewValue = $this->TotalI2FO->CurrentValue;
		$this->TotalI2FO->ViewCustomAttributes = "";

		// TotalFunction
		$this->TotalFunction->ViewValue = $this->TotalFunction->CurrentValue;
		$this->TotalFunction->ViewCustomAttributes = "";

		// File
		if (!ew_Empty($this->File->Upload->DbValue)) {
			$this->File->ViewValue = "desain_File_bv.php?" . "OrderID=" . $this->OrderID->CurrentValue;
			$this->File->IsBlobImage = ew_IsImageFile("image" . ew_ContentExt(substr($this->File->Upload->DbValue, 0, 11)));
			$this->File->Upload->FileName = $this->PCBName->CurrentValue;
		} else {
			$this->File->ViewValue = "";
		}
		$this->File->ViewCustomAttributes = "";

		// DaysOfWorks
		$this->DaysOfWorks->ViewValue = $this->DaysOfWorks->CurrentValue;
		$this->DaysOfWorks->ViewValue = ew_FormatDateTime($this->DaysOfWorks->ViewValue, 7);
		$this->DaysOfWorks->CssStyle = "font-weight: bold;";
		$this->DaysOfWorks->ViewCustomAttributes = "";

		// TotalDesignCost
		$this->TotalDesignCost->ViewValue = $this->TotalDesignCost->CurrentValue;
		$this->TotalDesignCost->ViewValue = ew_FormatNumber($this->TotalDesignCost->ViewValue, 0, -2, -2, -2);
		$this->TotalDesignCost->CssStyle = "font-weight: bold;";
		$this->TotalDesignCost->ViewCustomAttributes = "";

		// StatusOrderID
		if (strval($this->StatusOrderID->CurrentValue) <> "") {
			$sFilterWrk = "`StatusOrderID`" . ew_SearchString("=", $this->StatusOrderID->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `StatusOrderID`, `StatusOrder` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status_order`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->StatusOrderID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->StatusOrderID->ViewValue = $this->StatusOrderID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->StatusOrderID->ViewValue = $this->StatusOrderID->CurrentValue;
			}
		} else {
			$this->StatusOrderID->ViewValue = NULL;
		}
		$this->StatusOrderID->ViewCustomAttributes = "";

		// OrderID
		$this->OrderID->LinkCustomAttributes = "";
		$this->OrderID->HrefValue = "";
		$this->OrderID->TooltipValue = "";

		// UserID
		$this->_UserID->LinkCustomAttributes = "";
		$this->_UserID->HrefValue = "";
		$this->_UserID->TooltipValue = "";

		// PCBName
		$this->PCBName->LinkCustomAttributes = "";
		$this->PCBName->HrefValue = "";
		$this->PCBName->TooltipValue = "";

		// MakeFrom
		$this->MakeFrom->LinkCustomAttributes = "";
		$this->MakeFrom->HrefValue = "";
		$this->MakeFrom->TooltipValue = "";

		// AmountOfComponents
		$this->AmountOfComponents->LinkCustomAttributes = "";
		$this->AmountOfComponents->HrefValue = "";
		$this->AmountOfComponents->TooltipValue = "";

		// Program
		$this->Program->LinkCustomAttributes = "";
		$this->Program->HrefValue = "";
		$this->Program->TooltipValue = "";

		// TotalI/O
		$this->TotalI2FO->LinkCustomAttributes = "";
		$this->TotalI2FO->HrefValue = "";
		$this->TotalI2FO->TooltipValue = "";

		// TotalFunction
		$this->TotalFunction->LinkCustomAttributes = "";
		$this->TotalFunction->HrefValue = "";
		$this->TotalFunction->TooltipValue = "";

		// File
		$this->File->LinkCustomAttributes = "";
		if (!empty($this->File->Upload->DbValue)) {
			$this->File->HrefValue = "desain_File_bv.php?OrderID=" . $this->OrderID->CurrentValue;
			$this->File->LinkAttrs["target"] = "_blank";
			if ($this->Export <> "") $this->File->HrefValue = ew_ConvertFullUrl($this->File->HrefValue);
		} else {
			$this->File->HrefValue = "";
		}
		$this->File->HrefValue2 = "desain_File_bv.php?OrderID=" . $this->OrderID->CurrentValue;
		$this->File->TooltipValue = "";

		// DaysOfWorks
		$this->DaysOfWorks->LinkCustomAttributes = "";
		$this->DaysOfWorks->HrefValue = "";
		$this->DaysOfWorks->TooltipValue = "";

		// TotalDesignCost
		$this->TotalDesignCost->LinkCustomAttributes = "";
		$this->TotalDesignCost->HrefValue = "";
		$this->TotalDesignCost->TooltipValue = "";

		// StatusOrderID
		$this->StatusOrderID->LinkCustomAttributes = "";
		$this->StatusOrderID->HrefValue = "";
		$this->StatusOrderID->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// OrderID
		$this->OrderID->EditAttrs["class"] = "form-control";
		$this->OrderID->EditCustomAttributes = "";
		$this->OrderID->EditValue = $this->OrderID->CurrentValue;
		$this->OrderID->ViewCustomAttributes = "";

		// UserID
		$this->_UserID->EditAttrs["class"] = "form-control";
		$this->_UserID->EditCustomAttributes = "";
		if (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$this->UserIDAllow("info")) { // Non system admin
		$sFilterWrk = "";
		$sFilterWrk = $GLOBALS["user"]->AddUserIDFilter("");
		$sSqlWrk = "SELECT `UserID`, `NamaLengkap` AS `DispFld`, `NomorHP` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `user`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->_UserID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `UserID` ASC";
		$rswrk = Conn()->Execute($sSqlWrk);
		$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
		if ($rswrk) $rswrk->Close();
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
		$this->_UserID->EditValue = $arwrk;
		} else {
		}

		// PCBName
		$this->PCBName->EditAttrs["class"] = "form-control";
		$this->PCBName->EditCustomAttributes = "";
		$this->PCBName->EditValue = $this->PCBName->CurrentValue;
		$this->PCBName->PlaceHolder = ew_RemoveHtml($this->PCBName->FldCaption());

		// MakeFrom
		$this->MakeFrom->EditCustomAttributes = "";
		$this->MakeFrom->EditValue = $this->MakeFrom->Options(FALSE);

		// AmountOfComponents
		$this->AmountOfComponents->EditAttrs["class"] = "form-control";
		$this->AmountOfComponents->EditCustomAttributes = "";
		$this->AmountOfComponents->EditValue = $this->AmountOfComponents->CurrentValue;
		$this->AmountOfComponents->PlaceHolder = ew_RemoveHtml($this->AmountOfComponents->FldCaption());

		// Program
		$this->Program->EditCustomAttributes = "";
		$this->Program->EditValue = $this->Program->Options(FALSE);

		// TotalI/O
		$this->TotalI2FO->EditAttrs["class"] = "form-control";
		$this->TotalI2FO->EditCustomAttributes = "";
		$this->TotalI2FO->EditValue = $this->TotalI2FO->CurrentValue;
		$this->TotalI2FO->PlaceHolder = ew_RemoveHtml($this->TotalI2FO->FldCaption());

		// TotalFunction
		$this->TotalFunction->EditAttrs["class"] = "form-control";
		$this->TotalFunction->EditCustomAttributes = "";
		$this->TotalFunction->EditValue = $this->TotalFunction->CurrentValue;
		$this->TotalFunction->PlaceHolder = ew_RemoveHtml($this->TotalFunction->FldCaption());

		// File
		$this->File->EditAttrs["class"] = "form-control";
		$this->File->EditCustomAttributes = "";
		if (!ew_Empty($this->File->Upload->DbValue)) {
			$this->File->EditValue = "desain_File_bv.php?" . "OrderID=" . $this->OrderID->CurrentValue;
			$this->File->IsBlobImage = ew_IsImageFile("image" . ew_ContentExt(substr($this->File->Upload->DbValue, 0, 11)));
			$this->File->Upload->FileName = $this->PCBName->CurrentValue;
		} else {
			$this->File->EditValue = "";
		}
		if (!ew_Empty($this->PCBName->CurrentValue))
			$this->File->Upload->FileName = $this->PCBName->CurrentValue;

		// DaysOfWorks
		$this->DaysOfWorks->EditAttrs["class"] = "form-control";
		$this->DaysOfWorks->EditCustomAttributes = "";
		$this->DaysOfWorks->EditValue = ew_FormatDateTime($this->DaysOfWorks->CurrentValue, 7);
		$this->DaysOfWorks->PlaceHolder = ew_RemoveHtml($this->DaysOfWorks->FldCaption());

		// TotalDesignCost
		$this->TotalDesignCost->EditAttrs["class"] = "form-control";
		$this->TotalDesignCost->EditCustomAttributes = "";
		$this->TotalDesignCost->EditValue = $this->TotalDesignCost->CurrentValue;
		$this->TotalDesignCost->PlaceHolder = ew_RemoveHtml($this->TotalDesignCost->FldCaption());
		if (strval($this->TotalDesignCost->EditValue) <> "" && is_numeric($this->TotalDesignCost->EditValue)) $this->TotalDesignCost->EditValue = ew_FormatNumber($this->TotalDesignCost->EditValue, -2, -2, -2, -2);

		// StatusOrderID
		$this->StatusOrderID->EditCustomAttributes = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->OrderID->Exportable) $Doc->ExportCaption($this->OrderID);
					if ($this->_UserID->Exportable) $Doc->ExportCaption($this->_UserID);
					if ($this->PCBName->Exportable) $Doc->ExportCaption($this->PCBName);
					if ($this->MakeFrom->Exportable) $Doc->ExportCaption($this->MakeFrom);
					if ($this->AmountOfComponents->Exportable) $Doc->ExportCaption($this->AmountOfComponents);
					if ($this->Program->Exportable) $Doc->ExportCaption($this->Program);
					if ($this->TotalI2FO->Exportable) $Doc->ExportCaption($this->TotalI2FO);
					if ($this->TotalFunction->Exportable) $Doc->ExportCaption($this->TotalFunction);
					if ($this->File->Exportable) $Doc->ExportCaption($this->File);
					if ($this->DaysOfWorks->Exportable) $Doc->ExportCaption($this->DaysOfWorks);
					if ($this->TotalDesignCost->Exportable) $Doc->ExportCaption($this->TotalDesignCost);
					if ($this->StatusOrderID->Exportable) $Doc->ExportCaption($this->StatusOrderID);
				} else {
					if ($this->OrderID->Exportable) $Doc->ExportCaption($this->OrderID);
					if ($this->_UserID->Exportable) $Doc->ExportCaption($this->_UserID);
					if ($this->PCBName->Exportable) $Doc->ExportCaption($this->PCBName);
					if ($this->MakeFrom->Exportable) $Doc->ExportCaption($this->MakeFrom);
					if ($this->AmountOfComponents->Exportable) $Doc->ExportCaption($this->AmountOfComponents);
					if ($this->Program->Exportable) $Doc->ExportCaption($this->Program);
					if ($this->TotalI2FO->Exportable) $Doc->ExportCaption($this->TotalI2FO);
					if ($this->TotalFunction->Exportable) $Doc->ExportCaption($this->TotalFunction);
					if ($this->DaysOfWorks->Exportable) $Doc->ExportCaption($this->DaysOfWorks);
					if ($this->TotalDesignCost->Exportable) $Doc->ExportCaption($this->TotalDesignCost);
					if ($this->StatusOrderID->Exportable) $Doc->ExportCaption($this->StatusOrderID);
				}
				$Doc->EndExportRow();
			}
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
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->OrderID->Exportable) $Doc->ExportField($this->OrderID);
						if ($this->_UserID->Exportable) $Doc->ExportField($this->_UserID);
						if ($this->PCBName->Exportable) $Doc->ExportField($this->PCBName);
						if ($this->MakeFrom->Exportable) $Doc->ExportField($this->MakeFrom);
						if ($this->AmountOfComponents->Exportable) $Doc->ExportField($this->AmountOfComponents);
						if ($this->Program->Exportable) $Doc->ExportField($this->Program);
						if ($this->TotalI2FO->Exportable) $Doc->ExportField($this->TotalI2FO);
						if ($this->TotalFunction->Exportable) $Doc->ExportField($this->TotalFunction);
						if ($this->File->Exportable) $Doc->ExportField($this->File);
						if ($this->DaysOfWorks->Exportable) $Doc->ExportField($this->DaysOfWorks);
						if ($this->TotalDesignCost->Exportable) $Doc->ExportField($this->TotalDesignCost);
						if ($this->StatusOrderID->Exportable) $Doc->ExportField($this->StatusOrderID);
					} else {
						if ($this->OrderID->Exportable) $Doc->ExportField($this->OrderID);
						if ($this->_UserID->Exportable) $Doc->ExportField($this->_UserID);
						if ($this->PCBName->Exportable) $Doc->ExportField($this->PCBName);
						if ($this->MakeFrom->Exportable) $Doc->ExportField($this->MakeFrom);
						if ($this->AmountOfComponents->Exportable) $Doc->ExportField($this->AmountOfComponents);
						if ($this->Program->Exportable) $Doc->ExportField($this->Program);
						if ($this->TotalI2FO->Exportable) $Doc->ExportField($this->TotalI2FO);
						if ($this->TotalFunction->Exportable) $Doc->ExportField($this->TotalFunction);
						if ($this->DaysOfWorks->Exportable) $Doc->ExportField($this->DaysOfWorks);
						if ($this->TotalDesignCost->Exportable) $Doc->ExportField($this->TotalDesignCost);
						if ($this->StatusOrderID->Exportable) $Doc->ExportField($this->StatusOrderID);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '`UserID` IN (' . $sFilterWrk . ')';
		}

		// Call User ID Filtering event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $UserTableConn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `desain`";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $UserTableConn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType, EW_USER_TABLE_DBID);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
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

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
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

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
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
