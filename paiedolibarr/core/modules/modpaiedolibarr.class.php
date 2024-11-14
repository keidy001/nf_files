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
 * 	\defgroup   paiedolibarr     Module paiedolibarr
 *  \brief      Example of a module descriptor.
 *				Such a file must be copied into htdocs/paiedolibarr/core/modules directory.
 *  \file       htdocs/paiedolibarr/core/modules/modpaiedolibarr.class.php
 *  \ingroup    paiedolibarr
 *  \brief      Description and activation file for module paiedolibarr
 */
include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';

/**
 *  Description and activation class for module paiedolibarr
 */
class modpaiedolibarr extends DolibarrModules
{
	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
        global $langs,$conf;

        $this->db = $db;

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->numero = 1909671820;
		// Key text used to identify module (for permissions, menus, etc...)
		$this->rights_class = 'paiedolibarr';
		
		$this->editor_name = 'NextGestion';
		$this->editor_url = 'https://www.nextgestion.com';

		// Family can be 'crm','financial','hr','projects','products','ecm','technic','other'
		// It is used to group modules in module setup page
		$this->family = "NextGestion";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		// Module description, used if translation string 'ModuleXXXDesc' not found (where XXX is value of numeric property 'numero' of module)
		$this->description = "Module1909671820Desc";
		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = '6.5';
		// Key used in llx_const table to save module status enabled/disabled (where MYMODULE is value of property name of module in uppercase)
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		// Where to store the module in setup page (0=common,1=interface,2=others,3=very specific)
		$this->special = 0;
		// Name of image file used for this module.
		// If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
		// If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
		$this->picto='paiedolibarr@paiedolibarr';
		
		// Defined all module parts (triggers, login, substitutions, menus, css, etc...)
		// for default path (eg: /paiedolibarr/core/xxxxx) (0=disable, 1=enable)
		// for specific path of parts (eg: /paiedolibarr/core/modules/barcode)
		// for specific css file (eg: /paiedolibarr/css/paiedolibarr.css.php)
		//$this->module_parts = array(
		//                        	'triggers' => 0,                                 	// Set this to 1 if module has its own trigger directory (core/triggers)
		//							'login' => 0,                                    	// Set this to 1 if module has its own login method directory (core/login)
		//							'substitutions' => 0,                            	// Set this to 1 if module has its own substitution function file (core/substitutions)
		//							'menus' => 0,                                    	// Set this to 1 if module has its own menus handler directory (core/menus)
		//							'theme' => 0,                                    	// Set this to 1 if module has its own theme directory (theme)
		//                        	'tpl' => 0,                                      	// Set this to 1 if module overwrite template dir (core/tpl)
		//							'barcode' => 0,                                  	// Set this to 1 if module has its own barcode directory (core/modules/barcode)
		//							'models' => 0,                                   	// Set this to 1 if module has its own models directory (core/modules/xxx)
		//							'css' => array('/paiedolibarr/css/paiedolibarr.css.php'),	// Set this to relative path of css file if module has its own css file
	 	//							'js' => array('/paiedolibarr/js/paiedolibarr.js'),          // Set this to relative path of js file if module must load a js on all pages
		//							'hooks' => array('hookcontext1','hookcontext2')  	// Set here all hooks context managed by module
		//							'dir' => array('output' => 'othermodulename'),      // To force the default directories names
		//							'workflow' => array('WORKFLOW_MODULE1_YOURACTIONTYPE_MODULE2'=>array('enabled'=>'! empty($conf->module1->enabled) && ! empty($conf->module2->enabled)', 'picto'=>'yourpicto@paiedolibarr')) // Set here all workflow context managed by module
		//                        );
		$this->module_parts = array(
		    // 'hooks' => array('paiedolibarrpage','paiedolibarr'),
		    'hooks' => array('salarieslist'),
			'triggers' 	=> 1,
			'css' 	=> array('/paiedolibarr/css/paiedolibarr.css'),
			'js' 	=> array('/paiedolibarr/js/paiedolibarr.js'),
		);

		// Data directories to create when module is enabled.
		// Example: this->dirs = array("/paiedolibarr/temp");
		$this->dirs = array();

		// Config pages. Put here list of php page, stored into paiedolibarr/admin directory, to use to setup module.
		$this->config_page_url = array();
		$this->config_page_url = array("paiedolibarr_setup.php@paiedolibarr");

