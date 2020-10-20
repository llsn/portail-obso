<?php 
    require 'variable-db.php';
    require_once 'Classes/PHPExcel/IOFactory.php';
    ini_set('max_execution_time', 0);
    set_time_limit(0);
    ///recupération du fichier client du le serveur
    $file_name = $_FILES['xlsx']['name'];
    $extension=strrchr($file_name,'.');
    if ($extension == '.xlsx')
    {
        $datesys=shell_exec('date \'+%Y%m%d\'');
        $inputFileType = 'Excel2007';
        $inputFileName = $file_name;
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setLoadSheetsOnly(["SYSTEM"]);
        $objPHPExcelReader = $objReader->load($inputFileName);

        $loadedSheetNames = $objPHPExcelReader->getSheetNames();

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelReader, 'CSV');

        foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
            $objWriter->setDelimiter(';');
            $objWriter->setEnclosure();
            $objWriter->setSheetIndex($sheetIndex);
            $objWriter->save('imports/system_inventory_'.$datesys.'.csv');

        $createtable="CREATE TABLE `system_inventory_$datesys` (
            `system_inventory_id` int NOT NULL AUTO_INCREMENT,
            `CUSTOMER` text COLLATE utf8mb4_bin,
            `CLASSIFICATION` text COLLATE utf8mb4_bin,
            `CONFIGURATIONNUMBER` text COLLATE utf8mb4_bin,
            `CONFIGURATIONNAME` text COLLATE utf8mb4_bin,
            `CONFIGURATIONNAME_WO_EXTENSION` varchar(255) COLLATE utf8mb4_bin NOT NULL,
            `DESCRIPTION` text COLLATE utf8mb4_bin,
            `CIEXTERNALREFID` text COLLATE utf8mb4_bin,
            `ACTCINUM` text COLLATE utf8mb4_bin,
            `ACTCINAME` text COLLATE utf8mb4_bin,
            `STATUS` text COLLATE utf8mb4_bin,
            `STATUSDATE` text COLLATE utf8mb4_bin,
            `OPERATINGENVIRONMENT` varchar(255) COLLATE utf8mb4_bin NOT NULL,
            `BUSINESSIMPACT` text COLLATE utf8mb4_bin,
            `BUSINESSOWNER` text COLLATE utf8mb4_bin,
            `CIMANAGEDBY` text COLLATE utf8mb4_bin,
            `SUPPORTGROUP` text COLLATE utf8mb4_bin,
            `OWNERGROUP` text COLLATE utf8mb4_bin,
            `LOCATION` text COLLATE utf8mb4_bin,
            `DESCRIPTION_LOCATION` text COLLATE utf8mb4_bin,
            `CHANGEDATE` text COLLATE utf8mb4_bin,
            `OSNAME` varchar(255) COLLATE utf8mb4_bin NOT NULL,
            `OSVERSION` varchar(255) COLLATE utf8mb4_bin NOT NULL,
            `OSKERNEL` text COLLATE utf8mb4_bin,
            `ORBUILD` text COLLATE utf8mb4_bin,
            `BREAKOUT` text COLLATE utf8mb4_bin,
            `GLOBALOS` text COLLATE utf8mb4_bin,
            `TECHNOLOGY` text COLLATE utf8mb4_bin,
            `IPADRESSES` text COLLATE utf8mb4_bin,
            `HARDWAREHOST` text COLLATE utf8mb4_bin,
            `BUSINESSSERVICES` text COLLATE utf8mb4_bin,
            `BUSINESSAPPLICATIONS` text COLLATE utf8mb4_bin,
            `FUNCTIONALGROUPS` text COLLATE utf8mb4_bin,
            `CINUM` text COLLATE utf8mb4_bin,
            `SPEC_CHANGEDATE` text COLLATE utf8mb4_bin,
            `COMPUTERSYSTEM_TYPE` text COLLATE utf8mb4_bin,
            `COMPUTERSYSTEM_FQDN` text COLLATE utf8mb4_bin,
            `COMPUTERSYSTEM_VIRTUAL` text COLLATE utf8mb4_bin,
            `COMPUTERSYSTEM_SERIALNUMBER` text COLLATE utf8mb4_bin,
            `COMPUTERSYSTEM_MANUFACTURER` text COLLATE utf8mb4_bin,
            `COMPUTERSYSTEM_MODEL` text COLLATE utf8mb4_bin,
            `COMPUTERSYSTEM_ARCHITECTURE` text COLLATE utf8mb4_bin,
            `COMPUTERSYSTEM_CPUSPEED` text COLLATE utf8mb4_bin,
            `COMPUTERSYSTEM_CPUTYPE` text COLLATE utf8mb4_bin,
            `COMPUTERSYSTEM_MEMORYSIZE` text COLLATE utf8mb4_bin,
            `COMPUTERSYSTEM_NUMCPUS` text COLLATE utf8mb4_bin,
            `CRITICALITY` text COLLATE utf8mb4_bin,
            `BUILD_REFERENCE` text COLLATE utf8mb4_bin,
            `UNBUILD_REFERENCE` text COLLATE utf8mb4_bin,
            `INSTALL_DATE` text COLLATE utf8mb4_bin,
            `UNISTALL_DATE` text COLLATE utf8mb4_bin,
            `SERVICE_USAGE` text COLLATE utf8mb4_bin,
            `LASTSCAN` text COLLATE utf8mb4_bin,
            PRIMARY KEY (`system_inventory_id`),
            UNIQUE KEY `system_inventory_id_UNIQUE` (`system_inventory_id`),
            KEY `UNIQUE_ITEM_OS` (`system_inventory_id`,`CONFIGURATIONNAME_WO_EXTENSION`,`OSNAME`,`OSVERSION`),
            FULLTEXT KEY `INDEX_SYS_HOSTNAME` (`CONFIGURATIONNAME_WO_EXTENSION`),
            FULLTEXT KEY `INDEX_SYS_ENV` (`OPERATINGENVIRONMENT`),
            FULLTEXT KEY `INDEX_SYS_VERSION` (`OSVERSION`),
            FULLTEXT KEY `INDEX_SYS_EDITION` (`OSNAME`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='system_inventory_$datesys';";
        shell_exec('mysqlimport -h '.$host.' -u '.$user.' -p '.$password.' cmdb imports/system_inventory_'.$datesys.'.csv');

        }
    }
?>
