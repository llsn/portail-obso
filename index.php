<html>

<head>
   <title>Exploitation de la CMDB</title>
   <link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
   <link rel="stylesheet" type="text/css" href="css/menu.css" />
</head>

<body>
   <div>
      <p>
         <h1>
            <center>Bienvenue sur la page d'exploitation de la CMDB</center>
         </h1>
      </p>
   </div>
   
   <div>
      <center>
         <ul id="menu">
            <li><a href="#">Gestion de la base de données</a>
               <ul>
                  <li><a href="inventory.php" target="_blank">Consulter les tables de la CMDB</a></li>
                  <li><a href="import_to_db.php" target="_blank">Créer une table et importer des données dans la CMDB</a></li>
                  <li><a href="import_cmdb.php" target="_blank">procédure d'importation de la CMDB dans la base d'exploitation de la CMDB</a></li>
                  <li><a href="referentiel_support.php" target="_blank">Gestion du Référentiel Support</a></li>
               </ul>
            </li>
<!--            <li><a href="http://10.1.84.101:3000/" target="_blank">Metabase</a></li> -->
            <li><a href="http://metabase-portail-obso-portail-obsolescence.fpaas-pre.cld.cma-cgm.com/" target="_blank">Metabase</a></li>
            <li><a href="#">Consultations de données</a>
               <ul>
                  <li><a href="taux_obso_by_app.html" target="_blank">Taux obsolescence par application</a></li>
                  <li><a href="taux_obso_by_app_critic.html" target="_blank">Taux obsolescence par application CRITIQUE</a></li>
                  <li><a href="taux_obso_by_os.php" target="_blank">Listes des application avec un OS obsolète</a></li>
                  <li><a href="search_servers_by_appli.php" target="_blank">Recherche des OS par Application</a></li>
                  <li><a href="fiche_machine.php" target="_blank">Fiche descriptive de machine</a></li>
               </ul>
            </li>
            <li><a href="#">DECOMMISSIONNEMENTS</a>
               <ul>
                  <li><a href="PGMP.php" target="_blank">PGMP INFO</a></li>
                  <li><a href="secteur_decom.php" target="_blank">Décommissionnement</a></li>
                  <li><a href="archived_servers.php" target="_blank">Serveurs Archivés</a></li>
               </ul>
            <li><a href="#">Détails Techniques</a>
               <ul>
                  <li><a href="relationship.php" target="_blank">Relation CI</a></li>
                  <li><a href="components_detail.php" target="_blank">Détails des composants par appli</a></li>
                  <li><a href="virtual_by_hardware.php" target="_blank">Hardware -> Virtual</a></li>
                  <li><a href="dependance_hardware.php" target="_blank">Virtual -> Hardware</a></li>
               </ul>
            </li>
            <li><a href="secteur_projet.php" target="_blank">Projets</a></li>
         </ul> 
      </center>
   </div>
  
   <p>
      <div>
         <iframe src="http://metabase-portail-obso-portail-obsolescence.fpaas-pre.cld.cma-cgm.com/public/dashboard/c805f193-21de-4396-bd3e-16166cbe668d" frameborder="0"
            width="100%" height="100%" allowtransparency></iframe>
      </div>
   </p>
</body>

</html>
