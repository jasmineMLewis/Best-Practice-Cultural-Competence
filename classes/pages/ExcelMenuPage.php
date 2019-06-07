<?php
require_once 'classes/pages/Page.php';

class ExcelMenuPage extends Page {

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
        echo '<h1>Export Table Menu</h1>';
        echo '<div class="nav_container">';
        echo '<div class="row">';

        //Study Inventory
        echo '<div class="col-xs-4 col-md-2">';
        echo '<a href="excel.php?type=studyInventory" class="thumbnail"><span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span></a>';
        echo '<div class="portal_link">Study Inventory</div>';
        echo '</div>';

        //Article Screen Checklist
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="excel.php?type=articleScreenChecklist" class="thumbnail"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>';
        echo '<div class="portal_link">Article Screen Checklist</div>';
        echo '</div>';

        //Data Extraction
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="excel.php?type=dataExtraction" class="thumbnail"><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span></a>';
        echo '<div class="portal_link">Data Extraction</div>';
        echo '</div>';

        //QUERSTS Appraisal
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="excel.php?type=questsAppraisal" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-stats" aria-hidden="true">'
        . '</span>';
        echo '</a>';
        echo '<div class="portal_link">QUESTS Appraisal</div>';
        echo '</div>';

        //Kirkpatrick Rating
        echo '<div class="col-xs-6 col-md-2">';
        echo '<a href="excel.php?type=kirkpatrickRating" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-education" aria-hidden="true">'
        . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Kirkpatrick Rating</div>';
        echo '</div>';
//
        echo '</div>';
        echo '</div>';
        echo '<p class="gradient"></p>';
        echo '</div>';
        echo '</div>';
    }
}