USE cultural_competence;

CREATE TABLE IF NOT EXISTS score_t(
    Score       TINYINT      UNSIGNED        NOT NULL,
    Rating      VARCHAR(10),

   PRIMARY KEY(Score)
);

INSERT INTO score_t(Score, Rating)
VALUES("0", "Not stated"), ("1", "Poor"), ("2", "Good"), ("3", "High");

CREATE TABLE IF NOT EXISTS quest_appraise_t(
    StudyCode                VARCHAR(10)            NOT NULL,
    ReviewerID               TINYINT      UNSIGNED,
    IsPRDescription          CHAR(3)                         DEFAULT 'No',
    IsPRJustification        CHAR(3)                         DEFAULT 'No',
    IsPRClarification        CHAR(3)                         DEFAULT 'No',
    QDQualityScore           TINYINT(1)   UNSIGNED  DEFAULT '0', 
    QDUtilityScore           TINYINT(1)   UNSIGNED  DEFAULT '0',
    QDExtentScore            TINYINT(1)   UNSIGNED  DEFAULT '0',
    QDStrengthScore          TINYINT(1)   UNSIGNED  DEFAULT '0',
    QDTargetScore            TINYINT(1)   UNSIGNED  DEFAULT '0',
    QDSettingScore           TINYINT(1)   UNSIGNED  DEFAULT '0',
    PRDescriptionComments    VARCHAR(2500)          DEFAULT 'DummyValue',
    PRJustificationComments  VARCHAR(2500)          DEFAULT 'DummyValue',
    PRClarificationComments  VARCHAR(2500)          DEFAULT 'DummyValue',
    QDQualityComments        VARCHAR(2500)          DEFAULT 'DummyValue',
    QDUtilityComments        VARCHAR(2500)          DEFAULT 'DummyValue',
    QDExtentComments         VARCHAR(2500)          DEFAULT 'DummyValue',
    QDStrengthComments       VARCHAR(2500)          DEFAULT 'DummyValue',
    QDTargetComments         VARCHAR(2500)          DEFAULT 'DummyValue',
    QDSettingComments        VARCHAR(2500)          DEFAULT 'DummyValue',

    PRIMARY KEY(StudyCode, ReviewerID),
    FOREIGN KEY(StudyCode)        REFERENCES  study_inventory_t(StudyCode),
    FOREIGN KEY(ReviewerID)       REFERENCES  reviewer_t(ReviewerID)
);