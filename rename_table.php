<?php

    require 'variable-db.php';

    // debug de variables
    echo '<p class="debug">';
    error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

    $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
    $var_ignore = array('GLOBALS', '_ENV', '_SERVER'); // Détermine les var à ne pas afficher
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
    echo '</p>';

    // On récupère les champs
    if (isset($_POST['name_table'])) {
        $name_table = $_POST['name_table'];
    } else {
        $name_table = '';
    }

    if (isset($_POST['table'])) {
        $table = $_POST['table'];
    } else {
        $table = '';
    }
    $statut = '';
    if (isset($_POST['table']) and isset($_POST['name_table'])) {
        try {
            $alter_table = 'RENAME TABLE '.$_POST['table'].' TO '.$_POST['name_table'].';';

            $conn = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
                or die('Could not connect to the database server'.pdo_connect_error());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->beginTransaction();
            $conn->exec($alter_table);
            $conn->commit();
            $statut = 'OK';
        } catch (PDOException $e) {
            // roll back the transaction if something failed
            $conn->rollback();
            echo 'Error: '.$e->getMessage();
        }
        $conn = null;
    }

?>
<HTML>
<SCRIPT LANGUAGE="JavaScript">
	function confirmation() {
		var msg = "Êtes-vous sur de vouloir renommer cette table ?";
		if (confirm(msg))
			location.replace(rename_table.php);
	}
</SCRIPT>

<body class="blueTable">
	<center>
		<form method="POST" action="rename_table.php">
			Table <select name="table" size="1" maxlength="15" value="<?php if ($table != '') {
    echo $table;
} ?>"><br>
				<option value="">choisissez une table</option>

				<?php

    $querytable = "select `table_name` as `nom_table`,`table_comment` as `nom_origine` from `information_schema`.`tables` where `table_name` regexp('system_inventory|db_inventory|middleware_instance|middleware_inventory') and `table_name` not regexp ('count_all') order by `nom_table`;";

    $conn = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
                or die('Could not connect to the database server'.pdo_connect_error());
    if ($stmt = $conn->prepare($querytable)) {
        $stmt->execute();
        while ($resulttable = $stmt->fetch()) {
            if ($table == $resulttable[0]) {
                echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'--> Nom original:'.$resulttable[1].'</option>';
            } else {
                echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'--> Nom original:'.$resulttable[1].'</option>';
            }

            $stmt->pdo = null;
        }
    }
?>
			</select>
			renommé en
			<input type="text" name="name_table" placeholder="saisir le nouveau nom de la table selectionnée" size="50">
			<input type="submit" value="Renommer la table" onClick="confirmation();">
			<form>
</body>
<?php
    if ($statut == 'OK') {
        $_POST = array();
        echo "<p><center>La table <u><b>$table</b></u> a bien été renommé en <u><b>$name_table</b></u>.</center></p>";
        echo '<p><center><a href="rename_table.php" ><button>rafraichir la liste</button></a></center></p>';
    }

?>

</HTML>