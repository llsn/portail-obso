<?php

require 'variable-db.php';

// On récupère les champs
if (isset($_POST['NameServer'])) {
    $NameServer = $_POST['NameServer'];
} else {
    $NameServer = '';
}

if (isset($_POST['operatingenvironment'])) {
    $operatingenvironment = $_POST['operatingenvironment'];
} else {
    $operatingenvironment = '';
}

if (isset($_POST['osname'])) {
    $osname = $_POST['osname'];
} else {
    $osname = '';
}

if (isset($_POST['chk_archived'])) {
    $archived = " AND upper(status) <> upper('ARCHIVED')";
} else {
    $archived = '';
}

if (isset($_POST['chk_osversion_null'])) {
    $osversion_null = " AND upper(osversion) = ''";
} else {
    $osversion_null = '';
}

$table = isset($_POST['table']) ? $_POST['table'] : null;

$con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
    or die('Could not connect to the database server'.pdo_connect_error());
?>
<html>

<head>
    <title>Interrogation de la CMDB - table <?php echo $table; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="vendor/datatables/datatables/media/css/jquery.dataTables.css" />
	<link rel="stylesheet" type="text/css" href="vendor/datatables/datatables/media/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="Datatables/AutoFill-2.3.4/css/autoFill.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="Datatables/Responsive-2.2.3/css/dataTables.responsive.min.css" />
    <!-- <link rel="stylesheet" type="text/css" href="Datatables/FixedHeader-3.1.6/css/dataTables.fixedHeader.min.css" />
    <link rel="stylesheet" type="text/css" href="Datatables/FixedColumns-3.3.0/css/dataTables.fixedColumns.min.css" /> -->

	<!-- Chargement des scripts javascripts pour la mise en forme des tableaux-->
    <script type="text/javascript" language="javascript" src="Datatables/datatables.min.js"></script>
	<script type="text/javascript" language="javascript" src="vendor/components/jquery/jquery.min.js"></script>
	<script type="text/javascript" language="javascript" src="bootstrap/js/bootstrap.min.ls.js"></script>
	<script type="text/javascript" language="javascript" src="Datatables/DataTables-1.10.20/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="vendor/twbs/bootstrap/js/dist/tab.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/Buttons-1.6.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/Buttons-1.6.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/AutoFill-2.3.4/js/dataTables.autoFill.min.js"></script>
    <!-- <script type="text/javascript" language="javascript" src="Datatables/FixedHeader-3.1.6/js/dataTables.fixedHeader.min.js"></script> -->
	<script type="text/javascript" language="javascript" src="Datatables/FixedColumns-3.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script type="text/javascript" language="javascript" src="Datatables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" language="javascript" src="js/pace.min.js"></script>


	<!-- Initialisation des tableaux de données de la page -->
	<script>
		$(document).ready(function () {
			$('table.display').DataTable( {
                responsive: true,
                language: {
                       url: "Datatables/French.json"
                    },
                dom: 'Bfrtip',
                lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                ],
                buttons: ['copy','excel','csv',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'A2'
                    },'pageLength'
                ],
                content: [
                    {
                        fontSize: '10'
                    }
                ]
            } );
		});

        function chargement() {
            document.getElementById('chargement').style.display = 'none';
            document.getElementById('site').style.visibility = 'visible';
        }
    </script>
    <!-- forcage de paramètres des feuilles de style précédemment chargées -->
    <style type="text/css">
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
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css" /> -->
</head>
</head>
<div id="bandeau">
    <p class="imageflottante">
        <center>
            <H1 class="jumbotron"> Consultation de la table <?php echo $table; ?> </H1>
        </center>
    </p>

    <body>
        <script src="js/pace.min.js"></script>
        <a href="Descriptif.php" >Liste des tables et vues du portail</a>
        <center>
            <form method="POST" action="inventory.php">
                <select name="table" size="1" maxlength="15" value="<?php if ($table != '') {
    echo $table;
} ?>" class="input-lg">
                    <option value="">choisissez une table</option>

                    <?php

    $querytable = 'show tables;';

    if ($stmt = $con->prepare($querytable)) {
        $stmt->execute();
        while ($resulttable = $stmt->fetch()) {
            if ($table == $resulttable[0]) {
                echo '<option value="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
            } else {
                echo '<option value="'.$resulttable[0].'">'.$resulttable[0].'</option>';
            }

            $stmt->pdo = null;
        }
    }
