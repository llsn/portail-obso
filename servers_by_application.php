<?php
    require 'variable-db.php';
    require 'functions.php';

        // debug de variables
    // echo "<p class=\"debug\">";
    // error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

    // $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
    // $var_ignore=array("GLOBALS", "_ENV", "_SERVER"); // Détermine les var à ne pas afficher
    // echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
    // $nom_fonction=__FUNCTION__;
    // if (isset($nom_fonction)&&$nom_fonction!="") {
      // echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
    // }
    // foreach ($variables as $key=>$value)
    // {
      // if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
      // {
        // echo "<pre class=\"debug\">";
        // echo ("$".$key." => ");
        // print_r($value);
        // echo "</pre>\n";
      // }
    // }
    $table = isset($_POST['application']) ? $_POST['application'] : null;
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
    or die('Could not connect to the database server'.pdo_connect_error());
?>
<html>

<head>
	<title>Recherche serveurs par application</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Chargement des feuilles de style -->
	<link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="vendor/datatables/datatables/media/css/jquery.dataTables.css" />
	<link rel="stylesheet" type="text/css" href="vendor/datatables/datatables/media/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="Datatables/AutoFill-2.3.4/css/autoFill.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="Datatables/Responsive-2.2.3/css/dataTables.responsive.min.css" />
    <link rel="stylesheet" type="text/css" href="Datatables/Responsive-2.2.3/css/responsive.jqueryui.min.css" />
    <!-- <link rel="stylesheet" type="text/css" href="Datatables/FixedHeader-3.1.6/css/dataTables.fixedHeader.min.css" />
    <link rel="stylesheet" type="text/css" href="Datatables/FixedColumns-3.3.0/css/dataTables.fixedColumns.min.css" /> -->

	<!-- Chargement des scripts javascripts pour la mise en forme des tableaux-->
    <script type="text/javascript" language="javascript" src="Datatables/datatables.min.js"></script>
    <script type="text/javascript" language="javascript" src="vendor/components/jquery/jquery.min.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/jQuery-3.3.1/jquery-3.3.1.js"></script>
	<script type="text/javascript" language="javascript" src="bootstrap/js/bootstrap.min.ls.js"></script>
	<script type="text/javascript" language="javascript" src="Datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="vendor/twbs/bootstrap/js/dist/tab.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/AutoFill-2.3.4/js/dataTables.autoFill.min.js"></script>
    <!-- <script type="text/javascript" language="javascript" src="Datatables/FixedHeader-3.1.6/js/dataTables.fixedHeader.min.js"></script> -->
	<script type="text/javascript" language="javascript" src="Datatables/FixedColumns-3.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/Responsive-2.2.3/js/responsive.jqueryui.min.js"></script>
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
                        "pagingType": "full_numbers"
                        
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
		<h1 class="jumbotron">
			<center>Trouver les serveurs utilisées par une application</center>
		</h1>
	</div>
	<form id="application" class="form-horizontal" method="POST" enctype="multipart/form-data"
		action="servers_by_application.php">
		<acronym
			title="Saisissez ou sélectionnez le texte désiré et tapez sur 'ENTREE' ou cliquez sur le bouton 'Valider'"><input
				list="list_app" name="application" size="30" maxlength="30" value="<?php if ($table != '') {
    echo $table;
} ?>" class="input-lg" onchange='document.getElementById("application").submit()'
				onclick="if(this.value!='')this.value=''"></acronym>
		<datalist id="list_app">
			<?php
    
    $querytable = 'select application from cmdb.application order by application;';
    if ($stmt = $con->prepare($querytable)) {
        $stmt->execute();
        while ($resulttable = $stmt->fetch()) {
            if ($table == $resulttable[0]) {
                echo '<option value="'.$resulttable[0].'" selected onclick=\'document.getElementById(application).submit()\'>'.$resulttable[0].'</option>';
            } else {
                echo '<option value="'.$resulttable[0].'" onclick=\'document.getElementById(application).submit()\'>'.$resulttable[0].'</option>';
            }

            $stmt->pdo = null;
        }
    }

?>
		</datalist>
		<input type="submit" class="btn">
	</form>
	<?php
if ($table != '') {

    $querytable = "call cmdb.SERVER_BY_APPLI('".$table."');";
   
    if ($stmt = $con->prepare($querytable)) {
        echo '<table class="display" >';
        echo '<thead>';
        echo '<tr>';
        echo "<th>Hostname</th><th>Operating Environment</th><th>OS NAME</th><th>OS VERSION</th><th>BUSINESS SERVICES</th>";
        echo '</tr>';
        echo '</thead>';
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
                        case 'HOSTNAME':
                            $hostname = $valeur_sub_server;
                            break;
                        case 'operatingenvironment':
                            $operatingenvironment = $valeur_sub_server;
                            break;
                        case 'osname':
                            $osname = $valeur_sub_server;
                            break;
                        case 'osversion':
                            $osversion = $valeur_sub_server;
                            break;
                        case 'BUSINESSSERVICES':
                            $BUSINESSSERVICES = $valeur_sub_server;
                            break;
                    }
                }
                $tableau = array($hostname, $operatingenvironment,$osname, $osversion, $BUSINESSSERVICES);
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
                        <td>".$operatingenvironment."</td>
                        <td>".$osname."</td>
                        <td bgcolor='".status_obso_osversion($osversion, $host, $dbname, $user, $password)."'>".$osversion."</td>
                        <td>".$BUSINESSSERVICES."</td>
                        
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
    $stmt->pdo = null;

// // debug de variables
echo "<p class=\"debug\">";
error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

$variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
$var_ignore=array("GLOBALS", "_ENV", "_SERVER","_GET","host","dbname","user","password","port","socket"); // Détermine les var à ne pas afficher
echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
$nom_fonction=__FUNCTION__;
if (isset($nom_fonction)&&$nom_fonction!="")
{
	echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
}
foreach ($variables as $key=>$value)
{
	if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
   	{
		echo "<pre class=\"debug\">";
		echo ("$".$key." => ");
		print_r($value);
		echo "</pre>\n";
  	}
}
echo "</p>";
?>
</body>
</html>
