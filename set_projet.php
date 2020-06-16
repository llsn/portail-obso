<?php

require 'variable-db.php';

    $id = isset($_POST['PROJET_ID']) ? $_POST['PROJET_ID'] : null;
    $var_RFS_NUMBER_CMA = isset($_POST['RFS_NUMBER_CMA']) ? $_POST['RFS_NUMBER_CMA'] : null;
    $var_CODE_BUDGET = isset($_POST['CODE_BUDGET']) ? $_POST['CODE_BUDGET'] : null;
    $var_PROJECT_NAME = isset($_POST['PROJECT_NAME']) ? $_POST['PROJECT_NAME'] : null;
    $var_PM_TECHNIQUE = isset($_POST['PM_TECHNIQUE']) ? $_POST['PM_TECHNIQUE'] : null;
    $var_PM_FONCTIONNEL = isset($_POST['PM_FONCTIONNEL']) ? $_POST['PM_FONCTIONNEL'] : null;
    $var_REF_ORCHESTRA = isset($_POST['REF_ORCHESTRA']) ? $_POST['REF_ORCHESTRA'] : null;
    $var_DATE_CREATION = isset($_POST['DATE_CREATION']) ? $_POST['DATE_CREATION'] : '0000-00-00';
    $var_GLOBAL_STATUS = isset($_POST['GLOBAL_STATUS']) ? $_POST['GLOBAL_STATUS'] : null;
    $var_EVOLUTION_STATUS = isset($_POST['EVOLUTION_STATUS']) ? $_POST['EVOLUTION_STATUS'] : null;
    $var_SPS_MEETING = isset($_POST['SPS_MEETING']) ? $_POST['SPS_MEETING'] : '0000-00-00';
    $var_COARCH_VALIDATION = isset($_POST['COARCH_VALIDATION']) ? $_POST['COARCH_VALIDATION'] : '0000-00-00';
    $var_PDR_VALIDATION = isset($_POST['PDR_VALIDATION']) ? $_POST['PDR_VALIDATION'] : '0000-00-00';
    $var_GO_LIVE = isset($_POST['GO_LIVE']) ? $_POST['GO_LIVE'] : '0000-00-00';
    $var_INITIALE_CLOSURE_DATE = isset($_POST['INITIALE_CLOSURE_DATE']) ? $_POST['INITIALE_CLOSURE_DATE'] : '0000-00-00';
    $var_REVISED_CLOSURE_DATE = isset($_POST['REVISED_CLOSURE_DATE']) ? $_POST['REVISED_CLOSURE_DATE'] : '0000-00-00';
    $var_PROJECT_FINISH = isset($_POST['PROJECT_FINISH']) ? $_POST['PROJECT_FINISH'] : null;
    $var_LIST_MACHINES_IMPACTEES = isset($_POST['LIST_MACHINES_IMPACTEES']) ? unserialize(base64_decode($_POST['LIST_MACHINES_IMPACTEES'])) : null;
    $var_add_line = isset($_POST['add_comment_for_id']) ? $_POST['add_comment_for_id'] : null;
    $var_delete_line = isset($_POST['delete_comment_for_id']) ? $_POST['delete_comment_for_id'] : null;
    $var_APP_CMACGM = isset($_POST['APP_CMACGM']) ? $_POST['APP_CMACGM'] : null;
    $COMMENTAIRES = isset($_POST['COMMENTAIRES']) ? $_POST['COMMENTAIRES'] : null;
    $result = 0;

$con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
    or die('Could not connect to the database server'.pdo_connect_error());
if (isset($_POST['delete_comment_for_id']) != null) {
    $delete_line_id = $_POST['delete_comment_for_id'];
    $query_add_comment = "call `cmdb`.`delete_projet_line_id`($delete_line_id);";
    if ($stmt = $con->prepare($query_add_comment)) {
        $result = $stmt->execute();
    }
    $stmt->pdo = null;
    echo "<div bgcolor=\"#00FF00\"><center><h4>La ligne $delete_line_id a bien été supprimé <h4><center></table></div>";
}

