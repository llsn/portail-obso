<?php
    require 'variable-db.php';
    require 'functions.php';

    $STYLE = "style='border:1px solid black;border-collapse: collapse;text-align:center;'";
	$application = isset($_POST['application']) ? $_POST['application'] : null;
	$env = isset($_POST['env']) ? $_POST['env'] : null;
    $component=isset($_POST['component']) ? $_POST['component'] : null;
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
		or die('Could not connect to the database server'.pdo_connect_error());
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $con->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
?>
<html>

<head>
	<title>Logical CI</title>
	<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <link rel="stylesheet" href="css/w3.css">
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
<body class="w3-main">
    <script src='js/pace.min.js'></script>    
    <header style="background-color:#0A2A29; color:white;" >
        <h1 class="w3-center">LOGICAL CI</h1>
    </header>
    		<!-- Mise en place du formulaire de recherche d'application -->
		<form id="valid_app" name="valid_app" class="form-group form-group-lg" method="POST" enctype="multipart/form-data"
			action="logical_CI.php">
			<!-- Création du tableau de recherche -->
			<center>
				<Table class='w3-table-all w3-hoverable' width='90%'>
					<tr>
						<td>
							<h4> Application concernée</h4>
						</td>
						<td colspan='2'>
							<input list='list_data_app' name='application' id='application' width='auto' class='input'
								onchange='document.getElementById("valid_app").submit()' value='<?php echo $application?>'
								onclick="if(this.value!='')this.value=''">
							<datalist id="list_data_app">
								<?php
									
									$querytable="select distinct substring_index(functionalgroups,'#',1) as APP from system_inventory where FUNCTIONALGROUPS <> '' order by APP";
									if ($stmt = $con->prepare($querytable))
									{
										$stmt->execute();
										while ($resulttable = $stmt->fetch())
										{
											if ($application == strtoupper($resulttable[0]))
											{
												echo '<option valeur="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
											}
											else
											{
												echo '<option valeur="'.$resulttable[0].'">'.$resulttable[0].'</option>';
											}	
										}
										$stmt->pdo = null;
									}
								?>
							</datalist>
						</td>
						</form>
						<?php
            if($application!="")
            {
                echo "<td>";
                echo "<form id='valid_env' name='valid_functionalgroup' class='form-group form-group-lg' method='POST' enctype='multipart/form-data' action='logical_CI.php'>";
				echo "<input type='hidden' name='application' value='".$application."'/>";
                echo "<input list='list_env' name='env' id='env' width='auto' class='input' onchange='document.getElementById(\"valid_env\").submit()' value='".$env."' onclick=\"if(this.value!='')this.value=''\">";
                echo "<datalist id='list_env'>";
                $query_env = "select distinct substring_index(substring_index(functionalgroups,'#',2),'#',-1) as ENV from system_inventory where functionalgroups like ('%".$application."%') order by ENV";
				if ($stmt = $con->prepare($query_env)) 
                {
					try
					{
						$stmt->execute();
						while ($resulttable = $stmt->fetch())
						{
							if ($env == strtoupper($resulttable[0]))
							{
								echo '<option valeur="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
							}
							else
							{
								echo '<option valeur="'.$resulttable[0].'">'.$resulttable[0].'</option>';
							}	
						}
					}	
					catch(PDOException $message)
					{
						echo '<br/>Connection failed: ' . $message->getMessage();
					}
                    $stmt->pdo = null;
                }
                echo "</datalist>";
				echo "</td>";
				echo "</form>";
            }
        ?>
						
						
					</tr>
					<tr>

						<?php
						ob_start();
						echo "<center><H2>$application</H2></center>";
						/* si la variable application est vide alors on ne fait rien
						sinon on récupere dans la table "point_of_contact_by_app" les valeurs de la ligne correspondant à l'application*/
						if($application!='')
						{
							$querycall="call `HOPEX`.`point_of_contact_by_app` ('$application')";
							if ($stmt = $con->prepare($querycall)) 
							{
								$stmt->execute();
								$tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
								if (count($tuples)) 
								{
									$columns_names = array_keys($tuples[0]);
									echo "<center><H3>Personnes en charges de l'application $application</H3></center>";
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
										echo '<tr>';
										foreach ($tuple as $entete => $col) 
										{
											switch (strtoupper($entete)) 
											{
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
									echo 'Pas de résultat';
								}
							}
						}
						$stmt->pdo = null;
						?>
					</tr>
					
					
					
				</table>
			</center>
		
  

        <div>
            <Table>
            <thead colspan="">
                <tr>
                <th> </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            </tfoot>
            </table>
        </div>
		<p class="debug">
		<?php
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
				if (!in_array($key, $var_ignore)&&strpos($key,"HTTPS")===false)
					{
					echo "<pre class=\"debug\">";
					echo ("$".$key." => ");
					print_r($valeur);
					echo "</pre>\n";
					}
			}
		?>
		</p> 
</body>
</html>