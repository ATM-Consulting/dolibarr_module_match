<?php
/* Copyright (C) 2020 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/actions_match.class.php
 * \ingroup match
 * \brief   This file is an example hook overload class file
 *          Put some comments here
 */

dol_include_once('match/lib/match.lib.php');

/**
 * Class Actionsmatch
 */
class Actionsmatch
{
	/**
	 * @var DoliDb		Database handler (result of a new DoliDB)
	 */
	public $db;

	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 * @param DoliDB    $db    Database connector
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    $object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function getNomUrl($parameters, &$object, &$action, $hookmanager)
	{
		$error = 0; // Error counter
		$myvalue = ''; // A result value

		if (in_array('userdao', explode(':', $parameters['context']))) {
			$rank = calculRankPlayer($object);
			if ($rank != 0) {
				$myvalue = '&nbsp;&nbsp;<img valign="middle" src="' . dol_buildpath('/match/img/rang_' . $rank[0] . '.png', 1) . '" title="Rang: '. $rank[1].'" width="30px"/>';
			}
			// do something only for the context 'somecontext'
		}

		if (!$error) {
			$this->results = array('myreturn' => $myvalue);
			$this->resprints = $myvalue;
			return 0; // or return 1 to replace standard code
		} else {
			$this->errors[] = 'Error message';
			return -1;
		}
	}

	public function addMoreActionsButtons($parameters, &$object, &$action, $hookmanager)
	{
		if (in_array('usercard', explode(':', $parameters['context']))) {
			$rank = calculRankPlayer($object);
			if ($rank != 0) {
				print '<div><img id="img_rank_user" src="' . dol_buildpath('/match/img/rang_' . $rank[0] . '.png', 1) . '" title="Rang: ' . $rank[1] . '" style="width:50px;vertical-align:middle"/></div>';
?>
				<script type="text/javascript">
					$(document).ready(function() {
						$divImg = $("#img_rank_user");
						$(".arearef .statusref").prepend($divImg)
					})
				</script>
<?php
			}
			// do something only for the context 'somecontext'
		}
	}
}
