<?php

require_once 'classes/utility/Database.php';
require_once 'classes/DataExtraction.php';

class QuestsAppraisal {

    const QUESTS_RUBRIC = "assets/files/";
    private $_db;

    public function __construct() {
        $this->_db = Database::getInstance();
    }

    public function calculateFinalScore($studyCode, $reviewerID) {
        $this->_db->query("SELECT QDQualityScore, QDUtilityScore, QDExtentScore, "
                . "QDStrengthScore, QDTargetScore, QDSettingScore FROM quest_appraise_t WHERE "
                . "StudyCode = '$studyCode' AND ReviewerID = '$reviewerID'");
        foreach ($this->_db->getResults() as $quest) {
            $qdQualityScore = $quest->QDQualityScore;
            $qdUtilityScore = $quest->QDUtilityScore;
            $qdExtentScore = $quest->QDExtentScore;
            $qdStrengthScore = $quest->QDStrengthScore;
            $qdTargetScore = $quest->QDTargetScore;
            $qdSettingScore = $quest->QDSettingScore;
        }

        $finalScore = $qdQualityScore + $qdUtilityScore + $qdExtentScore +
                $qdStrengthScore + $qdTargetScore + $qdSettingScore;
        return $finalScore;
    }

    public function create($arr = array()) {
        $insertSuccess = $this->_db->insert("quest_appraise_t", $arr);
        return $insertSuccess;
    }


    public function downloadRubric() {
        $file = self::QUESTS_RUBRIC . 'QuestsDimensionRubric.pdf';
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit();
        }
    }

    public function edit($arr = array()) {
        $insertSuccess = $this->_db->updateMulitipleWhere("quest_appraise_t", "StudyCode", $arr['StudyCode'], "ReviewerID", $arr['ReviewerID'], $arr);
        return $insertSuccess;
    }

    public function getInfo($studyCode, $reviewerID) {
        $this->_db->query("SELECT * FROM quest_appraise_t WHERE "
                . "StudyCode = '$studyCode' AND ReviewerID = '$reviewerID'");
        foreach ($this->_db->getResults() as $quest) {
            $isPRDescription = $quest->IsPRDescription;
            $isPRJustification = $quest->IsPRJustification;
            $isPRClarification = $quest->IsPRClarification;
            $qdQualityScore = $quest->QDQualityScore;
            $qdUtilityScore = $quest->QDUtilityScore;
            $qdExtentScore = $quest->QDExtentScore;
            $qdStrengthScore = $quest->QDStrengthScore;
            $qdTargetScore = $quest->QDTargetScore;
            $qdSettingScore = $quest->QDSettingScore;
            $prDescriptionComments = $quest->PRDescriptionComments;
            $prJustificationComments = $quest->PRJustificationComments;
            $prClarificationComments = $quest->PRClarificationComments;
            $qdQualityComments = $quest->QDQualityComments;
            $qdUtilityComments = $quest->QDUtilityComments;
            $qdExtentComments = $quest->QDExtentComments;
            $qdStrengthComments = $quest->QDStrengthComments;
            $qdTargetComments = $quest->QDTargetComments;
            $qdSettingComments = $quest->QDSettingComments;
        }

        $finalScore = $qdQualityScore + $qdUtilityScore + $qdExtentScore +
                $qdStrengthScore + $qdTargetScore + $qdSettingScore;
       
        return array(
            "IsPRDescription" => $isPRDescription,
            "IsPRJustification" => $isPRJustification,
            "IsPRClarification" => $isPRClarification,
            "QDQualityScore" => $qdQualityScore,
            "QDUtilityScore" => $qdUtilityScore,
            "QDExtentScore" => $qdExtentScore,
            "QDStrengthScore" => $qdStrengthScore,
            "QDTargetScore" => $qdTargetScore,
            "QDSettingScore" => $qdSettingScore,
            "PRDescriptionComments" => $prDescriptionComments,
            "PRJustificationComments" => $prJustificationComments,
            "PRClarificationComments" => $prClarificationComments,
            "QDQualityComments" => $qdQualityComments,
            "QDUtilityComments" => $qdUtilityComments,
            "QDExtentComments" => $qdExtentComments,
            "QDStrengthComments" => $qdStrengthComments,
            "QDTargetComments" => $qdTargetComments,
            "QDSettingComments" => $qdSettingComments,
            "FinalScore" => $finalScore
        );
    }
}
