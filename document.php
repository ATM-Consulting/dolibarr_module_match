<?php
/* Copyright (C) 2002-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2017 Laurent Destailleur  <eldy@matchs.sourceforge.net>
 * Copyright (C) 2005-2015 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2010      Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2013      Cédric Salvador      <csalvador@gpcsolutions.fr>
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
 */

/**
 *  \file       htdocs/match/document.php
 *  \brief      Tab for documents linked to match
 *  \ingroup    match
 */

require 'config.php';

dol_include_once('match/class/match.class.php');
dol_include_once('match/lib/match.lib.php');
require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';

// Load translation files required by page
$langs->loadLangs(array('matchs', 'other'));

$action = GETPOST('action', 'aZ09');
$confirm = GETPOST('confirm');
$id = (GETPOST('matchid', 'int') ? GETPOST('matchid', 'int') : GETPOST('id', 'int'));
$ref = GETPOST('ref', 'alpha');
$contextpage = GETPOST('contextpage', 'aZ') ?GETPOST('contextpage', 'aZ') : 'matchdoc'; // To manage different context of search

// Define value to know what current match can do on matchs
$canaddmatch = (!empty($user->admin) || $user->rights->match->write);
$canreadmatch = (!empty($user->admin) || $user->rights->match->read);
$caneditmatch = (!empty($user->admin) || $user->rights->match->write);
$candisablematch = (!empty($user->admin) || $user->rights->match->delete);

// Security check
$socid = 0;
if ($match->socid > 0) $socid = $match->socid;
$feature2 = 'match';

if ($match->id <> $id && !$canreadmatch) accessforbidden();

// Get parameters
$limit = GETPOST('limit', 'int') ? GETPOST('limit', 'int') : $conf->liste_limit;
$sortfield = GETPOST("sortfield", 'alpha');
$sortorder = GETPOST("sortorder", 'alpha');
$page = GETPOSTISSET('pageplusone') ? (GETPOST('pageplusone') - 1) : GETPOST("page", 'int');
if (empty($page) || $page == -1) { $page = 0; }
$offset = $limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (!$sortorder) $sortorder = "ASC";
if (!$sortfield) $sortfield = "position_name";

$object = new Match($db);
if ($id > 0 || !empty($ref))
{
	$result = $object->fetch($id, $ref, '', 1);
	$object->getrights();
	//$upload_dir = $conf->match->multidir_output[$object->entity] . "/" . $object->id ;
	// For matchs, the upload_dir is always $conf->match->entity for the moment
	$upload_dir = $conf->match->dir_output."/".$object->ref;
}

// Initialize technical object to manage hooks of page. Note that conf->hooks_modules contains array of hook context
$hookmanager->initHooks(array('matchcard', 'matchdoc', 'globalcard'));


/*
 * Actions
 */

$parameters = array('id'=>$socid);
$reshook = $hookmanager->executeHooks('doActions', $parameters, $object, $action); // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

if (empty($reshook)) {
	include_once DOL_DOCUMENT_ROOT.'/core/actions_linkedfiles.inc.php';
}


/*
 * View
 */

$form = new Form($db);

llxHeader('', $langs->trans("MatchCard").' - '.$langs->trans("Files"));

if ($object->id)
{
	/*
	 * Affichage onglets
	 */
	if (!empty($conf->notification->enabled)) $langs->load("mails");
	$head = match_prepare_head($object);

	$form = new Form($db);

	dol_fiche_head($head, 'document', $langs->trans("Match"), -1, 'match');

	$linkback = '';
	if ($user->rights->match->read || $user->admin) {
		$linkback = '<a href="'.DOL_URL_ROOT.'/match/list.php?restore_lastsearch_values=1">'.$langs->trans("BackToList").'</a>';
	}

    dol_banner_tab($object, 'id', $linkback, $user->rights->match->lire || $user->admin);

    print '<div class="fichecenter">';
    print '<div class="underbanner clearboth"></div>';

	// Build file list
	$filearray = dol_dir_list($upload_dir, "files", 0, '', '(\.meta|_preview.*\.png)$', $sortfield, (strtolower($sortorder) == 'desc' ?SORT_DESC:SORT_ASC), 1);
	$totalsize = 0;
	foreach ($filearray as $key => $file)
	{
		$totalsize += $file['size'];
	}


	print '<table class="border tableforfield centpercent">';

    // Login
    print '<tr><td class="titlefield">'.$langs->trans("Login").'</td><td class="valeur">'.$object->login.'&nbsp;</td></tr>';

	// Nbre files
	print '<tr><td>'.$langs->trans("NbOfAttachedFiles").'</td><td>'.count($filearray).'</td></tr>';

	//Total taille
	print '<tr><td>'.$langs->trans("TotalSizeOfAttachedFiles").'</td><td>'.dol_print_size($totalsize, 1, 1).'</td></tr>';

	print '</table>';
    print '</div>';

	dol_fiche_end();


	$modulepart = 'match';
	$permission = $user->rights->match->write;
	$permtoedit = $user->rights->match->write;
	$param = '&id='.$object->id;
	include_once DOL_DOCUMENT_ROOT.'/core/tpl/document_actions_post_headers.tpl.php';
}
else
{
	accessforbidden('', 0, 1);
}

// End of page
llxFooter();
$db->close();
