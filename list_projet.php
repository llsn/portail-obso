<?php
    require 'variable-db.php';
    $chk_status = isset($_POST['chk_projet_done']) ? $_POST['chk_projet_done'] : null;
    $chk_archive = isset($_POST['chk_projet_archived']) ? $_POST['chk_projet_archived'] : null;
    $current_month = date('Y-m', time());
    $date_month = isset($_POST['mois']) ? $_POST['mois'] : null;
    $archive_project = isset($_POST['ARCHIVED_PROJET']) ? $_POST['ARCHIVED_PROJET'] : null;
    $projets = isset($_POST['list_projets']) ? $_POST['list_projets'] : null;
    $pm_tech = isset($_POST['pm_tech']) ? $_POST['pm_tech'] : null;

    $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
    or die('Could not connect to the database server'.pdo_connect_error());

?>
<html>

<head>
	<title>Revue des projets</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- <link rel="stylesheet" type="text/css" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" /> -->
	<link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
	<style type="text/css">
		/*
		Demo CSS code
	*/

		[type="checkbox"]:not(:checked),
		[type="checkbox"]:checked {
			position: absolute;
			left: -9999px;
		}

		[type="checkbox"]:not(:checked)+label,
		[type="checkbox"]:checked+label {
			position: relative;
			padding-left: 75px;
			cursor: pointer;
		}

		[type="checkbox"]:not(:checked)+label:before,
		[type="checkbox"]:checked+label:before,
		[type="checkbox"]:not(:checked)+label:after,
		[type="checkbox"]:checked+label:after {
			content: '';
			position: absolute;
		}

		[type="checkbox"]:not(:checked)+label:before,
		[type="checkbox"]:checked+label:before {
			left: 2;
			top: -3px;
			width: 65px;
			height: 30px;
			background: #DDDDDD;
			border-radius: 15px;
			-webkit-transition: background-color .2s;
			-moz-transition: background-color .2s;
			-ms-transition: background-color .2s;
			transition: background-color .2s;
		}

		[type="checkbox"]:not(:checked)+label:after,
		[type="checkbox"]:checked+label:after {
			width: 20px;
			height: 20px;
			-webkit-transition: all .2s;
			-moz-transition: all .2s;
			-ms-transition: all .2s;
			transition: all .2s;
			border-radius: 50%;
			background: #7F8C9A;
			top: 2px;
			left: 5px;
		}

		/* on checked */
		[type="checkbox"]:checked+label:before {
			background: #34495E;
		}

		[type="checkbox"]:checked+label:after {
			background: #39D2B4;
			top: 2px;
			left: 40px;
		}

		[type="checkbox"]:checked+label .ui,
		[type="checkbox"]:not(:checked)+label .ui:before,
		[type="checkbox"]:checked+label .ui:after {
			position: absolute;
			left: -4px;
			width: 65px;
			border-radius: 15px;
			font-size: 14px;
			font-weight: bold;
			line-height: 22px;
			-webkit-transition: all .2s;
			-moz-transition: all .2s;
			-ms-transition: all .2s;
			transition: all .2s;
		}

		[type="checkbox"]:not(:checked)+label .ui:before {
			content: "non";
			left: 13px
		}

		[type="checkbox"]:checked+label .ui:after {
			content: "yes";
			color: #39D2B4;
		}

		[type="checkbox"]:focus+label:before {
			border: 1px dashed #777;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			-ms-box-sizing: border-box;
			box-sizing: border-box;
			margin-top: -1px;
		}
	</style>
</head>