		// Dependencies
		$this->hidden = false;			// A condition to hide module
		$this->depends = array();		// List of modules id that must be enabled if this module is enabled
		$this->requiredby = array();	// List of modules id to disable if this one is disabled
		$this->conflictwith = array();	// List of modules id this module is in conflict with
		$this->phpmin = array(5,0);					// Minimum version of PHP required by module
		$this->need_dolibarr_version = array(3,0);	// Minimum version of Dolibarr required by module
		$this->langfiles = array("paiedolibarr@paiedolibarr");

		// Constants
		// List of particular constants to add when module is enabled (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
		// Example: $this->const=array(0=>array('MYMODULE_MYNEWCONST1','chaine','myvalue','This is a constant to add',1),
		//                             1=>array('MYMODULE_MYNEWCONST2','chaine','myvalue','This is another constant to add',0, 'current', 1)
		// );
		$this->const = array();

		// Array to add new pages in new tabs
		// Example: $this->tabs = array('objecttype:+tabname1:Title1:paiedolibarr@paiedolibarr:$user->rights->paiedolibarr->read:/paiedolibarr/mynewtab1.php?id=__ID__',  	// To add a new tab identified by code tabname1
        //                              'objecttype:+tabname2:Title2:paiedolibarr@paiedolibarr:$user->rights->othermodule->read:/paiedolibarr/mynewtab2.php?id=__ID__',  	// To add another new tab identified by code tabname2
        //                              'objecttype:-tabname:NU:conditiontoremove');                                                     						// To remove an existing tab identified by code tabname
		// where objecttype can be
		// 'categories_x'	  to add a tab in category view (replace 'x' by type of category (0=product, 1=supplier, 2=customer, 3=member)
		// 'contact'          to add a tab in contact view
		// 'contract'         to add a tab in contract view
		// 'group'            to add a tab in group view
		// 'intervention'     to add a tab in intervention view
		// 'invoice'          to add a tab in customer invoice view
		// 'invoice_supplier' to add a tab in supplier invoice view
		// 'member'           to add a tab in fundation member view
		// 'opensurveypoll'	  to add a tab in opensurvey poll view
		// 'order'            to add a tab in customer order view
		// 'order_supplier'   to add a tab in supplier order view
		// 'payment'		  to add a tab in payment view
		// 'payment_supplier' to add a tab in supplier payment view
		// 'product'          to add a tab in product view
		// 'propal'           to add a tab in propal view
		// 'project'          to add a tab in project view
		// 'stock'            to add a tab in stock view
		// 'thirdparty'       to add a tab in third party view
		// 'user'             to add a tab in user view
        $this->tabs = array();
        // $namtab = 'paiedolibarr2';
        // $this->tabs = array(
        // 	'user:+tab_paiedolibarr:'.$namtab.':paiedolibarr@paiedolibarr:(!empty($user->admin) || $user->rights->user->user->lire):/paiedolibarr/employee/card.php?id=__ID__',
        // );

        // Dictionaries
	    if (! isset($conf->paiedolibarr->enabled))
        {
        	$conf->paiedolibarr=new stdClass();
        	$conf->paiedolibarr->enabled=0;
        }
		$this->dictionaries=array();
        /* Example:
        if (! isset($conf->paiedolibarr->enabled)) $conf->paiedolibarr->enabled=0;	// This is to avoid warnings
        $this->dictionaries=array(
            'langs'=>'paiedolibarr@paiedolibarr',
            'tabname'=>array(MAIN_DB_PREFIX."table1",MAIN_DB_PREFIX."table2",MAIN_DB_PREFIX."table3"),		// List of tables we want to see into dictonnary editor
            'tablib'=>array("Table1","Table2","Table3"),													// Label of tables
            'tabsql'=>array('SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table1 as f','SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table2 as f','SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table3 as f'),	// Request to select fields
            'tabsqlsort'=>array("label ASC","label ASC","label ASC"),																					// Sort order
            'tabfield'=>array("code,label","code,label","code,label"),																					// List of fields (result of select to show dictionary)
            'tabfieldvalue'=>array("code,label","code,label","code,label"),																				// List of fields (list of fields to edit a record)
            'tabfieldinsert'=>array("code,label","code,label","code,label"),																			// List of fields (list of fields for insert)
            'tabrowid'=>array("rowid","rowid","rowid"),																									// Name of columns with primary key (try to always name it 'rowid')
            'tabcond'=>array($conf->paiedolibarr->enabled,$conf->paiedolibarr->enabled,$conf->paiedolibarr->enabled)												// Condition to show each dictionary
        );
        */

