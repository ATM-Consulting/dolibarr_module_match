<?php
/* Copyright (C) 2017  Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *
 * Need to have following variables defined:
 * $object (invoice, order, ...)
 * $action
 * $conf
 * $langs
 * $form
 */

// Protection to avoid direct call of template
if (empty($conf) || !is_object($conf)) {
    print "Error, template page can't be called as URL";
    exit;
}
//var_dump($object->fields);

?>
<!-- BEGIN PHP TEMPLATE commonfields_add.tpl.php -->
<?php

$object->fields = dol_sort_array($object->fields, 'position');

foreach ($object->fields as $key => $val) {
    $type = explode(':', $val['type'])[0];
    //var_dump($key, $val);

    // Discard if extrafield is a hidden field on form
    if (abs($val['visible']) != 1 && abs($val['visible']) != 3) continue;

    if (array_key_exists('enabled', $val) && isset($val['enabled']) && !verifCond($val['enabled'])) continue; // We don't want this field

    print '<tr id="field_' . $key . '">';
    print '<td';
    print ' class="titlefieldcreate';
    if ($val['notnull'] > 0) print ' fieldrequired';
    if ($type == 'text' || $type == 'html') print ' tdtop';
    print '"';
    print '>';
    if (!empty($val['help'])) print $form->textwithpicto($langs->trans($val['label']), $langs->trans($val['help']));
    else print $langs->trans($val['label']);
    print '</td>';
    print '<td>';
    if (in_array($type, array('int', 'integer'))) $value = GETPOST($key, 'int');
    elseif ($type == 'text' || $type == 'html') $value = GETPOST($key, 'none');
    else $value = GETPOST($key, 'alpha');
    if ($val['noteditable']) print $object->showOutputField($val, $key, $value, '', '', '', 0);
    else print $object->showInputField($val, $key, $value, '', '', '', 0);
    print '</td>';
    print '</tr>';
}

