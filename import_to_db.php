<html>

<head>
	<title>IMPORT CSV to MYSQL DB</title>
	<link rel="stylesheet" type="text/css" href="stylesheet/css/bootstrap.min.css" />
</head>

<body>
	<br>
	<center>
		<h1 class="jumbotron"> IMPORT CSV to MYSQL DB </h1>
	</center>
	<center>
		<p> Ce script PHP permet d'importer un fichier csv de n'importe quel format (nombre de colonne) dans une table
			sur une base de données</p>
	</center>
	<center>
		<h3> Instructions </h3>
		1. Le nom du fichier csv qui sera choisi sera le nom de la table qui va être créée. <br>
		2. le nom du fichier qui va être importé ne doit pas comporté de caractère accent, de controle et d'espace. <br>
		3. les séparateurs de données dans le fichier ".csv" doivent être obligatoirement le caractéres ";"
	</center>
	<center>
		<h3> Attention </h3>
		La table résultante de cette importation aura tout les colonne au format varchar(255).<br>
		Si vous souhaitez d'autres type de colonne, adressez-vous à un administrateur de base de données
	</center>
	</br>

	<form class="form-horizontal" action="import_csv_to_mysql.php" enctype="multipart/form-data" method="post">
		<center>
			<div>
				<label for="csvfile" class="control-label col-xs-2">Nom du fichier (de type csv)</label>
				<div class="col-xs-3">
					<input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
					<center><input type="file" class="form-control" name="csv" id="csv"></center>
				</div>
			</div>
		</center>
		<center>
			<div>
				<label for="login" class="control-label col-xs-2"></label>
				<div class="col-xs-3">
					<button type="submit" class="btn btn-primary">Upload</button>
				</div>
			</div>
		</center>
	</form>
	</div>

</body>

<?php
    // debug de variables
    // echo "<p class=\"debug\">";
    // error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

    // $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
    // $var_ignore=array("GLOBALS", "_ENV", "_SERVER"); // Détermine les var à ne pas afficher
    // echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
    // $nom_fonction=__FUNCTION__;
    // if (isset($nom_fonction)&&$nom_fonction!="") {
      // echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
    // }
    // foreach ($variables as $key=>$value)
    // {
      // if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
      // {
        // echo "<pre class=\"debug\">";
        // echo ("$".$key." => ");
        // print_r($value);
        // echo "</pre>\n";
      // }
    // }
// if(isset($_POST['username'])&&isset($_POST['mysql'])&&isset($_POST['db'])&&isset($_POST['username']))
// {
    // $sqlname=$_POST['mysql'];
    // $username=$_POST['username'];
    // $table=$_POST['table'];
    // if(isset($_POST['password']))
    // {
        // $password=$_POST['password'];
    // }
    // else
    // {
        // $password= '';
    // }
    // $db=$_POST['db'];
    // $file=$_POST['csv'];
    // $cons= mysqli_connect("$sqlname", "$username","$password","$db") or die(mysql_error());
    // $result1=mysqli_query($cons,"select count(*) count from $table");
    // $r1=mysqli_fetch_array($result1);
    // $count1=(int)$r1['count'];
    // If the fields in CSV are not seperated by comma(,)  replace comma(,) in the below query with that  delimiting character
    // If each tuple in CSV are not seperated by new line.  replace \n in the below query  the delimiting character which seperates two tuples in csv
    // for more information about the query http://dev.mysql.com/doc/refman/5.1/en/load-data.html
    // mysqli_query($cons, '
        // LOAD DATA LOCAL INFILE "'.$file.'"
            // INTO TABLE '.$table.'
            // FIELDS TERMINATED by \',\'
            // LINES TERMINATED BY \'\n\'
    // ')or die(mysql_error());
    // $result2=mysqli_query($cons,"select count(*) count from $table");
    // $r2=mysqli_fetch_array($result2);
    // $count2=(int)$r2['count'];
    // $count=$count2-$count1;
    // if($count>0)
    // echo "Success";
    // echo "<b> total $count records have been added to the table $table </b> ";
// }
// else
// {
    // echo "Mysql Server address/Host name ,Username , Database name ,Table name , File name are the Mandatory Fields";
// }
?>

</html>