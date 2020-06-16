<?php

require 'variable-db.php';

$database = isset($_POST['database']) ? $_POST['database'] : null;
$hostname = isset($_POST['hostname']) ? $_POST['hostname'] : null;
$priority = isset($_POST['priority']) ? $_POST['priority'] : null;
$operatingenvironment = isset($_POST['operatingenvironment']) ? $_POST['operatingenvironment'] : null;
$ticketPGMP = isset($_POST['ticketPGMP']) ? $_POST['ticketPGMP'] : null;
$titre = isset($_POST['Titre']) ? $_POST['Titre'] : null;
$demandeur = isset($_POST['demandeur']) ? $_POST['demandeur'] : null;
$createur = isset($_POST['createur']) ? $_POST['createur'] : null;
$date_creation = isset($_POST['date_creation']) ? $_POST['date_creation'] : null;
$var_LIST_MACHINES_IMPACTEES = isset($_POST['LIST_MACHINES_IMPACTEES']) ? unserialize(base64_decode($_POST['LIST_MACHINES_IMPACTEES'])) : null;
$var_LIST_DB_IMPACTEES = isset($_POST['LIST_DB_IMPACTEES']) ? unserialize(base64_decode($_POST['LIST_DB_IMPACTEES'])) : null;
$database = $var_LIST_DB_IMPACTEES;
$hostname = $var_LIST_MACHINES_IMPACTEES;
$COMMENTAIRES = isset($_POST['COMMENTAIRES']) ? $_POST['COMMENTAIRES'] : null;
$id = isset($_POST['DECOM_ID']) ? $_POST['DECOM_ID'] : null;
$result = 0;
$con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
    or die('Could not connect to the database server'.pdo_connect_error());

if (isset($_POST['delete_comment_for_id']) != null) {
    $delete_line_id = $_POST['delete_comment_for_id'];
    $query_add_comment = "call `cmdb`.`delete_decommission_line_id`($delete_line_id);";
    if ($stmt = $con->prepare($query_add_comment)) {
        $result = $stmt->execute();
    }
    $stmt->pdo = null;
    echo "<div bgcolor=\"#00FF00\"><center><h4>La ligne $delete_line_id a bien été supprimé <h4><center></table></div>";
}

if (isset($_POST['add_comment_for_id']) != null) {
    $add_line_id = $_POST['add_comment_for_id'];
    if ($COMMENTAIRES != '') {
        $query_add_comment = "call `cmdb`.`insert_decom_comment`($add_line_id, '".str_ireplace("'", "\'", $COMMENTAIRES)."');";
        if ($stmt = $con->prepare($query_add_comment)) {
            $result = $stmt->execute();
        }
        $stmt->pdo = null;
    } else {
        echo '<div bgcolor="#00FF00"><center><h4>Le commentaire est vide - enregistrement refusé<h4><center></table></div>';
    }
}
if (isset($_POST['date_decom'])) {
    $date_decom = $_POST['date_decom'];
} else {
    $date_decom = '0000-00-00';
}

$decom_done = isset($_POST['decom_done']) ? $_POST['decom_done'] : null;

