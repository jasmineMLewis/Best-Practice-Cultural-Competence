<?php


class ContactPageContent {
   
    public static function displayContactInfo() {
    ?>
        <div id="wrapper">
        <div id="contactInfo">
        <h1>Contact Us</h1>
        <br/>
        <p>Margarita Echeverri, Ph. D.<p>
        <ul>
            <li>Associate Professor</li>
            <li>Educational Coordinator in Health Disparities, Cultural Competence and Diversity</li>
            <li>Telephone Number: 504-520-6719</li>
            <li>Fax Number: 504-520-7971</li>
            <li>Email: <a href="mailto:mechever@xula.edu">mechever@xula.edu</a></li>
        </ul>
        <p class="gradient"></p>
        </div>
        </div>
    <?php
    }
    
    public static function displayFooter() {
        ?>
        <footer>
            <div class="footer">
            <div class="production"><a href="home.php">Home</a> | 
                <i>an</i> <a href="mailto:igloonationtech@gmail.com">IglooNation
                    Tech, LLC</a> <i>techartistry experience</i></div>
            </div>
        </footer>
        <?php
    }
}