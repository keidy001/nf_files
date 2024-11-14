<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**

/**
 *	\file		lib/paiedolibarr.lib.php
 *	\ingroup	paiedolibarr
 *	\brief		This file is an example module library
 *				Put some comments here
 */
function paiedolibarrAdminPrepareHead($nbscall=0)
{
    global $langs, $conf;
    
    $langs->load("paiedolibarr@paiedolibarr");
    
    $h = 0;
    $head = array();
    
    $head[$h][0] = dol_buildpath("/paiedolibarr/admin/paiedolibarr_setup.php", 1);
    $head[$h][1] = $langs->trans("General");
    $head[$h][2] = 'general';
    $h++;
    
    $head[$h][0] = dol_buildpath("/paiedolibarr/admin/paiedolibarr_extrafields.php", 1);
    $head[$h][1] = $langs->trans("ExtraFieldspaiedolibarr");
    $head[$h][2] = 'attributes';
    $h++;

    if(isset($conf->global->PAIEDOLIBARR_ENABLE_SALARYSCALL) && $conf->global->PAIEDOLIBARR_ENABLE_SALARYSCALL){
        $head[$h][0] = dol_buildpath("/paiedolibarr/admin/salaryscall.php", 1);
        $head[$h][1] = $langs->trans("salaryscall");
        if($nbscall>0){
            $head[$h][1] .= '<span class="badge marginleftonlyshort">'.$nbscall.'</span>';    
        }
        $head[$h][2] = 'salaryscall';
        $h++;
    }
    
    if (!empty($conf->presences->enabled)){
        $head[$h][0] = dol_buildpath("/paiedolibarr/admin/presences_employes.php", 1);
        $head[$h][1] = $langs->trans("EmployeeAttendance");
        $head[$h][2] = 'presences';
        $h++;
    }
    
    // complete_head_from_modules($conf, $langs, $object, $head, $h, 'paiedolibarr');
    
    return $head;
}

// function getElementPropertiespaiedolibarr(&$element_properties, $element_type)
// {
//     global $conf, $user, $db;

//     if($element_properties['classname'] == 'Fournisseur' && $element_type == 'commande_fournisseur') {

//         $element_properties['module'] = 'paiedolibarr';
//         $element_properties['element'] = 'paiedolibarr';
//         $element_properties['table_element'] = 'paiedolibarr_paies';
//         $element_properties['subelement'] = '';
//         $element_properties['classpath'] = 'paiedolibarr/class';
//         $element_properties['classfile'] = 'paiedolibarr_paies';
//         $element_properties['classname'] = 'paiedolibarr_paies';
//     }
// }

if (!function_exists("d")) {
    function d($array , $stop = true)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        if($stop) die;
    }
}