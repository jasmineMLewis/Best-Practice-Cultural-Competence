<?php

require_once 'includes/culturalCompetence.php';

if(Session::exists('reviewerID')){
    Session::get('reviewerID');
}

$studyCode = Input::get('StudyCode');

?>

<frameset cols="23%,25%">
    <frame src="articlescreen.php?type=create&StudyCode=<?php echo $studyCode; ?>">
    <frame src="studyinventory.php?type=viewArticle&StudyCode=<?php echo $studyCode; ?>">
</frameset>

