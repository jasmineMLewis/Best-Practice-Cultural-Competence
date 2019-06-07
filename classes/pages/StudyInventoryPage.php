<?php

require_once 'classes/displayContent/ArticleList.php';
require_once 'classes/forms/CreationForms.php';
require_once 'classes/forms/EditForms.php';
require_once 'classes/utility/Database.php';
require_once 'classes/displayContent/ReviewerPhasesData.php';
require_once 'classes/utility/Input.php';
require_once 'classes/pages/Page.php';
require_once 'classes/Reviewer.php';
require_once 'classes/utility/Session.php';
require_once 'classes/StudyInventory.php';

class StudyInventoryPage extends Page {

    const ARTICLE_DIRECTORY = "articles/";
    const TEMP_ARTICLE_DIRECTORY = "tempArticles/";
    const UPLOAD_FAIL = "fileNoUpload";

    private $_db;

    public function __construct() {
        parent::__construct();
        $this->_db = Database::getInstance();
    }

    public function createContent() {
        CreationForms::displayStudyInventoryCreationForm();

        if (Input::exists('post')) {
            $studyInventory = new StudyInventory();
            $tempFileName = $studyInventory->insertArticleIntoFolder("temp", Input::get('file'), "temp");
            
            if ($tempFileName == self::UPLOAD_FAIL) {
                echo '<div class="alert alert-danger message" role="alert"><h2><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>Article FAIL to Uplaod</h2></div>';
            } else {
                $hash = md5_file(self::TEMP_ARTICLE_DIRECTORY . $tempFileName);                          
                //$isArticleDuplicate = $studyInventory->isArticleADuplicate($hash);

//                if ($isArticleDuplicate == true) {
//                    unlink(self::TEMP_ARTICLE_DIRECTORY . $tempFileName);
//                    echo '<div class="alert alert-warning message" role="alert"><h2><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>Article is a DUPLICATE</h2></div>';
//                }  else {
                    $studyCode = $studyInventory->createStudyCode(Input::get('code'));
                    $fileName = $studyCode . ".pdf";

                    //move article into articles directory from tempArticles directory
                    rename(self::TEMP_ARTICLE_DIRECTORY . $tempFileName, self::ARTICLE_DIRECTORY . $fileName);

                    $studyInventory->create(array(
                        "StudyCode" => $studyCode,
                        "ReviewerID" => Session::get('reviewerID'),
                        "SearchMethodID" => Input::get('searchMethod'),
                        "DocumentTypeID" => Input::get('documentType'),
                        "DatabaseID" => Input::get('database'),
                        "YearPublished" => Input::get('publishedYear'),
                        "YearStudyBegan" => Input::get('studyYearBegan'),
                        "YearStudyEnd" => Input::get('studyYearEnd'),
                        "IsAnArticleDisabled" => "0",
                        "IsCountryExists" => Input::get('country'),
                        "IsProfessionExists" => Input::get('profession'),
                        "IsInstitutionExists" => Input::get('institution'),
                        "AuthorsNames" => Input::get('authors'),
                        "Title" => Input::get('title'),
                        "ArticleUrl" => $fileName,
                        "Hash" => $hash,
                        "MonthPublished" => Input::get('publishedMonth'),
                        "OtherInstitution" => Input::get('otherInstitutions'),
                        "Url" => Input::get('url'),
                        "Doi" => Input::get('doi'),
                        "Pubmed" => Input::get('pubmed'),
                        "OriginalAbstract" => Input::get('originalAbstract'),
                        "RelevantPointOne" => Input::get('relPoint1'),
                        "RelevantPointTwo" => Input::get('relPoint2'),
                        "RelevantPointThree" => Input::get('relPoint3'),
                        "Citation" => Input::get('citation')
                    ));

                      $assignedArticles =  $studyInventory->assignReviewersToArticles($studyCode);

                    if ($assignedArticles == 1) {
                        echo '<div class="alert alert-success message" role="alert"><h2><span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span class="sr-only">Success:</span>Successful Study Inventory Submission</h2></div>';
                    } else {
                        echo '<div class="alert alert-danger message" role="alert"><h2><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>Error in Submission. Please Try Again</h2></div>';
                    }
               // }
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
                $this->displayHeader();
                $this->displayAppHeader();
                $this->createContent();
                break;
            case 'download':
                $studyInventory = new StudyInventory();
                $studyInventory->downloadArticle($studyCode);
                break;
            case 'edit':
                $this->displayHeader();
                $this->displayAppHeader();
                $this->displayEditContent($studyCode);
                break;
            case 'searchArticles':
                $this->displayHeader();
                $this->displayAppHeader();
                ArticleList::displaySearchArticles();
                break;
            case 'view':
                $this->displayHeader();
                $this->displayAppHeader();
                ReviewerPhasesData::displayStudyInventory($studyCode);
                break;
            case 'viewArticle':
                $studyInventory = new StudyInventory();
                $studyInventory->viewArticle($studyCode);
                break;
            case 'viewArticleList':
                $this->displayHeader();
                $this->displayAppHeader();
                ArticleList::displayArticles();
                break;
            default:
                break;
        }
        echo "</body>\n";
        echo "</html>";
    }

    public function displayEditContent($studyCode) {
        EditForms::displayStudyInventory($studyCode);

        if (Input::exists('post')) {
            $inventory = new StudyInventory();
            $inventory->edit(array(
                "StudyCode" => $studyCode,
                "ReviewerID" => Input::get('reviewerID'),
                "SearchMethodID" => Input::get('searchMethod'),
                "DocumentTypeID" => Input::get('documentType'),
                "DatabaseID" => Input::get('database'),
                "YearPublished" => Input::get('publishedYear'),
                "YearStudyBegan" => Input::get('studyYearBegan'),
                "YearStudyEnd" => Input::get('studyYearEnd'),
                "IsCountryExists" => Input::get('country'),
                "IsProfessionExists" => Input::get('profession'),
                "IsInstitutionExists" => Input::get('institution'),
                "AuthorsNames" => Input::get('authors'),
                "Title" => Input::get('title'),
                "ArticleUrl" => Input::get('ArticleUrl'),
                "MonthPublished" => Input::get('publishedMonth'),
                "OtherInstitution" => Input::get('otherInstitutions'),
                "Url" => Input::get('url'),
                "Doi" => Input::get('doi'),
                "Pubmed" => Input::get('pubmed'),
                "OriginalAbstract" => Input::get('originalAbstract'),
                "RelevantPointOne" => Input::get('relPoint1'),
                "RelevantPointTwo" => Input::get('relPoint2'),
                "RelevantPointThree" => Input::get('relPoint3'),
                "Citation" => Input::get('citation')
            ));
            die("<script> parent.window.location.href='admin.php?type=articleList';</script>");
        }
    }
}