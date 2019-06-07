<?php
require_once 'classes/pages/HomePage.php';
require_once 'classes/displayContent/ContactPageContent.php';

class ContactUsPage extends HomePage{
    public function __construct() {
        parent::__construct();
    }
    
    public function content() {
        echo '<div class="pageWrapper">';
        $this->displaySchoolHeader();
        $this->displayAppHeader();
        ContactPageContent::displayContactInfo();
        echo '</div>';
    }

    public function display($type = null, $code = null, $id = null) {
        $this->getDoctype();
        echo "<html>\n";
        echo "<head>\n";
        $this->getTitle();
        $this->getMetaTags();
        $this->includeScripts();
        echo "</head>\n";
        echo "<body>\n";        
        $this->content();
        ContactPageContent::displayFooter();
        echo "</body>\n";
        echo "</html>";
    }
}