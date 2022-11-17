# 1. DELETE PAYHISTORYDATA ID TO DELETE

SELECT PayHistoryData.PayHistoryDataID FROM ProjectWeeklyData INNER JOIN ProjectDays ON ProjectDays.ProjectWeeklyDataID=ProjectWeeklyData.ProjectWeeklyDataID
LEFT JOIN PayHistory ON ProjectDays.PayHistoryID=PayHistory.PayHistoryID
LEFT JOIN PayHistoryData ON PayHistoryData.PayHistoryID=PayHistory.PayHistoryID WHERE PayHistoryData.PayHistoryDataID NOT NULL AND ProjectWeeklyData.ProjectWeeklyDataID=?;

# 2. SELECT PAYHISTORY ID TO DELETE
SELECT PayHistory.PayHistoryID FROM ProjectWeeklyData INNER JOIN ProjectDays ON ProjectDays.ProjectWeeklyDataID=ProjectWeeklyData.ProjectWeeklyDataID
LEFT JOIN PayHistory ON ProjectDays.PayHistoryID=PayHistory.PayHistoryID WHERE PayHistory.PayHistoryID NOT NULL AND ProjectWeeklyData.ProjectWeeklyDataID=?

# 3. SELECT PROJECTDAYS to DELETE

SELECT ProjectDays.ProjectDayID FROM ProjectWeeklyData INNER JOIN ProjectDays ON ProjectDays.ProjectWeeklyDataID=ProjectWeeklyData.ProjectWeeklyDataID WHERE ProjectDays.ProjectDayID NOT NULL AND ProjectWeeklyData.ProjectWeeklyDataID=?

# 4. FINALLY DELETE THE PROJECTWEEKLYDATA

#####
# PROJECT WEEKLY DELETION
####

# 1. SELECT PayHistoryID TO DELETE

SELECT PayHistoryData.PayHistoryDataID FROM ProjectWeekly INNER JOIN ProjectWeeklyData ON ProjectWeeklyData.ProjectWeeklyID=ProjectWeekly.ProjectWeeklyID INNER JOIN ProjectDays ON ProjectDays.ProjectWeeklyID=ProjectWeekly.ProjectWeeklyID INNER JOIN PayHistory ON PayHistory.PayHistoryID=ProjectDays.PayHistoryID LEFT JOIN PayHistoryData ON PayHistory.PayHistoryID=PayHistoryData.PayHistoryID WHERE PayHistoryData.PayHistoryDataID NOT NULL AND ProjectWeekly.ProjectWeeklyID=?