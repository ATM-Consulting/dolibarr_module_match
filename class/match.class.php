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

if (!class_exists('SeedObject'))
{
	/**
	 * Needed if $form->showLinkedObjectBlock() is call or for session timeout on our module page
	 */
	define('INC_FROM_DOLIBARR', true);
	require_once dirname(__FILE__).'/../config.php';
}


class match extends SeedObject
{
    /**
     * Draft status
     */
    const STATUS_DRAFT = 0;
	/**
	 * Validated status
	 */
	const STATUS_VALIDATED = 1;
	/**
	 * Accepted status
	 */
	const STATUS_FINISH = 2;

	/** @var array $TStatus Array of translate key for each const */
	public static $TStatus = array(
		self::STATUS_DRAFT => 'matchStatusShortDraft'
		,self::STATUS_VALIDATED => 'matchStatusShortValidated'
		,self::STATUS_FINISH => 'matchStatusShortAccepted'
	);

	/** @var string $table_element Table name in SQL */
	public $table_element = 'match';

	/** @var string $element Name of the element (tip for better integration in Dolibarr: this value should be the reflection of the class name with ucfirst() function) */
	public $element = 'match';

	/** @var int $isextrafieldmanaged Enable the fictionalises of extrafields */
    public $isextrafieldmanaged = 1;

    /** @var int $ismultientitymanaged 0=No test on entity, 1=Test with field entity, 2=Test with link by societe */
    public $ismultientitymanaged = 1;

    /**
     *  'type' is the field format.
     *  'label' the translation key.
     *  'enabled' is a condition when the field must be managed.
     *  'visible' says if field is visible in list (Examples: 0=Not visible, 1=Visible on list and create/update/view forms, 2=Visible on list only, 3=Visible on create/update/view form only (not list), 4=Visible on list and update/view form only (not create). Using a negative value means field is not shown by default on list but can be selected for viewing)
     *  'noteditable' says if field is not editable (1 or 0)
     *  'notnull' is set to 1 if not null in database. Set to -1 if we must set data to null if empty ('' or 0).
     *  'default' is a default value for creation (can still be replaced by the global setup of default values)
     *  'index' if we want an index in database.
     *  'foreignkey'=>'tablename.field' if the field is a foreign key (it is recommanded to name the field fk_...).
     *  'position' is the sort order of field.
     *  'searchall' is 1 if we want to search in this field when making a search from the quick search button.
     *  'isameasure' must be set to 1 if you want to have a total on list for this field. Field type must be summable like integer or double(24,8).
     *  'css' is the CSS style to use on field. For example: 'maxwidth200'
     *  'help' is a string visible as a tooltip on field
     *  'comment' is not used. You can store here any text of your choice. It is not used by application.
     *  'showoncombobox' if value of the field must be visible into the label of the combobox that list record
     *  'arraykeyval' to set list of value if type is a list of predefined values. For example: array("0"=>"Draft","1"=>"Active","-1"=>"Cancel")
     */

    public $fields = array(

        'ref' => array(
            'type' => 'varchar(50)',
            'length' => 50,
            'label' => 'Ref',
            'enabled' => 1,
            'visible' => 2,
            'notnull' => 0,
            'showoncombobox' => 1,
            'index' => 1,
            'position' => 10,
            'searchall' => 1,
            'comment' => 'Reference of object'
        ),

        'entity' => array(
            'type' => 'integer',
            'label' => 'Entity',
            'enabled' => 1,
            'visible' => 0,
            'index' => 1,
            'position' => 20
        ),

        'date' => array(
            'type' => 'date',
            'label' => 'Date',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 1,
            'default' => null,
            'index' => 1,
            'position' => 30
        ),

        'fk_user_1_1' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'User1',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 1,
            'default' => null,
            'index' => 1,
            'position' => 50
        ),

