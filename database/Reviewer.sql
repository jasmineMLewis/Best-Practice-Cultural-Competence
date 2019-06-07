CREATE DATABASE IF NOT EXISTS cultural_competence;

USE cultural_competence;

CREATE TABLE IF NOT EXISTS reviewer_t(
    ReviewerID  TINYINT         UNSIGNED  NOT NULL   AUTO_INCREMENT,
    FirstName   VARCHAR(50),
    LastName    VARCHAR(50),
    Email       VARCHAR(100)                                 UNIQUE,
    Password    VARCHAR(15)                                  DEFAULT 'Xavier01',
    IsAdmin     BOOLEAN                                      DEFAULT '0',
    IsDisabled  BOOLEAN                                      DEFAULT '0',

    PRIMARY KEY(ReviewerID)
);

INSERT INTO reviewer_t(FirstName, LastName, Email, Password, IsAdmin, IsDisabled)
VALUES
    ("User", "One", "user_one@gmail.com", "demo", "1", "0"),
    ("User", "Two", "user_two@gmail.com", "demo", "1", "0"),
    ("User", "Three", "user_three@gmail.com", "demo", "1", "0"),
    ("User", "Four", "user_four@gmail.com", "demo", "1", "0"),
    ("User", "Five", "user_five@gmail.com", "demo", "1", "0"),
    ("User", "Six", "user_six@gmail.com", "demo", "1", "0"),
    ("User", "Seven", "user_seven@gmail.com", "demo", "1", "0"),
    ("User", "Eight", "user_eight@gmail.com", "demo", "1", "0"),
    ("User", "Nine", "user_nine@gmail.com", "demo", "1", "0"),
    ("User", "Ten", "user_ten@gmail.com", "demo", "1", "0");

CREATE TABLE IF NOT EXISTS code_t(
    Code        CHAR(4)       NOT NULL       UNIQUE,
    Label       VARCHAR(50)                  UNIQUE,

    PRIMARY KEY(Code)
);

INSERT INTO code_t(Code, Label)
VALUES
    ("GISE", "Gender Identity and Sexuality"),
    ("CREO", "Culture, Race, Ethniciity and Origin"),
    ("LANG", "Language"),
    ("HDIS", "Health Disparities"),
    ("HLIT", "Health Literacy"),
    ("ASCC", "Assessment of Cultural Competence"),
    ("RESP", "Religion and Spirituality"),
    ("DISA", "Disabilities");

CREATE TABLE IF NOT EXISTS team_t(
    Code        CHAR(4),
    ReviewerID  TINYINT       UNSIGNED,

    PRIMARY KEY(Code, ReviewerID),
    FOREIGN KEY(Code)           REFERENCES code_t(Code),
    FOREIGN KEY(ReviewerID)     REFERENCES reviewer_t(ReviewerID)
);

INSERT INTO team_t(Code, ReviewerID)
VALUES
    ("GISE", "2"), ("GISE", "3"), ("GISE", "7"), ("GISE", "1"), 
    ("CREO", "1"), ("CREO", "4"), ("CREO", "5"), ("CREO", "9"), ("CREO", "6"), 
    ("CREO", "7"), ("CREO", "8"), ("CREO", "10"), ("LANG", "1"), ("HDIS", "1"), 
    ("HLIT", "1"), ("ASCC", "1"), ("RESP", "1"), ("DISA", "1");

CREATE TABLE IF NOT EXISTS team_admin_t(
    Code        CHAR(4),
    ReviewerID  TINYINT       UNSIGNED,

    PRIMARY KEY(Code, ReviewerID),
    FOREIGN KEY(Code)           REFERENCES code_t(Code),
    FOREIGN KEY(ReviewerID)     REFERENCES reviewer_t(ReviewerID)
);

INSERT INTO team_admin_t(Code, ReviewerID)
VALUES
    ("GISE", "1"), ("GISE", "2"), ("CREO", "1"), ("CREO", "4"),("LANG", "1"), 
    ("HDIS", "1"), ("HLIT", "1"), ("ASCC", "1"), ("RESP", "1"), ("DISA", "1");