        // Boxes
		// Add here list of php file(s) stored in core/boxes that contains class to show a box.
        $this->boxes = array();			// List of boxes
		// Example:
		//$this->boxes=array(array(0=>array('file'=>'myboxa.php','note'=>'','enabledbydefaulton'=>'Home'),1=>array('file'=>'myboxb.php','note'=>''),2=>array('file'=>'myboxc.php','note'=>'')););

		// Permissions
		$this->rights = array();		// Permission array used by this module
		$r=0;

		// Add here list of permission defined by an id, a label, a boolean and two constant strings.


		$this->rights[$r][0] = $this->numero+$r;	// Permission id (must not be already used)
		$this->rights[$r][1] = 'Show';	// Permission label
		$this->rights[$r][3] = 1; 					// Permission by default for new user (0/1)
		$this->rights[$r][4] = 'lire';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		$r++;

		$this->rights[$r][0] = $this->numero+$r;	// Permission id (must not be already used)
		$this->rights[$r][1] = 'Ajouter / Modifier';	// Permission label
		$this->rights[$r][3] = 1; 					// Permission by default for new user (0/1)
		$this->rights[$r][4] = 'creer';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		$r++;


		$this->rights[$r][0] = $this->numero+$r;	// Permission id (must not be already used)
		$this->rights[$r][1] = 'Delete';	// Permission label
		$this->rights[$r][3] = 1; 					// Permission by default for new user (0/1)
		$this->rights[$r][4] = 'supprimer';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		$r++;


		$this->rights[$r][0] = $this->numero+$r;	// Permission id (must not be already used)
		$this->rights[$r][1] = 'createsalary';	// Permission label
		$this->rights[$r][3] = 1; 					// Permission by default for new user (0/1)
		$this->rights[$r][4] = 'creer_salaire';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		$r++;
		

		// Main menu entries
		$this->menu = array();			// List of menus to add
		$r=0;
		// Add here entries to declare new menus
		//
		// Example to declare a new Top Menu entry and its Left menu entry:



		// $this->menu[$r]=array(	'fk_menu'=>0,		// Put 0 if this is a single top menu or keep fk_mainmenu to give an entry on left
		// 	'type'=>'top',			                // This is a Top menu entry
		// 	'titre'=>'paiedolibarr',
		// 	'mainmenu'=>'hrm',
		// 	'leftmenu'=>'paiedolibarr_left',			// This is the name of left menu for the next entries
		// 	'url'=>'paiedolibarr/index.php',
		// 	'langs'=>'paiedolibarr@paiedolibarr',	       
		// 	'position'=>410,
		// 	'enabled'=>'$conf->paiedolibarr->enabled',
		// 	'perms'=>'($user->rights->paiedolibarr->lire)',			                
		// 	'target'=>'',
		// 	'user'=>2);				               
		// $r++;
		
		$this->menu[$r]=array('fk_menu'=>'fk_mainmenu=hrm',
			'type'=>'left',
			'titre'=>'paielist',
			'leftmenu'=>'paielist',
			'url'=>'/paiedolibarr/list.php',
			'langs'=>'paiedolibarr@paiedolibarr',
			'position'=>201,
			'enabled'=>'1',
			'perms'=>'($user->rights->paiedolibarr->lire)',
			'target'=>'',
			'user'=>2);
		$r++;

			$this->menu[$r]=array('fk_menu'=>'fk_mainmenu=hrm,fk_leftmenu=paielist',
				'type'=>'left',
				'titre'=>'listofpaie',
				'leftmenu'=>'paielist2',
				'url'=>'/paiedolibarr/list.php',
				'langs'=>'paiedolibarr@paiedolibarr',
				'position'=>202,
				'enabled'=>'1',
				'perms'=>'($user->rights->paiedolibarr->lire)',
				'target'=>'',
				'user'=>2);
			$r++;

			$this->menu[$r]=array('fk_menu'=>'fk_mainmenu=hrm,fk_leftmenu=paielist2',
				'type'=>'left',
				'titre'=>'NewPaie',
				'url'=>'/paiedolibarr/card.php?action=create',
				'langs'=>'paiedolibarr@paiedolibarr',
				'position'=>203,
				'enabled'=>'1',
				'perms'=>'($user->rights->paiedolibarr->creer)',
				'target'=>'',
				'user'=>2);
			$r++;
		
