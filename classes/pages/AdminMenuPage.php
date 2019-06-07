<?php

require_once 'classes/pages/Page.php';
require_once 'classes/utility/Session.php';
require_once 'classes/Admin.php';

class AdminMenuPage extends Page{
     public function __construct() {
        parent::__construct();
    }
    
    public function display($type = null, $studyCode = null, $id = null) {
        $this->getDoctype();
        echo "<html>\n";
        echo "<head>\n";
        $this->getTitle();
        $this->getMetaTags();
        $this->includeScripts();
        echo "<body>\n";
         $this->displayHeader();
        $this->displayAppHeader();        
        $this->displayMenuNav();
        echo "</body>\n";
        echo "</html>";
    }

    public function displayMenuNav() {
        echo '<div id="wrapper">';
        echo '<div id="menu_nav">';
        echo '<h1>Admin Menu</h1>';
        echo '<div class="nav_container">';
        echo '<div class="row">';
        
        //Users
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="admin.php?type=userList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-user" aria-hidden="true">'
            . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Reviewers</div>';
        echo '</div>';
        
        //Articles
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="admin.php?type=articleList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-duplicate" aria-hidden="true">'
            . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Articles</div>';
        echo '</div>';
        
        //Article Screen Checklist
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="adminarticlemenu.php" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-list-alt" aria-hidden="true">'
            . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Article Screen Checklist</div>';
        echo '</div>';

        //Data Extraction
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="admin.php?type=dataExtractionList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-dashboard" aria-hidden="true">'
            . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Data Extraction</div>';
        echo '</div>';

        //QUERSTS Appraisal
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="admin.php?type=questsAppraisalList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-stats" aria-hidden="true">'
            . '</span>';
        echo '</a>';
        echo '<div class="portal_link">QUESTS Appraisal</div>';
        echo '</div>';

        //Kirkpatrick Rating
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="admin.php?type=kirkpatrickRatingList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-education" aria-hidden="true">'
            . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Kirkpatrick Rating</div>';
        echo '</div>';
        
        echo '<div id="manual"><a href="assets/files/AdminManual.pdf" target="_blank">ADMIN MANUAL</a></div>';
        echo '</div>';
        echo '</div>';
        echo '<p class="gradient"></p>';
        echo '</div>';
        echo '</div>'; 
    }
}
