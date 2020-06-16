<?php

function debugPrintStatut()
{
    global $Debug;
    if ($Debug) {
        echo "<div class = 'debug'>Mode débuggage activé</div>";
    }
}

function debugPrintVariable($nomvariable)
{
    global $Debug;
    global $$nomvariable;
    if ($Debug) {
        if (isset($$nomvariable)) {
            if (is_array($$nomvariable)) {
                echo "<div class = 'debug'><em>Valeur du tableau $".$nomvariable.': </em>';
                if ($$nomvariable) {
                    echo '<ul>';
                    foreach ($$nomvariable as $cle => $valeur) {
                        echo '<li>$'.$nomvariable."[$cle] = $valeur</li>";
                    }
                    echo '</ul>';
                }
                echo '</div>';
            } elseif (is_bool($$nomvariable)) {
                echo "<div class = 'debug'><em>Valeur du Booléen $".$nomvariable.': </em>';
                if ($$nomvariable) {
                    echo 'true';
                } else {
                    echo 'false';
                }
                echo '</div>';
            } elseif (is_string($$nomvariable)) {
                echo "<div class = 'debug'><em>Valeur de la chaîne de caractères $".$nomvariable.': </em>'.$$nomvariable.'</div>';
            } elseif (is_numeric($$nomvariable)) {
                echo "<div class = 'debug'><em>Valeur de la variable numrique $".$nomvariable.': </em>'.$$nomvariable.'</div>';
            } else {
                echo "<div class = 'debug'><em>La variable $".$nomvariable." existe mais je ne suis pas sûr de l'afficher correctement. Sa valeur est </em>".$$nomvariable.'</div>';
            }
        } else {
            echo "<div class = 'debug'><em>La variable $".$nomvariable." n'est pas définie!</em></div>";
        }
    }
}

function debugPrintVariablePOST()
{
    global $Debug;
    if ($Debug) {
        debugPrintVariable('_POST');
    }
}

function debugPrintVariableGET()
{
    global $Debug;
    if ($Debug) {
        debugPrintVariable('_GET');
    }
}

function debugPrintVariableSESSION()
{
    global $Debug;
    if ($Debug) {
        debugPrintVariable('_SESSION');
    }
}
