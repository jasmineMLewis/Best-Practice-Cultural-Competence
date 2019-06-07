<?php
session_start();

//Primary Classes
require_once 'classes/Admin.php';
require_once 'classes/ChecklistScreen.php';
require_once 'classes/DataExtraction.php';
require_once 'classes/Excel.php';
require_once 'classes/KirkpatrickRating.php';
require_once 'classes/StudyInventory.php';
require_once 'classes/Reviewer.php';
require_once 'classes/QuestsAppraisal.php';

//Pages Classes
require_once 'classes/pages/ArticleScreenPage.php';
require_once 'classes/pages/ContactUsPage.php';
require_once 'classes/pages/DataExtractionPage.php';
require_once 'classes/pages/ExcelMenuPage.php';
require_once 'classes/pages/HomePage.php';
require_once 'classes/pages/KirkpatrickRatingPage.php';
require_once 'classes/pages/Page.php';
require_once 'classes/pages/QuestsAppraisalPage.php';
require_once 'classes/pages/ReviewerMenuPage.php';
require_once 'classes/pages/StudyInventoryPage.php';

//Utility Classes
require_once 'classes/utility/Database.php';
require_once 'classes/utility/Input.php';
require_once 'classes/utility/Session.php';

//Form Classes
require_once 'classes/forms/CreationForms.php';
require_once 'classes/forms/FormHelper.php';
require_once 'classes/forms/EditForms.php';

//Display
require_once 'classes/displayContent/AdminArticleLists.php';
require_once 'classes/displayContent/AdminArticlePhaseLists.php';
require_once 'classes/displayContent/AdminReviewerContent.php';
require_once 'classes/displayContent/ArticleList.php';
require_once 'classes/displayContent/ContactPageContent.php';
require_once 'classes/displayContent/ReviewerPhaseLists.php';
require_once 'classes/displayContent/ReviewerPhasesData.php';


//Admin
require_once 'classes/pages/AdminArticleMenuPage.php';
require_once 'classes/pages/AdminMenuPage.php';
require_once 'classes/pages/AdminPage.php';

if(Session::exists('reviewerID')){
    Session::get('reviewerID');
}