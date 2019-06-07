<?php
require_once 'classes/pages/Page.php';
require_once 'classes/Reviewer.php';
require_once 'classes/utility/Input.php';
require_once 'classes/utility/Session.php';

class HomePage extends Page {

    public function __construct() {
        parent::__construct();
    }

    public function content() {
        echo '<div class="pageWrapper">';
        $this->displaySchoolHeader();
        $this->displayAppHeader();
        $this->displayLoginForm();
        echo '</div>';
        if (isset($_POST['login'])) {
            $reviewer = new Reviewer();

            if ($reviewer->isDisabled(Input::get('email'))) {
                echo '<div class="messageError"><h2>Account Disabled</h2></div>';
            } else {
                if ($reviewer->isValidLogin(array(Input::get('email'),
                            Input::get('password')))) {

                    $login = $reviewer->login(Input::get('email'), Input::get('password'));
                    if ($login) {
                    header('Location: reviewermenu.php');
                    exit();
                    } else {
                        echo '<div class="messageError"><h2>Invalid email/password combination</h2></div>';
                    }
                } else {
                    echo '<div class="messageError"><h2>Fill out all fields</h2></div>';
                }
            }
        }

        if (isset($_POST['resetPassword'])) {
            $reviewer = new Reviewer();
            $passwordReset = $reviewer->resetPassword(Input::get('email'));

            if ($passwordReset) {
                echo '<div class="messageSuccess"><h2>New Password Sent to Email</h2></div>';
            } else {
                echo '<div class="messageError"><h2>Error Reseting Password. Try Again!</h2></div>';
            }
        }
    }

    public function displayAppDescription() {
        ?>
        <div class="app_descrip">
            <h3><p class="app_descrip_name">Educational Interventions on Culturally Competent Healthcare:</p>
                <p class="app_descrip_name_extended">A Systematic Review of the Rationale, Content, Teaching Methods,and Measures of Effectiveness</p>
            </h3>
            <p class="app_descrip_info_chunk1">This review will address the general question of what are the best evidence-based educational 
                interventions to improve current and future healthcare </p>
            <p class="app_descrip_info_chunk2">professionals ability to provide culturally competent healthcare for patients of different
                aces/ethnicities, origins/ancestries, and cultures</p>
        </div>
        <?php
    }

    public function displayAppHeader() {
        echo '<div id="app_header">';
        $this->displayAppName();
        $this->displayAppDescription();
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
        $this->displayFooter();
        echo "</body>\n";
        echo "</html>";
    }

    public function displayFooter() {
        ?>
        <footer>
            <div class="footer">
                <div class="production"><a href="contactUs.php">Contact Us</a> | 
                    <i>an</i> <a href="mailto:igloonationtech@gmail.com">IglooNation Tech, LLC</a> 
                    <i> techartistry experience</i></div>
            </div>
        </footer>
        <?php
    }

    public function displayLoginForm() {
        ?>
        <div id="wrapper">
            <div id="login">
                <form id="loginForm" name="loginForm" method="post" action="">
                    <h1>Log In</h1>
                    <p><input id="email" name="email" type="email" placeholder="EMAIL" required="required"/></p>
                    <p><input id="password" name="password" type="password" placeholder="PASSWORD"/></p>
                    <p class="login_button">
                        <input name="login" type="submit" value="Login" />
                        <input id="resetPassword" name="resetPassword" type="submit" value="Reset Password" />
                    </p>
                    <p class="gradient"></p>
                </form>
            </div>
        </div>
        <?php
    }

    public function displaySchoolHeader() {
        echo '<div id="school_header">';
        $this->displaySchoolInfo();
        echo '</div>';
    }

    public function displaySchoolInfo() {
        ?>
        <div class="xula_img">
            <img src="assets/images/XulaSeal-min.png" alt="XULA">
        </div>

        <div class="xula_name"><h2>Xavier University of Louisiana</h2></div>
        <div class="college_name"><h4>College of Pharmacy</h4></div>

        <div class="college_pharamacy_img">
            <img src="assets/images/CollegeOfPharamacy.gif" alt="CollegeOfPharamacy">
        </div>
        <?php
    }

}
