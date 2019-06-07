<?php
require_once 'classes/ChecklistScreen.php';
require_once 'classes/forms/FormHelper.php';
require_once 'classes/utility/Database.php';
require_once 'classes/StudyInventory.php';

class DataExtraction {

    const DUMMY_FIELD = "DummyValue";
    private $_db;

    public function __construct() {
        $this->_db = Database::getInstance();
    }

    public function create($reviewerInput = array()) {
        $dummyValueFields = array("TheoryStudyYesDescrpt",
            "LearningOutcomeForStudyYesDescrpt", "LearnerOutcomeOtherDescrpt",
            "StudyDesignOtherDescrpt", "IsDesignAppropriateUnsureDescrpt",
            "InstrumentsUsed", "TotalStudyDuration",
            "TotalStudyDurationSpecification", "MethodsBiasYesDescrpt",
            "MethodsBiasUnsureDescrpt", "TotalParticipants",
            "TotalParticipantsSpecification", "ParticipantOtherDescrpt",
            "ParticipantStudentAcademicLevel", "AgeDemographic",
            "AgeDemographicSpecification", "GenderOtherDescrpt",
            "ProfessionOtherDescrpt", "EduIntervDescriptionUnsureDescrpt",
            "NumberIntervGroups", "NumberIntervGroupsSpecification",
            "NumberParticipantsInIntervGroups",
            "NumberParticipantsInIntervGroupsSpecification",
            "TopicsCover", "TopicsCoverSpecification", "TeachingMethodCreditHrs",
            "TeachingMethodOtherDescrpt", "InstructionalRescourceOtherDescrpt",
            "AssessmentIntervOther", "TimePointsCollected",
            "TimePointsCollectedSpecification", "UnitOfMeasurement",
            "UnitOfMeasurementSpecification", "ScaleLimitInterpretation",
            "ScaleLimitInterpretationSpecification", "EvaluationCriteria",
            "EvaluationCriteriaSpecifciation", "SampleSize",
            "SampleSizeSpecification", "ResponseRate", "ResponseRateSpecifcation",
            "MissingParticipants", "MissingParticipantsSpecification",
            "SummaryDataMean", "SummaryDataCI", "SummaryDataSD",
            "SummaryDataPValue", "SummaryDataOther", "SubgroupAnalyses",
            "AuthorConclusion", "StudyLimitation", "AuthorComments",
            "ReferenceToStudies");

        $studyInventory = new StudyInventory();
        $data = $studyInventory->fillEmptyFieldsWithDefault(self::DUMMY_FIELD, $reviewerInput, $dummyValueFields);

        //get array fields
        $levelLearnerOutcomes = $data['IsLearnerOutcomeExists'];
        $studyDesign = $data['IsStudyDesignExists'];
        $participants = $data['IsParticipantExists'];
        $gender = $data['IsGenderExists'];
        $professions = $data['IsProfessionExists'];
        $teachingMethdods = $data['IsTeachingMethodExists'];
        $instructionalResourse = $data['IsInstructionalRescourceExists'];
        $assessmentIntervention = $data['IsAssessmentIntervExists'];

        //convert values to boolean for DB
        empty($data['IsLearnerOutcomeExists']) ?
                        $data['IsLearnerOutcomeExists'] = "0" :
                        $data['IsLearnerOutcomeExists'] = "1";
        empty($data['IsStudyDesignExists']) ?
                        $data['IsStudyDesignExists'] = "0" :
                        $data['IsStudyDesignExists'] = "1";
        empty($data['IsParticipantExists']) ?
                        $data['IsParticipantExists'] = "0" :
                        $data['IsParticipantExists'] = "1";
        empty($data['IsGenderExists']) ?
                        $data['IsGenderExists'] = "0" :
                        $data['IsGenderExists'] = "1";
        empty($data['IsProfessionExists']) ?
                        $data['IsProfessionExists'] = "0" :
                        $data['IsProfessionExists'] = "1";
        empty($data['IsTeachingMethodExists']) ?
                        $data['IsTeachingMethodExists'] = "0" :
                        $data['IsTeachingMethodExists'] = "1";
        empty($data['IsInstructionalRescourceExists']) ?
                        $data['IsInstructionalRescourceExists'] = "0" :
                        $data['IsInstructionalRescourceExists'] = "1";
        empty($data['IsAssessmentIntervExists']) ?
                        $data['IsAssessmentIntervExists'] = "0" :
                        $data['IsAssessmentIntervExists'] = "1";

        $insertSuccess = $this->_db->insert("data_extraction_t", $data);

        $inventory = new StudyInventory();
        if (!empty($levelLearnerOutcomes)) {
            $inventory->insertMultipleTableRows('data_extract_learner_outcome_t', $data["StudyCode"], "OutcomeID", $levelLearnerOutcomes);
        }

        if (!empty($studyDesign)) {
            $inventory->insertMultipleTableRows('data_extract_study_design_t', $data['StudyCode'], "StudyDesignID", $studyDesign);
        }

        if (!empty($participants)) {
            $inventory->insertMultipleTableRows('data_extract_participant_t', $data["StudyCode"], "ParticipantID", $participants);
        }

        if (!empty($gender)) {
            $inventory->insertMultipleTableRows('data_extract_gender_t', $data["StudyCode"], "GenderID", $gender);
        }

        if (!empty($professions)) {
            $inventory->insertMultipleTableRows('data_extract_profession_t', $data["StudyCode"], "ProfessionID", $professions);
        }

        if (!empty($teachingMethdods)) {
            $inventory->insertMultipleTableRows('data_extract_teaching_method_t', $data["StudyCode"], "TeachingMethodID", $teachingMethdods);
        }

        if (!empty($instructionalResourse)) {
            $inventory->insertMultipleTableRows('data_extract_instructional_resource_t', $data["StudyCode"], "InstructionalResourceID", $instructionalResourse);
        }

        if (!empty($assessmentIntervention)) {
            $inventory->insertMultipleTableRows('data_extract_assessment_intervention_t', $data["StudyCode"], "AssessmentInterventionID", $assessmentIntervention);
        }

        return $insertSuccess;
    }

