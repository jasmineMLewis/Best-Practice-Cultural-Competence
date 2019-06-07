USE cultural_competence;

CREATE TABLE IF NOT EXISTS learner_outcome_t (
    OutcomeID       TINYINT         UNSIGNED   NOT NULL    AUTO_INCREMENT,
    Outcome         VARCHAR(45)                            UNIQUE,

    PRIMARY KEY(OutcomeID)
);

INSERT INTO learner_outcome_t(Outcome)
VALUES  ("None"), ("Learners reaction/Opinions"), ("Awareness"), ("Attiudes/Perceptions"), 
        ("Knowledge"), ("Skills"), ("Behavior"), ("Practice/Patient outcome"), 
        ("Other");

CREATE TABLE IF NOT EXISTS study_design_t (
    StudyDesignID       TINYINT         UNSIGNED   NOT NULL    AUTO_INCREMENT,
    StudyDesign         VARCHAR(45)                            UNIQUE,

    PRIMARY KEY(StudyDesignID)
);

INSERT INTO study_design_t(StudyDesign)
VALUES ("Not specified"), ("Randomized controlled clinical trials"),
       ("Open effect studies"), ("Quasi-experimental studies"), ("Meta-analysis"), 
       ("Descriptive studies"), ("Qualitative"), ("Reviews"), ("White Papers"),
       ("Expert task force/committee reports"), ("Other");

CREATE TABLE IF NOT EXISTS participant_t(
    ParticipantID    TINYINT         UNSIGNED   NOT NULL    AUTO_INCREMENT,
    Participant      VARCHAR(45)                            UNIQUE,

    PRIMARY KEY(ParticipantID)
);

INSERT INTO participant_t(Participant)
VALUES ("Not Specified"), ("Student"), ("Resident"), ("Provider"), ("Fellow"), 
       ("Other");

CREATE TABLE IF NOT EXISTS gender_t(
    GenderID    TINYINT         UNSIGNED   NOT NULL    AUTO_INCREMENT,
    Gender      VARCHAR(45)                            UNIQUE,

    PRIMARY KEY(GenderID)
);

INSERT INTO gender_t(Gender)
VALUES ("Not Specified"), ("Male"), ("Female"), ("Other");

CREATE TABLE IF NOT EXISTS teaching_method_t(
    TeachingMethodID      TINYINT     UNSIGNED    NOT NULL     AUTO_INCREMENT,
    TeachingMethod        VARCHAR(100)                         UNIQUE,

    PRIMARY KEY(TeachingMethodID)
);

INSERT INTO teaching_method_t(TeachingMethod)
VALUES ("Not Specified"),("Curriculum development"),("Continue education"), ("Course"), ("Lecture"), 
      ("Workshop"), ("Small Discussion Groups"), ("Team-based learning"), ("Cultural Immersion"),
      ("International exiperence"), ("Interative online"), ("Other");

CREATE TABLE IF NOT EXISTS instructional_resource_t(
    InstructionalResourceID    TINYINT     UNSIGNED   NOT NULL   AUTO_INCREMENT,
    InstructionalResource      VARCHAR(100)                      UNIQUE,
    
    PRIMARY KEY(InstructionalResourceID)
);

INSERT INTO instructional_resource_t(InstructionalResource)
VALUES ("Not Specifed"), ("PowerPoints, handouts"), ("Videos, audios"), 
       ("Readings"), ("Case studies"), ("Discussion"), 
       ("Interactive online and computer-assisted"), 
       ("Structured group activites or games"), ("Other");

CREATE TABLE IF NOT EXISTS assessment_intervention_t(
    AssessmentInterventionID    TINYINT     UNSIGNED   NOT NULL  AUTO_INCREMENT,
    AssessmentIntervention      VARCHAR(100)                     UNIQUE,

    PRIMARY KEY(AssessmentInterventionID)
);

