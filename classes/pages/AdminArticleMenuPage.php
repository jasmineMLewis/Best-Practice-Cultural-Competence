<?php

require_once 'classes/pages/Page.php';
require_once 'classes/utility/Session.php';
require_once 'classes/Admin.php';

class AdminArticleMenuPage extends Page {
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
        echo '<div id="wrapper_article_menu">';
        echo '<div id="article_menu_nav">';
        echo '<h1>Admin Article Menu</h1>';
        echo '<div class="nav_container">';
        echo '<div class="row">';
        
        //All Articles
        echo '<div class="col-xs-6 col-md-3">';
        echo '<a href="admin.php?type=allArticles" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-folder-open" aria-hidden="true">'
            . '</span>';
        echo '</a>';
        echo '<div class="portal_link">All</div>';
        echo '</div>';     
        
        //Include Articles
        echo '<div class="col-xs-6 col-md-3">';
        echo '<a href="admin.php?type=includeList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-inbox" aria-hidden="true">'
            . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Include</div>';
        echo '</div>';
        
        //Exclude Articles
        echo '<div class="col-xs-6 col-md-3">';
        echo '<a href="admin.php?type=excludeList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-trash" aria-hidden="true">'
            . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Exclude</div>';
        echo '</div>';
        
        //Flag Articles
        echo '<div class="col-xs-6 col-md-3">';
        echo '<a href="admin.php?type=flagList" class="thumbnail">';
        echo '<span class="glyphicon glyphicon-flag" aria-hidden="true">'
            . '</span>';
        echo '</a>';
        echo '<div class="portal_link">Flag</div>';
        echo '</div>';
        
        echo '</div>';
        echo '</div>';
        echo '<p class="gradient"></p>';
        echo '</div>';
        echo '</div>'; 
    }
}