if ($ticketPGMP != null) {
    if (isset($_POST['consult']) == 1) {
        $queryset = "select * from suivie_decommission where pgmp_ticket='$ticketPGMP'";
    } elseif (!isset($_POST['consult'])) {
        $queryset = "CALL `cmdb`.`new_decom`('$ticketPGMP', '$titre', '$demandeur', '$createur', '$priority', '$operatingenvironment', '$date_creation', '$date_decom', $decom_done);";
    }
    // echo $queryset;
    if ($stmt = $con->prepare($queryset)) {
        $result = $stmt->execute();
        while ($resultnameserver = $stmt->fetch()) {
            foreach ($resultnameserver as $entete => $valeur) {
                switch ($entete) {
                    case 'suivie_decommission_id':
                        $id = $valeur;
                        break;
                    case 'hostname':
                        $var_LIST_MACHINES_IMPACTEES = explode(',', $valeur);
                        //$hostname=$var_LIST_MACHINES_IMPACTEES;
                        break;
                    case 'database':
                        $var_LIST_DB_IMPACTEES = explode(',', $valeur);
                        //$database=$var_LIST_DB_IMPACTEES;
                        break;
                    case 'Priority':
                        $priority = $valeur;
                        break;
                    case 'OperatingEnvrionment':
                        $operatingenvironment = $valeur;
                        break;
                    case 'pgmp_ticket':
                        $ticketPGMP = $valeur;
                        break;
                    case 'TicketTitle':
                        $titre = $valeur;
                        break;
                    case 'Demandeur':
                        $demandeur = $valeur;
                        break;
                    case 'TicketCreatedBy':
                        $createur = $valeur;
                        break;
                    case 'DateOfCreationOfTicketPGMP':
                        $date_creation = $valeur;
                        break;
                    case 'DateOfDecommission':
                        $date_decom = $valeur;
                        break;
                    case 'DecommissionDone':
                        $decom_done = $valeur;
                        break;
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
   // // debug de variables
/*  	 echo "<p class=\"debug\">";
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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="generator" content="Bluefish 2.2.10" >
<!--  <script type="text/javascript" src="./database_xhr.js" charset="iso_8859-1"></script> -->
	<link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
  <title>Suivi du décommissionnement</title>
  </head>
  <body>
	<div>
		<?php
            if ($result == 1 && !isset($_POST['consult'])) {
                echo '<div bgcolor="#00FF00"><center><h4>Enregistrement réussi<h4><center></table></div>';
            }

        ?>
		<form id="ticket_pgmp" class="form-horizontal" method="POST" enctype="multipart/form-data" action="set_decommission.php">
			<input type='hidden' name='DECOM_ID' value='<?php echo $id; ?>'/>
			<table class="table table-bordered" width="80%">
				<thead>
					<tr>
						<th colspan='2'><center><h1 class="jumbotron">Fiche de saisie de décommissionnement</h1></center></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th width='20%'>Numéro de Ticket PGMP</th><td width='80%'>
							<acronym title="choisissez ou saisissez le numero du ticket">
							   <input type="text" name ="ticketPGMP" size="50" class="input-lg" value="<?php echo $ticketPGMP; ?>" required>
							</acronym>
						</td>
					</tr>
					<tr>
						<th width='20%'>Niveau de priorisation du ticket PGMP</th>
						<td width='80%'>
		                    <acronym title="Sélectionnez l'envrionnement de la machine à décommissionner">
								<select name="priority" class="input-lg" size="1" maxlength="15" required ><?php echo $priority; ?><br>
                                    <option value=""></option>
									<?php
                                        switch ($priority) {
                                            case 'LOW':
                                                   echo '<option value="LOW" selected>LOW</option>';
                                                   echo '<option value="MEDIUM">MEDIUM</option>';
                                                echo '<option value="HIGH">HIGH</option>';
                                                break;
                                            case 'MEDIUM':
                                                 echo '<option value="LOW">LOW</option>';
                                                   echo '<option value="MEDIUM" selected>MEDIUM</option>';
                                                echo '<option value="HIGH">HIGH</option>';
                                                break;
                                            case 'HIGH':
                                                echo '<option value="LOW">LOW</option>';
                                                   echo '<option value="MEDIUM">MEDIUM</option>';
                                                echo '<option value="HIGH" selected>HIGH</option>';
                                                break;
                                            default:
                                                echo '<option value="LOW">LOW</option>';
                                                   echo '<option value="MEDIUM">MEDIUM</option>';
                                                echo '<option value="HIGH">HIGH</option>';
                                                break;
                                        }
                                    ?>
								</select>
							</acronym>
						</td>
					</tr>
			        <tr>
						<th width='20%'>Titre</th><td width='80%'><textarea cols="100" name='Titre' style='width:auto' class="input-lg" required><?php echo $titre; ?></textarea></td>
					</tr>
			        <tr>
						<th width='20%'>Environnement dans la CMDB</th>
						<td width='80%'>
							<acronym title="Sélectionnez l'envrionnement de la machine à décommissionner">
								<select name="operatingenvironment" class="input-lg" size="1" maxlength="15" value="<?php echo $operatingenvironment; ?>" required><br>
									<option value=""></option>
									<?php

                                        $querytable = 'select distinct operatingenvironment from system_inventory order by operatingenvironment;';

                                        if ($stmt = $con->prepare($querytable)) {
                                            $stmt->execute();
                                            while ($resulttable = $stmt->fetch()) {
                                                if ($operatingenvironment == $resulttable[0]) {
                                                    echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
                                                } else {
                                                    echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
                                                }

                                                $stmt->pdo = null;
                                            }
                                        }
                                    ?>

								</select>
							</acronym>
						</td>
					</tr>
			        <tr>
						<th width='20%'>Demandeur du decommissionnement</th>
						<td width='80%'>
							<input list="demandeur_list" name="demandeur" width='auto' class="input-lg" value="<?php echo $demandeur; ?>" required>
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
						</td>
					</tr>
			        <tr>
						<th width='20%'>créateur du ticket</th>
						<td width='80%'>
						<input name='createur' list='createur_list' width='auto' class="input-lg" value="<?php echo $createur; ?>" required>
						<datalist id="createur_list">
						<option value=""></option>
						<?php

                            $querytable = 'select distinct TicketCreatedBy from cmdb.suivie_decommission order by TicketCreatedBy';

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
						</td>
					</tr>
			        <tr>
						<th width='20%'>Date de création du ticket</th><td width='80%'><input name='date_creation' require width='auto' type="date" class="input-lg" value="<?php echo $date_creation; ?>"required></td>
					</tr>
			        <tr>
						<th width='20%'>Date de la decommission de la machine</th><td width='80%'><input name='date_decom' width='auto' type="date" class="input-lg" value="<?php echo $date_decom; ?>"></td>
					</tr>
			        <tr>
						<th width='20%'>La machine a-t'elle bien été decommissionnée?</th><td width='80%'><select name="decom_done" width='auto' class="input-lg" value="<?php echo $decom_done; ?>">
							<?php
                                switch ($decom_done) {
                                    case 0:
                                           echo '<option value="0" selected> Pas encore fait</option>';
                                           echo '<option value="1"> Fait</option>';
                                        echo '<option Value="2"> Annulé </option>';
                                        break;
                                    case 1:
                                        echo '<option value="0"> Pas encore fait</option>';
                                           echo '<option value="1" selected> Fait</option>';
                                        echo '<option Value="2"> Annulé </option>';
                                        break;
                                    case 2:
                                        echo '<option value="0"> Pas encore fait</option>';
                                           echo '<option value="1"> Fait</option>';
                                        echo '<option Value="2" selected> Annulé </option>';
                                        break;
                                    default:
                                        echo '<option value="0" selected> Pas encore fait</option>';
                                           echo '<option value="1"> Fait</option>';
                                        echo '<option Value="2"> Annulé </option>';
                                        break;
                                }
                            ?>
						</select></td>
					</tr>
					
				</tbody>
				<tfoot>
					<tr>
						<td colspan='2'><center><input type="submit" class="btn btn-lg" value="Enregistrer"></center></form></td>
					</tr>
				</tfoot>
			</table>
			<br>
				<table class="table table-bordered" width="100%">
				<thead>
					<tr >
						<th colspan="2"><h2><center>Liste des éléments impactées par la décommission</center></h2></th>
					</tr>
					<tr>
						<th><h2><center>SERVEURS</center></h2></th><th><h2><center>BASES DE DONNEES</center></h2></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td >
							<center><?php
                                if (isset($var_LIST_MACHINES_IMPACTEES)) {
                                    foreach ($var_LIST_MACHINES_IMPACTEES as $value) {
                                        echo '<h4><form id="'.$value.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$value."\"/></form><a href='#' onclick='document.getElementById(\"".$value."\").submit()'><b>".$value.'</b></a></h4>';
                                        // echo $value."</br>";?>
										<input type="hidden" name="LIST_MACHINES_IMPACTEES" value="<?php echo base64_encode(serialize($var_LIST_MACHINES_IMPACTEES)); ?>"/>
										<input type="hidden" name="LIST_MACHINES_IMPACTEES" value="<?php echo base64_encode(serialize($var_LIST_MACHINES_IMPACTEES)); ?>"/>
										<?php
                                    }
                                }
                            ?></center>
						</td>
						<td >
							<center><?php
                                if (isset($var_LIST_DB_IMPACTEES)) {
                                    foreach ($var_LIST_DB_IMPACTEES as $value) {
                                        echo '<h4><form id="'.$value.'" method="POST" action="application_by_db.php"><input type="hidden" name="db" value="'.$value."\"/></form><a href='#' onclick='document.getElementById(\"".$value."\").submit()'><b>".$value.'</b></a></h4>';
                                        // echo $value."</br>";?>
										<input type="hidden" name="LIST_DB_IMPACTEES" value="<?php echo base64_encode(serialize($var_LIST_DB_IMPACTEES)); ?>"/>
										<?php
                                    }
                                }
                            ?></center>
						</td>
					</tr>
					<tr>
						<td>
							<center>
							<form id="select_machine" name="select_machine" method="POST" enctype="multipart/form-data" action="selection_decom.php" >
									
									<input type="hidden" name="ticketPGMP" value="<?php echo $ticketPGMP; ?>"/>
									<input type="hidden" name="TAB" value="<?php echo base64_encode(serialize($var_LIST_MACHINES_IMPACTEES)); ?>"/>
									<input type="hidden" name="UPDATE" value="yes"/>
									<center><input type="submit" class="btn btn-submit" value="Sélectionner ou Désélectionner des machines"></center>
							</form>
							</center>
						</td>
						<td>
							<center>
							<form id="select_db" name="select_db" method="POST" enctype="multipart/form-data" action="selection_decom_db.php" >
									
									<input type="hidden" name="ticketPGMP" value="<?php echo $ticketPGMP; ?>"/>
									<input type="hidden" name="TAB" value="<?php echo base64_encode(serialize($var_LIST_DB_IMPACTEES)); ?>"/>
									<input type="hidden" name="UPDATE" value="yes"/>
									<center><input type="submit" class="btn btn-submit" value="Sélectionner ou Désélectionner des bases de données"></center>
							</form>
							</center>
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
							<form id="comment" name="comment" method="POST" enctype="multipart/form-data" action="set_decommission.php">
							<td colspan="2">
								<input type='hidden' name='ticketPGMP' value='<?php echo $ticketPGMP; ?>'/>
								<input type='hidden' name='consult' value='1'/>
								<input type="hidden" name="TAB" value="<?php echo base64_encode(serialize($var_LIST_DB_IMPACTEES)); ?>"/>
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
                            $querycomment = "select suivie_decommission_comment_id,date_comment,content_comment from cmdb.suivie_decommission_comment where id_suivie_decommission='$id' order by date_comment desc";

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
                                            case 'suivie_decommission_comment_id':
                                                $line_id = $valeur;
                                                break;
                                        }
                                    }
                                    echo "<form id='DEL_$line_id' name='DEL_$line_id' method='POST' enctype='multipart/form-data' action='set_decommission.php'><input type='hidden' name='delete_comment_for_id' value='".$line_id."'/><input type='hidden' name='DECOM_ID' value='".$id."'/><input type='hidden' name='ticketPGMP' value='".$ticketPGMP."'/><input type='hidden' name='consult' value='1'/><input type='submit' class='btn btn-danger' value='supprimer la ligne' onClick=\"ConfirmMessage('DEL_$line_id')\"/></form> </td>";
                                    echo '</tr>';
                                }
                                $stmt->pdo = null;
                            }
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

