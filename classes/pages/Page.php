<?php

require_once 'classes/utility/Session.php';
require_once 'classes/Reviewer.php';

class Page {

    //Site Info
    const AUTHOR = "IglooNation Tech, LLC";
    const SITE_NAME = "Best Practices";
    const DOCTYPE = "html strict";
    const APP_NAME = "Best Practices Cultural Competence";
    //Meta Tags
    const CHARSET = "utf-8";
    const HTTP_EQUIV = "X-UA-Compatible";
    const HTTP_EQUIV_CONTENT = "IE=edge";
    const VIEWPORT_NAME = "viewport";
    const VIEWPORT_CONTENT = "width=device-width, initial-scale=1";

    public $_appName = "";
    public $_author = "";
    private $_charset = "";
    private $_doctype = "";
    private $_http_equiv = "";
    private $_http_equiv_content = "";
    private $_include = array();
    private $_title = "";
    private $_viewport = "";
    private $_viewport_content = "";

    public function __construct() {
        $this->_author = self::AUTHOR;
        $this->_appName = self::APP_NAME;
        $this->_title = self::SITE_NAME;
        
        list($type, $standard) = explode(" ", self::DOCTYPE);
        $this->doctype($type, $standard);

        $this->_charset = self::CHARSET;
        $this->_http_equiv = self::HTTP_EQUIV;
        $this->_http_equiv_content = self::HTTP_EQUIV_CONTENT;
        $this->_viewport = self::VIEWPORT_NAME;
        $this->_viewport_content = self::VIEWPORT_CONTENT;
    }

    /**
     * Append the list of scripts (css and js) so they are alined neatly on
     * individual lines
     * Used only in methods includeScripts
     * @param type $sorted ???
     * @return string the list of scripts with an endline
     */
    public function combineScripts($sorted) {
        if (empty($sorted)) {
            return "";
        }

        $scripts = array();

        if (isset($sorted['css'])) {
            foreach ($sorted['css'] as $script) {
                $scripts[] = '<link rel="stylesheet" type="text/css" href="css/'
                        . $script . '.css" />';
            }
        }
        if (isset($sorted['js'])) {
            foreach ($sorted['js'] as $script) {
                $scripts[] = '<script type="text/javascript" src="js/'
                        . $script . '.js"></script>';
            }
        }

        if (isset($sorted['other'])) {
            $scripts = array_merge($scripts, $sorted['other']);
        }
        return '  ' . implode("\n  ", $scripts) . "\n";
    }

    public function display($type, $code, $id) {
        $this->getDoctype() . "\n";
        echo "<html>\n";
        echo "<head>\n";
        $this->getTitle();
        $this->getMetaTags();
        $this->includeScripts();
        echo "</head>\n";
        echo "<body>\n";
        echo "</body>\n";
        echo "</html>";
    }

    public function displayAppHeader() {
        echo '<div id="app_header">';
        $this->displayAppName();
        echo '</div>';
    }

    public function displayAppName() {
        echo '<div class="app_name"><h2>' . $this->_appName . '</h2></div>';
    }

    public function displayHeader() {
        $admin = new Admin();
        echo '<nav class="navbar navbar-default navbar-fixed-top">';
        echo '<div class="container-fluid">';
        echo '<div class="navbar-header">';
        echo '<p class="navbar-brand">' . $this->_appName . str_repeat('&nbsp;', 4);
        echo 'Welcome, ' .
        $admin->getData('FirstName', Session::get('reviewerID')) . ' ' .
        $admin->getData('LastName', Session::get('reviewerID'));
        
        echo str_repeat('&nbsp;', 47) . ' <a href="reviewermenu.php"><span class="glyphicon glyphicon-home">'
        . '</span> Reviewer Menu</a> ';

        if ($admin->getData('IsAdmin', Session::get('reviewerID')) == 1) {
            echo str_repeat('&nbsp;', 2) . ' <a href="adminmenu.php"><span class="glyphicon glyphicon-lock">'
            . '</span> Admin Menu</a> ';
        }

        echo str_repeat('&nbsp;', 90) . ' <a href="logout.php"><span class="glyphicon glyphicon-off">'
        . '</span> Logout</a>';

        echo '</p>';
        echo '</div>';
        echo '</div>';
        echo '</nav>';
    }

    public function doctype($type = "html", $standard = "strict") {
        if (in_array($standard, array("strict"))) {
            if ($type == "html") {
                switch ($standard) {
                    case "strict":
                        $this->_doctype = "<!DOCTYPE HTML PUBLIC "
                                . "'-//W3C//DTD HTML 4.01//EN' "
                                . "'http://www.w3.org/TR/html4/strict.dtd'>";
                        break;
                }
            }
        }
    }

    public function getAppName() {
        return $this->_appName;
    }

    public function getAuthor() {
        echo "<meta name='author' content='" . $this->_author . "'/>" . "\n";
    }

    public function getCharsetTag() {
        echo "<meta charset='" . $this->_charset . "'/>" . "\n";
    }

    public function getDoctype() {
        echo $this->_doctype;
    }

    public function getHttpEquivTag() {
        echo "<meta http-equiv='" . $this->_http_equiv .
        "' content='" . $this->_http_equiv_content . "'/>" . "\n";
    }

    public function getMetaTags() {
        $this->getCharsetTag();
        $this->getHttpEquivTag();
        $this->getViewportTags();
        $this->getAuthor();
    }

    public function getTitle() {
        echo "<title>" . $this->_title . "</title>";
    }

    public function getViewportTags() {
        echo "<meta name='" . $this->_viewport .
        "' content='" . $this->_viewport_content . "'/>" . "\n";
    }

    /**
     * Firts sorts the scripts (csss and js), then combines the sripts into one
     * array
     * @return type list of scripts needed for app
     */
    public function includeScripts() {
        echo $this->combineScripts($this->sortScripts($this->_include));
    }

    /**
     * Create a list of include links for scripts (css and js). 
     * Used in method includeScripts
     * @link array $link List of links to include in <head> tags
     * @param boolean $prepend Merge array and set to $_include data member 
     */
    public function link($link = array(), $prepend = false) {
        if ($prepend) {
            $this->_include = array_merge($link, $this->_include);
        } else {
            foreach ($link as $value) {
                $this->_include[] = $value;
            }
        }
    }

    /**
     * Sorts scripts based on type and returns it in order: 1st) css 2) js
     * Used in method includeScripts() 
     * @arr array list of scripts
     * @return array list of scripts in sorted order
     */
    protected function sortScripts($arr = array()) {
        $array = array_unique($arr);
        $scripts = array();

        foreach ($array as $script) {
            $parts = explode('.', $script);
            $ext = array_pop($parts);
            $name = implode('.', $parts);
            switch ($ext) {
                case 'css': $scripts['css'][] = $name;
                    break;
                case 'js': $scripts['js'][] = $name;
                    break;
                default: $scripts['other'][] = $script;
                    break;
            }
        }
        return $scripts;
    }
}