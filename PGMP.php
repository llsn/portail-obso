<?php
    require 'variable-db.php';
    require 'functions.php';
    $theme="Bootstrap4";
    $PGMP = isset($_POST['PGMP']) ? $_POST['PGMP'] : null;
    $MACHINE = isset($_POST['machine']) ? $_POST['machine'] : null;
    $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());

    if($PGMP != null)
    {
        if (preg_match("#CMAS-CAT-00#i", "'.$PGMP.'") != TRUE)
        {
            $PGMP = "CMAS-CAT-00". $PGMP;

        }
    }
?>
<html>
<head>
	<!-- définition des caractères lié à l'affichage -->
	<meta charset="utf-8">
	<!-- Titre de la page -->
	<title>Recherche par PGMP - <?php echo "$PGMP"; ?></title>
	<!-- définition des metadata de la page  -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Chargement de DataTables -->
    <link rel="stylesheet" type="text/css" href="Datatables/default/datatables.css"/>
    <link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
    <script type="text/javascript" src="Datatables/default/datatables.js"></script>
    <script type="text/javascript" language="javascript" src="js/pace.min.js"></script>
    <script>
        
        $(document).ready
        (
            function () 
            {
                $('table.display').DataTable
                ( 
                    { 
                        "autoWidth": false,
                        responsive: true,
                        keys: true,
                        select: true,
                        fixedHeader: true,
                        colReorder: true,
                        language: 
                        {
                        url: "Datatables/French.json"
                        },
                        dom: 'Bfrtip',
                        lengthMenu: 
                        [
                            [ 10, 25, 50, -1 ],
                            [ '10 lgnes', '25 lignes', '50 lignes', 'toutes les lignes' ]
                        ],
                        buttons: 
                        [
                            'print',
                            'copy',
                            'excel',
                            'csv',
                            {
                                extend: 'pdfHtml5',
                                orientation: 'landscape',
                                pageSize: 'A3'
                            },
                            'pageLength'
                        ],
                        initComplete: function () 
                        {
                            // Setup - add a text input to each footer cell
                            $('#example tfoot th').each
                                ( 
                                    function () 
                                    {
                                        var title = $(this).text();
                                        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
                                    } 
                                );
                            
                                // DataTable
                                var table = $('#example').DataTable();
                            
                                // Apply the search
                                table.columns().every( function () 
                                {
                                    var that = this;
                            
                                    $( 'input', this.footer() ).on( 'keyup change clear', 
                                    function () 
                                    {
                                        if ( that.search() !== this.value ) {
                                            that
                                                .search( this.value )
                                                .draw();
                                        }
                                    } );
                                } );
                                
                                var r = $('#example tfoot tr');
                                r.find('th').each
                                (
                                    function()
                                        {
                                            $(this).css('padding', 8);
                                        }
                                );
                                $('#example thead').append(r);
                                $('#search_0').css('text-align', 'center');
                        },
                        "pagingType": "full_numbers",
						fixedHeader: true
                        
                    }
                     
                );
                
                
                
            }
        );
        
        function chargement() 
        {
			document.getElementById('chargement').style.display = 'none';
			document.getElementById('site').style.visibility = 'visible';
        };

		window.paceOptions = {
			ajax: true,
			element: true
		};
	</script>

    
