<?php
    require 'variable-db.php';
    require 'functions.php';
    //Récupération des $_POST dans des variables
    $application = isset($_POST['application']) ? $_POST['application'] : null;
    $chk_appli_obso = isset($_POST['chk_appli_obso']) ? $_POST['chk_appli_obso'] : null;

    //initialisation des variables
    $list_serveur = array();
    $list_instance_db = array();

    // Initialisation de la connexion à la base de données
    $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());

?>
<html>
<head>
		<title>Gestion de l'obsolescence</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" />
<!--         <script type="text/javascript" language="javascript" src="javascript/jquery-3.3.1.js"></script>
        <script type="text/javascript" language="javascript" src="javascript/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="javascript/dataTables.bootstrap.min.js"></script>
        
 -->        <style type="text/css">
                * {
                margin: 0;
                padding: 0;
            
                } 
            #menu-tab {
                /*background: white;*/
                /* font-family: 'trebuchet ms', geneva;  */
                font-size: 100%;
                color:black;
                }
            
            #menu-tab a {
            color: blue;
            font-weight: normal;
            font-style: normal;
            text-decoration: none;
            font-variant: normal;
            }
            #menu-tab a:hover{
            color: green;
            
            }
            
            /*--------------Dimensions tableau--------------*/
            
            #page-wrap {
                width: 100%; 
                margin: 10px auto;
            
                }
            
            /*--------------Onglets--------------*/
            
            .tabs {
                position: absolute;   
                min-height: 59%;  /*This part sucks */
                min-width: 98%;
                clear: both;
                margin: 25px 0;
                }
                .tab {
                float: left;
                }
                .tab label {
                background: rgba(170, 185, 185, 0.97);
                padding: 10px; 
                border: 1px solid #ccc; 
                margin-left: -1px; 
                position: relative;
                left: 1px; 
                border-radius: 10px 10px 0px 0px;
                box-shadow: 3px -3px 6px rgba(0, 0, 0, 0.71);
                }
                .tab [type=radio] {
                display: none;   
                }
            
            /*--------------Contenu article onglet--------------*/
                .content {
                position: absolute;
                top: 28px;
                left: 0;
                background: white;
                right: 0;
                bottom: 0;
                padding: 20px;
                border: 1px solid #ccc; 
                border-radius: 0px 10px 10px 10px;
                box-shadow:6px 6px 10px rgba(0, 0, 0, 0.41);
                overflow: hidden;
                overflow-y: auto;
                margin-bottom: -20px;
                }
                .content > * {
                opacity: 0;
            
                -webkit-transform: translate3d(0, 0, 0);
            
                -webkit-transform: translateX(-100%);
                -moz-transform:    translateX(-100%);
                -ms-transform:     translateX(-100%);
                -o-transform:      translateX(-100%);
            
                -webkit-transition: all 0.6s ease;
                -moz-transition:    all 0.6s ease;
                -ms-transition:     all 0.6s ease;
                -o-transition:      all 0.6s ease;
                }
            
            /*-------------Onglets actifs--------------*/
            
                [type=radio]:checked ~ label {
                background: rgb(104, 104, 104);
                border-bottom: 1px solid white;
                z-index: 2;
                }
                [type=radio]:checked ~ label ~ .content {
                z-index: 1;
                }
                [type=radio]:checked ~ label ~ .content > * {
                opacity: 1;
            
                -webkit-transform: translateX(0);
                -moz-transform:    translateX(0);
                -ms-transform:     translateX(0);
                -o-transform:      translateX(0);
                }
            /*--------------Images--------------*/
            
                .content img {
                border:4px solid white;
                box-shadow:6px 6px 10px grey;
                -webkit-transition: all 0.6s ease;
                -moz-transition:    all 0.6s ease;
                -ms-transition:     all 0.6s ease;
                -o-transition:      all 0.6s ease;
                }
            
                .content img:hover {
                opacity: 0.8;
                -webkit-transform: rotate(7deg);
                -moz-transform:    translateX(0);
                -ms-transform:     translateX(0);
                -o-transform:      translateX(0);
            
                -webkit-transition: all 0.6s ease;
                -moz-transition:    all 0.6s ease;
                -ms-transition:     all 0.6s ease;
                -o-transition:      all 0.6s ease;
                }
        </style>
