<?php

require_once 'classes/utility/Database.php';
require_once 'classes/utility/Session.php';
require_once 'classes/utility/Input.php';
require_once 'classes/pages/Page.php';
require_once 'classes/Reviewer.php';
require_once 'classes/KirkpatrickRating.php';
require_once 'classes/forms/CreationForms.php';
require_once 'classes/displayContent/ReviewerPhaseLists.php';
require_once 'classes/displayContent/ReviewerPhasesData.php';

class KirkpatrickRatingPage extends Page {

    private $_db;

    public function __construct() {
        parent::__construct();
        $this->_db = Database::getInstance();
    }

    public function displayCreateContent($studyCode) {
        CreationForms::displayKirkPatrickCreationForm();

        if (Input::exists('post')) {
            $kirk = new KirkpatrickRating();
            $creationSuccess = $kirk->create(array(
                "StudyCode" => $studyCode,
                "ReviewerID" => Session::get('reviewerID'),
                "IsLevelOne" => Input::get('isLevelOne'),
                "IsLevelTwoA" => Input::get('isLevelTwoA'),
                "IsLevelTwoB" => Input::get('isLevelTwoB'),
                "IsLevelThreeA" => Input::get('isLevelThreeA'),
                "IsLevelThreeB" => Input::get('isLevelThreeB'),
                "IsLevelFourA" => Input::get('isLevelFourA'),
                "IsLevelFourB" => Input::get('isLevelFourB'),
                "LevelOneAComments" => Input::get('levelOneAComment'),
                "LevelTwoAComments" => Input::get('levelTwoAComment'),
                "LevelTwoBComments" => Input::get('levelTwoBComment'),
                "LevelThreeAComments" => Input::get('levelThreeAComment'),
                "LevelThreeBComments" => Input::get('levelThreeBComment'),
                "LevelFourAComments" => Input::get('levelFourAComment'),
                "LevelFourBComments" => Input::get('levelFourBComment')
            ));

            if (!$creationSuccess) {
                $updatePhase = new StudyInventory();
                $updatePhase->updateStageForReviewer($studyCode, Session::get('reviewerID'), 5);
                die("<script> parent.window.location.href='kirkpatrickrating.php?type=ratingList';</script>");
            } else {
                echo '<h1>Error in Kirkpatrick Rating Submission</h1>';
            }
        }
    }

    public function display($type, $studyCode, $reviewerID) {
        $this->getDoctype();
        echo "<html>\n";
        echo "<head>\n";
        $this->getTitle();
        $this->getMetaTags();
        $this->includeScripts();
        echo "</head>\n";
        echo "<body>\n";

        switch ($type) {
            case 'create':
                $this->displayCreateContent($studyCode);
                break;
            case 'downloadRubric':
                $rubric = new KirkpatrickRating();
                $rubric->downloadRubric();
                break;
            case 'edit':
                $this->displayEditContent($studyCode, $reviewerID);
                break;
            case 'ratingList':
                $this->displayHeader();
                $this->displayAppHeader();
                //$rating = new KirkpatrickRating();
                //$rating->displayKirkpatrickList($reviewerID);
                ReviewerPhaseLists::displayKirkpatrickList($reviewerID);
                break;
            case 'view':
                $this->displayHeader();
                $this->displayAppHeader();
                ReviewerPhasesData::displayKirkpatrickRating($studyCode, $reviewerID);
                break;
            default:
                break;
        }
        echo "</body>\n";
        echo "</html>";
    }
    
    public function displayEditContent($studyCode, $reviewerID) {
        EditForms::displayKirkpatrickRating($studyCode, $reviewerID);

        if (Input::exists('post')) {
            $kirk = new KirkpatrickRating();
            $editSuccess = $kirk->edit($studyCode, $reviewerID, array(
                "StudyCode" => $studyCode,
                "ReviewerID" => $reviewerID,
                "IsLevelOne" => Input::get('isLevelOne'),
                "IsLevelTwoA" => Input::get('isLevelTwoA'),
                "IsLevelTwoB" => Input::get('isLevelTwoB'),
                "IsLevelThreeA" => Input::get('isLevelThreeA'),
                "IsLevelThreeB" => Input::get('isLevelThreeB'),
                "IsLevelFourA" => Input::get('isLevelFourA'),
                "IsLevelFourB" => Input::get('isLevelFourB'),
                "LevelOneAComments" => Input::get('levelOneAComment'),
                "LevelTwoAComments" => Input::get('levelTwoAComment'),
                "LevelTwoBComments" => Input::get('levelTwoBComment'),
                "LevelThreeAComments" => Input::get('levelThreeAComment'),
                "LevelThreeBComments" => Input::get('levelThreeBComment'),
                "LevelFourAComments" => Input::get('levelFourAComment'),
                "LevelFourBComments" => Input::get('levelFourBComment')
            ));

            if (!$editSuccess) {
                die("<script> parent.window.location.href='kirkpatrickrating.php?type=ratingList';</script>");
            } else {
                echo '<h1>Error in Kirkpatrick Rating Submission</h1>';
            }
        }
    }

}
