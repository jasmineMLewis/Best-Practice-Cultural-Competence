<?php
require_once 'classes/utility/Database.php';
require_once 'classes/utility/Session.php';
require_once 'classes/ChecklistScreen.php';
require_once 'classes/KirkpatrickRating.php';
require_once 'classes/Reviewer.php';
require_once 'classes/StudyInventory.php';

class Admin extends Reviewer {

    private $_db;

    public function __construct() {
        parent::__construct();
        $this->_db = Database::getInstance();
    }

    public function assignArticleNewReviewer($studyCode, $oldReviewer, $newReviewer) {
        //delete all of old reviewer cotent from all tables
        $this->_db->query("DELETE FROM article_process_stage_t WHERE (StudyCode = '$studyCode' AND ReviewerID =$oldReviewer)");
        $this->_db->query("DELETE FROM article_screen_checklist_t WHERE (StudyCode = '$studyCode' AND ReviewerID =$oldReviewer)");
         $this->_db->query("DELETE FROM data_extraction_t WHERE (StudyCode = '$studyCode' AND ReviewerID =$oldReviewer)");
         $this->_db->query("DELETE FROM data_extract_assessment_intervention_t WHERE (StudyCode = '$studyCode')");
         $this->_db->query("DELETE FROM data_extract_gender_t WHERE (StudyCode = '$studyCode')");
          $this->_db->query("DELETE FROM data_extract_instructional_resource_t WHERE (StudyCode = '$studyCode')");
           $this->_db->query("DELETE FROM data_extract_learner_outcome_t WHERE (StudyCode = '$studyCode')");
            $this->_db->query("DELETE FROM data_extract_participant_t WHERE (StudyCode = '$studyCode')");
             $this->_db->query("DELETE FROM data_extract_profession_t WHERE (StudyCode = '$studyCode')");
              $this->_db->query("DELETE FROM data_extract_study_design_t WHERE (StudyCode = '$studyCode')");
               $this->_db->query("DELETE FROM data_extract_teaching_method_t WHERE (StudyCode = '$studyCode')");
         $this->_db->query("DELETE FROM quest_appraise_t WHERE (StudyCode = '$studyCode' AND ReviewerID =$oldReviewer)");
         $this->_db->query("DELETE FROM kirkpatrick_rating_t WHERE (StudyCode = '$studyCode' AND ReviewerID =$oldReviewer)");
        
        
        //assign to new reviewer
        $this->_db->insert("article_process_stage_t", array(
            "StudyCode" => $studyCode,
            "ReviewerID" => $newReviewer,
            "ArticleProcessID" => "1",
            "IsDisabled" => "0"
        ));

        //if final decision is already decided delete it
        $this->_db->delete('article_screen_checklist_final_decision_t', array('StudyCode', '=', $studyCode));
    }

    public function disableArticle($studyCode, $reviewerID) {

        $this->_db->updateMulitipleWhere("article_process_stage_t", 'StudyCode', $studyCode, 'ReviewerID', $reviewerID, array(
            "IsDisabled" => 1
        ));
    }

    public function disableReviewer($reviewerID) {
        $this->_db->update("reviewer_t", 'ReviewerID', $reviewerID, array(
            "IsDisabled" => 1
        ));
    }
    
    public function getReviewersNamesNotAssignedToStudyCode($studyCode) {
        $inventory = new StudyInventory();
        $IDsForCode = $inventory->getTeamMembersForCode($studyCode);

        //go to article process stage table and get the Reviwers ids assigned
        $checklist = new ChecklistScreen();
        $IDsAssignToCode = $checklist->getReviewerIDsAssignedToStudyCode($studyCode);

        //IDs not assigned 
        $reviewersIDsNotAssigned = array_diff($IDsForCode, $IDsAssignToCode);

        $reviewerIDs = array();
        while (!empty($reviewersIDsNotAssigned)) {
            array_push($reviewerIDs, array_pop($reviewersIDsNotAssigned));
        }
        $reviewerIDs = array_reverse($reviewerIDs);
        return $this->getReviewerNames($reviewerIDs);
    }

    public function getReviewersIDsNotAssignedToStudyCode($studyCode) {
        $inventory = new StudyInventory();
        $IDsForCode = $inventory->getTeamMembersForCode($studyCode);

        //go to article process stage table and get the Reviwers ids assigned
        $checklist = new ChecklistScreen();
        $IDsAssignToCode = $checklist->getReviewerIDsAssignedToStudyCode($studyCode);

        //IDs not assigned 
        $arr = array_diff($IDsForCode, $IDsAssignToCode);
        $reviewerIDs = array();
        while (!empty($arr)) {
            array_push($reviewerIDs, array_pop($arr));
        }
        return array_reverse($reviewerIDs);
    }

