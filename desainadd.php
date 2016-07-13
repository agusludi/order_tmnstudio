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

$desain_add = NULL; // Initialize page object first

class cdesain_add extends cdesain {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{5641CEBD-AA62-4C55-9718-A8075144C930}";

	// Table name
	var $TableName = 'desain';

	// Page object name
	var $PageObjName = 'desain_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
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
			if (@$_GET["OrderID"] != "") {
				$this->OrderID->setQueryStringValue($_GET["OrderID"]);
				$this->setKey("OrderID", $this->OrderID->CurrentValue); // Set up key
			} else {
				$this->setKey("OrderID", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("desainlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "desainlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "desainview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->File->Upload->Index = $objForm->Index;
		$this->File->Upload->UploadFile();
		$this->PCBName->CurrentValue = $this->File->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->_UserID->CurrentValue = CurrentUserID();
		$this->PCBName->CurrentValue = NULL;
		$this->PCBName->OldValue = $this->PCBName->CurrentValue;
		$this->PCBName->CurrentValue = NULL; // Clear file related field
		$this->MakeFrom->CurrentValue = "PCB Clone";
		$this->AmountOfComponents->CurrentValue = NULL;
		$this->AmountOfComponents->OldValue = $this->AmountOfComponents->CurrentValue;
		$this->Program->CurrentValue = "Ya";
		$this->TotalI2FO->CurrentValue = NULL;
		$this->TotalI2FO->OldValue = $this->TotalI2FO->CurrentValue;
		$this->TotalFunction->CurrentValue = NULL;
		$this->TotalFunction->OldValue = $this->TotalFunction->CurrentValue;
		$this->File->Upload->DbValue = NULL;
		$this->File->OldValue = $this->File->Upload->DbValue;
		$this->DaysOfWorks->CurrentValue = NULL;
		$this->DaysOfWorks->OldValue = $this->DaysOfWorks->CurrentValue;
		$this->TotalDesignCost->CurrentValue = NULL;
		$this->TotalDesignCost->OldValue = $this->TotalDesignCost->CurrentValue;
		$this->StatusOrderID->CurrentValue = NULL;
		$this->StatusOrderID->OldValue = $this->StatusOrderID->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->_UserID->FldIsDetailKey) {
			$this->_UserID->setFormValue($objForm->GetValue("x__UserID"));
		}
		if (!$this->PCBName->FldIsDetailKey) {
			$this->PCBName->setFormValue($objForm->GetValue("x_PCBName"));
		}
		if (!$this->MakeFrom->FldIsDetailKey) {
			$this->MakeFrom->setFormValue($objForm->GetValue("x_MakeFrom"));
		}
		if (!$this->AmountOfComponents->FldIsDetailKey) {
			$this->AmountOfComponents->setFormValue($objForm->GetValue("x_AmountOfComponents"));
		}
		if (!$this->Program->FldIsDetailKey) {
			$this->Program->setFormValue($objForm->GetValue("x_Program"));
		}
		if (!$this->TotalI2FO->FldIsDetailKey) {
			$this->TotalI2FO->setFormValue($objForm->GetValue("x_TotalI2FO"));
		}
		if (!$this->TotalFunction->FldIsDetailKey) {
			$this->TotalFunction->setFormValue($objForm->GetValue("x_TotalFunction"));
		}
		if (!$this->DaysOfWorks->FldIsDetailKey) {
			$this->DaysOfWorks->setFormValue($objForm->GetValue("x_DaysOfWorks"));
			$this->DaysOfWorks->CurrentValue = ew_UnFormatDateTime($this->DaysOfWorks->CurrentValue, 7);
		}
		if (!$this->TotalDesignCost->FldIsDetailKey) {
			$this->TotalDesignCost->setFormValue($objForm->GetValue("x_TotalDesignCost"));
		}
		if (!$this->StatusOrderID->FldIsDetailKey) {
			$this->StatusOrderID->setFormValue($objForm->GetValue("x_StatusOrderID"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->_UserID->CurrentValue = $this->_UserID->FormValue;
		$this->MakeFrom->CurrentValue = $this->MakeFrom->FormValue;
		$this->AmountOfComponents->CurrentValue = $this->AmountOfComponents->FormValue;
		$this->Program->CurrentValue = $this->Program->FormValue;
		$this->TotalI2FO->CurrentValue = $this->TotalI2FO->FormValue;
		$this->TotalFunction->CurrentValue = $this->TotalFunction->FormValue;
		$this->DaysOfWorks->CurrentValue = $this->DaysOfWorks->FormValue;
		$this->DaysOfWorks->CurrentValue = ew_UnFormatDateTime($this->DaysOfWorks->CurrentValue, 7);
		$this->TotalDesignCost->CurrentValue = $this->TotalDesignCost->FormValue;
		$this->StatusOrderID->CurrentValue = $this->StatusOrderID->FormValue;
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('add');
			if (!$res) {
				$sUserIdMsg = ew_DeniedMsg();
				$this->setFailureMessage($sUserIdMsg);
			}
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("OrderID")) <> "")
			$this->OrderID->CurrentValue = $this->getKey("OrderID"); // OrderID
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// UserID
			$this->_UserID->EditAttrs["class"] = "form-control";
			$this->_UserID->EditCustomAttributes = "";
			if (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$this->UserIDAllow("add")) { // Non system admin
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
			if (trim(strval($this->_UserID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`UserID`" . ew_SearchString("=", $this->_UserID->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `UserID`, `NamaLengkap` AS `DispFld`, `NomorHP` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `user`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			if (!$GLOBALS["desain"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["user"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->_UserID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `UserID` ASC";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->_UserID->EditValue = $arwrk;
			}

			// PCBName
			$this->PCBName->EditAttrs["class"] = "form-control";
			$this->PCBName->EditCustomAttributes = "";
			$this->PCBName->EditValue = ew_HtmlEncode($this->PCBName->CurrentValue);
			$this->PCBName->PlaceHolder = ew_RemoveHtml($this->PCBName->FldCaption());

			// MakeFrom
			$this->MakeFrom->EditCustomAttributes = "";
			$this->MakeFrom->EditValue = $this->MakeFrom->Options(FALSE);

			// AmountOfComponents
			$this->AmountOfComponents->EditAttrs["class"] = "form-control";
			$this->AmountOfComponents->EditCustomAttributes = "";
			$this->AmountOfComponents->EditValue = ew_HtmlEncode($this->AmountOfComponents->CurrentValue);
			$this->AmountOfComponents->PlaceHolder = ew_RemoveHtml($this->AmountOfComponents->FldCaption());

			// Program
			$this->Program->EditCustomAttributes = "";
			$this->Program->EditValue = $this->Program->Options(FALSE);

			// TotalI/O
			$this->TotalI2FO->EditAttrs["class"] = "form-control";
			$this->TotalI2FO->EditCustomAttributes = "";
			$this->TotalI2FO->EditValue = ew_HtmlEncode($this->TotalI2FO->CurrentValue);
			$this->TotalI2FO->PlaceHolder = ew_RemoveHtml($this->TotalI2FO->FldCaption());

			// TotalFunction
			$this->TotalFunction->EditAttrs["class"] = "form-control";
			$this->TotalFunction->EditCustomAttributes = "";
			$this->TotalFunction->EditValue = ew_HtmlEncode($this->TotalFunction->CurrentValue);
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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->File);

			// DaysOfWorks
			$this->DaysOfWorks->EditAttrs["class"] = "form-control";
			$this->DaysOfWorks->EditCustomAttributes = "";
			$this->DaysOfWorks->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DaysOfWorks->CurrentValue, 7));
			$this->DaysOfWorks->PlaceHolder = ew_RemoveHtml($this->DaysOfWorks->FldCaption());

			// TotalDesignCost
			$this->TotalDesignCost->EditAttrs["class"] = "form-control";
			$this->TotalDesignCost->EditCustomAttributes = "";
			$this->TotalDesignCost->EditValue = ew_HtmlEncode($this->TotalDesignCost->CurrentValue);
			$this->TotalDesignCost->PlaceHolder = ew_RemoveHtml($this->TotalDesignCost->FldCaption());
			if (strval($this->TotalDesignCost->EditValue) <> "" && is_numeric($this->TotalDesignCost->EditValue)) $this->TotalDesignCost->EditValue = ew_FormatNumber($this->TotalDesignCost->EditValue, -2, -2, -2, -2);

			// StatusOrderID
			$this->StatusOrderID->EditCustomAttributes = "";
			if (trim(strval($this->StatusOrderID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`StatusOrderID`" . ew_SearchString("=", $this->StatusOrderID->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `StatusOrderID`, `StatusOrder` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `status_order`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->StatusOrderID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->StatusOrderID->ViewValue = $this->StatusOrderID->DisplayValue($arwrk);
			} else {
				$this->StatusOrderID->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->StatusOrderID->EditValue = $arwrk;

			// Add refer script
			// UserID

			$this->_UserID->LinkCustomAttributes = "";
			$this->_UserID->HrefValue = "";

			// PCBName
			$this->PCBName->LinkCustomAttributes = "";
			$this->PCBName->HrefValue = "";

			// MakeFrom
			$this->MakeFrom->LinkCustomAttributes = "";
			$this->MakeFrom->HrefValue = "";

			// AmountOfComponents
			$this->AmountOfComponents->LinkCustomAttributes = "";
			$this->AmountOfComponents->HrefValue = "";

			// Program
			$this->Program->LinkCustomAttributes = "";
			$this->Program->HrefValue = "";

			// TotalI/O
			$this->TotalI2FO->LinkCustomAttributes = "";
			$this->TotalI2FO->HrefValue = "";

			// TotalFunction
			$this->TotalFunction->LinkCustomAttributes = "";
			$this->TotalFunction->HrefValue = "";

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

			// DaysOfWorks
			$this->DaysOfWorks->LinkCustomAttributes = "";
			$this->DaysOfWorks->HrefValue = "";

			// TotalDesignCost
			$this->TotalDesignCost->LinkCustomAttributes = "";
			$this->TotalDesignCost->HrefValue = "";

			// StatusOrderID
			$this->StatusOrderID->LinkCustomAttributes = "";
			$this->StatusOrderID->HrefValue = "";
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
		if (!$this->_UserID->FldIsDetailKey && !is_null($this->_UserID->FormValue) && $this->_UserID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_UserID->FldCaption(), $this->_UserID->ReqErrMsg));
		}
		if (!$this->PCBName->FldIsDetailKey && !is_null($this->PCBName->FormValue) && $this->PCBName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->PCBName->FldCaption(), $this->PCBName->ReqErrMsg));
		}
		if ($this->MakeFrom->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->MakeFrom->FldCaption(), $this->MakeFrom->ReqErrMsg));
		}
		if (!$this->AmountOfComponents->FldIsDetailKey && !is_null($this->AmountOfComponents->FormValue) && $this->AmountOfComponents->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->AmountOfComponents->FldCaption(), $this->AmountOfComponents->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->AmountOfComponents->FormValue)) {
			ew_AddMessage($gsFormError, $this->AmountOfComponents->FldErrMsg());
		}
		if ($this->Program->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Program->FldCaption(), $this->Program->ReqErrMsg));
		}
		if (!$this->TotalI2FO->FldIsDetailKey && !is_null($this->TotalI2FO->FormValue) && $this->TotalI2FO->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->TotalI2FO->FldCaption(), $this->TotalI2FO->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->TotalI2FO->FormValue)) {
			ew_AddMessage($gsFormError, $this->TotalI2FO->FldErrMsg());
		}
		if (!$this->TotalFunction->FldIsDetailKey && !is_null($this->TotalFunction->FormValue) && $this->TotalFunction->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->TotalFunction->FldCaption(), $this->TotalFunction->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->TotalFunction->FormValue)) {
			ew_AddMessage($gsFormError, $this->TotalFunction->FldErrMsg());
		}
		if ($this->File->Upload->FileName == "" && !$this->File->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->File->FldCaption(), $this->File->ReqErrMsg));
		}
		if (!$this->DaysOfWorks->FldIsDetailKey && !is_null($this->DaysOfWorks->FormValue) && $this->DaysOfWorks->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DaysOfWorks->FldCaption(), $this->DaysOfWorks->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->DaysOfWorks->FormValue)) {
			ew_AddMessage($gsFormError, $this->DaysOfWorks->FldErrMsg());
		}
		if (!$this->TotalDesignCost->FldIsDetailKey && !is_null($this->TotalDesignCost->FormValue) && $this->TotalDesignCost->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->TotalDesignCost->FldCaption(), $this->TotalDesignCost->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->TotalDesignCost->FormValue)) {
			ew_AddMessage($gsFormError, $this->TotalDesignCost->FldErrMsg());
		}
		if (!$this->StatusOrderID->FldIsDetailKey && !is_null($this->StatusOrderID->FormValue) && $this->StatusOrderID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->StatusOrderID->FldCaption(), $this->StatusOrderID->ReqErrMsg));
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
		global $Language, $Security;

		// Check if valid User ID
		$bValidUser = FALSE;
		if ($Security->CurrentUserID() <> "" && !ew_Empty($this->_UserID->CurrentValue) && !$Security->IsAdmin()) { // Non system admin
			$bValidUser = $Security->IsValidUserID($this->_UserID->CurrentValue);
			if (!$bValidUser) {
				$sUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedUserID"));
				$sUserIdMsg = str_replace("%u", $this->_UserID->CurrentValue, $sUserIdMsg);
				$this->setFailureMessage($sUserIdMsg);
				return FALSE;
			}
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// UserID
		$this->_UserID->SetDbValueDef($rsnew, $this->_UserID->CurrentValue, 0, FALSE);

		// PCBName
		// MakeFrom

		$this->MakeFrom->SetDbValueDef($rsnew, $this->MakeFrom->CurrentValue, "", strval($this->MakeFrom->CurrentValue) == "");

		// AmountOfComponents
		$this->AmountOfComponents->SetDbValueDef($rsnew, $this->AmountOfComponents->CurrentValue, 0, FALSE);

		// Program
		$this->Program->SetDbValueDef($rsnew, $this->Program->CurrentValue, "", strval($this->Program->CurrentValue) == "");

		// TotalI/O
		$this->TotalI2FO->SetDbValueDef($rsnew, $this->TotalI2FO->CurrentValue, 0, FALSE);

		// TotalFunction
		$this->TotalFunction->SetDbValueDef($rsnew, $this->TotalFunction->CurrentValue, 0, FALSE);

		// File
		if ($this->File->Visible && !$this->File->Upload->KeepFile) {
			if (is_null($this->File->Upload->Value)) {
				$rsnew['File'] = NULL;
			} else {
				$rsnew['File'] = $this->File->Upload->Value;
			}
			$this->PCBName->SetDbValueDef($rsnew, $this->File->Upload->FileName, "", FALSE);
		}

		// DaysOfWorks
		$this->DaysOfWorks->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DaysOfWorks->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// TotalDesignCost
		$this->TotalDesignCost->SetDbValueDef($rsnew, $this->TotalDesignCost->CurrentValue, 0, FALSE);

		// StatusOrderID
		$this->StatusOrderID->SetDbValueDef($rsnew, $this->StatusOrderID->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->OrderID->setDbValue($conn->Insert_ID());
				$rsnew['OrderID'] = $this->OrderID->DbValue;
				if ($this->File->Visible && !$this->File->Upload->KeepFile) {
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
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// File
		ew_CleanUploadTempPath($this->File, $this->File->Upload->Index);
		return $AddRow;
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
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($desain_add)) $desain_add = new cdesain_add();

// Page init
$desain_add->Page_Init();

// Page main
$desain_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$desain_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fdesainadd = new ew_Form("fdesainadd", "add");

// Validate form
fdesainadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
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
			elm = this.GetElements("x" + infix + "__UserID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $desain->_UserID->FldCaption(), $desain->_UserID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_PCBName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $desain->PCBName->FldCaption(), $desain->PCBName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_MakeFrom");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $desain->MakeFrom->FldCaption(), $desain->MakeFrom->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_AmountOfComponents");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $desain->AmountOfComponents->FldCaption(), $desain->AmountOfComponents->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_AmountOfComponents");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($desain->AmountOfComponents->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Program");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $desain->Program->FldCaption(), $desain->Program->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_TotalI2FO");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $desain->TotalI2FO->FldCaption(), $desain->TotalI2FO->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_TotalI2FO");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($desain->TotalI2FO->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_TotalFunction");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $desain->TotalFunction->FldCaption(), $desain->TotalFunction->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_TotalFunction");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($desain->TotalFunction->FldErrMsg()) ?>");
			felm = this.GetElements("x" + infix + "_File");
			elm = this.GetElements("fn_x" + infix + "_File");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $desain->File->FldCaption(), $desain->File->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DaysOfWorks");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $desain->DaysOfWorks->FldCaption(), $desain->DaysOfWorks->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DaysOfWorks");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($desain->DaysOfWorks->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_TotalDesignCost");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $desain->TotalDesignCost->FldCaption(), $desain->TotalDesignCost->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_TotalDesignCost");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($desain->TotalDesignCost->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_StatusOrderID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $desain->StatusOrderID->FldCaption(), $desain->StatusOrderID->ReqErrMsg)) ?>");

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
fdesainadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdesainadd.ValidateRequired = true;
<?php } else { ?>
fdesainadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdesainadd.Lists["x__UserID"] = {"LinkField":"x__UserID","Ajax":true,"AutoFill":false,"DisplayFields":["x_NamaLengkap","x_NomorHP","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdesainadd.Lists["x_MakeFrom"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdesainadd.Lists["x_MakeFrom"].Options = <?php echo json_encode($desain->MakeFrom->Options()) ?>;
fdesainadd.Lists["x_Program"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdesainadd.Lists["x_Program"].Options = <?php echo json_encode($desain->Program->Options()) ?>;
fdesainadd.Lists["x_StatusOrderID"] = {"LinkField":"x_StatusOrderID","Ajax":true,"AutoFill":false,"DisplayFields":["x_StatusOrder","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $desain_add->ShowPageHeader(); ?>
<?php
$desain_add->ShowMessage();
?>
<form name="fdesainadd" id="fdesainadd" class="<?php echo $desain_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($desain_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $desain_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="desain">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($desain->_UserID->Visible) { // UserID ?>
	<div id="r__UserID" class="form-group">
		<label id="elh_desain__UserID" for="x__UserID" class="col-sm-2 control-label ewLabel"><?php echo $desain->_UserID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $desain->_UserID->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$desain->UserIDAllow("add")) { // Non system admin ?>
<span id="el_desain__UserID">
<select data-table="desain" data-field="x__UserID" data-value-separator="<?php echo ew_HtmlEncode(is_array($desain->_UserID->DisplayValueSeparator) ? json_encode($desain->_UserID->DisplayValueSeparator) : $desain->_UserID->DisplayValueSeparator) ?>" id="x__UserID" name="x__UserID"<?php echo $desain->_UserID->EditAttributes() ?>>
<?php
if (is_array($desain->_UserID->EditValue)) {
	$arwrk = $desain->_UserID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($desain->_UserID->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $desain->_UserID->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($desain->_UserID->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($desain->_UserID->CurrentValue) ?>" selected><?php echo $desain->_UserID->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `UserID`, `NamaLengkap` AS `DispFld`, `NomorHP` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user`";
$sWhereWrk = "";
if (!$GLOBALS["desain"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["user"]->AddUserIDFilter($sWhereWrk);
$desain->_UserID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$desain->_UserID->LookupFilters += array("f0" => "`UserID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$desain->Lookup_Selecting($desain->_UserID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `UserID` ASC";
if ($sSqlWrk <> "") $desain->_UserID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x__UserID" id="s_x__UserID" value="<?php echo $desain->_UserID->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el_desain__UserID">
<select data-table="desain" data-field="x__UserID" data-value-separator="<?php echo ew_HtmlEncode(is_array($desain->_UserID->DisplayValueSeparator) ? json_encode($desain->_UserID->DisplayValueSeparator) : $desain->_UserID->DisplayValueSeparator) ?>" id="x__UserID" name="x__UserID"<?php echo $desain->_UserID->EditAttributes() ?>>
<?php
if (is_array($desain->_UserID->EditValue)) {
	$arwrk = $desain->_UserID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($desain->_UserID->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $desain->_UserID->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($desain->_UserID->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($desain->_UserID->CurrentValue) ?>" selected><?php echo $desain->_UserID->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `UserID`, `NamaLengkap` AS `DispFld`, `NomorHP` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user`";
$sWhereWrk = "";
if (!$GLOBALS["desain"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["user"]->AddUserIDFilter($sWhereWrk);
$desain->_UserID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$desain->_UserID->LookupFilters += array("f0" => "`UserID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$desain->Lookup_Selecting($desain->_UserID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `UserID` ASC";
if ($sSqlWrk <> "") $desain->_UserID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x__UserID" id="s_x__UserID" value="<?php echo $desain->_UserID->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $desain->_UserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($desain->PCBName->Visible) { // PCBName ?>
	<div id="r_PCBName" class="form-group">
		<label id="elh_desain_PCBName" for="x_PCBName" class="col-sm-2 control-label ewLabel"><?php echo $desain->PCBName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $desain->PCBName->CellAttributes() ?>>
<span id="el_desain_PCBName">
<input type="text" data-table="desain" data-field="x_PCBName" name="x_PCBName" id="x_PCBName" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($desain->PCBName->getPlaceHolder()) ?>" value="<?php echo $desain->PCBName->EditValue ?>"<?php echo $desain->PCBName->EditAttributes() ?>>
</span>
<?php echo $desain->PCBName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($desain->MakeFrom->Visible) { // MakeFrom ?>
	<div id="r_MakeFrom" class="form-group">
		<label id="elh_desain_MakeFrom" class="col-sm-2 control-label ewLabel"><?php echo $desain->MakeFrom->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $desain->MakeFrom->CellAttributes() ?>>
<span id="el_desain_MakeFrom">
<div id="tp_x_MakeFrom" class="ewTemplate"><input type="radio" data-table="desain" data-field="x_MakeFrom" data-value-separator="<?php echo ew_HtmlEncode(is_array($desain->MakeFrom->DisplayValueSeparator) ? json_encode($desain->MakeFrom->DisplayValueSeparator) : $desain->MakeFrom->DisplayValueSeparator) ?>" name="x_MakeFrom" id="x_MakeFrom" value="{value}"<?php echo $desain->MakeFrom->EditAttributes() ?>></div>
<div id="dsl_x_MakeFrom" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $desain->MakeFrom->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($desain->MakeFrom->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="desain" data-field="x_MakeFrom" name="x_MakeFrom" id="x_MakeFrom_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $desain->MakeFrom->EditAttributes() ?>><?php echo $desain->MakeFrom->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($desain->MakeFrom->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="desain" data-field="x_MakeFrom" name="x_MakeFrom" id="x_MakeFrom_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($desain->MakeFrom->CurrentValue) ?>" checked<?php echo $desain->MakeFrom->EditAttributes() ?>><?php echo $desain->MakeFrom->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $desain->MakeFrom->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($desain->AmountOfComponents->Visible) { // AmountOfComponents ?>
	<div id="r_AmountOfComponents" class="form-group">
		<label id="elh_desain_AmountOfComponents" for="x_AmountOfComponents" class="col-sm-2 control-label ewLabel"><?php echo $desain->AmountOfComponents->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $desain->AmountOfComponents->CellAttributes() ?>>
<span id="el_desain_AmountOfComponents">
<input type="text" data-table="desain" data-field="x_AmountOfComponents" name="x_AmountOfComponents" id="x_AmountOfComponents" size="30" placeholder="<?php echo ew_HtmlEncode($desain->AmountOfComponents->getPlaceHolder()) ?>" value="<?php echo $desain->AmountOfComponents->EditValue ?>"<?php echo $desain->AmountOfComponents->EditAttributes() ?>>
</span>
<?php echo $desain->AmountOfComponents->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($desain->Program->Visible) { // Program ?>
	<div id="r_Program" class="form-group">
		<label id="elh_desain_Program" class="col-sm-2 control-label ewLabel"><?php echo $desain->Program->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $desain->Program->CellAttributes() ?>>
<span id="el_desain_Program">
<div id="tp_x_Program" class="ewTemplate"><input type="radio" data-table="desain" data-field="x_Program" data-value-separator="<?php echo ew_HtmlEncode(is_array($desain->Program->DisplayValueSeparator) ? json_encode($desain->Program->DisplayValueSeparator) : $desain->Program->DisplayValueSeparator) ?>" name="x_Program" id="x_Program" value="{value}"<?php echo $desain->Program->EditAttributes() ?>></div>
<div id="dsl_x_Program" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php
$arwrk = $desain->Program->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($desain->Program->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<label class="radio-inline"><input type="radio" data-table="desain" data-field="x_Program" name="x_Program" id="x_Program_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $desain->Program->EditAttributes() ?>><?php echo $desain->Program->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php
	}
	if ($emptywrk && strval($desain->Program->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="desain" data-field="x_Program" name="x_Program" id="x_Program_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($desain->Program->CurrentValue) ?>" checked<?php echo $desain->Program->EditAttributes() ?>><?php echo $desain->Program->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $desain->Program->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($desain->TotalI2FO->Visible) { // TotalI/O ?>
	<div id="r_TotalI2FO" class="form-group">
		<label id="elh_desain_TotalI2FO" for="x_TotalI2FO" class="col-sm-2 control-label ewLabel"><?php echo $desain->TotalI2FO->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $desain->TotalI2FO->CellAttributes() ?>>
<span id="el_desain_TotalI2FO">
<input type="text" data-table="desain" data-field="x_TotalI2FO" name="x_TotalI2FO" id="x_TotalI2FO" size="30" placeholder="<?php echo ew_HtmlEncode($desain->TotalI2FO->getPlaceHolder()) ?>" value="<?php echo $desain->TotalI2FO->EditValue ?>"<?php echo $desain->TotalI2FO->EditAttributes() ?>>
</span>
<?php echo $desain->TotalI2FO->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($desain->TotalFunction->Visible) { // TotalFunction ?>
	<div id="r_TotalFunction" class="form-group">
		<label id="elh_desain_TotalFunction" for="x_TotalFunction" class="col-sm-2 control-label ewLabel"><?php echo $desain->TotalFunction->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $desain->TotalFunction->CellAttributes() ?>>
<span id="el_desain_TotalFunction">
<input type="text" data-table="desain" data-field="x_TotalFunction" name="x_TotalFunction" id="x_TotalFunction" size="30" placeholder="<?php echo ew_HtmlEncode($desain->TotalFunction->getPlaceHolder()) ?>" value="<?php echo $desain->TotalFunction->EditValue ?>"<?php echo $desain->TotalFunction->EditAttributes() ?>>
</span>
<?php echo $desain->TotalFunction->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($desain->File->Visible) { // File ?>
	<div id="r_File" class="form-group">
		<label id="elh_desain_File" class="col-sm-2 control-label ewLabel"><?php echo $desain->File->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $desain->File->CellAttributes() ?>>
<span id="el_desain_File">
<div id="fd_x_File">
<span title="<?php echo $desain->File->FldTitle() ? $desain->File->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($desain->File->ReadOnly || $desain->File->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="desain" data-field="x_File" name="x_File" id="x_File"<?php echo $desain->File->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_File" id= "fn_x_File" value="<?php echo $desain->File->Upload->FileName ?>">
<input type="hidden" name="fa_x_File" id= "fa_x_File" value="0">
<input type="hidden" name="fs_x_File" id= "fs_x_File" value="0">
<input type="hidden" name="fx_x_File" id= "fx_x_File" value="<?php echo $desain->File->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_File" id= "fm_x_File" value="<?php echo $desain->File->UploadMaxFileSize ?>">
</div>
<table id="ft_x_File" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $desain->File->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($desain->DaysOfWorks->Visible) { // DaysOfWorks ?>
	<div id="r_DaysOfWorks" class="form-group">
		<label id="elh_desain_DaysOfWorks" for="x_DaysOfWorks" class="col-sm-2 control-label ewLabel"><?php echo $desain->DaysOfWorks->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $desain->DaysOfWorks->CellAttributes() ?>>
<span id="el_desain_DaysOfWorks">
<input type="text" data-table="desain" data-field="x_DaysOfWorks" data-format="7" name="x_DaysOfWorks" id="x_DaysOfWorks" placeholder="<?php echo ew_HtmlEncode($desain->DaysOfWorks->getPlaceHolder()) ?>" value="<?php echo $desain->DaysOfWorks->EditValue ?>"<?php echo $desain->DaysOfWorks->EditAttributes() ?>>
<?php if (!$desain->DaysOfWorks->ReadOnly && !$desain->DaysOfWorks->Disabled && !isset($desain->DaysOfWorks->EditAttrs["readonly"]) && !isset($desain->DaysOfWorks->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fdesainadd", "x_DaysOfWorks", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $desain->DaysOfWorks->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($desain->TotalDesignCost->Visible) { // TotalDesignCost ?>
	<div id="r_TotalDesignCost" class="form-group">
		<label id="elh_desain_TotalDesignCost" for="x_TotalDesignCost" class="col-sm-2 control-label ewLabel"><?php echo $desain->TotalDesignCost->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $desain->TotalDesignCost->CellAttributes() ?>>
<span id="el_desain_TotalDesignCost">
<input type="text" data-table="desain" data-field="x_TotalDesignCost" name="x_TotalDesignCost" id="x_TotalDesignCost" size="30" placeholder="<?php echo ew_HtmlEncode($desain->TotalDesignCost->getPlaceHolder()) ?>" value="<?php echo $desain->TotalDesignCost->EditValue ?>"<?php echo $desain->TotalDesignCost->EditAttributes() ?>>
</span>
<?php echo $desain->TotalDesignCost->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($desain->StatusOrderID->Visible) { // StatusOrderID ?>
	<div id="r_StatusOrderID" class="form-group">
		<label id="elh_desain_StatusOrderID" for="x_StatusOrderID" class="col-sm-2 control-label ewLabel"><?php echo $desain->StatusOrderID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $desain->StatusOrderID->CellAttributes() ?>>
<span id="el_desain_StatusOrderID">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $desain->StatusOrderID->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x_StatusOrderID" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
$arwrk = $desain->StatusOrderID->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($desain->StatusOrderID->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "") {
			$emptywrk = FALSE;
?>
<input type="radio" data-table="desain" data-field="x_StatusOrderID" name="x_StatusOrderID" id="x_StatusOrderID_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $desain->StatusOrderID->EditAttributes() ?>><?php echo $desain->StatusOrderID->DisplayValue($arwrk[$rowcntwrk]) ?>
<?php
		}
	}
	if ($emptywrk && strval($desain->StatusOrderID->CurrentValue) <> "") {
?>
<input type="radio" data-table="desain" data-field="x_StatusOrderID" name="x_StatusOrderID" id="x_StatusOrderID_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($desain->StatusOrderID->CurrentValue) ?>" checked<?php echo $desain->StatusOrderID->EditAttributes() ?>><?php echo $desain->StatusOrderID->CurrentValue ?>
<?php
    }
}
?>
		</div>
	</div>
	<div id="tp_x_StatusOrderID" class="ewTemplate"><input type="radio" data-table="desain" data-field="x_StatusOrderID" data-value-separator="<?php echo ew_HtmlEncode(is_array($desain->StatusOrderID->DisplayValueSeparator) ? json_encode($desain->StatusOrderID->DisplayValueSeparator) : $desain->StatusOrderID->DisplayValueSeparator) ?>" name="x_StatusOrderID" id="x_StatusOrderID" value="{value}"<?php echo $desain->StatusOrderID->EditAttributes() ?>></div>
</div>
<?php
$sSqlWrk = "SELECT `StatusOrderID`, `StatusOrder` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status_order`";
$sWhereWrk = "";
$desain->StatusOrderID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$desain->StatusOrderID->LookupFilters += array("f0" => "`StatusOrderID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$desain->Lookup_Selecting($desain->StatusOrderID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $desain->StatusOrderID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_StatusOrderID" id="s_x_StatusOrderID" value="<?php echo $desain->StatusOrderID->LookupFilterQuery() ?>">
</span>
<?php echo $desain->StatusOrderID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $desain_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fdesainadd.Init();
</script>
<?php
$desain_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$(document).ready(function(){
	var totalDesignCost = 0;
	$("#x_TotalDesignCost").val(totalDesignCost);
	$("#x_MakeFrom, #x_AmountOfComponent").keyup(function(){
		if($("#x_MakeFrom").val()=='PCB Clone'){
			var amountOfComponent = parseFloat($("#x_AmountOfComponent");
			totalDesignCost = parseFloat(20000*amountOfComponent);
			$("#x_TotalDesignCost").val(totalDesignCost);
		}
		else {
			totalDesignCost = parseFloat(10000*amountOfComponent);
			$("#x_TotalDesignCost").val(totalDesignCost);
		}
	});
});
</script>
<?php include_once "footer.php" ?>
<?php
$desain_add->Page_Terminate();
?>
