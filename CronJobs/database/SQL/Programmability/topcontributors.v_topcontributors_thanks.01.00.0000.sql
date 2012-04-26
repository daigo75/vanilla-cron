/**
 * Retrieve the score for User Answers
 */
DROP VIEW IF EXISTS `gdn_v_topcontributors_thanks`;

DELIMITER $$

CREATE VIEW `gdn_v_topcontributors_thanks` AS
SELECT
    TL.DateInserted AS ThanksDateTime
    ,TL.InsertUserID AS ThanksFrom_UserID
    ,TL.UserID AS ThanksTo_UserID
    ,TL.CommentID AS CommentID
    -- The following can be used for debugging purposes, but they are not needed
    -- in production.
--     ,U1.Name AS ThanksFrom_UserName
--     ,U2.Name AS ThanksTo_UserName
FROM
    vanilla.gdn_thankslog TL
    -- The following can be used for debugging purposes, but they are not needed
    -- in production.
--     JOIN
--     gdn_user U1 ON
--         (U1.UserID = TL.InsertedUserID)
--     JOIN
--     gdn_user U2 ON
--         (U2.UserID = TL.UserID)
;
$$

DELIMITER ;

SELECT *
FROM gdn_v_topcontributors_thanks;