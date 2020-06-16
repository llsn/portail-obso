<?php
	require "variable-db.php";
	require "functions.php";
	if(isset($_POST['add_Type'])<>isset($add_Type))
	{
		$add_Type="";
		$add_osname="";
		$add_osversion="";
	}
	$action=isset($_POST['action']) ? $_POST['action'] : NULL;
	$id_support=isset($_POST['ID_SUPPORT']) ? $_POST['ID_SUPPORT'] : NULL;
	$typeproduit=isset($_POST['TYPE']) ? $_POST['TYPE'] : NULL;
	$OSNAME=isset($_POST['OSNAME']) ? $_POST['OSNAME'] : NULL;
	$add_Type=isset($_POST['add_Type']) ? $_POST['add_Type'] : NULL;
	$add_osname=isset($_POST['add_osname']) ? $_POST['add_osname'] : NULL;
	$add_osversion=isset($_POST['add_osversion']) ? $_POST['add_osversion'] : NULL;
	$add_eos=isset($_POST['add_eos']) ? $_POST['add_eos'] : NULL;
	$add_eoes=isset($_POST['add_eoes']) ? $_POST['add_eoes'] : NULL;
	$add_data=isset($_POST['add_data']) ? $_POST['add_data'] : NULL;
	
	$con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8',$user,$password)
	or die ('Could not connect to the database server' . pdo_connect_error());	
	
	if($action=="DELETE_LINE")
	{
			$querydelete="call `cmdb`.`delete_id_support`($id_support);";
			if ($stmt = $con->prepare($querydelete))
				{
					$result=$stmt->execute();
				}
	}
	else if($action=="MODIFY_LINE")
	{
		$querymodify="select * from cmdb.os_support_eos_eol where id_support=$id_support";
		if ($stmt = $con->prepare($querymodify)) 
		{
			$stmt->execute();
			$tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if(count($tuples))
			{
				foreach($tuples as $tuple) 
				{
					echo '<tr>';
					foreach($tuple as $entete=>$col)
					{
						switch(strtoupper ($entete))
						{
							case "TYPE":
								$add_Type=$col;
								break;
							case "OSNAME":
								$add_osname=$col;
								break;
							case "OSVERSION":
								$add_osversion=$col;
								break;	
							case "EOS":
								$add_eos=$col;
								break;
							case "EOES":
								$add_eoes=$col;
								break;
						}
					}
				}
			}
		}
	}
	
	if(isset($_POST['init'])!='')
	{
		$id_support='';
		$typeproduit='';
		$OSNAME='';
		$add_Type="";
		$add_osname="";
		$add_osversion="";
	}

	if(isset($_POST['add_data'])== "Valider")
	{
			if($add_Type!=NULL && $add_osname!=NULL && $add_osversion!=NULL && $add_eos!=NULL)
			{
				echo "TOUS LES CHAMPS SONT BIEN REMPLI :)";
				$queryset="CALL `cmdb`.`new_os_support_oes_eol`('$add_Type', '$add_osname','$add_osversion', '$add_eos', '$add_eoes');";
				
				echo "<pre>$queryset</pre>";
				if ($stmt = $con->prepare($queryset))
				{
					$result=$stmt->execute();
				}
			}
			else
			{
				echo "TOUS LES CHAMPS DOIVENT ÊTRE REMPLI POUR AJOUTER UNE LIGNE DANS LA TABLE!!!";
			}
	}
	
	

		// debug de variables
// 	echo "<p class=\"debug\">";
// 	error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

// 	$variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
// 	$var_ignore=array("GLOBALS", "_ENV", "_SERVER","_GET","host","dbname","user","password","port","socket"); // Détermine les var à ne pas afficher
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
// ?>

