#!/bin/ksh
# pass in the file name as an argument: ./mktable filename.csv
var_date=$(date +'%Y%m%d')
DATABASE='cmdb'
TABLE_NAME=`echo $1|cut -d'/' -f6|cut -d'.' -f1|sed -e "s/_[[:digit:]].*$/_$var_date/g"`
echo "drop table if exists $DATABASE.$TABLE_NAME;" > make_table_$TABLE_NAME.sql
echo "create table $DATABASE.$TABLE_NAME ( " >> make_table_$TABLE_NAME.sql
head -1 $1 | sed -e "s/'/\`/g"| sed -e 's/,/ TEXT,\n/g' >> make_table_$TABLE_NAME.sql
echo " TEXT ) COMMENT = '$TABLE_NAME';" >> make_table_$TABLE_NAME.sql
#tac $1 |head -n -1|tac|sed -e "s/,/\",\"/g"|sed -e "s/$/\"/g"|sed -e "s/^/\"/g"|sed -e "s/\"\"/\"/g"| while read data
tac $1 |head -n -1|tac| while read data
do
	echo "insert into $DATABASE.$TABLE_NAME values ($data);" >> make_table_$TABLE_NAME.sql
done


