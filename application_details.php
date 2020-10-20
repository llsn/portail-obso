<?php
    require 'variable-db.php';
    // // debug de variables
    //         echo "<p class=\"debug\">";
    //         error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

    //         $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
    //         $var_ignore=array("GLOBALS", "_ENV", "_SERVER","_GET","host","dbname","user","password","port","socket"); // Détermine les var à ne pas afficher
    //         echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
    //         $nom_fonction=__FUNCTION__;
    //         if (isset($nom_fonction)&&$nom_fonction!="")
    //         {
    //         	echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
    //         }
    //         foreach ($variables as $key=>$value)
    //         {
    //         	if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
    //            	{
    //         		echo "<pre class=\"debug\">";
    //         		echo ("$".$key." => ");
    //         		print_r($value);
    //         		echo "</pre>\n";
    //           	}
    //         }
    //         echo "</p>";
            $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
            or die('Could not connect to the database server'.pdo_connect_error());
?>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Applications liés à une base</title>
	<!-- définition des metadata de la page  -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
	
<?php
    $app = isset($_POST['app']) ? $_POST['app'] : null;
    $environment = isset($_POST['environment']) ? $_POST['environment'] : null;
//	$component= isset($_POST['ComponentName']) ? $_POST['ComponentName'] : NULL;
    if ($app != null) {
        ?>
        <table id='' class='display'>
        <thead>
            <tr>
                <th colspan='16'>
                    <H3>
                        <center>COMPOSANT lié à l'application <b><u><?php echo $app ?></u></b> tiré de l'EAM</center>
                    </H3>
                </th>
            </tr>
            <tr>
                <th>Application name</th>
                <th>Configurationname</th>
                <th>Logical CI Name</th>
                <th>HOSTNAME</th>
                <th>OPERATINGENVIRONMENT</th>
                <th>Stereotype</th>
                <th>IT support team L2</th>
                <th>IT owner team L3</th>
                <th>Operating System</th>
                <th>Main Middleware</th>
                <th>Based on product</th>
                <th>Used Framework</th>
                <th>Other technology</th>
                <th>It support team cal</th>
                <th>Citrix version</th>
                <th>Java</th>
            </tr>
        </thead>
        <?php
        //		$queryapp="SELECT * FROM eam.view_app_details where upper(`Application name`) = upper('".$app."') and upper(`O11: SoftwareDeployment Name:Env`) = upper('".$environment."') and upper(`O5: Component name`) = upper('".$component."') order by `O7: Server name`;";
        $queryapp = "SELECT * FROM eam.view_app_details where upper(`Application name`) = upper('".$app."') and upper(`OPERATINGENVIRONMENT`) = upper('".$environment."') order by `HOSTNAME`;";
        // echo "$queryapp";
        if ($stmt = $con->prepare($queryapp)) {
            $stmt->execute();
            $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($tuples)) 
            {
                /*echo "<pre>";
                print_r($tuples);
                echo "</pre>";*/
                ?>

                    <tbody>
                    <?php
                    foreach ($tuples as $ligne) 
                    {
                        echo '<tr>';
                        foreach ($ligne as $entete => $valeur) 
                        {
                            if ($entete == 'HOSTNAME') 
                            {
                                echo '  <td>
                                            <form id="'.$valeur.'" method="POST" action="fiche_machine.php">
                                                <input type="hidden" name="machine" value="'.$valeur."\"/> 
                                            </form>
                                            <a href='#' onclick='document.getElementById(\"".$valeur."\").submit()' target=\"_blank\">
                                                <b>".$valeur.'</b>
                                            </a>
                                        </td>';
                            } 
                            else 
                            {
                                echo '<td>'.$valeur.'</td>';
                            }
                        }
                        echo '</tr>';
                    }
            } 
            else 
            {
            ?>

                    <tbody>
                    <tr>
                        <th colspan='16'>
                            <center>PAS de détails liés à l'APPLICATION <?php echo $app ?></center>
                        </th>
                    </tr>

            <?php
            }
            $stmt->pdo = null;
        }
    } else {
        echo "erreur lors de l'a transmission de la variable !!!";
    }
?>                    </tbody>
                </table>
</body>
</html>