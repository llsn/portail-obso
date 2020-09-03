<html>
    <head>
         <title>Portail de gestion de l'obsolescence</title>
        <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
        <link rel="stylesheet" href="css/w3.css">

        
    </head>
    <body style="background-color:#0A2A29; color:white;">
        <header style="background-color:#0A2A29; color:white;" >
            <h1 class="w3-opacity w3-center">Portail de gestion de l'obsolescence</h1>
        </header>
        <div id='menu' class="w3-bar w3-center w3-light-grey">
            <a href="#" class="w3-bar-item w3-button w3-blue">Menu</a>
            <div class="w3-dropdown-hover"> 
                <button class="w3-button w3-light-grey w3-hover-blue">Gestion de la base de données</button>
                <div class="w3-dropdown-content w3-bar-block w3-card-4">
                    <a href="inventory.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Consulter les tables de la CMDB</a>
                    <a href="import_to_db.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Créer une table et importer des données dans la CMDB</a>
                    <a href="import_cmdb.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">procédure d'importation de la CMDB dans la base d'exploitation de la CMDB</a>
                    <a href="referentiel_support.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Gestion du Référentiel Support</a>
                    <a href="ibmbox.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Récupération des fichiers de données dans la IBM Box</a>
                </div>
            </div>
            <a href="https://metabase-portail-obso-portail-obsolescence.fpaas-pre.cld.cma-cgm.com/" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Metabase</a>
            <div class="w3-dropdown-hover w3-hover-blue">
                <button class="w3-button w3-hover-blue">Consultations de données</button>
                <div class="w3-dropdown-content w3-bar-block w3-card-4">
                    <a href="taux_obso_by_app.html" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Taux obsolescence par application</a>
                    <a href="taux_obso_by_app_critic.html" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Taux obsolescence par application CRITIQUE</a>
                    <a href="taux_obso_by_os.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Listes des application avec un OS obsolète</a>
                    <a href="search_servers_by_appli.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Recherche des OS par Application</a>
                    <a href="fiche_machine.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Fiche descriptive de machine</a>
                </div>
            </div>
            <div class="w3-dropdown-hover w3-hover-blue">
                <button class="w3-button w3-hover-blue">DECOMMISSIONNEMENTS</button>
                <div class="w3-dropdown-content w3-bar-block w3-card-4">
                    <a href="PGMP.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">PGMP INFO</a>
                    <a href="secteur_decom.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Décommissionnement</a>
                    <a href="archived_servers.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Serveurs Archivés</a>
                </div>
            </div>
            <div class="w3-dropdown-hover w3-hover-blue">
                <button class="w3-button w3-hover-blue">Détails Techniques</button>
                <div class="w3-dropdown-content w3-bar-block w3-card-4">
                    <a href="relationship.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Relation CI</a>
                    <a href="components_detail.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Détails des composants par appli</a>
                    <a href="virtual_by_hardware.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Hardware -> Virtual</a>
                    <a href="dependance_hardware.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Virtual -> Hardware</a>
                </div>
            </div>
            <a href="secteur_projet.php" target="_blank" class="w3-bar-item w3-button w3-hover-blue">Projets</a>
        </div>
        <div>
            <iframe src="https://metabase-portail-obso-portail-obsolescence.fpaas-pre.cld.cma-cgm.com/public/dashboard/3bf5b0b9-5c4b-479d-8096-826600ae50a4#night" frameborder="0" width="100%" height="100%" allowtransparency></iframe>
            <!-- <iframe src="https://metabase-portail-obso-portail-obsolescence.fpaas-pre.cld.cma-cgm.com/public/dashboard/c805f193-21de-4396-bd3e-16166cbe668d" frameborder="0" width="100%" height="100%" allowtransparency></iframe> --> 
        </div>        
        <footer>
            <h6 class="w3-center w3-grey">Développé par Lionel SEVERIAN</h6>
        </footer>
    </body>
</html>