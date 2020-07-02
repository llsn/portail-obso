<?php
    require 'variable-db.php';
    require 'functions.php';
    $theme="Bootstrap4";
    $var_app = isset($_POST['var_app']) ? $_POST['var_app'] : null;
    $con = new PDO('mysql:host='.$host.';dbname=eam;charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());
?>
<html>
<head>
	<!-- définition des caractères lié à l'affichage -->
	<meta charset="utf-8">
	<!-- Titre de la page -->
	<title>Tableau des référants applicatifs</title>
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
        <center><H1>HOPEX - Tableau des référants applicatifs</H1></center>

        <div class="divTableCell">
            <form id="valid_app" name="valid_app" class="form-group form-group-lg" method="POST" enctype="multipart/form-data"	action="referant_applicatif.php">
                <input list="List_app" name="var_app" size="30" maxlength="30" onchange='document.getElementById("valid_app").submit()' onclick="this.value=''" value="<?php if ($var_app != '') { echo $var_app;} ?>" >
                <datalist id="List_app">
                    <?php
                        
                        $querytable = "select distinct upper(`Local name`) as `APPLICATION` from `HOPEX`.`APPLICATIONS` union select distinct upper(`Platform`) as `APPLICATION` from `HOPEX`.`APPLICATIONS` order by `APPLICATION`;";
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
            </form>
        </div>
        <div class="container">
            <?php
                $query="call HOPEX.point_of_contact_by_app('".$var_app."');";
                if ($stmt = $con->prepare($query)) 
                {
                    $stmt->execute();
                    $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($tuples)) 
                    {
                        $columns_names = array_keys($tuples[0]);
                        echo "<table id='' class='display' style='width: 100%;'>";
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
                                switch (strtoupper($entete)) 
                                {
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
            ?>
        </div>
    </body>
</html>