    /**
     * Deletes the rows for table fields that are boolean in main phase table.
     * These are tables that contain mulitiple responses for the same form.
     * @param string $studyCode code for article where row is located
     * @param string $phaseTable name of stage table of article
     */
    public function deletePhaseSubtableRows($studyCode, $phaseTable) {
        $this->_db->delete($phaseTable, array('StudyCode', '=', $studyCode));
    }

    public function edit($reviewerInput = array()) {
        $dummyValueFields = array("TheoryStudyYesDescrpt",
            "LearningOutcomeForStudyYesDescrpt", "LearnerOutcomeOtherDescrpt",
            "StudyDesignOtherDescrpt", "IsDesignAppropriateUnsureDescrpt",
            "InstrumentsUsed", "TotalStudyDuration",
            "TotalStudyDurationSpecification", "MethodsBiasYesDescrpt",
            "MethodsBiasUnsureDescrpt", "TotalParticipants",
            "TotalParticipantsSpecification", "ParticipantOtherDescrpt",
            "ParticipantStudentAcademicLevel", "AgeDemographic",
            "AgeDemographicSpecification", "GenderOtherDescrpt",
            "ProfessionOtherDescrpt", "EduIntervDescriptionUnsureDescrpt",
            "NumberIntervGroups", "NumberIntervGroupsSpecification",
            "NumberParticipantsInIntervGroups",
            "NumberParticipantsInIntervGroupsSpecification",
            "TopicsCover", "TopicsCoverSpecification", "TeachingMethodCreditHrs",
            "TeachingMethodOtherDescrpt", "InstructionalRescourceOtherDescrpt",
            "AssessmentIntervOther", "TimePointsCollected",
            "TimePointsCollectedSpecification", "UnitOfMeasurement",
            "UnitOfMeasurementSpecification", "ScaleLimitInterpretation",
            "ScaleLimitInterpretationSpecification", "EvaluationCriteria",
            "EvaluationCriteriaSpecifciation", "SampleSize",
            "SampleSizeSpecification", "ResponseRate", "ResponseRateSpecifcation",
            "MissingParticipants", "MissingParticipantsSpecification",
            "SummaryDataMean", "SummaryDataCI", "SummaryDataSD",
            "SummaryDataPValue", "SummaryDataOther", "SubgroupAnalyses",
            "AuthorConclusion", "StudyLimitation", "AuthorComments",
            "ReferenceToStudies");

        $studyInventory = new StudyInventory();
        $data = $studyInventory->fillEmptyFieldsWithDefault(self::DUMMY_FIELD, $reviewerInput, $dummyValueFields);

        //get array fields
        $levelLearnerOutcomes = $data['IsLearnerOutcomeExists'];
        $studyDesign = $data['IsStudyDesignExists'];
        $participants = $data['IsParticipantExists'];
        $gender = $data['IsGenderExists'];
        $professions = $data['IsProfessionExists'];
        $teachingMethdods = $data['IsTeachingMethodExists'];
        $instructionalResourse = $data['IsInstructionalRescourceExists'];
        $assessmentIntervention = $data['IsAssessmentIntervExists'];

        //convert values to boolean for DB
        empty($data['IsLearnerOutcomeExists']) ?
                        $data['IsLearnerOutcomeExists'] = "0" :
                        $data['IsLearnerOutcomeExists'] = "1";
        empty($data['IsStudyDesignExists']) ?
                        $data['IsStudyDesignExists'] = "0" :
                        $data['IsStudyDesignExists'] = "1";
        empty($data['IsParticipantExists']) ?
                        $data['IsParticipantExists'] = "0" :
                        $data['IsParticipantExists'] = "1";
        empty($data['IsGenderExists']) ?
                        $data['IsGenderExists'] = "0" :
                        $data['IsGenderExists'] = "1";
        empty($data['IsProfessionExists']) ?
                        $data['IsProfessionExists'] = "0" :
                        $data['IsProfessionExists'] = "1";
        empty($data['IsTeachingMethodExists']) ?
                        $data['IsTeachingMethodExists'] = "0" :
                        $data['IsTeachingMethodExists'] = "1";
        empty($data['IsInstructionalRescourceExists']) ?
                        $data['IsInstructionalRescourceExists'] = "0" :
                        $data['IsInstructionalRescourceExists'] = "1";
        empty($data['IsAssessmentIntervExists']) ?
                        $data['IsAssessmentIntervExists'] = "0" :
                        $data['IsAssessmentIntervExists'] = "1";

        $updateSuccess = $this->_db->update('data_extraction_t', 'StudyCode', $data['StudyCode'], $data);

        $phaseTablesForDeletion = array('data_extract_learner_outcome_t',
            'data_extract_study_design_t', 'data_extract_participant_t',
            'data_extract_gender_t', 'data_extract_profession_t',
            'data_extract_teaching_method_t', 'data_extract_instructional_resource_t',
            'data_extract_assessment_intervention_t');

        $this->deleteTablesRowsForCode($data['StudyCode'], $phaseTablesForDeletion);

        $inventory = new StudyInventory();
        if (!empty($levelLearnerOutcomes)) {
            $inventory->insertMultipleTableRows('data_extract_learner_outcome_t', $data['StudyCode'], "OutcomeID", $levelLearnerOutcomes);
        }

        if (!empty($studyDesign)) {
            $inventory->insertMultipleTableRows('data_extract_study_design_t', $data['StudyCode'], "StudyDesignID", $studyDesign);
        }

        if (!empty($participants)) {
            $inventory->insertMultipleTableRows('data_extract_participant_t', $data['StudyCode'], "ParticipantID", $participants);
        }

        if (!empty($gender)) {
            $inventory->insertMultipleTableRows('data_extract_gender_t', $data['StudyCode'], "GenderID", $gender);
        }

        if (!empty($professions)) {
            $inventory->insertMultipleTableRows('data_extract_profession_t', $data['StudyCode'], "ProfessionID", $professions);
        }

        if (!empty($teachingMethdods)) {
            $inventory->insertMultipleTableRows('data_extract_teaching_method_t', $data['StudyCode'], "TeachingMethodID", $teachingMethdods);
        }

        if (!empty($instructionalResourse)) {
            $inventory->insertMultipleTableRows('data_extract_instructional_resource_t', $data['StudyCode'], "InstructionalResourceID", $instructionalResourse);
        }

        if (!empty($assessmentIntervention)) {
            $inventory->insertMultipleTableRows('data_extract_assessment_intervention_t', $data['StudyCode'], "AssessmentInterventionID", $assessmentIntervention);
        }
        return $updateSuccess;
    }