INSERT INTO assessment_intervention_t(AssessmentIntervention)
VALUES ("Not Specified"), ("Pre-post tests"), ("Self-racting scales"), ("Self-reflections"), ("Course evaluations"), 
      ("Essays, written reports"), ("Quizzes, tests, exams"), ("Role-playing"), 
      ("Direct observations by external observer"), ("Standardized/virtual patients"),
      ("Patients' rating of student performance"), ("Video-recorded encounters rated by independent observers"),
      ("Objective-structured clinical examinations"), ("Patient satisfaction"), ("Other");

CREATE TABLE IF NOT EXISTS data_extraction_t(
    StudyCode                                        VARCHAR(10)           NOT NULL,
    ReviewerID                                       TINYINT      UNSIGNED,
    IsLiteratureReview                               VARCHAR(10)           DEFAULT 'No',
    IsTheoryForStudy                                 VARCHAR(10)           DEFAULT 'No',
    IsLearningOutcomeForStudy                        VARCHAR(3)            DEFAULT 'No',
    IsObjectiveClear                                 VARCHAR(3)            DEFAULT 'No',
    IsStudyDesignReported                            VARCHAR(3)            DEFAULT 'No',
    IsDesignAppropriate                              VARCHAR(10)           DEFAULT 'No',
    IsMethodsBias                                    VARCHAR(10)           DEFAULT 'No',
    IsCorrespondenceRequired                         VARCHAR(3)            DEFAULT 'No',
    IsLearnerOutcomeExists                           BOOLEAN               DEFAULT '0',
    IsStudyDesignExists                              BOOLEAN               DEFAULT '0',
    IsParticipantExists                              BOOLEAN               DEFAULT '0', 
    IsGenderExists                                   BOOLEAN               DEFAULT '0',
    IsProfessionExists                               BOOLEAN               DEFAULT '0',
    IsEduIntervDescriptionClear                      VARCHAR(10)           DEFAULT 'No',
    IsTeachingMethodExists                           BOOLEAN               DEFAULT '0',
    IsInstructionalRescourceExists                   BOOLEAN               DEFAULT '0',
    IsAssessmentIntervExists                         BOOLEAN               DEFAULT '0',
    TheoryStudyYesDescrpt                            VARCHAR(500)          DEFAULT 'DummyValue', 
    LearningOutcomeForStudyYesDescrpt                VARCHAR(500)          DEFAULT 'DummyValue',
    LearnerOutcomeOtherDescrpt                       VARCHAR(500)          DEFAULT 'DummyValue',
    StudyDesignOtherDescrpt                          VARCHAR(500)          DEFAULT 'DummyValue',
    IsDesignAppropriateUnsureDescrpt                 VARCHAR(500)          DEFAULT 'DummyValue',
    InstrumentsUsed                                  VARCHAR(500)          DEFAULT 'DummyValue',
    TotalStudyDuration                               VARCHAR(500)          DEFAULT 'DummyValue',
    TotalStudyDurationSpecification                  VARCHAR(20)           DEFAULT 'DummyValue',
    MethodsBiasYesDescrpt                            VARCHAR(500)          DEFAULT 'DummyValue',
    MethodsBiasUnsureDescrpt                         VARCHAR(500)          DEFAULT 'DummyValue',
    TotalParticipants                                VARCHAR(500)          DEFAULT 'DummyValue',
    TotalParticipantsSpecification                   VARCHAR(20)           DEFAULT 'DummyValue',
    ParticipantOtherDescrpt                          VARCHAR(20)           DEFAULT 'DummyValue',
    ParticipantStudentAcademicLevel                  VARCHAR(500)          DEFAULT 'DummyValue',
    AgeDemographic                                   VARCHAR(500)          DEFAULT 'DummyValue',
    AgeDemographicSpecification                      VARCHAR(20)           DEFAULT 'DummyValue',
    GenderOtherDescrpt                               VARCHAR(500)          DEFAULT 'DummyValue',
    ProfessionOtherDescrpt                           VARCHAR(500)          DEFAULT 'DummyValue',
    EduIntervDescriptionUnsureDescrpt                VARCHAR(500)          DEFAULT 'DummyValue',
    NumberIntervGroups                               VARCHAR(500)          DEFAULT 'DummyValue',
    NumberIntervGroupsSpecification                  VARCHAR(20)           DEFAULT 'DummyValue',
    NumberParticipantsInIntervGroups                 VARCHAR(500)          DEFAULT 'DummyValue',
    NumberParticipantsInIntervGroupsSpecification    VARCHAR(20)           DEFAULT 'DummyValue',
    TopicsCover                                      VARCHAR(500)         DEFAULT 'DummyValue',
    TopicsCoverSpecification                         VARCHAR(20)           DEFAULT 'DummyValue',
    TeachingMethodCreditHrs                          VARCHAR(500)          DEFAULT 'DummyValue',
    TeachingMethodOtherDescrpt                       VARCHAR(500)          DEFAULT 'DummyValue',
    InstructionalRescourceOtherDescrpt               VARCHAR(500)          DEFAULT 'DummyValue',
    AssessmentIntervOther                            VARCHAR(500)          DEFAULT 'DummyValue',
    TimePointsCollected                              VARCHAR(500)          DEFAULT 'DummyValue',
    TimePointsCollectedSpecification                 VARCHAR(20)           DEFAULT 'DummyValue',
    UnitOfMeasurement                                VARCHAR(500)          DEFAULT 'DummyValue',
    UnitOfMeasurementSpecification                   VARCHAR(20)           DEFAULT 'DummyValue',
    ScaleLimitInterpretation                         VARCHAR(500)          DEFAULT 'DummyValue',
    ScaleLimitInterpretationSpecification            VARCHAR(20)           DEFAULT 'DummyValue',
    EvaluationCriteria                               VARCHAR(500)          DEFAULT 'DummyValue',
    EvaluationCriteriaSpecifciation                  VARCHAR(20)           DEFAULT 'DummyValue',
    SampleSize                                       VARCHAR(500)          DEFAULT 'DummyValue',
    SampleSizeSpecification                          VARCHAR(20)           DEFAULT 'DummyValue',
    ResponseRate                                     VARCHAR(500)          DEFAULT 'DummyValue',
    ResponseRateSpecifcation                         VARCHAR(20)           DEFAULT 'DummyValue',
    MissingParticipants                              VARCHAR(500)          DEFAULT 'DummyValue',
    MissingParticipantsSpecification                 VARCHAR(500)          DEFAULT 'DummyValue',  
    SummaryDataMean                                  VARCHAR(500)          DEFAULT 'DummyValue',
    SummaryDataCI                                    VARCHAR(500)          DEFAULT 'DummyValue',
    SummaryDataSD                                    VARCHAR(500)          DEFAULT 'DummyValue',
    SummaryDataPValue                                VARCHAR(500)          DEFAULT 'DummyValue',    
    SummaryDataOther                                 VARCHAR(500)          DEFAULT 'DummyValue',
    SubgroupAnalyses                                 VARCHAR(500)          DEFAULT 'DummyValue',
    AuthorConclusion                                 VARCHAR(2500)         DEFAULT 'DummyValue',
    StudyLimitation                                  VARCHAR(2500)         DEFAULT 'DummyValue',
    AuthorComments                                   VARCHAR(2500)         DEFAULT 'DummyValue',
    ReferenceToStudies                               VARCHAR(2500)         DEFAULT 'DummyValue',

    PRIMARY KEY(StudyCode),
    FOREIGN KEY(StudyCode)           REFERENCES study_inventory_t(StudyCode),
    FOREIGN KEY(ReviewerID)          REFERENCES  reviewer_t(ReviewerID)
);

