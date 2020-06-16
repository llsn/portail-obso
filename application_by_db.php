<?php
    require 'variable-db.php';
    $db = isset($_POST['db']) ? $_POST['db'] : null;
    $application = isset($_POST['application']) ? $_POST['application'] : null;

?>

<html>
	<head>
		<title>Applications liés à une base</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" />
	</head>
	<body>
<div>
<h1 class="jumbotron"><center>Trouver les bases données d'une application</center></h1>
</div>
<div>
<form id="db" class="form-horizontal" method="POST" enctype="multipart/form-data" action="application_by_db.php">
		<acronym
			title="Saisissez ou sélectionnez le texte désiré et tapez sur 'ENTREE' ou cliquez sur le bouton 'Valider'">
			<input list="list_db" name="db" size="30" maxlength="30"
				value="<?php if ($db != '') {
    echo $db;
} ?>" class="input-lg"
				onchange='document.getElementById("db").submit()'></acronym>
		<datalist id="list_db">
			<?php
        $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());
        $querytable = "select distinct instance from db_inventory;";
        if ($stmt = $con->prepare($querytable)) {
            $stmt->execute();
            while ($resulttable = $stmt->fetch()) {
                if ($db == $resulttable[0]) {
                    echo '<option value="'.$resulttable[0].'" selected onclick=\'document.getElementById(db).submit()\'>'.$resulttable[0].'</option>';
                } else {
                    echo '<option value="'.$resulttable[0].'" onclick=\'document.getElementById(db).submit()\'>'.$resulttable[0].'</option>';
                }

                $stmt->pdo = null;
            }
        }

    ?>
		</datalist>
		<input type="submit" class="btn">
	</form>
</div>
<?php

    if ($db != null) {
        $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());

        $querydb = "SELECT `O11: SoftwareDeployment Name:Env`,`﻿O2: Application name`,`O5: Component name`,`ICD Logical CI`,`O9: ICD Physical CI` FROM eam.x7_icdapplication where upper(`O9: ICD Physical CI`) like upper('%$db%');";
        if ($stmt = $con->prepare($querydb)) {
            $stmt->execute();
            $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($tuples)) {
                /*echo "<pre>";
                print_r($tuples);
                echo "</pre>";*/
                echo '<table class="table table-bordered">';
                echo '<tbody>';
                echo '<tr>';
                echo "<th colspan='10'><titre 1><center>APPLICATIONS liées à la base de données <b><u>$db</u></b> tiré de l'EAM</center></titre 1></th>";
                echo '</tr>';
                echo '<tr>';
                echo '<th>O11: SoftwareDeployment Name:Env</th><th>﻿O2: Application name</th><th>O5: Component name</th><th>ICD Logical CI</th><th>O9: ICD Physical CI</th>';
                echo '</tr>';
                foreach ($tuples as $ligne) {
                    echo '<tr>';

                    foreach ($ligne as $entete => $valeur) {
                        switch ($entete) {
                            case 'O11: SoftwareDeployment Name:Env':
                                $environment = $valeur;
                                echo '<td>'.$environment.'</td>';
                                break;

                            case '﻿O2: Application name':
                                echo '<td><b><form id="'.$valeur.'_'.$environment.'" method="POST" action="application_details.php"><input type="hidden" name="app" value="'.$valeur.'"/><input type="hidden" name="environment" value="'.$environment."\"/></form><a href='#' onclick='document.getElementById(\"".$valeur.'_'.$environment."\").submit()' target=\"_blank\">".$valeur.'</a></b></td>';
                                break;
                            case 'ICD Logical CI':
                                echo '<td><b><form id="'.$valeur.'_'.$environment.'" method="POST" action="components_detail.php"><input type="hidden" name="var_consult_component" value="true"/><input type="hidden" name="var_logical_CI" value="'.$valeur."\"/></form><a href='#' onclick='document.getElementById(\"".$valeur.'_'.$environment."\").submit()' target=\"_blank\">".$valeur.'</a></b></td>';
                                break;
                            case 'O9: ICD Physical CI':
                                list($serveur, $database) = explode(':', $valeur);
                                echo '<td><form id="'.$serveur.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$serveur."\"/></form><a href='#' onclick='document.getElementById(\"".$serveur."\").submit()'><b>".$serveur."</b></a>:$database</td>";
                                break;
                            
    
                            default:
                                echo '<td>'.$valeur.'</td>';
                                break;
                        }
                    }
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<table class="table table-bordered">';
                echo '<tbody>';
                echo '<tr>';
                echo "<th colspan='6'><center>PAS d'APPLICATION(S) liée(s) à ".$db.'</center></th>';
                echo '</tbody>';
                echo '</table>';
            }
            $stmt->pdo = null;
        }
    } else {
        echo "erreur lors de l'a transmission de la variable !!!";
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
    //     echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
    // }
    // foreach ($variables as $key=>$value)
    // {
    //     if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
    //     {
    //         echo "<pre class=\"debug\">";
    //         echo ("$".$key." => ");
    //         print_r($value);
    //         echo "</pre>\n";
    //     }
    // }
    // echo "</p>"; 

?>
</body>
</html>