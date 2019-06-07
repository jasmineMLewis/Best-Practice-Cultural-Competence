<?php

require_once 'classes/forms/CreationForms.php';
require_once 'classes/DataExtraction.php';
require_once 'classes/forms/EditForms.php';
require_once 'classes/pages/Page.php';
require_once 'classes/Reviewer.php';
require_once 'classes/utility/Session.php';
require_once 'classes/displayContent/ReviewerPhaseLists.php';
require_once 'classes/displayContent/ReviewerPhasesData.php';


class DataExtractionPage extends Page {

    public function __construct() {
        parent::__construct();
    }

    public function createContent($studyCode) {
        CreationForms::displayDataExtractionCreationForm();

        if (Input::exists('post')) {
            $create = new DataExtraction();
            $creationExists = $create->create(array(
                "StudyCode" => $studyCode,
                "ReviewerID" => Session::get('reviewerID'),
                "IsLiteratureReview" => Input::get('isLiteratureReview'),
                "IsTheoryForStudy" => Input::get('isTheoryForStudy'),
                "IsLearningOutcomeForStudy" => Input::get('isLearningOutcomeForStudy'),
                "IsObjectiveClear" => Input::get('isClearWellDefined'),
                "IsStudyDesignReported" => Input::get('isStudyDesignReported'),
                "IsDesignAppropriate" => Input::get('isDesignAppropriate'),
                "IsMethodsBias" => Input::get('isBiasMethods'),
                "IsCorrespondenceRequired" => Input::get('correspondenceRequired'),
                "IsLearnerOutcomeExists" => Input::get('outcome'),
                "IsStudyDesignExists" => Input::get('studyDesign'),
                "IsParticipantExists" => Input::get('participantTypes'),
                "IsGenderExists" => Input::get('gender'),
                "IsProfessionExists" => Input::get('profession'),
                "IsEduIntervDescriptionClear" => Input::get('isEducationDescribed'),
                "IsTeachingMethodExists" => Input::get('teachingmethod'),
                "IsInstructionalRescourceExists" => Input::get('instructionalResource'),
                "IsAssessmentIntervExists" => Input::get('assessmentIntervention'),
                "TheoryStudyYesDescrpt" => Input::get('isTheoryForStudyDescribe'),
                "LearningOutcomeForStudyYesDescrpt" => Input::get('learningOutcomeForStudyYesDescribe'),
                "LearnerOutcomeOtherDescrpt" => Input::get('levelsLearnerOutcomeOther'),
                "StudyDesignOtherDescrpt" => Input::get('studyDesignOtherDescribe'),
                "IsDesignAppropriateUnsureDescrpt" => Input::get('isDesignAppropriateDescription'),
                "InstrumentsUsed" => Input::get('instrumentsUsed'),
                "TotalStudyDuration" => Input::get('totalStudyDurationTime'),
                "TotalStudyDurationSpecification" => Input::get('totalStudyDurationOption'),
                "MethodsBiasYesDescrpt" => Input::get('isBiasMethodsYesDescribe'),
                "MethodsBiasUnsureDescrpt" => Input::get('isBiasMethodsUnsure'),
                "TotalParticipants" => Input::get('totalParticipants'),
                "TotalParticipantsSpecification" => Input::get('particiantsTotalSpecificiation'),
                "ParticipantOtherDescrpt" => Input::get('participantOther'),
                "ParticipantStudentAcademicLevel" => Input::get('studentAcademicLevel'),
                "AgeDemographic" => Input::get('age'),
                "AgeDemographicSpecification" => Input::get('ageSpecificiation'),
                "GenderOtherDescrpt" => Input::get('genderOther'),
                "ProfessionOtherDescrpt" => Input::get('professionOther'),
                "EduIntervDescriptionUnsureDescrpt" => Input::get('educationNotSureDescribe'),
                "NumberIntervGroups" => Input::get('numberInterventionGrp'),
                "NumberIntervGroupsSpecification" => Input::get('numberInterSpecificiation'),
                "NumberParticipantsInIntervGroups" => Input::get('participantNumberInterventionGrp'),
                "NumberParticipantsInIntervGroupsSpecification" => Input::get('numberParticipantGrpInterSpecificiation'),
                "TopicsCover" => Input::get('topicCover'),
                "TopicsCoverSpecification" => Input::get('topicCoverSpecificiation'),
                "TeachingMethodCreditHrs" => Input::get('teachingMethodCreditHrs'),
                "TeachingMethodOtherDescrpt" => Input::get('teachingMethodOther'),
                "InstructionalRescourceOtherDescrpt" => Input::get('instructionalResourceOther'),
                "AssessmentIntervOther" => Input::get('assessmentInterventionOther'),
                "TimePointsCollected" => Input::get('timePointsCollected'),
                "TimePointsCollectedSpecification" => Input::get('timePointCollectedSpecification'),
                "UnitOfMeasurement" => Input::get('unitOfMeasurement'),
                "UnitOfMeasurementSpecification" => Input::get('unitOfMesaurementSpecification'),
                "ScaleLimitInterpretation" => Input::get('limitsAndInterpretation'),
                "ScaleLimitInterpretationSpecification" => Input::get('limitSpecification'),
                "EvaluationCriteria" => Input::get('evaluationCriteria'),
                "EvaluationCriteriaSpecifciation" => Input::get('levuationCriteriaSpecification'),
                "SampleSize" => Input::get('sampleSize'),
                "SampleSizeSpecification" => Input::get('sampleSizeSpecification'),
                "ResponseRate" => Input::get('responseRate'),
                "ResponseRateSpecifcation" => Input::get('responseRateSpecification'),
                "MissingParticipants" => Input::get('missingParticipants'),
                "MissingParticipantsSpecification" => Input::get('missingParticipantsSpecification'),
                "SummaryDataMean" => Input::get('mean'),
                "SummaryDataCI" => Input::get('ci'),
                "SummaryDataSD" => Input::get('sd'),
                "SummaryDataPValue" => Input::get('pValue'),
                "SummaryDataOther" => Input::get('otherSummaryData'),
                "SubgroupAnalyses" => Input::get('subgrpAnalyses'),
                "AuthorConclusion" => Input::get('keyAuthorConclusion'),
                "StudyLimitation" => Input::get('studyLimiation'),
                "AuthorComments" => Input::get('authorComments'),
                "ReferenceToStudies" => Input::get('referenceToOthStudies')
            ));

            if (!$creationExists) {
                $updatePhase = new StudyInventory();
                $updatePhase->updateStageForArticle($studyCode, 3);
                die("<script> parent.window.location.href='dataextraction.php?type=dataExtList';</script>");
            } else {
                echo '<h1>Error in Data Extraction Submission</h1>';
            }
        }
    }

