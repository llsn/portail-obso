<?php
    require 'variable-db.php';
    ini_set('memory_limit', '1024M');
    $chk_status = isset($_POST['chk_decom_done']) ? $_POST['chk_decom_done'] : 'off';
    $chk_cancel = isset($_POST['chk_cancel']) ? $_POST['chk_cancel'] : 'off';
    $chk_archived_decom = isset($_POST['chk_archived_decom']) ? $_POST['chk_archived_decom'] : 'off';
    $current_month = date('Y-m', time());
    $date_month = isset($_POST['mois']) ? $_POST['mois'] : null;
    $demandeur = isset($_POST['demandeur']) ? $_POST['demandeur'] : null;
    $createur = isset($_POST['createur']) ? $_POST['createur'] : null;
    $reset = isset($_POST['RESET']) ? $_POST['RESET'] : null;
    $ARCHIVED_DECOM = isset($_POST['ARCHIVED_DECOM']) ? $_POST['ARCHIVED_DECOM'] : null;
    $RESTORE_DECOM = isset($_POST['RESTORE_DECOM']) ? $_POST['RESTORE_DECOM'] : null;

    if ($reset == 'RESET') {
        $date_month = '';
        $current_month = '';
        $chk_status = 'off';
        $chk_cancel = 'off';
        $chk_archived_decom = 'off';
        $demandeur = '';
        $createur = '';
        $reset = '';
    }

    $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());

    if ($ARCHIVED_DECOM != null) {
        $queryarchived = "update cmdb.suivie_decommission set ARCHIVED_DECOM='1' where pgmp_ticket='".$ARCHIVED_DECOM."';";
        // echo "<pre>$queryarchived</pre>";
        if ($stmt = $con->prepare($queryarchived)) {
            $result = $stmt->execute();
            // echo "<pre>".print_r($stmt)."</pre>";
        }
    }
    if ($RESTORE_DECOM != null) {
        $queryrestore = "update cmdb.suivie_decommission set ARCHIVED_DECOM='0' where pgmp_ticket='".$RESTORE_DECOM."';";
        // echo "<pre>$queryarchived</pre>";
        if ($stmt = $con->prepare($queryrestore)) {
            $result = $stmt->execute();
            // echo "<pre>".print_r($stmt)."</pre>";
        }
    }

?>
<html>