			$this->menu[$r]=array('fk_menu'=>'fk_mainmenu=hrm,fk_leftmenu=paielist',
				'type'=>'left',
				'titre'=>'PayrollJournal',
				'leftmenu'=>'paielist4',
				'url'=>'/paiedolibarr/etat.php',
				'langs'=>'paiedolibarr@paiedolibarr',
				'position'=>206,
				'enabled'=>'1',
				'perms'=>'($user->rights->paiedolibarr->lire)',
				'target'=>'',
				'user'=>2);
			$r++;
		
			$this->menu[$r]=array('fk_menu'=>'fk_mainmenu=hrm,fk_leftmenu=paielist',
				'type'=>'left',
				'titre'=>'paierules',
				'leftmenu'=>'paielist3',
				'url'=>'/paiedolibarr/rules/index.php',
				'langs'=>'paiedolibarr@paiedolibarr',
				'position'=>207,
				'enabled'=>'1',
				'perms'=>'($user->rights->paiedolibarr->lire)',
				'target'=>'',
				'user'=>2);
			$r++;
		
			// $this->menu[$r]=array('fk_menu'=>'fk_mainmenu=hrm,fk_leftmenu=paielist3',
			// 	'type'=>'left',
			// 	'titre'=>'NewPaieRule2',
			// 	'url'=>'/paiedolibarr/rules/card.php?action=add',
			// 	'langs'=>'paiedolibarr@paiedolibarr',
			// 	'position'=>208,
			// 	'enabled'=>'1',
			// 	'perms'=>'($user->rights->paiedolibarr->creer)',
			// 	'target'=>'',
			// 	'user'=>2);
			// $r++;
		
			// $this->menu[$r]=array('fk_menu'=>'fk_mainmenu=paiedolibarr,fk_leftmenu=paielist3',
			// 	'type'=>'left',
			// 	'titre'=>'PaieRuleParentElem',
			// 	'url'=>'/paiedolibarr/rules/title/index.php',
			// 	'langs'=>'paiedolibarr@paiedolibarr',
			// 	'position'=>209,
			// 	'enabled'=>'1',
			// 	'perms'=>'($user->rights->paiedolibarr->creer)',
			// 	'target'=>'',
			// 	'user'=>2);
			// $r++;
		
			$this->menu[$r]=array('fk_menu'=>'fk_mainmenu=hrm,fk_leftmenu=paielist',
				'type'=>'left',
				'titre'=>'Setup',
				'leftmenu'=>'Configuration',
				'url'=>'paiedolibarr/admin/paiedolibarr_setup.php',
				'langs'=>'paiedolibarr@paiedolibarr',
				'position'=>211,
				'enabled'=>'1',
				'perms'=>'($user->rights->paiedolibarr->lire)',
				'target'=>'',
				'user'=>2);
			$r++;

		// Exports
		$r=1;

	}

	/**
	 *		Function called when module is enabled.
	 *		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *		It also creates data directories
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function init($options='')
	{
		global $conf, $langs;
		$langs->load('paiedolibarr@paiedolibarr');
		$sqlm = array();

		dol_include_once('/paiedolibarr/class/paiedolibarr.class.php');
		$paiedolibarr = new paiedolibarr($this->db);

		$paiedolibarr->initpaiedolibarrModule($this->version);


		// if(floatval(DOL_VERSION) == 19){
        //     $file = DOL_DOCUMENT_ROOT.'/core/lib/functions.lib.php';
        //     @chmod($file, octdec(775));
        //     $configfile = file_get_contents($file);
        //     // ####################################################### replacewith
        //     $replacewith = '// var_dump($element_properties);

        //     // BEGIN PAIEDOLIBARR MODULE
        //     $paiedolibarrexist = dol_include_once(\'/paiedolibarr/lib/paiedolibarr.lib.php\');
        //     if($paiedolibarrexist)
        //     getElementPropertiespaiedolibarr($element_properties, $element_type); // INTEGRATED BY PAIEDOLIBARR MODULE TO MANAGE HIERARCHICAL VIEW FOR MANAGERS
        //     // END PAIEDOLIBARR MODULE
        //     ';

        //     $configfile = str_replace('//var_dump($element_properties)', $replacewith, $configfile);
        //     file_put_contents($file, $configfile);
        //     @chmod($file, octdec(644));
        // }

		return $this->_init($sqlm, $options);
	}

	/**
	 *		Function called when module is disabled.
	 *      Remove from database constants, boxes and permissions from Dolibarr database.
	 *		Data directories are not deleted
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function remove($options='')
	{
		$sql = array();
		$sql = array(
			'DELETE FROM `'.MAIN_DB_PREFIX.'extrafields` WHERE `name` like "%paie%"',
		);
		return $this->_remove($sql, $options);
	}

}