?>
                </select>

                <input list="NameServer" name="NameServer" size="50" value="<?php echo $NameServer; ?>"
                    class="input-lg">
                <datalist id="NameServer">
                    <option value="">choisissez le nom du serveur</option>
                    <?php

    $querynameserver = 'select configurationname_wo_extension from system_inventory order by configurationname_wo_extension';

    if ($stmt = $con->prepare($querynameserver)) {
        $stmt->execute();
        while ($resultnameserver = $stmt->fetch()) {
            if ($NameServer == $resultnameserver[0]) {
                echo '<option value="'.$resultnameserver[0].'" selected>'.$resultnameserver[0].'</option>';
            } else {
                echo '<option value="'.$resultnameserver[0].'" >'.$resultnameserver[0].'</option>';
            }

            $stmt->pdo = null;
        }
    }
?>
                </datalist>

                <select name="operatingenvironment" size="1" maxlength="15" value="<?php if ($operatingenvironment != '') {
    echo $operatingenvironment;
} ?>" class="input-lg"><br>
                    <option value="">Environnement Opérationnel</option>
                    <?php

    $querytable = 'select distinct operatingenvironment from global_inventory;';

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

                <select name="osname" size="1" maxlength="15" class="input-lg">
                    <option value="">Nom de l'OS</option>
                    <option value="AIX" <?php if ($osname == 'AIX') {
    echo 'selected';
} ?>>AIX</option>
                    <option value="LINUX" <?php if ($osname == 'LINUX') {
    echo 'selected';
} ?>>LINUX</option>
                    <option value="WINDOWS" <?php if ($osname == 'WINDOWS') {
    echo 'selected';
} ?>>WINDOWS</option>
                    <option value="IOS" <?php if ($osname == 'IOS') {
    echo 'selected';
} ?>>IOS</option>
                    <option value="VMNIX" <?php if ($osname == 'VMNIX') {
    echo 'selected';
} ?>>VMNIX</option>
                    <option value="NX-OS" <?php if ($osname == 'NX-OS') {
    echo 'selected';
} ?>>NX-OS</option>
                </select><br><br>
                <input type="checkbox" name="chk_archived" <?php if (isset($_POST['chk_archived'])) {
    echo 'checked';
} ?> class="input-lg">
                <label for="chk_archived">Cacher les machines "archived" ?</label></t>
                <input type="checkbox" name="chk_osversion_null" <?php if (isset($_POST['chk_osversion_null'])) {
    echo 'checked';
} ?> class="input-lg">
                <label for="chk_osversion_null">montrer les osversion à "null"</label>
                <input type="submit" value="Envoyer" name="envoyer" class="input-lg">

            </form>
        </center>


        <?php

    //// debug de variables
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
    // echo "</p>";

 // $con = new PDO('mysql:host=localhost;dbname=cmdb;charset=utf8','root','!Maverick02#')
    // or die ('Could not connect to the database server' . pdo_connect_error());

