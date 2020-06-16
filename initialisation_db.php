<?php
        require 'variable-db.php';
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

    // On récupère les champs
    if (isset($_POST['date_table'])) {
        $date_table = $_POST['date_table'];
    } else {
        $date_table = '';
    }

    if (isset($_POST['initialisation_db'])) {
        $initialisation_db = $_POST['initialisation_db'];
    } else {
        $initialisation_db = '';
    }
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());

    $query_verif = "select table_name from `information_schema`.`tables` where table_name like '%".$date_table."'";
    if ($stmt = $con->prepare($query_verif)) {
        $stmt->execute();

        $result_verif = $stmt->fetch();
    }

    if ($result_verif[0] == '') {
        echo '<H1>ERREUR</H1><P>Cette date est déjà utilisé par les table avec le suffixe "_avant".</P>';
    } else {
        $call_procedure = "call cmdb.init_db('".$date_table."');";
        if ($stmt = $con->prepare($call_procedure)) {
            $stmt->execute();

            $result_call = $stmt->fetch();
            print_r($result_call);
            // $call_procedure="call cmdb.modify_table_ref();";
            // if ($stmt = $con->prepare($call_procedure))
            // {
                // $stmt->execute();

                // $result_call = $stmt->fetch();
            // }
        }
        echo '<center><H1><P>INITIALISATON DE LA BASE REUSSI<br></p></H1></CENTER>';
    }
    echo '<center><P><button onclick="self.close()">Fermer la page</button></P></CENTER>';