    public function getAssignedStudyCodesHigherThanPhase($reviewerID, $phase) {
        $studyCodes = array();
        $query = $this->_db->query("SELECT StudyCode FROM article_process_stage_t WHERE "
                . "ReviewerID = '$reviewerID' AND ArticleProcessID >= '$phase'");
        if ($query->getCount() == 0) {
            return $studyCodes;
        } else {
            foreach ($query->getResults() as $code) {
                array_push($studyCodes, $code->StudyCode);
            }
            return $studyCodes;
        }
    }

    public function getEditInfo($studyCode) {
        $this->_db->select('data_extraction_t', array('StudyCode', '=', $studyCode));
        foreach ($this->_db->getResults() as $extraction) {
            $isLiteratureReview = $extraction->IsLiteratureReview;
            $isTheoryForStudy = $extraction->IsTheoryForStudy;
            $isLearningOutcomeForStudy = $extraction->IsLearningOutcomeForStudy;
            $isObjectiveClear = $extraction->IsObjectiveClear;
            $isStudyDesignReported = $extraction->IsStudyDesignReported;
            $isDesignAppropriate = $extraction->IsDesignAppropriate;
            $isMethodsBias = $extraction->IsMethodsBias;
            $isCorrespondenceRequired = $extraction->IsCorrespondenceRequired;
            $isLearnerOutcomeExists = $extraction->IsLearnerOutcomeExists;
            $isStudyDesignExists = $extraction->IsStudyDesignExists;
            $isParticipantExists = $extraction->IsParticipantExists;
            $isGenderExists = $extraction->IsGenderExists;
            $isProfessionExists = $extraction->IsProfessionExists;
            $isEduIntervDescriptionClear = $extraction->IsEduIntervDescriptionClear;
            $isTeachingMethodExists = $extraction->IsTeachingMethodExists;
            $isInstructionalRescourceExists = $extraction->IsInstructionalRescourceExists;
            $isAssessmentIntervExists = $extraction->IsAssessmentIntervExists;
            $theoryStudyYesDescrpt = $extraction->TheoryStudyYesDescrpt;
            $learningOutcomeForStudyYesDescrpt = $extraction->LearningOutcomeForStudyYesDescrpt;
            $learnerOutcomeOtherDescrpt = $extraction->LearnerOutcomeOtherDescrpt;
            $studyDesignOtherDescrpt = $extraction->StudyDesignOtherDescrpt;
            $isDesignAppropriateUnsureDescrpt = $extraction->IsDesignAppropriateUnsureDescrpt;
            $instrumentsUsed = $extraction->InstrumentsUsed;
            $totalStudyDuration = $extraction->TotalStudyDuration;
            $totalStudyDurationSpecification = $extraction->TotalStudyDurationSpecification;
            $methodsBiasYesDescrpt = $extraction->MethodsBiasYesDescrpt;
            $methodsBiasUnsureDescrpt = $extraction->MethodsBiasUnsureDescrpt;
            $totalParticipants = $extraction->TotalParticipants;
            $totalParticipantsSpecification = $extraction->TotalParticipantsSpecification;
            $participantOtherDescrpt = $extraction->ParticipantOtherDescrpt;
            $participantStudentAcademicLevel = $extraction->ParticipantStudentAcademicLevel;
            $ageDemographic = $extraction->AgeDemographic;
            $ageDemographicSpecification = $extraction->AgeDemographicSpecification;
            $genderOtherDescrpt = $extraction->GenderOtherDescrpt;
            $professionOtherDescrpt = $extraction->ProfessionOtherDescrpt;
            $eduIntervDescriptionUnsureDescrpt = $extraction->EduIntervDescriptionUnsureDescrpt;
            $numberIntervGroups = $extraction->NumberIntervGroups;
            $numberIntervGroupsSpecification = $extraction->NumberIntervGroupsSpecification;
            $numberParticipantsInIntervGroups = $extraction->NumberParticipantsInIntervGroups;
            $numberParticipantsInIntervGroupsSpecification = $extraction->NumberParticipantsInIntervGroupsSpecification;
            $topicsCover = $extraction->TopicsCover;
            $topicsCoverSpecification = $extraction->TopicsCoverSpecification;
            $teachingMethodCreditHrs = $extraction->TeachingMethodCreditHrs;
            $teachingMethodOtherDescrpt = $extraction->TeachingMethodOtherDescrpt;
            $instructionalRescourceOtherDescrpt = $extraction->InstructionalRescourceOtherDescrpt;
            $assessmentIntervOther = $extraction->AssessmentIntervOther;
            $timePointsCollected = $extraction->TimePointsCollected;
            $timePointsCollectedSpecification = $extraction->TimePointsCollectedSpecification;
            $unitOfMeasurement = $extraction->UnitOfMeasurement;
            $unitOfMeasurementSpecification = $extraction->UnitOfMeasurementSpecification;
            $scaleLimitInterpretation = $extraction->ScaleLimitInterpretation;
            $scaleLimitInterpretationSpecification = $extraction->ScaleLimitInterpretationSpecification;
            $evaluationCriteria = $extraction->EvaluationCriteria;
            $evaluationCriteriaSpecifciation = $extraction->EvaluationCriteriaSpecifciation;
            $sampleSize = $extraction->SampleSize;
            $sampleSizeSpecification = $extraction->SampleSizeSpecification;
            $responseRate = $extraction->ResponseRate;
            $responseRateSpecifcation = $extraction->ResponseRateSpecifcation;
            $missingParticipants = $extraction->MissingParticipants;
            $missingParticipantsSpecification = $extraction->MissingParticipantsSpecification;
            $summaryDataMean = $extraction->SummaryDataMean;
            $summaryDataCI = $extraction->SummaryDataCI;
            $summaryDataSD = $extraction->SummaryDataSD;
            $summaryDataPValue = $extraction->SummaryDataPValue;
            $summaryDataOther = $extraction->SummaryDataOther;
            $subgroupAnalyses = $extraction->SubgroupAnalyses;
            $authorConclusion = $extraction->AuthorConclusion;
            $studyLimitation = $extraction->StudyLimitation;
            $authorComments = $extraction->AuthorComments;
            $referenceToStudies = $extraction->ReferenceToStudies;
        }
        
        return array(
            "IsLiteratureReview" => $isLiteratureReview,
            "IsTheoryForStudy" => $isTheoryForStudy,
            "IsLearningOutcomeForStudy" => $isLearningOutcomeForStudy,
            "IsObjectiveClear" => $isObjectiveClear,
            "IsStudyDesignReported" => $isStudyDesignReported,
            "IsDesignAppropriate" => $isDesignAppropriate,
            "IsMethodsBias" => $isMethodsBias,
            "IsCorrespondenceRequired" => $isCorrespondenceRequired,
            "IsLearnerOutcomeExists" =>  $isLearnerOutcomeExists,
            "IsStudyDesignExists" => $isStudyDesignExists,
            "IsParticipantExists" => $isParticipantExists,
            "IsGenderExists" => $isGenderExists,
            "IsProfessionExists" => $isProfessionExists,
            "IsEduIntervDescriptionClear" => $isEduIntervDescriptionClear,
            "IsTeachingMethodExists" => $isTeachingMethodExists,
            "IsInstructionalRescourceExists" => $isInstructionalRescourceExists,
            "IsAssessmentIntervExists" => $isAssessmentIntervExists,
            "TheoryStudyYesDescrpt" => $theoryStudyYesDescrpt,
            "LearningOutcomeForStudyYesDescrpt" => $learningOutcomeForStudyYesDescrpt,
            "LearnerOutcomeOtherDescrpt" => $learnerOutcomeOtherDescrpt,
            "StudyDesignOtherDescrpt" => $studyDesignOtherDescrpt,
            "IsDesignAppropriateUnsureDescrpt" => $isDesignAppropriateUnsureDescrpt,
            "InstrumentsUsed" => $instrumentsUsed,
            "TotalStudyDuration" => $totalStudyDuration,
            "TotalStudyDurationSpecification" => $totalStudyDurationSpecification,
            "MethodsBiasYesDescrpt" => $methodsBiasYesDescrpt,
            "MethodsBiasUnsureDescrpt" => $methodsBiasUnsureDescrpt,
            "TotalParticipants" => $totalParticipants,
            "TotalParticipantsSpecification" => $totalParticipantsSpecification,
            "ParticipantOtherDescrpt" => $participantOtherDescrpt,
            "ParticipantStudentAcademicLevel" => $participantStudentAcademicLevel,
            "AgeDemographic" => $ageDemographic,
            "AgeDemographicSpecification" => $ageDemographicSpecification,
            "GenderOtherDescrpt" => $genderOtherDescrpt,
            "ProfessionOtherDescrpt" => $professionOtherDescrpt,
            "EduIntervDescriptionUnsureDescrpt" => $eduIntervDescriptionUnsureDescrpt,
            "NumberIntervGroups" => $numberIntervGroups,
            "NumberIntervGroupsSpecification" => $numberIntervGroupsSpecification,
            "NumberParticipantsInIntervGroups" => $numberParticipantsInIntervGroups,
            "NumberParticipantsInIntervGroupsSpecification" => $numberParticipantsInIntervGroupsSpecification,
            "TopicsCover" => $topicsCover,
            "TopicsCoverSpecification" => $topicsCoverSpecification,
            "TeachingMethodCreditHrs" => $teachingMethodCreditHrs,
            "TeachingMethodOtherDescrpt" => $teachingMethodOtherDescrpt,
            "InstructionalRescourceOtherDescrpt" => $instructionalRescourceOtherDescrpt,
            "AssessmentIntervOther" => $assessmentIntervOther,
            "TimePointsCollected" => $timePointsCollected,
            "TimePointsCollectedSpecification" => $timePointsCollectedSpecification,
            "UnitOfMeasurement" => $unitOfMeasurement,
            "UnitOfMeasurementSpecification" => $unitOfMeasurementSpecification,
            "ScaleLimitInterpretation" => $scaleLimitInterpretation,
            "ScaleLimitInterpretationSpecification" => $scaleLimitInterpretationSpecification,
            "EvaluationCriteria" => $evaluationCriteria,
            "EvaluationCriteriaSpecifciation" => $evaluationCriteriaSpecifciation,
            "SampleSize" => $sampleSize,
            "SampleSizeSpecification" => $sampleSizeSpecification,
            "ResponseRate" => $responseRate,
            "ResponseRateSpecifcation" => $responseRateSpecifcation,
            "MissingParticipants" => $missingParticipants,
            "MissingParticipantsSpecification" => $missingParticipantsSpecification,
            "SummaryDataMean" => $summaryDataMean,
            "SummaryDataCI" => $summaryDataCI,
            "SummaryDataSD" => $summaryDataSD,
            "SummaryDataPValue" => $summaryDataPValue,
            "SummaryDataOther" => $summaryDataOther,
            "SubgroupAnalyses" => $subgroupAnalyses,
            "AuthorConclusion" => $authorConclusion,
            "StudyLimitation" => $studyLimitation,
            "AuthorComments" => $authorComments,
            "ReferenceToStudies" => $referenceToStudies
        );
    }

