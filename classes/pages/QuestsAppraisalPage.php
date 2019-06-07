<?php

require_once 'classes/utility/Database.php';
require_once 'classes/forms/CreationForms.php';
require_once 'classes/utility/Session.php';
require_once 'classes/utility/Input.php';
require_once 'classes/pages/Page.php';
require_once 'classes/Reviewer.php';
require_once 'classes/displayContent/ReviewerPhaseLists.php';
require_once 'classes/displayContent/ReviewerPhasesData.php';
require_once 'classes/QuestsAppraisal.php';


class QuestsAppraisalPage extends Page {

    private $_db;

    public function __construct() {
        parent::__construct();
        $this->_db = Database::getInstance();
    }

    public function displayCreateContent($studyCode) {
        CreationForms::displayQuestsAppraisalCreationForm();
                       
        if (Input::exists('post')) {
            $create = new QuestsAppraisal();
            $creationSuccess = $create->create(array(
                "StudyCode" => $studyCode,
                "ReviewerID" => Session::get('reviewerID'),
                "IsPRDescription" => Input::get('isDescription'),
                "ISPRJustification" => Input::get('isJustification'),
                "IsPRClarification" => Input::get('isClarification'),
                "QDQualityScore" => Input::get('qualityScore'),
                "QDUtilityScore" => Input::get('utilityScore'),
                "QDExtentScore" => Input::get('extentScore'),
                "QDStrengthScore" => Input::get('strengthScore'),
                "QDTargetScore" => Input::get('targetScore'),
                "QDSettingScore" => Input::get('settingScore'),
                "PRDescriptionComments" => Input::get('descriptionComment'),
                "PRJustificationComments" => Input::get('justificationComment'),
                "PRClarificationComments" => Input::get('clarificationComment'),
                "QDQualityComments" => Input::get('qualityComment'),
                "QDUtilityComments" => Input::get('utilityComment'),
                "QDExtentComments" => Input::get('extentComment'),
                "QDStrengthComments" => Input::get('strengthComment'),
                "QDTargetComments" => Input::get('targetComment'),
                "QDSettingComments" => Input::get('settingComment')
            ));

            if (!$creationSuccess) {
                $updatePhase = new StudyInventory();
                $updatePhase->updateStageForReviewer($studyCode, Session::get('reviewerID'), 4);
                die("<script> parent.window.location.href='questsappraisal.php?type=questsList';</script>");
            } else {
                echo '<h1>Error in QUESTS Appraise Submission</h1>';
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
                $rubric = new QuestsAppraisal();
                $rubric->downloadRubric();
                break;
            case 'edit':
                $this->displayEditContent($studyCode, $reviewerID);
                break;
            case 'questsList':
                $this->displayHeader();
                $this->displayAppHeader();
                ReviewerPhaseLists::displayQuestsList($reviewerID);
                break;
            case 'view':
                $this->displayHeader();
                $this->displayAppHeader();
                ReviewerPhasesData::displayQuestsAppraisal($studyCode, $reviewerID);
                break;
            default:
                break;
        }
        echo "</body>\n";
        echo "</html>";
    }

    public function displayEditContent($studyCode, $reviewerID) {
        EditForms::displayQuestsAppraisal($studyCode, $reviewerID);

        if (Input::exists('post')) {
            $edit = new QuestsAppraisal();
            $editSuccess = $edit->edit(array(
                "StudyCode" => $studyCode,
                "ReviewerID" => $reviewerID,
                "IsPRDescription" => Input::get('isDescription'),
                "ISPRJustification" => Input::get('isJustification'),
                "IsPRClarification" => Input::get('isClarification'),
                "QDQualityScore" => Input::get('qualityScore'),
                "QDUtilityScore" => Input::get('utilityScore'),
                "QDExtentScore" => Input::get('extentScore'),
                "QDStrengthScore" => Input::get('strengthScore'),
                "QDTargetScore" => Input::get('targetScore'),
                "QDSettingScore" => Input::get('settingScore'),
                "PRDescriptionComments" => Input::get('descriptionComment'),
                "PRJustificationComments" => Input::get('justificationComment'),
                "PRClarificationComments" => Input::get('clarificationComment'),
                "QDQualityComments" => Input::get('qualityComment'),
                "QDUtilityComments" => Input::get('utilityComment'),
                "QDExtentComments" => Input::get('extentComment'),
                "QDStrengthComments" => Input::get('strengthComment'),
                "QDTargetComments" => Input::get('targetComment'),
                "QDSettingComments" => Input::get('settingComment')
            ));

            if (!$editSuccess) {
                die("<script> parent.window.location.href='questsappraisal.php?type=questsList';</script>");
            } else {
                echo '<h1>Error in QUESTS Appraise Submission</h1>';
            }
        }
    }

}
