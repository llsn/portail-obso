<?php

    require 'variable-db.php';
    require 'functions.php';
    $list_application=array();
    // $STYLE = "style='border:1px solid black;border-collapse: collapse;text-align:center;'";
    // $ping_server = isset($_POST['ping_server']) ? $_POST['ping_server'] : null;
    // $exportpdf = isset($_POST['EXPORTPDF']) ? $_POST['EXPORTPDF'] : null;
    // $machine = isset($_POST['machine']) ? $_POST['machine'] : $ping_server;
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());


?>
<html>

<head>
	<title>Serveurs Archivés</title>
	<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <!-- Chargement de DataTables -->
    <link rel="stylesheet" type="text/css" href="Datatables/default/datatables.css"/>
    <link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
    <script type="text/javascript" src="Datatables/default/datatables.js"></script>
    <script type="text/javascript" language="javascript" src="js/pace.min.js"></script>


	<!-- Initialisation des tableaux de données de la page -->
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
                            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                        ],
                        buttons: 
                        [
                            {
                                extend: 'print',
                                text: 'Print current page',
                                exportOptions: 
                                {
                                    modifier: 
                                    {
                                        page: 'current'
                                    }
                                }
                            },
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
                        // "initComplete": function () 
                        // {
                        //     var api = this.api();
                        //     api.$('td').click
                        //     ( 
                        //         function () 
                        //         {
                        //             api.search( this.innerHTML ).draw();
                        //         } 
                        //     );
                        // },
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
	<div style='text-align:center;'>
		<h4 class="jumbotron">Serveurs Archivés</h4><br />
    </div>
	<div class="container">
    <?php
        $query="SELECT * FROM cmdb.archived_server;";
        if ($stmt = $con->prepare($query)) {
            $stmt->execute();
            $tuples = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($tuples)) {
                $columns_names = array_keys($tuples[0]); ?>
    <table id='example' class='display' style='width: 100%;'>
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
                                case 'SERVERS NAME':
                                    $list_servers=explode(',',$col);
                                    echo '<td>';
                                    if (count($list_servers))
                                    {
                                        foreach ($list_servers as $ligne) 
                                        {
                                            echo '<form id="'.$ligne.'" method="POST" action="fiche_machine.php"><input type="hidden" name="machine" value="'.$ligne."\"/> </form><a href='#' onclick='document.getElementById(\"".$ligne."\").submit()' target=\"_blank\"><b>".$ligne.'</b></a>';
                                            echo ' ';
                                        }
                                    }
                                    echo '</td>';
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
                    } ?>
        </tbody>
        <tfoot>
            <tr>
                <?php
                        foreach ($columns_names as $col) {
                            echo '<th>'.$col.'</th>';
                        } ?>
            </tr>
        </tfoot>
    </table>
    <?php
            } else {
                echo 'Pas de résultat';
            }

            $stmt->pdo = null;
        }
?>
</div>
</body>
</html>
