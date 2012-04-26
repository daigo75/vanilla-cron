/**
 * Retrieve the score for User Answers
 */
DROP VIEW IF EXISTS `gdn_v_topcontributors_answer`;

DELIMITER $$

CREATE VIEW `gdn_v_topcontributors_answer` AS
SELECT
    A.ActivityID
    ,A.DateInserted AS AcceptanceDateTime
    ,A.ActivityUserID AS AcceptedBy_UserID
    ,A.RegardingUserID AS AnsweredBy_UserID
    -- The following can be used for debugging purposes, but they are not needed
    -- in production.
--     ,U1.Name AS UserWhoAnswered
--     ,U2.Name AS UserWhoAcceptedAnswer
FROM
    gdn_activity A
    -- The following can be used for debugging purposes, but they are not needed
    -- in production.
--     JOIN
--     gdn_user U1 ON
--         (U1.UserID = A.RegardingUserID)
--     JOIN
--     gdn_user U2 ON
--         (U2.UserID = A.ActivityUserID)
WHERE
    -- Type 24 = Accepted Answer
    (A.ActivityTypeID = 24);
$$

DELIMITER ;

SELECT *
FROM gdn_v_topcontributors_answer;