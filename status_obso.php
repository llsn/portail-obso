<?php
    require 'variable-db.php';
    function status_obso_osversion($strosversion, $host, $dbname, $user, $password)
    {
        $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
            or die('Could not connect to the database server'.pdo_connect_error());
        echo '<pre>';
        $queryobsoos = "select * from os_obsolete where osversion ='".$strosversion."'";
        if ($statement = $con->prepare($queryobsoos)) {
            // echo $queryobsoos."\n";
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            $color = '#F95858';
            if (empty($result)) {
                $queryobsoos = "select * from os_in_extended_support where osversion ='".$strosversion."'";
                if ($statement = $con->prepare($queryobsoos)) {
                    // echo $queryobsoos;
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    $color = '#F9E558';
                    if (empty($result)) {
                        $queryobsoos = "select * from os_in_standard_support where osversion ='".$strosversion."'";
                        if ($statement = $con->prepare($queryobsoos)) {
                            echo $queryobsoos;
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                            $color = '#A0F958';
                        }
                    }
                }
            }
        }
        // print_r($result);
        echo '</pre>';

        return $color;
    }
    echo '<html><body><table><tr><td bgcolor="'.status_obso_osversion('6.1.0.0', $host, $dbname, $user, $password).'">'.status_obso_osversion('6.1.0.0', $host, $dbname, $user, $password).'</td></tr></table></body></html>';