<?php

require_once 'classes/Admin.php';
require_once 'classes/ChecklistScreen.php';
require_once 'classes/displayContent/AdminArticleLists.php';
require_once 'classes/displayContent/AdminArticlePhaseLists.php';
require_once 'classes/displayContent/AdminReviewerContent.php';
require_once 'classes/pages/Page.php';
require_once 'classes/utility/Session.php';

class AdminPage extends Page {

    public function __construct() {
        parent::__construct();
    }

    public function display($type, $reviewerID, $studyCode) {
        $this->getDoctype();
        echo "<html>\n";
        echo "<head>\n";
        $this->getTitle();
        $this->getMetaTags();
        $this->includeScripts();
        echo "</head>\n";
        $this->displayHeader();
        $this->displayAppHeader();
        echo "<body>\n";

        switch ($type) {
            case 'allArticles':
                AdminArticlePhaseLists::displayAllArticleScreenChecklists();
                break;
            case 'articledupcontrol':
                AdminArticleLists::displayDuplicateArticles();
                break;
            case 'articleList':
                AdminArticleLists::displayArticleList();
                break;
            case 'assignReviewerToArticle':
                $this->displayAssignReviewerContent($studyCode);
                break;
            case 'disableArticle':
                $this->disableArticle();
                break;
            case 'dataExtractionList':
                AdminArticlePhaseLists::displayDataExtractionList();
                break;
            case 'disableReviewer':
                $this->displayDisableReviewer();
                break;
            case 'excludeList':
                AdminArticlePhaseLists::displayExcludeArticleScreenChecklists();
                break;
            case 'flagList':
               AdminArticlePhaseLists::displayFlagArticleScreenChecklists();
                break;
            case 'includeList':
                AdminArticlePhaseLists::displayIncludeArticleScreenChecklists();
                break;
            case 'kirkpatrickRatingList':
                AdminArticlePhaseLists::displayKirkpatrickRatingList();
                break;
            case 'questsAppraisalList':
                AdminArticlePhaseLists::displayQuestsAppraisalList();
                break;
            case 'reassignReviewerToArticle':
                $this->displayReassignReviewerContent($studyCode, $reviewerID);
                break;
            case 'registerReviewer':
                $this->displayRegisterReviewerContent();
                break;
            case 'reviewerArticleList':
               AdminReviewerContent::displayReviewerArticleList($reviewerID);
                break;
            case 'reviewerTeamList':
                AdminReviewerContent::displayReviewerTeamList($reviewerID);
                break;
            case 'reviewerTeamTable':
                AdminReviewerContent::displayTeamTable();
                break;
            case 'undisableArticle':
                $this->undisableArticle($studyCode);
                break;
            case 'undisableReviewer':
                $this->displayUndisableReviewer();
                break;
            case 'updateReviewer':
                $this->displayUpdateReviewerContent($reviewerID);
                break;
            case 'userList':
                AdminReviewerContent::displayReviewerList();
                break;
            default:
                break;
        }
        echo "</body>\n";
        echo "</html>";
    }

    public function displayAssignReviewerContent($studyCode) {
        AdminReviewerContent::displayAssignReviewerForm($studyCode);
        
        if (Input::exists('post')) {
            $inventory = new StudyInventory();
            $inventory->assignArticleToReviewer(Input::get('StudyCode'), Input::get('reviewerToAssign'));
            header('Location: admin.php?type=articleList');
            exit();
        }
    }

    public function disableArticle() {
        if (!empty($_GET['StudyCode'])) {
            $studyCode = Input::get('StudyCode');
        }

        if (!empty($_GET['ReviewerID'])) {
            $reviewerID = Input::get('ReviewerID');
        }

        $disable = new Admin();
        $disable->disableArticle($studyCode, $reviewerID);

        header('Location: admin.php?type=articledupcontrol');
        exit();
    }

    public function displayDisableReviewer() {
        if (!empty($_GET['reviewerID'])) {
            $reviewerID = Input::get('reviewerID');
        }

        $disable = new Admin();
        $disable->disableReviewer($reviewerID);

        header('Location: admin.php?type=userList');
        exit();
    }

    public function displayReassignReviewerContent($studyCode, $oldReviewer) {
        AdminReviewerContent::displayAssignReviewerForm($studyCode);

        if (Input::exists('post')) {
            $assignReviewer = new Admin();
            $assignReviewer->assignArticleNewReviewer(Input::get('StudyCode'), $oldReviewer, Input::get('reviewerToAssign'));
            header('Location: admin.php?type=articleList');
            exit();
        }
    }

    public function displayRegisterReviewerContent() {
           AdminReviewerContent::displayRegisterReviewerForm();

        if (Input::exists('post')) {
            $register = new Admin();
            $register->registerReviewer(Input::get('Team'), Input::get('TeamAdmin'), array(
                "FirstName" => Input::get('FirstName'),
                "LastName" => Input::get('LastName'),
                "Email" => Input::get('Email'),
                "Password" => 'Xavier01',
                "IsAdmin" => '0',
                "IsDisabled" => '0'));
            if ($register) {
                echo '<div class="message"><h2><b>SUCCESSFUL</b> Reviewer Registeration</h2></div>';
            } else {
                echo '<div class="message"><h2><b>FAILED</b> Reviewer Registeration</h2></div>';
            }
        }
    }

    public function undisableArticle($studyCode) {
        if (!empty($_GET['StudyCode'])) {
            $studyCode = Input::get('StudyCode');
        }

        if (!empty($_GET['ReviewerID'])) {
            $reviewerID = Input::get('ReviewerID');
        }

        $undisable = new Admin();
        $undisable->undisableArticle($studyCode, $reviewerID);

        header('Location: admin.php?type=articledupcontrol');
        exit();
    }

    public function displayUndisableReviewer() {
        if (!empty($_GET['reviewerID'])) {
            $reviewerID = Input::get('reviewerID');
        }

        $disable = new Admin();
        $disable->undisableReviewer($reviewerID);

        header('Location: admin.php?type=userList');
        exit();
    }

    public function displayUpdateReviewerContent($reviewerID) {
        AdminReviewerContent::displayUpdateReviewerForm($reviewerID);
        
        if (Input::exists('post')) {
            $update = new Admin();
            $update->updateReviewer($reviewerID, Input::get('Team'), Input::get('Admin'), array(
                "FirstName" => Input::get('FirstName'),
                "LastName" => Input::get('LastName'),
                "Email" => Input::get('Email'),
                "IsAdmin" => '0',
                "IsDisabled" => Input::get('Disable')
            ));

            die("<script> parent.window.location.href='admin.php?type=userList';</script>");            
        }
    }
}
