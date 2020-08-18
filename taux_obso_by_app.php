<?php
	// Simple function to write text file

	shell_exec("rm -f /var/www/html/taux_obso_by_app.html");	
	function writeTextFile($fileName,$text) {
 	 
  		$session = fopen($fileName,"a+");
    	fputs($session,$text);
    	fclose($session);
	}
 
	ob_start(); // Début de l'enregistrement
    require 'variable-db.php';
    require 'functions.php';
    $list_application=array();
    // $STYLE = "style='border:1px solid black;border-collapse: collapse;text-align:center;'";
    // $ping_server = isset($_POST['ping_server']) ? $_POST['ping_server'] : null;
    // $exportpdf = isset($_POST['EXPORTPDF']) ? $_POST['EXPORTPDF'] : null;
    // $machine = isset($_POST['machine']) ? $_POST['machine'] : $ping_server;
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());


?>
<html>

<head>
	<title>Taux obsolescence par Application</title>
	<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
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
                        "autosize":true,
                        "paging": false,
                        language: 
                        {
                        url: "Datatables/French.json"
                        },
                        dom: 'Bfrtip',
                        // lengthMenu: 
                        // [
                        //     [ 10, 25, 50, -1 ],
                        //     [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                        // ],
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
                        // "initComplete": function () 
                        // {
                        //     var api = this.api();
                        //     api.$('td').click
                        //     ( 
                        //         function () 
                        //         {
                        //             api.search( this.innerHTML ).draw();
                        //         } 
                        //     );
                        // },
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
	<script src="js/pace.min.js"></script>
    <?php
        $querylistapp = "select `application` from cmdb.application where ARCHIVED = 0;";
        if ($stmt = $con->prepare($querylistapp)) 
        {
            $stmt->execute();
            $list_app = $stmt->fetchAll();
            
            foreach($list_app as $ligne)
            {
                if(!in_array($ligne['application'],$list_application))
				{
                    //on charge le tableau $list_serveur avec la colonne 'CONFIGURATIONNAME_WO_EXTENSION'
                    $list_application[]=$ligne['application'];
                }
            }
            // echo "<pre>";
            // print_r($list_application);
            // echo "</pre>";
        }
    ?>
	<div style='text-align:center;'>
		<h4 class="jumbotron">Taux obsolescence par Application</h4><br />
		<h5 class="jumbotron"> dernière mise à jour: <?php echo shell_exec('date \'+%H:%M:%S %d-%m-%Y\''); ?> </h5> <br />
    </div>
	<div>
		<center>
			<table style="width: 90%;" class="display">
				<thead>
            	<tr bgcolor='silver'>
               	<th >
                  	Application
                  </th>
                  <th>
                     Taux d'obsolescence Serveur (en %)
                  </th>
                  <th>
                     Taux d'obsolescence Database (en %)
                  </th>
                  <th>
                     Taux d'obsolescence Middleware (en %)
                  </th>
                  <th>
                     Product Owner
                  </th>
                  <th>
                     Product Manager
                  </th>
                  <th>
                    BRM in charge
                  </th>
                  <th>
                    Will be Replace By
                  </th>
               </tr>
            </thead>
            <?php 
					$content = ob_get_contents(); // Fin de l'enregistrement
					// Sauvegarder ma page dans un fichier html
					writeTextFile("/var/www/html/taux_obso_by_app.html",$content);//recuperation du nom pour nommer le document
					ob_clean();					
					ob_start();				
				?>

            <tbody>
				<?php
            	foreach($list_application as $element)
               {
                  $querytauxsrv="call cmdb.Taux_OS_Obso('$element');";
                  if ($stmt = $con->prepare($querytauxsrv)) 
                  {
                     $stmt->execute();
                     $result = $stmt->fetch();
                     if($result[0]=="") 
                     {
                     	$taux_server="-";
							}                     
                     else
							{                     
                     	$taux_server=$result[0];
                     }
                  }
                  
                  $querytauxdb="call cmdb.Taux_DB_Obso('$element');";
                  if ($stmt = $con->prepare($querytauxdb)) 
                  {
                      $stmt->execute();
                      $result = $stmt->fetch();
                      if($result[0]=="") 
                     {
                     	$taux_db="-";
							}                     
                     else
							{                     
                     	$taux_db=$result[0];
                     }
                  }
                  
                  $querytauxmdw="call cmdb.Taux_MDW_Obso('$element');";
                  if ($stmt = $con->prepare($querytauxmdw)) 
                  {
                  	$stmt->execute();
                  	$result = $stmt->fetch();
                  	if($result[0]=="") 
                     {
                     	$taux_mdw="-";
							}                     
                     else
							{                     
                     	$taux_mdw=$result[0];
                     }
                  }
                  //$queryproductowner="call cmdb.point_of_contact_by_app('$element');";
                  $queryproductowner="call HOPEX.point_of_contact_by_app('$element');";
                  if ($stmt = $con->prepare($queryproductowner)) 
                  {
                  	$stmt->execute();
                  	$result = $stmt->fetch();
                  	if($result[0]=="" && $result[1]=="") 
                    {
                        $IT_Functionnal="-";
                        $IT_Technical="-";
                        $BRM="-"; 
                        $ReplaceBy="Nothing for the moment";
					}                     
                    else
					{                     
                        // $IT_Functionnal=$result[1];
                        // $IT_Technical=$result[2];
                        // $BRM=$result[3]; 
                        $IT_Functionnal=$result[4];
                        $IT_Technical=$result[5];
                        $BRM=$result[6];
                        $ReplaceBy=$result[7]; 
                    }
                  }
                  echo "<tr>\n";
                  echo "<td>\n";
                  echo "<form id=\"".$element."\" method=\"POST\" action=\"gestion_obso_v2.php\"><input type=\"hidden\" name=\"application\" value=\"".$element."\"/><input type=\"hidden\" name=\"taux_server\" value=\"".$taux_server."\"/><input type=\"hidden\" name=\"taux_db\" value=\"".$taux_db."\"/><input type=\"hidden\" name=\"taux_mdw\" value=\"".$taux_mdw."\"/></form><a href='#' onclick='document.getElementById(\"".$element."\").submit()'><b>".$element."</b></a>\n";
                  echo "</td>\n";
                  
                  echo "<td>\n";
                  echo $taux_server;                 
                  echo "</td>\n";
						echo "<td>\n";
						echo $taux_db;
                  echo "</td>";
                  echo "<td>";
                  echo $taux_mdw;
                  echo "</td>";
                  echo "<td>";
                  echo $IT_Functionnal;
                  echo "</td>";
                  echo "<td>";
                  echo $IT_Technical;
                  echo "</td>";
                  echo "<td>";
                  echo $BRM;
                  echo "</td>";
                  echo "<td>";
                  echo $ReplaceBy;
                  echo "</td>";
                  echo "</tr>";
						$content = ob_get_contents(); // Fin de l'enregistrement
						// Sauvegarder ma page dans un fichier html
						writeTextFile("/var/www/html/taux_obso_by_app.html",$content);//recuperation du nom pour nommer le document
						ob_clean();						
						ob_start();
					}
				?>
            </tbody>
         </table>
		</center>
	</div>
</body>
</html>
<?php 
$content = ob_get_contents(); // Fin de l'enregistrement
// Sauvegarder ma page dans un fichier html
writeTextFile("/var/www/html/taux_obso_by_app.html",$content);//recuperation du nom pour nommer le document
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
