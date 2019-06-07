<?php

require_once 'classes/pages/Page.php';
require_once 'classes/utility/Session.php';
require_once 'classes/Reviewer.php';

class ReviewerMenuPage extends Page {

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
        echo "</head>\n";
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
        echo '<h1>Reviewer Menu</h1>';

        echo '<div class="nav_container">';
        echo '<div class="row">';

        //Study Inventory
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="studyinventory.php?type=create" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-file" aria-hidden="true">'
        . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Create Study Inventory</div>';
        echo '</div>';
        
        //Articles
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="studyinventory.php?type=viewArticleList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-duplicate" aria-hidden="true">'
        . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Articles</div>';
        echo '</div>';
        
        //Article Screen Checklist
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="articlescreen.php?type=screenList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-list-alt" aria-hidden="true">'
        . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Article Screen Checklist</div>';
        echo '</div>';

        //Data Extraction
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="dataextraction.php?type=dataExtList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>';
        echo '</a>';
        echo '<div class="portal_link">Data Extraction</div>';
        echo '</div>';

        //QUERSTS Appraisal
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="questsappraisal.php?type=questsList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-stats" aria-hidden="true"></span>';
        echo '</a>';
        echo '<div class="portal_link">QUESTS Appraisal</div>';
        echo '</div>';

        //Kirkpatrick Rating
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="kirkpatrickrating.php?type=ratingList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-education" aria-hidden="true"></span>';
        echo '</a>';
        echo '<div class="portal_link">Kirkpatrick Rating</div>';
        echo '</div>';

          echo '<div id="manual"><a href="assets/files/ReviewerManual.pdf" target="_blank">REVIEWER MANUAL</a></div>';
        echo '</div>';
        echo '</div>';
        echo '<p class="gradient"></p>';
        echo '</div>';
        echo '</div>';
    }
}