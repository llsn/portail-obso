<html>

<head>
   <title>Exploitation de la CMDB</title>
   <link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
</head>

<body>
   <div>
      <p>
         <h1>
            <center>Bienvenue sur la page d'exploitation de la CMDB</center>
         </h1>
      </p>
      <center>
         <div>
            <a href="inventory.php" target="_blank">
               <button class="btn btn-info">Consulter les tables de la CMDB</button>
            </a>
            <a href="import_to_db.php" target="_blank">
               <button class="btn btn-danger">Créer une table et importer des données dans la CMDB</button>
            </a>
            <a href="http://10.1.84.101:3000/" target="_blank">
               <button class="btn btn-warning">Outils metabase</button>
            </a>
            <a href="import_cmdb.php" target="_blank">
               <button class="btn btn-danger">procédure d'importation de la CMDB dans la base d'exploitation de la
                  CMDB</button>
            </a>
         </div>
         <br/>
         <div>
            <a href="taux_obso_by_app.html" target="_blank">
               <button class="btn btn-info">Taux obsolescence par application</button>
            </a>
            <a href="taux_obso_by_app_critic.html" target="_blank">
               <button class="btn btn-info">Taux obsolescence par application CRITIQUE</button>
            </a>
            <a href="fiche_machine.php" target="_blank">
               <button class="btn btn-info">Fiche descriptive de machine</button>
            </a>
            <a href="PGMP.php" target="_blank">
               <button class="btn btn-info">PGMP INFO</button>
            </a>
            <a href="relationship.php" target="_blank">
               <button class="btn btn-info">Relation CI</button>
            </a>
            <a href="components_detail.php" target="_blank">
               <button class="btn btn-info">Détails des composants par appli</button>
            </a>
         </div>
         <br/>
         <div>
            <a href="virtual_by_hardware.php" target="_blank" >
               <button class="btn btn-info">Hardware -> Virtual</button>
            </a>
            <a href="dependance_hardware.php" target="_blank">
               <button class="btn btn-info">Virtual -> Hardware</button>
            </a>
            <a href="secteur_decom.php" target="_blank">
               <button class="btn btn-info">Décommissionnement</button>
            </a>
            <a href="secteur_projet.php" target="_blank">
               <button class="btn btn-info">Projets</button>
            </a>
            <a href="referentiel_support.php" target="_blank">
               <button class="btn btn-info">Gestion du Référentiel Support</button>
            </a>
         </div>
      </center>
   </div>
   <p>
      <div>
         <iframe src="http://10.1.84.101:3000/public/dashboard/c805f193-21de-4396-bd3e-16166cbe668d" frameborder="0"
            width="100%" height="100%" allowtransparency></iframe>
      </div>
   </p>
</body>

</html>