</head>
<body>
    <center><H3 class='jumbotron'> Gestion de l'obsolescence</h3></center>
    <form id="valid_app" name="valid_app" class="form-horizontal" method="POST" enctype="multipart/form-data" action="gestion_obso.php">

        <center><Table class='table-hover' style='width:90%'>
<!--             <tr>
                <td><h4>Numéro de ticket obso</h4></td>
                <td colspan='2'>
                    <input style='text' name='num_ticket_obso' id='num_ticket_obso' class='input-lg' />
                </td>
            </tr> -->
            <tr>
                <td>
                   <h4> Application concernée</h4>
                </td>
                <td colspan='2'>
                    <input list='list_data_app' name='application' id='application' width='auto' class='input-lg' onchange='document.getElementById("valid_app").submit()' value='<?php echo $application; ?>' onclick="if(this.value!='')this.value=''">
                    <datalist id="list_data_app">
                            <?php
                                if ($chk_appli_obso == 'on') {
                                    $querytable = 'SELECT * FROM cmdb.list_application_with_os_obsolete;';
                                } else {
                                    $querytable = 'select application from cmdb.application order by application';
                                }
                                if ($stmt = $con->prepare($querytable)) {
                                    $stmt->execute();
                                    while ($resulttable = $stmt->fetch()) {
                                        if ($application == strtoupper($resulttable[0])) {
                                            echo '<option valeur="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
                                        } else {
                                            echo '<option valeur="'.$resulttable[0].'">'.$resulttable[0].'</option>';
                                        }
                                    }
                                    $stmt->pdo = null;
                                }
                            ?>
                        </datalist>
                        <?php
                        if ($chk_appli_obso == 'on') {
                            ?>
                            <input type='checkbox' name='chk_appli_obso' id='chk_appli_obso' class='input-lg' onclick='document.getElementById("valid_app").submit()' checked>
                            <label>Filtrer uniquement les applications avec des OS obsoletes</label>
                            <?php
                        } else {
                            ?>
                            <input type='checkbox' name='chk_appli_obso' id='chk_appli_obso' class='input-lg' onclick='document.getElementById("valid_app").submit()'>
                            <label> Filtrer uniquement les applications avec des OS obsoletes</label>
                            <?php
                        }
                        ?>
                </td>
                </tr>
                <tr>
                    
                    <?php
                    if ($application != '') {
                        $querycall = "call `cmdb`.`point_of_contact_by_app` ('$application')";

                        if ($stmt = $con->prepare($querycall)) {
                            $stmt->execute();
                            /* echo "<pre>";
                            print_r($stmt); */
                            echo'<td><h4>Référants</h4></td>';
                            while ($resultcall = $stmt->fetchAll()) {
                                /* print_r($resultcall);
                                echo "</pre>"; */
                                if ($application == strtoupper($resultcall[0][0])) {
                                    if ($resultcall[0][1] != '') {
                                        echo '<td>';
                                        echo '<h4>IT Functional: <u><b>'.$resultcall[0][1].'</b></u></h4>';
                                        echo '</td>';
                                    } else {
                                        echo '<td>';
                                        echo '<h4> Pas de IT Functional Trouvé </h4>';
                                        echo '</td>';
                                    }
                                    if ($resultcall[0][2] != '') {
                                        echo '<td>';
                                        echo '<h4>IT Technical: <u><b>'.$resultcall[0][2].'</b></u></h4>';
                                        echo '</td>';
                                    } else {
                                        echo '<td>';
                                        echo '<h4> Pas de IT Technical Trouvé </h4>';
                                        echo '</td>';
                                    }
                                } else {
                                    echo '<td>';
                                    echo '<h4> Impossible de récupérer les données!!! </h4>';
                                    echo '</td>';
                                }
                            }
                            $stmt->pdo = null;
                        } ?>
                    </tr>
                </table></center>
            </form>
        
            <?php
                if ($application != '') {
                    $queryserver = "select CONFIGURATIONNAME_WO_EXTENSION,OPERATINGENVIRONMENT,OSNAME,OSVERSION,`DB Subsystem Type`,`DB Middleware Edition`,`DB Middleware Version`,`DB Instance Name`,`DB Instance`,`MDW Subsystem Type`,`MDW Middleware Edition`,`MDW Middleware Version`,`MDW Type`,`MDW STATUS` from cmdb.global_inventory where status <> 'ARCHIVED' and businessservices like '%".$application."%' order by OPERATINGENVIRONMENT;";

                    if ($stmt = $con->prepare($queryserver)) {
                        $stmt->execute();
                        $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($tuples)) {
                            ?>
            <div id="menu-tab"><!----------------tableau-01---------------------------------->
            <div id="page-wrap">
            <div class="tabs" style="left:10px">
            <!----------------onglet-01-------------------------->
            <div class="tab"><input id="tab-1" checked="checked" name="tab-group-1" type="radio" /> 
            <label for="tab-1">Serveurs</label>
            <div class="content">
            <?php
                ob_start(); ?>
            <table id="List_OS" class="table table-hovered" style="width: 100%;" role="grid" aria-describedby="example_info">
                <thead>
                    <tr>
                        <th colspan='4'><center><H3>LISTE DES SERVEURS de l'application <?php echo $application; ?></center></th>
                    </tr>
                    <tr bgcolor='silver'>
                        <th>HOSTNAME</th>
                        <th>ENVIRONNEMENT</th>
                        <th>OSNAME</th>
                        <th>OSVERSION</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($tuples as $ligne) {
                        if (!in_array($ligne['CONFIGURATIONNAME_WO_EXTENSION'], $list_serveur)) {
                            $list_serveur[] = $ligne['CONFIGURATIONNAME_WO_EXTENSION'];
                            echo "<tr bgcolor='".status_obso_osversion($ligne['OSVERSION'], $host, $dbname, $user, $password)."'>";
                            foreach ($ligne as $entete => $valeur) {
                                switch ($entete) {
                                    case 'CONFIGURATIONNAME_WO_EXTENSION':
                                        echo '<td><form id="'.$valeur.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$valeur."\"/></form><a href='#' onclick='document.getElementById(\"".$valeur."\").submit()'><b>".$valeur.'</b></a></td>';
                                        break;
                                    case 'OPERATINGENVIRONMENT':
                                        echo "<td>$valeur</td>";
                                        break;
                                    case 'OSNAME':
                                        echo "<td>$valeur</td>";
                                        break;
                                    case 'OSVERSION':
                                        echo "<td>$valeur</td>";
                                        break;
                                }
                            }
                        }
                        echo '</tr>';
                    }
                            $list_serveur = array(); ?>
                </tbody>
            </table>
            <?php
                $PDF_serveurs = ob_get_contents(); ?> 
            <center>
            <form id="exporttopdfservers" class="form-horizontal" method="POST" enctype="multipart/form-data" action="export_to_pdf.php">
                <input type="hidden" name="EXPORTPDF" value="true">
    
                <input type="hidden" name="DATA" value='<?php  echo base64_encode(serialize($PDF_serveurs)); ?>'>
    
                <input type="button" class="input-lg" value="Export en PDF" onclick="document.getElementById('exporttopdfservers').submit();" >
            </form>
            </center> 
        </div>
    </div>
                                        <!----------------onglet-02-------------------------->
                                        <div class="tab"><input id="tab-2" name="tab-group-1" type="radio" /> 
                                        <label for="tab-2">Base de données</label>
                                        <div class="content">
                                            <?php
                                                ob_start(); ?>
                                            <table class='table table-hover' width='90%'>
                                                <thead>
                                                    <tr>
                                                        <th colspan='6'><center><H3>LISTE DES BASE DE DONNEES de l'application <?php echo $application; ?></center></th>
                                                    </tr>
                                                    <tr bgcolor='silver'>
                                                    <th>HOSTNAME</th><th>ENVIRONNEMENT</th><th>DB Instance Name</th><th>DB Subsystem Type</th><th>DB Middleware Edition</th><th>DB Middleware Version</th><th>DB Instance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php

                                                    foreach ($tuples as $ligne) {
                                                        if (!in_array($ligne['DB Instance'], $list_instance_db) && $ligne['DB Subsystem Type'] != '' && $ligne['DB Middleware Edition'] != '' && $ligne['DB Middleware Version'] != '') {
                                                            $list_instance_db[] = $ligne['DB Instance'];
                                                            echo "<tr bgcolor='".status_obso_dbversion($ligne['DB Middleware Version'], $host, $dbname, $user, $password)."'>";
                                                            foreach ($ligne as $entete => $valeur) {
                                                                switch ($entete) {
                                                                    case 'CONFIGURATIONNAME_WO_EXTENSION':
                                                                        echo '<td><form id="'.$valeur.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$valeur."\"/></form><a href='#' onclick='document.getElementById(\"".$valeur."\").submit()'><b>".$valeur.'</b></a></td>';
                                                                        break;
                                                                    case 'OPERATINGENVIRONMENT':
                                                                        echo "<td>$valeur</td>";
                                                                        break;
                                                                    case 'DB Subsystem Type':
                                                                        echo "<td>$valeur</td>";
                                                                        break;
                                                                    case 'DB Instance Name':
                                                                        if (strpos($valeur, 'MSSQLSERVER')) {
                                                                            $sqlserver = 1;
                                                                        } else {
                                                                            $sqlserver = 0;
                                                                            // $datasql="";
                                                                        }
                                                                        $datasql = $valeur;
                                                                        echo '<td>'.$valeur.'</td>';
                                                                        break;

                                                                    case 'DB Middleware Version':
                                                                        if ($valeur != '') {
                                                                            echo '<td>'.$valeur.'</td>';
                                                                        } else {
                                                                            echo "<td >Pas d'info</td>";
                                                                        }
                                                                        break;

                                                                    case 'DB Middleware Edition':
                                                                        if ($valeur != '') {
                                                                            echo '<td>'.$valeur.'</td>';
                                                                        } else {
                                                                            echo "<td >Pas d'info</td>";
                                                                        }
                                                                        break;

                                                                    case 'DB Instance':
                                                                        if ($valeur != '') {
                                                                            echo "<td><b><form id='$datasql' method='POST' action='application_by_db.php'><input type='hidden' name='db' value='$datasql'/><input type='hidden' name='application' value='$application'/> </form> <a href='#' onclick=\"document.getElementById('".$datasql."').submit()\">$valeur</a></b></td>";
                                                                        } else {
                                                                            echo "<td >Pas d'info</td>";
                                                                        }
                                                                        break;
                                                                }
                                                            }
                                                        }
                                                        echo '</tr>';
                                                    }
                            $list_instance_db = array(); ?>
                                                </tbody>
                                            </table>
                                            <?php
                                                $PDF_database = ob_get_contents(); ?> 
                                            <center>
                                            <form id="exporttopdfdatabase" class="form-horizontal" method="POST" enctype="multipart/form-data" action="export_to_pdf.php">
                                                <input type="hidden" name="EXPORTPDF" value="true">
                                    
                                                <input type="hidden" name="DATA" value='<?php  echo base64_encode(serialize($PDF_database)); ?>'>
                                    
                                                <input type="button" class="input-lg" value="Export en PDF" onclick="document.getElementById('exporttopdfdatabase').submit();" >
                                            </form>
                                            </center> 
                                            </div>
                                        </div>
                                        <!----------------onglet-03-------------------------->
                                        <div class="tab"><input id="tab-3" name="tab-group-1" type="radio" /> 
                                        <label for="tab-3">Middleware</label>
                                        <div class="content">
                                            <?php
                                                ob_start(); ?>
                                            <table class='table table-hover' width='90%'>
                                                <thead>
                                                    <tr>
                                                        <th colspan='6'><center><H3>LISTE DES MIDDLEWARE de l'application <?php echo $application; ?></center></th>
                                                    </tr>
                                                    <tr bgcolor='silver'>
                                                        <th>HOSTNAME</th><th>ENVIRONNEMENT</th><th>MDW Subsystem Type</th><th>MDW Middleware Edition</th><th>MDW Middleware Version</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    foreach ($tuples as $ligne) {
                                                        if ($ligne['MDW Subsystem Type'] != '' && $ligne['MDW Middleware Edition'] != '' && $ligne['MDW Middleware Version'] != '') {
                                                            $list_serveur = array($ligne['CONFIGURATIONNAME_WO_EXTENSION']);
                                                            echo "<tr bgcolor='".status_obso_middlewareversion($ligne['MDW Middleware Version'], $host, $dbname, $user, $password)."'>";
                                                            foreach ($ligne as $entete => $valeur) {
                                                                switch ($entete) {
                                                                    case 'CONFIGURATIONNAME_WO_EXTENSION':
                                                                        echo '<td><form id="'.$valeur.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$valeur."\"/></form><a href='#' onclick='document.getElementById(\"".$valeur."\").submit()'><b>".$valeur.'</b></a></td>';
                                                                        break;
                                                                    case 'OPERATINGENVIRONMENT':
                                                                        echo "<td>$valeur</td>";
                                                                        break;
                                                                    case 'MDW Subsystem Type':
                                                                        echo "<td>$valeur</td>";
                                                                        break;

                                                                    case 'MDW Middleware Version':
                                                                        if ($valeur != '') {
                                                                            echo '<td>'.$valeur.'</td>';
                                                                        } else {
                                                                            echo "<td >Pas d'info</td>";
                                                                        }
                                                                        break;

                                                                    case 'MDW Middleware Edition':
                                                                        if ($valeur != '') {
                                                                            echo '<td>'.$valeur.'</td>';
                                                                        } else {
                                                                            echo "<td >Pas d'info</td>";
                                                                        }
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        echo '</tr>';
                                                    }
                        }
                        $stmt->pdo = null;
                    }
                } ?>
                                                </tbody>
                                            </table>
                                            <?php
                                                $PDF_middleware = ob_get_contents(); ?> 
                                            <center>
                                            <form id="exporttopdfmiddleware" class="form-horizontal" method="POST" enctype="multipart/form-data" action="export_to_pdf.php">
                                                <input type="hidden" name="EXPORTPDF" value="true">
                                    
                                                <input type="hidden" name="DATA" value='<?php  echo base64_encode(serialize($PDF_middleware)); ?>'>
                                    
                                                <input type="button" class="input-lg" value="Export en PDF" onclick="document.getElementById('exporttopdfmiddleware').submit();" >
                                            </form>
                                            </center>
                                            </div>
                                        </div>
                                        <!----------------onglet-DEBUG-------------------------->
                                        <div class="tab"><input id="tab-4" name="tab-group-1" type="radio" /> 
                                            <label for="tab-4">Debug</label>
                                            <div class="content">
                                                <?php
                                                    echo '<p class="debug">';
                        error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

                        $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
                                                    $var_ignore = array('GLOBALS', '_ENV', '_SERVER', '_GET', 'host', 'dbname', 'user', 'password', 'port', 'socket'); // Détermine les var à ne pas afficher
                                                    echo '<strong>Etat des variables a la ligne : '.__LINE__.' dans le fichier : '.__FILE__."</strong><br />\n";
                        $nom_fonction = __FUNCTION__;
                        if (isset($nom_fonction) && $nom_fonction != '') {
                            echo '<strong>Dans la fonction : '.$nom_fonction."</strong><br />\n";
                        }
                        foreach ($variables as $key => $valeur) {
                            if (!in_array($key, $var_ignore) && strpos($key, 'HTTP') === false) {
                                echo '<pre class="debug">';
                                echo '$'.$key.' => ';
                                print_r($valeur);
                                echo "</pre>\n";
                            }
                        }

                        echo '</p>'; ?>
                                            </div>
                                        </div>
                            <?php
                    }
                    ?>
</body>
</html>
