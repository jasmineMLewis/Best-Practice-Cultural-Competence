<?php

require_once 'classes/ChecklistScreen.php';
require_once 'classes/forms/CreationForms.php';
require_once 'classes/utility/Database.php';
require_once 'classes/displayContent/ReviewerPhasesData.php';
require_once 'classes/displayContent/ReviewerPhaseLists.php';
require_once 'classes/forms/EditForms.php';
require_once 'classes/utility/Input.php';
require_once 'classes/pages/Page.php';
require_once 'classes/Reviewer.php';
require_once 'classes/utility/Session.php';
require_once 'classes/StudyInventory.php';

class ArticleScreenPage extends Page {

    public function __construct() {
        parent::__construct();
    }

    public function displayCreateContent($studyCode) {
        CreationForms::displayArticleChecklistCreationForm();

        if (Input::exists('post')) {
            $checklist = new ChecklistScreen();
            $checklist->create(array(
                "StudyCode" => $studyCode,
                "ReviewerID" => Session::get('reviewerID'),
                "IsPublishedWithinTimeFrame" => Input::get('isArticlePublished'),
                "IsPopProvideDirectPatientContact" => Input::get('isPopProvideDirectPatientContact'),
                "IsPeerReviewed" => Input::get('isPeerReviewed'),
                "IsDescribedPlanEducationIntervention" => Input::get('isPlannedEduInterv'),
                "IsCulturalCompetenceTopicOriginRelated" => Input::get('isRelatedToRace'),
                "DecisionID" => Input::get('decision'),
                "DecisionComments" => Input::get('commentDecision')
            ));

            $isFinalDecsion = $checklist->isFinalDecisionNeeded($studyCode);
         //   echo "IS Final Decision is " . $isFinalDecsion . "<br/>";
            if ($isFinalDecsion == 1) {
                $decision = $checklist->determineFinalDecision($studyCode);
                
           //     echo "Final Decision is " . $decision . "<br/>";
                if ($decision == 1) {
                    $inventory = new StudyInventory();
                    $inventory->updateStageForArticle($studyCode, 2);
                }
            }
            die("<script> parent.window.location.href='articlescreen.php?type=screenList';</script>");
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
            case 'edit':
                $this->displayEditContent($studyCode, $reviewerID);
                break;
            case 'screenList':
                $this->displayHeader();
                $this->displayAppHeader();
                ReviewerPhaseLists::displayArticleScreenChecklist(Session::get('reviewerID'));
                break;
            case 'view':
                $this->displayHeader();
                $this->displayAppHeader();
                ReviewerPhasesData::displayScreenChecklist($studyCode, $reviewerID);
                break;
            default:
                break;
        }
        echo "</body>\n";
        echo "</html>";
    }

    public function displayEditContent($studyCode, $reviewerID) {
        EditForms::displayScreenChecklist($studyCode, $reviewerID);
      
        if (Input::exists('post')) {
            $checklist = new ChecklistScreen();
            $checklist->update(array(
                "StudyCode" => $studyCode,
                "ReviewerID" => $reviewerID,
                "IsPublishedWithinTimeFrame" => Input::get('IsPublishedWithinTimeFrame'),
                "IsPopProvideDirectPatientContact" => Input::get('IsPopProvideDirectPatientContact'),
                "IsPeerReviewed" => Input::get('IsPeerReviewed'),
                "IsDescribedPlanEducationIntervention" => Input::get('IsDescribedPlanEducationIntervention'),
                "IsCulturalCompetenceTopicOriginRelated" => Input::get('IsCulturalCompetenceTopicOriginRelated'),
                "DecisionID" => Input::get('Decision'),
                "DecisionComments" => Input::get('commentDecision')
            ));

            $isFinalDecsion = $checklist->isFinalDecisionNeeded($studyCode);
            if ($isFinalDecsion) {
                $decision = $checklist->determineFinalDecision($studyCode);
                if ($decision == 1) {
                    $inventory = new StudyInventory();
                    $inventory->updateStageForArticle($studyCode, 2);
                }
            }
            die("<script> parent.window.location.href='articlescreen.php?type=screenList';</script>");
        }
    }
}