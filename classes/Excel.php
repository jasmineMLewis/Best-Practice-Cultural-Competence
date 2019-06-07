<?php

require_once 'classes/utility/Database.php';
//require_once 'classes/displayContent/AdminPhaseLists.php';
require_once 'classes/StudyInventory.php';

class Excel {

    private $_db;

    public function __construct() {
        $this->_db = Database::getInstance();
    }

    public function displayExcelTableColumnNames($tableName) {
        $tableFields = $this->_db->fetchColumnNames($tableName);
        for ($i = 0; $i < count($tableFields); $i++) {
            echo $tableFields[$i] . "\t";
        }
        print("\n");
    }

    public function exportArticleScreenChecklist() {
        $this->openExcel("ArticleScreenChecklist");
        $this->displayExcelTableColumnNames("article_screen_checklist_t");

        $this->_db->query("SELECT StudyCode, reviewer_t.FirstName, reviewer_t.LastName, 
                IsPublishedWithinTimeFrame, IsPopProvideDirectPatientContact,
                IsPeerReviewed, IsDescribedPlanEducationIntervention, 
                IsCulturalCompetenceTopicOriginRelated, article_screen_checklist_decision_t.Decision,
                DecisionComments
                FROM article_screen_checklist_t
                INNER JOIN reviewer_t ON article_screen_checklist_t.ReviewerID = reviewer_t.ReviewerID 
                INNER JOIN article_screen_checklist_decision_t ON article_screen_checklist_t.DecisionID = article_screen_checklist_decision_t.DecisionID");

        foreach ($this->_db->getResults() as $row) {
            echo $row->StudyCode . "\t" . $row->FirstName . " " . $row->LastName . "\t" . $row->IsPublishedWithinTimeFrame . "\t" .
            $row->IsPopProvideDirectPatientContact . "\t" . $row->IsPeerReviewed . "\t" . $row->IsDescribedPlanEducationIntervention . "\t" .
            $row->IsCulturalCompetenceTopicOriginRelated . "\t" . $row->Decision . "\t" . $row->DecisionComments . "\r\n";
        }
    }

    public function exportDataExtraction() {
        $this->openExcel("DataExtraction");
        $this->displayExcelTableColumnNames("data_extraction_t");

        $this->_db->query("SELECT StudyCode, reviewer_t.FirstName, reviewer_t.LastName, IsLiteratureReview,
            IsTheoryForStudy, IsLearningOutcomeForStudy, IsObjectiveClear, IsStudyDesignReported,
            IsDesignAppropriate, IsMethodsBias, IsCorrespondenceRequired, IsLearnerOutcomeExists,
            IsStudyDesignExists, IsParticipantExists, IsGenderExists, IsProfessionExists, 
            IsEduIntervDescriptionClear, IsTeachingMethodExists, IsInstructionalRescourceExists,
            IsAssessmentIntervExists, TheoryStudyYesDescrpt, LearningOutcomeForStudyYesDescrpt,
            LearnerOutcomeOtherDescrpt, StudyDesignOtherDescrpt, IsDesignAppropriateUnsureDescrpt,
            InstrumentsUsed, TotalStudyDuration, TotalStudyDurationSpecification,
            MethodsBiasYesDescrpt, MethodsBiasUnsureDescrpt, TotalParticipants, TotalParticipantsSpecification,
            ParticipantOtherDescrpt, ParticipantStudentAcademicLevel, AgeDemographic, AgeDemographicSpecification,
            GenderOtherDescrpt, ProfessionOtherDescrpt, EduIntervDescriptionUnsureDescrpt,
            NumberIntervGroups, NumberIntervGroupsSpecification, NumberParticipantsInIntervGroups,
            NumberParticipantsInIntervGroupsSpecification, TopicsCover, TopicsCoverSpecification,
            TeachingMethodCreditHrs, TeachingMethodOtherDescrpt, InstructionalRescourceOtherDescrpt,
            AssessmentIntervOther, TimePointsCollected, TimePointsCollectedSpecification,
            UnitOfMeasurement, UnitOfMeasurementSpecification, ScaleLimitInterpretation,
            ScaleLimitInterpretationSpecification, EvaluationCriteria, EvaluationCriteriaSpecifciation,
            SampleSize, SampleSizeSpecification, ResponseRate, ResponseRateSpecifcation, 
            MissingParticipants, MissingParticipantsSpecification, SummaryDataMean, SummaryDataCI,
            SummaryDataSD, SummaryDataPValue, SummaryDataOther, SubgroupAnalyses,
            AuthorConclusion, StudyLimitation, AuthorComments, ReferenceToStudies
        FROM data_extraction_t
        INNER JOIN reviewer_t ON data_extraction_t.ReviewerID = reviewer_t.ReviewerID");

        foreach ($this->_db->getResults() as $row) {
            $isLearnerOutcomeExists = FormHelper::getDropDownListNames($row->StudyCode, $row->IsLearnerOutcomeExists, 'data_extract_learner_outcome_t', 'learner_outcome_t', 'OutcomeID', 'Outcome');
            $isStudyDesignExists = FormHelper::getDropDownListNames($row->StudyCode, $row->IsStudyDesignExists, 'data_extract_study_design_t', 'study_design_t', 'StudyDesignID', 'StudyDesign');
            $isParticipantExists = FormHelper::getDropDownListNames($row->StudyCode, $row->IsParticipantExists, 'data_extract_participant_t', 'participant_t', 'ParticipantID', 'Participant');
            $isGenderExists = FormHelper::getDropDownListNames($row->StudyCode, $row->IsGenderExists, 'data_extract_gender_t', 'gender_t', 'GenderID', 'Gender');
            $isProfessionExists = FormHelper::getDropDownListNames($row->StudyCode, $row->IsProfessionExists, 'data_extract_profession_t', 'profession_t', 'ProfessionID', 'Profession');
            $isTeachingMethodExists = FormHelper::getDropDownListNames($row->StudyCode, $row->IsTeachingMethodExists, 'data_extract_teaching_method_t', 'teaching_method_t', 'TeachingMethodID', 'TeachingMethod');
            $isInstructionalRescourceExists = FormHelper::getDropDownListNames($row->StudyCode, $row->IsInstructionalRescourceExists, 'data_extract_instructional_resource_t', 'instructional_resource_t', 'InstructionalResourceID', 'InstructionalResource');
            $isAssessmentIntervExists = FormHelper::getDropDownListNames($row->StudyCode, $row->IsAssessmentIntervExists, 'data_extract_assessment_intervention_t', 'assessment_intervention_t', 'AssessmentInterventionID', 'AssessmentIntervention');

            echo $row->StudyCode . "\t" . $row->FirstName . " " . $row->LastName . "\t" .
            $row->IsLiteratureReview . "\t" . $row->IsTheoryForStudy . "\t" . $row->IsLearningOutcomeForStudy . "\t" .
            $row->IsObjectiveClear . "\t" . $row->IsStudyDesignReported . "\t" . $row->IsDesignAppropriate . "\t" .
            $row->IsMethodsBias . "\t" . $row->IsCorrespondenceRequired . "\t" . implode(",", $isLearnerOutcomeExists) . "\t" .
            implode(",", $isStudyDesignExists) . "\t" . implode(",", $isParticipantExists) . "\t" . implode(",", $isGenderExists) . "\t" .
            implode(",", $isProfessionExists) . "\t" . $row->IsEduIntervDescriptionClear . "\t" . implode(",", $isTeachingMethodExists) . "\t" .
            implode(",", $isInstructionalRescourceExists) . "\t" . implode(",", $isAssessmentIntervExists) . "\t" .
            $row->TheoryStudyYesDescrpt . "\t" . $row->LearningOutcomeForStudyYesDescrpt . "\t" .
            $row->LearnerOutcomeOtherDescrpt . "\t" . $row->StudyDesignOtherDescrpt . "\t" . $row->IsDesignAppropriateUnsureDescrpt . "\t" .
            $row->InstrumentsUsed . "\t" . $row->TotalStudyDuration . "\t" . $row->TotalStudyDurationSpecification . "\t" .
            $row->MethodsBiasYesDescrpt . "\t" . $row->MethodsBiasUnsureDescrpt . "\t" . $row->TotalParticipants . "\t" .
            $row->TotalParticipantsSpecification . "\t" . $row->ParticipantOtherDescrpt . "\t" . $row->ParticipantStudentAcademicLevel . "\t" .
            $row->AgeDemographic . "\t" . $row->AgeDemographicSpecification . "\t" . $row->GenderOtherDescrpt . "\t" .
            $row->ProfessionOtherDescrpt . "\t" . $row->EduIntervDescriptionUnsureDescrpt . "\t" . $row->NumberIntervGroups . "\t" .
            $row->NumberIntervGroupsSpecification . "\t" . $row->NumberParticipantsInIntervGroups . "\t" .
            $row->NumberParticipantsInIntervGroupsSpecification . "\t" . $row->TopicsCover . "\t" . $row->TopicsCoverSpecification . "\t" .
            $row->TeachingMethodCreditHrs . "\t" . $row->TeachingMethodOtherDescrpt . "\t" . $row->InstructionalRescourceOtherDescrpt . "\t" .
            $row->AssessmentIntervOther . "\t" . $row->TimePointsCollected . "\t" . $row->TimePointsCollectedSpecification . "\t" .
            $row->UnitOfMeasurement . "\t" . $row->UnitOfMeasurementSpecification . "\t" . $row->ScaleLimitInterpretation . "\t" .
            $row->ScaleLimitInterpretationSpecification . "\t" . $row->EvaluationCriteria . "\t" . $row->EvaluationCriteriaSpecifciation . "\t" .
            $row->SampleSize . "\t" . $row->SampleSizeSpecification . "\t" . $row->ResponseRate . "\t" . $row->ResponseRateSpecifcation . "\t" .
            $row->MissingParticipants . "\t" . $row->MissingParticipantsSpecification . "\t" . $row->SummaryDataMean . "\t" . $row->SummaryDataCI . "\t" .
            $row->SummaryDataSD . "\t" . $row->SummaryDataPValue . "\t" . $row->SummaryDataOther . "\t" . $row->SubgroupAnalyses . "\t" .
            $row->AuthorConclusion . "\t" . $row->StudyLimitation . "\t" . $row->AuthorComments . "\t" . $row->ReferenceToStudies . "\t" . "\r\n";
        }
    }

    public function exportKirkpatrickRating() {
        $this->openExcel("KirkpatrickRating");
        $this->displayExcelTableColumnNames("kirkpatrick_rating_t");

        $this->_db->query("SELECT StudyCode, reviewer_t.FirstName, reviewer_t.LastName,
            IsLevelOne, IsLevelTwoA, IsLevelThreeA, IsLevelThreeB, IsLevelFourA,
            IsLevelFourB, LevelOneAComments, LevelTwoAComments, LevelTwoBComments, 
            LevelThreeAComments, LevelThreeBComments, LevelFourAComments, LevelFourBComments
            FROM kirkpatrick_rating_t
            INNER JOIN reviewer_t ON kirkpatrick_rating_t.ReviewerID = reviewer_t.ReviewerID");

        foreach ($this->_db->getResults() as $row) {
            echo $row->StudyCode . "\t" . $row->FirstName . " " . $row->LastName . "\t" .
            $row->IsLevelOne . "\t" . $row->IsLevelTwoA . "\t" . $row->IsLevelThreeA . "\t" . $row->IsLevelThreeB . "\t" .
            $row->IsLevelFourA . "\t" . $row->IsLevelFourB . "\t" . $row->LevelOneAComments . "\t" . $row->LevelTwoAComments . "\t" .
            $row->LevelTwoBComments . "\t" . $row->LevelThreeAComments . "\t" . $row->LevelThreeBComments . "\t" .
            $row->LevelFourAComments . "\t" . $row->LevelFourBComments . "\t" . "\r\n";
        }
    }

    public function exportQuestsAppraisal() {
        $this->openExcel("QuestsAppraisal");
        $this->displayExcelTableColumnNames("quest_appraise_t");

        $this->_db->query("SELECT StudyCode, reviewer_t.FirstName, reviewer_t.LastName,
            IsPRDescription, IsPRJustification, IsPRClarification, QDQualityScore, QDUtilityScore,
            QDExtentScore, QDStrengthScore, QDTargetScore, QDSettingScore, PRDescriptionComments,
            PRJustificationComments, PRClarificationComments, QDQualityComments, QDUtilityComments,
            QDExtentComments, QDStrengthComments, QDTargetComments, QDSettingComments
        FROM quest_appraise_t
        INNER JOIN reviewer_t ON quest_appraise_t.ReviewerID = reviewer_t.ReviewerID");

        foreach ($this->_db->getResults() as $row) {
            echo $row->StudyCode . "\t" . $row->FirstName . " " . $row->LastName . "\t" .
            $row->IsPRDescription . "\t" . $row->IsPRJustification . "\t" . $row->IsPRClarification . "\t" . $row->QDQualityScore . "\t" .
            $row->QDUtilityScore . "\t" . $row->QDExtentScore . "\t" . $row->QDStrengthScore . "\t" . $row->QDTargetScore . "\t" .
            $row->QDSettingScore . "\t" . $row->PRDescriptionComments . "\t" . $row->PRJustificationComments . "\t" .
            $row->PRClarificationComments . "\t" . $row->QDQualityComments . "\t" . $row->QDUtilityComments . "\t" .
            $row->QDExtentComments . "\t" . $row->QDStrengthComments . "\t" . $row->QDTargetComments . "\t" . $row->QDSettingComments . "\t" . "\r\n";
        }
    }

    public function exportStudyInventory() {
        $this->openExcel("StudyInventory");
        $this->displayExcelTableColumnNames("study_inventory_t");

        $this->_db->query("SELECT study_inventory_t.StudyCode, reviewer_t.FirstName, reviewer_t.LastName,
 search_method_t.SearchMethod, document_type_t.DocumentType, database_t.ResDatabase,Hash,
 YearPublished, YearStudyBegan, YearStudyEnd, IsCountryExists, IsProfessionExists, IsInstitutionExists, AuthorsNames, Title, ArticleUrl,
 MonthPublished, OtherInstitution, Url, Doi, Pubmed, OriginalAbstract, RelevantPointOne,
 RelevantPointTwo, RelevantPointThree, Citation, IsAnArticleDisabled
FROM study_inventory_t
INNER JOIN reviewer_t ON study_inventory_t.ReviewerID = reviewer_t.ReviewerID
INNER JOIN search_method_t ON study_inventory_t.SearchMethodID = search_method_t.SearchMethodID
INNER JOIN document_type_t ON study_inventory_t.DocumentTypeID = document_type_t.DocumentTypeID
INNER JOIN database_t ON study_inventory_t.DatabaseID = database_t.DatabaseID");

        foreach ($this->_db->getResults() as $row) {
            $countries = FormHelper::getDropDownListNames($row->StudyCode, $row->IsCountryExists, 'study_inventory_country_t', 'country_t', 'CountryID', 'Country');
            $professions = FormHelper::getDropDownListNames($row->StudyCode, $row->IsProfessionExists, 'study_inventory_profession_t', 'profession_t', 'ProfessionID', 'Profession');
            $institutions = FormHelper::getDropDownListNames($row->StudyCode, $row->IsInstitutionExists, 'study_inventory_institutions_t', 'institution_t', 'InstitutionID', 'Institution');

            echo $row->StudyCode . "\t" . $row->FirstName . " " . $row->LastName . "\t" .
            $row->SearchMethod . "\t" . $row->DocumentType . "\t" . $row->ResDatabase . "\t" .
            $row->YearPublished . "\t" . $row->YearStudyBegan . "\t" . $row->YearStudyEnd . "\t" . $row->IsAnArticleDisabled . "\t" .
            implode(", ", $countries) . "\t" . implode(", ", $professions) . "\t" . implode(", ", $institutions) . "\t" .
            $row->AuthorsNames . "\t" . $row->Title . "\t" . $row->ArticleUrl . "\t" . $row->Hash . "\t" .
            $row->MonthPublished . "\t" . "\t" . "\t" . $row->Url . "\t" . $row->Doi . "\t" .
            $row->Pubmed . "\t" . $row->OriginalAbstract . "\t" . $row->RelevantPointOne . "\t" .
            $row->RelevantPointTwo . "\t" . $row->RelevantPointThree . "\t" . $row->Citation . "\t" . "\r\n";
        }
    }

    public function openExcel($fileName) {
        header("Content-Type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=$fileName.xls");
        header("Pragma:no-cache");
        header("Expires:0");
    }
}