CREATE TABLE IF NOT EXISTS data_extract_learner_outcome_t(
    StudyCode       VARCHAR(10)                 NOT NULL,
    OutcomeID       TINYINT         UNSIGNED    NOT NULL,

    PRIMARY KEY(StudyCode,OutcomeID),
    FOREIGN KEY(StudyCode)           REFERENCES study_inventory_t(StudyCode),
    FOREIGN KEY(OutcomeID)           REFERENCES learner_outcome_t(OutcomeID)
);

CREATE TABLE IF NOT EXISTS data_extract_study_design_t(
    StudyCode       VARCHAR(10)                 NOT NULL,
    StudyDesignID   TINYINT         UNSIGNED    NOT NULL,

    PRIMARY KEY(StudyCode, StudyDesignID),
    FOREIGN KEY(StudyCode)           REFERENCES study_inventory_t(StudyCode),
    FOREIGN KEY(StudyDesignID)       REFERENCES study_design_t(StudyDesignID)
);

CREATE TABLE IF NOT EXISTS data_extract_participant_t(
    StudyCode       VARCHAR(10)                 NOT NULL,
    ParticipantID   TINYINT         UNSIGNED    NOT NULL,

    PRIMARY KEY(StudyCode, ParticipantID),
    FOREIGN KEY(StudyCode)           REFERENCES study_inventory_t(StudyCode),
    FOREIGN KEY(ParticipantID)       REFERENCES participant_t(ParticipantID)
);
    