    public function display($type, $studyCode, $reviewerID) {
        $this->getDoctype();
        echo "<html>\n";
        echo "<head>\n";
        $this->getTitle();
        $this->getMetaTags();
        $this->includeScripts();
        echo "</head>\n";
        echo "<body>\n";

        switch ($type) {
            case 'create':
                $this->createContent($studyCode);
                break;
            case 'dataExtList':
                $this->displayHeader();
                $this->displayAppHeader(); 
                ReviewerPhaseLists::displayDataExtractionList(Session::get('reviewerID'));
                break;
            case 'edit':
                $this->displayEditContent($studyCode);
                break;
            case 'view':
                $this->displayHeader();
                $this->displayAppHeader();
                ReviewerPhasesData::displayDataExtraction($studyCode);
                break;
            default:
                break;
        }
        echo "</body>\n";
        echo "</html>\n";
    }

    public function displayEditContent($studyCode) {
        EditForms::displayDataExtraction($studyCode);

        if (Input::exists('post')) {
            $edit = new DataExtraction();
            $editExists = $edit->edit(array(
                "StudyCode" => $studyCode,
                "IsLiteratureReview" => Input::get('isLiteratureReview'),
                "IsTheoryForStudy" => Input::get('isTheoryForStudy'),
                "IsLearningOutcomeForStudy" => Input::get('isLearningOutcomeForStudy'),
                "IsObjectiveClear" => Input::get('isClearWellDefined'),
                "IsStudyDesignReported" => Input::get('isStudyDesignReported'),
                "IsDesignAppropriate" => Input::get('isDesignAppropriate'),
                "IsMethodsBias" => Input::get('isBiasMethods'),
                "IsCorrespondenceRequired" => Input::get('correspondenceRequired'),
                "IsLearnerOutcomeExists" => Input::get('outcome'),
                "IsStudyDesignExists" => Input::get('studyDesign'),
                "IsParticipantExists" => Input::get('participantTypes'),
                "IsGenderExists" => Input::get('gender'),
                "IsProfessionExists" => Input::get('profession'),
                "IsEduIntervDescriptionClear" => Input::get('isEducationDescribed'),
                "IsTeachingMethodExists" => Input::get('teachingmethod'),
                "IsInstructionalRescourceExists" => Input::get('instructionalResource'),
                "IsAssessmentIntervExists" => Input::get('assessmentIntervention'),
                "TheoryStudyYesDescrpt" => Input::get('isTheoryForStudyDescribe'),
                "LearningOutcomeForStudyYesDescrpt" => Input::get('learningOutcomeForStudyYesDescribe'),
                "LearnerOutcomeOtherDescrpt" => Input::get('levelsLearnerOutcomeOther'),
                "StudyDesignOtherDescrpt" => Input::get('studyDesignOtherDescribe'),
                "IsDesignAppropriateUnsureDescrpt" => Input::get('isDesignAppropriateDescription'),
                "InstrumentsUsed" => Input::get('instrumentsUsed'),
                "TotalStudyDuration" => Input::get('totalStudyDurationTime'),
                "TotalStudyDurationSpecification" => Input::get('totalStudyDurationOption'),
                "MethodsBiasYesDescrpt" => Input::get('isBiasMethodsYesDescribe'),
                "MethodsBiasUnsureDescrpt" => Input::get('isBiasMethodsUnsure'),
                "TotalParticipants" => Input::get('totalParticipants'),
                "TotalParticipantsSpecification" => Input::get('particiantsTotalSpecificiation'),
                "ParticipantOtherDescrpt" => Input::get('participantOther'),
                "ParticipantStudentAcademicLevel" => Input::get('studentAcademicLevel'),
                "AgeDemographic" => Input::get('age'),
                "AgeDemographicSpecification" => Input::get('ageSpecificiation'),
                "GenderOtherDescrpt" => Input::get('genderOther'),
                "ProfessionOtherDescrpt" => Input::get('professionOther'),
                "EduIntervDescriptionUnsureDescrpt" => Input::get('educationNotSureDescribe'),
                "NumberIntervGroups" => Input::get('numberInterventionGrp'),
                "NumberIntervGroupsSpecification" => Input::get('numberInterSpecificiation'),
                "NumberParticipantsInIntervGroups" => Input::get('participantNumberInterventionGrp'),
                "NumberParticipantsInIntervGroupsSpecification" => Input::get('numberParticipantGrpInterSpecificiation'),
                "TopicsCover" => Input::get('topicCover'),
                "TopicsCoverSpecification" => Input::get('topicCoverSpecificiation'),
                "TeachingMethodCreditHrs" => Input::get('teachingMethodCreditHrs'),
                "TeachingMethodOtherDescrpt" => Input::get('teachingMethodOther'),
                "InstructionalRescourceOtherDescrpt" => Input::get('instructionalResourceOther'),
                "AssessmentIntervOther" => Input::get('assessmentInterventionOther'),
                "TimePointsCollected" => Input::get('timePointsCollected'),
                "TimePointsCollectedSpecification" => Input::get('timePointCollectedSpecification'),
                "UnitOfMeasurement" => Input::get('unitOfMeasurement'),
                "UnitOfMeasurementSpecification" => Input::get('unitOfMesaurementSpecification'),
                "ScaleLimitInterpretation" => Input::get('limitsAndInterpretation'),
                "ScaleLimitInterpretationSpecification" => Input::get('limitSpecification'),
                "EvaluationCriteria" => Input::get('evaluationCriteria'),
                "EvaluationCriteriaSpecifciation" => Input::get('levuationCriteriaSpecification'),
                "SampleSize" => Input::get('sampleSize'),
                "SampleSizeSpecification" => Input::get('sampleSizeSpecification'),
                "ResponseRate" => Input::get('responseRate'),
                "ResponseRateSpecifcation" => Input::get('responseRateSpecification'),
                "MissingParticipants" => Input::get('missingParticipants'),
                "MissingParticipantsSpecification" => Input::get('missingParticipantsSpecification'),
                "SummaryDataMean" => Input::get('mean'),
                "SummaryDataCI" => Input::get('ci'),
                "SummaryDataSD" => Input::get('sd'),
                "SummaryDataPValue" => Input::get('pValue'),
                "SummaryDataOther" => Input::get('otherSummaryData'),
                "SubgroupAnalyses" => Input::get('subgrpAnalyses'),
                "AuthorConclusion" => Input::get('keyAuthorConclusion'),
                "StudyLimitation" => Input::get('studyLimiation'),
                "AuthorComments" => Input::get('authorComments'),
                "ReferenceToStudies" => Input::get('referenceToOthStudies')
            ));

            if (!$editExists) {
                die("<script> parent.window.location.href='dataextraction.php?type=dataExtList';</script>");
            } else {
                echo '<h1>Error Editing Data Extraction</h1>';
            }
        }
    }
}