if ($NameServer != '' && $operatingenvironment != '' && $osname != '') 
{

    $query = "SELECT * FROM `".$table."` where UPPER(configurationname_wo_extension) like UPPER('".$NameServer."%') and UPPER(osname) like UPPER('".$osname."%') and UPPER(operatingenvironment) = UPPER('".$operatingenvironment."')".$archived.$osversion_null;
} 
elseif ($NameServer != '' && $operatingenvironment != '' && $osname == '') 
{

    $query = "SELECT * FROM `".$table."` where UPPER(configurationname_wo_extension) like UPPER('".$NameServer."%') and UPPER(operatingenvironment) = UPPER('".$operatingenvironment."')".$archived.$osversion_null;
} elseif ($NameServer != '' && $operatingenvironment == '' && $osname != '') {
    $query = "SELECT * FROM `".$table."` where UPPER(configurationname_wo_extension) like UPPER('".$NameServer."%') and UPPER(osname) like UPPER('".$osname."%')".$archived.$osversion_null;
} 
elseif ($NameServer == '' && $operatingenvironment != '' && $osname != '') 
{
    $query = "SELECT * FROM `".$table."` where UPPER(osname) like UPPER('".$osname."%') and UPPER(operatingenvironment) = UPPER('".$operatingenvironment."')".$archived.$osversion_null;
} 
elseif ($NameServer != '' && $operatingenvironment == '' && $osname == '') 
{
    $query = "SELECT * FROM `".$table."` where UPPER(configurationname_wo_extension) like UPPER('".$NameServer."%')".$archived.$osversion_null;
} 
elseif ($NameServer == '' && $operatingenvironment != '' && $osname == '') {
    // echo "6";
    $query = "SELECT * FROM `".$table."` where UPPER(operatingenvironment) = UPPER('".$operatingenvironment."')".$archived.$osversion_null;
} 
elseif ($NameServer == '' && $operatingenvironment == '' && $osname != '') {
    // echo "7";
    $query = "SELECT * FROM `".$table."` where UPPER(osname) like UPPER('".$osname."%')".$archived.$osversion_null;
} 
else 
{
    if ($archived != '' && $osversion_null != '') {
        $query = 'SELECT * FROM `'.$table.'` '.str_replace('AND', 'WHERE', $archived).$osversion_null;
    } elseif ($archived != '' && $osversion_null == '') {
        $query = 'SELECT * FROM `'.$table.'` '.str_replace('AND', 'WHERE', $archived);
    } elseif ($archived == '' && $osversion_null != '') {
        $query = 'SELECT * FROM `'.$table.'` '.str_replace('AND', 'WHERE', $osversion_null);
    } else {
        $query = 'SELECT * FROM `'.$table.'`;';
    }
}
if ($table != null) {
    $count_query = str_replace('*', 'count(*)', $query);

    if ($stmt = $con->prepare($count_query)) {
        $stmt->execute();

        $count_item = $stmt->fetch();
    }

    echo "<center><br><u>Requête SQL générée :</u> <br><br><textarea cols=80 rows=5 disabled>$query</textarea></center><br>";

    echo "<center>Nombre d'occurences liées à la requête:".$count_item[0].'</center><br>'; ?>

        <!-- <center>
            <form method="POST" action="export_to_csv.php" TARGET="_blank">
                <input type="hidden" name="requete" value="<?php echo $query; ?>">
                <input type="submit" value="Export en CSV" name="export">
            </form>
        </center> -->
</div>
<div class="container">
    <?php

        if ($stmt = $con->prepare($query)) {
            $stmt->execute();
            $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($tuples)) {
                $columns_names = array_keys($tuples[0]); ?>
    <table id="" class="display" style="width: 100%;">
        <thead>
            <tr>
                <?php
                        foreach ($columns_names as $col) {
                            echo '<th>'.$col.'</th>';
                        } ?>
            </tr>
        </thead>
        <tbody>
            <?php
                    foreach ($tuples as $tuple) {
                        echo '<tr>';
                        foreach ($tuple as $entete => $col) {
                            switch (strtoupper($entete)) {
                                case 'CONFIGURATIONNAME_WO_EXTENSION':
                                    echo '<td><form id="'.$col.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$col."\"/> </form><a href='#' onclick='document.getElementById(\"".$col."\").submit()' ><b>".$col.'</b></a></td>';
                                    break;
                                case 'HOSTNAME':
                                    echo '<td><form id="'.$col.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$col."\"/> </form><a href='#' onclick='document.getElementById(\"".$col."\").submit()' ><b>".$col.'</b></a></td>';
                                    break;
                                default:
                                    echo '<td>'.$col.'</td>';
                                    break;
                            }
                        }
                        echo '</tr>';
                    } ?>
        </tbody>
    </table>
    <?php
            } else {
                echo 'Pas de résultat';
            }

            $stmt->pdo = null;
        }
}
?>
</div>
</body>

</html>
