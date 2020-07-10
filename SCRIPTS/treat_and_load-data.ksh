#!/usr/bin/ksh
var_date=$(date +'%Y%m%d')

cd /root/OBSOLESCENCE/EXPORTS/
#Suppression des espace dans les noms de fichiers
ls *CMDB*|while read i 
do 
        mv "$i" $(echo $i|tr "[:blank:]" "_") 
done
# Création de l'environnement de travail
if [[ ! -d spreadsheet-csv/ ]]
then
        mkdir spreadsheet-csv/
fi
if [[ ! -d system/ ]]
then
        mkdir system/
fi
if [[ ! -d db/ ]]
then
        mkdir db/
fi
if [[ ! -d middleware/ ]]
then
        mkdir middleware/
fi

if [[ ! -d ARCHIVES/ ]]
then
        mkdir ARCHIVES/
fi
# Traitement System

libreoffice --headless --convert-to xlsx ./CMA-CMDB-SYSTEM* --outdir ./system/
cd ./system

for j in $(ls -1 | sed -e 's/\..*$//') 
do
        xlsx2csv -d '£' -s 1 $j.xlsx ../spreadsheet-csv/$j.csv
        rm -f $j.xlsx
	sed -i "s/;/-/g" ../spreadsheet-csv/$j.csv
	sed -i "s/,/|/g" ../spreadsheet-csv/$j.csv
	sed -i "s/£/\',\'/g" ../spreadsheet-csv/$j.csv
	sed -i "s/^/\'/" ../spreadsheet-csv/$j.csv
	sed -i "s/$/\'/" ../spreadsheet-csv/$j.csv
	sed -i -E "s/([A-Za-z])'([A-Za-z])/\1-\2/g" ../spreadsheet-csv/$j.csv
	sed -i -E "s/([A-Za-z])''([A-Za-z])/\1-\2/g" ../spreadsheet-csv/$j.csv
	sed -i -E "s/([A-Za-z])''([,])/\1'\2/g" ../spreadsheet-csv/$j.csv
	awk '{ORS=( (c+=gsub(/"/,"&"))%2 ? FS : RS )} 1' ../spreadsheet-csv/$j.csv > ../spreadsheet-csv/system_inventory_$var_date.csv
	sed -i "s/\' \'/|/g" ../spreadsheet-csv/system_inventory_$var_date.csv
	sed -i "s/\"\'/'/g" ../spreadsheet-csv/system_inventory_$var_date.csv
done
cd ..

#Traitement DB
libreoffice --headless --convert-to xlsx ./CMDB-DB-INSTANCE_* --outdir ./db/
cd ./db
for j in $(ls -1 | sed -e 's/\..*$//'); do
        xlsx2csv -d '£' -s 2 $j.xlsx ../spreadsheet-csv/$j.csv
        rm -f $j.xlsx
        sed -i "s/;/-/g" ../spreadsheet-csv/$j.csv
        sed -i "s/,/|/g" ../spreadsheet-csv/$j.csv
        sed -i "s/£/\',\'/g" ../spreadsheet-csv/$j.csv
        sed -i "s/^/\'/" ../spreadsheet-csv/$j.csv
        sed -i "s/$/\'/" ../spreadsheet-csv/$j.csv
        sed -i -E "s/([A-Za-z])'([A-Za-z])/\1-\2/g" ../spreadsheet-csv/$j.csv
        sed -i -E "s/([A-Za-z])''([A-Za-z])/\1-\2/g" ../spreadsheet-csv/$j.csv
        sed -i -E "s/([A-Za-z])''([,])/\1'\2/g" ../spreadsheet-csv/$j.csv
        awk '{ORS=( (c+=gsub(/"/,"&"))%2 ? FS : RS )} 1' ../spreadsheet-csv/$j.csv > ../spreadsheet-csv/db_inventory_$var_date.csv
        sed -i "s/\' \'/|/g" ../spreadsheet-csv/db_inventory_$var_date.csv
        sed -i "s/\"\'/'/g" ../spreadsheet-csv/db_inventory_$var_date.csv
done
cd ..
#Traitement Middleware
libreoffice --headless --convert-to xlsx ./CMDB-MW_* --outdir ./middleware/
cd ./middleware
for j in $(ls -1 | sed -e 's/\..*$//'); do
        xlsx2csv -d '£' -s 2 $j.xlsx ../spreadsheet-csv/$j.csv
        rm -f $j.xlsx
        sed -i "s/;/-/g" ../spreadsheet-csv/$j.csv
        sed -i "s/,/|/g" ../spreadsheet-csv/$j.csv
        sed -i "s/£/\',\'/g" ../spreadsheet-csv/$j.csv
        sed -i "s/^/\'/" ../spreadsheet-csv/$j.csv
        sed -i "s/$/\'/" ../spreadsheet-csv/$j.csv
        sed -i -E "s/([A-Za-z])'([A-Za-z])/\1-\2/g" ../spreadsheet-csv/$j.csv
        sed -i -E "s/([A-Za-z])''([A-Za-z])/\1-\2/g" ../spreadsheet-csv/$j.csv
        sed -i -E "s/([A-Za-z])''([,])/\1'\2/g" ../spreadsheet-csv/$j.csv
        awk '{ORS=( (c+=gsub(/"/,"&"))%2 ? FS : RS )} 1' ../spreadsheet-csv/$j.csv > ../spreadsheet-csv/middleware_inventory_$var_date.csv
        sed -i "s/\' \'/|/g" ../spreadsheet-csv/middleware_inventory_$var_date.csv
        sed -i "s/\"\'/'/g" ../spreadsheet-csv/middleware_inventory_$var_date.csv

done
cd ..
rm -rf system db middleware
if [[ ! -d SQL/ ]]
then
	mkdir SQL
fi
cd SQL
/root/OBSOLESCENCE/SCRIPTS/mktable.ksh /root/OBSOLESCENCE/EXPORTS/spreadsheet-csv/system_inventory_$var_date.csv
/root/OBSOLESCENCE/SCRIPTS/mktable.ksh /root/OBSOLESCENCE/EXPORTS/spreadsheet-csv/db_inventory_$var_date.csv
/root/OBSOLESCENCE/SCRIPTS/mktable.ksh /root/OBSOLESCENCE/EXPORTS/spreadsheet-csv/middleware_inventory_$var_date.csv

mysql -v -h mysql -uroot -p\!Maverick02# cmdb < /root/OBSOLESCENCE/EXPORTS/SQL/make_table_system_inventory_$var_date.sql
mysql -v -h mysql -uroot -p\!Maverick02# cmdb < /root/OBSOLESCENCE/EXPORTS/SQL/make_table_db_inventory_$var_date.sql
mysql -v -h mysql -uroot -p\!Maverick02# cmdb < /root/OBSOLESCENCE/EXPORTS/SQL/make_table_middleware_inventory_$var_date.sql

cd ..

mv *.xlsb ARCHIVES/.
