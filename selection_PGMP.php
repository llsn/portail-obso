<?php
    require 'variable-db.php';

    $var_RFS_NUMBER_CMA = isset($_POST['RFS_NUMBER_CMA']) ? $_POST['RFS_NUMBER_CMA'] : null;
    $var_UPDATE = isset($_POST['UPDATE']) ? $_POST['UPDATE'] : null;
    $tableau = isset($_POST['TAB']) ? unserialize(base64_decode($_POST['TAB'])) : null;
    $list_pgmp_add = isset($_POST['list_pgmp_add']) ? $_POST['list_pgmp_add'] : null;
    $list_pgmp_del = isset($_POST['list_pgmp_del']) ? $_POST['list_pgmp_del'] : null;
    $submit = isset($_POST['SUBMIT']) ? $_POST['SUBMIT'] : null;

    /* Initialisation de la connexion à  la base de données */

    $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());

    /* Si le tableau "tableau" et la variable "submit" sont initialisés alors mettre à jour la ligne du projet */

    if (isset($tableau) != null && isset($submit) != null) {
        $queryset = "CALL `cmdb`.`update_pgmp_in_suivie_projets`('$var_RFS_NUMBER_CMA', '".implode(', ', $tableau)."');";
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
        $queryset = "select pgmp_tickets from cmdb.suivie_projets where RFS_NUMBER_CMA='$var_RFS_NUMBER_CMA';";
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

    if (isset($list_pgmp_add) != null) {
        $tableau[] = $list_pgmp_add;
    }
    if (isset($list_pgmp_del) != null) {
        unset($tableau[array_search($list_pgmp_del, $tableau)]);
    }

    /////////////////////////////////////////////////////////
            // debug de variables
/*    echo '<p class="debug">';
    error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

    $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
    $var_ignore = array('GLOBALS', '_ENV', '_SERVER', '_GET', 'host', 'dbname', 'user', 'password', 'port', 'socket'); // Détermine les var à ne pas afficher
    echo '<strong>Etat des variables a la ligne : '.__LINE__.' dans le fichier : '.__FILE__."</strong><br />\n";
    $nom_fonction = __FUNCTION__;
    if (isset($nom_fonction) && $nom_fonction != '') {
        echo '<strong>Dans la fonction : '.$nom_fonction."</strong><br />\n";
    }
    foreach ($variables as $key => $value) {
        if (!in_array($key, $var_ignore) && strpos($key, 'HTTP') === false) {
            echo '<pre class="debug">';
            echo '$'.$key.' => ';
            print_r($value);
            echo "</pre>\n";
        }
    }

    echo '</p>';*/

    /////////////////////////////////////////////////////////

?>
<html>
	<head>
		<title>Sélection les PGMP liés au projet</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
	</head>
<body>

<?php
    $liste_exclude = '';
    if (isset($tableau)) {
        $liste = " where pgmp_tickets not in ('".implode(', ', $tableau)."')";
        $liste_exclude = str_replace(', ', "', '", $liste);
       // echo 'LISTE_EXCLUDE: '.$liste_exclude;
    }
?>

<table class="table table-hover" width="100%">
<thead>
<tr><th colspan="2"><h3><center>Sélection les PGMP liés au projet</center></h3></th></tr>
<tr><th colspan="2"><h2><center>Numéro RFS CMA : <?php echo '<form id="'.$var_RFS_NUMBER_CMA.'" method="POST" action="set_projet.php"><input type="hidden" name="RFS_NUMBER_CMA" value="'.$var_RFS_NUMBER_CMA."\"/><input type=\"hidden\" name=\"consult\" value=\"1\"/> </form><a href='#' onclick='document.getElementById(\"".$var_RFS_NUMBER_CMA."\").submit()'><b>".$var_RFS_NUMBER_CMA.'</b></a>'; ?></center></H2></td></tr>;

<tr>
<td>
<form id="select_pgmp_add" name="select_pgmp_add" method="POST" enctype="multipart/form-data" action="selection_PGMP.php">
<h3>Liste des PGMP</h3></br>
<input list="list_pgmp" name="list_pgmp_add" size="30" maxlength="30" class="input-lg" onchange='document.getElementById("select_pgmp_add").submit()'></acronym>
	<datalist id="list_pgmp" >
	<!-- <select multiple size="20" id="list_server_add" name="list_server_add" onchange='document.getElementById("select_add").submit()' > -->
	<?php

        if ($liste_exclude != " where pgmp_tickets not in ('')") {
            $querylist = 'select distinct pgmp_ticket from cmdb.suivie_decommission'.$liste_exclude.' order by pgmp_ticket;';
            echo "liste_exclude n'est pas NULL --> ".$querylist;
        } else {
            $querylist = 'select distinct pgmp_ticket from cmdb.suivie_decommission order by pgmp_ticket;';
            echo 'liste_exclude est NULL --> '.$querylist;
        }
        echo 'SQL REQUEST --> '.$querylist;
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
<input type="hidden" name="RFS_NUMBER_CMA" value="<?php echo $var_RFS_NUMBER_CMA; ?>" />
</form>

</td>
<?php
    if (isset($tableau) != null) {
        if (count($tableau) > 1) {
            echo '<td>';
            echo '<form id="select_pgmp_del" name="select_pgmp_del" method="POST" enctype="multipart/form-data" action="selection_PGMP.php" >';
            echo "<select multiple size=\"20\" id=\"list_pgmp_del\" name=\"list_pgmp_del\" class=\"input-lg\" onchange='document.getElementById(\"select_pgmp_del\").submit()' value=\"<? echo $list_pgmp_del; ?>\">";
            if (isset($tableau[0]) == '') {
                if (count($tableau) == 1) {
                    unset($tableau[0]);
                }
            }
            foreach ($tableau as $valeur) {
                if ($valeur != $list_pgmp_del && $valeur != '') {
                    echo '<option value="'.$valeur.'" >'.$valeur.'</option>';
                }
            }
            echo '</select>';
            echo '<input type="hidden" name="TAB" value="'.base64_encode(serialize($tableau)).'" />';
            echo '<input type="hidden" name="RFS_NUMBER_CMA" value="'.$var_RFS_NUMBER_CMA.'" />';
            echo'</form>';
            echo '</td>';
        }
    }
?>
</tr>
<tfoot>
	<tr>
		<td colspan="2">
			<form id="submit_sel" name="submit_sel" method="POST" enctype="multipart/form-data" action="selection_PGMP.php" >
				<input type="hidden" name="TAB" value="<?php echo base64_encode(serialize($tableau)); ?>"/>
				<input type="hidden" name="RFS_NUMBER_CMA" value="<?php echo $var_RFS_NUMBER_CMA; ?>"/>
				<input type="hidden" name="SUBMIT" value="submit"/>
				<center><input type="submit" class="btn btn-submit"></center>
			</form>
		</td>
	</tr>
</tfoot>
</table>
</body>
</html>