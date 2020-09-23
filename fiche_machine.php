<?php
    require 'variable-db.php';
    require 'functions.php';

    $STYLE = "style='border:1px solid black;border-collapse: collapse;text-align:center;'";
    $ping_server = isset($_POST['ping_server']) ? $_POST['ping_server'] : null;
    $exportpdf = isset($_POST['EXPORTPDF']) ? $_POST['EXPORTPDF'] : null;
    $machine = isset($_POST['machine']) ? $_POST['machine'] : $ping_server;
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());
?>
<html>

<head>
	<title>Fiche Machine</title>
	<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="vendor/datatables/datatables/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="vendor/datatables/datatables/media/css/jquery.dataTables.min.css">
	<!-- 	<link rel="stylesheet" type="text/css" href="javascript/DataTables-1.10.20/examples/resources/syntax/shCore.css">
		<link rel="stylesheet" type="text/css" href="javascript/DataTables-1.10.20/examples/resources/demo.css">
	-->
	<!-- Chargement des scripts javascripts pour la mise en forme des tableaux-->
	<script type="text/javascript" language="javascript" src="vendor/components/jquery/jquery.min.js"></script>
	<script type="text/javascript" language="javascript" src="bootstrap/js/bootstrap.min.ls.js"></script>
	<script type="text/javascript" language="javascript" src="vendor/datatables/datatables/media/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="vendor/twbs/bootstrap/js/dist/tab.js"></script>
	<!-- 	<script type="text/javascript" language="javascript" src="javascript/DataTables-1.10.20/examples/resources/syntax/shCore.js"></script>
		<script type="text/javascript" language="javascript" src="javascript/DataTables-1.10.20/examples/resources/demo.js"></script>
	
		<script type="text/javascript" language="javascript" src="js/pace.min.js"></script>-->


	<!-- Initialisation des tableaux de données de la page -->
	<script>
		$(document).ready(function () {
			$('table.display').DataTable();
		});

		function chargement() {
			document.getElementById('chargement').style.display = 'none';
			document.getElementById('site').style.visibility = 'visible';
		}
	</script>
	<!-- forcage de paramètres des feuilles de style précédemment chargées -->
	<style type="text/css">
		div.dataTables_wrapper {
			margin-bottom: 3em;
		}

		.container {
			width: 100%;
		}

		.table.table-unruled>tbody>tr>td,
		.table.table-unruled>tbody>tr>th {
			border-top: 0 none transparent;
			border-bottom: 0 none transparent;
			background-color: 0 none transparent;
		}

		tr:nth-child(even) {
			background-color: none;
		}

		.pace {
			-webkit-pointer-events: none;
			pointer-events: none;

			-webkit-user-select: none;
			-moz-user-select: none;
			user-select: none;

			position: fixed;
			top: 0;
			left: 0;
			width: 100%;

			-webkit-transform: translate3d(0, -50px, 0);
			-ms-transform: translate3d(0, -50px, 0);
			transform: translate3d(0, -50px, 0);

			-webkit-transition: -webkit-transform .5s ease-out;
			-ms-transition: -webkit-transform .5s ease-out;
			transition: transform .5s ease-out;
		}

		.pace.pace-active {
			-webkit-transform: translate3d(0, 0, 0);
			-ms-transform: translate3d(0, 0, 0);
			transform: translate3d(0, 0, 0);
		}

		.pace .pace-progress {
			display: block;
			position: fixed;
			z-index: 2000;
			top: 0;
			right: 100%;
			width: 100%;
			height: 10px;
			background: #29d;

			pointer-events: none;
		}
	</style>
	<script>
		window.paceOptions = {
			ajax: true,
			element: true
		}
	</script>
</head>

