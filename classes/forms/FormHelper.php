<?php

require_once 'classes/utility/Database.php';

class FormHelper {

    const DUMMY_FIELD = "DummyValue";
    const NON_APPLICABLE = "N/A";

    public static function checkIfDummyValue($item) {
        if ($item == self::DUMMY_FIELD) {
            return self::NON_APPLICABLE;
        } else {
            return $item;
        }
    }

    public static function displayComboBox($dropDownListTable, $dropDownListKey, $dropDownListFieldName, $inputName) {
        $db = Database::getInstance();
        $db->query('SELECT * FROM ' . $dropDownListTable . ' ORDER BY ' . $dropDownListKey . ' ASC');
        echo '<select name="' . $inputName . '[]" id="' . $inputName . '" multiple="multiple">';
        foreach ($db->getResults() as $row) {
            echo '<option value="' . $row->$dropDownListKey . '">' .
            $row->$dropDownListFieldName . '</option>';
        }
        echo '</select>';
    }

    public static function displayComboBoxSelection($dropDownListTable, $dropDownListKey, $dropDownListFieldName, $inputName, $listIDs = array()) {
        $db = Database::getInstance();
        $db->query('SELECT * FROM ' . $dropDownListTable . ' ORDER BY ' . $dropDownListKey . ' ASC');
        echo '<select name="' . $inputName . '[]" id="' . $inputName . '" multiple="multiple">';
        foreach ($db->getResults() as $row) {
            if (in_array($row->$dropDownListKey, $listIDs)) {
                echo '<option value="' . $row->$dropDownListKey . '" selected>' .
                $row->$dropDownListFieldName . '</option>';
            } else {
                echo '<option value="' . $row->$dropDownListKey . '">' .
                $row->$dropDownListFieldName . '</option>';
            }
        }
        echo '</select>';
    }

    public static function displayDropdownBoxNotRequired($dropDownListTable, $dropDownListKey, $dropDownListFieldName, $inputName, $dropDownName) {
        $db = Database::getInstance();
        $db->query('SELECT * FROM ' . $dropDownListTable . ' ORDER BY ' . $dropDownListKey . ' ASC');
        echo '<select name="' . $inputName . '" id="' . $inputName . '">';
        echo '<option value="">' . $dropDownName . '</option>';
        foreach ($db->getResults() as $row) {
            echo '<option value="' . $row->$dropDownListKey . '">' .
            $row->$dropDownListFieldName . '</option>';
        }
        echo '</select>';
    }

      public static function displayDoubleContentDropdownBoxRequired($dropDownListTable, $dropDownListKey, $dropDownListFieldName, $inputName, $dropDownName) {
        $db = Database::getInstance();
        $db->query('SELECT * FROM ' . $dropDownListTable . ' ORDER BY ' . $dropDownListKey . ' ASC');
        echo '<select name="' . $inputName . '" id="' . $inputName . '" required="required">';
        echo '<option value="">' . $dropDownName . '</option>';
        foreach ($db->getResults() as $row) {
            echo '<option value="' . $row->$dropDownListKey . '">' . '[' . $row->$dropDownListKey . '] ' . str_repeat('&nbsp;', 2) .
            $row->$dropDownListFieldName . '</option>';
        }
        echo '</select>';
    }
    
    public static function displayDropdownBoxRequired($dropDownListTable, $dropDownListKey, $dropDownListFieldName, $inputName, $dropDownName) {
        $db = Database::getInstance();
        $db->query('SELECT * FROM ' . $dropDownListTable . ' ORDER BY ' . $dropDownListKey . ' ASC');
        echo '<select name="' . $inputName . '" id="' . $inputName . '" required="required">';
        echo '<option value="">' . $dropDownName . '</option>';
        foreach ($db->getResults() as $row) {
            echo '<option value="' . $row->$dropDownListKey . '">' .
            $row->$dropDownListFieldName . '</option>';
        }
        echo '</select>';
    }

    public static function displayDropdownSelection($dropDownListTable, $dropDownListKey, $dropDownListFieldName, $inputName, $listIDs = array()) {
        $db = Database::getInstance();
        $db->query('SELECT * FROM ' . $dropDownListTable . ' ORDER BY ' . $dropDownListKey . ' ASC');
        echo '<select name="' . $inputName . '" id="' . $inputName . '">';
        foreach ($db->getResults() as $row) {
            if (in_array($row->$dropDownListKey, $listIDs)) {
                echo '<option value="' . $row->$dropDownListKey . '" selected>' .
                $row->$dropDownListFieldName . '</option>';
            } else {
                echo '<option value="' . $row->$dropDownListKey . '">' .
                $row->$dropDownListFieldName . '</option>';
            }
        }
        echo '</select>';
    }
    
    public static function displayOrderedNumberedArray($list = array()) {
        echo '<ol>';
        for ($i = 0; $i < count($list); $i++) {
            echo '<li>' . $list[$i] . '</li>';
        }
        echo '</ol>';
    }

    /**
     * Provides the list of IDs for field forms that are dropdown list
     * @param string $studyCode code for article
     * @param boolean $isDBValueExists indicates if article has value for dropdown
     * @param string $phaseDropDownListTable name of table for phase for dropdown list
     * @param string $dropDownListTable name of table with content
     * @param string $dropDownListTableKey name of primary key
     */
    public static function getDropDownListIDs($studyCode, $isDBValueExists, $phaseDropDownListTable, $dropDownListTable, $dropDownListTableKey) {
        $list = array();
        $db = Database::getInstance();
        if ($isDBValueExists == '1') {
            $db->select($phaseDropDownListTable, array('StudyCode', '=', $studyCode));
            foreach ($db->getResults() as $data) {
                $db->select($dropDownListTable, array($dropDownListTableKey, '=', $data->$dropDownListTableKey));
                foreach ($db->getResults() as $id) {
                    array_push($list, $id->$dropDownListTableKey);
                }
            }
        }
        return $list;
    }

    public static function getDropDownListNames($studyCode, $isDBValueExists, $phaseDropDownListTable, $dropDownListTable, $dropDownListTableKey, $fieldName) {
        $list = array();
        $db = Database::getInstance();
        if ($isDBValueExists == '1') {
            $db->select($phaseDropDownListTable, array('StudyCode', '=', $studyCode));
            foreach ($db->getResults() as $data) {
                $db->select($dropDownListTable, array($dropDownListTableKey, '=', $data->$dropDownListTableKey));
                foreach ($db->getResults() as $name) {
                    array_push($list, $name->$fieldName);
                }
            }
        }
        return $list;
    }
}