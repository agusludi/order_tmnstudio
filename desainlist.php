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

$desain_list = NULL; // Initialize page object first

class cdesain_list extends cdesain {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{5641CEBD-AA62-4C55-9718-A8075144C930}";

	// Table name
	var $TableName = 'desain';

	// Page object name
	var $PageObjName = 'desain_list';

	// Grid form hidden field names
	var $FormName = 'fdesainlist';
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

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "desainadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "desaindelete.php";
		$this->MultiUpdateUrl = "desainupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fdesainlistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
				$this->Page_Terminate();
			}
		}

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
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
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
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

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
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

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore filter list
			$this->RestoreFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
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
			$this->DisplayRecs = 20; // Load default
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
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
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
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->OrderID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->OrderID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->OrderID->AdvancedSearch->ToJSON(), ","); // Field OrderID
		$sFilterList = ew_Concat($sFilterList, $this->_UserID->AdvancedSearch->ToJSON(), ","); // Field UserID
		$sFilterList = ew_Concat($sFilterList, $this->PCBName->AdvancedSearch->ToJSON(), ","); // Field PCBName
		$sFilterList = ew_Concat($sFilterList, $this->MakeFrom->AdvancedSearch->ToJSON(), ","); // Field MakeFrom
		$sFilterList = ew_Concat($sFilterList, $this->AmountOfComponents->AdvancedSearch->ToJSON(), ","); // Field AmountOfComponents
		$sFilterList = ew_Concat($sFilterList, $this->Program->AdvancedSearch->ToJSON(), ","); // Field Program
		$sFilterList = ew_Concat($sFilterList, $this->TotalI2FO->AdvancedSearch->ToJSON(), ","); // Field TotalI/O
		$sFilterList = ew_Concat($sFilterList, $this->TotalFunction->AdvancedSearch->ToJSON(), ","); // Field TotalFunction
		$sFilterList = ew_Concat($sFilterList, $this->DaysOfWorks->AdvancedSearch->ToJSON(), ","); // Field DaysOfWorks
		$sFilterList = ew_Concat($sFilterList, $this->TotalDesignCost->AdvancedSearch->ToJSON(), ","); // Field TotalDesignCost
		$sFilterList = ew_Concat($sFilterList, $this->StatusOrderID->AdvancedSearch->ToJSON(), ","); // Field StatusOrderID
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field OrderID
		$this->OrderID->AdvancedSearch->SearchValue = @$filter["x_OrderID"];
		$this->OrderID->AdvancedSearch->SearchOperator = @$filter["z_OrderID"];
		$this->OrderID->AdvancedSearch->SearchCondition = @$filter["v_OrderID"];
		$this->OrderID->AdvancedSearch->SearchValue2 = @$filter["y_OrderID"];
		$this->OrderID->AdvancedSearch->SearchOperator2 = @$filter["w_OrderID"];
		$this->OrderID->AdvancedSearch->Save();

		// Field UserID
		$this->_UserID->AdvancedSearch->SearchValue = @$filter["x__UserID"];
		$this->_UserID->AdvancedSearch->SearchOperator = @$filter["z__UserID"];
		$this->_UserID->AdvancedSearch->SearchCondition = @$filter["v__UserID"];
		$this->_UserID->AdvancedSearch->SearchValue2 = @$filter["y__UserID"];
		$this->_UserID->AdvancedSearch->SearchOperator2 = @$filter["w__UserID"];
		$this->_UserID->AdvancedSearch->Save();

		// Field PCBName
		$this->PCBName->AdvancedSearch->SearchValue = @$filter["x_PCBName"];
		$this->PCBName->AdvancedSearch->SearchOperator = @$filter["z_PCBName"];
		$this->PCBName->AdvancedSearch->SearchCondition = @$filter["v_PCBName"];
		$this->PCBName->AdvancedSearch->SearchValue2 = @$filter["y_PCBName"];
		$this->PCBName->AdvancedSearch->SearchOperator2 = @$filter["w_PCBName"];
		$this->PCBName->AdvancedSearch->Save();

		// Field MakeFrom
		$this->MakeFrom->AdvancedSearch->SearchValue = @$filter["x_MakeFrom"];
		$this->MakeFrom->AdvancedSearch->SearchOperator = @$filter["z_MakeFrom"];
		$this->MakeFrom->AdvancedSearch->SearchCondition = @$filter["v_MakeFrom"];
		$this->MakeFrom->AdvancedSearch->SearchValue2 = @$filter["y_MakeFrom"];
		$this->MakeFrom->AdvancedSearch->SearchOperator2 = @$filter["w_MakeFrom"];
		$this->MakeFrom->AdvancedSearch->Save();

		// Field AmountOfComponents
		$this->AmountOfComponents->AdvancedSearch->SearchValue = @$filter["x_AmountOfComponents"];
		$this->AmountOfComponents->AdvancedSearch->SearchOperator = @$filter["z_AmountOfComponents"];
		$this->AmountOfComponents->AdvancedSearch->SearchCondition = @$filter["v_AmountOfComponents"];
		$this->AmountOfComponents->AdvancedSearch->SearchValue2 = @$filter["y_AmountOfComponents"];
		$this->AmountOfComponents->AdvancedSearch->SearchOperator2 = @$filter["w_AmountOfComponents"];
		$this->AmountOfComponents->AdvancedSearch->Save();

		// Field Program
		$this->Program->AdvancedSearch->SearchValue = @$filter["x_Program"];
		$this->Program->AdvancedSearch->SearchOperator = @$filter["z_Program"];
		$this->Program->AdvancedSearch->SearchCondition = @$filter["v_Program"];
		$this->Program->AdvancedSearch->SearchValue2 = @$filter["y_Program"];
		$this->Program->AdvancedSearch->SearchOperator2 = @$filter["w_Program"];
		$this->Program->AdvancedSearch->Save();

		// Field TotalI/O
		$this->TotalI2FO->AdvancedSearch->SearchValue = @$filter["x_TotalI2FO"];
		$this->TotalI2FO->AdvancedSearch->SearchOperator = @$filter["z_TotalI2FO"];
		$this->TotalI2FO->AdvancedSearch->SearchCondition = @$filter["v_TotalI2FO"];
		$this->TotalI2FO->AdvancedSearch->SearchValue2 = @$filter["y_TotalI2FO"];
		$this->TotalI2FO->AdvancedSearch->SearchOperator2 = @$filter["w_TotalI2FO"];
		$this->TotalI2FO->AdvancedSearch->Save();

		// Field TotalFunction
		$this->TotalFunction->AdvancedSearch->SearchValue = @$filter["x_TotalFunction"];
		$this->TotalFunction->AdvancedSearch->SearchOperator = @$filter["z_TotalFunction"];
		$this->TotalFunction->AdvancedSearch->SearchCondition = @$filter["v_TotalFunction"];
		$this->TotalFunction->AdvancedSearch->SearchValue2 = @$filter["y_TotalFunction"];
		$this->TotalFunction->AdvancedSearch->SearchOperator2 = @$filter["w_TotalFunction"];
		$this->TotalFunction->AdvancedSearch->Save();

		// Field DaysOfWorks
		$this->DaysOfWorks->AdvancedSearch->SearchValue = @$filter["x_DaysOfWorks"];
		$this->DaysOfWorks->AdvancedSearch->SearchOperator = @$filter["z_DaysOfWorks"];
		$this->DaysOfWorks->AdvancedSearch->SearchCondition = @$filter["v_DaysOfWorks"];
		$this->DaysOfWorks->AdvancedSearch->SearchValue2 = @$filter["y_DaysOfWorks"];
		$this->DaysOfWorks->AdvancedSearch->SearchOperator2 = @$filter["w_DaysOfWorks"];
		$this->DaysOfWorks->AdvancedSearch->Save();

		// Field TotalDesignCost
		$this->TotalDesignCost->AdvancedSearch->SearchValue = @$filter["x_TotalDesignCost"];
		$this->TotalDesignCost->AdvancedSearch->SearchOperator = @$filter["z_TotalDesignCost"];
		$this->TotalDesignCost->AdvancedSearch->SearchCondition = @$filter["v_TotalDesignCost"];
		$this->TotalDesignCost->AdvancedSearch->SearchValue2 = @$filter["y_TotalDesignCost"];
		$this->TotalDesignCost->AdvancedSearch->SearchOperator2 = @$filter["w_TotalDesignCost"];
		$this->TotalDesignCost->AdvancedSearch->Save();

		// Field StatusOrderID
		$this->StatusOrderID->AdvancedSearch->SearchValue = @$filter["x_StatusOrderID"];
		$this->StatusOrderID->AdvancedSearch->SearchOperator = @$filter["z_StatusOrderID"];
		$this->StatusOrderID->AdvancedSearch->SearchCondition = @$filter["v_StatusOrderID"];
		$this->StatusOrderID->AdvancedSearch->SearchValue2 = @$filter["y_StatusOrderID"];
		$this->StatusOrderID->AdvancedSearch->SearchOperator2 = @$filter["w_StatusOrderID"];
		$this->StatusOrderID->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->PCBName, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
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
			$this->UpdateSort($this->OrderID); // OrderID
			$this->UpdateSort($this->_UserID); // UserID
			$this->UpdateSort($this->PCBName); // PCBName
			$this->UpdateSort($this->MakeFrom); // MakeFrom
			$this->UpdateSort($this->Program); // Program
			$this->UpdateSort($this->DaysOfWorks); // DaysOfWorks
			$this->UpdateSort($this->TotalDesignCost); // TotalDesignCost
			$this->UpdateSort($this->StatusOrderID); // StatusOrderID
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
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
				$this->OrderID->setSort("");
				$this->_UserID->setSort("");
				$this->PCBName->setSort("");
				$this->MakeFrom->setSort("");
				$this->Program->setSort("");
				$this->DaysOfWorks->setSort("");
				$this->TotalDesignCost->setSort("");
				$this->StatusOrderID->setSort("");
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
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = TRUE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView() && $this->ShowOptionLink('view'))
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit() && $this->ShowOptionLink('edit')) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd() && $this->ShowOptionLink('add')) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->OrderID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fdesainlist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fdesainlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fdesainlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fdesainlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fdesainlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;

		// Hide detail items for dropdown if necessary
		$this->ListOptions->HideDetailItemsForDropDown();
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = FALSE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_desain\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_desain',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fdesainlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($desain_list)) $desain_list = new cdesain_list();

