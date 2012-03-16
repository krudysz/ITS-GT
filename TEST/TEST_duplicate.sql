create table dtest(checkInOutID INT NOT NULL AUTO_INCREMENT, employeeID int not null, isCheckIn bit not null, checkInOutTime datetime not null,PRIMARY KEY (checkInOutID));
insert into dtest(employeeID,isCheckIn,checkInOutTime)
values(1,1,'1 Jan 2008 10:23');
insert into dtest(employeeID,isCheckIn,checkInOutTime)
values(1,0,'1 Jan 2008 15:34');
insert into dtest(employeeID,isCheckIn,checkInOutTime)
values(2,1,'1 Feb 2008 08:14');
insert into dtest(employeeID,isCheckIn,checkInOutTime)
values(2,1,'2 Feb 2008 08:15');
insert into dtest(employeeID,isCheckIn,checkInOutTime)
values(1,0,'2 Jan 2008 23:45');
insert into dtest(employeeID,isCheckIn,checkInOutTime)
values(3,1,'15 Jan 2008 07:00');
insert into dtest(employeeID,isCheckIn,checkInOutTime)
values(3,0,'15 Jan 2008 19:00');

WITH CTE AS (SELECT checkInOutID,employeeID,isCheckInOut,timeInOut,ROW_NUMBER() OVER (PARTITION BY employeeID ORDER BY checkInOutID) - ROW_NUMBER() OVER(PARTITION BY employeeID,isCheckInOut ORDER BY checkInOutID) AS grp FROM EMPLOYEE_CHECKINOUT),
CTE2 AS
(SELECT checkInOutID,employeeID,isCheckInOut,timeInOut,COUNT(*) OVER(PARTITION BY employeeID,isCheckInOut,grp) AS grpcnt FROM CTE) SELECT checkInOutID,employeeID,isCheckInOut,timeInOut FROM CTE2
WHERE grpcnt>1
ORDER BY checkInOutID

SELECT question_id,answered,epochtime,ROW_NUMBER() OVER (PARTITION BY id ORDER BY epochtime) FROM stats_1448;

# REMOVE duplicates
select distinct * into mytable_tmp from mytable
drop table mytable
alter table mytable_tmp rename mytable