<body>
    <?php
	echo "<script src='js/pace.min.js'></script>";
	echo "<div style='text-align:center;'>";
    echo "<h4 class='jumbotron'>Fiche descriptive d'une machine</h4>";
	echo "</div>";
    echo "<form id='machine' class='form-horizontal' method='POST' enctype='multipart/form-data' action='fiche_machine.php'>";
    echo "<acronym title='Saisissez ou sélectionnez le texte désiré et tapez sur 'ENTREE' ou cliquez sur le bouton 'Valider'>";
    echo "<input list='list_machine' name='machine' size='30' maxlength='30' value='".$machine."' class='input-lg' onchange='document.getElementById('application').submit()'></acronym>";
    echo "<datalist id='list_machine'>";
    $querytable = "select configurationname_wo_extension from system_inventory where status <> 'ARCHIVED';";
    if ($stmt = $con->prepare($querytable)) 
    {
        $stmt->execute();
        while ($resulttable = $stmt->fetch()) 
        {
            if ($machine == $resulttable[0]) 
            {
                echo "<option value='".$resulttable[0]."' selected onclick='document.getElementById('application').submit()\'>".$resulttable[0]."</option>";
            } 
            else 
            {
                echo "<option value='".$resulttable[0]."' onclick=\'document.getElementById('application').submit()\'>".$resulttable[0]."</option>";
            }

            $stmt->pdo = null;
        }
    }
    echo "</datalist>";
    echo "<input type='submit' class='btn'>";
    echo "</form>";
    if (isset($machine) != null) 
    {
        echo "<div>";
        echo "<form id='ping_server' class='form-horizontal' method='POST' enctype='multipart/form-data' action='fiche_machine.php'>";
        echo "<input type='hidden' name='ping_server' value='".$machine."'/>";
        echo "<input type='submit' value='PING vers la machine ".$machine."' class='input-lg'>";
        echo "</form>";
        echo "</div>";

        if (isset($ping_server) != null) 
        {
            echo "<div><pre>";
            exec("/bin/ping -c 3 $ping_server",$output,$result);
            if ($result == 0)
                echo "Ping du serveur $ping_server réussi!";
            else
                echo "Ping du serveur $ping_server échoué!!!!!";
            //echo "<pre>".print_r($output)."</pre>";
            $machine = $ping_server;
            echo "</pre></div>";
        }

        $querymachine = "select * from system_inventory where upper(configurationname_wo_extension) = upper('".str_replace(' ', '', $machine)."');";
        if ($stmt = $con->prepare($querymachine)) 
        {
            $stmt->execute();
            $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($tuples) != 0) 
            {
                if (count($tuples)) 
                {
                    $data = array_values($tuples[0]);
                    $hostname = $data[5];
                    $status = $data[10];
                    $environment = $data[12];
                    $osname = $data[21];
                    $osversion = $data[22];
                    $kernelversion = $data[23];
                    $osbuild = $data[24];
                    $businessservices = $data[30];
                    $businessapplication = $data[31];
                    $functionalgroup = $data[32];
                    $virtual_server=$data[37];
                    $criticity = $data[46];
                    $buildref = $data[47];
                    $unbuildref = $data[48];
                    $installdate = $data[49];
                    $uninstalldate = $data[50];
                    switch(true)
                    {
                        case (stristr($osname,'aix')):
                            $queryparent="select distinct `child` from cmdb.relationship where `parent` like '".$machine."%' and `parent.sys_class_name` = 'Computer' and `child.sys_class_name` = 'Computer';";
                        break;
                        case (stristr($osname,'windows')):
                            $queryparent="select distinct `parent` from cmdb.relationship where `child` like '".$machine."%' and `child.sys_class_name` = 'Windows Cluster Node';";
                        break;
                        case (stristr($osname,'linux')):
                            $queryparent="select distinct SUBSTRING_INDEX(SUBSTRING_INDEX(HARDWAREHOST,'.',1),',',1) as `parent` from cmdb.system_inventory where  `CONFIGURATIONNAME_WO_EXTENSION` = '".$machine."';";
                        break;
                        case (stristr($osname,'systemp')):
                            $queryparent="select distinct SUBSTRING_INDEX(SUBSTRING_INDEX(HARDWAREHOST,'.',1),',',1) as `parent` from cmdb.system_inventory where  `HARDWAREHOST` = '".$machine."';";
                        break;
                    }
                    if ($stmt = $con->prepare($queryparent)) 
                    {
                        $stmt->execute();
                        $tuplesparents = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        switch(true)
                        {
                            case (stristr($osname,'aix')):
                                $parent=$tuplesparents[0]['child'];
                            break;
                            case (stristr($osname,'windows')):
                                $parent=$tuplesparents[0]['parent'];
                                if($parent=='')
                                {
                                    $subquery="select distinct SUBSTRING_INDEX(SUBSTRING_INDEX(HARDWAREHOST,'.',1),',',1) as `parent` from cmdb.system_inventory where  `CONFIGURATIONNAME_WO_EXTENSION` = '".$machine."';";
                                    if ($substmt = $con->prepare($subquery)) 
                                    {
                                        $substmt->execute();
                                        $wintuples = $substmt->fetchAll(PDO::FETCH_ASSOC);
                                        $parent=$wintuples[0]['parent'];
                                    }
                                }
                            break;
                            case (stristr($osname,'linux')):
                                $parent=$tuplesparents[0]['parent'];
                            break;
                            case (stristr($osname,'systemp')):
                                $parent=$tuplesparents[0]['parent'];
                            break;
                        }        
                    }
                }
                $BGCOLOR_OS = status_obso_osversion($osversion, $host, $dbname, $user, $password);
                ob_start(); 
                echo "<div class='container'>";
                echo "<ul class='nav nav-tabs'>";
                echo "<li class='active'><a data-toggle='tab' href='#details'>Détails</a></li>";
                echo "<li><a data-toggle='tab' href='#db'>Base de données</a></li>";
                echo "<li><a data-toggle='tab' href='#mdw'>Middleware</a></li>";
                echo "<li><a data-toggle='tab' href='#hopex_mdw'>HOPEX DEPLOYED OBJECT</a></li>";
                echo "<li><a data-toggle='tab' href='#pgmp'>PGMP</a></li>";
                echo "<li><a data-toggle='tab' href='#debug'>Debug</a></li>";
                echo "</ul>";
                echo "<div class='tab-content'>";
                echo "<div id='details' class='tab-pane fade in active'>";
                echo "<center>";
                echo "<table id='' class='table table-hover' style='width: 100%;'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th colspan='4' bgcolor='silver'>";
                echo "<h4>FICHE MACHINE</h4>";
                echo "</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                echo "<tr>";
                echo "<td colspan='3' bgcolor='". $BGCOLOR_OS."'>";
                echo "<h4><b>".$machine."</b></h4>";
                echo "</td>";
                $queryponderation = "call cmdb.poderation(".$data[0].",1, @ponderation);";
                if ($stmt = $con->prepare($queryponderation)) 
                {
                    $stmt->execute();
                    $result_ponderation = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (count($result_ponderation) != 0) 
                    {
                        echo "<td colspan='1' bgcolor='".$BGCOLOR_OS."'>";
                        echo "<h4>".$result_ponderation[0][ponderation]."</h4>";
                        echo "</td>";
                    }
                }
                echo "</tr>";
                echo "<tr>";
                echo "<th colspan='1'>Status</th>";
                if ($status=='ARCHIVED')
                {
                    echo "<td colspan='1' bgcolor='#F95858' style='text-align:center;color:white;'><b>".$status."</b></td>";
                }
                else
                {
                    echo "<td colspan='1'>".$status."</td>";
                }
                
                echo "<th colspan='1'>Criticité</th>";
                echo "<td colspan='1'>".$criticity."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th colspan='1'>Operating Environment</th>";
                echo "<td colspan='1'>".$environment."</td>";
                echo "<th colspan='1'>Machine Virtuel</th>";
                echo "<td colspan='1'>".$virtual_server."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th colspan='1'>Operating System</th>";
                echo "<td colspan='1'>".$osname."</td>";
                if($parent!='')
                {
                    
                    switch(true)
                    {
                        case (stristr($osname,'aix')):
                            echo "<th colspan='1'>Fils de</th>";
                            echo "<td colspan='1'><form id='".$parent."' method='POST' action='fiche_machine.php'><input type='hidden' name='machine' value='".$parent."'/></form><a href='#' onclick='document.getElementById(\"$parent\").submit()' ><b>".$parent."</b></a><td>";
                        break;
                        case (stristr($osname,'windows')):
                            $subwinquery="select distinct `parent` from cmdb.relationship where `parent`= '".$parent."';";
                            if ($subwinstmt = $con->prepare($subwinquery)) 
                            {
                                $subwinstmt->execute();
                                $subwintuples = $subwinstmt->fetchAll(PDO::FETCH_ASSOC);
                                $subwinparent=$subwintuples[0]['parent'];
                            }
                            if($subwinparent!='')
                            {
                                echo "<th colspan='1'>Fils de</th>";
                                echo "<td colspan='1'><form id='".$subwinparent."' method='POST' action='relationship.php'><input type='hidden' name='var_parent' value='".$subwinparent."'/></form><a href='#' onclick='document.getElementById(\"$subwinparent\").submit()' ><b>".$subwinparent."</b></a><td>";
                            }
                            else
                            {
                                echo "<th colspan='1'>Fils de</th>";
                                echo "<td colspan='1'><form id='".$parent."' method='POST' action='fiche_machine.php'><input type='hidden' name='machine' value='".$parent."'/></form><a href='#' onclick='document.getElementById(\"$parent\").submit()' ><b>".$parent."</b></a><td>";

                            }
                        break;
                        case (stristr($osname,'linux')):
                            echo "<th colspan='1'>Fils de</th>";
                            echo "<td colspan='1'><form id='".$parent."' method='POST' action='fiche_machine.php'><input type='hidden' name='machine' value='".$parent."'/></form><a href='#' onclick='document.getElementById(\"$parent\").submit()' ><b>".$parent."</b></a><td>";
                        break;
                        case (stristr($osname,'systemp')):
                            echo "<th colspan='1'>Détail du contenu de</th>";
                            echo "<td colspan='1'><form id='".$parent."' method='POST' action='virtual_by_hardware.php'><input type='hidden' name='id_machine' value='".$parent."'/></form><a href='#' onclick='document.getElementById(\"$parent\").submit()' ><b>".$parent."</b></a><td>";
                        break;
                        default:
                            echo "<th colspan='1'>Fils de</th>";
                            echo "<td colspan='1'>Machine parent</td>";
                        break;
                    }
                }
                else
                {
                    if(stristr($osname,'vmnix'))
                    {
                        echo "<th colspan='1'>Détail du contenu de</th>";
                        echo "<td colspan='1'><form id='".$machine."' method='POST' action='virtual_by_hardware.php'><input type='hidden' name='id_machine' value='".$machine."'/></form><a href='#' onclick='document.getElementById(\"$machine\").submit()' ><b>".$machine."</b></a><td>";
                    }
                    else
                    {
                        echo "<td colspan='1'><font color='red' style='bold'>Pas de parent renseigné dans la CMDB</font></td>";
                    }
                }
                echo "</tr>";
                echo "<tr>";
                echo "<th colspan='1' >OS Version</th><td colspan='2' bgcolor='$BGCOLOR_OS' >".$osversion."</td>"; 
                echo "</tr>";
                echo "<tr><th colspan='1' >Business Services</th><th colspan='1' >Business Application</th><th colspan='2' >Functional Group</th></tr>";
                echo "<tr><td>";
                if ($businessservices != '') 
                {
                    $list_business_services=explode('|',$businessservices);
                    // echo "<td colspan='5' >";
                    if (count($list_business_services))
                    {
                        foreach ($list_business_services as $ligne) 
                        {
                            echo "<form id='".$ligne."' method='POST' action='gestion_obso_v2.php'><input type='hidden' name='application' value='".$ligne."'/></form><a href='#' onclick='document.getElementById(\"$ligne\").submit()' ><b>".$ligne."</b></a>";
                            echo "<br/>";
                        }
                    }
                    // echo "</td>";
                } 
                else 
                {
                    // echo "<td colspan='5' >Pas d'info</td>";
                    echo "Pas d'info";
                }
                echo "</td>";
                echo "<td>";
                // echo "<th colspan='1' >Business Application</th>";
                if ($businessapplication != '') 
                {
                    $list_business_application=explode('|',$businessapplication);
                    // echo "<td colspan='5' >";
                    if (count($list_business_application))
                    {
                        foreach ($list_business_application as $ligne) 
                        {
                            //echo "<form id='".$ligne."' method='POST' action='components_detail.php'><input type='hidden' name='var_logical_CI' value='".$ligne."'/><input type='hidden' name='var_consult_component' value='true'/></form><a href='#' onclick='document.getElementById(\"$ligne\").submit()' ><b>".$ligne."</b></a>";
                            echo "<form id='".$ligne."' method='POST' action='business_application.php'><input type='hidden' name='affichage' value='".$ligne."'/><input type='hidden' name='var_consult_component' value='true'/></form><a href='#' onclick='document.getElementById(\"$ligne\").submit()' ><b>".$ligne."</b></a>";
                            echo "<br/>";
                        }
                    }
                    // echo "</td>";
                } 
                else 
                {
                    // echo "<td colspan='5' >Pas d'info</td>";
                    echo "Pas d'info";
                }
                echo "</td>";
                echo "<td colspan='2'>";
                // echo "<th colspan='1' >Functional Group</th>";
                if ($functionalgroup != '') 
                {
		    	$list_functional_group=explode('|',$functionalgroup);
			// echo "<td colspan='5'>";
			if (count($list_functional_group))
			{
				foreach ($list_functional_group as $ligne)
				{
					echo "<form id='".$ligne."' method='POST' action='logical_CI.php'><input type='hidden' name='affichage' value='".$ligne."'/><input type='hidden' name='var_consult_component' value='true'/></form><a href='#' onclick='document.getElementById(\"$ligne\").submit()' ><b>".$ligne."</b></a>";
                    echo "<br/>";
				}
			}
                    	// echo "</td>";
                } 
                else 
                {
                    // echo "<td colspan='5' >Pas d'info</td>";
                    echo "Pas d'info";
                }
                echo "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th colspan='1' >Date installation</th>";
                if ($installdate != '') 
                {
                    echo "<td colspan='1' >".date('d/m/Y', strtotime($installdate))."</td>";
                } 
                else 
                {
                    echo "<td colspan='1' >Pas d'info</td>";
                }
                echo "<th colspan='1' >Date de désinstallation</th>";
                if ($uninstalldate != '') 
                {
                    echo "<td colspan='1' >".date('d/m/Y', strtotime($uninstalldate))."</td>";
                } 
                else 
                {
                    echo "<td colspan='1' >Pas d'info</td>";
                }
                echo "</tr>";                         
                $stmt->pdo = null;
            }
        }
        echo "</tbody>";
        echo "</table>";
        echo "</center>";
        echo "</div>";
        echo "<br />";
        echo "<div id='db' class='tab-pane fade'>";
        if (count($tuples) != 0) 
        {
            $querydb = "select `Database Instance Name`,`Instance`,`Middleware Edition`,`Middleware Version` from db_inventory where upper(hostname) like upper('".str_replace(' ', '', $machine)."%');";
            if ($stmt = $con->prepare($querydb)) {
                $stmt->execute();
                $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                echo "<table id='' class='table table-hover' style='width: 100%;'>";
                echo "<thead>";
                if (count($tuples)) 
                {
                    echo "<tr>";
                    echo "<th colspan='4' bgcolor='silver' ><h4>BASE DE DONNEE</h4></th>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<th  >Nom du serveur:Nom de l'instance</th><th >Nom de l'instance</th><th >Type de Base de données</th><th >Version de la base de données</th>";
                    echo "</tr></thead><tbody>";
                    foreach ($tuples as $ligne) 
                    {
                        echo "<tr>";
                        $BGCOLOR_DB = status_obso_dbversion($ligne['Middleware Version'], $host, $dbname, $user, $password);
                        foreach ($ligne as $entete => $valeur) 
                        {
                            switch ($entete) 
                            {
                                case 'Database Instance Name':
                                    if (strpos($valeur, 'MSSQLSERVER')) 
                                    {
                                        $sqlserver = 1;
                                    } else {
                                        $sqlserver = 0;
                                    }
                                    $datasql = $valeur;
                                    echo "<td bgcolor='$BGCOLOR_DB' >".$valeur."</td>";
                                    break;

                                case 'Middleware Version':
                                    if ($valeur != '') 
                                    {
                                        echo "<td bgcolor='$BGCOLOR_DB' >".$valeur."</td>";
                                    } 
                                    else 
                                    {
                                        echo "<td >Pas d'info</td>";
                                    }
                                    break;

                                case 'Middleware Edition':
                                    if ($valeur != '') 
                                    {
                                        echo "<td bgcolor='$BGCOLOR_DB' >".$valeur."</td>";
                                    } 
                                    else 
                                    {
                                        echo "<td >Pas d'info</td>";
                                    }
                                    break;

                                case 'Instance':
                                    if ($valeur != '') 
                                    {
                                        if ($sqlserver == 0) 
                                        {
                                            echo "<td bgcolor='$BGCOLOR_DB' ><b><form id='$valeur' method='POST' action='application_by_db.php'><input type='hidden' name='db' value='$datasql'/> </form><a href='#' onclick='document.getElementById(\"$valeur\").submit()' >$valeur</a></b></td>";
                                        } 
                                        elseif ($sqlserver == 1) 
                                        {
                                            echo "<td bgcolor='$BGCOLOR_DB' ><b><form id='$datasql' method='POST' action='application_by_db.php'><input type='hidden' name='db' value='$datasql'/> </form><a href='#' onclick='document.getElementById(\"$datasql\").submit()' >$datasql</a></b></td>";
                                        }
                                    } 
                                    else 
                                    {
                                        echo "<td >Pas d'info</td>";
                                    }
                                    break;
                            }
                        }
                        echo "</tr>";
                    }
                } 
                else 
                {
                    echo "<tr>";
                    echo "<th colspan='6' bgcolor='silver' ><h4>PAS de BASE DE DONNEE liée(s) à ".$hostname."</h4></th>";
                    echo "</tr>";
                }
                echo "";
                $stmt->pdo = null;
            } 
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "<div id='mdw' class='tab-pane fade'>";
            $querymw = "select `Subsystem Type`,`Middleware Edition`,`Middleware Version`,`MDW Status`,`MDW Name`,`MDW Classification`from middleware_inventory where upper(hostname) like upper('".str_replace(' ', '', $machine)."%');";
            if ($stmt = $con->prepare($querymw)) 
            {
                $stmt->execute();
                $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                echo "<table id='' class='table table-hover' style='width: 100%;'>";
                echo "<thead>";
                if (count($tuples)) 
                {
                    echo "<tr>";
                    echo "<th colspan='6' bgcolor='silver' ><h4>MIDDLEWARE</h4></th>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<th >Subsystem Type</th><th >Middleware Edition</th><th >Middleware Version</th><th >MDW Status</th><th >MDW Name</th><th >MDW Classification</th>";
                    echo "</tr></thead></tbody>";
                    foreach ($tuples as $ligne) 
                    {
                        echo "<tr>";
                        $BGCOLOR_MDW = status_obso_middlewareversion($ligne['Middleware Version'], $host, $dbname, $user, $password);
                        foreach ($ligne as $entete => $valeur) {
                            switch ($entete) 
                            {
                                case 'Middleware Version':
                                    if ($valeur != '') 
                                    {
                                        echo "<td  bgcolor='$BGCOLOR_MDW' >".$valeur."</td>";
                                    } else {
                                        echo "<td >Pas d'info</td>";
                                    }
                                    break;

                                default:
                                    if ($valeur != '') 
                                    {
                                        echo "<td  bgcolor='$BGCOLOR_MDW' >".$valeur."</td>";
                                    } else {
                                        echo "<td >Pas d'info</td>";
                                    }
                                    break;
                            }
                        }
                        echo "</tr>";
                    }
                } 
                else 
                {
                    echo "<tr>";
                    echo "<th colspan='6' bgcolor='silver' ><h4>PAS de MIDDLEWARE lié(s) à ".$hostname."</h4></th>";
                    echo "</tr>";
                }
                $stmt->pdo = null;
            } 
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            ///////////////////////
            echo "<div id='hopex_mdw' class='tab-pane fade'>";
            $queryhopexmw = "select Servers, object_type, Deployable_Object, Product_Name, Lifecycle, app_type, soft_type, soft_version from HOPEX.OBJECT_BY_SERVERS where upper(servers) like upper('".str_replace(' ', '', $machine)."%') and lifecycle ='Live';";
            if ($stmt = $con->prepare($queryhopexmw)) 
            {
                $stmt->execute();
                $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                if (count($tuples)) 
                {
                    $columns_names = array_keys($tuples[0]); 
                    echo "<table id='' class='display' style='width: 100%;'>";
                    echo "<thead>";
                    echo "<tr>";
                    foreach ($columns_names as $col) 
                    {
                        echo '<th>'.$col.'</th>';
                    } 
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    foreach ($tuples as $tuple) 
                    {
                        switch($tuple['soft_type'])
							{
								case "Tomcat":
                                case "IIsWebService":
                                case "ApacheServer":
                                case "OracleAppHTTPServer":
                                case "HTTPServer":
									echo "<tr style='background-color: ".status_obso_middlewareversion($tuple['soft_version'],$host,$dbname,$user,$password)."'>";
                                    break;
                                case "SqlServer":	
                                case "MySql":
                                case "Db2Instance":
                                case "PostgreSQL":
                                case "OracleInstance":
                                    echo "<tr style='background-color: ".status_obso_dbversion($tuple['soft_version'],$host,$dbname,$user,$password)."'>";
                                    break;
    						}
                        $BGCOLOR_MDW = status_obso_middlewareversion($tuple['soft_version'], $host, $dbname, $user, $password);
                        foreach ($tuple as $entete => $col) 
                        {
                            switch (strtoupper($entete)) 
                            {
                                case 'soft_version':
                                    if ($col != '') 
                                    {
                                        echo "<td  style='background-color: '".$BGCOLOR_MDW."' >".$col."</td>";
                                    } else {
                                        echo "<td >Pas d'info</td>";
                                    }
                                    break;
                                default:
                                    echo '<td>'.$col.'</td>';
                                    break;
                            }
                        }
                        echo '</tr>';
                    }
                    echo "</tbody>";
                    echo "</table>";
        
                } 
                else 
                {
                    echo "Pas de d'objet déclaré dans HOPEX";
                }
                $stmt->pdo = null;
            }
            echo "</div>";
            ///////////////////////
            echo "<div id='pgmp' class='tab-pane fade'>";
            $querymw = "select * from cmdb.suivie_decommission where upper(hostname) like upper('%".str_replace(' ', '', $machine)."%');";
            if ($stmt = $con->prepare($querymw)) 
            {
                $stmt->execute();
                $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $today = new DateTime('today');
                echo "<table id='pgmp_cmdb' class='table table-hover' style='width: 100%;'>";
                echo "<thead>";
                if (count($tuples)) 
                {
                    echo "<tr>";
                    echo "<th colspan='12' bgcolor='silver' ><h4>Liste des decommissions déclarées</h4></th>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<th >ID</th><th >﻿Liste de<br/>machines</th><th >Base de<br/>données</th><th >Environnement</th><th >N° PGMP</th><th >Titre</th><th >Demandeur</th><th >Createur<br/>du ticket</th><th >Priorité</th><th >Date de<br/>Création<br/>du<br/>ticket<br/>PGMP</th><th >Date de<br/>décomm</th><th >Action<br/>faite</th>";
                    echo "</tr></thead><tbody>";
                    foreach ($tuples as $ligne) 
                    {
                        echo "<tr>";

                        foreach ($ligne as $entete => $valeur) 
                        {
                            switch ($entete) 
                            {
                                case 'hostname':

                                    $var_LIST_MACHINES_IMPACTEES = explode(',', $valeur);
                                    echo "<td >";
                                    if (isset($var_LIST_MACHINES_IMPACTEES)) 
                                    {
                                        foreach ($var_LIST_MACHINES_IMPACTEES as $value) 
                                        {
                                            echo "<form id='".$value."' method='POST' action='fiche_machine.php'><input type='hidden' name='machine' value='".$value."'/></form><a href='#' onclick='document.getElementById(\"$value\").submit()' ><b>".$value."</b></a><br/>";                        
                                            echo "<input type='hidden' name='LIST_MACHINES_IMPACTEES' value='".base64_encode(serialize($var_LIST_MACHINES_IMPACTEES))."' />";
                                        }
                                    }
                                    echo "</td>";
                                    break;
                                case 'database':
                                    $var_LIST_DB_IMPACTEES = explode(',', $valeur);
                                    echo "<td >";
                                    if (isset($var_LIST_DB_IMPACTEES)) 
                                    {
                                        foreach ($var_LIST_DB_IMPACTEES as $value) 
                                        {
                                            echo "<form id='".$value."' method='POST' action='application_by_db.php'><input type='hidden' name='db' value='".$value."'/></form><a href='#' onclick='document.getElementById(\"$value\").submit()' ><b>".$value."</b></a><br/>"; 
                                            echo "<input type='hidden' name='LIST_DB_IMPACTEES' value='". base64_encode(serialize($var_LIST_DB_IMPACTEES)) ."' />";
                                        }
                                    }
                                    echo "</td>";

                                    break;
                                case 'pgmp_ticket':
                                    echo "<td style='border:1px solid black;border-collapse: collapse; text-align: center; word-wrap: break-word; word-break: break-all;'><form id='".$valeur."' method='POST' action='set_decommission.php'><input type='hidden' name='ticketPGMP' value='".$valeur."'/><input type='hidden' name='consult' value='1'/> </form><a href='#' onclick='document.getElementById(\"$valeur\").submit()' target='frame_decom'><b>".$valeur."</b></a></td>";
                                    break;
                                case 'DateOfDecommission':
                                    $date_decom = new DateTime($valeur);
                                    if ($today > $date_decom) 
                                    {
                                        if ($ligne['DecommissionDone'] == 0) 
                                        {
                                            echo"<td bgcolor='#FF0000' ><b>$valeur</b></td>";
                                        } 
                                        else 
                                        {
                                            echo "<td bgcolor='#00FF00' ><b>$valeur</b></td>";
                                        }
                                    } 
                                    else 
                                    {
                                        echo"<td bgcolor='#FFFF00'>$valeur</td>";
                                    }
                                    break;
                                case 'DecommissionDone':
                                    if ($valeur == 1) 
                                    {
                                        echo "<td >FAIT</td>";
                                    } else {
                                        echo "<td >PAS ENCORE FAIT</td>";
                                    }
                                    break;
                                case 'COMMENTAIRES':
                                    break;

                                default:
                                    echo "<td >".wordwrap($valeur, 16, "<br/>\n", false)."</td>";
                                    break;
                            }
                        }
                        echo "</tr>";
                    }
                    $stmt->pdo = null;
                } 
                else 
                {
                    echo "<tr>";
                    echo "<th colspan='6' bgcolor='silver' ><h4>PAS de PGMP lié(s) à ".$hostname."</h4></th>";
                    echo "</tr>";
                }
            }
        } 
        echo "</tbody>";
        echo "</table>";
        //UO, OU_REF, CATEGORIE, MOIS, COMPTAGE, HOSTNAME, IP_ADDRESS, CHANGE_NUMBER, TYPE, APPLICATION, OS, COMMENT, PGMP, PGMP_COMMENT, REQUESTOR
        $query_pgmp="select PGMP,CATEGORIE,`PGMP COMMENT`,MOIS,`CHANGE NUMBER`,APPLICATION,HOSTNAME,UO,REQUESTOR from PGMP.PGMP_HISTORY where upper(hostname) = upper('".str_replace(' ', '', $hostname)."');";
        if ($stmt = $con->prepare($query_pgmp)) 
        {
            $stmt->execute();
            $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($tuples)) 
            {
                $columns_names = array_keys($tuples[0]); 
                echo "<br />";
                echo "<center><h3> HISTORIQUE PGMP</h3></center>";
                echo "<table  id='pgmp_PGMP' class='table table-hover' style='width: 100%;'>";
                echo "<thead>";
                echo "<tr>";
                foreach ($columns_names as $col) 
                {
                    echo "<th>".$col."</th>";
                } 
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($tuples as $tuple) 
                {
                    echo "<tr>";
                    foreach ($tuple as $entete => $col) 
                    {
                        switch (strtoupper($entete)) 
                        {
                            case 'PGMP':
                                echo "<td><form id='$col' method='POST' action='PGMP.php'><input type='hidden' name='PGMP' value='$col'/> </form><a href='#' onclick='document.getElementById(\"$col\").submit()' ><b>$col</b></a></td>";
                                break;
                            default:
                                echo "<td>".$col."</td>";
                                break;
                        }
                    }
                    echo "</tr>";
                } 
                echo "</tbody>";
                echo "</table>";
            } 
            else 
            {
                echo "Pas de résultat";
            }
            $stmt->pdo = null;
        }
    }
    echo "</div>";
    $toto = ob_get_contents();
    echo "<div id='debug' class='tab-pane fade'>";
    echo "<details>";
    echo "<summary>Details du debug</summary>";
    /////////////////////////////////
    // Page de debug
    /////////////////////////////////
    echo "<p class='debug'>";
    error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

    $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
    $var_ignore=array("GLOBALS", "_ENV", "_SERVER","_GET","host","dbname","user","password","port","socket"); // Détermine les var à ne pas afficher
    echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
    $nom_fonction=__FUNCTION__;
    if (isset($nom_fonction)&&$nom_fonction!="")
    {
        echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
    }
    foreach ($variables as $key=>$valeur)
    {
        if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
            {
            echo "<pre class='debug'>";
            echo ("$".$key." => ");
            print_r($valeur);
            echo "</pre>\n";
            }
    }

    echo "</p>";

    echo "</details>";
    echo "</div>";
    echo "</div>";
		
	echo "</div>";
	
	echo "<center>";
    echo "<form id='exporttopdf' class='form-horizontal' method='POST' enctype='multipart/form-data' action='export_to_pdf.php'>";
    echo "<input type='hidden' name='EXPORTPDF' value='true'>";

    echo "<input type='hidden' name='DATA' value='".base64_encode(serialize($toto))."'>";

    echo "<input type='submit' class='btn' value='Export en PDF'>";
    echo "</form>";
    echo "<a href=\"javascript:history.go(-1)\">Retour à la page précédente</a>";
    echo "</center>";
    ?>
</body>

</html>
