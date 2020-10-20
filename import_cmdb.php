<html>

<head>
	<title>Procédure d'importation de données dans la CMDB</title>
	<link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
	<script type="text/javascript">
		function traitement() {
			//Traitement à effectuer sur la page
			setTimeout(suiteTraitement, 2000) //Attendez 2 secondes avant de continuer dans la fonction suivante
		}

		function suiteTraitement() {
			window.location.reload();
		}
	</script>
</head>

<body>
	<center>
		<H1 class="jumbotron"> Procédure d'importation de données dans la CMDB </H1>
	</center>
	<H2 class="jumbotron">PRINCIPE</H2>
	<P><br>Chaque semaine IBM doit nous transmettre par le biais du site IBM BOX les extracts de la CMDB.<br>
		à partir de ces extracts, nous allons pouvoir croiser les données afinde faire ressortir des informations
		pertinantes sur, par exemple:
		<blockquote>- les machines mis à jour depuis la semaine précedente<br>
			- vérifier que de nom de machines déjà utilisés ne soit réutilisée<br>
			- déterminé le nombre de machines obsolètes sur chacun de environnements<br>
			- retracer les évolutions de chaque machines dans le temps<br></blockquote>
		...
	</p>
	<H2 class="jumbotron">L'ARCHITECTURE</H2>
	<u>
		<h3 class="jumbotron"> la base de données</h3>
	</u>
	<p>
		L'architecture utilisé pour l'exploitation des données est une base de données MySQL V8.
		Cette base de données hébergent:
		<blockquote class="jumbotron">- les tables de données <br>
			- les vues permettant de croiser les données <br>
			- des procédure stockée utilisé pour la maintenance de la base de données</blockquote>
		La gestion des utilisateurs de cette base de données est basique puisque seuls 2 utilisateurs sont déclarés:<br>
		<blockquote class="jumbotron">- root (servant à la mise à jour de la base de données)<br>
			- service (servant à l'interrogation de la base de données)<br></blockquote>

	</p>
	<u>
		<h3 class="jumbotron">Metabase</h3>
	</u>
	<i>
		<h4 class="jumbotron"> Présentation de Metabase</h4>
	</i>
	<p>
		Metabase est un outils gratuit permettant de générer des tableaux dynamiques croisés et des graphiques en
		s'appuyant sur une base de données interne à Metabase.<br>
		Cette base de donnée est synchronisé automatiquement (1 soit par heure) par l'outils lui-même.
		Il y a toutefois possibilité de forcer la synchronisation directement dans metabase dans la partie
		administrateur.
	</p>
	<i>
		<h4 class="jumbotron"> Utilisation de Metabase </h4>
	</i>
	<p>
		Une documentation (en anglais) existe sur internet. Voici le lien : <br><a
			href="https://metabase.com/docs/v0.32.9/" target="_blank">Documentation en ligne </a><br>
		La gestion des utilisateurs de cette application est local puisque seuls 2 utilisateurs sont déclarés:<br>
		<blockquote class="jumbotron">- administrateur@localhost.local (servant à l'administration de l'application)<br>
			- service@localhost.local (servant à l'exploitation de l'application)<br></blockquote>
		chaque utilisateur peut créer des vues et les partager avec les autres utilisateurs de metabase.<br>
		L'utilisation et la création de vue sont conviviales et faciles d'utilisation avec un peu de pratique.<br>
	</p>
	<u>
		<h3 class="jumbotron">Keepass</h3>
	</u>
	<i>
		<h4 class="jumbotron"> Présentation de Keepass</h4>
	</i><br>
	<p>
		Keepass est un outils permttant de centraliser et de conserver en sécurité les mots de passe de outils utilisés
		pour le site d'OBSOLESCENCE.<br>
		L'outils est stocké dans le répertoire "Keepass" et la base de donnée contenant les mots de passe dans
		"db_keepass".<br>
		Pour pouvoir ouvrir cette base de donnée de mot de passe, une autre base de donnée s'ouvrant avec un certificat
		et un mot de passe sont nécessaire. <br>
		Ils sont délivré par le manager de l'outils.<br>

	</p>
	<h2 class="jumbotron"> importation des données dans la base d'exploitation </h2>
	<p>Toutes les semaines, les équipes d'IBM dépose l'extraction des données de la CMDB sous le lien suivant:<br>
		<a href="https://app.box.com/folder/76929452754" target="_blank"> IBM BOX</a><br>
		vous devez retrouvez sous dossier 3 fichiers nommés comme suit:<br>
		<blockquote class="jumbotron">- CMA-CMDB-SYSTEM yyyymmdd.xlsb<br>
			- CMDB-DB-INSTANCE yyyymmdd.xlsb<br>
			- CMDB-MW yyyymmdd.xlsb<br></blockquote>
		<b>(yyyymmdd représentant la date.)</b><br>
		Vous trouverez aussi un répertoire nommé "DATA RETENTION - 3 MONTHS" qui contient comme son nom l'indique<br>
		l'historique sur les 3 derniers mois des précédentements extractions.<br>
		Télécharger ces 3 fichiers sur votre poste.<br>
		Ensuite pour chaque fichier, vous allez:<br>
		<blockquote class="jumbotron">- ouvrir le fichier excel<br>
			- placez vous sur la feuille correspondant à la table de données de la CMDB et enregistrez les comme suit
			:<br><br>
			<blockquote class="jumbotron">- <b>"SYSTEM"</b> pour le fichier "CMA-CMDB-SYSTEM yyyymmdd.xlsb" a
				<b><u>"enregistré sous"</u></b> <i>system_inventory_yyyymmdd.csv</i> (csv avec séparateur ";" en UTF-8)
				<br>- <b>"DB Instance Inventory"</b> pour le fichier "CMDB-DB-INSTANCE yyyymmdd.xlsb" a
				<b><u>"enregistré sous"</u></b> <i>db_inventory_yyyymmdd.csv</i> (csv avec séparateur ";" en UTF-8)
				<br>- <b>"MDW Instance Inventory"</b> pour le fichier "CMDB-MW yyyymmdd.xlsb" a <b><u>"enregistré
						sous"</u></b> <i>middleware_inventory_yyyymmdd.csv</i> (csv avec séparateur ";" en UTF-8)
			</blockquote>
		</blockquote>

		une fois ces 3 fichiers nous allons les transformer en table pour la base de données d'exploitation.<br>

		<b>ATTENTION AU NOMMAGE DES FICHER CSV: <br>la date "YYYYMMDD" doit être identique pour tous les fichiers. Elle
			represente la date d'importation dans la base et non la date d'extraction de la CMDB.</b>
	</p>

	<p>Cliquez sur le bouton suivant:<a href="import_to_db.php" target="_blank"><button>Créer une table et importer des
				données dans la CMDB</button></a> et charger un à un les fichier e n csv précedemment créer.

		<br>Une fois cette opération terminée, nous initialiser la base pour englober ces nouvelles données dans nos
		analyses.
	</p>

	<u>
		<H3 class="jumbotron"> Initialisation de la base de données </H3>
	</u>
	<p>une fois les importations terminés, vous pourrez voir dans la page <a href="inventory.php"
			target="_blank"><button>Consulter les tables de la CMDB</button></a> 3 nouvelles tables ayant à la fin de
		leurs noms "YYYYMMDD" correspondant aux fichiers nouvellement importés.
		Maitenant, nous allons initialiser la base pour qu'une correspondent à notre semaine en cours et pour pouvoir
		faire nos analyses par la suite. <br>
		Pour cela, choisissez la date au format "yyyymmdd" correspondant aux fichiers importés la fois précédente et
		cliquez sur le bouton "intialisation_db":
		<form method="POST" action="initialisation_db.php" TARGET="_blank">
			<select name="date_table" placeholder="YYYYMMDD">
				<?php
        require 'variable-db.php';
        $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());
        $query_table = "select distinct substring_index(table_comment,'_',-1) as suffixe_date from `information_schema`.`tables` where table_schema='cmdb' and table_comment regexp ('[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]') ORDER BY suffixe_date DESC LIMIT 10000 OFFSET 1";
        // $query_table="select distinct substring_index(table_name,'_',-1) from `information_schema`.`tables` where table_name regexp ('[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]');";
        if ($stmt = $con->prepare($query_table)) {
            $stmt->execute();

            while ($result_table = $stmt->fetch()) {
                echo '<option value="'.$result_table[0].'">'.$result_table[0].'</option>';
                $stmt->pdo = null;
            }
        }

    ?>
			</select>
			<input type="submit" value="initialisation_db" name="initialisation_db" onclick="traitement();">
		</form>

		<p>Ne vous inquiétez pas, il ne s'agit que d'un renommage de table. Un retour arrière rapide peut se faire sans
			aucun problème en sélectionnant la date précédente dans la liste.</p>
		<p>Cette initialisation de base permet de définir les derniers fichiers intégrés comme tables de référence, de
			passer les tables de la fois d'avant en table secondaire de références, afin de faire les comparaison entre
			la fois fois d'avant et aujourd'hui, et d'isoler les autres tables.<br>
			Si l'on veut comparer les tablesde références de la semaines avec les tables du mois dernier, il suffit de
			sélectionner les tables du mois deriers dans la liste ci-dessus et de lancer l'initialisation.
		</p>
		<p>Vous avez terminé d'intégrer les nouvelles données dans la base. Vous pouvez clore cette procédure à présent.
		</p>
		<br>
		<P><button onclick="self.close()">Fermer la page</button></P>

	</p>
</body>

</html>