<html>
	<head>
		<title>Référentiel des supports produits</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
			<center><H1>Gestion des Références Supports des produits</H1></center>
		</div>
		<div>
			<form id="requete" name="requete" method="POST" action="referentiel_support.php">
				<fieldset>
					<legend>Requête:</legend>
					<center><table>
					<tr>
					<td>Type de produit: <input name="TYPE" id="TYPE" list="TypeProduct" type="text" value="<?php echo $typeproduit ?>" class="input-lg" onchange='document.getElementById("requete").submit()' onclick="if(this.value!='')this.value=''">
					<datalist id="TypeProduct" >
						<?php
							$querylisttype="select distinct `TYPE` from cmdb.os_support_eos_eol order by `TYPE`";
							if ($stmt = $con->prepare($querylisttype)) 
							{
								$stmt->execute();
								while ($resulttable = $stmt->fetch())
								{
									if ($typeproduit == $resulttable[0])
									{
										echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
									}
									else
									{
										echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
									}
								}
							}

						?>
					</datalist>
					</td>
					<td>Editeur du produit: <input name="OSNAME" id="OSNAME" size="50" list="TypeOSNAME" type="text" value="<?php echo $OSNAME ?>" class="input-lg" onchange='document.getElementById("requete").submit()' onclick="if(this.value!='')this.value=''">
					<datalist id="TypeOSNAME" >
						<?php
							if($typeproduit=='')
							{
								$querylistOSNAME="select distinct `OSNAME` from cmdb.os_support_eos_eol order by `OSNAME`";
							}
							else
							{
								$querylistOSNAME="select distinct `OSNAME` from cmdb.os_support_eos_eol where `TYPE` = '". $typeproduit ."' order by `OSNAME`";
							}
							if ($stmt = $con->prepare($querylistOSNAME)) 
							{
								$stmt->execute();
								while ($resulttable = $stmt->fetch())
								{
									if ($OSNAME == $resulttable[0])
									{
										echo '<option value="'.$resulttable[0].'" selected >'.$resulttable[0].'</option>';
									}
									else
									{
										echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
									}
								}
							}

						?>
					
					
					</datalist>
					</td>
					<td><input type="submit" name="init" value="Réinitialisation Requête"class="btn btn-lg"></td>
				</table></center>
				</fieldset>
				
			</form>
			<form id="add_line" name="add_line" method="POST" action="referentiel_support.php">
				<fieldset>
					<legend>Ajouter référence:</legend>
						<?php
							echo "<input type=\"hidden\" name=\"add_Type\" value=\"".$add_Type."\">";
							echo "<input type=\"hidden\" name=\"add_osname\" value=\"".$add_osname."\">";
							echo "<input type=\"hidden\" name=\"add_osversion\" value=\"".$add_osversion."\">";
							echo "<table class=\'table table-hover\' width=\"100%\">";
							echo "<thead>";
							echo "<tr><th><center>Type</center></th><th><center>OSNAME</center></th><th><center>OSVERSION</center></th><th><center>EOS</center></th><th><center>EOES</center></th><th><center>ACTION</center></th></tr>";
							echo "</thead>";
							echo "<tr>";
							echo "<td><center><input type=\"text\" name=\"add_Type\" id=\"add_Type\" list=\"ListaddType\" value=\"". $add_Type."\" width=\"100%\"  class=\"input-lg\" onchange='document.getElementById(\"add_line\").submit()'></center></td>";
							echo "<datalist id=\"ListaddType\" >";
							$querylistaddtype="select distinct type from cmdb.os_support_eos_eol order by type";
							if ($stmt = $con->prepare($querylistaddtype)) 
							{
								$stmt->execute();
								while ($resulttable = $stmt->fetch())
								{
									if ($add_Type == $resulttable[0])
									{
										echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
									}
									else
									{
										echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
									}
								}
							}

							if($add_Type==NULL || $add_Type=='')
							{
								echo "<td><center><input type=\"text\" name=\"add_osname\" id=\"add_osname\" list=\"ListOSNAME\" value=\"". $add_osname."\"width=\"100%\"  class=\"input-lg\" onchange='document.getElementById(\"add_line\").submit()' disabled></center></td>";
							}
							else
							{
								echo "<td><center><input type=\"text\" name=\"add_osname\" id=\"add_osname\" list=\"ListOSNAME\" value=\"". $add_osname."\"width=\"100%\"  class=\"input-lg\" onchange='document.getElementById(\"add_line\").submit()' ></center></td>";
								echo "<datalist id=\"ListOSNAME\" >";
								switch($add_Type)
								{
									case "OS":
										$querylistOSNAME="select distinct `OSNAME` from cmdb.system_inventory order by `OSNAME`";
										break;
									case "DATABASE":
										$querylistOSNAME="select distinct `Middleware Edition` from cmdb.db_inventory order by `Middleware Edition`";
										break;
									case "MIDDLEWARE":
										$querylistOSNAME="select distinct `Middleware Edition` from cmdb.middleware_inventory order by `Middleware Edition`";
										break;
								}
							
								if ($stmt = $con->prepare($querylistOSNAME)) 
								{
									$stmt->execute();
									while ($resulttable = $stmt->fetch())
									{
										if ($add_osname == $resulttable[0])
										{
											echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
										}
										else
										{
											echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
										}
									}
								}
								echo "</datalist>";
							}
							if ($add_osname==NULL || $add_osname=='')
							{
								echo "<td><center><input type=\"text\" name=\"add_osversion\" id=\"add_osversion\" list=\"ListOSVERSION\" value=\"". $add_osversion."\" width=\"100%\"  class=\"input-lg\" disabled></center></td>";
							}
							else
							{
								echo "<td><center><input type=\"text\" name=\"add_osversion\" id=\"add_osversion\" list=\"ListOSVERSION\" value=\"". $add_osversion."\" width=\"100%\"  class=\"input-lg\"></center></td>";							
								echo "<datalist id=\"ListOSVERSION\" >";
								switch($add_Type)
								{
									case "OS":
										$querylistOSVERSION="select distinct `OSVERSION` from cmdb.system_inventory where `OSNAME` = '".$add_osname."' order by `OSVERSION`";
										break;
									case "DATABASE":
										$querylistOSVERSION="select distinct `Middleware Version` from cmdb.db_inventory where `Middleware Edition` = '".$add_osname."' order by `Middleware Version`";
										break;
									case "MIDDLEWARE":
										$querylistOSVERSION="select distinct `Middleware Version` from cmdb.middleware_inventory where `Middleware Edition` = '".$add_osname."' order by `Middleware Version`";
										break;
								}
							
							
								if ($stmt = $con->prepare($querylistOSVERSION)) 
								{
									$stmt->execute();
									while ($resulttable = $stmt->fetch())
									{
										if ($add_osversion == $resulttable[0])
										{
											echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
										}
										else
										{
											echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
										}
									}
								}
								echo "</datalist>";
							}
							echo "<td><center><input type=\"date\" name=\"add_eos\" id=\"add_eos\" value=\"".$add_eos."\" class=\"input-lg\" width=\"100%\"></center></td>";
							echo "<td><center><input type=\"date\" name=\"add_eoes\" id=\"add_eoes\" value=\"".$add_eoes."\" class=\"input-lg\" width=\"100%\"></center></td>";
							echo "<td><center><input type=\"submit\" name=\"add_data\" id=\"add_data\" width=\"100%\" class=\"btn btn-lg\"></center></td>";
							echo "</tr>";
							echo "</table>";
						?>
				</fieldset>
			</form>
		</div>
		
		<div>
		<fieldset>
			<legend>Listing de produit identifié</legend>
			<?php
				if($typeproduit<>'' && $OSNAME=='')
				{
					$querylist="select * from cmdb.os_support_eos_eol where type='".$typeproduit."' order by `EOES`";
				}
				else if($typeproduit=='' && $OSNAME<>'')
				{
					$querylist="select * from cmdb.os_support_eos_eol where osname='".$OSNAME."' order by `EOES`";
				}
				else if($typeproduit<>'' && $OSNAME<>'')
				{
					$querylist="select * from cmdb.os_support_eos_eol where type='".$typeproduit."' and osname='".$OSNAME."' order by `EOES`";
				}
				else
				{
					$querylist="select * from cmdb.os_support_eos_eol order by `EOES`";
				}
				if ($stmt = $con->prepare($querylist)) 
				{
					$stmt->execute();
					$tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
					if(count($tuples))
					{
						$columns_names = array_keys($tuples[0]);
						echo "<table id='' class='display'><thead><tr>";
						foreach($columns_names as $col) 
						{
							if($col!="id_support")
							{
								echo '<th><center>'. $col .'</center></th>';
							}
						}
						//echo "<th colspan=\"2\"><center>ACTION</center></th>";
						echo "<th><center>Modifier</center></th>";
						echo "<th><center>Supprimer</center></th>";
						echo '</tr></thead><tbody>';
						foreach($tuples as $tuple) 
						{
							// echo "<pre>".print_r($tuple)."</pre>";
							switch($tuple['Type'])
							{
								case "OS":
									echo "<tr style='background-color: ".status_obso_osversion($tuple['OSVERSION'],$host,$dbname,$user,$password)."'>";
									break;
								case "DATABASE":
									echo "<tr style='background-color: ".status_obso_dbversion($tuple['OSVERSION'],$host,$dbname,$user,$password)."'>";
									break;
								case "MIDDLEWARE":
									echo "<tr style='background-color: ".status_obso_middlewareversion($tuple['OSVERSION'],$host,$dbname,$user,$password)."'>";
									break;
							}
								
							foreach($tuple as $entete=>$col)
							{
								switch(strtoupper ($entete))
								{
									case "ID_SUPPORT":
										$id_support=$col;
										// echo '<td><center>'. $col .'</center></td>';
										break;
									case "TYPE":
										$type_tab=$col;
										echo '<td><center>'. $col .'</center></td>';
										break;
									case "OSNAME":
										$osname_tab=$col;
										echo '<td><center>'. $col .'</center></td>';
										break;
									case "OSVERSION":
										$osversion_tab=$col;
										echo '<td><center>'. $col .'</center></td>';
										break;
									default:
										echo '<td><center>'. $col .'</center></td>';
										break;
								}
							}
							echo "<td><form id=\"MOD_".$id_support."\" name=\"MOD_".$id_support."\" method=\"POST\" action=\"referentiel_support.php\"><input type=\"hidden\" name=\"action\" value=\"MODIFY_LINE\"/><input type=\"hidden\" name=\"ID_SUPPORT\" value=\"".$id_support."\"/><center><input type=\"submit\" value=\"Modifier\" class=\"btn btn-info\"/></center></form></td>";
							echo "<td><form id=\"DEL_".$id_support."\" name=\"DEL_".$id_support."\" method=\"POST\" action=\"referentiel_support.php\"><input type=\"hidden\" name=\"action\" value=\"DELETE_LINE\"/><input type=\"hidden\" name=\"ID_SUPPORT\" value=\"".$id_support."\"/><center><input type=\"button\" value=\"Supprimer\" class=\"btn btn-danger\" onClick=\"ConfirmMessage('DEL_$id_support')\"/></center></form></td>";
							?>
							<script type="text/javascript">
							   function ConfirmMessage(Name_Form) 
							   {
									if (confirm("Êtes-vous de vouloir supprimer cette ligne?")) 
									{
									   // Clic sur OK
									   document.getElementById(Name_Form).submit()
									}
								}
							</script>
							<?php
							echo '</tr>';
						}
						echo '</tbody><tfoot></tfoot></table>';
					}
				}			

				?>

		</fieldset>
		</div>
	</body>
</html>
