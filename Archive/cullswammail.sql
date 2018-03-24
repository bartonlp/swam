-- This is run via crontab
-- We only want to keep swam mail for 30 days
/usr/bin/mysql swam -pjohnz <~/cullswammail.sql
select @deldate:= TO_DAYS(now()) - 10;

delete from swammailmsg where TO_DAYS(datesent) < @deldate;

select date_format(datesent, '%Y %M %d') from swammailmsg;

delete from swammailto where TO_DAYS(dateread) < @deldate;

select date_format(dateread, '%Y %M %d') from swammailto;

