<?php
    require 'variable-db.php';
    require 'functions.php';
//     // debug de variables
// 	echo "<p class=\"debug\">";
// 	error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.
//
// 	$variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
// 	$var_ignore=array("GLOBALS", "_ENV", "_SERVER"); // Détermine les var à ne pas afficher
// 	echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
// 	$nom_fonction=__FUNCTION__;
// 	if (isset($nom_fonction)&&$nom_fonction!="")
// 	{
// 		echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
// 	}
// 	foreach ($variables as $key=>$value)
// 	{
// 		if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
// 	   	{
// 			echo "<pre class=\"debug\">";
// 			echo ("$".$key." => ");
// 			print_r($value);
// 			echo "</pre>\n";
// 	  	}
// 	}
// 	echo "</p>";
    $machine = isset($_POST['id_machine']) ? $_POST['id_machine'] : null;
    $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());
?>
<html>
	<head>
		<title>Virtuel -> Hardawre</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- Chargement de DataTables -->
        <link rel="stylesheet" type="text/css" href="Datatables/default/datatables.css"/>
    <link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
    <script type="text/javascript" src="Datatables/default/datatables.js"></script>
    <script type="text/javascript" language="javascript" src="js/pace.min.js"></script>
    
 	<!-- Initialisation des tableaux de données de la page -->
     <script>
        
        $(document).ready
        (
            function () 
            {
                $('table.display').DataTable
                ( 
                    {
                        language: 
                        {
                        url: "Datatables/French.json"
                        },
                        dom: 'Bfrtip',
                        lengthMenu: 
                        [
                            [ 10, 25, 50, -1 ],
                            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                        ],
                        buttons: 
                        [
                            {
                                extend: 'print',
                                text: 'Print current page',
                                exportOptions: 
                                {
                                    modifier: 
                                    {
                                        page: 'current'
                                    }
                                }
                            },
                            'copy',
                            'excel',
                            'csv',
                            {
                                extend: 'pdfHtml5',
                                orientation: 'landscape',
                                pageSize: 'A3'
                            },
                            'pageLength'
                        ],
                        "initComplete": function () 
                        {
                            var api = this.api();
                            api.$('td').click
                            ( 
                                function () 
                                {
                                    api.search( this.innerHTML ).draw();
                                } 
                            );
                        },
                        "pagingType": "full_numbers",
						fixedHeader: true
                        
                    }
                     
                );
                
                
                
            }
        );
        
        function chargement() 
        {
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
	    <div>
			<h1 class="jumbotron"><center>Trouver une machine physique par ces machines virtuels</center></h1>
		</div>
	<form id="form_machine" class="form-horizontal" method="POST" enctype="multipart/form-data" action="dependance_hardware.php" >
    <acronym title="Saisissez ou sélectionnez le texte désiré et tapez sur 'ENTREE' ou cliquez sur le bouton 'Valider'">
    <input list="NameServer" class="input-lg" name ="id_machine" size="50" value="<?php echo $machine; ?>" onclick="if(this.value!='')this.value=''">
	</acronym>
	<datalist id="NameServer">
	<option value="" >choisissez le nom du serveur</option>
<?php

    $querynameserver = "SELECT configurationname_wo_extension from cmdb.system_inventory where COMPUTERSYSTEM_VIRTUAL = 'true' order by configurationname_wo_extension";
    if ($stmt = $con->prepare($querynameserver)) {
        $stmt->execute();
        while ($resultnameserver = $stmt->fetch()) {
            echo $resultnameserver[0];
            if ($machine == $resultnameserver[0]) {
                echo "<option value='".$resultnameserver[0]."' selected onclick=\"document.getElementById(application).submit()\">".$resultnameserver[0].'</option>';
            } else {
                echo "<option value='".$resultnameserver[0]."' onclick=\"document.getElementById(application).submit()\">".$resultnameserver[0].'</option>';
            }

            $stmt->pdo = null;
        }
    }
?>
    </datalist>
 	<input type="submit" class="btn btn-lg">
	</form>
<?php

    $count = 0;
    $queryhardwarehost = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(hardwarehost,'.',1),',',1) as hardwarehost  FROM cmdb.system_inventory where upper(configurationname_wo_extension) like upper('".$machine."%');";
    // echo $queryhardwarehost;
    if ($stmt_hardware = $con->prepare($queryhardwarehost)) {
        $stmt_hardware->execute();
        $hardware = $stmt_hardware->fetchAll(PDO::FETCH_ASSOC);
        // echo "<br/>";
        // print_r($harware);
    }
    if ($hardware[0]['hardwarehost'] != '') {
        echo "<table class=\"display\"><thead><tr><th colspan='4'><form id=\"".$hardware[0]['hardwarehost'].'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$hardware[0]['hardwarehost']."\"/> </form><a href='#' onclick='document.getElementById(\"".$hardware[0]['hardwarehost']."\").submit()' target=\"_blank\"><center><b>".$hardware[0]['hardwarehost'].'</b></center></a></th></tr>';
        echo '<tr><th>Sub Server Host</th><th>OSNAME</th><th>OSVERSION</th><th>STATUS</th></tr>';
        echo '</thead><tbody>';
        $querydb = "SELECT configurationname_wo_extension FROM cmdb.system_inventory where upper(configurationname_wo_extension) like upper('".$machine."%');";

        if ($stmt = $con->prepare($querydb)) {
            $stmt->execute();
            $result_server = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result_server)) {
                foreach ($result_server as $ligne_server) {
                    foreach ($ligne_server as $entete_server => $valeur_server) {
                        $queryhardwarehost = "select distinct hardwarehost from cmdb.system_inventory where upper(configurationname_wo_extension) like upper('".$valeur_server."%');";

                        if ($stmt_hardwarehost = $con->prepare($queryhardwarehost)) {
                            $stmt_hardwarehost->execute();
                            $_result_hardwarehost = $stmt_hardwarehost->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($_result_hardwarehost as $ligne_hardwarehost) {
                                foreach ($ligne_hardwarehost as $entete_hardwarehost => $valeur_hardwarehost) {
                                    $querysubserver = "select * from cmdb.system_inventory where upper(hardwarehost) like upper('".$valeur_hardwarehost."%');";

                                    if ($stmt_sub_server = $con->prepare($querysubserver)) {
                                        $stmt_sub_server->execute();
                                        $_result_sub_server = $stmt_sub_server->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($_result_sub_server as $ligne_sub_server) {
                                            foreach ($ligne_sub_server as $entete_sub_server => $valeur_sub_server) {
                                                switch ($entete_sub_server) {
                                                case 'CONFIGURATIONNAME_WO_EXTENSION':
                                                    $hostname = $valeur_sub_server;
                                                    break;
                                                case 'OSNAME':
                                                    $osname = $valeur_sub_server;
                                                    break;
                                                case 'OSVERSION':
                                                    $osversion = $valeur_sub_server;
                                                    break;
                                                case 'STATUS':
                                                    $status = $valeur_sub_server;
                                                    break;
                                            }
                                            }
                                            $tableau = array($hostname, $osname, $osversion, $status);
                                            ++$count;
                                            echo '<tr><td><form id="'.$hostname.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$hostname."\"/> </form><a href='#' onclick='document.getElementById(\"".$hostname."\").submit()' target=\"_blank\"><b>".$hostname.'</b></a></td><td>'.$osname."</td><td bgcolor='".status_obso_osversion($osversion, $host, $dbname, $user, $password)."'>".$osversion.'</td><td>'.$status.'</td></tr>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    } else {
        echo "<table class=\"display\"><thead><tr><th colspan='4'><tbody><tr><th><center>Pas de lien trouvé(s) entre <u><b><form id=\"".$machine.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$machine."\"/></form><a href='#' onclick='document.getElementById(\"".$machine."\").submit()' target=\"_blank\"><center><b>".$machine."</b></center></a></b></u> et d'autres machines. Soit il manque des informations dans la CMDB soit cette machine est une machine physique</center></th></tr>";
    }
 

 echo '</tbody></table>';
?>
</body>
</html>