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
    $db_instance = isset($_POST['db']) ? $_POST['db'] : null;
?>
<html>
<head>
<title>Recherche serveurs par application</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
</head>
<body>
<form class="form-horizontal" method="POST" enctype="multipart/form-data" action="db_by_server.php">
<input list="list_db" name="db" size="30" maxlength="30" value="<?php if ($table != '') {
    echo $table;
} ?>" class="input-lg">
<datalist id="list_db">
<?php
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
    or die('Could not connect to the database server'.pdo_connect_error());
    $querytable = "select distinct instance from db_inventory where instance <>'' order by instance;";
        if ($stmt = $con->prepare($querytable)) {
            $stmt->execute();
            while ($resulttable = $stmt->fetch()) {
                if ($db_instance == $resulttable[0]) {
                    echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
                } else {
                    echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
                }

                $stmt->pdo = null;
            }
        }

?>
</datalist>
<input type="submit" class="btn btn-lg">
</form>
<?php
if ($db_instance != '') {
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
    or die('Could not connect to the database server'.pdo_connect_error());
    $querytable = "call cmdb.DB_BY_SERVER('".$db_instance."');";
    echo '<center><form method="POST" action="export_to_csv.php" TARGET="_blank">';
    echo "<input type=\"hidden\" name=\"requete\" value=\"$querytable\"> ";
    echo '<input type="submit" value="Export en CSV" name="export" class="btn btn-success">';
    echo '</form>';
    echo '</center>';
    if ($stmt = $con->prepare($querytable)) 
    {
        $stmt->execute();
        $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($tuples)) 
        {
            $columns_names = array_keys($tuples[0]); ?>
            <table id="" class="display" style="width: 100%;">
                <thead>
                    <tr>
                        <?php
                                foreach ($columns_names as $col) 
                                {
                                    echo '<th>'.$col.'</th>';
                                } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($tuples as $tuple) 
                        {
                            echo '<tr>';
                            foreach ($tuple as $entete => $col) 
                            {
                                switch (strtoupper($entete)) 
                                {
                                    case 'CONFIGURATIONNAME_WO_EXTENSION':
                                        echo '<td><form id="'.$col.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$col."\"/> </form><a href='#' onclick='document.getElementById(\"".$col."\").submit()' target=\"_blank\"><b>".$col.'</b></a></td>';
                                        break;
                                    case 'HOSTNAME':
                                        echo '<td><form id="'.$col.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$col."\"/> </form><a href='#' onclick='document.getElementById(\"".$col."\").submit()' target=\"_blank\"><b>".$col.'</b></a></td>';
                                        break;
                                    default:
                                        echo '<td>'.$col.'</td>';
                                        break;
                                }
                            }
                            echo '</tr>';
                        }
        // if ($stmt = $con->prepare($querytable)) {
        //     echo '<table class="table table-hover" >';
        //     echo '<thead>';
        //     echo '<tr>';
        //     echo "<th style='width: auto'>Hostname</th><th style='width: auto'>Operating Environment</th><th style='width: auto'>OS NAME</th><th style='width: auto'>OS VERSION</th><th style='width: auto'>BUSINESS SERVICES</th>";
        //     echo '</tr>';
        //     echo '</thead>';
        //     $stmt->execute();
        //     echo '<tbody class="tbdoy">';
        //     while ($resulttable = $stmt->fetch()) {
        //         echo '<tr>';
        //         if (preg_match('#[,]#', $resulttable[0])) {
        //             $array = explode(',', $resulttable[0]);
        //             echo '<th>';
        //             foreach ($array as $machine) {
        //                 echo '<form id="'.$machine.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$machine."\"/> </form><a href='#' onclick='document.getElementById(\"".$machine."\").submit()' target=\"_blank\">".$machine.'</a>';
        //             }

        //             echo '</th>';
        //         } else {
        //             echo "<th style='column-width: 250px;'><form id=\"".$resulttable[0].'" method="POST" action="fiche_machine.php"> <input type="hidden" name="machine" value="'.$resulttable[0]."\"/> </form><a href='#' onclick='document.getElementById(\"".$resulttable[0]."\").submit()' target=\"_blank\">".$resulttable[0].'</a></th>';
        //         }
        //         echo '<th>'.$resulttable[1].'</th>';
        //         echo '<th>'.$resulttable[2].'</th>';
        //         echo '<th>'.$resulttable[3].'</th>';
        //         echo '<th>'.$resulttable[4].'</th>';
        //     }
        //     echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }
        $stmt->pdo = null;
    }
}
?>

</body>
</html>
