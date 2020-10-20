
<?php
require 'variable-db.php';
function telechargerFichier($cheminTotal, $fichierFauxNomSansExtension, $extension)
{
    $fichierFauxNomAvecExtension = $fichierFauxNomSansExtension.'.'.$extension;
    switch ($extension) {
        case 'gif': $type = 'image/gif'; break;
        case 'gz': $type = 'application/x-gzip'; break;
        case 'jpg': $type = 'image/jpeg'; break;
        case 'htm': $type = 'text/html'; break;
        case 'html': $type = 'text/html'; break;
        case 'pdf': $type = 'application/pdf'; break;
        case 'png': $type = 'image/png'; break;
        case 'tgz': $type = 'application/x-gzip'; break;
        case 'txt': $type = 'text/plain'; break;
        case 'zip': $type = 'application/zip'; break;
        case 'csv': $type = 'application/excel'; break;
        default: $type = 'application/octet-stream'; break;
    }
    header("Content-disposition: attachment; filename=$fichierFauxNomAvecExtension");
    header('Content-Type: application/force-download');
    header("Content-Transfer-Encoding: $type");
    header('Content-Length: '.filesize($cheminTotal));
    header('Pragma: no-cache');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0, public');
    header('Expires: 0');
    readfile($cheminTotal);
}

    $query = isset($_POST['requete']) ? $_POST['requete'] : null;
    // debug de variables
    // error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

    // $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
    // $var_ignore=array("GLOBALS", "_ENV", "_SERVER"); // Détermine les var à ne pas afficher
    // echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
    // $nom_fonction=__FUNCTION__;
    // if (isset($nom_fonction)&&$nom_fonction!="") {
      // echo ("<strong>Dans la fonction : ".$nom_fonction."</strong><br />\n");
    // }
    // foreach ($variables as $key=>$value) {
      // if (!in_array($key, $var_ignore)&&strpos($key,"HTTP")===false)
      // {
        // echo "<pre>";
        // echo ("$".$key." => ");
        // print_r($value);
        // echo "</pre>\n";
      // }
    // }

    try {
        $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
        or die('Could not connect to the database server'.pdo_connect_error());
    } catch (Exception $e) {
        die('Erreur : '.$e->getMessage());
    }

    if ($stmt = $con->prepare($query)) {
        $stmt->execute();
        // echo "<pre>";
        // print_r($stmt);
        // echo "</pre>";
        // Paramétrage de l'écriture du futur fichier CSV
        $date = date('YmdHis');
        $path_export = 'exports';
        $nom_fichier = 'export_result_'.$date;
        $chemin = $path_export.'/'.'export_result_'.$date.'.csv';
        $delimiteur = ';';
        $ROOT_DOCUMENT = $_SERVER['DOCUMENT_ROOT'].'/';
        $cheminTotal = $ROOT_DOCUMENT.$chemin;
        $fichierFauxNomSansExtension = $nom_fichier;
        $extension = 'csv';
        $fichier_csv = fopen($chemin, 'w+');
        $entete = '';
        $ligne = '';
        // Si votre fichier a vocation a être importé dans Excel,
        // vous devez impérativement utiliser la ligne ci-dessous pour corriger
        // les problèmes d'affichage des caractères internationaux (les accents par exemple)
        // fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF)."\n");

        // Boucle while sur chaque ligne du tableau
        while ($result = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            // $result=preg_replace("#\n|\t|\r#","",$resultat);
            // echo "<pre>";
            // print_r($result);
            if (count($result)) {
                $columns_names = array_keys($result[0]);
                foreach ($columns_names as $col) {
                    if ($entete == '') {
                        $entete = $col;
                    } else {
                        $entete = $entete.$delimiteur.$col;
                    }
                }
                // echo $entete."\n";
                fprintf($fichier_csv, $entete." \n");
                foreach ($result as $tuple) {
                    foreach ($tuple as $colonne) {
                        $col = preg_replace("#\n#", ', ', $colonne);
                        if ($ligne == '') {
                            $ligne = $col;
                        } else {
                            $ligne = $ligne.$delimiteur.$col;
                        }
                    }
                    // echo $ligne."\n";
                    fprintf($fichier_csv, $ligne." \n");
                    $ligne = '';
                }
            }
            // echo "</pre>";
        }

        // fermeture du fichier csv
        fclose($fichier_csv);
        $stmt->pdo = null;
        telechargerFichier($cheminTotal, $fichierFauxNomSansExtension, $extension);
        // supression sur le serveur du fichier généré
        if (file_exists($cheminTotal)) {
            unlink($cheminTotal);
        }
    }
echo "<script language='javascript'>window.close()</script>";
?>
