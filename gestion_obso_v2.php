<?php
	//chargements de variable globale et des functions
	require "variable-db.php";
	require "functions.php";

	//Récupération des variables transmises par d'autres pages et intialisation des variables
    $application=isset($_POST['application']) ? $_POST['application'] : NULL;
    $chk_appli_obso=isset($_POST['chk_appli_obso']) ? $_POST['chk_appli_obso'] : NULL;
    //Création des tableaux de traitements
    $list_serveur=array();
    $list_instance_db=array();
    $taux_server=isset($_POST['taux_server']) ? $_POST['taux_server'] : NULL;
    $taux_db=isset($_POST['taux_db']) ? $_POST['taux_db'] : NULL;
	$taux_mdw=isset($_POST['taux_mdw']) ? $_POST['taux_mdw'] : NULL;
	 
    // Initialisation de la connexion à la base de données
	$con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$password)
		or die ('Could not connect to the database server' . pdo_connect_error());
	$pdo = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$password)
        or die ('Could not connect to the database server' . pdo_connect_error());
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
?>
<!-- Début de la page HTML -->
<html>

	<head>
		<!-- définition des caractères lié à l'affichage -->
		<meta charset="utf-8">
		<!-- Titre de la page -->
		<title>Gestion de l'obsolescence</title>
		<!-- définition des metadata de la page  -->
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="Datatables/datatables.css"/>
		<link rel="stylesheet" type="text/css" href="Datatables/stylesheet.css"/>

		<script type="text/javascript" language="javascript" src="Datatables/datatables.js"></script>
		<!-- Chargement des feuilles de style -->
		<link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="vendor/datatables/datatables/media/css/jquery.dataTables.css" />
		<link rel="stylesheet" type="text/css" href="vendor/datatables/datatables/media/css/jquery.dataTables.min.css" />
		<link rel="stylesheet" type="text/css" href="Datatables/AutoFill-2.3.4/css/autoFill.dataTables.min.css" />
		<link rel="stylesheet" type="text/css" href="Datatables/Responsive-2.2.3/css/responsive.dataTables.min.css" />
		<link rel="stylesheet" type="text/css" href="Datatables/Responsive-2.2.3/css/responsive.jqueryui.min.css" />
		<link rel="stylesheet" type="text/css" href="Datatables/FixedHeader-3.1.6/css/fixedHeader.dataTables.min.css" />
		<link rel="stylesheet" type="text/css" href="Datatables/FixedColumns-3.3.0/css/fixedColumns.dataTables.min.css" /> 

		<!-- Chargement des scripts javascripts pour la mise en forme des tableaux-->
		<script type="text/javascript" language="javascript" src="bootstrap/js/bootstrap.min.ls.js"></script>	
		<script type="text/javascript" language="javascript" src="vendor/components/jquery/jquery.min.js"></script>
		<script type="text/javascript" language="javascript" src="vendor/twbs/bootstrap/js/dist/tab.js"></script>
		<script type="text/javascript" language="javascript" src="vendor/twbs/bootstrap/js/dist/util.js"></script>
		<script type="text/javascript" language="javascript" src="js/pace.min.js"></script>

		<script type="text/javascript" language="javascript" src="Datatables/datatables.min.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/jQuery-3.3.1/jquery-3.3.1.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/AutoFill-2.3.4/js/dataTables.autoFill.min.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/FixedHeader-3.1.6/js/fixedHeader.dataTables.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/FixedColumns-3.3.0/js/fixedColumns.dataTables.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/Responsive-2.2.3/js/responsive.jqueryui.min.js"></script>
		<script type="text/javascript" language="javascript" src="Datatables/default/datatables.js"></script>


		<!-- Initialisation des tableaux de données de la page -->
		<script>
			
			$(document).ready
			(
				function () 
				{
					table=$('table.display').DataTable
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
									pageSize: 'A4'
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
	<!-- Corps de la page HTML -->

	<body>
		<script src="js/pace.min.js"></script>
		<!-- Titre de la page -->
		<?php echo "<pre>".$application."</pre>";?>
		<center>
			<H3 class='jumbotron'> Gestion de l'obsolescence</h3>
		</center>
		<!-- Mise en place du formulaire de recherche d'application -->
		<form id="valid_app" name="valid_app" class="form-group form-group-lg" method="POST" enctype="multipart/form-data"
			action="gestion_obso_v2.php">
			<!-- Création du tableau de recherche -->
			<center>
				<Table class='table-hover' width='90%'>
					<tr>
						<td>
							<h4> Application concernée</h4>
						</td>
						<td colspan='2'>
							<input list='list_data_app' name='application' id='application' width='auto' class='input-lg'
								onchange='document.getElementById("valid_app").submit()' value='<?php echo $application?>'
								onclick="if(this.value!='')this.value=''">
							<datalist id="list_data_app">
								<?php
									/* vérifie si la case à cocher chk_appli_obso est à "on"
									si chk_appli_obso est à "on" alors on charger les données de la vue "list_application_with_os_obsolete" 
									sinon on charge les données de la table "application" dans l'ordre alphabetique*/

									if($chk_appli_obso=="on")
									{
										$querytable="SELECT * FROM cmdb.list_application_with_os_obsolete;";
									}
									else
									{
										$querytable="select application from cmdb.application where archived = 0 order by application";
									}
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
							<?php
							/* si la variable de la case à cocher chk_appli_obso est à "on" alors
							on force la valeur de la case à cocher chk_appli_obso à "on"
							sinon on force la valeur de la case à cocher à "off" */
							if($chk_appli_obso=="on")
							{
								?>
							<input type='checkbox' name='chk_appli_obso' id='chk_appli_obso' class='input-lg'
								onclick='document.getElementById("valid_app").submit()' checked>
							<label>Filtrer uniquement les applications avec des OS obsoletes</label>
							<?php
							}
							else
							{
								?>
							<input type='checkbox' name='chk_appli_obso' id='chk_appli_obso' class='input-lg'
								onclick='document.getElementById("valid_app").submit()'>
							<label> Filtrer uniquement les applications avec des OS obsoletes</label>
							<?php
							}
							?>
						</td>
						
						
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
						
						?>
					</tr>
					
					
					
				</table>
			</center>
		</form>

		<?php
			/*Si application est différent de "" alors */
			
			if ($application!="")
			{
				
				/*on charge les données de la table "global_inventory" dans le tableau $list_serveur */
				$queryserver="select CONFIGURATIONNAME_WO_EXTENSION,STATUS,OPERATINGENVIRONMENT,OSNAME,OSVERSION,FUNCTIONALGROUPS,`DB Subsystem Type`,`DB Middleware Edition`,`DB Middleware Version`,`DB Instance Name`,`DB Instance`,`MDW Subsystem Type`,`MDW Middleware Edition`,`MDW Middleware Version`,`MDW Type`,`MDW STATUS`,`CRITICALITY` from cmdb.global_inventory where status <> 'ARCHIVED' and businessservices REGEXP '(^|\|)".$application."(\||$)' order by OPERATINGENVIRONMENT;";

				if ($stmt = $con->prepare($queryserver))
				{
					$stmt->execute();
					$tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
					if(count($tuples))
					{
		?>
		<!-- ouverture du conteneur d'onglet -->
		<div class="container">
			<!-- définition des onglets de navigation -->
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#server">Serveurs</a></li>
				<li><a data-toggle="tab" href="#db">Base de données</a></li>
				<li><a data-toggle="tab" href="#mdw">Middleware</a></li>
				<li><a data-toggle="tab" href="#LCI">Logical CI</a></li>
				<li><a data-toggle="tab" href="#debug">Debug</a></li>
			</ul>
			<!-- définition de la zone de contenu d'onglet -->
			<div class="tab-content">

				<!-- déclaration de l'onglet "Serveurs" -->
				<div id="server" class="tab-pane fade in active">
					<!-- Début du tableau des serveurs -->
					<table id="" class="display" style="width: 100%;">
						<thead>
							<tr>
								<th colspan="6">
									<center>
										<H3>LISTE DES SERVEURS de l'application <?php echo $application?> </H3><br />
										<?php
											if($application!="" && $taux_server==NULL) 
											{
												//echo "<tr ><td colspan='3'>";
												$query_taux_os_obso="call cmdb.Taux_OS_Obso('$application');";
												//echo "<pre>$query_taux_os_obso</pre>";
												if ($stmt = $con->prepare($query_taux_os_obso))
												{
													$stmt->execute();
													$resulttable = $stmt->fetch();
													echo "<h3> Taux d'Obsolescence des serveurs pour $application: <b><font color='red'>".$resulttable[0]."</font></b></h3>";
												}
												//echo "</td></tr>";
											}
											elseif($application!="" && $taux_server!=NULL) 
											{
													echo "<h3> Taux d'Obsolescence des serveurs pour $application: <b><font color='red'>".$taux_server."</font></b></h3>";											
											}
										?>
									</center>
								</th>
							</tr>
							<tr bgcolor='silver'>
								<th>HOSTNAME</th>
								<th>STATUS</th>
								<th>ENVIRONNEMENT</th>
								<th>OSNAME</th>
								<th>OSVERSION</th>
								<th>CRITICALITY</th>
							</tr>
						</thead>
						<tbody>
							<?php
									/* on parcours la variable $tuples et l'on créé le tableau $ligne contenu le détail de chaque colonne de la table "global_inventory"*/
									foreach($tuples as $ligne)
									{
										
										if(!in_array($ligne['CONFIGURATIONNAME_WO_EXTENSION'],$list_serveur))
										{
											//on charge le tableau $list_serveur avec la colonne 'CONFIGURATIONNAME_WO_EXTENSION'
											$list_serveur[]=$ligne['CONFIGURATIONNAME_WO_EXTENSION'];
											// on colore la ligne selon son niveau d'obosolescence avec la fonction "status_obso_osversion" contenu dans la librairie functions.php 
											echo "<tr style='background-color: ".status_obso_osversion($ligne['OSVERSION'],$host,$dbname,$user,$password).";'>";
											// on parcour chaque ligne et l'on sépare les entete de colonne avec les valeurs
											foreach($ligne as $entete=>$valeur)
											{
												// si l'entete est "..." alors on fait une action particulière
												switch($entete)
												{
													case "CONFIGURATIONNAME_WO_EXTENSION":
														echo "<td><form id=\"".$valeur."\" method=\"POST\" action=\"fiche_machine.php\"><input type=\"hidden\" name=\"machine\" value=\"".$valeur."\"/></form><a href='#' onclick='document.getElementById(\"".$valeur."\").submit()' ><b>".$valeur."</b></a></td>";
														break;
													case "STATUS":
														echo "<td>$valeur</td>";
														break;
													case "OPERATINGENVIRONMENT":
														echo "<td>$valeur</td>";
														break;
													case "OSNAME":
														echo "<td>$valeur</td>";
														break;
													case "OSVERSION":
														echo "<td>$valeur</td>";
														break;
													case "CRITICALITY":
															echo "<td>$valeur</td>";
															break;
												}
											}
											echo "</tr>";
										}
										
									}
									// on vide le tableau $list_serveur
									$list_serveur=array();
								}
							}
						?>
						</tbody>
					</table>
					<!-- fin du tableau des serveurs -->
				

					<!-- Fermeture de l'onglet "Serveurs" -->
				</div>


				<!----------------onglet-02-------------------------->
				<!-- Ouverture de l'onglet "Base de données" -->
				<div id="db" class="tab-pane fade">

					<!-- Début du tableau des base de données -->
					<table id="" class="display" style="width: 100%;">
						<thead>
							<tr>
								<th colspan='7'>
									<center>
										<H3>LISTE DES BASE DE DONNEES de l'application <?php echo $application?>
										<?php
											if($application!="" && $taux_db==NULL) 
											{
												//echo "<tr ><td colspan='3'>";
												$query_taux_db_obso="call cmdb.Taux_DB_Obso('$application');";
												//echo "<pre>$query_taux_os_obso</pre>";
												if ($stmt = $con->prepare($query_taux_db_obso))
												{
													$stmt->execute();
													$resulttable = $stmt->fetch();
													echo "<h3> Taux d'Obsolescence des bases de données pour $application: <b><font color='red'>".$resulttable[0]."</font></b></h3>";
												}
												//echo "</td></tr>";
											}
											elseif($application!="" && $taux_db!=NULL) 
											{
													echo "<h3> Taux d'Obsolescence des bases de données pour $application: <b><font color='red'>".$taux_db."</font></b></h3>";											
											}
										?>
									</center>
								</th>
							</tr>
							<tr bgcolor='silver'>
								<th>HOSTNAME</th>
								<th>ENVIRONNEMENT</th>
								<th>DB Subsystem Type</th>
								<th>DB Middleware Edition</th>
								<th>DB Middleware Version</th>
								<th>DB Instance Name</th>
								<th>DB Instance</th>
							</tr>
						</thead>
						<tbody>
							<?php
					/* on parcours le tableau $tuples et l'on créé le tableau $ligne contenu le détail de chaque colonne de la table "global_inventory"*/
					foreach($tuples as $ligne)
					{
						if($ligne['DB Instance']!="")
						{
							// on colore la ligne selon son niveau d'obosolescence avec la fonction "status_obso_dbversion" contenu dans la librairie functions.php 
							echo "<tr style='background-color: ".status_obso_dbversion($ligne['DB Middleware Version'],$host,$dbname,$user,$password).";'>";
							// on parcour chaque ligne et l'on sépare les entete de colonne avec les valeurs
							foreach($ligne as $entete=>$valeur)
							{
								switch($entete)
								{
									// si $entete="CONFIGURATIONNAME_WO_EXTENSION" alors on créer un formualire avec un lien vers la page fiche_machine.php en trasnmettant $valeur dans la variable "machine"
									case "CONFIGURATIONNAME_WO_EXTENSION":
										echo "<td><form id=\"".$valeur."\" method=\"POST\" action=\"fiche_machine.php\"><input type=\"hidden\" name=\"machine\" value=\"".$valeur."\"/></form><a href='#' onclick='document.getElementById(\"".$valeur."\").submit()'><b>".$valeur."</b></a></td>";
										break;
									case "OPERATINGENVIRONMENT":
										echo "<td>$valeur</td>";
										break;
									case "DB Subsystem Type":
										echo "<td>$valeur</td>";
										break;
									case "DB Instance Name":
										if(strpos($valeur,"MSSQLSERVER"))
										{
											$sqlserver=1;
											
										}
										else
										{
											$sqlserver=0;
											// $datasql="";
										}
										$datasql=$valeur;
										echo "<td>".$valeur."</td>";
										break;
							
														
									case "DB Middleware Version":
										if($valeur!="")
										{
											echo "<td>".$valeur."</td>";
										}
										else
										{
											echo "<td >Pas d'info</td>";
										}
										break;
								
									case "DB Middleware Edition":
										if($valeur!="")
										{
											echo "<td>".$valeur."</td>";
										}
										else
										{
											echo "<td >Pas d'info</td>";
										}
										break;
									// si $entete="DB Instance" alors on créer un formulaire avec un lien vers la page application_by_db.php en trasnmettant $valeur dans la variable "db"
									case "DB Instance":
										if($valeur!="")
										{
												echo "<td><b><form id='$datasql' method='POST' action='application_by_db.php'><input type='hidden' name='db' value='$datasql'/><input type='hidden' name='application' value='$application'/> </form> <a href='#' onclick=\"document.getElementById('".$datasql."').submit()\">$valeur</a></b></td>";
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

				?>
						</tbody>
					</table>
					<!-- fin du tableau des base de données -->
					
					<!-- Fermeture de l'onglet "Base de données" -->
				</div>
				<!----------------onglet-03-------------------------->
				<!-- Ouverture de l'onglet "Middleware" -->
				<div id="mdw" class="tab-pane fade">
					<!-- Début du tableau des Middleware -->
					<table id="" class="display table" style="width: 100%;">
						<thead>
							<tr>
								<th colspan='5'>
									<center>
										<H3>LISTE DES MIDDLEWARE de l'application <?php echo $application?>
										<?php
											if($application!="" && $taux_db==NULL) 
											{
												//echo "<tr ><td colspan='3'>";
												$query_taux_mdw_obso="call cmdb.Taux_MDW_Obso('$application');";
												//echo "<pre>$query_taux_os_obso</pre>";
												if ($stmt = $con->prepare($query_taux_mdw_obso))
												{
													$stmt->execute();
													$resulttable = $stmt->fetch();
													echo "<h3> Taux d'Obsolescence des middlewares pour $application: <b><font color='red'>".$resulttable[0]."</font></b></h3>";
												}
												//echo "</td></tr>";
											}
											elseif($application!="" && $taux_mdw!=NULL) 
											{
													echo "<h3> Taux d'Obsolescence des middlewares pour $application: <b><font color='red'>".$taux_mdw."</font></b></h3>";											
											}
										?>
									</center>
								</th>
							</tr>
							<tr bgcolor='silver'>
								<th>HOSTNAME</th>
								<th>ENVIRONNEMENT</th>
								<th>MDW Subsystem Type</th>
								<th>MDW Middleware Edition</th>
								<th>MDW Middleware Version</th>
							</tr>
						</thead>
						<tbody>
							<?php
							/* on parcours le tableau $tuples et l'on créé le tableau $ligne contenu le détail de chaque colonne de la table "global_inventory"*/
							foreach($tuples as $ligne)
							{
								if($ligne['MDW Subsystem Type']!="")
								{
									// on colore la ligne selon son niveau d'obosolescence avec la fonction "status_obso_middlewareversion" contenu dans la librairie functions.php
									echo "<tr style='background-color: ".status_obso_middlewareversion($ligne['MDW Middleware Version'],$host,$dbname,$user,$password).";'>";
									// on parcour chaque ligne et l'on sépare les entete de colonne avec les valeurs
									foreach($ligne as $entete=>$valeur)
									{
										switch($entete)
										{
											// si $entete="CONFIGURATIONNAME_WO_EXTENSION" alors on créer un formualire avec un lien vers la page fiche_machine.php en trasnmettant $valeur dans la variable "machine"
											case "CONFIGURATIONNAME_WO_EXTENSION":
												echo "<td><form id=\"".$valeur."\" method=\"POST\" action=\"fiche_machine.php\"><input type=\"hidden\" name=\"machine\" value=\"".$valeur."\"/></form><a href='#' onclick='document.getElementById(\"".$valeur."\").submit()'><b>".$valeur."</b></a></td>";
												break;
											case "OPERATINGENVIRONMENT":
												echo "<td>$valeur</td>";
												break;
											case "MDW Subsystem Type":
												echo "<td>$valeur</td>";
												break;
																
											case "MDW Middleware Version":
												if($valeur!="")
												{
													echo "<td>".$valeur."</td>";
												}
												else
												{
													echo "<td >Pas d'info</td>";
												}
												break;
										
											case "MDW Middleware Edition":
												if($valeur!="")
												{
													echo "<td>".$valeur."</td>";
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
						// on cloture la connexion à la base de données MYSQL
						// $stmt->pdo = null;
						?>
						</tbody>
					</table>
					<!-- fin du tableau des Middleware -->
					
					<!-- Fermeture de l'onglet "Middleware" -->
				</div>
				<!-- Ouverture de l'onglet "LOGICAL CI" -->
				<div id="LCI" class="tab-pane fade">
					
					<!-- Début du tableau des Logical CI -->
					<table id="" class="display table" style="width: 100%;">
						<thead>
							<tr>
								<th colspan='3'>
									<center>
										<H3>LISTE DES RELATION CI de l'application <?php echo $application?></H3>
									</center>
									<?php 
										$LCI_application=str_replace(" ","_",$application);
										$query_LCI="call `cmdb`.`Logical_IC`('$LCI_application');";
										if($stmt = $pdo->prepare($query_LCI))
										{
											try{
												$stmt->execute();
												$tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);	
											}
											catch (PDOException $message)
											{
												echo 'Connection failed: ' . $message->getMessage();
											}

											
											// echo "<pre>";
											// echo count($LCItuples);
											// print_r($LCItuples);
											// echo "</pre>";
											// error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.
				
											// $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
											// $var_ignore=array("GLOBALS", "_ENV", "_SERVER","_GET","host","dbname","user","password","port","socket"); // Détermine les var à ne pas afficher
											// echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
											// $nom_fonction=__FUNCTION__;
											// if (isset($nom_fonction)&&$nom_fonction!="")
											// {
											// 	echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
											// }
											// foreach ($variables as $key=>$valeur)
											// {
											// 	if (!in_array($key, $var_ignore)&&strpos($key,"HTTPS")===false)
											// 		{
											// 		echo "<pre class=\"debug\">";
											// 		echo ("$".$key." => ");
											// 		print_r($valeur);
											// 		echo "</pre>\n";
											// 		}
											// }
											if(count($tuples))						
											{
									?>
									
								</th>
							</tr>
							<tr bgcolor='silver'>
								
								<th>HOSTNAME</th>
								<th>ENVIRONNEMENT</th>
								<th>FUNCTIONAL GROUPS</th>
							</tr>
						</thead>
						<tbody>
							<?php
									/* on parcours le tableau $tuples et l'on créé le tableau $ligne contenu le détail de chaque colonne de la table "global_inventory"*/
									foreach($tuples as $ligne)
									{
										// echo $LCIligne['FUNCTIONALGROUPS'];
										// if($LCIligne['FUNCTIONALGROUPS']!="")
										// {
											// on colore la ligne selon son niveau d'obosolescence avec la fonction "status_obso_middlewareversion" contenu dans la librairie functions.php
											echo "<tr style='background-color: ".status_obso_os($ligne['OSVERSION'],$host,$dbname,$user,$password).";'>";
											// on parcour chaque ligne et l'on sépare les entete de colonne avec les valeurs
											foreach($ligne as $entete => $valeur)
											{
												switch($entete)
												{
													// si $entete="CONFIGURATIONNAME_WO_EXTENSION" alors on créer un formualire avec un lien vers la page fiche_machine.php en trasnmettant $valeur dans la variable "machine"
													
													case "CONFIGURATIONNAME_WO_EXTENSION":
														echo "<td><form id=\"".$valeur."\" method=\"POST\" action=\"fiche_machine.php\"><input type=\"hidden\" name=\"machine\" value=\"".$valeur."\"/></form><a href='#' onclick='document.getElementById(\"".$valeur."\").submit()'><b>".$valeur."</b></a></td>";
														break;
													case "OPERATINGENVIRONMENT":
														echo "<td>$valeur</td>";
														break;
													case "FUNCTIONALGROUPS":
														echo "<td>$valeur</td>";
														break; 						                
												}
											}
											echo "</tr>";
										// }
										
									}
								}
								// $LCIstmt->pdo = null;
								// on cloture la connexion à la base de données MYSQL
								$stmt->pdo = null;
							}
						?>
						</tbody>
					</table>
				</div>
				<div id="debug" class="tab-pane fade">
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
				</div>
			</div>
			<?php
				$PDF_SHEET = ob_get_contents();	
			?>
			
			<center>
				<form id='exporttopdf' class='form-horizontal' method='POST' enctype='multipart/form-data' action='export_to_pdf.php'>
				<input type='hidden' name='EXPORTPDF' value='true'>
				<input type='hidden' name='FORMAT_PAPER' value='A3'>
				<?php
					echo "<input type='hidden' name='FILE_NAME' value='".$application."-detail-technique-CMDB.pdf'>";
					echo "<input type='hidden' name='DATA' value='".base64_encode(serialize($PDF_SHEET))."'>";
					echo "<input type='submit' class='btn' value='Export en PDF de toutes les informations de $application'>";
				?>
				</form>
			</center>
			<?php
				}
			?>
		</div>
		
	</body>
</html>