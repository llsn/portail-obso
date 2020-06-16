<html>

<head>
	<TITLE>Liste de référence des tables et vue de la table d'exploitation de la CMDB</TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="style.css" media="all" type="text/css" />
</head>

<body>
	<center>
		<p>Vous trouverez ci-dessous la liste des tables et vues utilisés par le portail avec leurs descriptifs</p>

		<table class="blueTable">
			<thead>
				<tr>
					<TH>Type(Table ou Vue)</TH>
					<TH>Nom</TH>
					<TH>DESCRIPTIF</TH>
					<TH>DEPENDANCE(S)</TH>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Vue</td>
					<td>aix_obso_horsprd_horspre</td>
					<td>Requête faisant ressortir les OS AIX OBSOLETE dans les environnements <b>HORS-PRODUCTION et
							HORS-PREPRODUCTION</b></td>
					<td>system_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>aix_obso_PRODUCTION_pre</td>
					<td>Requête faisant ressortir les OS AIX OBSOLETE dans les environnements <b>PRODUCTION et
							PREPRODUCTION</b></td>
					<td>system_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>compte_cuml_os+bd_others_obso</td>
					<td>Compte le nombre d'OS et de base de données OBSOLETE dans les environnements
						<b>HORS-PRODUCTION</b></td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>compte_cumul_os+bd_prd_obso</td>
					<td>Compte le nombre d'OS et de base de données OBSOLETE dans les environnements de
						<b>PRODUCTION</b></td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>compte_cumul_os+bd_pre_obso</td>
					<td>Compte le nombre d'OS et de base de données OBSOLETE dans les environnements de
						<b>PREPRODUCTION</b></td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>count_all_db_inventory</td>
					<td>Liste le nombre d'instance de base de données par Version, Edition et Envrionnement</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>db_in_extended_support</td>
					<td>Liste les middleware en <b>Extended Support</b> par rapport à la date du jour</td>
					<td>os_support_eos_eol</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>db_in_standard_support</td>
					<td>Liste les middleware en <b>Standard Support</b> par rapport à la date du jour</td>
					<td>os_support_eos_eol</td>
				</tr>
				<tr>
					<td>Table</td>
					<td>db_inventory</td>
					<td><b>Table Référence</b> - Données de toutes les instances de base de données relevées par TADDM
						dans la CMDB IBM</td>
					<td><b>Origine des données:<br>CMDB</b></td>
				</tr>
				<tr>
					<td>Table</td>
					<td>db_inventory_YYYYMMDD</td>
					<td><b>Table<u>s</u> ARCHIVE</b> - Données de toutes les instances de base de données relevées par
						TADDM dans la CMDB IBM</td>
					<td><b>Origine des données:<br>CMDB</b></td>
				</tr>
				<tr>
					<td>Table</td>
					<td>db_inventory_avant</td>
					<td><b>Table Référence Secondaire</b> - Données de toutes les instances de base de données relevées
						par TADDM dans la CMDB IBM</td>
					<td><b>Origine des données:<br>CMDB</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>db_obsolete</td>
					<td>Liste les middleware en <b>HORS Support</b> par rapport à la date du jour</td>
					<td>os_support_eos_eol</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>db_unbuild</td>
					<td>Liste les bases de données deconstruite par rapport àl la préiode précédente</td>
					<td><b>db_inventory<br>db_inventory_avant</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>global_inventory</td>
					<td>Requete regroupant l'ensemble des tables de référence</td>
					<td><b>system_inventory<br>db_inventory<br>middleware_inventory</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>global_inventory_avant</td>
					<td>Requete regroupant l'ensemble des tables de référence secondaire</td>
					<td><b>system_inventory_avant<br>db_inventory_avant<br>middleware_inventory_avant</b></td>
				</tr>
				<tr>
					<td>Table</td>
					<td>ibm_aix_servers_inventory_YYYYMM</td>
					<td>Données des inventaires fournies par les équipes d'HOCINE pour la partie AIX</td>
					<td><b>-</b></td>
				</tr>
				<tr>
					<td>Table</td>
					<td>ibm_linux_servers_inventory_YYYYMM</td>
					<td>Données des inventaires fournies par les équipes d'HOCINE pour la partie Linux</td>
					<td><b>-</b></td>
				</tr>
				<tr>
					<td>Table</td>
					<td>ibm_windows_servers_inventory_YYYYMM</td>
					<td>Données des inventaires fournies par les équipes d'HOCINE pour la partie Windows</td>
					<td><b>-</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_all_type_of_db</td>
					<td>Liste de toutes les type de bases de données sur tous les environnements</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_fully_obso_db_by_env</td>
					<td>Liste le nombre de bases de données OBSOLETE par Envrionnement</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_fully_obso_os_by_env</td>
					<td>Liste le nombre d'OS OBSOLETE par Envrionnement</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_obso_db_only</td>
					<td>Liste des bases de données OBSOLETE uniquement (HORS OS)</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_and_db_in_extended_support_others</td>
					<td>Liste des OS et des bases de données en <b>extended support</b> (HORS-PRODUCTION et
						PREPRODUCTION)</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_and_db_in_extended_support_pre</td>
					<td>Liste des OS et des bases de données en <b>extended support</b> en PREPRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_and_db_in_extended_support_PRODUCTION</td>
					<td>Liste des OS et des bases de données en <b>extended support</b> en PRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_and_db_in_standard_support_others</td>
					<td>Liste des OS et des bases de données en <b>standard support</b> (HORS-PRODUCTION et
						PREPRODUCTION)</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_and_db_in_standard_support_pre</td>
					<td>Liste des OS et des bases de données en <b>standard support</b> en PREPRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_and_db_in_standard_support_PRODUCTION</td>
					<td>Liste des OS et des bases de données en <b>standard support</b> en PRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_and_db_obso_others</td>
					<td>Liste des OS et des bases de données <b>OBSOLETE</b> (HORS-PRODUCTION et PREPRODUCTION)</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_and_db_obso_pre</td>
					<td>Liste des OS et des bases de données <b>OBSOLETE</b> en PREPRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_and_db_obso_PRODUCTION</td>
					<td>Liste des OS et des bases de données <b>OBSOLETE</b> en PRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_extended_support_others</td>
					<td>Liste des OS en <b>extended support</b> (HORS-PRODUCTION et PREPRODUCTION)</td>
					<td><b>global_inventory<br>os_in_extended_support</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_extended_support_pre</td>
					<td>Liste des OS en <b>extended support</b> en PREPRODUCTION</td>
					<td><b>global_inventory<br>os_in_extended_support</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_extended_support_PRODUCTION</td>
					<td>Liste des OS en <b>extended support</b> en PRODUCTION</td>
					<td><b>global_inventory<br>os_in_extended_support</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_extended_support_and_db_in_standard_support_others</td>
					<td>Liste des OS en <b>extended support</b> et des bases de données en <b>stantard support</b>
						(HORS-PRODUCTION et PREPRODUCTION)</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_extended_support_and_db_in_standard_support_pre</td>
					<td>Liste des OS en <b>extended support</b> et des bases de données en <b>stantard support</b> en
						PREPRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_extended_support_and_db_in_standard_support_PRODUCTION</td>
					<td>Liste des OS en <b>extended support</b> et des bases de données en <b>stantard support</b> en
						PRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_extended_support_and_db_obso_others</td>
					<td>Liste des OS en <b>extended support</b> et des bases de données <b>OBSOLETE</b> (HORS-PRODUCTION
						et PREPRODUCTION)</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_extended_support_and_db_obso_pre</td>
					<td>Liste des OS en <b>extended support</b> et des bases de données <b>OBSOLETE</b> en PREPRODUCTION
					</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_extended_support_and_db_obso_PRODUCTION</td>
					<td>Liste des OS en <b>extended support</b> et des bases de données <b>OBSOLETE</b> en PRODUCTION
					</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_standard_support_and_db_in_extended_support_others</td>
					<td>Liste des OS en <b>standard support</b>et des bases de données en <b>extended support</b>
						(HORS-PRODUCTION et PREPRODUCTION)</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_standard_support_and_db_in_extended_support_pre</td>
					<td>Liste des OS en <b>standard support</b>et des bases de données en <b>extended support</b> en
						PREPRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_standard_support_and_db_in_extended_support_PRODUCTION</td>
					<td>Liste des OS en <b>standard support</b>et des bases de données en <b>extended support</b> en
						PRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_standard_support_and_db_obso_others</td>
					<td>Liste des OS et des bases de données en <b>standard support</b> (HORS-PRODUCTION et
						PREPRODUCTION)</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_standard_support_and_db_obso_pre</td>
					<td>Liste des OS et des bases de données en <b>standard support</b> en PREPRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_in_standard_support_and_db_obso_PRODUCTION</td>
					<td>Liste des OS et des bases de données en <b>standard support</b> en PRODUCTION</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_obso_and_db_in_extended_support_others</td>
					<td>Liste des OS <b>OBSOLETE</b> et des bases de données en <b>extended support</b> (HORS-PRODUCTION
						et PREPRODUCTION)</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_obso_and_db_in_extended_support_pre</td>
					<td>Liste des OS <b>OBSOLETE</b> et des bases de données en <b>extended support</b> en PREPRODUCTION
					</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_obso_and_db_in_extended_support_PRODUCTION</td>
					<td>Liste des OS <b>OBSOLETE</b> et des bases de données en <b>extended support</b> en PRODUCTION
					</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_obso_and_db_in_standard_support_others</td>
					<td>Liste des OS <b>OBSOLETE</b> et des bases de données en <b>extended support</b> (HORS-PRODUCTION
						et PREPRODUCTION)</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_obso_and_db_in_standard_support_pre</td>
					<td>Liste des OS <b>OBSOLETE</b> et des bases de données en <b>extended support</b> en PREPRODUCTION
					</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_obso_and_db_in_standard_support_PRODUCTION</td>
					<td>Liste des OS <b>OBSOLETE</b> et des bases de données en <b>extended support</b> en PRODUCTION
					</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_obsolete_others</td>
					<td>Liste des OS <b>OBSOLETE</b> uniquement (HORS-PRODUCTION et PREPRODUCTION)</td>
					<td><b>global_inventory<br>os_obsolete</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_obsolete_pre</td>
					<td>Liste des OS <b>OBSOLETE</b> uniquement en PREPRODUCTION</td>
					<td><b>global_inventory<br>os_obsolete</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_obsolote_PRODUCTION</td>
					<td>Liste des OS <b>OBSOLETE</b> uniquement en PRODUCTION</td>
					<td><b>global_inventory<br>os_obsolete</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_standard_support_others</td>
					<td>Liste des OS <b>Standard support</b> uniquement (HORS-PRODUCTION et PREPRODUCTION)</td>
					<td><b>global_inventory<br>os_in_standard_support</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_standard_support_pre</td>
					<td>Liste des OS <b>Standard support</b> uniquement en PREPRODUCTION</td>
					<td><b>global_inventory<br>os_in_standard_support</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_standard_support_PRODUCTION</td>
					<td>Liste des OS <b>Standard support</b> uniquement en PRODUCTION</td>
					<td><b>global_inventory<br>os_in_standard_support</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_os_updated_last_month</td>
					<td>Liste des machines mise à jour entre <b>la période précédente et aujourd'hui</b> tout
						environnements confondus</td>
					<td><b>system_inventory<br>system_inventory_avant</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>list_server_wo_os_version</td>
					<td>Liste des serveurs n'ayant aucune version d'indiqué dans la CMDB</td>
					<td>system_inventory</td>
				</tr>
				<tr>
					<td>Table</td>
					<td>middleware_inventory</td>
					<td><b>Table Référence</b> - Données de toutes les applications relevées par TADDM dans la CMDB IBM
					</td>
					<td><b>Origine des données:<br>CMDB</b></td>
				</tr>
				<tr>
					<td>Table</td>
					<td>middleware_inventory_YYYYMMDD</td>
					<td><b>Table<u>s</u> ARCHIVE</b> - Données de toutes les applications relevées par TADDM dans la
						CMDB IBM</td>
					<td><b>Origine des données:<br>CMDB</b></td>
				</tr>
				<tr>
					<td>Table</td>
					<td>middleware_inventory_avant</td>
					<td><b>Table Référence Secondaire</b> - Données de toutes les applications relevées par TADDM dans
						la CMDB IBM</td>
					<td><b>Origine des données:<br>CMDB</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>nb_os_version_by_env</td>
					<td>Liste du nombre de version OS par environnement</td>
					<td>system_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>os_in_extended_support</td>
					<td>Liste du nombre de version OS en <b>extended support</b> tout environnements confondus</td>
					<td>os_support_eos_eol</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>os_in_standard_support</td>
					<td>Liste du nombre de version OS en <b>standard support</b> tout environnements confondus</td>
					<td>os_support_eos_eol</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>os_obsolete</td>
					<td>Liste du nombre de version OS <b>OBSOLETE</b> tout environnements confondus</td>
					<td>os_support_eos_eol</td>
				</tr>
				<tr>
					<td>Table</td>
					<td>os_support_eos_eol</td>
					<td>liste de référence répertoriant tous les OS, base de données ou applications <br>avec leur dates
						d'expirations de support ( standard ou extended)</td>
					<td>Données recehrché sur internet<br>et saisie à la main dans le fichier excel<br><a
							href="reference/Support_EOL-EOS.xlsx">Support_EOL-EOS.xlsx</a></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>partial_inventory_os+db</td>
					<td>Requete regroupant l'ensemble des tables de référence excepté les middlewares</td>
					<td><b>system_inventory<br>db_inventory</b></td>
				</tr>
				<tr>
					<td>Table</td>
					<td>system_inventory</td>
					<td><b>Table Référence</b> - Données de tous les serveurs relevés par TADDM dans la CMDB IBM</td>
					<td><b>Origine des données:<br>CMDB</b></td>
				</tr>
				<tr>
					<td>Table</td>
					<td>system_inventory_YYYYMMDD</td>
					<td><b>Table<u>s</u> ARCHIVE</b> - Données de tous les serveurs relevés par TADDM dans la CMDB IBM
					</td>
					<td><b>Origine des données:<br>CMDB</b></td>
				</tr>
				<tr>
					<td>Table</td>
					<td>system_inventory_avant</td>
					<td><b>Table Référence Secondaire</b> - Données de tous les serveurs relevés par TADDM dans la CMDB
						IBM</td>
					<td><b>Origine des données:<br>CMDB</b></td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>tableau_cumul_os+bd_all_env</td>
					<td>Tableau faisant un cumul, tout environnements confondus, de toutes les entités (que ce soit OS
						ou base de données) où:<br>
						<blockquote>-les serveurs ou les bases de données sont <b>OBSOLETE</b><br>-les serveurs ou les
							bases de données sont <b>PROCHE de OBSOLESCENCE</b><br>-les serveurs ou les bases de données
							sont <b>à jour</b></blockquote>
					</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>tableau_inventaire_obso</td>
					<td>Tableau faisant un cumul, tout environnements confondus, de tout les cas d'obsolescence
						possible.</blockquote>
					</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>tableau_inventaire_others</td>
					<td>Tableau faisant un cumul, HORS-PRODUCTION et HORS PREPRODUCTION, de toutes les entités (que ce
						soit OS ou base de données) où:<br>
						<blockquote>-les serveurs ou les bases de données sont <b>OBSOLETE</b><br>-les serveurs ou les
							bases de données sont <b>PROCHE de OBSOLESCENCE</b><br>-les serveurs ou les bases de données
							sont <b>à jour</b></blockquote>
					</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>tableau_inventaire_pre</td>
					<td>Tableau faisant un cumul, en PREPRODUCTION, de toutes les entités (que ce soit OS ou base de
						données) où:<br>
						<blockquote>-les serveurs ou les bases de données sont <b>OBSOLETE</b><br>-les serveurs ou les
							bases de données sont <b>PROCHE de OBSOLESCENCE</b><br>-les serveurs ou les bases de données
							sont <b>à jour</b></blockquote>
					</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>tableau_inventaire_PRODUCTION</td>
					<td>Tableau faisant un cumul, en PRODUCTION, de toutes les entités (que ce soit OS ou base de
						données) où:<br>
						<blockquote>-les serveurs ou les bases de données sont <b>OBSOLETE</b><br>-les serveurs ou les
							bases de données sont <b>PROCHE de OBSOLESCENCE</b><br>-les serveurs ou les bases de données
							sont <b>à jour</b></blockquote>
					</td>
					<td>global_inventory</td>
				</tr>
				<tr>
					<td>Vue</td>
					<td>tableau_obso_all_env</td>
					<td>Tableau faisant le total par typee d'OS et par envrionnement des OS OBSOLETE</td>
					<td>system_inventory</td>
				</tr>
			</tbody>
		</table>
	</center>
</body>

</html>