</head>
	<body>
        <script src="js/pace.min.js"></script>
        <div>
			<h1 class="page-header"><center>PGMP INFO</center></h1>
        </div>
	    <div>
            <h3 class="page-header"><center>Recherche par PGMP</center></h3>
            <center><h4> PGMP : <?php echo $PGMP ?></h4></center>
            <br/>
        
            <form id="FORM_PGMP" class="form-horizontal" method="POST" enctype="multipart/form-data" action="PGMP.php" >
                <div class="form-row align-items-center" >
                    <div class="col-auto">
                        <acronym title="Saisissez ou sélectionnez le texte désiré et tapez sur 'ENTREE' ou cliquez sur le bouton 'Valider'">
                            <input list="PGMP_LIST" class="form-control mb-2" width="30%" name ="PGMP" size="50" value="<?php echo $PMGP; ?>" onclick="if(this.value!='')this.value=''">
                        </acronym>
                        <datalist id="PGMP_LIST">
                            <option value="" >Choisissez le PGMP</option>
                            <?php

                                $queryPGMP = "select distinct PgMP from PGMP.PGMP_HISTORY where PgMP like 'CMAS-CAT-%' order by PgMP";
                                if ($stmt = $con->prepare($queryPGMP)) 
                                {
                                    $stmt->execute();
                                    while ($resultPGMP = $stmt->fetch()) 
                                    {
                                        echo $resultPGMP[0];
                                        if ($PGMP == $resultPGMP[0]) 
                                        {
                                            echo "<option value='".$resultPGMP[0]."' selected onclick=\"document.getElementById(FORM_PGMP).submit()\">".$resultPGMP[0].'</option>';
                                        } 
                                        else 
                                        {
                                            echo "<option value='".$resultPGMP[0]."' onclick=\"document.getElementById(FORM_PGMP).submit()\">".$resultPGMP[0].'</option>';
                                        }

                                        $stmt->pdo = null;
                                    }
                                }
                            ?>
                        </datalist>
                    <div>
                    <input type="submit" class="btn btn-lg">
                </div>
            </form>
        </div>
        <div>
			<h3 class="page-header"><center>Recherche par machine</center></h3>
            <form id="FORM_MACHINE" class="form-horizontal" method="POST" enctype="multipart/form-data" action="PGMP.php" >
                <div class="form-row align-items-center" >
                    <div class="col-auto">
                        <acronym title="Saisissez ou sélectionnez le texte désiré et tapez sur 'ENTREE' ou cliquez sur le bouton 'Valider'">
                            <input list="SERVEUR_LIST" class="form-control mb-2" width="30%" name ="machine" size="50" value="<?php echo $PMGP; ?>" onclick="if(this.value!='')this.value=''">
                        </acronym>
                        <datalist id="SERVEUR_LIST">
                            <option value="" >Choisissez le PGMP</option>
                            <?php

                                $queryHostname = "select distinct Hostname from PGMP.PGMP_HISTORY order by Hostname";
                                if ($stmt = $con->prepare($queryHostname)) 
                                {
                                    $stmt->execute();
                                    while ($resultHostname = $stmt->fetch()) 
                                    {
                                        echo $resultHostname[0];
                                        if ($MACHINE == $resultHostname[0]) 
                                        {
                                            echo "<option value='".$resultHostname[0]."' selected onclick=\"document.getElementById(FORM_MACHINE).submit()\">".$resultHostname[0].'</option>';
                                        } 
                                        else 
                                        {
                                            echo "<option value='".$resultHostname[0]."' onclick=\"document.getElementById(FORM_MACHINE).submit()\">".$resultHostname[0].'</option>';
                                        }

                                        $stmt->pdo = null;
                                    }
                                }
                            ?>
                        </datalist>
                    <div>
                    <input type="submit" class="btn btn-lg">
                </div>
            </form>
        </div>
        <br/>
        <?php

            $count = 0;

            if ($PGMP != null && $MACHINE == null) 
            {
                ?>
                <table id='example' class='display' width='95%'>
                    <thead>
                        <tr>
                            <th colspan='12'>
                                <form id="<?php echo $PGMP ?>" method="POST" action="fiche_machine.php">
                                    <input type='hidden' name='PGMP' value='<?php echo $PGMP ?>'/> 
                                </form>
                                <!-- <a href='#' onclick='document.getElementById("<?php echo $PGMP ?>").submit()' target="_blank">
                                    <center>
                                        <b>
                                            <?php echo $PGMP ?>
                                        </b>
                                    </center>
                                </a> -->
                            </th>  
                        </tr>
                        <tr>
                            <th>HOSTNAME</th>
                            <th>OPERATING SYSTEM</th>
                            <th>CHANGER NUMBER</th>
                            <th>REQUESTOR</th>
                            <th>CATEGORIE</th>
                            <th>MOIS</th>
                            <th>UO</th>
                            <th>COMMENT</th>
                            <th>PGMP COMMENT</th>
                            <th>TYPE</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "call PGMP.search_pgmp('".$PGMP."');";
                    // echo $querydb;
                    if ($stmt = $con->prepare($query)) 
                    {
                        $stmt->execute();
                        $result_PGMP = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($result_PGMP)) 
                        {
                            foreach ($result_PGMP as $ligne_PGMP) 
                            {
                                foreach ($ligne_PGMP as $entete_pgmp => $valeur_pgmp) 
                                {                  
                                    switch ($entete_pgmp) 
                                    {
                                        case 'UO':
                                            $UO = $valeur_pgmp;
                                            break;
                                        case 'UO_ref':
                                            $UO_ref = $valeur_pgmp;
                                            break;
                                        case 'Categorie':
                                            $Categorie = $valeur_pgmp;
                                            break;
                                        case 'Mois':
                                            $Mois = $valeur_pgmp;
                                            break;
                                        case 'Comptage':
                                            $Comptage = $valeur_pgmp;
                                            break;
                                        case 'Hostname':
                                            $Hostname = $valeur_pgmp;
                                            break;
                                        case 'IP address':
                                            $IP_address = $valeur_pgmp;
                                            break;
                                        case 'Type':
                                            $Type = $valeur_pgmp;
                                            break;
                                        case 'Application':
                                            $Application = $valeur_pgmp;
                                            break;
                                        case 'OS':
                                            $OS = $valeur_pgmp;
                                            break;
                                        case 'Comment':
                                            $Comment = $valeur_pgmp;
                                            break;
                                        case 'PgMP':
                                            $PgMP = $valeur_pgmp;
                                            break;
                                        case 'PgMP_comment':
                                            $PgMP_comment = $valeur_pgmp;
                                            break;
                                        case 'Requestor':
                                            $Requestor = $valeur_pgmp;
                                            break;
                                    }
                                }
                                $tableau = array( $UO, $UO_ref, $Categorie, $Mois, $Comptage, $Hostname, $IP_address, $Change_number, $Type, $Application, $OS, $Comment, $PgMP, $PgMP_comment, $Requestor);
                                ++$count;
                                echo "<tr>
                                        
                                        <td>
                                            <form id='".$Hostname."' method=\"POST\" action=\"fiche_machine.php\">
                                                <input type=\"hidden\" name=\"machine\" value=\"".$Hostname."\"/> 
                                            </form>
                                            <a href='#' onclick='document.getElementById(\"".$Hostname."\").submit()' target=\"_blank\">
                                                <b>".$Hostname."</b>
                                            </a>
                                        </td>
                                        <td>".$OS."</td>
                                        <td>".$Change_number."</td>
                                        <td>".$Requestor."</td>
                                        <td>".$Categorie."</td>
                                        <td>".$Mois."</td>
                                        <td>".$UO."</td>
                                        <td>".$Comment."</td>
                                        <td>".$PgMP_comment."</td>
                                        <td>".$Type."</td>
                                    </tr>";
                            }
                        }
                    }
                }

            else if ($PGMP == null && $MACHINE != null) 
            {
                ?>
                <table id='example' class='display' width='95%'>
                    <thead>
                        <tr>
                            <th colspan='12'>
                                <form id="<?php echo $MACHINE ?>" method="POST" action="fiche_machine.php">
                                    <input type='hidden' name='machine' value='<?php echo $MACHINE ?>'/> 
                                </form>
                                <!-- <a href='#' onclick='document.getElementById("<?php echo $MACHINE ?>").submit()' target="_blank">
                                    <center>
                                        <b>
                                            <?php echo $MACHINE ?>
                                        </b>
                                    </center>
                                </a> -->
                            </th>  
                        </tr>
                        <tr>
                            <th>PGMP</th>
                            <th>HOSTNAME</th>
                            <th>OPERATING SYSTEM</th>
                            <th>CHANGER NUMBER</th>
                            <th>REQUESTOR</th>
                            <th>CATEGORIE</th>
                            <th>MOIS</th>
                            <th>UO</th>
                            <th>COMMENT</th>
                            <th>PGMP COMMENT</th>
                            <th>TYPE</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query_machine = "call PGMP.search_pgmp_by_machine('".$MACHINE."');";
                    // echo $querydb;
                    if ($stmt = $con->prepare($query_machine)) 
                    {
                        $stmt->execute();
                        $result_machine = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($result_machine)) 
                        {
                            foreach ($result_machine as $ligne_machine) 
                            {
                                foreach ($ligne_machine as $entete_machine => $valeur_machine) 
                                {                  
                                    switch ($entete_machine) 
                                    {
                                        case 'UO':
                                            $UO = $valeur_machine;
                                            break;
                                        case 'UO_ref':
                                            $UO_ref = $valeur_machine;
                                            break;
                                        case 'Categorie':
                                            $Categorie = $valeur_machine;
                                            break;
                                        case 'Mois':
                                            $Mois = $valeur_machine;
                                            break;
                                        case 'Comptage':
                                            $Comptage = $valeur_machine;
                                            break;
                                        case 'Hostname':
                                            $Hostname = $valeur_machine;
                                            break;
                                        case 'IP address':
                                            $IP_address = $valeur_machine;
                                            break;
                                        case 'Type':
                                            $Type = $valeur_machine;
                                            break;
                                        case 'Application':
                                            $Application = $valeur_machine;
                                            break;
                                        case 'OS':
                                            $OS = $valeur_machine;
                                            break;
                                        case 'Comment':
                                            $Comment = $valeur_machine;
                                            break;
                                        case 'PgMP':
                                            $PgMP = $valeur_machine;
                                            break;
                                        case 'PgMP_comment':
                                            $PgMP_comment = $valeur_machine;
                                            break;
                                        case 'Requestor':
                                            $Requestor = $valeur_machine;
                                            break;
                                    }
                                }
                                $tableau = array( $UO, $UO_ref, $Categorie, $Mois, $Comptage, $Hostname, $IP_address, $Change_number, $Type, $Application, $OS, $Comment, $PgMP, $PgMP_comment, $Requestor);
                                ++$count;
                                echo "<tr>
                                        <td>
                                            <form id='".$PgMP."' method=\"POST\" action=\"PGMP.php\">
                                                <input type=\"hidden\" name=\"PGMP\" value=\"".$PgMP."\"/> 
                                            </form>
                                            <a href='#' onclick='document.getElementById(\"".$PgMP."\").submit()' target=\"_blank\">
                                                <b>".$PgMP."</b>
                                            </a>

                                        </td>
                                        <td>
                                            <form id='".$Hostname."' method=\"POST\" action=\"fiche_machine.php\">
                                                <input type=\"hidden\" name=\"machine\" value=\"".$Hostname."\"/> 
                                            </form>
                                            <a href='#' onclick='document.getElementById(\"".$Hostname."\").submit()' target=\"_blank\">
                                                <b>".$Hostname."</b>
                                            </a>
                                        </td>
                                        <td>".$OS."</td>
                                        <td>".$Change_number."</td>
                                        <td>".$Requestor."</td>
                                        <td>".$Categorie."</td>
                                        <td>".$Mois."</td>
                                        <td>".$UO."</td>
                                        <td>".$Comment."</td>
                                        <td>".$PgMP_comment."</td>
                                        <td>".$Type."</td>
                                    </tr>";
                            }
                        }
                    }
                }
                else 
                {
                    ?>
                    <table class="display">
                        <thead>
                            <tr colspan='4'>
                                <th>
                                    <center>Pas d'information trouvée ... :S</center>
                                </th>
                            </tr>
                            <?php
                }
                ?>
                        </tbody>
                        <?php
                        if ($PGMP != null && $MACHINE == null) 
                        {
                            echo "<tfoot>
                                <tr>
                                    <th>HOSTNAME</th>
                                    <th>OPERATING SYSTEM</th>
                                    <th>CHANGER NUMBER</th>
                                    <th>REQUESTOR</th>
                                    <th>CATEGORIE</th>
                                    <th>MOIS</th>
                                    <th>UO</th>
                                    <th>COMMENT</th>
                                    <th>PGMP COMMENT</th>
                                    <th>TYPE</th>
                                </tr>
                            </tfoot>";
                        }
                        else if($PGMP == null && $MACHINE != null) 
                        {
                            echo "<tfoot>
                            <tr>
                                <th>PGMP</th>
                                <th>HOSTNAME</th>
                                <th>OPERATING SYSTEM</th>
                                <th>CHANGER NUMBER</th>
                                <th>REQUESTOR</th>
                                <th>CATEGORIE</th>
                                <th>MOIS</th>
                                <th>UO</th>
                                <th>COMMENT</th>
                                <th>PGMP COMMENT</th>
                                <th>TYPE</th>
                            </tr>
                        </tfoot>";
                        }
                        ?>
                    </table>
                <?php
            // // debug de variables
            // echo "<p class=\"debug\">";
            // error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

            // $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
            // $var_ignore=array("GLOBALS", "_ENV", "_SERVER","_GET","host","dbname","user","password","port","socket"); // Détermine les var à ne pas afficher
            // echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
            // $nom_fonction=__FUNCTION__;
            // if (isset($nom_fonction)&&$nom_fonction!="")
            // {
            // 	echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
            // }
            // foreach ($variables as $key=>$value)
            // {
            // 	if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
            //    	{
            // 		echo "<pre class=\"debug\">";
            // 		echo ("$".$key." => ");
            // 		print_r($value);
            // 		echo "</pre>\n";
            //   	}
            // }
            // echo "</p>";
        ?>
    </body>
</html>