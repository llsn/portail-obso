<?php
    // require_once('vendor/autoload.php');
    //require_once '/vendor/autoload.php';
    // require_once 'vendor/dompdf/dompdf/lib/html5lib/Parser.php';
    // require_once 'vendor/dompdf/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
    // require_once 'vendor/dompdf/dompdf/lib/php-svg-lib/src/autoload.php';
    // require_once 'vendor/dompdf/dompdf/src/Autoloader.php';
    require_once __DIR__.'/vendor/autoload.php';
    use Dompdf\Dompdf;
    use Dompdf\Options;


    // use Spipu\Html2Pdf\HTML2PDF;
    // use Spipu\Html2Pdf\Exception\Html2PdfException;
    // use Spipu\Html2Pdf\Exception\ExceptionFormatter;

    // echo "<p class=\"debug\">";
    // error_reporting(E_ALL);   // Activer le rapport d'erreurs PHP . Vous pouvez n'utiliser que cette ligne, elle donnera déjà beaucoup de détails.

    // $variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle
    // $var_ignore=array("GLOBALS", "_ENV", "_SERVER","_GET","host","dbname","user","password","port","socket"); // Détermine les var à ne pas afficher
    // echo ("<strong>Etat des variables a la ligne : ".__LINE__." dans le fichier : ".__FILE__."</strong><br />\n");
    // $nom_fonction=__FUNCTION__;
    // if (isset($nom_fonction)&&$nom_fonction!="")
    // {
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

    // echo "</p>";

    $EXPORTPDF = isset($_POST['EXPORTPDF']) ? $_POST['EXPORTPDF'] : null;
    $DATA = isset($_POST['DATA']) ? unserialize(base64_decode($_POST['DATA'])) : null;
    $ORIENTATION_PAPER = isset($_POST['ORIENTATION_PAPER']) ? $_POST['ORIENTATION_PAPER'] : 'landscape';
    $FORMAT_PAPER = isset($_POST['FORMAT_PAPER']) ? $_POST['FORMAT_PAPER'] : 'A4';
    $FILE_NAME=isset($_POST['FILE_NAME']) ? $_POST['FILE_NAME'] : 'export.pdf';
    // $CONTENT=str_ireplace('<table class=\'table table-bordered\'>','<table style=\'border:1px solid black;border-collapse: collapse; text-align:center;\'>',$DATA);
    // $DATA=str_ireplace('<tr>','<tr style=\'border:1px solid black;border-collapse: collapse; text-align:center;\'>',$CONTENT);
    // $CONTENT=str_ireplace('<td>','<td style=\'border:1px solid black;border-collapse: collapse; text-align:center;\'>',$DATA);
    // $DATA=str_ireplace('<td ','<td style=\'border:1px solid black;border-collapse: collapse; text-align:center;\' ',$CONTENT);
    // $CONTENT=str_ireplace('word-wrap: break-word; word-break: break-all;','border:1px solid black;border-collapse: collapse;text-align:center;word-wrap: break-word; word-break: break-all;',$DATA);

    // $DATA=str_ireplace('<center>','',$CONTENT);
    // $CONTENT=str_ireplace('</center>','',$DATA);
    // $DATA=str_ireplace('</br>','',$CONTENT);

    // $CONTENT="<div style='align:center'>".$DATA."</div>";
    // echo nl2br(htmlentities($CONTENT),"</br>\n");

    // if(isset($EXPORTPDF)!=NULL)
    // {
        // try
        // {

            // $html2pdf = new HTML2PDF("P", "A3", "fr");

            // $html2pdf->setDefaultFont("Arial");
            // $html2pdf->writeHTML($CONTENT);
            // $html2pdf->Output("votre_pdf.pdf");
        // }
        // catch(HTML2PDF_exception $e) {
            // echo $e;
            // exit;
        // }
    // }
    // reference the Dompdf namespace

    // instantiate and use the dompdf class
    $options = new Options();
    $options->set('defaultFont', 'helvetica');
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($DATA);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper($FORMAT_PAPER, $ORIENTATION_PAPER);

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    $dompdf->stream($FILE_NAME);
