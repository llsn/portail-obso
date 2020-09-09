<?php
$GIT_REFRESH=isset($_POST['git_refresh']) ? $_POST['git_refresh'] : "FALSE";

echo "<div>";
echo "<form id='git_refresh' class='form-horizontal' method='POST' enctype='multipart/form-data' action='git.php'>";
echo "<input type='hidden' name='git_refresh' value='TRUE'/>";
echo "<input type='submit' value='lancer le rafraichissement du code avec la dernière version GIT' class='input-lg'>";
echo "</form>";
echo "</div>";

// if (isset($GIT_REFRESH) != "TRUE") 
// {
    echo "<div><pre>";
    // exec("cd /var/www/html/; /usr/bin/git pull;",$output,$result);
    echo shell_exec("cd /var/www/html/ && git status 2>&1");
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
// }
?>