<head>
	<title>Revue des decommissions</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
			left: 5px;
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
			/* 		content: "non";
 */
			left: 28px
		}

		[type="checkbox"]:checked+label .ui:after {
			/* 		content: "oui";
 */
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
		<h3 class="jumbotron">
			<center>Revue des decommissions</center>
		</h3>
	</div>
	<div>
		<form id="filtre_decom_done" name="filtre_decom_done" class="form-horizontal" method="POST"
			enctype="multipart/form-data" action="list_decommission.php">

			<Table class="table table-hover" width="100%">
				<tbody>
					<tr>
						<td>
							<br />
							<?php

            if ($chk_archived_decom == 'off') {
                echo "<input type=\"checkbox\" id=\"chk_archived_decom\" name=\"chk_archived_decom\" target=\"frame_decom\" onclick='document.getElementById(\"filtre_decom_done\").submit()' >";
                echo '<label for="chk_archived_decom" aria-describedby="label"><span class="ui"></span>Décommissionnements Archivés: Cachés</label><span class="ui"></span></br></br>';
                $value_archived_decom = 0;
            } else {
                echo "<input type=\"checkbox\" id=\"chk_archived_decom\" name=\"chk_archived_decom\" target=\"frame_decom\" onclick='document.getElementById(\"filtre_decom_done\").submit()' checked >";
                echo '<label for="chk_archived_decom" aria-describedby="label"><span class="ui"></span>Décommissionnements Archivés: Affichés</label><span class="ui"></span></br></br>';
                $value_archived_decom = 1;
            }

            if ($chk_status == 'on' && $chk_cancel == 'off') {
                echo "<input type=\"checkbox\" id=\"chk_decom_done\" name=\"chk_decom_done\" target=\"frame_decom\" onclick='document.getElementById(\"filtre_decom_done\").submit()' checked>";
                echo '<label for="chk_decom_done" aria-describedby="label"><span class="ui"></span>Décommissionnements Terminés: Cachés</label><span class="ui"></span></br></br>';
                echo "<input type=\"checkbox\" id=\"chk_cancel\" name=\"chk_cancel\" target=\"frame_decom\" onclick='document.getElementById(\"filtre_decom_done\").submit()'  >";
                echo '<label for="chk_cancel" aria-describedby="label"><span class="ui"></span>Décommissionnements annulées: Affichés</label><span class="ui"></span></br></br>';

                $value_status = ' and DecommissionDone<>1';
            } elseif ($chk_status == 'off' && $chk_cancel == 'off') {
                echo "<input type=\"checkbox\" id=\"chk_decom_done\" name=\"chk_decom_done\" target=\"frame_decom\" onclick='document.getElementById(\"filtre_decom_done\").submit()'  >";
                echo '<label for="chk_decom_done" aria-describedby="label"><span class="ui"></span>Décommissionnements Terminés: Affichés</label><span class="ui"></span></br></br>';
                echo "<input type=\"checkbox\" id=\"chk_cancel\" name=\"chk_cancel\" target=\"frame_decom\" onclick='document.getElementById(\"filtre_decom_done\").submit()'  >";
                echo '<label for="chk_cancel" aria-describedby="label"><span class="ui"></span>Décommissionnements annulées: Affichés</label><span class="ui"></span></br></br>';
                $value_status = ' ';
            } elseif ($chk_status == 'off' && $chk_cancel == 'on') {
                echo "<input type=\"checkbox\" id=\"chk_decom_done\" name=\"chk_decom_done\" target=\"frame_decom\" onclick='document.getElementById(\"filtre_decom_done\").submit()'  >";
                echo '<label for="chk_decom_done" aria-describedby="label"><span class="ui"></span>Décommissionnements Terminés: Affichés</label><span class="ui"></span></br></br>';
                echo "<input type=\"checkbox\" id=\"chk_cancel\" name=\"chk_cancel\" target=\"frame_decom\" onclick='document.getElementById(\"filtre_decom_done\").submit()' checked >";
                echo '<label for="chk_cancel" aria-describedby="label"><span class="ui"></span>Décommissionnements annulées: Cachés</label><span class="ui"></span></br></br>';
                $value_status = ' and DecommissionDone<>2';
            } elseif ($chk_status == 'on' && $chk_cancel == 'on') {
                echo "<input type=\"checkbox\" id=\"chk_decom_done\" name=\"chk_decom_done\" target=\"frame_decom\" onclick='document.getElementById(\"filtre_decom_done\").submit()' checked >";
                echo '<label for="chk_decom_done" aria-describedby="label"><span class="ui"></span>Décommissionnements Terminés: Cachés</label><span class="ui"></span></br></br>';
                echo "<input type=\"checkbox\" id=\"chk_cancel\" name=\"chk_cancel\" target=\"frame_decom\" onclick='document.getElementById(\"filtre_decom_done\").submit()' checked >";
                echo '<label for="chk_cancel" aria-describedby="label"><span class="ui"></span>Décommissionnements annulées: Cachés</label><span class="ui"></span></br></br>';
                $value_status = ' and DecommissionDone=0';
            }

            ?>
            </td>
						<td>
							<center>
								<label>Filtrer le mois de décommissionnement: </label> <input type="month" name="mois"
									id="mois" class="input-lg" value="<?php echo $date_month; ?>"
									onchange='document.getElementById("filtre_decom_done").submit()'>

							</center>
						</td>
						<td>
							<center>
								<label>Filtrer par demandeur: </label>
								<input list="demandeur_list" name="demandeur" width='auto' class="input-lg"
									onchange='document.getElementById("filtre_decom_done").submit()' value="">
								<datalist id="demandeur_list">
									<option value=""></option>
									<?php

                        $querytable = 'select distinct Demandeur from cmdb.suivie_decommission order by Demandeur';

                        if ($stmt = $con->prepare($querytable)) {
                            $stmt->execute();
                            while ($resulttable = $stmt->fetch()) {
                                if ($demandeur == $resulttable[0]) {
                                    echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
                                } else {
                                    echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
                                }

                                $stmt->pdo = null;
                            }
                        }
                    ?>
								</datalist>
							</center>
						</td>
						<td>
							<center>
								<label>Filtrer par createur: </label>
								<input list="createur_list" name="createur" width='auto' class="input-lg"
									onchange='document.getElementById("filtre_decom_done").submit()' value="">
								<datalist id="createur_list">
									<option value=""></option>
									<?php

                            $querytable = 'select distinct TicketCreatedBy from cmdb.suivie_decommission order by TicketCreatedBy';

                            if ($stmt = $con->prepare($querytable)) {
                                $stmt->execute();
                                while ($resulttable = $stmt->fetch()) {
                                    if ($createur == $resulttable[0]) {
                                        echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
                                    } else {
                                        echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
                                    }

                                    $stmt->pdo = null;
                                }
                            }
                    ?>
								</datalist>
							</center>
						</td>
						<td>
							<center><input name="RESET" type="submit" value="RESET" class="btn btn-sm"
									onclick='document.getElementById("filtre_decom_done").submit()'></center>
						</td>
					</tr>
				</tbody>
			</Table>

		</form>
	</div>
	<?php

        $querydb = "SELECT * FROM cmdb.suivie_decommission where ARCHIVED_DECOM=$value_archived_decom".$value_status;

        if ($date_month != '') {
            $querydb = $querydb." and DateOfDecommission like '$date_month%'";
            if ($demandeur != '') {
                $querydb = $querydb." and Demandeur = '$demandeur'";
            }
            if ($createur != '') {
                $querydb = $querydb." and TicketCreatedBy = '$createur'";
            }
        } else {
            if ($demandeur != '') {
                $querydb = $querydb." and Demandeur = '$demandeur'";
            }
            if ($createur != '') {
                $querydb = $querydb." and TicketCreatedBy = '$createur'";
            }
        }

        $querydb = $querydb.' order by pgmp_ticket';
        // echo "<pre>";
        // echo $querydb;
        // echo "</pre>";
       // debug de variables
 	//    echo "<p class=\"debug\">";
    //    error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

    //    $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
    //    $var_ignore=array("GLOBALS", "_ENV", "_SERVER","_GET","host","dbname","user","password","port","socket"); // Détermine les var à ne pas afficher
    //    echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
    //    $nom_fonction=__FUNCTION__;
    //    if (isset($nom_fonction)&&$nom_fonction!="")
    //    {
    //        echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
    //    }
    //    foreach ($variables as $key=>$value)
    //    {
    //        if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
    //           {
    //            echo "<pre class=\"debug\">";
    //            echo ("$".$key." => ");
    //            print_r($value);
    //            echo "</pre>\n";
    //          }
    //    }

    //    echo "</p>"; 
        if ($stmt = $con->prepare($querydb)) {
            $stmt->execute();
            $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $today = new DateTime('today');

            if (count($tuples)) 
            {
                echo '<table class="display">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>ID</th><th>Nom des machines</th><th>Base de données</th><th>Environnement</th><th>Numéro pgmp</th><th>Description</th><th>Demandeur</th><th>PGMP créé par </th><th>Priorité</th><th>Date de création du PGMP</th><th>Date de décom </th><th>Décom faites</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($tuples as $ligne) 
                {
                    if ($ligne['DecommissionDone'] == 2) 
                    {
                        $debut_barrer = '<td><del>';
                        $fin_barrer = '</del></td>';
                    } 
                    else 
                    {
                        $debut_barrer = '<td>';
                        $fin_barrer = '</td>';
                    }
                    echo '<tr>';
                    foreach ($ligne as $entete => $valeur) {
                        switch ($entete) {
                            
                            case 'hostname':

                                $var_LIST_MACHINES_IMPACTEES = explode(',', $valeur);
                                echo $debut_barrer;
                                if (isset($var_LIST_MACHINES_IMPACTEES)) {
                                    foreach ($var_LIST_MACHINES_IMPACTEES as $value) 
                                    {
                                        echo '
                                            <form id="'.$value.'" method="POST" action="fiche_machine.php">
                                                <input type="hidden" name="machine" value="'.$value."\"/>
                                            </form>
                                            <a href='#' onclick='document.getElementById(\"".$value."\").submit()'>
                                                <b>".$value.'</b>
                                            </a>';
                                        echo "<input type='hidden' name='LIST_MACHINES_IMPACTEES'
                                            value='<?php echo base64_encode(serialize($var_LIST_MACHINES_IMPACTEES)); ?>' />";
                                        
                                    }
                                }
                                echo $fin_barrer;
                                break;
                            
                            case 'database':
                                $var_LIST_DB_IMPACTEES = explode(',', $valeur);
                                echo $debut_barrer;
                                if (isset($var_LIST_DB_IMPACTEES)) {
                                    foreach ($var_LIST_DB_IMPACTEES as $value) 
                                    {
                                        echo '
                                            <form id="'.$value.'" method="POST" action="application_by_db.php">
                                                <input type="hidden" name="db" value="'.$value."\"/>
                                            </form>
                                            <a href='#' onclick='document.getElementById(\"".$value."\").submit()'>
                                                <b>".$value.'</b>
                                            </a>';                                        
                                        echo "<input type='hidden' name='LIST_DB_IMPACTEES' value='<?php echo base64_encode(serialize($var_LIST_DB_IMPACTEES)); ?>' />";
                                        
                                    }
                                }
                                echo $fin_barrer;

                                break;
                            case 'pgmp_ticket':
                                if ($ligne['ARCHIVED_DECOM'] == 0) 
                                {
                                    echo $debut_barrer.'<form id="'.$valeur.'" method="POST" action="set_decommission.php"><input type="hidden" name="ticketPGMP" value="'.$valeur."\"/><input type=\"hidden\" name=\"consult\" value=\"1\"/> </form><a href='#' onclick='document.getElementById(\"".$valeur."\").submit()' target=\"frame_decom\"><b>".$valeur.'</b></a>'.$fin_barrer;
                                } 
                                else 
                                {
                                    echo $debut_barrer.$valeur.$fin_barrer;
                                }
                                break;
                            case 'DateOfDecommission':
                                $date_decom = new DateTime($valeur);
                                if ($today > $date_decom) {
                                    switch ($ligne['DecommissionDone']) {
                                        case 0:
                                            echo"<td bgcolor=\"#FF0000\"><b>$valeur</b></td>";
                                            break;
                                        case 1:
                                            echo "<td bgcolor=\"#00FF00\"><b>$valeur</b></td>";
                                            break;
                                        case 2:
                                            echo $debut_barrer.'<b>'.$valeur.'</b>'.$fin_barrer;
                                            break;
                                    }
                                } 
                                else 
                                {
                                    switch ($ligne['DecommissionDone']) {
                                        case 2:
                                            echo $debut_barrer.'<b>'.$valeur.'</b>'.$fin_barrer;
                                            break;
                                        default:
                                            echo"<td bgcolor=\"#FFFF00\">$valeur</td>";
                                            break;
                                    }
                                }
                                break;
                            case 'DecommissionDone':
                                switch ($valeur) {
                                    case 0:
                                        echo '<td>PAS ENCORE FAIT</td>';
                                        break;
                                    case 1:
                                        echo '<td>FAIT</td>';
                                        break;
                                    case 2:
                                        echo $debut_barrer.'Annulé'.$fin_barrer;
                                        break;
                                }
                                break;
                            case 'ARCHIVED_DECOM':
                                if ($ligne['ARCHIVED_DECOM'] == 0 && $ligne['DecommissionDone'] != 0) 
                                {
                                    echo '<td><form id="DEL_'.$ligne['pgmp_ticket'].'" method="POST" action="list_decommission.php"><input type="hidden" name="ARCHIVED_DECOM" value="'.$ligne['pgmp_ticket'].'"/><input type="submit" class="btn btn-sm" value="Archiver Décom"></form></td>';
                                } elseif ($ligne['ARCHIVED_DECOM'] == 1 && $ligne['DecommissionDone'] != 0) 
                                {
                                    echo '<td><form id="RESTORE_'.$ligne['pgmp_ticket'].'" method="POST" action="list_decommission.php"><input type="hidden" name="RESTORE_DECOM" value="'.$ligne['pgmp_ticket'].'"/><input type="submit" class="btn btn-sm" value="Restaurer Décom"></form></td>';
                                }
                                break;
                            default:
                                switch ($ligne['DecommissionDone']) 
                                {
                                    case 2:
                                        echo $debut_barrer.'<b>'.$valeur.'</b>'.$fin_barrer;
                                        break;
                                    default:
                                          echo $debut_barrer.$valeur.$fin_barrer;
                                        break;
                                }
                                break;
                        }
                    }
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
            }

            $stmt->pdo = null;
        }

        ?>
    </body>

</html>