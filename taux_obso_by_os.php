<?php

    require 'variable-db.php';
    require 'functions.php';
    $list_application=array();
    $OS=isset($_POST['OS']) ? $_POST['OS'] : null;
    $version=isset($_POST['version']) ? $_POST['version'] : null;
    // $STYLE = "style='border:1px solid black;border-collapse: collapse;text-align:center;'";
    // $ping_server = isset($_POST['ping_server']) ? $_POST['ping_server'] : null;
    // $exportpdf = isset($_POST['EXPORTPDF']) ? $_POST['EXPORTPDF'] : null;
    // $machine = isset($_POST['machine']) ? $_POST['machine'] : $ping_server;
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());


?>
<html>

<head>
	<title>Listes des application avec un OS obsolète</title>
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
                        "paging": false,
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
                        // lengthMenu: 
                        // [
                        //     [ 10, 25, 50, -1 ],
                        //     [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                        // ],
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
		<h4 class="jumbotron">Listes des application avec un OS obsolète</h4><br />
    </div>
    <div>
        <form id="valid" name="valid_OS" class="form-group form-group-lg" method="POST" enctype="multipart/form-data"	action="taux_obso_by_os.php"> 
            <input list='list_OS' name='OS' id='OS' width='auto' class='input-lg' onchange='document.getElementById("valid").submit()' value='<?php echo $OS?>' onclick="if(this.value!='')this.value=''">
                <datalist id="list_OS">
                <option valeur="" ></option>
                    <option valeur="AIX" >AIX</option>
                    <option valeur="LINUX" >LINUX</option>
                    <option valeur="WINDOWS" >WINDOWS</option>
                   <!-- <?php
                       /* $queryOSNAME="select distinct upper(osname) from cmdb.system_inventory where CLASSIFICATION in ('CI.AIXCOMPUTERSYSTEM','CI.LINUXCOMPUTERSYSTEM','CI.WINDOWSCOMPUTERSYSTEM') order by osname";
                        if ($stmt = $con->prepare($queryOSNAME))
                        {
                            $stmt->execute();
                            while ($resulttable = $stmt->fetch())
                            {
                                if ($OS == strtoupper($resulttable[0]))
                                {
                                    echo '<option valeur="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
                                }
                                else
                                {
                                    echo '<option valeur="'.$resulttable[0].'">'.$resulttable[0].'</option>';
                                }	
                            }
                            $stmt->pdo = null;
                        }*/
                    ?> -->
                </datalist>
                <?php
                       if($OS =='WINDOWS')
                        {
                            ?>
                <input list='list_OSVERSION' name='version' id='OS' width='auto' class='input-lg' onchange='document.getElementById("valid").submit()' value='<?php echo $version?>' onclick="if(this.value!='')this.value=''">
                <datalist id="list_OSVERSION">
                            <option valeur=""></option>
                            <option valeur="2003">2003</option>
                            <option valeur="2008">2008</option>
                </datalist>
                
               <?php
                        }
                        elseif ($OS!="")
                        {
                            //$version="";
                            ?>
                            <input list='list_OSVERSION' name='version' id='OS' width='auto' class='input-lg' onchange='document.getElementById("valid").submit()' value='<?php echo $version?>' onclick="if(this.value!='')this.value=''">
                <datalist id="list_OSVERSION">
                            <?php
                    
                            $queryOSVERSION="SELECT OSVERSION FROM cmdb.os_obsolete where OSNAME = '".$OS."' order by osversion ";
                            if ($stmt = $con->prepare($queryOSVERSION))
                            {
                                $stmt->execute();
                                while ($resulttable = $stmt->fetch())
                                {
                                    if ($OS == strtoupper($resulttable[0]))
                                    {
                                        echo '<option valeur="'.$resulttable[0].'" selected>'.$resulttable[0].'</option>';
                                    }
                                    else
                                    {
                                        echo '<option valeur="'.$resulttable[0].'">'.$resulttable[0].'</option>';
                                    }	
                                }
                                $stmt->pdo = null;
                            }
                            echo "</datalist>";
                        }
                        ?>
        </form>
    </div>
	<div class="container">
    <?php
        if($OS!="" & $version!="")
        {
            $query="call cmdb.calcul_taux_obso_by_OS('".$OS."', '".$version."');";
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
                            foreach ($tuple as $entete => $col) 
                            {
                                switch (strtoupper($entete)) 
                                {
                                    case 'LOCAL NAME':
                                        echo "<td><form id=\"".$col."\" method=\"POST\" action=\"gestion_obso_v2.php\"><input type=\"hidden\" name=\"application\" value=\"".$col."\"/></form><a href='#' onclick='document.getElementById(\"".$col."\").submit()'><b>".$col."</b></a></td>";
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
    }
?>
</div>
</body>
</html>
