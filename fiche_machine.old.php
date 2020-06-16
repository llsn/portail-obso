<?php
	require "variable-db.php";
	require "functions.php";
	$STYLE="style='border:1px solid black;border-collapse: collapse;text-align:center;'";
	$ping_server= isset($_POST['ping_server']) ? $_POST['ping_server'] : NULL;
	$exportpdf= isset($_POST['EXPORTPDF']) ? $_POST['EXPORTPDF'] : NULL;
	$machine= isset($_POST['machine']) ? $_POST['machine'] : $ping_server;
	   // debug de variables
	// echo "<p class=\"debug\">";
	// error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

	// $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
	// $var_ignore=array("GLOBALS", "_ENV", "_SERVER","_GET","host","dbname","user","password","port","socket"); // Détermine les var à ne pas afficher
	// echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
	// $nom_fonction=__FUNCTION__;
	// if (isset($nom_fonction)&&$nom_fonction!="")
	// {
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

	// echo "</p>";

?>
<html>

<head>
    <title>Fiche Machine</title>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
</head>

<body>

    <div style='text-align:center;'>
        <h4 class="jumbotron">Fiche descriptive d'une machine</h4>
    </div>

    <form id="machine" class="form-horizontal" method="POST" enctype="multipart/form-data" action="fiche_machine.php">
        <acronym
            title="Saisissez ou sélectionnez le texte désiré et tapez sur 'ENTREE' ou cliquez sur le bouton 'Valider'">
            <input list="list_machine" name="machine" size="30" maxlength="30"
                value="<?php if($machine != "") echo $machine; ?>" class="input-lg"
                onchange='document.getElementById("application").submit()'></acronym>
        <datalist id="list_machine">
            <?php
		$con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$password)
		or die ('Could not connect to the database server' . pdo_connect_error());
		$querytable="select configurationname_wo_extension from system_inventory where status <> 'ARCHIVED';";
		if ($stmt = $con->prepare($querytable))
		{
			$stmt->execute();
			while ($resulttable = $stmt->fetch())
			{
				if ($machine == $resulttable[0])
				{
					echo '<option value="'.$resulttable[0].'" selected onclick=\'document.getElementById(application).submit()\'>'.$resulttable[0].'</option>';
				}
				else
				{
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
	if(isset($machine)!=NULL)
	{
		echo "<div>";
		echo "<form id=\"ping_server\" class=\"form-horizontal\" method=\"POST\" enctype=\"multipart/form-data\" action=\"fiche_machine.php\">";
		echo "<input type=\"hidden\" name=\"ping_server\" value=\"$machine\"/>";
		echo "<input type=\"submit\" value=\"LANCEMENT d'un PING vers la machine $machine\" class=\"input-lg\">";
		echo "</form>";
		echo "</div>";
		
		if(isset($ping_server)!=NULL)
		{
			echo "<div>";
			$output = shell_exec("ping -n 3 $ping_server");
			echo "<pre>$output</pre>";
			$machine=$ping_server;
			echo "</div>";
		}	
		
		
		

		$con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$password)
		or die ('Could not connect to the database server' . pdo_connect_error());
		
		$querymachine="select * from system_inventory where upper(configurationname_wo_extension) = upper('".str_replace(" ","",$machine)."');";
		if ($stmt = $con->prepare($querymachine)) 
		{
			$stmt->execute();
			$tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if(count($tuples)!=0)
			{

				if(count($tuples))
				{
					$data = array_values($tuples[0]);
					$hostname=$data[5];
					$status=$data[10];
					$environment=$data[12];
					$osname=$data[21];
					$osversion=$data[22];
					$kernelversion=$data[23];
					$osbuild=$data[24];
					$businessservices=$data[30];
					$businessapplication=$data[31];
					$functionalgroup=$data[32];
					$criticity=$data[46];
					$buildref=$data[47];
					$unbuildref=$data[48];
					$installdate=$data[49];
					$uninstalldate=$data[50];
				}
				$BGCOLOR_OS=status_obso_osversion($osversion,$host,$dbname,$user,$password);
		ob_start(); 		
	?>
    <center>
        <table class='table table-hover'>
            <tr>
                <th colspan='6' bgcolor='silver'>
                    <h4>FICHE MACHINE</h4>
                </th>
            </tr>
            <tr>
                <td colspan='6' bgcolor='<?php echo $BGCOLOR_OS?>'>
                    <h4>
                        <font color='blue'><?php echo $machine ?></font>
                    </h4>
                </td>
            </tr>
            <tr>
                <th colspan='1'>Status</th>
                <td colspan='2'><?php echo $status ?></td>
                <th colspan='1'>Criticité</th>
                <td colspan='2'><?php echo $criticity ?></td>
            </tr>
            <tr>
                <th colspan='1'>Operating Environment</th>
                <td colspan='5'><?php echo $environment ?></td>
            </tr>
            <tr>
                <th colspan='1'>Operating System</th>
                <td colspan='5'><?php echo $osname ?></td>
            </tr>
            <tr>
                <?php
					echo "<th colspan='1' >OS Version</th><td colspan='5' bgcolor='$BGCOLOR_OS' >".$osversion."</td>";
	?>
            </tr>
            <?php 

						echo "<tr>";
						echo "<th colspan='1' >Business Services</th>";
						if($businessservices!="")
						{
							echo "<td colspan='5' >".chunk_split ($businessservices,100,"<br/>\n")."</td>";
						}
						else
						{
							echo "<td colspan='5' >Pas d'info</td>";
						}
						echo "</tr>";
						echo "<tr>";
						echo "<th colspan='1' >Business Application</th>";
						if($businessapplication!="")
						{
							echo "<td colspan='5' >".chunk_split ($businessapplication,100,"<br/>\n")."</td>";
						}
						else
						{
							echo "<td colspan='5' >Pas d'info</td>";
						}
						echo "</tr>";
						echo "<tr>";
						echo "<th colspan='1' >Functional Group</th>";
						if($functionalgroup!="")
						{
							echo "<td colspan='5' >".chunk_split ($functionalgroup,100,"<br/>\n")."</td>";
						}
						else
						{
							echo "<td colspan='5' >Pas d'info</td>";
						}
						echo "</tr>";
						echo "<tr>";
						echo "<th colspan='1' >Date installation</th>";
						if($installdate!="")
						{
							echo "<td colspan='2' >".date('d/m/Y',strtotime($installdate))."</td>";
						}
						else
						{
							echo "<td colspan='2' >Pas d'info</td>";
						}	
						echo "<th colspan='1' >Date de désinstallation</th>";
						if($uninstalldate!="")
						{
							echo "<td colspan='2' >".date('d/m/Y',strtotime($uninstalldate))."</td>";
						}
						else
						{
							echo "<td colspan='2' >Pas d'info</td>";
						}
						echo "</tr>";
				?>

        </table>
    </center><br />

    <?php		
				$stmt->pdo = null;
			}
		}
		if(count($tuples)!=0)
		{
			$con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$password)
			or die ('Could not connect to the database server' . pdo_connect_error());
			
			$querydb="select `Database Instance Name`,`Instance`,`Middleware Edition`,`Middleware Version` from db_inventory where upper(hostname) like upper('".str_replace(" ","",$machine)."%');";
			if ($stmt = $con->prepare($querydb)) 
			{
				$stmt->execute();
				$tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
				echo "<table class='table table-bordered' >";
				
				if(count($tuples))
				{
					

					echo "<tr>";
					echo "<th colspan='4' bgcolor='silver' ><h4>BASE DE DONNEE</h4></th>";
					echo "</tr>";
					echo "<tr>";
					echo "<th  >Nom du serveur:Nom de l'instance</th><th >Nom de l'instance</th><th >Type de Base de données</th><th >Version de la base de données</th>";
					echo "</tr>";
					foreach($tuples as $ligne)
					{
						echo "<tr>";
						$BGCOLOR_DB=status_obso_dbversion($ligne['Middleware Version'],$host,$dbname,$user,$password);
						foreach($ligne as $entete=>$valeur)
						{
							
							switch($entete)
							{
								case "Database Instance Name":
									if(strpos($valeur,"MSSQLSERVER"))
									{
										$sqlserver=1;
									}
									else
									{
										$sqlserver=0;
									}
									$datasql=$valeur;
									echo "<td bgcolor='$BGCOLOR_DB' >".$valeur."</td>";
									break;
						
													
								case "Middleware Version":
									if($valeur!="")
									{
										echo "<td bgcolor='$BGCOLOR_DB' >".$valeur."</td>";
									}
									else
									{
										echo "<td >Pas d'info</td>";
									}
									break;
							
								case "Middleware Edition":
									if($valeur!="")
									{
										echo "<td bgcolor='$BGCOLOR_DB' >".$valeur."</td>";
									}
									else
									{
										echo "<td >Pas d'info</td>";
									}
									break;
							
								case "Instance":
									if($valeur!="")
									{
										if($sqlserver==0)
										{
											echo "<td bgcolor='$BGCOLOR_DB' ><b><form id=\"".$valeur."\" method=\"POST\" action=\"application_by_db.php\"><input type=\"hidden\" name=\"db\" value=\"".$datasql."\"/> </form><a href='#' onclick='document.getElementById(\"".$valeur."\").submit()'>".$valeur."</a></b></td>";
										}
										elseif ($sqlserver==1)
										{
											echo "<td bgcolor='$BGCOLOR_DB' ><b><form id=\"".$datasql."\" method=\"POST\" action=\"application_by_db.php\"><input type=\"hidden\" name=\"db\" value=\"".$datasql."\"/> </form><a href='#' onclick='document.getElementById(\"".$datasql."\").submit()'>".$valeur."</a></b></td>";
											
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
				echo "</table><br/>";
				$stmt->pdo = null;
			}
			
			$con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$password)
			or die ('Could not connect to the database server' . pdo_connect_error());
			
			$querymw="select `Subsystem Type`,`Middleware Edition`,`Middleware Version`,`MDW Status`,`MDW Name`,`MDW Classification`from middleware_inventory where upper(hostname) like upper('".str_replace(" ","",$machine)."%');";
			if ($stmt = $con->prepare($querymw)) 
			{
				$stmt->execute();
				$tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
				echo "<table class='table table-bordered'>";
				if(count($tuples))
				{
					echo "<tr>";
					echo "<th colspan='6' bgcolor='silver' ><h4>MIDDLEWARE</h4></th>";
					echo "</tr>";
					echo "<tr>";
					echo "<th >Subsystem Type</th><th >Middleware Edition</th><th >Middleware Version</th><th >MDW Status</th><th >MDW Name</th><th >MDW Classification</th>";
					echo "</tr>";
					
					foreach($tuples as $ligne)
					{
						echo "<tr>";
						$BGCOLOR_MDW=status_obso_middlewareversion($ligne['Middleware Version'],$host,$dbname,$user,$password);
						foreach($ligne as $entete=>$valeur)
						{
							switch($entete)
							{
								case "Middleware Version":
									if($valeur!="")
									{
										echo "<td  bgcolor='$BGCOLOR_MDW' >".$valeur."</td>";
									}
									else
									{
										echo "<td >Pas d'info</td>";
									}
									break;
								
								default:
									if($valeur!="")
									{
										echo "<td  bgcolor='$BGCOLOR_MDW' >".$valeur."</td>";
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
					echo "<th colspan='6' bgcolor='silver' ><h4>PAS de MIDDLEWARE lié(s) à ".$hostname."</h4></th>";
					echo "</tr>";
					
				}
				echo "</table><br/>";

				$stmt->pdo = null;
			}
			
			$con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$password)
			or die ('Could not connect to the database server' . pdo_connect_error());
			
			$querymw="select * from cmdb.suivie_decommission where upper(hostname) like upper('%".str_replace(" ","",$machine)."%');";
			if ($stmt = $con->prepare($querymw)) 
			{
				$stmt->execute();
				$tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$today = new DateTime('today');
				echo "<table class='table table-bordered'>";
				if(count($tuples))
				{
					echo "<tr>";
					echo "<th colspan='12' bgcolor='silver' ><h4>Liste des decommissions déclarées</h4></th>";
					echo "</tr>";
					echo "<tr>";
					echo "<th >ID</th><th >﻿Liste de<br/>machines</th><th >Base de<br/>données</th><th >Environnement</th><th >N° PGMP</th><th >Titre</th><th >Demandeur</th><th >Createur<br/>du ticket</th><th >Priorité</th><th >Date de<br/>Création<br/>du<br/>ticket<br/>PGMP</th><th >Date de<br/>décomm</th><th >Action<br/>faite</th>";
					echo "</tr>";
					foreach($tuples as $ligne)
					{
						echo "<tr>";

						
						foreach($ligne as $entete=>$valeur)
						{
							switch($entete)
							{
								
								case "hostname":

									$var_LIST_MACHINES_IMPACTEES=explode(",",$valeur);
									echo "<td >";
									if(isset($var_LIST_MACHINES_IMPACTEES))
									{
										foreach($var_LIST_MACHINES_IMPACTEES as $value)
										{
											echo "<form id=\"".$value."\" method=\"POST\" action=\"fiche_machine.php\"><input type=\"hidden\" name=\"machine\" value=\"".$value."\"/></form><a href='#' onclick='document.getElementById(\"".$value."\").submit()'><b>".$value."</b></a><br/>";
											?>
    <input type="hidden" name="LIST_MACHINES_IMPACTEES"
        value="<?php echo base64_encode(serialize($var_LIST_MACHINES_IMPACTEES)) ?>" />
    <?php
										}
									}
									echo "</td>";
									break;
								case "database":
									$var_LIST_DB_IMPACTEES=explode(",",$valeur);
									echo "<td >";
									if(isset($var_LIST_DB_IMPACTEES))
									{
										foreach($var_LIST_DB_IMPACTEES as $value)
										{
											echo "<form id=\"".$value."\" method=\"POST\" action=\"application_by_db.php\"><input type=\"hidden\" name=\"db\" value=\"".$value."\"/></form><a href='#' onclick='document.getElementById(\"".$value."\").submit()'><b>".$value."</b></a><br/>";
											?>
    <input type="hidden" name="LIST_DB_IMPACTEES"
        value="<?php echo base64_encode(serialize($var_LIST_DB_IMPACTEES)) ?>" />
    <?php
										}
									}
									echo "</td>";

									break;
								case "pgmp_ticket":
									echo "<td style='border:1px solid black;border-collapse: collapse; text-align: center; word-wrap: break-word; word-break: break-all;'><form id=\"".$valeur."\" method=\"POST\" action=\"set_decommission.php\"><input type=\"hidden\" name=\"ticketPGMP\" value=\"".$valeur."\"/><input type=\"hidden\" name=\"consult\" value=\"1\"/> </form><a href='#' onclick='document.getElementById(\"".$valeur."\").submit()' target=\"frame_decom\"><b>".$valeur."</b></a></td>";
									break;
								case "DateOfDecommission":
									$date_decom= new DateTime($valeur);
									if($today>$date_decom)
									{
										if($ligne['DecommissionDone']==0)
										{
											echo"<td bgcolor=\"#FF0000\" ><b>$valeur</b></td>";
										}
										else
										{
											echo "<td bgcolor=\"#00FF00\" ><b>$valeur</b></td>";
										}
									}
									else
									{
										echo"<td bgcolor=\"#FFFF00\">$valeur</td>";
									}
									break;
								case "DecommissionDone":
									if($valeur==1)
									{
										echo "<td >FAIT</td>";
									}
									else
									{
										echo "<td >PAS ENCORE FAIT</td>";
									}
									break;
								case "COMMENTAIRES":
									break;

								default:
									echo "<td >".wordwrap($valeur,16,"<br/>\n",false)."</td>";
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
				echo "</table><br/>";
				
			}
			
		}

		$toto = ob_get_contents();
		//echo $toto;
		//ob_end_clean();
		?>
    <center>
        <form id="exporttopdf" class="form-horizontal" method="POST" enctype="multipart/form-data"
            action="export_to_pdf.php">
            <input type="hidden" name="EXPORTPDF" value="true">

            <input type="hidden" name="DATA" value='<?php  echo base64_encode(serialize($toto)) ?>'>

            <input type="button" class="input-lg" value="Export en PDF"
                onclick="document.getElementById('exporttopdf').submit();">
        </form>
    </center>
    <?php
	}	
	else
	{
		echo "<p>Choisissez une donnée dans le champs ci-dessus</p>";
	}
	
	

	?>


</body>

</html>