        'fk_user_1_2' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'User2',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 0,
            'index' => 1,
            'position' => 51
        ),

        'fk_user_2_1' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'User3',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 1,
            'default' => null,
            'index' => 1,
            'position' => 52
        ),

        'fk_user_2_2' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'User4',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 0,
            'index' => 1,
            'position' => 53
        ),

        'score_1' => array(
            'type' => 'integer',
            'label' => 'Score 1',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 0,
            'index' => 1,
            'default' => '0',
            'position' => 70
        ),

        'score_2' => array(
            'type' => 'integer',
            'label' => 'Score 2',
            'enabled' => 1,
            'visible' => 1,
            'notnull' => 0,
            'index' => 1,
            'default' => '0',
            'position' => 80
        ),

        'winner_1' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'Winner',
            'enabled' => 1,
            'visible' => 5,
            'position' => 90
        ),

        'winner_2' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'Winner',
            'enabled' => 1,
            'visible' => 5,
            'position' => 90
        ),

        'looser_1' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'Looser',
            'enabled' => 1,
            'visible' => 5,
            'position' => 100
        ),

        'looser_2' => array(
            'type' => 'integer:User:user/class/user.class.php',
            'label' => 'Looser',
            'enabled' => 1,
            'visible' => 5,
            'position' => 100
        ),

        'fk_discipline' => array(
            'type' => 'sellist:c_dictdiscipline:label:rowid::active=1',
            'label' => 'Discipline',
            'visible' => 1,
            'enabled' => 1,
            'position' => 10,
            'index' => 1
        ),

        'status' => array(
            'type' => 'integer',
            'label' => 'Status',
            'enabled' => 1,
            'visible' => 2,
            'default' => '0',
            'index' => 1,
            'position' => 40,
            'arrayofkeyval' => array(
                0 => 'Draft',
                1 => 'Active',
                -1 => 'Canceled'
            )
        ),

        'import_key' => array(
            'type' => 'varchar(14)',
            'label' => 'ImportId',
            'enabled' => 1,
            'visible' => -2,
            'notnull' => -1,
            'index' => 0,
            'position' => 1000
        ),

    );

    /** @var string $ref Object reference */
	public $ref;

    /** @var int $entity Object entity */
	public $entity;

    /** @var int $status Object date */
    public $date;
    
    /** @var int $status Object status */
    public $status;

    /** @var int $status Object team1 */
    public $fk_user_1_1;

    /** @var int $status Object team2 */
    public $fk_user_1_2;

    /** @var int $status Object team1 */
    public $fk_user_2_1;

    /** @var int $status Object team2 */
    public $fk_user_2_2;

    /** @var string $label Object score1 */
    public $score_1;

    /** @var string $description Object description */
    public $score_2;

    public $winner_1;
    public $winner_2;

    public $looser_1;
    public $looser_2;

    /** @var string $description Object description */
    public $discipline;



    /**
     * match constructor.
     * @param DoliDB    $db    Database connector
     */
    public function __construct($db)
    {
		global $conf;

        parent::__construct($db);

		$this->init();

		$this->status = self::STATUS_DRAFT;
		$this->entity = $conf->entity;
    }

    /**
     * @param User $user User object
     * @return int
     */
    public function save($user)
    {
        global $langs;
        if ($this->fk_user_1_1 == '-1') {
            $this->fk_user_1_1 = null;
        }
        if ($this->fk_user_2_1 == '-1') {
            $this->fk_user_2_1 = null;
        }
        if ($this->fk_user_1_2 == '-1') {
            $this->fk_user_1_2 = null;
        }
        if ($this->fk_user_2_2 == '-1') {
            $this->fk_user_2_2 = null;
        }
        if ($this->score_1 == 0) {
            $this->score_1 = '0';
        }
        if ($this->score_2 == 0) {
            $this->score_2 = '0';
        }
        if (empty($this->status)) {
            $this->status = '0';
        }
        foreach ($this->fields as $key => $value) {
            if($value['notnull'] == 1 && empty($this->{$key})){
                setEventMessage($langs->trans('miss_required_field'), 'errors');
                return -1;
            }
        }
        $res = $this->create($user);
        if (!empty($this->is_clone) || empty($this->ref)) {
            
            // TODO determinate if auto generate
            $this->ref = '(PROV' . $this->id . ')';
            $res = $this->update($user);
        }
        return $res;
    }


    /**
     * @see cloneObject
     * @return void
     */
    public function clearUniqueFields()
    {
        $this->ref = 'Copy of '.$this->ref;
    }


    /**
     * @param User $user User object
     * @return int
     */
    public function delete(User &$user, $notrigger = false)
    {
        $this->deleteObjectLinked();
        
        $this->setReopen($user);

        unset($this->fk_element); // avoid conflict with standard Dolibarr comportment
        return parent::delete($user, $notrigger = false);
    }

    /**
     * @return string
     */
    public function getRef()
    {
		if (preg_match('/^[\(]?PROV/i', $this->ref) || empty($this->ref))
		{
			return $this->getNextRef();
		}

		return $this->ref;
    }

    /**
     * @return string
     */
    private function getNextRef()
    {
		global $db,$conf;

		require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';

		$mask = !empty($conf->global->MATCH_REF_MASK) ? $conf->global->MATCH_REF_MASK : 'MM{yy}{mm}-{0000}';
		$ref = get_next_value($db, $mask, 'match', 'ref');

		return $ref;
    }


    /**
     * @param User  $user   User object
     * @return int
     */
    public function setDraft($user)
    {
        if ($this->status === self::STATUS_VALIDATED)
        {
            $this->status = self::STATUS_DRAFT;
            $this->withChild = false;

            return $this->update($user);
        }

        return 0;
    }

    /**
     * @param User $user User object
     * @return int
     */
    public function setValid($user)
    {
        if ($this->status === self::STATUS_DRAFT || $this->status === self::STATUS_FINISH) {
            // TODO determinate if auto generate
            $this->ref = $this->getRef();
            $this->fk_user_valid = $user->id;
            $this->status = self::STATUS_VALIDATED;
            $this->withChild = false;

            return $this->save($user);
        }

        return 0;
    }

    /**
     * @param User  $user   User object
     * @return int
     */
    public function setAccepted($user)
    {

        if ($this->status === self::STATUS_VALIDATED)
        {
            //Set winner and looser
            if ($this->score_1 > $this->score_2) {
                $this->winner_1 = $this->fk_user_1_1;
                $this->winner_2 = $this->fk_user_1_2;
                $this->looser_1 = $this->fk_user_2_1;
                $this->looser_2 = $this->fk_user_2_2;
            } elseif ($this->score_2 > $this->score_1) {
                $this->winner_1 = $this->fk_user_2_1;
                $this->winner_2 = $this->fk_user_2_2;
                $this->looser_1 = $this->fk_user_1_1;
                $this->looser_2 = $this->fk_user_1_2;
            }
            $this->injectScoreToUserExtrafields($this->fk_user_1_1, $this->score_1);
            $this->injectScoreToUserExtrafields($this->fk_user_1_2, $this->score_1);
            $this->injectScoreToUserExtrafields($this->fk_user_2_1, $this->score_2);
            $this->injectScoreToUserExtrafields($this->fk_user_2_2, $this->score_2);
            
            $this->status = self::STATUS_FINISH;
            $this->withChild = false;

            return $this->save($user);
        }

        return 0;
    }

    /**
     * @param user_id  id utilisateur
     * @return int
     */
    private function injectScoreToUserExtrafields($user_id, $score)
    {
        global $user;
        $player = new User($this->db);
        $player->fetch($user_id);
        $player->array_options['options_nbr_match']++;
        $player->array_options['options_nbr_goal'] += $score;
        if($score == 10) {
            $player->array_options['options_nbr_win']++;  
        }
        else {
            $player->array_options['options_nbr_loose']++;
        }
        $player->array_options['options_ratio_win_loose'] = $player->array_options['options_nbr_win'] / $player->array_options['options_nbr_match'] * 100;
        $player->update($user);
    }


    /**
     * @param User $user User object
     * @return int
     */
    public function setReopen($user)
    {
        if ($this->status === self::STATUS_FINISH)
        {
            $this->reverseScoreToUserExtrafields($this->fk_user_1_1, $this->score_1);
            $this->reverseScoreToUserExtrafields($this->fk_user_1_2, $this->score_1);
            $this->reverseScoreToUserExtrafields($this->fk_user_2_1, $this->score_2);
            $this->reverseScoreToUserExtrafields($this->fk_user_2_2, $this->score_2);
            
            $this->status = self::STATUS_VALIDATED;
            $this->withChild = false;

            return $this->save($user);
        }

        return 0;
    }
    /**
     * @param user_id  id utilisateur
     * @return int
     */
    private function reverseScoreToUserExtrafields($user_id, $score)
    {
        global $user;
        $player = new User($this->db);
        $player->fetch($user_id);
        if(!empty($player)){
            $player->array_options['options_nbr_match']--;
            $player->array_options['options_nbr_goal'] -= $score;
            if ($score == 10) {
                $player->array_options['options_nbr_win']--;
            } else {
                $player->array_options['options_nbr_loose']--;
            }
            if($player->array_options['options_nbr_match'] == 0){
                $player->array_options['options_ratio_win_loose'] = null;
            }
            else{
                $player->array_options['options_ratio_win_loose'] = $player->array_options['options_nbr_win'] / $player->array_options['options_nbr_match'] * 100;
            }
            $player->update($user);
        }
    }


    /**
     * @param int    $withpicto     Add picto into link
     * @param string $moreparams    Add more parameters in the URL
     * @return string
     */
    public function getNomUrl($withpicto = 0, $moreparams = '')
    {
		global $langs;

        $result='';
        $label = '<u>' . $langs->trans("Showmatch") . '</u>';
        if (! empty($this->ref)) $label.= '<br><b>'.$langs->trans('Ref').':</b> '.$this->ref;

        $linkclose = '" title="'.dol_escape_htmltag($label, 1).'" class="classfortooltip">';
        $link = '<a href="'.dol_buildpath('/match/card.php', 1).'?id='.$this->id.urlencode($moreparams).$linkclose;

        $linkend='</a>';

        $picto='generic';
        //        $picto='match@match';

        if ($withpicto) $result.=($link.img_object($label, $picto, 'class="classfortooltip"').$linkend);
        if ($withpicto && $withpicto != 2) $result.=' ';

        $result.=$link.$this->ref.$linkend;

        return $result;
    }

    /**
     * @param int       $id             Identifiant
     * @param null      $ref            Ref
     * @param int       $withpicto      Add picto into link
     * @param string    $moreparams     Add more parameters in the URL
     * @return string
     */
    public static function getStaticNomUrl($id, $ref = null, $withpicto = 0, $moreparams = '')
    {
		global $db;

		$object = new match($db);
		$object->fetch($id, false, $ref);

		return $object->getNomUrl($withpicto, $moreparams);
    }


    /**
     * @param int $mode     0=Long label, 1=Short label, 2=Picto + Short label, 3=Picto, 4=Picto + Long label, 5=Short label + Picto, 6=Long label + Picto
     * @return string
     */
    public function getLibStatut($mode = 0)
    {
        return self::LibStatut($this->status, $mode);
    }

    /**
     * @param int       $status   Status
     * @param int       $mode     0=Long label, 1=Short label, 2=Picto + Short label, 3=Picto, 4=Picto + Long label, 5=Short label + Picto, 6=Long label + Picto
     * @return string
     */
    public static function LibStatut($status, $mode)
    {
		global $langs;

		$langs->load('match@match');
        $res = '';

        if ($status==self::STATUS_DRAFT) { $statusType='status0'; $statusLabel=$langs->trans('matchStatusDraft'); $statusLabelShort=$langs->trans('matchStatusShortDraft'); }
        elseif ($status==self::STATUS_VALIDATED) { $statusType='status1'; $statusLabel=$langs->trans('matchStatusValidated'); $statusLabelShort=$langs->trans('matchStatusShortValidate'); }
        elseif ($status==self::STATUS_FINISH) { $statusType='status6'; $statusLabel=$langs->trans('matchStatusAccepted'); $statusLabelShort=$langs->trans('matchStatusShortAccepted'); }

        if (function_exists('dolGetStatus'))
        {
            $res = dolGetStatus($statusLabel, $statusLabelShort, '', $statusType, $mode);
        }
        else
        {
            if ($mode == 0) $res = $statusLabel;
            elseif ($mode == 1) $res = $statusLabelShort;
            elseif ($mode == 2) $res = img_picto($statusLabel, $statusType).$statusLabelShort;
            elseif ($mode == 3) $res = img_picto($statusLabel, $statusType);
            elseif ($mode == 4) $res = img_picto($statusLabel, $statusType).$statusLabel;
            elseif ($mode == 5) $res = $statusLabelShort.img_picto($statusLabel, $statusType);
            elseif ($mode == 6) $res = $statusLabel.img_picto($statusLabel, $statusType);
        }
        
        return $res;
    }

    /**
     * @Overide
     */
   /* public function setValues(&$Tab)
    {

        foreach ($Tab as $key => &$value) {
           if($key == '' && $value == '-1'){
                $value = null;
           }
        }

        return parent::setValues($Tab);
    }*/
}


//class matchDet extends SeedObject
//{
//    public $table_element = 'matchdet';
//
//    public $element = 'matchdet';
//
//
//    /**
//     * matchDet constructor.
//     * @param DoliDB    $db    Database connector
//     */
//    public function __construct($db)
//    {
//        $this->db = $db;
//
//        $this->init();
//    }
//}