CREATE TABLE IF NOT EXISTS data_extract_gender_t(
    StudyCode   VARCHAR(10)                NOT NULL,
    GenderID    TINYINT         UNSIGNED   NOT NULL,

    PRIMARY KEY(StudyCode, GenderID),
    FOREIGN KEY(StudyCode)           REFERENCES study_inventory_t(StudyCode),
    FOREIGN KEY(GenderID)            REFERENCES gender_t(GenderID)
);

CREATE TABLE IF NOT EXISTS data_extract_profession_t(
   StudyCode           VARCHAR(10)               NOT NULL,
   ProfessionID        TINYINT       UNSIGNED    NOT NULL,

   PRIMARY KEY(StudyCode, ProfessionID),
   FOREIGN KEY(StudyCode)           REFERENCES study_inventory_t(StudyCode),
   FOREIGN KEY(ProfessionID)        REFERENCES profession_t(ProfessionID)
);

CREATE TABLE IF NOT EXISTS data_extract_teaching_method_t(
   StudyCode           VARCHAR(10)               NOT NULL,
   TeachingMethodID    TINYINT       UNSIGNED    NOT NULL,

   PRIMARY KEY(StudyCode, TeachingMethodID),
   FOREIGN KEY(StudyCode)           REFERENCES study_inventory_t(StudyCode),
   FOREIGN KEY(TeachingMethodID)    REFERENCES teaching_method_t(TeachingMethodID)
);

CREATE TABLE IF NOT EXISTS data_extract_instructional_resource_t(
    StudyCode                  VARCHAR(10)               NOT NULL,
    InstructionalResourceID    TINYINT     UNSIGNED      NOT NULL,

    PRIMARY KEY(StudyCode, InstructionalResourceID),
    FOREIGN KEY(StudyCode)                  REFERENCES study_inventory_t(StudyCode),
    FOREIGN KEY(InstructionalResourceID)    REFERENCES instructional_resource_t(InstructionalResourceID)
);

CREATE TABLE IF NOT EXISTS data_extract_assessment_intervention_t(
    StudyCode                  VARCHAR(10)               NOT NULL,
    AssessmentInterventionID   TINYINT     UNSIGNED      NOT NULL,

    PRIMARY KEY(StudyCode, AssessmentInterventionID),
    FOREIGN KEY(StudyCode)                           REFERENCES study_inventory_t(StudyCode),
    FOREIGN KEY(AssessmentInterventionID)            REFERENCES assessment_intervention_t(AssessmentInterventionID)
);