if ($var_add_line != null) {
    if ($COMMENTAIRES != '') {
        $query_add_comment = "call `cmdb`.`insert_projet_comment`($var_add_line, '".str_ireplace("'", "\'", $COMMENTAIRES)."');";
        if ($stmt = $con->prepare($query_add_comment)) {
            $result = $stmt->execute();
        }
        $stmt->pdo = null;
    } else {
        echo '<div bgcolor="#00FF00"><center><h4>Le commentaire est vide - enregistrement refusé<h4><center></table></div>';
    }
}
if ($var_RFS_NUMBER_CMA != null) {
    if (isset($_POST['consult']) == 1) {
        $queryset = "select * from suivie_projets where RFS_NUMBER_CMA='$var_RFS_NUMBER_CMA'";
    } elseif (!isset($_POST['consult'])) {
        $queryset = "CALL `cmdb`.`new_projet`('$var_RFS_NUMBER_CMA', '$var_CODE_BUDGET', '$var_PROJECT_NAME', '$var_PM_TECHNIQUE', '$var_PM_FONCTIONNEL', '$var_REF_ORCHESTRA', '$var_DATE_CREATION', '$var_GLOBAL_STATUS', '$var_EVOLUTION_STATUS', '$var_SPS_MEETING', '$var_COARCH_VALIDATION', '$var_PDR_VALIDATION', '$var_GO_LIVE', '$var_INITIALE_CLOSURE_DATE', '$var_REVISED_CLOSURE_DATE', $var_PROJECT_FINISH,'$var_APP_CMACGM');";
        echo "<pre>$queryset</pre>";
    }
    // echo $queryset;
    if ($stmt = $con->prepare($queryset)) {
        $result = $stmt->execute();
        // echo "<pre>".print_r($result)."</pre>";
        $queryset = "select * from suivie_projets where RFS_NUMBER_CMA='$var_RFS_NUMBER_CMA'";
        if ($stmt = $con->prepare($queryset)) {
            $result = $stmt->execute();
            while ($resultnameserver = $stmt->fetch()) {
                foreach ($resultnameserver as $entete => $valeur) {
                    switch ($entete) {
                        case '﻿suivie_projets_id':
                            $id = $valeur;
                        break;
                        case 'RFS_NUMBER_CMA':
                            $var_RFS_NUMBER_CMA = $valeur;
                        break;
                        case 'CODE_BUDGET':
                            $var_CODE_BUDGET = $valeur;
                        break;
                        case 'PROJECT_NAME':
                            $var_PROJECT_NAME = $valeur;
                        break;
                        case 'PM_TECHNIQUE':
                            $var_PM_TECHNIQUE = $valeur;
                        break;
                        case 'PM_FONCTIONNEL':
                            $var_PM_FONCTIONNEL = $valeur;
                        break;
                        case 'REF_ORCHESTRA':
                            $var_REF_ORCHESTRA = $valeur;
                        break;
                        case 'DATE_CREATION':
                            $var_DATE_CREATION = $valeur;
                        break;
                        case 'GLOBAL_STATUS':
                            $var_GLOBAL_STATUS = $valeur;
                        break;
                        case 'EVOLUTION_STATUS':
                            $var_EVOLUTION_STATUS = $valeur;
                        break;
                        case 'SPS_MEETING':
                            $var_SPS_MEETING = $valeur;
                        break;
                        case 'COARCH_VALIDATION':
                            $var_COARCH_VALIDATION = $valeur;
                        break;
                        case 'PDR_VALIDATION':
                            $var_PDR_VALIDATION = $valeur;
                        break;
                        case 'GO_LIVE':
                            $var_GO_LIVE = $valeur;
                        break;
                        case 'INITIALE_CLOSURE_DATE':
                            $var_INITIALE_CLOSURE_DATE = $valeur;
                        break;
                        case 'REVISED_CLOSURE_DATE':
                            $var_REVISED_CLOSURE_DATE = $valeur;
                        break;
                        case 'PROJECT_FINISH':
                            $var_PROJECT_FINISH = $valeur;
                        break;
                        case 'LIST_MACHINES_IMPACTEES':
                            $var_LIST_MACHINES_IMPACTEES = explode(',', $valeur);
                        break;
                        case 'pgmp_tickets':
                            $var_LIST_PGMP = explode(',', $valeur);
                        break;
                        case 'Application_name':
                            $var_APP_CMACGM = $valeur;
                        break;
                    }
                }
            }
        }
        // echo "<pre>";
        // print_r($stmt);
        // print_r($resultnameserver);
        // echo "</pre>";
    }
    $stmt->pdo = null;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="generator" content="Bluefish 2.2.10" >
<!--  <script type="text/javascript" src="./database_xhr.js" charset="iso_8859-1"></script> -->
<link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
  <title>Saisie des projets</title>
  </head>
  <body>
	<div>
		<?php
            if ($result == 1 && !isset($_POST['consult'])) {
                echo '<div bgcolor="#00FF00"><center><h4>Enregistrement réussi<h4><center></table></div>';
            }

        ?>
		<form id="projet" class="form-horizontal" method="POST" enctype="multipart/form-data" action="set_projet.php">
			<table class="table table-bordered" width="80%">
				<thead>
					<tr>
						<th colspan='2'><center><h1 class="jumbotron">Fiche de saisie du projet</h1></center></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th width='20%'><label class="required" :after>Numéro RFS</label></th><td width='80%'>
							<acronym title="choisissez ou saisissez le numero du ticket RFS (source Orchestra)">
							   <input type="text" name ="RFS_NUMBER_CMA" size="50" class="input-lg" value="<?php echo $var_RFS_NUMBER_CMA; ?>" required>
							</acronym>
						</td>
					</tr>
                    <tr>
						<th width='20%'><label class="required" :after>Code Budget</label></th>
						<td width='80%'>
					       	<acronym title="SaisisseZ le code budget correspondant (source Orchestra)">
							   <input type="text" id="CODE_BUDGET" name ="CODE_BUDGET" size="50" class="input-lg" value="<?php echo $var_CODE_BUDGET; ?>" required>
							</acronym>
						</td>
					</tr>
					<tr>
						<th width='20%'><label class="required" :after>Application CMA-CGM</label></th>
						<td width='80%'>
						<acronym title="Saisir le nom de l'application CMACGM impactés par ce projet">
								<input list="Application_list" id="APP_CMACGM" name="APP_CMACGM" size ="50" class="input-lg" size="1" value="<?php echo $var_APP_CMACGM; ?>" required>
							</acronym>
							<datalist id="Application_list">
									<option value=""></option>
									<?php

                                        $querytable = 'select distinct businessservices from cmdb.application order by businessservices';

                                        if ($stmt = $con->prepare($querytable)) {
                                            $stmt->execute();
                                            while ($resulttable = $stmt->fetch()) {
                                                if ($var_APP_CMACGM == $resulttable[0]) {
                                                    echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
                                                } else {
                                                    echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
                                                }

                                                $stmt->pdo = null;
                                            }
                                        }
                                    ?>
								</datalist>
						</td>
					</tr>
                    <tr>
						<th width='20%'><label class="required" :after>Intitulé du projet</label></th>
							<td width='80%'>
                                <acronym title="Saisir l'intitulé du projet (source Orchestra)">
								   <input type="text" id="PROJECT_NAME" name ="PROJECT_NAME" size="100" class="input-lg" value="<?php echo $var_PROJECT_NAME; ?>" required>
								</acronym>
							</td>

					</tr>
					<tr>
						<th width='20%'><label class="required" :after>Nom du chef de projet technique</label></th>
						<td width='80%'>
		                    <acronym title="Saisir le nom du chef de projet technique (source Orchestra)">
								<input list="PM_TECHNIQUE_list" id="PM_TECHNIQUE" name="PM_TECHNIQUE" size ="50" class="input-lg" size="1" value="<?php echo $var_PM_TECHNIQUE; ?>" required>
							</acronym>
							<datalist id="PM_TECHNIQUE_list">
									<option value=""></option>
									<?php

                                        $querytable = 'select distinct PM_TECHNIQUE from cmdb.suivie_projets order by PM_TECHNIQUE';

                                        if ($stmt = $con->prepare($querytable)) {
                                            $stmt->execute();
                                            while ($resulttable = $stmt->fetch()) {
                                                if ($var_PM_TECHNIQUE == $resulttable[0]) {
                                                    echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
                                                } else {
                                                    echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
                                                }

                                                $stmt->pdo = null;
                                            }
                                        }
                                    ?>
								</datalist>
						</td>
					</tr>
					<tr>
						<th width='20%'>Nom du chef de projet fonctionnel</th>
						<td width='80%'>
		                    <acronym title="Saisir le nom du chef de projet fonctionnel (source Orchestra)">
								<input list="PM_FONCTIONNEL_list" id="PM_FONCTIONNEL" name="PM_FONCTIONNEL" size ="50" class="input-lg" size="1" value="<?php echo $var_PM_FONCTIONNEL; ?>">
							</acronym>
							<datalist id="PM_FONCTIONNEL_list">
									<option value=""></option>
									<?php

                                        $querytable = 'select distinct PM_FONCTIONNEL from cmdb.suivie_projets order by PM_FONCTIONNEL';

                                        if ($stmt = $con->prepare($querytable)) {
                                            $stmt->execute();
                                            while ($resulttable = $stmt->fetch()) {
                                                if ($var_PM_FONCTIONNEL == $resulttable[0]) {
                                                    echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
                                                } else {
                                                    echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
                                                }

                                                $stmt->pdo = null;
                                            }
                                        }
                                    ?>
								</datalist>
						</td>
					</tr>
			        <tr>
						<th width='20%'><label class="required" :after>REFERENCE ORCHESTRA</label></th>
						<td width='80%'>
							<acronym title="Saisir la référence ORCHESTRA (source Orchestra)">
								<input type="text" id="REF_ORCHESTRA" name='REF_ORCHESTRA' class="input-lg" value="<?php echo $var_REF_ORCHESTRA; ?>" required>
							</acronym>
						</td>
					</tr>
			        <tr>
						<th width='20%'><label class="required" :after>DATE de CREATION du PROJET</label></th>
						<td width='80%'>
							<acronym title="Sélectionnez la date de création du projet (source Orchestra)">
								<input type="date" id="DATE_CREATION" name="DATE_CREATION" class="input-lg" value="<?php echo $var_DATE_CREATION; ?>" required>
							</acronym>
						</td>
					</tr>
			        <tr>
						<th width='20%'>GLOBAL STATUS</th>
						<td width='80%'>
							<!-- <input name='GLOBAL_STATUS' id="GLOBAL_STATUS" width='auto' type="text" class="input-lg" value="<?php echo $var_GLOBAL_STATUS; ?>"> -->
							<select id="GLOBAL_STATUS" name="GLOBAL_STATUS" class="input-lg">
								<?php
                                switch ($var_GLOBAL_STATUS) {
                                    case 3:
                                        echo '<option value="3" selected>Terminé</option>';
                                        echo '<option value="4">Aucune info</option>';
                                           echo '<option  value="2">En cours</option>';
                                           echo '<option  value="1">En pause</option>';
                                        echo '<option  value="0" >Annulé</option>';
                                        break;
                                    case 2:
                                        echo '<option value="3">Terminé</option>';
                                        echo '<option value="4">Aucune info</option>';
                                           echo '<option  value="2" selected>En cours</option>';
                                           echo '<option  value="1">En pause</option>';
                                        echo '<option  value="0" >Annulé</option>';
                                        break;
                                    case 1:
                                        echo '<option value="3">Terminé</option>';
                                        echo '<option value="4">Aucune info</option>';
                                        echo '<option  value="2">En cours</option>';
                                           echo '<option  value="1" selected>En pause</option>';
                                        echo '<option  value="0">Annulé</option>';
                                        break;
                                    case 0:
                                        echo '<option value="3">Terminé</option>';
                                        echo '<option value="4">Aucune info</option>';
                                        echo '<option  value="2">En cours</option>';
                                           echo '<option  value="1">En pause</option>';
                                        echo '<option  value="0" selected>Annulé</option>';
                                        break;
                                    default:
                                        echo '<option value="3">Terminé</option>';
                                        echo '<option value="4" selected>Aucune info</option>';
                                        echo '<option  value="2">En cours</option>';
                                           echo '<option  value="1">En pause</option>';
                                        echo '<option  value="0">Annulé</option>';
                                        break;
                                }
                                ?>
							</select> 
						</td>
					</tr>
					<tr>
						<th width='20%'>EVOLUTION STATUS</th>
						<td width='80%'>
							<!-- <input name='EVOLUTION_STATUS' id="EVOLUTION_STATUS" width='auto' type="text" class="input-lg" value="<?php echo $var_EVOLUTION_STATUS; ?>"> -->
							<select id="EVOLUTION_STATUS" name="EVOLUTION_STATUS" class="input-lg">
								<?php
                                switch ($var_EVOLUTION_STATUS) {
                                    case 2:
                                        echo '<option value="4">Aucune info</option>';
                                           echo '<option  value="2" selected>en avance</option>';
                                           echo '<option  value="1">dans les temps</option>';
                                        echo '<option  value="0">en retard</option>';
                                        break;
                                    case 1:
                                        echo '<option value="4">Aucune info</option>';
                                        echo '<option  value="2">en avance</option>';
                                           echo '<option  value="1" selected>dans les temps</option>';
                                        echo '<option  value="0">en retard</option>';
                                        break;
                                    case 0:
                                        echo '<option value="4">Aucune info</option>';
                                        echo '<option  value="2">en avance</option>';
                                           echo '<option  value="1">dans les temps</option>';
                                        echo '<option  value="0" selected>en retard</option>';
                                        break;
                                    default:
                                        echo '<option value="4" selected>Aucune info</option>';
                                        echo '<option  value="2">en avance</option>';
                                           echo '<option  value="1">dans les temps</option>';
                                        echo '<option  value="0">en retard</option>';
                                        break;
                                }
                                ?>
							</select> 
						</td>
					</tr>
					<tr>
						<th width='20%'>SPS MEETING</th><td width='80%'><input name='SPS_MEETING' id="SPS_MEETING" width='auto' type="date" class="input-lg" value="<?php echo $var_SPS_MEETING; ?>"></td>
					</tr>
			        <tr>
						<th width='20%'>COARCH_VALIDATION</th><td width='80%'><input name='COARCH_VALIDATION' id="COARCH_VALIDATION" width='auto' type="date" class="input-lg" value="<?php echo $var_COARCH_VALIDATION; ?>"></td>
					</tr>
					<tr>
						<th width='20%'>PDR_VALIDATION</th><td width='80%'><input name='PDR_VALIDATION' id="PDR_VALIDATION" width='auto' type="date" class="input-lg" value="<?php echo $var_PDR_VALIDATION; ?>"></td>
					</tr>
					<tr>
						<th width='20%'>GO_LIVE</th><td width='80%'><input name='GO_LIVE' id="GO_LIVE" width='auto' type="date" class="input-lg" value="<?php echo $var_GO_LIVE; ?>"></td>
					</tr>
					<tr>
						<th width='20%'>INITIALE_CLOSURE_DATE</th><td width='80%'><input name='INITIALE_CLOSURE_DATE' id="INITIALE_CLOSURE_DATE" width='auto' type="date" class="input-lg" value="<?php echo $var_INITIALE_CLOSURE_DATE; ?>"></td>
					</tr>
					<tr>
						<th width='20%'>REVISED_CLOSURE_DATE</th><td width='80%'><input name='REVISED_CLOSURE_DATE' id="REVISED_CLOSURE_DATE" width='auto' type="date" class="input-lg" value="<?php echo $var_REVISED_CLOSURE_DATE; ?>"></td>
					</tr>
			        <tr>
						<th width='20%'>Le projet est-il bien terminé (Base de donnée ou/et machine décommissionnées)?</th><td width='80%'><select name="PROJECT_FINISH" width='auto' class="input-lg" value="<?php echo $var_PROJECT_FINISH; ?>">
							<?php
                                switch ($var_PROJECT_FINISH) {
                                    case 0:
                                           echo '<option value="0" selected>NON</option>';
                                           echo '<option value="1">OUI</option>';
                                        break;
                                    case 1:
                                        echo '<option value="0">NON</option>';
                                           echo '<option value="1" selected>OUI</option>';
                                        break;
                                    default:
                                        echo '<option value="0" selected>NON</option>';
                                           echo '<option value="1">OUI</option>';
                                        break;
                                }
                            ?>
						</select></td>
					</tr>
					
				</tbody>
				<tfoot>
					<tr>
						<td colspan="2"><center><input type="submit" class="btn btn-lg" value="Enregistrer"></form></center></td>
					</tr>
				</tfoot>
				</table>
				<br>

				<table class="tablea table-hover">
					<thead>
						<tr>
							<th colspan="2"><h2><center>Liste des PGMP liés au projet</center></h2></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2">
								<center><?php
                                    if (isset($var_LIST_PGMP)) {
                                        foreach ($var_LIST_PGMP as $value) {
                                            echo '<form id="'.$value.'" method="POST" action="set_decommission.php"><input type="hidden" name="ticketPGMP" value="'.str_replace(' ', '', $value)."\"/><input type=\"hidden\" name=\"consult\" value=\"1\"/></form><a href='#' onclick='document.getElementById(\"".$value."\").submit()'><h4><b>".$value.'</b></h4></a>';
                                            // echo $value."</br>";?>
											<input type="hidden" name="ticketPGMP" value="<?php echo base64_encode(serialize($var_LIST_PGMP)); ?>"/>
											<?php
                                        }
                                    }
                                ?></center>
							</td>
						</tr>
						<tr>
							<td colspan="2">
							<form id="select_machine" name="select_machine" method="POST" enctype="multipart/form-data" action="selection_PGMP.php" >
									
									<input type="hidden" name="RFS_NUMBER_CMA" value="<?php echo $var_RFS_NUMBER_CMA; ?>"/>
									<input type="hidden" name="UPDATE" value="yes"/>
									<center><input type="submit" class="btn btn-submit" value="Sélectionner ou Désélectionner des PGMP"></center>
							</form>
						</td>
						</tr>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				<br>
				<table class="tablea table-hover">
				<thead>
					<tr>
						<th colspan="2"><h2><center>Liste des machines impactées par le projet</center></h2></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="2">
							<center><?php
                                if (isset($var_LIST_MACHINES_IMPACTEES)) {
                                    foreach ($var_LIST_MACHINES_IMPACTEES as $value) {
                                        echo '<form id="'.$value.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$value."\"/></form><a href='#' onclick='document.getElementById(\"".$value."\").submit()'><b>".$value.'</b></a>';
                                        // echo $value."</br>";?>
										<input type="hidden" name="LIST_MACHINES_IMPACTEES" value="<?php echo base64_encode(serialize($var_LIST_MACHINES_IMPACTEES)); ?>"/>
										<?php
                                    }
                                }
                            ?></center>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<form id="select_machine" name="select_machine" method="POST" enctype="multipart/form-data" action="selection.php" >
								
								<input type="hidden" name="RFS_NUMBER_CMA" value="<?php echo $var_RFS_NUMBER_CMA; ?>"/>
								<input type="hidden" name="UPDATE" value="yes"/>
								<center><input type="submit" class="btn btn-submit" value="Sélectionner ou Désélectionner des machines"></center>
						</form>
					</td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<table class="table table-bordered"  width="100%">
				<thead>
					<tr>
						<th colspan="4"><h2><center>Historique des commentaires</center></h2></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th> Commentaires </th>
						<form id="comment" name="comment" method="POST" enctype="multipart/form-data" action="set_projet.php">
						<td colspan="2">
							<input type="hidden" name="RFS_NUMBER_CMA" value="<?php echo $var_RFS_NUMBER_CMA; ?>"/>
							<input type='hidden' name='consult' value='1'/>
							<input type="hidden" name="add_comment_for_id" value="<?php echo $id; ?>"/>
							<textarea cols="180" rows="5" style='width:auto' class='input-lg' name="COMMENTAIRES"></textarea>
						</td>
						<td>
							<input type="submit" class="btn btn-info" value="ajouter ce commentaire">
						</td>
						</form>
					</tr>
					<tr>
						<th>Date</th><th colspan="3"><center>Commentaires</center></th>
					</tr>
					<?php
                        $querycomment = "select suivie_projets_comment_id,date_comment,content_comment from cmdb.suivie_projets_comment where id_suivie_projet='$id' order by date_comment desc";

                        if ($stmt = $con->prepare($querycomment)) {
                            $stmt->execute();
                            $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($tuples as $ligne) {
                                echo '<tr>';
                                // echo "<pre>";
                                // print_r($ligne);
                                // echo "</pre>";
                                foreach ($ligne as $entete => $valeur) {
                                    switch ($entete) {
                                        case 'date_comment':
                                            echo "<th>$valeur</th>";
                                            break;
                                        case 'content_comment':
                                            echo '<td colspan="2">'.nl2br($valeur).'<td>';
                                            break;
                                        case 'suivie_projets_comment_id':
                                            $line_id = $valeur;
                                            break;
                                    }
                                }
                                echo "<form id='DEL_$line_id' name='DEL_$line_id' method='POST' enctype='multipart/form-data' action='set_projet.php'>
								<input type='hidden' name='delete_comment_for_id' value='".$line_id."'/>
								<input type='hidden' name='PROJET_ID' value='".$id."'/>
								<input type='hidden' name='RFS_NUMBER_CMA' value='".$var_RFS_NUMBER_CMA."'/>
								<input type='hidden' name='consult' value='1'/>
								<input type='submit' class='btn btn-danger' value='supprimer la ligne' onClick=\"ConfirmMessage('DEL_$line_id')\"/></form> </td>";
                                echo '</tr>';
                            }
                            $stmt->pdo = null;
                        }
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
				</tbody>
				
			</table>
	</div>
  </body>
</html>