function toto($value, $param,$keysuffix = '', $keyprefix = '', $key){ {

    global $db, $conf, $langs;

    $form = new Form($db);
        
    if (is_array($value)) {
        $value_arr = $value;
    } else {
        $value_arr = explode(',', $value);
    }

    if (is_array($param['options'])) {
            $param_list = array_keys($param['options']);
            $InfoFieldList = explode(":", $param_list[0]);
            $parentName = '';
            $parentField = '';
            // 0 : tableName
            // 1 : label field name
            // 2 : key fields name (if differ of rowid)
            // 3 : key field parent (for dependent lists)
            // 4 : where clause filter on column or table extrafield, syntax field='value' or extra.field=value
            $keyList = (empty($InfoFieldList[2]) ? 'rowid' : $InfoFieldList[2] . ' as rowid');

            if (count($InfoFieldList) > 3 && !empty($InfoFieldList[3])) {
                list($parentName, $parentField) = explode('|', $InfoFieldList[3]);
                $keyList .= ', ' . $parentField;
            }
            if (count($InfoFieldList) > 4 && !empty($InfoFieldList[4])) {
                if (strpos($InfoFieldList[4], 'extra.') !== false) {
                    $keyList = 'main.' . $InfoFieldList[2] . ' as rowid';
                } else {
                    $keyList = $InfoFieldList[2] . ' as rowid';
                }
            }

            $fields_label = explode('|', $InfoFieldList[1]);
            if (is_array($fields_label)) {
                $keyList .= ', ';
                $keyList .= implode(', ', $fields_label);
            }

            $sqlwhere = '';
            $sql = 'SELECT ' . $keyList;
            $sql .= ' FROM ' . MAIN_DB_PREFIX . $InfoFieldList[0];
            if (!empty($InfoFieldList[4])) {
                // can use SELECT request
                if (strpos($InfoFieldList[4], '$SEL$') !== false) {
                    $InfoFieldList[4] = str_replace('$SEL$', 'SELECT', $InfoFieldList[4]);
                }

                // current object id can be use into filter
                if (strpos($InfoFieldList[4], '$ID$') !== false && !empty($objectid)) {
                    $InfoFieldList[4] = str_replace('$ID$', $objectid, $InfoFieldList[4]);
                } else {
                    $InfoFieldList[4] = str_replace('$ID$', '0', $InfoFieldList[4]);
                }

                // We have to join on extrafield table
                if (strpos($InfoFieldList[4], 'extra') !== false) {
                    $sql .= ' as main, ' . MAIN_DB_PREFIX . $InfoFieldList[0] . '_extrafields as extra';
                    $sqlwhere .= ' WHERE extra.fk_object=main.' . $InfoFieldList[2] . ' AND ' . $InfoFieldList[4];
                } else {
                    $sqlwhere .= ' WHERE ' . $InfoFieldList[4];
                }
            } else {
                $sqlwhere .= ' WHERE 1=1';
            }
            // Some tables may have field, some other not. For the moment we disable it.
            if (in_array($InfoFieldList[0], array('tablewithentity'))) {
                $sqlwhere .= ' AND entity = ' . $conf->entity;
            }
            // $sql.=preg_replace('/^ AND /','',$sqlwhere);
            // print $sql;

            $sql .= $sqlwhere;
            //dol_syslog(get_class($this) . '::showInputField type=chkbxlst', LOG_DEBUG);
            $resql = $db->query($sql);
            if ($resql) {
                $num = $db->num_rows($resql);
                $i = 0;

                $data = array();

                while ($i < $num) {
                    $labeltoshow = '';
                    $obj = $db->fetch_object($resql);

                    $notrans = false;
                    // Several field into label (eq table:code|libelle:rowid)
                    $fields_label = explode('|', $InfoFieldList[1]);
                    if (count($fields_label) > 1) {
                        $notrans = true;
                        foreach ($fields_label as $field_toshow) {
                            $labeltoshow .= $obj->$field_toshow . ' ';
                        }
                    } else {
                        $labeltoshow = $obj->{$InfoFieldList[1]};
                    }
                    $labeltoshow = dol_trunc($labeltoshow, 45);

                    if (is_array($value_arr) && in_array($obj->rowid, $value_arr)) {
                        foreach ($fields_label as $field_toshow) {
                            $translabel = $langs->trans($obj->$field_toshow);
                            if ($translabel != $obj->$field_toshow) {
                                $labeltoshow = dol_trunc($translabel, 18) . ' ';
                            } else {
                                $labeltoshow = dol_trunc($obj->$field_toshow, 18) . ' ';
                            }
                        }

                        $data[$obj->rowid] = $labeltoshow;
                    } else {
                        if (!$notrans) {
                            $translabel = $langs->trans($obj->{$InfoFieldList[1]});
                            if ($translabel != $obj->{$InfoFieldList[1]}) {
                                $labeltoshow = dol_trunc($translabel, 18);
                            } else {
                                $labeltoshow = dol_trunc($obj->{$InfoFieldList[1]}, 18);
                            }
                        }
                        if (empty($labeltoshow)) {
                            $labeltoshow = '(not defined)';
                        }

                        if (is_array($value_arr) && in_array($obj->rowid, $value_arr)) {
                            $data[$obj->rowid] = $labeltoshow;
                        }

                        if (!empty($InfoFieldList[3]) && $parentField) {
                            $parent = $parentName . ':' . $obj->{$parentField};
                        }

                        $data[$obj->rowid] = $labeltoshow;
                    }

                    $i++;
                }
                $db->free($resql);

                $out = $form->multiselectarray($keyprefix . $key . $keysuffix, $data, $value_arr, '', 0, '', 0, '100%');
            } else {
                print 'Error in request ' . $sql . ' ' . $db->lasterror() . '. Check setup of extra parameters.<br>';
            }
        }
}