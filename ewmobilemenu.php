<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mmi_desain", $Language->MenuPhrase("1", "MenuText"), "desainlist.php", -1, "", IsLoggedIn() || AllowListMenu('{5641CEBD-AA62-4C55-9718-A8075144C930}desain'), FALSE);
$RootMenu->AddMenuItem(9, "mmci_Master", $Language->MenuPhrase("9", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(5, "mmi_status_order", $Language->MenuPhrase("5", "MenuText"), "status_orderlist.php", 9, "", IsLoggedIn() || AllowListMenu('{5641CEBD-AA62-4C55-9718-A8075144C930}status_order'), FALSE);
$RootMenu->AddMenuItem(11, "mmi_userlevels", $Language->MenuPhrase("11", "MenuText"), "userlevelslist.php", 9, "", IsLoggedIn() || AllowListMenu('{5641CEBD-AA62-4C55-9718-A8075144C930}userlevels'), FALSE);
$RootMenu->AddMenuItem(10, "mmi_userlevelpermissions", $Language->MenuPhrase("10", "MenuText"), "userlevelpermissionslist.php", 9, "", IsLoggedIn() || AllowListMenu('{5641CEBD-AA62-4C55-9718-A8075144C930}userlevelpermissions'), FALSE);
$RootMenu->AddMenuItem(4, "mmci_My_Account", $Language->MenuPhrase("4", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mmi_user", $Language->MenuPhrase("2", "MenuText"), "userlist.php", 4, "", IsLoggedIn() || AllowListMenu('{5641CEBD-AA62-4C55-9718-A8075144C930}user'), FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