// Page init
$desain_list->Page_Init();

// Page main
$desain_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$desain_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($desain->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fdesainlist = new ew_Form("fdesainlist", "list");
fdesainlist.FormKeyCountName = '<?php echo $desain_list->FormKeyCountName ?>';

// Form_CustomValidate event
fdesainlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdesainlist.ValidateRequired = true;
<?php } else { ?>
fdesainlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdesainlist.Lists["x__UserID"] = {"LinkField":"x__UserID","Ajax":true,"AutoFill":false,"DisplayFields":["x_NamaLengkap","x_NomorHP","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdesainlist.Lists["x_MakeFrom"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdesainlist.Lists["x_MakeFrom"].Options = <?php echo json_encode($desain->MakeFrom->Options()) ?>;
fdesainlist.Lists["x_Program"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdesainlist.Lists["x_Program"].Options = <?php echo json_encode($desain->Program->Options()) ?>;
fdesainlist.Lists["x_StatusOrderID"] = {"LinkField":"x_StatusOrderID","Ajax":true,"AutoFill":false,"DisplayFields":["x_StatusOrder","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fdesainlistsrch = new ew_Form("fdesainlistsrch");
</script>
<style type="text/css">
.ewTablePreviewRow { /* main table preview row color */
	background-color: #FFFFFF; /* preview row color */
}
.ewTablePreviewRow .ewGrid {
	display: table;
}
.ewTablePreviewRow .ewGrid .ewTable {
	width: auto;
}
</style>
<div id="ewPreview" class="hide"><ul class="nav nav-tabs"></ul><div class="tab-content"><div class="tab-pane fade"></div></div></div>
<script type="text/javascript" src="phpjs/ewpreview.min.js"></script>
<script type="text/javascript">
var EW_PREVIEW_PLACEMENT = EW_CSS_FLIP ? "right" : "left";
var EW_PREVIEW_SINGLE_ROW = false;
var EW_PREVIEW_OVERLAY = false;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($desain->Export == "") { ?>
<div class="ewToolbar">
<?php if ($desain->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($desain_list->TotalRecs > 0 && $desain_list->ExportOptions->Visible()) { ?>
<?php $desain_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($desain_list->SearchOptions->Visible()) { ?>
<?php $desain_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($desain_list->FilterOptions->Visible()) { ?>
<?php $desain_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($desain->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $desain_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($desain_list->TotalRecs <= 0)
			$desain_list->TotalRecs = $desain->SelectRecordCount();
	} else {
		if (!$desain_list->Recordset && ($desain_list->Recordset = $desain_list->LoadRecordset()))
			$desain_list->TotalRecs = $desain_list->Recordset->RecordCount();
	}
	$desain_list->StartRec = 1;
	if ($desain_list->DisplayRecs <= 0 || ($desain->Export <> "" && $desain->ExportAll)) // Display all records
		$desain_list->DisplayRecs = $desain_list->TotalRecs;
	if (!($desain->Export <> "" && $desain->ExportAll))
		$desain_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$desain_list->Recordset = $desain_list->LoadRecordset($desain_list->StartRec-1, $desain_list->DisplayRecs);

	// Set no record found message
	if ($desain->CurrentAction == "" && $desain_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$desain_list->setWarningMessage(ew_DeniedMsg());
		if ($desain_list->SearchWhere == "0=101")
			$desain_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$desain_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$desain_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($desain->Export == "" && $desain->CurrentAction == "") { ?>
<form name="fdesainlistsrch" id="fdesainlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($desain_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fdesainlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="desain">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($desain_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($desain_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $desain_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($desain_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($desain_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($desain_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($desain_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $desain_list->ShowPageHeader(); ?>
<?php
$desain_list->ShowMessage();
?>
<?php if ($desain_list->TotalRecs > 0 || $desain->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fdesainlist" id="fdesainlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($desain_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $desain_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="desain">
<div id="gmp_desain" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($desain_list->TotalRecs > 0) { ?>
<table id="tbl_desainlist" class="table ewTable">
<?php echo $desain->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$desain_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$desain_list->RenderListOptions();

// Render list options (header, left)
$desain_list->ListOptions->Render("header", "left");
?>
<?php if ($desain->OrderID->Visible) { // OrderID ?>
	<?php if ($desain->SortUrl($desain->OrderID) == "") { ?>
		<th data-name="OrderID"><div id="elh_desain_OrderID" class="desain_OrderID"><div class="ewTableHeaderCaption"><?php echo $desain->OrderID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="OrderID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $desain->SortUrl($desain->OrderID) ?>',1);"><div id="elh_desain_OrderID" class="desain_OrderID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $desain->OrderID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($desain->OrderID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($desain->OrderID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($desain->_UserID->Visible) { // UserID ?>
	<?php if ($desain->SortUrl($desain->_UserID) == "") { ?>
		<th data-name="_UserID"><div id="elh_desain__UserID" class="desain__UserID"><div class="ewTableHeaderCaption"><?php echo $desain->_UserID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_UserID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $desain->SortUrl($desain->_UserID) ?>',1);"><div id="elh_desain__UserID" class="desain__UserID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $desain->_UserID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($desain->_UserID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($desain->_UserID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($desain->PCBName->Visible) { // PCBName ?>
	<?php if ($desain->SortUrl($desain->PCBName) == "") { ?>
		<th data-name="PCBName"><div id="elh_desain_PCBName" class="desain_PCBName"><div class="ewTableHeaderCaption"><?php echo $desain->PCBName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="PCBName"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $desain->SortUrl($desain->PCBName) ?>',1);"><div id="elh_desain_PCBName" class="desain_PCBName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $desain->PCBName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($desain->PCBName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($desain->PCBName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($desain->MakeFrom->Visible) { // MakeFrom ?>
	<?php if ($desain->SortUrl($desain->MakeFrom) == "") { ?>
		<th data-name="MakeFrom"><div id="elh_desain_MakeFrom" class="desain_MakeFrom"><div class="ewTableHeaderCaption"><?php echo $desain->MakeFrom->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="MakeFrom"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $desain->SortUrl($desain->MakeFrom) ?>',1);"><div id="elh_desain_MakeFrom" class="desain_MakeFrom">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $desain->MakeFrom->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($desain->MakeFrom->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($desain->MakeFrom->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($desain->Program->Visible) { // Program ?>
	<?php if ($desain->SortUrl($desain->Program) == "") { ?>
		<th data-name="Program"><div id="elh_desain_Program" class="desain_Program"><div class="ewTableHeaderCaption"><?php echo $desain->Program->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Program"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $desain->SortUrl($desain->Program) ?>',1);"><div id="elh_desain_Program" class="desain_Program">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $desain->Program->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($desain->Program->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($desain->Program->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($desain->DaysOfWorks->Visible) { // DaysOfWorks ?>
	<?php if ($desain->SortUrl($desain->DaysOfWorks) == "") { ?>
		<th data-name="DaysOfWorks"><div id="elh_desain_DaysOfWorks" class="desain_DaysOfWorks"><div class="ewTableHeaderCaption"><?php echo $desain->DaysOfWorks->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DaysOfWorks"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $desain->SortUrl($desain->DaysOfWorks) ?>',1);"><div id="elh_desain_DaysOfWorks" class="desain_DaysOfWorks">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $desain->DaysOfWorks->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($desain->DaysOfWorks->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($desain->DaysOfWorks->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($desain->TotalDesignCost->Visible) { // TotalDesignCost ?>
	<?php if ($desain->SortUrl($desain->TotalDesignCost) == "") { ?>
		<th data-name="TotalDesignCost"><div id="elh_desain_TotalDesignCost" class="desain_TotalDesignCost"><div class="ewTableHeaderCaption"><?php echo $desain->TotalDesignCost->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="TotalDesignCost"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $desain->SortUrl($desain->TotalDesignCost) ?>',1);"><div id="elh_desain_TotalDesignCost" class="desain_TotalDesignCost">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $desain->TotalDesignCost->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($desain->TotalDesignCost->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($desain->TotalDesignCost->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($desain->StatusOrderID->Visible) { // StatusOrderID ?>
	<?php if ($desain->SortUrl($desain->StatusOrderID) == "") { ?>
		<th data-name="StatusOrderID"><div id="elh_desain_StatusOrderID" class="desain_StatusOrderID"><div class="ewTableHeaderCaption"><?php echo $desain->StatusOrderID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="StatusOrderID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $desain->SortUrl($desain->StatusOrderID) ?>',1);"><div id="elh_desain_StatusOrderID" class="desain_StatusOrderID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $desain->StatusOrderID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($desain->StatusOrderID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($desain->StatusOrderID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$desain_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($desain->ExportAll && $desain->Export <> "") {
	$desain_list->StopRec = $desain_list->TotalRecs;
} else {

	// Set the last record to display
	if ($desain_list->TotalRecs > $desain_list->StartRec + $desain_list->DisplayRecs - 1)
		$desain_list->StopRec = $desain_list->StartRec + $desain_list->DisplayRecs - 1;
	else
		$desain_list->StopRec = $desain_list->TotalRecs;
}
$desain_list->RecCnt = $desain_list->StartRec - 1;
if ($desain_list->Recordset && !$desain_list->Recordset->EOF) {
	$desain_list->Recordset->MoveFirst();
	$bSelectLimit = $desain_list->UseSelectLimit;
	if (!$bSelectLimit && $desain_list->StartRec > 1)
		$desain_list->Recordset->Move($desain_list->StartRec - 1);
} elseif (!$desain->AllowAddDeleteRow && $desain_list->StopRec == 0) {
	$desain_list->StopRec = $desain->GridAddRowCount;
}

// Initialize aggregate
$desain->RowType = EW_ROWTYPE_AGGREGATEINIT;
$desain->ResetAttrs();
$desain_list->RenderRow();
while ($desain_list->RecCnt < $desain_list->StopRec) {
	$desain_list->RecCnt++;
	if (intval($desain_list->RecCnt) >= intval($desain_list->StartRec)) {
		$desain_list->RowCnt++;

		// Set up key count
		$desain_list->KeyCount = $desain_list->RowIndex;

		// Init row class and style
		$desain->ResetAttrs();
		$desain->CssClass = "";
		if ($desain->CurrentAction == "gridadd") {
		} else {
			$desain_list->LoadRowValues($desain_list->Recordset); // Load row values
		}
		$desain->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$desain->RowAttrs = array_merge($desain->RowAttrs, array('data-rowindex'=>$desain_list->RowCnt, 'id'=>'r' . $desain_list->RowCnt . '_desain', 'data-rowtype'=>$desain->RowType));

		// Render row
		$desain_list->RenderRow();

		// Render list options
		$desain_list->RenderListOptions();
?>
	<tr<?php echo $desain->RowAttributes() ?>>
<?php

// Render list options (body, left)
$desain_list->ListOptions->Render("body", "left", $desain_list->RowCnt);
?>
	<?php if ($desain->OrderID->Visible) { // OrderID ?>
		<td data-name="OrderID"<?php echo $desain->OrderID->CellAttributes() ?>>
<span id="el<?php echo $desain_list->RowCnt ?>_desain_OrderID" class="desain_OrderID">
<span<?php echo $desain->OrderID->ViewAttributes() ?>>
<?php echo $desain->OrderID->ListViewValue() ?></span>
</span>
<a id="<?php echo $desain_list->PageObjName . "_row_" . $desain_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($desain->_UserID->Visible) { // UserID ?>
		<td data-name="_UserID"<?php echo $desain->_UserID->CellAttributes() ?>>
<span id="el<?php echo $desain_list->RowCnt ?>_desain__UserID" class="desain__UserID">
<span<?php echo $desain->_UserID->ViewAttributes() ?>>
<?php echo $desain->_UserID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($desain->PCBName->Visible) { // PCBName ?>
		<td data-name="PCBName"<?php echo $desain->PCBName->CellAttributes() ?>>
<span id="el<?php echo $desain_list->RowCnt ?>_desain_PCBName" class="desain_PCBName">
<span<?php echo $desain->PCBName->ViewAttributes() ?>>
<?php echo $desain->PCBName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($desain->MakeFrom->Visible) { // MakeFrom ?>
		<td data-name="MakeFrom"<?php echo $desain->MakeFrom->CellAttributes() ?>>
<span id="el<?php echo $desain_list->RowCnt ?>_desain_MakeFrom" class="desain_MakeFrom">
<span<?php echo $desain->MakeFrom->ViewAttributes() ?>>
<?php echo $desain->MakeFrom->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($desain->Program->Visible) { // Program ?>
		<td data-name="Program"<?php echo $desain->Program->CellAttributes() ?>>
<span id="el<?php echo $desain_list->RowCnt ?>_desain_Program" class="desain_Program">
<span<?php echo $desain->Program->ViewAttributes() ?>>
<?php echo $desain->Program->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($desain->DaysOfWorks->Visible) { // DaysOfWorks ?>
		<td data-name="DaysOfWorks"<?php echo $desain->DaysOfWorks->CellAttributes() ?>>
<span id="el<?php echo $desain_list->RowCnt ?>_desain_DaysOfWorks" class="desain_DaysOfWorks">
<span<?php echo $desain->DaysOfWorks->ViewAttributes() ?>>
<?php echo $desain->DaysOfWorks->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($desain->TotalDesignCost->Visible) { // TotalDesignCost ?>
		<td data-name="TotalDesignCost"<?php echo $desain->TotalDesignCost->CellAttributes() ?>>
<span id="el<?php echo $desain_list->RowCnt ?>_desain_TotalDesignCost" class="desain_TotalDesignCost">
<span<?php echo $desain->TotalDesignCost->ViewAttributes() ?>>
<?php echo $desain->TotalDesignCost->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($desain->StatusOrderID->Visible) { // StatusOrderID ?>
		<td data-name="StatusOrderID"<?php echo $desain->StatusOrderID->CellAttributes() ?>>
<span id="el<?php echo $desain_list->RowCnt ?>_desain_StatusOrderID" class="desain_StatusOrderID">
<span<?php echo $desain->StatusOrderID->ViewAttributes() ?>>
<?php echo $desain->StatusOrderID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$desain_list->ListOptions->Render("body", "right", $desain_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($desain->CurrentAction <> "gridadd")
		$desain_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($desain->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($desain_list->Recordset)
	$desain_list->Recordset->Close();
?>
<?php if ($desain->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($desain->CurrentAction <> "gridadd" && $desain->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($desain_list->Pager)) $desain_list->Pager = new cPrevNextPager($desain_list->StartRec, $desain_list->DisplayRecs, $desain_list->TotalRecs) ?>
<?php if ($desain_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($desain_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $desain_list->PageUrl() ?>start=<?php echo $desain_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($desain_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $desain_list->PageUrl() ?>start=<?php echo $desain_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $desain_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($desain_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $desain_list->PageUrl() ?>start=<?php echo $desain_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($desain_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $desain_list->PageUrl() ?>start=<?php echo $desain_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $desain_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $desain_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $desain_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $desain_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($desain_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="desain">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($desain_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($desain_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($desain_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="ALL"<?php if ($desain->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($desain_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($desain_list->TotalRecs == 0 && $desain->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($desain_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($desain->Export == "") { ?>
<script type="text/javascript">
fdesainlistsrch.Init();
fdesainlistsrch.FilterList = <?php echo $desain_list->GetFilterList() ?>;
fdesainlist.Init();
</script>
<?php } ?>
<?php
$desain_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($desain->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$desain_list->Page_Terminate();
?>
