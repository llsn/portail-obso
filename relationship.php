<?php
    require 'variable-db.php';
    require 'functions.php';
    $theme="Bootstrap4";
    $var_parent = isset($_POST['var_parent']) ? $_POST['var_parent'] : null;
    $var_parent_install_status = isset($_POST['var_parent_install_status']) ? $_POST['var_parent_install_status'] : null;
    $var_parent_sys_class_name = isset($_POST['var_parent_sys_class_name']) ? $_POST['var_parent_sys_class_name'] : null;
    $var_type = isset($_POST['var_type']) ? $_POST['var_type'] : null;
    $var_child = isset($_POST['var_child']) ? $_POST['var_child'] : null;
    $var_child_install_status = isset($_POST['var_child_install_status']) ? $_POST['var_child_install_status'] : null;
    $var_child_sys_class_name = isset($_POST['var_child_sys_class_name']) ? $_POST['var_child_sys_class_name'] : null;
    $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());
?>
<html>
<head>
	<!-- définition des caractères lié à l'affichage -->
	<meta charset="utf-8">
	<!-- Titre de la page -->
	<title>Relation entre les CI</title>
	<!-- définition des metadata de la page  -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Chargement de DataTables -->
    <link rel="stylesheet" type="text/css" href="Datatables/default/datatables.css"/>
    <link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/tableau_formulaire.css" />
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
                            var api = this.api();
                            api.$('td').dblclick
                            ( 
                                function () 
                                {
                                    api.search( this.innerHTML ).draw();
                                } 
                            );
                            this.api().columns().every( function () 
                            {
                                
                                var column = this;
                                var select = $('<select><option value=""></option></select>')
                                    .appendTo( $(column.footer()).empty() )
                                    .on( 'change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );
                
                                        column
                                            .search( val ? '^'+val+'$' : '', true, false )
                                            .draw();
                                    } );
                
                                column.data().unique().sort().each( function ( d, j ) 
                                {
                                    select.append( '<option value="'+d+'">'+d+'</option>' )
                                } );
                                
                            }
                             )
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
</head>
	<body>
        <script src="js/pace.min.js"></script>
        <center><H1>Tableau des relations</H1></center>
        <br/>
        <form id="requete" class="form-horizontal" method="POST" enctype="multipart/form-data" action="relationship.php">
            <center>
                <div class="divTable paleBlueRows">
                    <div class="divTableHeading">
                        <div class="divTableRow">
                            <div class="divTableHead">parent</div>
                            <div class="divTableHead">parent.install_status</div>
                            <div class="divTableHead">parent.sys_class_name</div>
                            <div class="divTableHead">type</div>
                            <div class="divTableHead">child</div>
                            <div class="divTableHead">child.install_status</div>
                            <div class="divTableHead">child.sys_class_name</div>
                        </div>
                    </div>
                    <div class="divTableBody">
                        <div class="divTableRow">
                            <div class="divTableCell">
                                        <input list="list_parent" name="var_parent" size="30" maxlength="30" onclick="this.value=''" value="<?php if ($var_parent != '') { echo $var_parent;} ?>" >
                                        <datalist id="list_parent">
                                            <?php
                                                
                                                $querytable = "select distinct `parent` from cmdb.relationship where `parent.install_status` <> 'ARCHIVED' order by `parent`;";
                                                if ($stmt = $con->prepare($querytable)) 
                                                {
                                                    $stmt->execute();
                                                    while ($resulttable = $stmt->fetch()) 
                                                    {
                                                        if ($var_parent == $resulttable[0]) 
                                                        {
                                                            echo '<option value="'.$resulttable[0].'" selected >'.$resulttable[0].'</option>';
                                                        } else {
                                                            echo '<option value="'.$resulttable[0].'" >'.$resulttable[0].'</option>';
                                                        }

                                                        $stmt->pdo = null;
                                                    }
                                                }

                                            ?>
                                        </datalist>
                                    
                            </div>
                            <div class="divTableCell">
                                <input list="list_parent_install_status" name="var_parent_install_status" size="30" maxlength="30" onclick="this.value=''" value="<?php if ($var_parent_install_status != '') { echo $var_parent_install_status;} ?>" >
                                    <datalist id="list_parent_install_status">
                                        <?php
                                            
                                            $querytable = "select distinct `parent.install_status` from cmdb.relationship where `parent.install_status` <> 'ARCHIVED' order by `parent.install_status`;";
                                            if ($stmt = $con->prepare($querytable)) 
                                            {
                                                $stmt->execute();
                                                while ($resulttable = $stmt->fetch()) 
                                                {
                                                    if ($var_parent == $resulttable[0]) 
                                                    {
                                                        echo '<option value="'.$resulttable[0].'" selected >'.$resulttable[0].'</option>';
                                                    } else {
                                                        echo '<option value="'.$resulttable[0].'" >'.$resulttable[0].'</option>';
                                                    }

                                                    $stmt->pdo = null;
                                                }
                                            }

                                        ?>
                                    </datalist>
                            </div>
                            <div class="divTableCell">
                                <input list="list_parent_sys_class_name" name="var_parent_sys_class_name" size="30" maxlength="30" onclick="this.value=''" value="<?php if ($var_parent_sys_class_name != '') { echo $var_parent_sys_class_name;} ?>" >
                                    <datalist id="list_parent_sys_class_name">
                                        <?php
                                            
                                            $querytable = "select distinct `parent.sys_class_name` from cmdb.relationship where `parent.install_status` <> 'ARCHIVED' order by `parent.sys_class_name`;";
                                            if ($stmt = $con->prepare($querytable)) 
                                            {
                                                $stmt->execute();
                                                while ($resulttable = $stmt->fetch()) 
                                                {
                                                    if ($var_parent == $resulttable[0]) 
                                                    {
                                                        echo '<option value="'.$resulttable[0].'" selected >'.$resulttable[0].'</option>';
                                                    } else {
                                                        echo '<option value="'.$resulttable[0].'" >'.$resulttable[0].'</option>';
                                                    }

                                                    $stmt->pdo = null;
                                                }
                                            }

                                        ?>
                                    </datalist>
                            </div>
                            <div class="divTableCell">
                                <input list="list_type" name="var_type" size="30" maxlength="30" onclick="this.value=''" value="<?php if ($var_type != '') { echo $var_type;} ?>" >
                                    <datalist id="list_type">
                                        <?php
                                            
                                            $querytable = "select distinct `type` from cmdb.relationship where `parent.install_status` <> 'ARCHIVED' order by `type`;";
                                            if ($stmt = $con->prepare($querytable)) 
                                            {
                                                $stmt->execute();
                                                while ($resulttable = $stmt->fetch()) 
                                                {
                                                    if ($var_parent == $resulttable[0]) 
                                                    {
                                                        echo '<option value="'.$resulttable[0].'" selected >'.$resulttable[0].'</option>';
                                                    } else {
                                                        echo '<option value="'.$resulttable[0].'" >'.$resulttable[0].'</option>';
                                                    }

                                                    $stmt->pdo = null;
                                                }
                                            }

                                        ?>
                                    </datalist>
                            </div>
                            <div class="divTableCell">
                                <input list="list_child" name="var_child" size="30" maxlength="30" onclick="this.value=''" value="<?php if ($var_child != '') { echo $var_child;} ?>" >
                                    <datalist id="list_child">
                                        <?php
                                            
                                            $querytable = "select distinct `child` from cmdb.relationship where `parent.install_status` <> 'ARCHIVED' order by `child`;";
                                            if ($stmt = $con->prepare($querytable)) 
                                            {
                                                $stmt->execute();
                                                while ($resulttable = $stmt->fetch()) 
                                                {
                                                    if ($var_parent == $resulttable[0]) 
                                                    {
                                                        echo '<option value="'.$resulttable[0].'" selected >'.$resulttable[0].'</option>';
                                                    } else {
                                                        echo '<option value="'.$resulttable[0].'" >'.$resulttable[0].'</option>';
                                                    }

                                                    $stmt->pdo = null;
                                                }
                                            }

                                        ?>
                                    </datalist>
                            </div>
                            <div class="divTableCell">
                                <input list="list_child_install_status" name="var_child_install_status" size="30" maxlength="30" onclick="this.value=''" value="<?php if ($var_child_install_status != '') { echo $var_child_install_status;} ?>" >
                                    <datalist id="list_child_install_status">
                                        <?php
                                            
                                            $querytable = "select distinct `child.install_status` from cmdb.relationship where `parent.install_status` <> 'ARCHIVED' order by `child.install_status`;";
                                            if ($stmt = $con->prepare($querytable)) 
                                            {
                                                $stmt->execute();
                                                while ($resulttable = $stmt->fetch()) 
                                                {
                                                    if ($var_parent == $resulttable[0]) 
                                                    {
                                                        echo '<option value="'.$resulttable[0].'" selected >'.$resulttable[0].'</option>';
                                                    } else {
                                                        echo '<option value="'.$resulttable[0].'" >'.$resulttable[0].'</option>';
                                                    }

                                                    $stmt->pdo = null;
                                                }
                                            }

                                        ?>
                                    </datalist>
                            </div>
                            <div class="divTableCell">
                                <input list="list_child_sys_class_name" name="var_child_sys_class_name" size="30" maxlength="30" onclick="this.value=''" value="<?php if ($var_child_sys_class_name != '') { echo $var_child_sys_class_name;} ?>" >
                                    <datalist id="list_child_sys_class_name">
                                        <?php
                                            
                                            $querytable = "select distinct `child.sys_class_name` from cmdb.relationship where `parent.install_status` <> 'ARCHIVED' order by `child.sys_class_name`;";
                                            if ($stmt = $con->prepare($querytable)) 
                                            {
                                                $stmt->execute();
                                                while ($resulttable = $stmt->fetch()) 
                                                {
                                                    if ($var_parent == $resulttable[0]) 
                                                    {
                                                        echo '<option value="'.$resulttable[0].'" selected >'.$resulttable[0].'</option>';
                                                    } else {
                                                        echo '<option value="'.$resulttable[0].'" >'.$resulttable[0].'</option>';
                                                    }

                                                    $stmt->pdo = null;
                                                }
                                            }

                                        ?>
                                    </datalist>
                            </div>
                           
                        </div>
                        
                    </div>
                    
                </div>
                 <center><input type="submit" class="btn"></center>
            </center>
        </form>
        <div class="container">
            <?php
                $query="select * from cmdb.relationship where";
                $flag_data=0;

                if($var_parent!=null)
                {
                    $flag_data=1;
                    $query=$query." `parent` like '".$var_parent."%' and";
                }
                
                if($var_parent_install_status!=null)
                {
                    $flag_data=1;
                    $query=$query." `parent.install_status` like '".$var_parent_install_status."%' and";
                }

                if($var_parent_sys_class_name!=null)
                {
                    $flag_data=1;
                    $query=$query." `parent.sys_class_name` like '".$var_parent_sys_class_name."%' and";
                }

                if($var_type!=null)
                {
                    $flag_data=1;
                    $query=$query." `type` like '".$var_type."%' and";
                }
                else
                
                if($var_child!=null)
                {
                    $flag_data=1;
                    $query=$query." `child` like '".$var_child."%' and";
                }
                
                if($var_child_install_status!=null)
                {
                    $flag_data=1;
                    $query=$query." `child.install_status` like '".$var_child_install_status."%' and";
                }
                if($var_child_sys_class_name!=null)
                {
                    $flag_data=1;
                    $query=$query." `child.sys_class_name` like '".$var_child_sys_class_name."%' and";
                }
                
                $query=$query . ';';
                $query=str_replace(" and;",";",$query);

                if ($flag_data==1)
                {                    
                    if ($stmt = $con->prepare($query)) 
                    {
                        $stmt->execute();
                        $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($tuples)) 
                        {
                            $columns_names = array_keys($tuples[0]); 
                            echo "<table id='' class='display' style='width: 95%;'>";
                            echo "<thead>";
                            echo "<tr>";
                            foreach ($columns_names as $col) 
                            {
                                echo '<th>'.$col.'</th>';
                            } 
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            foreach ($tuples as $tuple) 
                            {
                                echo '<tr>';
                                foreach ($tuple as $entete => $col) 
                                {
                                    switch ($entete)
                                    {
                                        case "child":
                                            echo "<td><form id=\"".$col."\" method=\"POST\" action=\"fiche_machine.php\"><input type=\"hidden\" name=\"machine\" value=\"".$col."\"/> </form><a href='#' onclick='document.getElementById(\"$col\").submit()' ><b>".$col."</b></a></td>";
                                            break;
                                        case "parent":
                                            echo "<td><form id=\"".$col."\" method=\"POST\" action=\"components_detail.php\"><input type=\"hidden\" name=\"var_consult_component\" value=\"true\"/><input type=\"hidden\" name=\"var_logical_CI\" value=\"".$col."\"/> </form><a href='#' onclick='document.getElementById(\"$col\").submit()' ><b>".$col."</b></a></td>";
                                            break;
                                        default:
                                            echo '<td>'.$col.'</td>';
                                            break;
                                    }
                                }
                                echo '</tr>';
                            }           
                            echo "</tbody>";
                            echo "</table>";
                        } 
                        else 
                        {
                            echo 'Pas de résultat';
                        }
                        $stmt->pdo = null;
                    }
                }
        ?>
        </div>

    </body>
</html>
<?php
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