<body>
	<div>
		<h3 class="jumbotron">
			<center>Revue des projets</center>
		</h3>
	</div>
	<div>
		<form id="filtre_projet_done" name="filtre_projet_done" class="form-horizontal" method="POST"
			enctype="multipart/form-data" action="list_projet.php">
			<table class="table table-hover">
				<thead>
					<th colspan="6" bgcolor='silver'>
						<h4>
							<center>Lancer des requêtes pour filtrer le tableau</center>
						</h4>
					</th>
				</thead>
				<tbody>
					<tr>
						<th>
							<acronym
								title="Saisissez ou sélectionnez le texte désiré et tapez sur 'ENTREE' ou cliquez sur le bouton 'Valider'">
								<h4>Liste des projets (non archivé)</h4>
						</th>
						<td colspan="2">
							<input list="list_projets" name="list_projets" size="60" maxlength="60" value=""
								class="input-lg"
								onchange='document.getElementById("filtre_projet_done").submit()'></acronym>
							<datalist id="list_projets">
								<?php
                        $querylistprojet = "select PROJECT_NAME from cmdb.suivie_projets where ARCHIVED_PROJET='0' order by PROJECT_NAME";
                        if ($stmt = $con->prepare($querylistprojet)) {
                            $stmt->execute();
                            while ($resulttable = $stmt->fetch()) {
                                if ($projets == $resulttable[0]) {
                                    echo '<option value="'.$resulttable[0].'" selected onclick=\'document.getElementById(filtre_projet_done).submit()\'>'.$resulttable[0].'</option>';
                                } else {
                                    echo '<option value="'.$resulttable[0].'" onclick=\'document.getElementById(filtre_projet_done).submit()\'>'.$resulttable[0].'</option>';
                                }
                            }
                        }
                        ?>
							</datalist>
						</td>

						<th>
							<acronym
								title="Saisissez ou sélectionnez le texte désiré et tapez sur 'ENTREE' ou cliquez sur le bouton 'Valider'">
								<h4>Liste des PM TECHNIQUE</h4>
						</th>
						<td colspan="2">
							<input list="pm_tech" name="pm_tech" size="60" maxlength="60" value="" class="input-lg"
								onchange='document.getElementById("filtre_projet_done").submit()'></acronym>
							<datalist id="pm_tech">
								<?php
                        $querylistpmtech = 'select distinct PM_TECHNIQUE from cmdb.suivie_projets order by PM_TECHNIQUE';
                        if ($stmt = $con->prepare($querylistpmtech)) {
                            $stmt->execute();
                            while ($resulttable = $stmt->fetch()) {
                                if ($projets == $resulttable[0]) {
                                    echo '<option value="'.$resulttable[0].'" selected onclick=\'document.getElementById(filtre_projet_done).submit()\'>'.$resulttable[0].'</option>';
                                } else {
                                    echo '<option value="'.$resulttable[0].'" onclick=\'document.getElementById(filtre_projet_done).submit()\'>'.$resulttable[0].'</option>';
                                }
                            }
                        }
                        ?>
							</datalist>
						</td>
					</tr>
					<tr>

						<td colspan="3">
							<center>
								<?php
                    if ($chk_status == 'on') {
                        echo "<input type=\"checkbox\" id=\"chk_projet_done\" name=\"chk_projet_done\" target=\"frame_projet\" onchange='document.getElementById(\"filtre_projet_done\").submit()' checked>";
                        echo '<label for="chk_projet_done" aria-describedby="label"><span class="ui"></span>Cacher les projets terminés</label><span class="ui"></span>';
                    } else {
                        echo "<input type=\"checkbox\" id=\"chk_projet_done\" name=\"chk_projet_done\" target=\"frame_projet\" onchange='document.getElementById(\"filtre_projet_done\").submit()' >";
                        echo '<label for="chk_projet_done" aria-describedby="label"><span class="ui"></span>Cacher les projets terminés</label><span class="ui"></span>';
                    }
                    if ($chk_archive == 'on') {
                        echo "</br></br><input type=\"checkbox\" class=\"input-lg\" id=\"chk_projet_archived\" name=\"chk_projet_archived\" target=\"frame_projet\" onchange='document.getElementById(\"filtre_projet_done\").submit()' checked>";
                        echo '<label for="chk_projet_archived" aria-describedby="label"><span class="ui"></span>Afficher uniquement les projets terminés</label><span class="ui"></span>';
                    } else {
                        echo "</br></br><input type=\"checkbox\" class=\"input-lg\" id=\"chk_projet_archived\" name=\"chk_projet_archived\" target=\"frame_projet\" onchange='document.getElementById(\"filtre_projet_done\").submit()' >";
                        echo '<label for="chk_projet_archived" aria-describedby="label"><span class="ui"></span>Afficher uniquement les projets terminés</label><span class="ui"></span>';
                    }

                    ?>
							</center>
						</td>




						<th>
							<h4>
								<center>Filtrer le mois de cloture des projets</center>
							</h4>
						</th>
						<td>
							<input type="month" name="mois" id="mois" class="input-sm"
								value="<?php echo $date_month; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<center><input type="submit" class="btn btn-sm"></center>
						</td>
		</form>
		</tr>
		</tbody>
		</table>
	</div>
	<?php
        if ($archive_project != null) {
            $queryarchived = "update cmdb.suivie_projets set ARCHIVED_PROJET='1' where RFS_NUMBER_CMA='".$archive_project."';";
            // echo "<pre>$queryarchived</pre>";
            if ($stmt = $con->prepare($queryarchived)) {
                $result = $stmt->execute();
                // echo "<pre>".print_r($stmt)."</pre>";
            }
            //$querydb="SELECT suivie_projets_id, RFS_NUMBER_CMA, CODE_BUDGET, PROJECT_NAME, PM_TECHNIQUE, PM_FONCTIONNEL, REF_ORCHESTRA, DATE_CREATION, GLOBAL_STATUS, EVOLUTION_STATUS, SPS_MEETING, COARCH_VALIDATION, PDR_VALIDATION, GO_LIVE, INITIALE_CLOSURE_DATE, REVISED_CLOSURE_DATE, PROJECT_FINISH FROM cmdb.suivie_projets where ARCHIVED_PROJET='0'";
            if ($chk_archive == 'on') {
                $querydb = "SELECT `RFS_NUMBER_CMA`, `CODE_BUDGET`, `PROJECT_NAME`, `PM_TECHNIQUE`, `PM_FONCTIONNEL`, `REF_ORCHESTRA`, `GLOBAL_STATUS`, `EVOLUTION_STATUS`, `PROJECT_FINISH` FROM cmdb.suivie_projets where PROJECT_FINISH='1' and ARCHIVED_PROJET='0'";
            } else {
                $querydb = "SELECT `RFS_NUMBER_CMA`, `CODE_BUDGET`, `PROJECT_NAME`, `PM_TECHNIQUE`, `PM_FONCTIONNEL`, `REF_ORCHESTRA`, `GLOBAL_STATUS`, `EVOLUTION_STATUS`, `PROJECT_FINISH` FROM cmdb.suivie_projets where PROJECT_FINISH='0' and ARCHIVED_PROJET='0'";
            }
        } else {
            if ($chk_archive == 'on') {
                $querydb = "SELECT `RFS_NUMBER_CMA`, `CODE_BUDGET`, `PROJECT_NAME`, `PM_TECHNIQUE`, `PM_FONCTIONNEL`, `REF_ORCHESTRA`, `GLOBAL_STATUS`, `EVOLUTION_STATUS`, `PROJECT_FINISH` FROM cmdb.suivie_projets where PROJECT_FINISH='1' and ARCHIVED_PROJET='0'";
            } else {
                $querydb = "SELECT `RFS_NUMBER_CMA`, `CODE_BUDGET`, `PROJECT_NAME`, `PM_TECHNIQUE`, `PM_FONCTIONNEL`, `REF_ORCHESTRA`, `GLOBAL_STATUS`, `EVOLUTION_STATUS`, `PROJECT_FINISH` FROM cmdb.suivie_projets where PROJECT_FINISH='0' and ARCHIVED_PROJET='0'";
            }
        }
        if ($projets != null) {
            $querydb = $querydb." and PROJECT_NAME like '%".$projets."%'";
        }
        if ($pm_tech != null) {
            $querydb = $querydb." and PM_TECHNIQUE like '%".$pm_tech."%'";
        }
        if ($chk_status == 'on') {
            $querydb = $querydb." and GLOBAL_STATUS<>'3'";
            if ($date_month != '') {
                $querydb = $querydb." and (INITIALE_CLOSURE_DATE like '$date_month%' or REVISED_CLOSURE_DATE like '$date_month%')";
            }
        } else {
            if ($date_month != '') {
                $querydb = $querydb." and (INITIALE_CLOSURE_DATE like '$date_month%' or REVISED_CLOSURE_DATE like '$date_month%')";
            }
        }
        // echo "<pre>";
        // echo $querydb;
        // echo "</pre>";
        if ($stmt = $con->prepare($querydb)) {
            $result = $stmt->execute();
            $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $today = new DateTime('today');
            // echo "<pre>".print_r($result)."</pre>";

            if (count($tuples)) {
                // echo "<pre>";
                // print_r($tuples);
                // echo "</pre>";
                ob_start();
                echo "<table class='table table-hover'>";
                echo '<thead>';
                echo '<tr>';
                echo "<th colspan='17' bgcolor='silver'><h4><center>Liste des projets touchant à l'obsolescence</center></h4></th>";
                echo '</tr>';
                echo '<tr>';
                echo '<th>ID</th><th>Num RFS</th><th>CODE BUDGET</th><th>PROJECT NAME</th><th>PM TECHNIQUE</th><th>PM FONCTIONNEL</th><th>REF ORCHESTRA</th><th>GLOBAL STATUS</th><th>EVOLUTION STATUS</th><th>PROJECT FINISH</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($tuples as $ligne) {
                    echo '<tr>';
                    foreach ($ligne as $entete => $valeur) {
                        switch ($entete) {
                            case 'PROJECT_NAME':
                                echo '<td>'.chunk_split($valeur, 30, "</br>\n").'</td>';
                                break;
                            case 'GLOBAL_STATUS':
                                switch ($valeur) {
                                        case '2':
                                            echo '<td bgcolor="#00FF00"><center><b>En Cours</b></center></td>';
                                            break;
                                        case '1':
                                            echo '<td bgcolor="#FFFF00"><center><b>En pause</b></center></td>';
                                            break;
                                        case '0':
                                            echo '<td bgcolor="#FF0000"><font color="#FFFFFF"><center><b>Annulé</b></center></font></td>';
                                            break;
                                        case '3':
                                            echo '<td bgcolor="#0000FF"><font color="#FFFFFF"><center><b>Terminé</b></center></font></td>';
                                            break;
                                        case '4':
                                            echo '<td><center><b>Aucune info</b></center></td>';
                                            break;
                                }
                                break;
                            case 'EVOLUTION_STATUS':
                                switch ($valeur) {
                                        case '2':
                                            echo '<td bgcolor="#00FF00"><center><b>En Avance</b></center></td>';
                                            break;
                                        case '1':
                                            echo '<td bgcolor="#FFFF00"><center><b>Dans les temps</b></center></td>';
                                            break;
                                        case '0':
                                            echo '<td bgcolor="#FF0000"><font color="#FFFFFF"><center><b>En Retard</b></center></font></td>';
                                            break;

                                        case '4':
                                            echo '<td><center><b>Aucune info</b></center></td>';
                                            break;
                                }
                                break;
                            case 'RFS_NUMBER_CMA':
                                echo '<td><form id="'.$valeur.'" method="POST" action="set_projet.php"><input type="hidden" name="RFS_NUMBER_CMA" value="'.$valeur."\"/><input type=\"hidden\" name=\"consult\" value=\"1\"/> </form><a href='#' onclick='document.getElementById(\"".$valeur."\").submit()' target=\"frame_projet\"><b>".$valeur.'</b></a></td>';
                                break;
                            case 'DateOfDecommission':
                                $date_decom = new DateTime($valeur);
                                if ($today > $date_decom) {
                                    if ($ligne['DecommissionDone'] == 0) {
                                        echo"<td bgcolor=\"#FF0000\"><b>$valeur</b></td>";
                                    } else {
                                        echo "<td bgcolor=\"#00FF00\"><b>$valeur</b></td>";
                                    }
                                } else {
                                    echo"<td bgcolor=\"#FFFF00\">$valeur</td>";
                                }
                                break;
                            case 'PROJECT_FINISH':
                                if ($valeur == 1) {
                                    echo '<td>OUI</td>';
                                } else {
                                    echo '<td>NON</td>';
                                }
                                break;
                            default:
                                echo "<td>$valeur</td>";
                                break;
                        }
                    }
                    if ($ligne['PROJECT_FINISH'] == 1) {
                        echo'<td><form id="'.$ligne['RFS_NUMBER_CMA'].'" method="POST" action="set_projet.php"><input type="hidden" name="RFS_NUMBER_CMA" value="'.$ligne['RFS_NUMBER_CMA'].'"/><input type="hidden" name="consult" value="1"/><input type="submit" class="btn btn-sm" value="Détails"></form></td><td><form id="DEL_'.$ligne['RFS_NUMBER_CMA'].'" method="POST" action="list_projet.php"><input type="hidden" name="ARCHIVED_PROJET" value="'.$ligne['RFS_NUMBER_CMA'].'"/><input type="submit" class="btn btn-sm" value="Archiver"></form></td>';
                    } else {
                        echo '<td><form id="'.$ligne['RFS_NUMBER_CMA'].'" method="POST" action="set_projet.php"><input type="hidden" name="RFS_NUMBER_CMA" value="'.$ligne['RFS_NUMBER_CMA'].'"/><input type="hidden" name="consult" value="1"/><input type="submit" class="btn btn-sm" value="Détails"></form></td>';
                    }

                    echo '</tr>';
                }
                $PDF_EXPORT = ob_get_contents(); ?>
	<tr>
		<td colspan='11'>
			<form id="exporttopdf" class="form-horizontal" method="POST" enctype="multipart/form-data"
				action="export_to_pdf.php">
				<input type="hidden" name="EXPORTPDF" value="true">

				<input type="hidden" name="DATA" value='<?php  echo base64_encode(serialize($PDF_EXPORT)); ?>'>

				<center><input type="button" class="btn btn-sm" value="Export en PDF"
						onclick="document.getElementById('exporttopdf').submit();"></center>
			</form>
		</td>
	</tr>
	</tbody>
	</table>
	<?php
            }

            $stmt->pdo = null;
            /* 				   // debug de variables
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

                                echo "</p>"; */
        }

?>
</body>

</html>