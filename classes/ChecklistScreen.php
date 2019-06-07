<?php

require_once 'classes/utility/Database.php';
require_once 'classes/forms/FormHelper.php';
require_once 'classes/StudyInventory.php';

class ChecklistScreen {

    const DUMMY_FIELD = "DummyValue";

    private $_db;

    public function __construct() {
        $this->_db = Database::getInstance();
    }

    public function create($arr = array()) {
        $inventory = new StudyInventory();
        $dummyValueFields = array("DecisionComments");

        $data = $inventory->fillEmptyFieldsWithDefault(self::DUMMY_FIELD, $arr, $dummyValueFields);
        $this->_db->insert("article_screen_checklist_t", $data);
    }

    public function determineFinalDecision($studyCode) {
        $decisionIDS = array();
        $this->_db->select('article_screen_checklist_t', array('StudyCode', '=', $studyCode));
        foreach ($this->_db->getResults() as $results) {
            $decisionID = $results->DecisionID;
            array_push($decisionIDS, $decisionID);
        }

        $totalReviewersAssigned = $this->getTotalReviewersAssignedToArticle($studyCode);
        if ($totalReviewersAssigned == 1) {
            $this->_db->query("SELECT * FROM article_screen_checklist_final_decision_t WHERE StudyCode = '$studyCode'");
            if ($this->_db->getCount() < 1) {
            if (count(array_unique($decisionIDS)) == 1) {
                $this->_db->insert('article_screen_checklist_final_decision_t', array(
                    "StudyCode" => $studyCode,
                    "DecisionID" => $decisionIDS[0]
                ));
                $decision = $decisionIDS[0];
            } else {
                //flag
                $this->_db->insert('article_screen_checklist_final_decision_t', array(
                    "StudyCode" => $studyCode,
                    "DecisionID" => 3
                ));
                $decision = 3;
            }
           }else{
                if (count(array_unique($decisionIDS)) == 1) {
                $this->_db->update('article_screen_checklist_final_decision_t', 'StudyCode', $studyCode, array(
                    "DecisionID" => $decisionIDS[0]
                ));
                $decision = $decisionIDS[0];
            } else {
                //flag
                $this->_db->update('article_screen_checklist_final_decision_t', 'StudyCode', $studyCode, array(
                    "DecisionID" => 3
                ));
                $decision = 3;
            }
           }
        } else if ($totalReviewersAssigned == 2) {
            //check if final decision exists
            $this->_db->query("SELECT * FROM article_screen_checklist_final_decision_t WHERE StudyCode = '$studyCode'");
            if ($this->_db->getCount() < 1) {
                if (count(array_unique($decisionIDS)) == 1) {
                    $this->_db->insert('article_screen_checklist_final_decision_t', array(
                        "StudyCode" => $studyCode,
                        "DecisionID" => $decisionIDS[0]
                    ));
                    $decision = $decisionIDS[0];
                } else {
                    //flag
                    $this->_db->insert('article_screen_checklist_final_decision_t', array(
                        "StudyCode" => $studyCode,
                        "DecisionID" => 3
                    ));
                    $decision = 3;
                }
            }else{
                if (count(array_unique($decisionIDS)) == 1) {
                $this->_db->update('article_screen_checklist_final_decision_t', 'StudyCode', $studyCode, array(
                    "DecisionID" => $decisionIDS[0]
                ));
                $decision = $decisionIDS[0];
            } else {
                //flag
                $this->_db->update('article_screen_checklist_final_decision_t', 'StudyCode', $studyCode, array(
                    "DecisionID" => 3
                ));
                $decision = 3;
            }
            }
        } else {
            if (count(array_unique($decisionIDS)) == 1) {
                $this->_db->update('article_screen_checklist_final_decision_t', 'StudyCode', $studyCode, array(
                    "DecisionID" => $decisionIDS[0]
                ));
                $decision = $decisionIDS[0];
            } else {
                //flag
                $this->_db->update('article_screen_checklist_final_decision_t', 'StudyCode', $studyCode, array(
                    "DecisionID" => 3
                ));
                $decision = 3;
            }
            //  $this->_db->update('data_extraction_t', 'StudyCode', $data['StudyCode'], $data); 
        }
        return $decision;
    }

    public function getInfo($studyCode, $reviewerID) {
        $this->_db->query("SELECT * FROM article_screen_checklist_t WHERE "
                . "StudyCode = '$studyCode' AND ReviewerID = '$reviewerID'");
        foreach ($this->_db->getResults() as $checklist) {
            $publishedTimeFrame = $checklist->IsPublishedWithinTimeFrame;
            $isPopProvideDirectPatientContact = $checklist->IsPopProvideDirectPatientContact;
            $isPeerReviewed = $checklist->IsPeerReviewed;
            $isDescribedPlanEducationIntervention = $checklist->IsDescribedPlanEducationIntervention;
            $isCulturalCompetenceTopicOriginRelated = $checklist->IsCulturalCompetenceTopicOriginRelated;
            $decisionID = $checklist->DecisionID;
            $decisionComments = $checklist->DecisionComments;
        }

        $this->_db->select('article_screen_checklist_decision_t', array('DecisionID', '=', $decisionID));
        $decision = $this->_db->getFirstResult()->Decision;
        FormHelper::checkIfDummyValue($decisionComments);

        return array(
            "IsPublishedWithinTimeFrame" => $publishedTimeFrame,
            "IsPopProvideDirectPatientContact" => $isPopProvideDirectPatientContact,
            "IsPeerReviewed" => $isPeerReviewed,
            "IsDescribedPlanEducationIntervention" => $isDescribedPlanEducationIntervention,
            "IsCulturalCompetenceTopicOriginRelated" => $isCulturalCompetenceTopicOriginRelated,
            "Decision" => $decision,
            "DecisionComments" => $decisionComments
        );
    }

