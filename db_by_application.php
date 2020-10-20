<?php
    require 'variable-db.php';
    require 'functions.php';

    // debug de variables
// 	echo "<p class=\"debug\">";
// 	error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

// 	$variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
// 	$var_ignore=array("GLOBALS", "_ENV", "_SERVER"); // Détermine les var à ne pas afficher
// 	echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
// 	$nom_fonction=__FUNCTION__;
// 	if (isset($nom_fonction)&&$nom_fonction!="")
// 	{
// 		echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
// 	}
// 	foreach ($variables as $key=>$value)
// 	{
// 		if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
// 	  	{
// 			echo "<pre class=\"debug\">";
// 			echo ("$".$key." => ");
// 			print_r($value);
// 			echo "</pre>\n";
// 	  	}
// 	}
    $table = isset($_POST['application']) ? $_POST['application'] : null;

?>
<html>
<head>
<title>Recherche serveurs par application</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" />
</head>
<body>
	    <div>
			<h1 class="jumbotron"><center>Trouver les bases de donnée utilisées par une application</center></h1>
		</div>
<form id="application" class="form-horizontal" method="POST" enctype="multipart/form-data" action="db_by_application.php">
<acronym title="Saisissez ou sélectionnez le texte désiré et tapez sur 'ENTREE' ou cliquez sur le bouton 'Valider'"><input list="list_app" name="application" size="30" maxlength="30" value="<?php if ($table != '') {
    echo $table;
} ?>" class="input-lg" onchange='document.getElementById("application").submit()' onclick="if(this.value!='')this.value=''"></acronym>
<datalist id="list_app" >
<?php
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
    or die('Could not connect to the database server'.pdo_connect_error());
    $querytable = 'select application from cmdb.application order by application;';
    if ($stmt = $con->prepare($querytable)) {
        $stmt->execute();
        while ($resulttable = $stmt->fetch()) {
            if ($table == $resulttable[0]) {
                echo '<option value="'.$resulttable[0].'" selected onclick=\'document.getElementById(application).submit()\'>'.$resulttable[0].'</option>';
            } else {
                echo '<option value="'.$resulttable[0].'" onclick=\'document.getElementById(application).submit()\'>'.$resulttable[0].'</option>';
            }

            $stmt->pdo = null;
        }
    }

?>
</datalist>
<input type="submit" class="btn btn-lg">
</form>
<?php
if ($table != '') {
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
    or die('Could not connect to the database server'.pdo_connect_error());
    $querytable = "call cmdb.DB_BY_APPLI('".$table."');";
    echo '<center><form method="POST" action="export_to_csv.php" TARGET="_blank">';
    echo "<input type=\"hidden\" name=\"requete\" value=\"$querytable\"> ";
    echo '<input type="submit" value="Export en CSV" name="export" class="btn btn-success">';
    echo '</form>';
    echo '</center>';
    if ($stmt = $con->prepare($querytable)) {
        echo '<table class="table table-hover" >';
        echo '<thead>';
        echo '<tr>';
        echo "<th style='width: auto'>DB NAME</th><th>DB Instance Name</th><th style='width: auto'>Operating Environment</th><th style='width: auto'>DB Middleware Edition</th><th style='width: auto'>DB Middleware Version</th><th style='width: auto'>BUSINESS SERVICES</th>";
        echo '</tr>';
        echo '</thead>';
        $stmt->execute();
        echo '<tbody>';
        while ($resulttable = $stmt->fetch()) {
            echo "<tr bgcolor='".status_obso_dbversion($resulttable[4], $host, $dbname, $user, $password)."'>";
            if (preg_match('#[,]#', $resulttable[0])) {
                $array = explode(',', $resulttable[0]);
                echo '<th>';
                foreach ($array as $database) {
                    echo '<form id="'.$resulttable[1].'" method="POST" action="application_by_db.php"><input type="hidden" name="db" value="'.$resulttable[1].'"/> <input type="hidden" name="application" value="'.$table."\"/> </form><a href='#' onclick='document.getElementById(\"".$resulttable[1]."\").submit()' target=\"_blank\">".$database.'</a>';
                }

                echo '</th>';
            } else {
                echo "<th style='column-width: 250px;'>";
                echo '<form id="'.$resulttable[1].'" method="POST" action="application_by_db.php"><input type="hidden" name="db" value="'.$resulttable[1].'"/><input type="hidden" name="application" value="'.$table."\"/> </form><a href='#' onclick='document.getElementById(\"".$resulttable[1]."\").submit()' target=\"_blank\">".$resulttable[0].'</a>';
                echo '</th>';
            }
            echo '<th>'.$resulttable[1].'</th>';
            echo '<th>'.$resulttable[2].'</th>';
            echo '<th>'.$resulttable[3].'</th>';
            echo '<th>'.$resulttable[4].'</th>';
            echo '<th>'.$resulttable[5].'</th>';
        }
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
    }
    $stmt->pdo = null;
}
?>

</body>
</html>
