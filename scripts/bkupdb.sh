#!/bin/bash
# Backup the database before starting.
# I create a file SWAM_BACKUP.sql which can be used to create a new
# database
echo "Backup swam database";

cd /var/www/zupons.net/htdocs/swam
dir=other
bkupdate=`date +%B-%d-%y`
filename="SWAM_BACKUP.$bkupdate.sql"

mysqldump --user=7991 --no-data --password=eiBoo2Hor5uo zuponsdotnet > $dir/swam.schema
mysqldump --user=7991 --add-drop-table --password=eiBoo2Hor5uo zuponsdotnet >$dir/$filename

gzip $dir/$filename

find $dir -ctime +30 -exec rm '{}' \;

echo "Backup swam database DONE";
