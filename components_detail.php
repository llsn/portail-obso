<?php
    require 'variable-db.php';
    $var_environnement = isset($_POST['var_environnement']) ? $_POST['var_environnement'] : null;
    $var_application = isset($_POST['var_application']) ? $_POST['var_application'] : null;
    $var_consult_component = isset($_POST['var_consult_component']) ? $_POST['var_consult_component'] : 'false';
    $var_logical_CI=isset($_POST['var_logical_CI']) ? $_POST['var_logical_CI'] : null;
    // Initialisation de la connexion à la base de données
    $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8',$user,$password)
    or die ('Could not connect to the database server' . pdo_connect_error());
?>

<html>
	<head>
		<title>Détail d'un composant</title>
        <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />

    <!-- Chargement de DataTables -->
    <link rel="stylesheet" type="text/css" href="Datatables/default/datatables.css"/>
    <link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/tableau_formulaire.css" />
    <script type="text/javascript" src="Datatables/default/datatables.js"></script>
    <script type="text/javascript" language="javascript" src="js/pace.min.js"></script>

    <!-- chargement des autres modules -->
    <!-- Tabs -->
    <!-- <script type="text/javascript" language="javascript" src="vendor/components/jquery/jquery.min.js"></script>
	<script type="text/javascript" language="javascript" src="bootstrap/js/bootstrap.min.ls.js"></script>
	<script type="text/javascript" language="javascript" src="vendor/datatables/datatables/media/js/jquery.dataTables.min.js"></script> -->
    <!-- <script type="text/javascript" language="javascript" src="vendor/twbs/bootstrap/js/dist/tab.js"></script> -->

	<!-- Initialisation des tableaux de données de la page -->
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
                            api.$('td').dblclick
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
		div.dataTables_wrapper 
        {
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
        <h1 class="jumbotron"><center>Détail des composants d'une application</center></h1>
        </div>
        <div>
            <?php
            if($var_consult_component=='true')
            {
                $query="select * from eam.app_details where `Logical CI Name` like '".$var_logical_CI."%'";
            }
            else
            {
            ?>
                <form id="requete" class="form-horizontal" method="POST" enctype="multipart/form-data" action="components_detail.php">
                <center>
                    <div class="divTable paleBlueRows">
                        <div class="divTableHeading">
                            <div class="divTableRow">
                                <div class="divTableHead">Environnement</div>
                                <div class="divTableHead">Application name</div>
                            </div>
                        </div>
                        <div class="divTableBody">
                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input list="list_environnement" name="var_environnement" size="30" maxlength="30" onclick="this.value=''" onchange='document.getElementById("requete").submit()'value="<?php if ($var_environnement != '') { echo $var_environnement;} ?>" >
                                    <datalist id="list_environnement">
                                        <?php
                                            $queryenvrionnement = "select distinct `OPERATINGENVIRONMENT` from eam.app_details order by `OPERATINGENVIRONMENT` ;";
                                            if ($stmt = $con->prepare($queryenvrionnement)) 
                                            {
                                                $stmt->execute();
                                                while ($resulttable = $stmt->fetch()) 
                                                {
                                                    if ($var_environnement == $resulttable[0]) 
                                                    {
                                                        echo '<option value="'.$resulttable[0].'" selected >'.$resulttable[0].'</option>';
                                                    } else {
                                                        echo '<option value="'.$resulttable[0].'" >'.$resulttable[0].'</option>';
                                                    }
                                                    $stmt->pdo = null;
                                                }
                                            }
                                        ?>
                                    </datalist>
                                </div>
                                <div class="divTableCell">
                                    <?php
                                    if($var_environnement!=null)
                                    {
                                    ?>
                                    <input list="list_application" name="var_application" size="30" maxlength="30" onclick="this.value=''" onchange='document.getElementById("requete").submit()'value="<?php if ($var_application != '') { echo $var_application;} ?>" >
                                    <datalist id="list_application">
                                        <?php
                                            $queryapplication = "select distinct `Application name` from eam.app_details where `OPERATINGENVIRONMENT` = '".$var_environnement."' order by `Application name` ;";
                                            if ($stmt = $con->prepare($queryapplication)) 
                                            {
                                                $stmt->execute();
                                                while ($resulttable = $stmt->fetch()) 
                                                {
                                                    if ($var_application == $resulttable[0]) 
                                                    {
                                                        echo '<option value="'.$resulttable[0].'" selected >'.$resulttable[0].'</option>';
                                                    } else {
                                                        echo '<option value="'.$resulttable[0].'" >'.$resulttable[0].'</option>';
                                                    }
                                                    $stmt->pdo = null;
                                                }
                                            }
                                        ?>
                                    </datalist>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </center>
            </form>
        </div>
        <?php
            $query="select * from eam.app_details where";
            $flag_data=0;
            if($var_environnement!=null)
            {
                $flag_data++;
                $query=$query." `OPERATINGENVIRONMENT` like '".$var_environnement."%' and";
            }
            if($var_application!=null)
            {
                $flag_data++;
                $query=$query." `Application Name` like '".$var_application."%' and";
            }
            
            $query=$query . ';';
            $query=str_replace(" and;",";",$query);

        }                    
        ?>
        <div class="container">
            <?php
            if ($flag_data >= 2 || $var_consult_component=='true')
            {
            ?>
                <div id="details" class="tab-pane fade in active">
                    <?php
                        if ($stmt = $con->prepare($query)) 
                        {
                            $stmt->execute();
                            $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($tuples)) 
                            {
                                $columns_names = array_keys($tuples[0]);
                    ?>
                    <table id="" class="display" style="width: 100%;">
                        <thead>
                            <tr>
                                <?php
                                    foreach ($columns_names as $col) 
                                    {
                                        echo '<th>'.$col.'</th>';
                                    } 
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    foreach ($tuples as $tuple) {
                                        echo '<tr>';
                                        foreach ($tuple as $entete => $col) {
                                            switch (strtoupper($entete)) 
                                            {
                                                case 'HOSTNAME':
                                                    echo '<td><form id="'.$col.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$col."\"/> </form><a href='#' onclick='document.getElementById(\"".$col."\").submit()' target=\"_blank\"><b>".$col.'</b></a></td>';
                                                    break;
                                                default:
                                                    echo '<td>'.$col.'</td>';
                                                    break;
                                            }
                                        }
                                        echo '</tr>';
                                    } ?>
                        </tbody>
                    </table>
                    <?php
                            } 
                            else 
                            {
                                echo 'Pas de résultat';
                            }

                            $stmt->pdo = null;
                        }
                    ?>
                </div>
                <?php
                }
                ?>
                </div>

                    <?php
                        // echo "<p class=\"debug\">";
                        // error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.
                    
                        // $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
                        // $var_ignore=array("GLOBALS", "_ENV", "_SERVER","_GET","host","dbname","user","password","port","socket"); // Détermine les var à ne pas afficher
                        // echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
                        // $nom_fonction=__FUNCTION__;
                        // if (isset($nom_fonction)&&$nom_fonction!="")
                        // {
                        //     echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
                        // }
                        // foreach ($variables as $key=>$valeur)
                        // {
                        //     if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
                        //         {
                        //         echo "<pre class=\"debug\">";
                        //         echo ("$".$key." => ");
                        //         print_r($valeur);
                        //         echo "</pre>\n";
                        //         }
                        // }
                    
                        // echo "</p>";
                    ?>

            </div>
    </body>
</html>
