<?php
$GIT_REFRESH=isset($_POST['git_refresh']) ? $_POST['git_refresh'] : "FALSE";

echo "<div>";
echo "<form id='git_refresh' class='form-horizontal' method='POST' enctype='multipart/form-data' action='git.php'>";
echo "<input type='hidden' name='git_refresh' value='TRUE'/>";
echo "<input type='submit' value='lancer le rafraichissement du code avec la dernière version GIT' class='input-lg'>";
echo "</form>";
echo "</div>";
echo "<pre>";
echo "ID:".shell_exec("id");
echo shell_exec("bash cd /var/www/html/; git status 2>&1");
echo "</pre>";
if (isset($GIT_REFRESH) != "TRUE") 
{
    echo "<div><pre>";
    // exec("git pull;",$output,$result);
    echo shell_exec("bash -c \"git status\"");
    print_r($output);
    if($result=0)
    {   
        echo "Mise à jour réussi";
    }
    else
    {
        echo "Mise à jour échoué!!!";
    }
    echo "</pre></div>";
}
?>