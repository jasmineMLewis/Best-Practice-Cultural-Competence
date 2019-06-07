<?php

require_once 'classes/utility/Database.php';

class KirkpatrickRating {

    const RATING_RUBRIC = "assets/files/";

    private $_db;

    public function __construct() {
        $this->_db = Database::getInstance();
    }

    public function create($arr = array()) {
        return $this->_db->insert("kirkpatrick_rating_t", $arr);
    }

    public function downloadRubric() {
        $file = self::RATING_RUBRIC . 'KirkpatrickRatingRubric.pdf';
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

    public function edit($studyCode, $reviewerID, $arr = array()) {
        return $this->_db->updateMulitipleWhere("kirkpatrick_rating_t", "StudyCode", $studyCode, "ReviewerID", $reviewerID, $arr);
    }

    public function getHighestKirkpatrickGrade($studyCode, $reviewerID) {
        $kirkpatrick = new KirkpatrickRating();
        $list = $kirkpatrick->getInfo($studyCode, $reviewerID);
        $DATABASE_FIELDS = array('IsLevelOne', 'IsLevelTwoA', 'IsLevelTwoB',
            'IsLevelThreeA', 'IsLevelThreeB', 'IsLevelFourA', 'IsLevelFourB');
        $FIELD_GRADE = array('Level 1', 'Level 2a', 'Level 2b', 'Level 3a', 'Level 3b', 'Level 4a', 'Level 4b');

        for ($i = count($DATABASE_FIELDS) - 1; $i > -1; $i--) {
            if ($list[$DATABASE_FIELDS[$i]] == 'Yes') {
                return $FIELD_GRADE[$i];
            }
        }
    }

    public function getInfo($studyCode, $reviewerID) {
        $this->_db->query("SELECT * FROM kirkpatrick_rating_t WHERE "
                . "StudyCode = '$studyCode' AND ReviewerID = '$reviewerID'");
        foreach ($this->_db->getResults() as $rating) {
            $isLevelOne = $rating->IsLevelOne;
            $isLevelTwoA = $rating->IsLevelTwoA;
            $isLevelTwoB = $rating->IsLevelTwoB;
            $isLevelThreeA = $rating->IsLevelThreeA;
            $isLevelThreeB = $rating->IsLevelThreeB;
            $isLevelFourA = $rating->IsLevelFourA;
            $isLevelFourB = $rating->IsLevelFourB;
            $levelOneAComments = $rating->LevelOneAComments;
            $levelTwoAComments = $rating->LevelTwoAComments;
            $levelTwoBComments = $rating->LevelTwoBComments;
            $levelThreeAComments = $rating->LevelThreeAComments;
            $levelThreeBComments = $rating->LevelThreeBComments;
            $levelFourAComments = $rating->LevelFourAComments;
            $levelFourBComments = $rating->LevelFourBComments;
        }

        return array(
            "IsLevelOne" => $isLevelOne,
            "IsLevelTwoA" => $isLevelTwoA,
            "IsLevelTwoB" => $isLevelTwoB,
            "IsLevelThreeA" => $isLevelThreeA,
            "IsLevelThreeB" => $isLevelThreeB,
            "IsLevelFourA" => $isLevelFourA,
            "IsLevelFourB" => $isLevelFourB,
            "LevelOneAComments" => $levelOneAComments,
            "LevelTwoAComments" => $levelTwoAComments,
            "LevelTwoBComments" => $levelTwoBComments,
            "LevelThreeAComments" => $levelThreeAComments,
            "LevelThreeBComments" => $levelThreeBComments,
            "LevelFourAComments" => $levelFourAComments,
            "LevelFourBComments" => $levelFourBComments
        );
    }

}
