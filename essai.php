<html>
	<head>
		<title>Fiche Machine</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
	</head>
	<body>
<?php
    require 'variable-db.php';

        // debug de variables
    // echo "<p class=\"debug\">";
    // error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

    // $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
    // $var_ignore=array("GLOBALS", "_ENV", "_SERVER"); // Détermine les var à ne pas afficher
    // echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
    // $nom_fonction=__FUNCTION__;
    // if (isset($nom_fonction)&&$nom_fonction!="") {
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
    $machine = isset($_POST['machine']) ? $_POST['machine'] : null;

    if ($machine != null) {
        $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());

        $querymachine = "select * from system_inventory where upper(configurationname_wo_extension) = upper('".str_replace(' ', '', $machine)."');";
        //echo "$querymachine";
        if ($stmt = $con->prepare($querymachine)) {
            $stmt->execute();
            $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($tuples)) {
                $data = array_values($tuples[0]);
                // echo "<pre>";
                // print_r ($data);
                // echo "</pre>";
                $hostname = $data[4];
                $status = $data[9];
                $environment = $data[11];
                $osname = $data[20];
                $osversion = $data[21];
                $kernelversion = $data[22];
                $osbuild = $data[23];
                $businessservices = $data[29];
                $businessapplication = $data[30];
                $functionalgroup = $data[31];
                $criticity = $data[45];
                $buildref = $data[46];
                $unbuildref = $data[47];
                $installdate = $data[48];
                $uninstalldate = $data[49];
            } ?>
<table class="table table-bordered" >
		<thead>
			<tr>
				<th colspan='6'><center>FICHE MACHINE</center></th>
			</tr>
			<tr>
				<th colspan='6'><center><?php echo $hostname; ?></center></th>
			</tr>
			<tr>
				<th colspan='1'>Status</th><td colspan='2'><?php echo $status; ?></td><th colspan='1'>Criticité</th><td colspan='2'><?php echo $criticity; ?></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th colspan='1'>Operating Environment</th><td colspan='5'><?php echo $environment; ?></td>
			</tr>
			<tr>
				<th colspan='1'>Operating System</th><td colspan='5'><?php echo $osname; ?></td>
			</tr>
			<tr>
				<th>OS Version</th><td><?php echo $osversion; ?></td><th>Kernel Version</th><td><?php echo $kernelversion; ?></td><th>OS Build</th><td><?php echo $osbuild; ?></td>
			</tr>
			<tr>
				<th colspan='1'>Business Services</th><td colspan='5'><?php echo $businessservices; ?></td>
			</tr>
			<tr>
				<th colspan='1'>Business Application</th><td colspan='5'><?php echo $businessapplication; ?></td>
			</tr>
			<tr>
				<th colspan='1'>Functional Group</th><td colspan='5'><?php echo $functionalgroup; ?></td>
			</tr>
			<tr>
				<th colspan='1'>Date d'installation</th><td colspan='2'><?php echo $installdate; ?></td><th colspan='1'>Date de désinstallation</th><td colspan='2'><?php echo $uninstalldate; ?></td>
			</tr>
		</tbody>
		</table><br/>
<?php

            // echo "<table class=\"table table-hover\">
            // <thead><tr><th>FICHE DE LA MACHINE</th></tr></thead>
            // <tbody>
            // <tr><th>".$machine."</th></tr>
            // <tr><th>Operating Envrionment</th><th></th></tr>
            // </tbody></table>";
            // echo "</table>";
            // echo"</div>";
            // if(count($tuples))
            // {
                // $columns_names = array_keys($tuples[0]);

                // echo "<table class=\"table table-hover\"><thead><tr>";
                // foreach($columns_names as $col)
                // {
                    // echo '<th>'. $col .'</th>';
                // }
                // echo '</tr></thead><tbody>';
                // foreach($tuples as $tuple)
                // {
                    // echo '<tr>';
                    // foreach($tuple as $col)
                    // {
                        // echo '<td>'. $col .'</td>';
                    // }
                    // echo '</tr>';
                // }
                // echo '</tbody></table>';
            // }
            // else
            // {
                // echo 'Pas de résultat';
            // }
            $stmt->pdo = null;

            /////////////////////////////////////
            // $querymachine="call cmdb.SEARCH_SERVER_EAM('".str_replace(" ","",$machine)."');";
            // if ($stmt = $con->prepare($querymachine))
            // {
                // $stmt->execute();
                // $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // echo "<div id=\"contenu\" >";

                // if(count($tuples))
                // {
                    // $columns_names = array_keys($tuples[0]);

                    // echo "<table class=\"table table-hover\"><thead><tr>";
                    // foreach($columns_names as $col)
                    // {
                        // echo '<th>'. $col .'</th>';
                    // }
                    // echo '</tr></thead><tbody>';
                    // foreach($tuples as $tuple)
                    // {
                        // echo '<tr>';
                        // foreach($tuple as $col)
                        // {
                            // echo '<td>'. $col .'</td>';
                        // }
                        // echo '</tr>';
                    // }
                    // echo '</tbody></table>';
            // }
            // else
            // {
                // echo 'Pas de résultat';
            // }
            // $stmt->pdo = null;
        }
    } else {
        echo "erreur lors de l'a transmission de la variable !!!";
    }
?>

	</body>
</html>