    public function getAssignedCodesForReviewer($reviewerID, $table) {
        $this->_db->select($table, array('ReviewerID', '=', $reviewerID));
        $codes = array();
        foreach ($this->_db->getResults() as $code) {
            array_push($codes, $code->Code);
        }
        return $codes;
    }

    public function getAssignedCodesForAdmin($reviewerID, $table) {
        $this->_db->select($table, array('ReviewerID', '=', $reviewerID));
        $codes = array();
        foreach ($this->_db->getResults() as $code) {
            array_push($codes, $code->Code);
        }
        return $codes;
    }

    public function getReviewerIDsAssignedToStudyCode($StudyCode) {
        $reviewerIDs = array();
        $this->_db->select('article_process_stage_t', array('StudyCode', '=', $StudyCode));
        foreach ($this->_db->getResults() as $reviewerID) {
            array_push($reviewerIDs, $reviewerID->ReviewerID);
        }
        return $reviewerIDs;
    }

    public function getReviewersNamesAssignedToStudyCode($StudyCode) {
        $reviewerIDs = array();
        $this->_db->select('article_process_stage_t', array('StudyCode', '=', $StudyCode));
        foreach ($this->_db->getResults() as $reviewerID) {
            array_push($reviewerIDs, $reviewerID->ReviewerID);
        }

        $reviewerNames = array();
        for ($i = 0; $i < count($reviewerIDs); $i++) {
            $this->_db->select('reviewer_t', array('ReviewerID', '=', $reviewerIDs[$i]));
            foreach ($this->_db->getResults() as $reviewerName) {
                array_push($reviewerNames, $reviewerName->FirstName . ' ' . $reviewerName->LastName);
            }
        }
        return $reviewerNames;
    }

    public function getReviewerNames($reviewerIDs = array()) {
        $reviewerNames = array();
        for ($i = 0; $i < count($reviewerIDs); $i++) {
            $this->_db->select('reviewer_t', array('ReviewerID', '=', $reviewerIDs[$i]));
            foreach ($this->_db->getResults() as $reviewerName) {
                array_push($reviewerNames, $reviewerName->FirstName . ' ' . $reviewerName->LastName);
            }
        }
        return $reviewerNames;
    }

    private function insertArrayDatabaseFields($table, $reviewerID, $field, $data = array()) {
        if (!empty($data) && is_array($data)) {
            for ($i = 0; $i < count($data); $i++) {
                $this->_db->insert($table, array(
                    "Code" => $data[$i],
                    $field => $reviewerID
                ));
            }
        }
    }

    public function registerReviewer($team = array(), $admin = array(), $info = array()) {
        empty($admin) ? $info['IsAdmin'] = "0" : $info['IsAdmin'] = "1";

        $this->_db->insert('reviewer_t', $info);
        $reviewerID = $this->_db->lastInsertId();
        if (!empty($team)) {
            $this->insertArrayDatabaseFields("team_t", $reviewerID, "ReviewerID", $team);
        }

        if (!empty($admin)) {
            $this->insertArrayDatabaseFields("team_admin_t", $reviewerID, "ReviewerID", $admin);
        }

        return $this->_db;
    }

    public function undisableArticle($studyCode, $reviewerID) {
        $this->_db->updateMulitipleWhere("article_process_stage_t", 'StudyCode', $studyCode, 'ReviewerID', $reviewerID, array(
            "IsDisabled" => 0
        ));
    }

    public function undisableReviewer($reviewerID) {
        $this->_db->update("reviewer_t", 'ReviewerID', $reviewerID, array(
            "IsDisabled" => 0
        ));
    }

    public function updateReviewer($reviewerID, $team = array(), $admin = array(), $info = array()) {
        empty($admin) ? $info['IsAdmin'] = "0" : $info['IsAdmin'] = "1";

        $this->_db->update('reviewer_t', 'ReviewerID', $reviewerID, $info);

        $this->_db->delete('team_t', array('ReviewerID', '=', $reviewerID));
        $this->_db->delete('team_admin_t', array('ReviewerID', '=', $reviewerID));

        if (!empty($team)) {
            $this->insertArrayDatabaseFields('team_t', $reviewerID, 'ReviewerID', $team);
        }

        if (!empty($admin)) {
            $this->insertArrayDatabaseFields('team_admin_t', $reviewerID, 'ReviewerID', $team);
        }
    }

}
