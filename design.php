<html>
    <head>
    <!-- définition des caractères lié à l'affichage -->
    <meta charset="utf-8">
    <!-- Titre de la page -->
    <title>Design : Liste d'adresse</title>
    <link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="Datatables/default/datatables.css"/>
    <link rel="stylesheet" type="text/css" href="css/perso.css"/>
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
                        "pagingType": "full_numbers",
						fixedHeader: true
                        
                    }
                     
                );
                
                
                
            }
        );
        </script>
    </head>
    <body>
        <table class="display">
            <thead>
                <tr>
                    <th>
                        nom
                    </th>
                    <th>
                        prénom
                    </th>
                    <th>
                        age
                    </th>
                    <th>
                        adresse
                    </th>
                    <th>
                        code postal
                    </th>
                    <th>
                        ville
                    </th>
                    <th>
                        téléphone fixe
                    </th>
                    <th>
                        téléphone portable
                    </th>
                    <th>
                        Adresse Email
                    </th>
                    <th>
                        Pays
                    </th>
                    <th>
                        Situation Familiale
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Lambert</td><td>Wilson</td><td>60</td><td>2 rue du tas</td><td>75001</td><td>Paris</td><td>01-85-96-32-47</td><td>06-12-58-46-79</td><td>wilson.lambert@gmail.com</td><td>France</td><td>Marié</td>
                </tr>
                <tr>    
                    <td>Glodberg</td><td>Woopy</td><td>65</td><td>3 Cameron street</td><td>25431</td><td>New-york</td><td>485-555-9632</td><td>785-555-9874</td><td>woopy.goldberg@gmail.com</td><td>Etat-Unis</td><td>Marié</td>
                </tr>
                <tr> 
                    <td>Pitt</td><td>Brad</td><td>60</td><td>16 humpton street</td><td>78654</td><td>Los Angeles</td><td>423-654-9147</td><td>785-687-8521</td><td>Brad.pitt@gmail.com</td><td>Etat-Unis</td><td>Célibataire</td>
                </tr>
                <tr>
                    <td>Binoche</td><td>juliette</td><td>51</td><td>45 rue de la taillie</td><td>75013</td><td>Paris</td><td>01-85-78-85-45</td><td>06-98-63-45-74</td><td>juliette.binoche@gmail.com</td><td>France</td><td>Pacsé</td>
                </tr>
                <tr> 
                    <td>Zindane</td><td>Zinedine</td><td>45</td><td>231 calle Arturio Soria</td><td>45216</td><td>Madrid</td><td>245-36-89-54</td><td>245-63-54-82</td><td>zinedine.zidane@gmail.com</td><td>Espagne</td><td>Marié</td>                
                </tr>
            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </body>
</html>