    public function getViewInfo($studyCode) {
        $this->_db->select('data_extraction_t', array('StudyCode', '=', $studyCode));
        foreach ($this->_db->getResults() as $extraction) {
            $isLiteratureReview = $extraction->IsLiteratureReview;
            $isTheoryForStudy = $extraction->IsTheoryForStudy;
            $isLearningOutcomeForStudy = $extraction->IsLearningOutcomeForStudy;
            $isObjectiveClear = $extraction->IsObjectiveClear;
            $isStudyDesignReported = $extraction->IsStudyDesignReported;
            $isDesignAppropriate = $extraction->IsDesignAppropriate;
            $isMethodsBias = $extraction->IsMethodsBias;
            $isCorrespondenceRequired = $extraction->IsCorrespondenceRequired;
            $isLearnerOutcomeExists = $extraction->IsLearnerOutcomeExists;
            $isStudyDesignExists = $extraction->IsStudyDesignExists;
            $isParticipantExists = $extraction->IsParticipantExists;
            $isGenderExists = $extraction->IsGenderExists;
            $isProfessionExists = $extraction->IsProfessionExists;
            $isEduIntervDescriptionClear = $extraction->IsEduIntervDescriptionClear;
            $isTeachingMethodExists = $extraction->IsTeachingMethodExists;
            $isInstructionalRescourceExists = $extraction->IsInstructionalRescourceExists;
            $isAssessmentIntervExists = $extraction->IsAssessmentIntervExists;
            $theoryStudyYesDescrpt = $extraction->TheoryStudyYesDescrpt;
            $learningOutcomeForStudyYesDescrpt = $extraction->LearningOutcomeForStudyYesDescrpt;
            $learnerOutcomeOtherDescrpt = $extraction->LearnerOutcomeOtherDescrpt;
            $studyDesignOtherDescrpt = $extraction->StudyDesignOtherDescrpt;
            $isDesignAppropriateUnsureDescrpt = $extraction->IsDesignAppropriateUnsureDescrpt;
            $instrumentsUsed = $extraction->InstrumentsUsed;
            $totalStudyDuration = $extraction->TotalStudyDuration;
            $totalStudyDurationSpecification = $extraction->TotalStudyDurationSpecification;
            $methodsBiasYesDescrpt = $extraction->MethodsBiasYesDescrpt;
            $methodsBiasUnsureDescrpt = $extraction->MethodsBiasUnsureDescrpt;
            $totalParticipants = $extraction->TotalParticipants;
            $totalParticipantsSpecification = $extraction->TotalParticipantsSpecification;
            $participantOtherDescrpt = $extraction->ParticipantOtherDescrpt;
            $participantStudentAcademicLevel = $extraction->ParticipantStudentAcademicLevel;
            $ageDemographic = $extraction->AgeDemographic;
            $ageDemographicSpecification = $extraction->AgeDemographicSpecification;
            $genderOtherDescrpt = $extraction->GenderOtherDescrpt;
            $professionOtherDescrpt = $extraction->ProfessionOtherDescrpt;
            $eduIntervDescriptionUnsureDescrpt = $extraction->EduIntervDescriptionUnsureDescrpt;
            $numberIntervGroups = $extraction->NumberIntervGroups;
            $numberIntervGroupsSpecification = $extraction->NumberIntervGroupsSpecification;
            $numberParticipantsInIntervGroups = $extraction->NumberParticipantsInIntervGroups;
            $numberParticipantsInIntervGroupsSpecification = $extraction->NumberParticipantsInIntervGroupsSpecification;
            $topicsCover = $extraction->TopicsCover;
            $topicsCoverSpecification = $extraction->TopicsCoverSpecification;
            $teachingMethodCreditHrs = $extraction->TeachingMethodCreditHrs;
            $teachingMethodOtherDescrpt = $extraction->TeachingMethodOtherDescrpt;
            $instructionalRescourceOtherDescrpt = $extraction->InstructionalRescourceOtherDescrpt;
            $assessmentIntervOther = $extraction->AssessmentIntervOther;
            $timePointsCollected = $extraction->TimePointsCollected;
            $timePointsCollectedSpecification = $extraction->TimePointsCollectedSpecification;
            $unitOfMeasurement = $extraction->UnitOfMeasurement;
            $unitOfMeasurementSpecification = $extraction->UnitOfMeasurementSpecification;
            $scaleLimitInterpretation = $extraction->ScaleLimitInterpretation;
            $scaleLimitInterpretationSpecification = $extraction->ScaleLimitInterpretationSpecification;
            $evaluationCriteria = $extraction->EvaluationCriteria;
            $evaluationCriteriaSpecifciation = $extraction->EvaluationCriteriaSpecifciation;
            $sampleSize = $extraction->SampleSize;
            $sampleSizeSpecification = $extraction->SampleSizeSpecification;
            $responseRate = $extraction->ResponseRate;
            $responseRateSpecifcation = $extraction->ResponseRateSpecifcation;
            $missingParticipants = $extraction->MissingParticipants;
            $missingParticipantsSpecification = $extraction->MissingParticipantsSpecification;
            $summaryDataMean = $extraction->SummaryDataMean;
            $summaryDataCI = $extraction->SummaryDataCI;
            $summaryDataSD = $extraction->SummaryDataSD;
            $summaryDataPValue = $extraction->SummaryDataPValue;
            $summaryDataOther = $extraction->SummaryDataOther;
            $subgroupAnalyses = $extraction->SubgroupAnalyses;
            $authorConclusion = $extraction->AuthorConclusion;
            $studyLimitation = $extraction->StudyLimitation;
            $authorComments = $extraction->AuthorComments;
            $referenceToStudies = $extraction->ReferenceToStudies;
        }

        $outcomeList = FormHelper::getDropDownListNames($studyCode, $isLearnerOutcomeExists, 'data_extract_learner_outcome_t', 'learner_outcome_t', 'OutcomeID', 'Outcome');
        $isStudyDesignExists = FormHelper::getDropDownListNames($studyCode, $isStudyDesignExists, 'data_extract_study_design_t', 'study_design_t', 'StudyDesignID', 'StudyDesign');
        $isParticipantExists = FormHelper::getDropDownListNames($studyCode, $isParticipantExists, 'data_extract_participant_t', 'participant_t', 'ParticipantID', 'Participant');
        $isGenderExists = FormHelper::getDropDownListNames($studyCode, $isGenderExists, 'data_extract_gender_t', 'gender_t', 'GenderID', 'Gender');
        $isProfessionExists = FormHelper::getDropDownListNames($studyCode, $isProfessionExists, 'data_extract_profession_t', 'profession_t', 'ProfessionID', 'Profession');
        $isTeachingMethodExists = FormHelper::getDropDownListNames($studyCode, $isTeachingMethodExists, 'data_extract_teaching_method_t', 'teaching_method_t', 'TeachingMethodID', 'TeachingMethod');
        $isInstructionalRescourceExists = FormHelper::getDropDownListNames($studyCode, $isInstructionalRescourceExists, 'data_extract_instructional_resource_t', 'instructional_resource_t', 'InstructionalResourceID', 'InstructionalResource');
        $isAssessmentIntervExists = FormHelper::getDropDownListNames($studyCode, $isAssessmentIntervExists, 'data_extract_assessment_intervention_t', 'assessment_intervention_t', 'AssessmentInterventionID', 'AssessmentIntervention');

        return array(
            "IsLiteratureReview" => $isLiteratureReview,
            "IsTheoryForStudy" => $isTheoryForStudy,
            "IsLearningOutcomeForStudy" => $isLearningOutcomeForStudy,
            "IsObjectiveClear" => $isObjectiveClear,
            "IsStudyDesignReported" => $isStudyDesignReported,
            "IsDesignAppropriate" => $isDesignAppropriate,
            "IsMethodsBias" => $isMethodsBias,
            "IsCorrespondenceRequired" => $isCorrespondenceRequired,
            "IsLearnerOutcomeExists" => $outcomeList,
            "IsStudyDesignExists" => $isStudyDesignExists,
            "IsParticipantExists" => $isParticipantExists,
            "IsGenderExists" => $isGenderExists,
            "IsProfessionExists" => $isProfessionExists,
            "IsEduIntervDescriptionClear" => $isEduIntervDescriptionClear,
            "IsTeachingMethodExists" => $isTeachingMethodExists,
            "IsInstructionalRescourceExists" => $isInstructionalRescourceExists,
            "IsAssessmentIntervExists" => $isAssessmentIntervExists,
            "TheoryStudyYesDescrpt" => FormHelper::checkIfDummyValue($theoryStudyYesDescrpt),
            "LearningOutcomeForStudyYesDescrpt" => FormHelper::checkIfDummyValue($learningOutcomeForStudyYesDescrpt),
            "LearnerOutcomeOtherDescrpt" => FormHelper::checkIfDummyValue($learnerOutcomeOtherDescrpt),
            "StudyDesignOtherDescrpt" => FormHelper::checkIfDummyValue($studyDesignOtherDescrpt),
            "IsDesignAppropriateUnsureDescrpt" => FormHelper::checkIfDummyValue($isDesignAppropriateUnsureDescrpt),
            "InstrumentsUsed" => FormHelper::checkIfDummyValue($instrumentsUsed),
            "TotalStudyDuration" => FormHelper::checkIfDummyValue($totalStudyDuration),
            "TotalStudyDurationSpecification" => FormHelper::checkIfDummyValue($totalStudyDurationSpecification),
            "MethodsBiasYesDescrpt" => FormHelper::checkIfDummyValue($methodsBiasYesDescrpt),
            "MethodsBiasUnsureDescrpt" => FormHelper::checkIfDummyValue($methodsBiasUnsureDescrpt),
            "TotalParticipants" => FormHelper::checkIfDummyValue($totalParticipants),
            "TotalParticipantsSpecification" => FormHelper::checkIfDummyValue($totalParticipantsSpecification),
            "ParticipantOtherDescrpt" => FormHelper::checkIfDummyValue($participantOtherDescrpt),
            "ParticipantStudentAcademicLevel" => FormHelper::checkIfDummyValue($participantStudentAcademicLevel),
            "AgeDemographic" => FormHelper::checkIfDummyValue($ageDemographic),
            "AgeDemographicSpecification" => FormHelper::checkIfDummyValue($ageDemographicSpecification),
            "GenderOtherDescrpt" => FormHelper::checkIfDummyValue($genderOtherDescrpt),
            "ProfessionOtherDescrpt" => FormHelper::checkIfDummyValue($professionOtherDescrpt),
            "EduIntervDescriptionUnsureDescrpt" => FormHelper::checkIfDummyValue($eduIntervDescriptionUnsureDescrpt),
            "NumberIntervGroups" => FormHelper::checkIfDummyValue($numberIntervGroups),
            "NumberIntervGroupsSpecification" => FormHelper::checkIfDummyValue($numberIntervGroupsSpecification),
            "NumberParticipantsInIntervGroups" => FormHelper::checkIfDummyValue($numberParticipantsInIntervGroups),
            "NumberParticipantsInIntervGroupsSpecification" => FormHelper::checkIfDummyValue($numberParticipantsInIntervGroupsSpecification),
            "TopicsCover" => FormHelper::checkIfDummyValue($topicsCover),
            "TopicsCoverSpecification" => FormHelper::checkIfDummyValue($topicsCoverSpecification),
            "TeachingMethodCreditHrs" => FormHelper::checkIfDummyValue($teachingMethodCreditHrs),
            "TeachingMethodOtherDescrpt" => FormHelper::checkIfDummyValue($teachingMethodOtherDescrpt),
            "InstructionalRescourceOtherDescrpt" => FormHelper::checkIfDummyValue($instructionalRescourceOtherDescrpt),
            "AssessmentIntervOther" => FormHelper::checkIfDummyValue($assessmentIntervOther),
            "TimePointsCollected" => FormHelper::checkIfDummyValue($timePointsCollected),
            "TimePointsCollectedSpecification" => FormHelper::checkIfDummyValue($timePointsCollectedSpecification),
            "UnitOfMeasurement" => FormHelper::checkIfDummyValue($unitOfMeasurement),
            "UnitOfMeasurementSpecification" => FormHelper::checkIfDummyValue($unitOfMeasurementSpecification),
            "ScaleLimitInterpretation" => FormHelper::checkIfDummyValue($scaleLimitInterpretation),
            "ScaleLimitInterpretationSpecification" => FormHelper::checkIfDummyValue($scaleLimitInterpretationSpecification),
            "EvaluationCriteria" => FormHelper::checkIfDummyValue($evaluationCriteria),
            "EvaluationCriteriaSpecifciation" => FormHelper::checkIfDummyValue($evaluationCriteriaSpecifciation),
            "SampleSize" => FormHelper::checkIfDummyValue($sampleSize),
            "SampleSizeSpecification" => FormHelper::checkIfDummyValue($sampleSizeSpecification),
            "ResponseRate" => FormHelper::checkIfDummyValue($responseRate),
            "ResponseRateSpecifcation" => FormHelper::checkIfDummyValue($responseRateSpecifcation),
            "MissingParticipants" => FormHelper::checkIfDummyValue($missingParticipants),
            "MissingParticipantsSpecification" => FormHelper::checkIfDummyValue($missingParticipantsSpecification),
            "SummaryDataMean" => FormHelper::checkIfDummyValue($summaryDataMean),
            "SummaryDataCI" => FormHelper::checkIfDummyValue($summaryDataCI),
            "SummaryDataSD" => FormHelper::checkIfDummyValue($summaryDataSD),
            "SummaryDataPValue" => FormHelper::checkIfDummyValue($summaryDataPValue),
            "SummaryDataOther" => FormHelper::checkIfDummyValue($summaryDataOther),
            "SubgroupAnalyses" => FormHelper::checkIfDummyValue($subgroupAnalyses),
            "AuthorConclusion" => FormHelper::checkIfDummyValue($authorConclusion),
            "StudyLimitation" => FormHelper::checkIfDummyValue($studyLimitation),
            "AuthorComments" => FormHelper::checkIfDummyValue($authorComments),
            "ReferenceToStudies" => FormHelper::checkIfDummyValue($referenceToStudies)
        );
    }

    public function getReviewerNamesExceptSpecficReviewer($reviewerID, $reviewerIDs = array()) {
        //remove reviewerID if from array
        $key = array_search($reviewerID, $reviewerIDs);
        unset($reviewerIDs[$key]);

        //copy old array to new array without exception id
        $reviewerIDsException = array();
        for ($i = 0; $i <= count($reviewerIDs); $i++) {
            if (array_key_exists($i, $reviewerIDs)) {
                array_push($reviewerIDsException, $reviewerIDs[$i]);
            }
        }

        $reviewerNames = array();
        for ($i = 0; $i < count($reviewerIDsException); $i++) {
            $this->_db->select('reviewer_t', array('ReviewerID', '=', $reviewerIDsException[$i]));
            foreach ($this->_db->getResults() as $reviewerName) {
                array_push($reviewerNames, $reviewerName->FirstName . ' ' . $reviewerName->LastName);
            }
        }
        return $reviewerNames;
    }

    public function deleteTablesRowsForCode($studyCode, $tables = array()) {
        for ($i = 0; $i < count($tables); $i++) {
            $this->deletePhaseSubtableRows($studyCode, $tables[$i]);
        }
    }
}
