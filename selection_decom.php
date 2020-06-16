<?php
    require 'variable-db.php';

    $var_PGMP_TICKET = isset($_POST['ticketPGMP']) ? $_POST['ticketPGMP'] : null;
    $var_UPDATE = isset($_POST['UPDATE']) ? $_POST['UPDATE'] : null;
    $tableau = isset($_POST['TAB']) ? unserialize(base64_decode($_POST['TAB'])) : null;
    $list_server_add = isset($_POST['list_server_add']) ? $_POST['list_server_add'] : null;
    $list_server_del = isset($_POST['list_server_del']) ? $_POST['list_server_del'] : null;
    $submit = isset($_POST['SUBMIT']) ? $_POST['SUBMIT'] : null;

    /* Initialisation de la connexion à  la base de données */

    $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());

    /* Si le tableau "tableau" et la variable "submit" sont initialisés alors mettre à jour la ligne du projet */

    if (isset($tableau) != null && isset($submit) != null) {
        $update = str_replace(' ', '', preg_replace('[^,]', '', implode(', ', $tableau)));

        $queryset = "CALL `cmdb`.`update_machine_in_suivie_decom`('$var_PGMP_TICKET', '".$update."');";
        if ($stmt = $con->prepare($queryset)) {
            $result = $stmt->execute();
            //$stmt->pdo = null;
            if ($result == true) {
                echo '<center><h4>La mise à jour à réussi</center></h4>';
            } else {
                echo '<center><h4>La mise à jour à échoué</center></h4>';
            }
        }
    }
    if ($var_UPDATE == 'yes') {
        $queryset = "select LIST_MACHINES_IMPACTEES from cmdb.suivie_decommision where pgmp_ticket='$var_PGMP_TICKET';";
        // echo $queryset;
        if ($stmt = $con->prepare($queryset)) {
            // echo "LANCEMENT LA REQUETE DE RECUPERATION";
            $stmt->execute();
            $queryresult = $stmt->fetchAll();
            foreach ($queryresult as $field => $value) {
                $tableau = explode(',', $value[0]);
            }
            // $stmt->pdo = null;
        }
    }

    if (isset($list_server_add) != null) {
        $tableau[] = $list_server_add;
    }
    if (isset($list_server_del) != null) {
        unset($tableau[array_search($list_server_del, $tableau)]);
    }

    /////////////////////////////////////////////////////////
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

    /////////////////////////////////////////////////////////

?>
<html>

<head>
	<title>Sélection des machines impactées par la décommission</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="css/bootstrap.min.css" />
</head>

<body>

	<?php
    $liste_exclude = '';
    if (isset($tableau)) {
        $liste = " where configurationname_wo_extension not in ('".implode(', ', $tableau)."')";
        $liste_exclude = str_replace(', ', "', '", $liste);
        // echo "LISTE_EXCLUDE: ".$liste_exclude;
    }
?>

	<table class="table table-hover" width="100%">
		<thead>
			<tr>
				<th colspan="2">
					<h3>
						<center>Sélection des machines impactées par la décommission</center>
					</h3>
				</th>
			</tr>
			<tr>
				<th colspan="2">
					<h2>
						<center>Numéro Ticket PGMP:
							<?php echo '<form id="'.$var_PGMP_TICKET.'" method="POST" action="set_decommission.php"><input type="hidden" name="ticketPGMP" value="'.$var_PGMP_TICKET."\"/><input type=\"hidden\" name=\"consult\" value=\"1\"/> </form><a href='#' onclick='document.getElementById(\"".$var_PGMP_TICKET."\").submit()'><b>".$var_PGMP_TICKET.'</b></a>'; ?>
						</center>
					</H2>
					</td>
			</tr>;

			<tr>
				<td>
					<form id="select_add" name="select_add" method="POST" enctype="multipart/form-data"
						action="selection_decom.php">
						<h3>Liste des machines</h3></br>
						<input list="list_machine" name="list_server_add" size="30" maxlength="30" class="input-lg"
							onchange='document.getElementById("select_add").submit()'></acronym>
						<datalist id="list_machine">
							<!-- <select multiple size="20" id="list_server_add" name="list_server_add" onchange='document.getElementById("select_add").submit()' > -->
							<?php

    if ($liste_exclude != " where configurationname_wo_extension not in ('')") {
        $querylist = 'select distinct configurationname_wo_extension from cmdb.system_inventory'.$liste_exclude.' order by configurationname_wo_extension;';
    } else {
        $querylist = 'select distinct configurationname_wo_extension from cmdb.system_inventory order by configurationname_wo_extension;';
    }
    if ($stmt = $con->prepare($querylist)) {
        $stmt->execute();
        $resulttable = $stmt->fetchAll();
        foreach ($resulttable as $val) {
            if (isset($val)) {
                echo '<option value="'.$val[0].'" >'.$val[0].'</option>';
            }
        }
    }

?>
							<!-- </select> -->
						</datalist>
						<input type="hidden" name="TAB" value="<?php echo base64_encode(serialize($tableau)); ?>" />
						<input type="hidden" name="ticketPGMP" value="<?php echo $var_PGMP_TICKET; ?>" />
					</form>

				</td>
				<?php
    // if(count($tableau)>0)
    // {
        echo '<td>';
        echo '<form id="select_del" name="select_del" method="POST" enctype="multipart/form-data" action="selection_decom.php" >';
        echo "<select multiple size=\"20\" id=\"list_server_del\" name=\"list_server_del\" class=\"input-lg\" onchange='document.getElementById(\"select_del\").submit()' value=\"<? echo $list_server_del; ?>\">";
				if (isset($tableau[0]) == '') {
				unset($tableau[0]);
				}
				if (isset($tableau) != null) {
				foreach ($tableau as $valeur) {
				if ($valeur != $list_server_del && $valeur != '') {
				echo '<option value="'.$valeur.'">'.$valeur.'</option>';
				}
				}
				}
				echo '</select>';
				echo '<input type="hidden" name="TAB" value="'.base64_encode(serialize($tableau)).'" />';
				echo '<input type="hidden" name="ticketPGMP" value="'.$var_PGMP_TICKET.'" />';
				echo'</form>';
				echo '</td>';
				// }
				?>
			</tr>
		<tfoot>
			<tr>
				<td colspan="2">
					<form id="submit_sel" name="submit_sel" method="POST" enctype="multipart/form-data"
						action="selection_decom.php">
						<input type="hidden" name="TAB" value="<?php echo base64_encode(serialize($tableau)); ?>" />
						<input type="hidden" name="ticketPGMP" value="<?php echo $var_PGMP_TICKET; ?>" />
						<input type="hidden" name="SUBMIT" value="submit" />
						<center><input type="submit" class="btn btn-submit"></center>
					</form>
				</td>
			</tr>
		</tfoot>
	</table>
</body>

</html>