    public function getEditInfo($studyCode, $reviewerID) {
        $this->_db->query("SELECT * FROM article_screen_checklist_t WHERE "
                . "StudyCode = '$studyCode' AND ReviewerID = '$reviewerID'");

        foreach ($this->_db->getResults() as $checklist) {
            $publishedTimeFrame = $checklist->IsPublishedWithinTimeFrame;
            $isPopProvideDirectPatientContact = $checklist->IsPopProvideDirectPatientContact;
            $isPeerReviewed = $checklist->IsPeerReviewed;
            $isDescribedPlanEducationIntervention = $checklist->IsDescribedPlanEducationIntervention;
            $isCulturalCompetenceTopicOriginRelated = $checklist->IsCulturalCompetenceTopicOriginRelated;
            $decisionID = $checklist->DecisionID;
            $decisionComments = $checklist->DecisionComments;
        }

        FormHelper::checkIfDummyValue($decisionComments);

        return array(
            "IsPublishedWithinTimeFrame" => $publishedTimeFrame,
            "IsPopProvideDirectPatientContact" => $isPopProvideDirectPatientContact,
            "IsPeerReviewed" => $isPeerReviewed,
            "IsDescribedPlanEducationIntervention" => $isDescribedPlanEducationIntervention,
            "IsCulturalCompetenceTopicOriginRelated" => $isCulturalCompetenceTopicOriginRelated,
            "DecisionID" => $decisionID,
            "DecisionComments" => $decisionComments
        );
    }

    public function getReviewerAssignedStudyCodes($reviewerID) {
        $studyCodes = array();
        $this->_db->select('article_process_stage_t ', array('ReviewerID', '=', $reviewerID));

        if ($this->_db->getCount() == 0) {
            return $studyCodes;
        } else {
            foreach ($this->_db->getResults() as $code) {
                array_push($studyCodes, $code->StudyCode);
            }
            return $studyCodes;
        }
    }

    public function getReviewersAssignedToStudyCode($StudyCode) {
        $reviewerIDs = $this->getReviewerIDsAssignedToStudyCode($StudyCode);
        $reviewerNames = array();
        for ($i = 0; $i < count($reviewerIDs); $i++) {
            $this->_db->select('reviewer_t', array('ReviewerID', '=', $reviewerIDs[$i]));
            foreach ($this->_db->getResults() as $reviewerName) {
                array_push($reviewerNames, $reviewerName->FirstName . ' ' . $reviewerName->LastName);
            }
        }
        return $reviewerNames;
    }

    public function getReviewerIDsAssignedToStudyCode($StudyCode) {
        $reviewerIDs = array();
        $this->_db->select('article_process_stage_t', array('StudyCode', '=', $StudyCode));
        foreach ($this->_db->getResults() as $reviewerID) {
            array_push($reviewerIDs, $reviewerID->ReviewerID);
        }
        return $reviewerIDs;
    }

    public function getTotalReviewersAssignedToArticle($studyCode) {
        $this->_db->query("SELECT COUNT(*) AS Count FROM article_process_stage_t WHERE StudyCode = '$studyCode'");
        foreach ($this->_db->getResults() as $article) {
            $totalAssigned = $article->Count;
        }
        return $totalAssigned;
    }

    public function isFinalDecisionNeeded($studyCode) {
        //get total amount of assigned articles
        $this->_db->query("SELECT COUNT(*) AS Count FROM article_process_stage_t WHERE StudyCode = '$studyCode'");
        foreach ($this->_db->getResults() as $article) {
            $totalAssigned = $article->Count;
        }

        //compare total assigned to number currently submitted
        $checklist = $this->_db->select('article_screen_checklist_t', array('StudyCode', '=', $studyCode));
        if ($totalAssigned >= 2) {
            if ($checklist->getCount() >= 2) {
                return 1;
            } else {
                return 0;
            }
        } else {
            if ($checklist->getCount() == 1) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function update($arr = array()) {
        $inventory = new StudyInventory();
        $dummyValueFields = array("DecisionComments");
        $data = $inventory->fillEmptyFieldsWithDefault(self::DUMMY_FIELD, $arr, $dummyValueFields);
        $this->_db->updateMulitipleWhere("article_screen_checklist_t", "StudyCode", $data['StudyCode'], "ReviewerID", $data['ReviewerID'], $data);
    }

}
