USE cultural_competence;

CREATE TABLE IF NOT EXISTS kirkpatrick_rating_t(
    StudyCode                VARCHAR(10)            NOT NULL,
    ReviewerID               TINYINT      UNSIGNED  NOT NULL,
    IsLevelOne               CHAR(3)                DEFAULT 'No',
    IsLevelTwoA              CHAR(3)                DEFAULT 'No',
    IsLevelTwoB              CHAR(3)                DEFAULT 'No',
    IsLevelThreeA            CHAR(3)                DEFAULT 'No',
    IsLevelThreeB            CHAR(3)                DEFAULT 'No',
    IsLevelFourA             CHAR(3)                DEFAULT 'No',
    IsLevelFourB             CHAR(3)                DEFAULT 'No',
    LevelOneAComments        VARCHAR(2500)          DEFAULT 'DummyValue',
    LevelTwoAComments        VARCHAR(2500)          DEFAULT 'DummyValue',
    LevelTwoBComments        VARCHAR(2500)          DEFAULT 'DummyValue',
    LevelThreeAComments      VARCHAR(2500)          DEFAULT 'DummyValue',
    LevelThreeBComments      VARCHAR(2500)          DEFAULT 'DummyValue',
    LevelFourAComments       VARCHAR(2500)          DEFAULT 'DummyValue',
    LevelFourBComments       VARCHAR(2500)          DEFAULT 'DummyValue',

    PRIMARY KEY(StudyCode, ReviewerID),
    FOREIGN KEY(StudyCode)        REFERENCES  study_inventory_t(StudyCode),
    FOREIGN KEY(ReviewerID)       REFERENCES  reviewer_t(ReviewerID)
);