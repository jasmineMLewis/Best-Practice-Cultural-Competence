<?php
require_once 'includes/culturalCompetence.php';

$contactUs = new ContactUsPage();
$contactUs->link(array('page.css','contactUs.css'));
$contactUs->display($type = null, $code = null, $id = null);