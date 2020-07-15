#!/usr/bin/ksh
var_date=$(date +'%Y%m%d')
HTML=/var/www/html
WORK=$HTML/WORK
SYSTEM=$WORK/system
DB=$WORK/db
MIDDLEWARE=$WORK/middleware
SPREADSHEET=$WORK/spreadsheet-csv
ARCHIVES=$WORK/ARCHIVES
SQL=$WORK/SQL

cd $WORK
#Suppression des espace dans les noms de fichiers
ls *CMDB*|while read i 
do 
        mv "$i" $(echo $i|tr "[:blank:]" "_") 
done
# Création de l'environnement de travail
if [[ ! -d $SPREADSHEET ]]
then
        mkdir $SPREADSHEET
fi
if [[ ! -d $SYSTEM ]]
then
        mkdir $SYSTEM
fi
if [[ ! -d $DB ]]
then
        mkdir $DB
fi
if [[ ! -d $MIDDLEWARE ]]
then
        mkdir $MIDDLEWARE
fi

if [[ ! -d $ARCHIVES ]]
then
        mkdir $ARCHIVES
fi
# Traitement System

libreoffice --headless --convert-to xlsx ./CMA-CMDB-SYSTEM* --outdir $SYSTEM
cd $SYSTEM

for j in $(ls -1 | sed -e 's/\..*$//') 
do
    xlsx2csv -d '%' -s 1 $j.xlsx $SPREADSHEET/$j.csv
    rm -f $j.xlsx
	sed -i "s/;/-/g" $SPREADSHEET/$j.csv
	sed -i "s/,/|/g" $SPREADSHEET/$j.csv
	sed -i "s/%/\',\'/g" $SPREADSHEET/$j.csv
	sed -i "s/^/\'/" $SPREADSHEET/$j.csv
	sed -i "s/$/\'/" $SPREADSHEET/$j.csv
	sed -i -E "s/([A-Za-z])'([A-Za-z])/\1-\2/g" $SPREADSHEET/$j.csv
	sed -i -E "s/([A-Za-z])''([A-Za-z])/\1-\2/g" $SPREADSHEET/$j.csv
	sed -i -E "s/([A-Za-z])''([,])/\1'\2/g" $SPREADSHEET/$j.csv
	awk '{ORS=( (c+=gsub(/"/,"&"))%2 ? FS : RS )} 1' $SPREADSHEET/$j.csv > $SPREADSHEET/system_inventory_$var_date.csv
	sed -i "s/\' \'/|/g" $SPREADSHEET/system_inventory_$var_date.csv
	sed -i "s/\"\'/'/g" $SPREADSHEET/system_inventory_$var_date.csv
done
cd ..

#Traitement DB
libreoffice --headless --convert-to xlsx ./CMDB-DB-INSTANCE_* --outdir $DB
cd $DB
for j in $(ls -1 | sed -e 's/\..*$//'); do
        xlsx2csv -d '%' -s 2 $j.xlsx $SPREADSHEET/$j.csv
        rm -f $j.xlsx
        sed -i "s/;/-/g" $SPREADSHEET/$j.csv
        sed -i "s/,/|/g" $SPREADSHEET/$j.csv
        sed -i "s/%/\',\'/g" $SPREADSHEET/$j.csv
        sed -i "s/^/\'/" $SPREADSHEET/$j.csv
        sed -i "s/$/\'/" $SPREADSHEET/$j.csv
        sed -i -E "s/([A-Za-z])'([A-Za-z])/\1-\2/g" $SPREADSHEET/$j.csv
        sed -i -E "s/([A-Za-z])''([A-Za-z])/\1-\2/g" $SPREADSHEET/$j.csv
        sed -i -E "s/([A-Za-z])''([,])/\1'\2/g" $SPREADSHEET/$j.csv
        awk '{ORS=( (c+=gsub(/"/,"&"))%2 ? FS : RS )} 1' $SPREADSHEET/$j.csv > $SPREADSHEET/db_inventory_$var_date.csv
        sed -i "s/\' \'/|/g" $SPREADSHEET/db_inventory_$var_date.csv
        sed -i "s/\"\'/'/g" $SPREADSHEET/db_inventory_$var_date.csv
done
cd ..
#Traitement Middleware
libreoffice --headless --convert-to xlsx ./CMDB-MW_* --outdir $MIDDLEWARE
cd $MIDDLEWARE
for j in $(ls -1 | sed -e 's/\..*$//'); do
        xlsx2csv -d '%' -s 2 $j.xlsx $SPREADSHEET/$j.csv
        rm -f $j.xlsx
        sed -i "s/;/-/g" $SPREADSHEET/$j.csv
        sed -i "s/,/|/g" $SPREADSHEET/$j.csv
        sed -i "s/%/\',\'/g" $SPREADSHEET/$j.csv
        sed -i "s/^/\'/" $SPREADSHEET/$j.csv
        sed -i "s/$/\'/" $SPREADSHEET/$j.csv
        sed -i -E "s/([A-Za-z])'([A-Za-z])/\1-\2/g" $SPREADSHEET/$j.csv
        sed -i -E "s/([A-Za-z])''([A-Za-z])/\1-\2/g" $SPREADSHEET/$j.csv
        sed -i -E "s/([A-Za-z])''([,])/\1'\2/g" $SPREADSHEET/$j.csv
        awk '{ORS=( (c+=gsub(/"/,"&"))%2 ? FS : RS )} 1' ../spreadsheet-csv/$j.csv > $SPREADSHEET/middleware_inventory_$var_date.csv
        sed -i "s/\' \'/|/g" $SPREADSHEET/middleware_inventory_$var_date.csv
        sed -i "s/\"\'/'/g" $SPREADSHEET/middleware_inventory_$var_date.csv

done
cd ..
rm -rf $SYSTEM $DB $MIDDLEWARE
if [[ ! -d $SQL ]]
then
	mkdir $SQL
fi
cd SQL
$HTML/SCRIPTS/mktable.ksh $SPREADSHEET/system_inventory_$var_date.csv
$HTML/SCRIPTS/mktable.ksh $SPREADSHEET/db_inventory_$var_date.csv
$HTML/SCRIPTS/mktable.ksh $SPREADSHEET/middleware_inventory_$var_date.csv

mysql -v -h mysql -uroot -p\!Maverick02# cmdb < $SQL/make_table_system_inventory_$var_date.sql
mysql -v -h mysql -uroot -p\!Maverick02# cmdb < $SQL/make_table_db_inventory_$var_date.sql
mysql -v -h mysql -uroot -p\!Maverick02# cmdb < $SQL/make_table_middleware_inventory_$var_date.sql

cd ..

mv *.xlsb $ARCHIVES