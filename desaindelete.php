<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "desaininfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$desain_delete = NULL; // Initialize page object first

class cdesain_delete extends cdesain {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{5641CEBD-AA62-4C55-9718-A8075144C930}";

	// Table name
	var $TableName = 'desain';

	// Page object name
	var $PageObjName = 'desain_delete';

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

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
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
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
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
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
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
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (desain)
		if (!isset($GLOBALS["desain"]) || get_class($GLOBALS["desain"]) == "cdesain") {
			$GLOBALS["desain"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["desain"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'desain', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (user)
		if (!isset($UserTable)) {
			$UserTable = new cuser();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("desainlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
				$this->Page_Terminate(ew_GetUrl("desainlist.php"));
			}
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->OrderID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $desain;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($desain);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

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
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("desainlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in desain class, desaininfo.php

		$this->CurrentFilter = $sFilter;

		// Check if valid user id
		$conn = &$this->Connection();
		$sql = $this->GetSQL($this->CurrentFilter, "");
		if ($this->Recordset = ew_LoadRecordset($sql, $conn)) {
			$res = TRUE;
			while (!$this->Recordset->EOF) {
				$this->LoadRowValues($this->Recordset);
				if (!$this->ShowOptionLink('delete')) {
					$sUserIdMsg = $Language->Phrase("NoDeletePermission");
					$this->setFailureMessage($sUserIdMsg);
					$res = FALSE;
					break;
				}
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
			if (!$res) $this->Page_Terminate("desainlist.php"); // Return to list
		}

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("desainlist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->OrderID->setDbValue($rs->fields('OrderID'));
		$this->_UserID->setDbValue($rs->fields('UserID'));
		$this->PCBName->setDbValue($rs->fields('PCBName'));
		$this->MakeFrom->setDbValue($rs->fields('MakeFrom'));
		$this->AmountOfComponents->setDbValue($rs->fields('AmountOfComponents'));
		$this->Program->setDbValue($rs->fields('Program'));
		$this->TotalI2FO->setDbValue($rs->fields('TotalI/O'));
		$this->TotalFunction->setDbValue($rs->fields('TotalFunction'));
		$this->File->Upload->DbValue = $rs->fields('File');
		if (is_array($this->File->Upload->DbValue) || is_object($this->File->Upload->DbValue)) // Byte array
			$this->File->Upload->DbValue = ew_BytesToStr($this->File->Upload->DbValue);
		$this->DaysOfWorks->setDbValue($rs->fields('DaysOfWorks'));
		$this->TotalDesignCost->setDbValue($rs->fields('TotalDesignCost'));
		$this->StatusOrderID->setDbValue($rs->fields('StatusOrderID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->OrderID->DbValue = $row['OrderID'];
		$this->_UserID->DbValue = $row['UserID'];
		$this->PCBName->DbValue = $row['PCBName'];
		$this->MakeFrom->DbValue = $row['MakeFrom'];
		$this->AmountOfComponents->DbValue = $row['AmountOfComponents'];
		$this->Program->DbValue = $row['Program'];
		$this->TotalI2FO->DbValue = $row['TotalI/O'];
		$this->TotalFunction->DbValue = $row['TotalFunction'];
		$this->File->Upload->DbValue = $row['File'];
		$this->DaysOfWorks->DbValue = $row['DaysOfWorks'];
		$this->TotalDesignCost->DbValue = $row['TotalDesignCost'];
		$this->StatusOrderID->DbValue = $row['StatusOrderID'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->TotalDesignCost->FormValue == $this->TotalDesignCost->CurrentValue && is_numeric(ew_StrToFloat($this->TotalDesignCost->CurrentValue)))
			$this->TotalDesignCost->CurrentValue = ew_StrToFloat($this->TotalDesignCost->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// Program
			$this->Program->LinkCustomAttributes = "";
			$this->Program->HrefValue = "";
			$this->Program->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
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
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
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
				$sThisKey .= $row['OrderID'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
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
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->_UserID->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("desainlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($desain_delete)) $desain_delete = new cdesain_delete();

// Page init
$desain_delete->Page_Init();

// Page main
$desain_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$desain_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fdesaindelete = new ew_Form("fdesaindelete", "delete");

// Form_CustomValidate event
fdesaindelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdesaindelete.ValidateRequired = true;
<?php } else { ?>
fdesaindelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdesaindelete.Lists["x__UserID"] = {"LinkField":"x__UserID","Ajax":true,"AutoFill":false,"DisplayFields":["x_NamaLengkap","x_NomorHP","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdesaindelete.Lists["x_MakeFrom"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdesaindelete.Lists["x_MakeFrom"].Options = <?php echo json_encode($desain->MakeFrom->Options()) ?>;
fdesaindelete.Lists["x_Program"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdesaindelete.Lists["x_Program"].Options = <?php echo json_encode($desain->Program->Options()) ?>;
fdesaindelete.Lists["x_StatusOrderID"] = {"LinkField":"x_StatusOrderID","Ajax":true,"AutoFill":false,"DisplayFields":["x_StatusOrder","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $desain_delete->ShowPageHeader(); ?>
<?php
$desain_delete->ShowMessage();
?>
<form name="fdesaindelete" id="fdesaindelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($desain_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $desain_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="desain">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($desain_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $desain->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($desain->OrderID->Visible) { // OrderID ?>
		<th><span id="elh_desain_OrderID" class="desain_OrderID"><?php echo $desain->OrderID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($desain->_UserID->Visible) { // UserID ?>
		<th><span id="elh_desain__UserID" class="desain__UserID"><?php echo $desain->_UserID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($desain->PCBName->Visible) { // PCBName ?>
		<th><span id="elh_desain_PCBName" class="desain_PCBName"><?php echo $desain->PCBName->FldCaption() ?></span></th>
<?php } ?>
<?php if ($desain->MakeFrom->Visible) { // MakeFrom ?>
		<th><span id="elh_desain_MakeFrom" class="desain_MakeFrom"><?php echo $desain->MakeFrom->FldCaption() ?></span></th>
<?php } ?>
<?php if ($desain->Program->Visible) { // Program ?>
		<th><span id="elh_desain_Program" class="desain_Program"><?php echo $desain->Program->FldCaption() ?></span></th>
<?php } ?>
<?php if ($desain->DaysOfWorks->Visible) { // DaysOfWorks ?>
		<th><span id="elh_desain_DaysOfWorks" class="desain_DaysOfWorks"><?php echo $desain->DaysOfWorks->FldCaption() ?></span></th>
<?php } ?>
<?php if ($desain->TotalDesignCost->Visible) { // TotalDesignCost ?>
		<th><span id="elh_desain_TotalDesignCost" class="desain_TotalDesignCost"><?php echo $desain->TotalDesignCost->FldCaption() ?></span></th>
<?php } ?>
<?php if ($desain->StatusOrderID->Visible) { // StatusOrderID ?>
		<th><span id="elh_desain_StatusOrderID" class="desain_StatusOrderID"><?php echo $desain->StatusOrderID->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$desain_delete->RecCnt = 0;
$i = 0;
while (!$desain_delete->Recordset->EOF) {
	$desain_delete->RecCnt++;
	$desain_delete->RowCnt++;

	// Set row properties
	$desain->ResetAttrs();
	$desain->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$desain_delete->LoadRowValues($desain_delete->Recordset);

	// Render row
	$desain_delete->RenderRow();
?>
	<tr<?php echo $desain->RowAttributes() ?>>
<?php if ($desain->OrderID->Visible) { // OrderID ?>
		<td<?php echo $desain->OrderID->CellAttributes() ?>>
<span id="el<?php echo $desain_delete->RowCnt ?>_desain_OrderID" class="desain_OrderID">
<span<?php echo $desain->OrderID->ViewAttributes() ?>>
<?php echo $desain->OrderID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($desain->_UserID->Visible) { // UserID ?>
		<td<?php echo $desain->_UserID->CellAttributes() ?>>
<span id="el<?php echo $desain_delete->RowCnt ?>_desain__UserID" class="desain__UserID">
<span<?php echo $desain->_UserID->ViewAttributes() ?>>
<?php echo $desain->_UserID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($desain->PCBName->Visible) { // PCBName ?>
		<td<?php echo $desain->PCBName->CellAttributes() ?>>
<span id="el<?php echo $desain_delete->RowCnt ?>_desain_PCBName" class="desain_PCBName">
<span<?php echo $desain->PCBName->ViewAttributes() ?>>
<?php echo $desain->PCBName->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($desain->MakeFrom->Visible) { // MakeFrom ?>
		<td<?php echo $desain->MakeFrom->CellAttributes() ?>>
<span id="el<?php echo $desain_delete->RowCnt ?>_desain_MakeFrom" class="desain_MakeFrom">
<span<?php echo $desain->MakeFrom->ViewAttributes() ?>>
<?php echo $desain->MakeFrom->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($desain->Program->Visible) { // Program ?>
		<td<?php echo $desain->Program->CellAttributes() ?>>
<span id="el<?php echo $desain_delete->RowCnt ?>_desain_Program" class="desain_Program">
<span<?php echo $desain->Program->ViewAttributes() ?>>
<?php echo $desain->Program->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($desain->DaysOfWorks->Visible) { // DaysOfWorks ?>
		<td<?php echo $desain->DaysOfWorks->CellAttributes() ?>>
<span id="el<?php echo $desain_delete->RowCnt ?>_desain_DaysOfWorks" class="desain_DaysOfWorks">
<span<?php echo $desain->DaysOfWorks->ViewAttributes() ?>>
<?php echo $desain->DaysOfWorks->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($desain->TotalDesignCost->Visible) { // TotalDesignCost ?>
		<td<?php echo $desain->TotalDesignCost->CellAttributes() ?>>
<span id="el<?php echo $desain_delete->RowCnt ?>_desain_TotalDesignCost" class="desain_TotalDesignCost">
<span<?php echo $desain->TotalDesignCost->ViewAttributes() ?>>
<?php echo $desain->TotalDesignCost->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($desain->StatusOrderID->Visible) { // StatusOrderID ?>
		<td<?php echo $desain->StatusOrderID->CellAttributes() ?>>
<span id="el<?php echo $desain_delete->RowCnt ?>_desain_StatusOrderID" class="desain_StatusOrderID">
<span<?php echo $desain->StatusOrderID->ViewAttributes() ?>>
<?php echo $desain->StatusOrderID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$desain_delete->Recordset->MoveNext();
}
$desain_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $desain_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fdesaindelete.Init();
</script>
<?php
$desain_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$desain_delete->Page_Terminate();
?>
