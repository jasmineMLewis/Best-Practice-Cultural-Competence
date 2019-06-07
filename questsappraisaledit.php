<?php
require_once 'includes/culturalCompetence.php';

if(Session::exists('reviewerID')){
    Session::get('reviewerID');
}

$studyCode = Input::get('StudyCode');
$reviewerID = Input::get('reviewerID');

?>

<frameset cols="23%,25%">
    <frame src="questsappraisal.php?type=edit&StudyCode=<?php echo $studyCode; ?>&reviewerID=<?php echo $reviewerID; ?>">
    <frame src="studyinventory.php?type=viewArticle&StudyCode=<?php echo $studyCode; ?>">
</frameset>
