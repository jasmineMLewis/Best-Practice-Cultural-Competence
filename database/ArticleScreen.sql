USE cultural_competence;

CREATE TABLE IF NOT EXISTS article_screen_checklist_decision_t(
    DecisionID     TINYINT         UNSIGNED   NOT NULL    AUTO_INCREMENT,
    Decision       VARCHAR(10)                            UNIQUE,

    PRIMARY KEY(DecisionID)
);

INSERT INTO article_screen_checklist_decision_t(Decision)
VALUES
    ("Include"), ("Exclude"), ("Flag");

CREATE TABLE IF NOT EXISTS article_screen_checklist_final_decision_t(
    StudyCode      VARCHAR(10)                NOT NULL              UNIQUE,
    DecisionID     TINYINT         UNSIGNED,

    PRIMARY KEY(StudyCode, DecisionID),
    FOREIGN KEY(StudyCode)        REFERENCES  study_inventory_t(StudyCode),
    FOREIGN KEY(DecisionID)       REFERENCES  article_screen_checklist_decision_t(DecisionID)
);

CREATE TABLE IF NOT EXISTS article_screen_checklist_t(
    StudyCode                                   VARCHAR(10)           NOT NULL,
    ReviewerID                                  TINYINT      UNSIGNED NOT NULL,
    IsPublishedWithinTimeFrame                  VARCHAR(6),
    IsPopProvideDirectPatientContact            VARCHAR(6),
    IsPeerReviewed                              VARCHAR(6),
    IsDescribedPlanEducationIntervention        VARCHAR(6),
    IsCulturalCompetenceTopicOriginRelated      VARCHAR(6),
    DecisionID                                  TINYINT      UNSIGNED,
    DecisionComments                            VARCHAR(5000),

    PRIMARY KEY(StudyCode, ReviewerID),
    FOREIGN KEY(StudyCode)        REFERENCES  study_inventory_t(StudyCode),
    FOREIGN KEY(ReviewerID)       REFERENCES  reviewer_t(ReviewerID),
    FOREIGN KEY(DecisionID)       REFERENCES  
                                 article_screen_checklist_decision_t(DecisionID)
);