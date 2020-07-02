<?php
    require 'variable-db.php';
    require 'functions.php';
    $theme="Bootstrap4";
    $machine = isset($_POST['id_machine']) ? $_POST['id_machine'] : null;
    $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());
?>
<html>
<head>
	<!-- définition des caractères lié à l'affichage -->
	<meta charset="utf-8">
	<!-- Titre de la page -->
	<title>Contenu de la machine physique <?php echo "$machine"; ?></title>
	<!-- définition des metadata de la page  -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Chargement de DataTables -->
    <link rel="stylesheet" type="text/css" href="Datatables/default/datatables.css"/>
    <link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
    <script type="text/javascript" src="Datatables/default/datatables.js"></script>
    <script type="text/javascript" language="javascript" src="js/pace.min.js"></script>
    <script>
        
        $(document).ready
        (
            function () 
            {
                $('table.display').DataTable
                ( 
                    { 
                        "autoWidth": false,
                        responsive: true,
                        keys: true,
                        select: true,
                        fixedHeader: true,
                        colReorder: true,
                        language: 
                        {
                        url: "Datatables/French.json"
                        },
                        dom: 'Bfrtip',
                        lengthMenu: 
                        [
                            [ 10, 25, 50, -1 ],
                            [ '10 lgnes', '25 lignes', '50 lignes', 'toutes les lignes' ]
                        ],
                        buttons: 
                        [
                            'print',
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
	<!-- <style type="text/css">
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
	</style> -->
	<script>
		window.paceOptions = {
			ajax: true,
			element: true
		}
	</script>
</head>
	<body>
        <script src="js/pace.min.js"></script>
	    <div>
			<h1 class="page-header"><center>Contenu de la machine physique <?php echo $machine ?> </center></h1>
        </div>
        
        <form id="form_machine" class="form-horizontal" method="POST" enctype="multipart/form-data" action="virtual_by_hardware.php" >
            <div class="form-row align-items-center" >
                <div class="col-auto">
                    <acronym title="Saisissez ou sélectionnez le texte désiré et tapez sur 'ENTREE' ou cliquez sur le bouton 'Valider'">
                        <input list="NameServer" class="form-control mb-2" width="30%" name ="id_machine" size="50" value="<?php echo $machine; ?>" onclick="if(this.value!='')this.value=''">
                    </acronym>
                    <datalist id="NameServer">
                        <option value="" >Choisissez le nom du serveur Physique</option>
                        <?php

                            $querynameserver = 'select distinct SUBSTRING_INDEX(SUBSTRING_INDEX(HARDWAREHOST,\'.\',1),\',\',1) from cmdb.system_inventory order by HARDWAREHOST';
                            if ($stmt = $con->prepare($querynameserver)) {
                                $stmt->execute();
                                while ($resultnameserver = $stmt->fetch()) {
                                    echo $resultnameserver[0];
                                    if ($machine == $resultnameserver[0]) {
                                        echo "<option value='".$resultnameserver[0]."' selected onclick=\"document.getElementById(form_machine).submit()\">".$resultnameserver[0].'</option>';
                                    } else {
                                        echo "<option value='".$resultnameserver[0]."' onclick=\"document.getElementById(form_machine).submit()\">".$resultnameserver[0].'</option>';
                                    }

                                    $stmt->pdo = null;
                                }
                            }
                        ?>
                    </datalist>
                <div>
                <input type="submit" class="btn btn-lg">
            </div>
        </form>
        <br/>
        <?php

            $count = 0;

            if ($machine != null) 
            {
                ?>
                <table id='example' class='display' width='95%'>
                    <thead>
                        <tr>
                            <th colspan='12'>
                                <form id="<?php echo $machine ?>" method="POST" action="fiche_machine.php">
                                    <input type='hidden' name='machine' value='<?php echo $machine ?>'/> 
                                </form>
                                <a href='#' onclick='document.getElementById("<?php echo $machine ?>").submit()' >
                                    <center>
                                        <b>
                                            <?php echo $machine ?>
                                        </b>
                                    </center>
                                </a>
                            </th>  
                        </tr>
                        <tr>
                            <th>Sub Server Host</th>
                            <th>Application(s)</th>
                            <th>OSNAME</th>
                            <th>OSVERSION</th>
                            <th>STATUS</th>
                            <th>DB Subsystem Type</th>
                            <th>DB Middleware Edition</th>
                            <th>DB Middleware Version</th>
                            <th>MDW Subsystem Type</th>
                            <th>MDW Middleware Edition</th>
                            <th>MDW Middleware Version</th>
                            <th>DB Instance Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $querydb = "SELECT * FROM cmdb.global_inventory where upper(HARDWAREHOST) like upper('%".$machine."%');";
                    // echo $querydb;
                    if ($stmt = $con->prepare($querydb)) 
                    {
                        $stmt->execute();
                        $result_server = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($result_server)) 
                        {
                            foreach ($result_server as $ligne_server) 
                            {
                                foreach ($ligne_server as $entete_sub_server => $valeur_sub_server) 
                                {                  
                                    switch ($entete_sub_server) 
                                    {
                                        case 'CONFIGURATIONNAME_WO_EXTENSION':
                                            $hostname = $valeur_sub_server;
                                            break;
                                        case 'BUSINESSSERVICES':
                                            $business_services = "- ".str_replace(',','<br/> - ',$valeur_sub_server);
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
                                        case 'DB Subsystem Type':
                                            $DB_Subsystem_Type = $valeur_sub_server;
                                            break;
                                        case 'DB Middleware Edition':
                                            $DB_Middleware_Edition = $valeur_sub_server;
                                            break;
                                        case 'DB Middleware Version':
                                            $DB_Middleware_Version = $valeur_sub_server;
                                            break;
                                        case 'MDW Subsystem Type':
                                            $MDW_Subsystem_Type = $valeur_sub_server;
                                            break;
                                        case 'MDW Middleware Edition':
                                            $MDW_Middleware_Edition = $valeur_sub_server;
                                            break;
                                        case 'MDW Middleware Version':
                                            $MDW_Middleware_Version = $valeur_sub_server;
                                            break;
                                        case 'DB Instance Name':
                                            $DB_Instance_Name = $valeur_sub_server;
                                            break;
                                    }
                                }
                                $tableau = array($hostname,$business_services, $osname, $osversion, $status,$DB_Subsystem_Type,$DB_Middleware_Edition,$DB_Middleware_Version,$DB_Instance_Name);
                                ++$count;
                                echo "<tr>
                                        <td>
                                            <form id='".$hostname."' method=\"POST\" action=\"fiche_machine.php\">
                                                <input type=\"hidden\" name=\"machine\" value=\"".$hostname."\"/> 
                                            </form>
                                            <a href='#' onclick='document.getElementById(\"".$hostname."\").submit()' >
                                                <b>".$hostname."</b>
                                            </a>
                                        </td>
                                        <td>".$business_services."</td>
                                        <td>".$osname."</td>
                                        <td bgcolor='".status_obso_osversion($osversion, $host, $dbname, $user, $password)."'>".$osversion."</td>
                                        <td>".$status."</td>
                                        <td>".$DB_Subsystem_Type."</td>
                                        <td>".$DB_Middleware_Edition."</td>
                                        <td bgcolor='".status_obso_dbversion($DB_Middleware_Version, $host, $dbname, $user, $password)."'>".$DB_Middleware_Version."</td>
                                        <td>".$MDW_Subsystem_Type."</td>
                                        <td>".$MDW_Middleware_Edition."</td>
                                        <td bgcolor='".status_obso_middlewareversion($MDW_Middleware_Version, $host, $dbname, $user, $password)."'>".$MDW_Middleware_Version."</td>
                                        <td>".$DB_Instance_Name."</td>
                                    </tr>";
                            }
                        }
                    }
                }
                else 
                {
                    ?>
                    <table class="display">
                        <thead>
                            <tr colspan='4'>
                                <th>
                                    <center>Pas d'information trouvée ... :S</center>
                                </th>
                            </tr>
                            <?php
                }
                ?>
                        </tbody>
                    </table>
                <?php
            // // debug de variables
            // echo "<p class=\"debug\">";
            // error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

            // $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
            // $var_ignore=array("GLOBALS", "_ENV", "_SERVER","_GET","host","dbname","user","password","port","socket"); // Détermine les var à ne pas afficher
            // echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
            // $nom_fonction=__FUNCTION__;
            // if (isset($nom_fonction)&&$nom_fonction!="")
            // {
            // 	echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
            // }
            // foreach ($variables as $key=>$value)
            // {
            // 	if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
            //    	{
            // 		echo "<pre class=\"debug\">";
            // 		echo ("$".$key." => ");
            // 		print_r($value);
            // 		echo "</pre>\n";
            //   	}
            // }
            // echo "</p>";
        ?>
    </body>
</html>
