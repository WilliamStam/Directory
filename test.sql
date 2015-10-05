SELECT mp_meetings.*, mp_companies.company, if (mp_meetings.timeStart>=now() and mp_meetings.timeEnd<= now(),1,0) AS active, if(mp_users_group.userID,1,0) AS access, if (mp_users.global_admin='1','1',mp_users_company.admin) as admin

FROM (((mp_meetings INNER JOIN mp_meetings_group ON mp_meetings.ID = mp_meetings_group.meetingID) INNER JOIN mp_users_group ON mp_meetings_group.groupID = mp_users_group.groupID) INNER JOIN mp_companies ON mp_meetings.companyID = mp_companies.ID) INNER JOIN (mp_users_company INNER JOIN mp_users ON mp_users_company.userID = mp_users.ID) ON (mp_meetings.companyID = mp_users_company.companyID) AND (mp_users_group.userID = mp_users.ID)

WHERE mp_users_group.userID='1' AND mp_meetings.companyID = '3' GROUP BY mp_meetings.ID 




UPDATE mp_logs SET typeID = if(commentID is not null,)