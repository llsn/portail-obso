<?php
    require 'variable-db.php';

    // fonction permet de visualiser le contenu de toutes les variables

        // debug de variables
        // echo "<p class=\"debug\">";
        // error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

        // $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
        // $var_ignore=array("GLOBALS", "_ENV", "_SERVER"); // Détermine les var à ne pas afficher
        // echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
        // $nom_fonction=__FUNCTION__;
        // if (isset($nom_fonction)&&$nom_fonction!="") {
        //   echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
        // }
        // foreach ($variables as $key=>$value)
        // {
        //   if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
        //   {
        //     echo "<pre class=\"debug\">";
        //     echo ("$".$key." => ");
        //     print_r($value);
        //     echo "</pre>\n";
        //   }
        // }

    ini_set('max_execution_time', 0);
    set_time_limit(0);
    ///recupération du fichier client du le serveur
    $file_name = $_FILES['csv']['name'];
    $extension=strrchr($file_name,'.');
    echo $_SERVER['DOCUMENT_ROOT'].'/imports/'.$_FILES['csv']['name'];
    if ($extension == '.csv')
    {
        // $file_tmp=$_FILES['csv']['tmp_name'];

        move_uploaded_file($_FILES['csv']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/imports/'.$_FILES['csv']['name']);

        $delimiteur = ',';
        $csv = new SplFileObject($_SERVER['DOCUMENT_ROOT'].'/var/www/html/imports/'.$_FILES['csv']['name']);
        $csv->setFlags(SplFileObject::READ_CSV);
        $csv->setCsvControl(';');
        $table = str_replace('.csv', '', $file_name);
        $sql_query = 'insert into '.str_replace('.csv', '', $file_name).' values (';
        $create_table = 'drop table if exists '.$table.'; create table '.$table.'(';
        $ligne = '';
        $row = 1;
        $sql_request = '';
        $nb_ligne = 0;
        try {
            $conn = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
                or die('Could not connect to the database server'.pdo_connect_error());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // begin the transaction
            $conn->beginTransaction();
            echo '<pre>';
            if (($handle = fopen('imports/'.$file_name, 'r')) !== false) {
                while (($data = fgetcsv($handle, 0, ';')) !== false) {
                    $num = count($data);

                    if ($num > 1) {
                        if ($row == 1) {
                            for ($c = 0; $c < $num; ++$c) {
                                if ($c == 0) {
                                    $create_query = '`'.$data[$c].'` text';
                                } else {
                                    $create_query = $create_query.$delimiteur.'`'.$data[$c].'` text';
                                }
                            }

                            echo '</pre>';
                            echo "CREATION DE LA TABLE $table:\n";
                            echo '<pre>';
                            echo $create_table.str_replace(' `', '`', $create_query).') COMMENT="'.$table."\";\n";
                            echo '</pre>';
                            $sql_request = $create_table.str_replace(' `', '`', $create_query).') COMMENT="'.$table."\";\n";
                            // our SQL statements
                            $conn->exec($sql_request);
                            echo "Insertion des données dans la table $table:\n";
                        } else {
                            for ($c = 0; $c < $num; ++$c) {
                                if ($c == 0) {
                                    $insert_query = "'".$data[$c]."'";
                                } else {
                                    $donnee = preg_replace("#'#", "\'", $data[$c]);
                                    $insert_query = $insert_query.$delimiteur."'".$donnee."'";
                                }
                            }
                            //echo $sql_query.$insert_query.");\n";

                            $sql_request = $sql_query.$insert_query.');';
                            $conn->exec($sql_request);
                        }
                        ++$row;
                    }
                    ++$nb_ligne;
                }

                fclose($handle);
            }
            echo '</pre><br>';

            $count_query = "select count(*) from $table;";
            if ($stmt = $conn->prepare($count_query)) {
                $stmt->execute();

                $count_item = $stmt->fetch();
            }
            // commit the transaction
            $conn->commit();
            echo "\n $count_item[0] New records created successfully.\n";
        } catch (PDOException $e) {
            // roll back the transaction if something failed
            $conn->rollback();
            echo 'Error: '.$e->getMessage();
        }
        $conn = null;
    }
    else
    {
        echo "<pre>Le fichier est de type $extension.<br/>Le type de fichier upload n'est pas de type \"CSV\"</pre>";
	move_uploaded_file($_FILES['csv']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/WORK/'.$_FILES['csv']['name']);
    }

    // fonction permet de visualiser le contenu de toutes les variables

        // debug de variables
         echo "<p class=\"debug\">";
         error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

         $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
         $var_ignore=array("GLOBALS", "_ENV", "_SERVER", "host", "dbname", "user", "password"); // Détermine les var à ne pas afficher
         echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
         $nom_fonction=__FUNCTION__;
	 if (isset($nom_fonction)&&$nom_fonction!="") 
	 {
           echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
         }
         foreach ($variables as $key=>$value)
         {
           if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
           {
             echo "<pre class=\"debug\">";
             echo ("$".$key." => ");
             print_r($value);
             echo "</pre>\n";
           }
         }
