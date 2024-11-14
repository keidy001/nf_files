<?php
/* Copyright (C) 2000-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Jean-Louis Bergamo   <jlb@j1b.org>
 * Copyright (C) 2004-2009 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009 Regis Houssin        <regis.houssin@inodbox.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * or see https://www.gnu.org/
 */

if (!defined('NOTOKENRENEWAL'))  define('NOTOKENRENEWAL', 1);
if (!defined('NOCSRFCHECK'))     define('NOCSRFCHECK', 1);

// Dolibarr environment
$res = @include("../../main.inc.php"); // From htdocs directory
if (! $res) {
    $res = @include("../../../main.inc.php"); // From "custom" directory
}

// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once '../lib/paiedolibarr.lib.php';

dol_include_once('/paiedolibarr/class/paiedolibarr.class.php');
$paiedolibarr   = new paiedolibarr($db);

// Translations
$langs->load("paiedolibarr@paiedolibarr");
$langs->load("ecm");
$langs->load("banks");

// Access control
// if (! $user->admin) {
//     accessforbidden();
// }

// Parameters
$action = GETPOST('action', 'alpha');

if(!empty($action)){
    if (!$user->rights->paiedolibarr->creer) accessforbidden();
}

$nbdayworkglobal = GETPOST('nbdayworkglobal','alpha') ? GETPOST('nbdayworkglobal','alpha') : 'maroc';
$paiedolibarrmodel = GETPOST('paiedolibarrmodel','alpha') ? GETPOST('paiedolibarrmodel','alpha') : 'maroc';
$paiedolibarrnbmonth = GETPOST('paiedolibarrnbmonth','int') ? GETPOST('paiedolibarrnbmonth','int') : '9';
$presence = GETPOST('presence', 'int');
$groupusers = GETPOST('groupusers', 'int') ? GETPOST('groupusers', 'int') : $paiedolibarr->groupusers;
$nombreheurestravail = GETPOST('nombreheurestravail') ? GETPOST('nombreheurestravail') : $paiedolibarr->nombreheurestravail;
$heuresdeclarees = GETPOST('heuresdeclarees') ? GETPOST('heuresdeclarees') : $paiedolibarr->heuresdeclarees;
$accountid  = GETPOST('accountid');
$yearsofposition  = GETPOST('yearsofposition', 'int');
$numberhourstoexceed  = (float) GETPOST('numberhourstoexceed');
$attendancebonus  = (float) GETPOST('attendancebonus');


/*
 * Actions
 */

if(!empty($action)){

    if($action == "update"){

        $error = 0;

        if(!dolibarr_set_const($db, "PAIE_DOLIBARR_PRESENCE", $presence,'chaine',0,'',$conf->entity))
            $error++; 

        if(!dolibarr_set_const($db, "PAIE_DOLIBARR_GROUPUSERS", $groupusers,'chaine',0,'',$conf->entity))
            $error++;

        if(!dolibarr_set_const($db, "PAIE_DOLIBARR_NOMBRE_HEURES_TRAVAIL", $nombreheurestravail,'chaine',0,'',$conf->entity))
            $error++;

        if(!dolibarr_set_const($db, "PAIE_DOLIBARR_HEURES_DECLAREES", $heuresdeclarees,'chaine',0,'',$conf->entity))
            $error++;
    }

    if (! $error) {
        setEventMessage($langs->trans("SetupSavedpaie"), '', 'mesgs');
    } else {
        setEventMessage($langs->trans("Error"), '', 'errors');
    }

    header('Location: ./presences_employes.php');
    exit;
}



/*
 * View
 */
$page_name = "paiedolibarrSetup";
llxHeader('', $langs->trans($page_name));

// Subheader
$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">'. $langs->trans("BackToModuleList") . '</a>';
// print_fiche_titre($langs->trans($page_name), $linkback);

$head = paiedolibarrAdminPrepareHead();

print dol_get_fiche_head($head, 'presences', $langs->trans("paiedolibarr"), -1, 'payment');



// Setup page goes here
$form=new Form($db);

print '<div class="paieconfigurationmod">';
print '<form method="post" action="'.$_SERVER["PHP_SELF"].'" enctype="multipart/form-data" class="">';
    print '<input type="hidden" name="token" value="'.newToken().'">';
    print '<input type="hidden" name="action" value="update" />';
    print '<table class="noborder dstable_" width="100%">';

        $presence = $paiedolibarr->presence;
        print '<tr class="oddeven">';
            print '<td width="43%">'.$langs->trans('SalaryDependsOnTheNumberOfHoursWorked').'</td>';
            print '<td>';
               print '<input type="radio" '.(($presence>0) ? 'checked' : '').' name="presence" id="yes" value="1"><label for="yes">'.$langs->trans('Yes').'</label>&nbsp;&nbsp;';
               print '&nbsp;&nbsp;<input type="radio" '.(($presence>0) ? '' : 'checked').' name="presence" id="no" value="0"><label for="no">'.$langs->trans('No').'</label>';
            print '</td>';
            if(!empty($presence)){
                print '<td>'.$langs->trans('UserGroups').'</td>';
                print '<td>';
                    $groupusers = $paiedolibarr->groupusers;
                    print $form->select_dolgroups($groupusers, 'groupusers', $_show_empty = 1);
                print '</td>';
            }
        print '</tr>';

        if(!empty($presence)){
            $nombreheurestravail = $paiedolibarr->nombreheurestravail;
            print '<tr class="oddeven">';
                print '<td width="43%">'.$langs->trans('NumberOfWorkingHours').'</td>';
                print '<td colspan="3" >';
                    print '<input type="number" step="any" min="0" name="nombreheurestravail" value="'.$nombreheurestravail.'" />';
                print '</td>';
            print '</tr>'; 

            $heuresdeclarees = $paiedolibarr->heuresdeclarees;
            print '<tr class="oddeven">';
                print '<td width="43%">'.$langs->trans('TheEmployeeWillBePaidAccordingToTheHoursDeclared').'</td>';
                print '<td colspan="3">'.$langs->trans('IfTheTotalHoursWorkedAreLessThan').'&nbsp;&nbsp;';
                    print '<input type="number" step="any" min="0" name="heuresdeclarees" value="'.$heuresdeclarees.'" />';
                print '</td>';
            print '</tr>';
        }


    print '</table><br>';

    // Actions
    print '<table class="" width="100%">';
    print '<tr class="oddeven">';
        print '<td colspan="2" align="left">';
        print '<input type="submit" value="'.$langs->trans('Validate').'" name="bouton" class="button" />';
        print '</td>';
    print '</tr>';
    print '</table>';

print '</form>';
print '</div>';
dol_fiche_end(1);


llxFooter();
$db->close();
