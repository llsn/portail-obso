<?php

    function status_obso_osversion($strosversion, $host, $dbname, $user, $password)
    {
        $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
            or die('Could not connect to the database server'.pdo_connect_error());
        // echo "<pre>";
        $queryobsoos = "select * from os_obsolete where osversion ='".$strosversion."'";
        if ($statement = $con->prepare($queryobsoos)) {
            // echo $queryobsoos."\n";
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            $color = '#F95858';   //rouge
            if (empty($result)) {
                $queryobsoos = "select * from os_in_extended_support where osversion ='".$strosversion."'";
                if ($statement = $con->prepare($queryobsoos)) {
                    // echo $queryobsoos;
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    $color = '#F9E558';  //jaune
                    if (empty($result)) {
                        $queryobsoos = "select * from os_in_standard_support where osversion ='".$strosversion."'";
                        if ($statement = $con->prepare($queryobsoos)) {
                            // echo $queryobsoos;
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            if (empty($result)) {
                                $color = '#FFFFFF'; //blanc
                            } else {
                                $color = '#A0F958'; //vert
                            }
                        }
                    }
                }
            }
        }
        $statement->pdo = null;
        // print_r($result);
        // echo "</pre>";
        return $color;
    }

    function status_obso_dbversion($strosversion, $host, $dbname, $user, $password)
    {
        $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
            or die('Could not connect to the database server'.pdo_connect_error());
        // echo "<pre>";
        $queryobsoos = "select * from db_obsolete where osversion ='".$strosversion."'";
        if ($statement = $con->prepare($queryobsoos)) {
            // echo $queryobsoos."\n";
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            // print_r($result);
            $color = '#F95858';
            if (empty($result)) {
                $queryobsoos = "select * from db_in_extended_support where osversion ='".$strosversion."'";
                if ($statement = $con->prepare($queryobsoos)) {
                    // echo $queryobsoos;
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    // print_r($result);
                    $color = '#F9E558';
                    if (empty($result)) {
                        $queryobsoos = "select * from db_in_standard_support where osversion ='".$strosversion."'";
                        if ($statement = $con->prepare($queryobsoos)) {
                            // echo $queryobsoos;
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            if (empty($result)) {
                                $color = '#FFFFFF'; //blanc
                            } else {
                                $color = '#A0F958'; //vert
                            }
                        }
                    }
                }
            }
        }
        $statement->pdo = null;
        // print_r($result);
        // echo "</pre>";
        return $color;
    }

    function status_obso_dbedition($strdbedition, $host, $dbname, $user, $password)
    {
        $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
            or die('Could not connect to the database server'.pdo_connect_error());
        // echo "<pre>";
        $queryobsodbed = "select * from db_obsolete where osname ='".$strdbedition."'";
        if ($statement = $con->prepare($queryobsodbed)) {
            // echo $queryobsodbed."\n";
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            // print_r($result);
            $color = '#F95858';
            if (empty($result)) {
                $queryobsodbed = "select * from db_in_extended_support where osname ='".$strdbedition."'";
                if ($statement = $con->prepare($queryobsodbed)) {
                    // echo $queryobsodbed;
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    // print_r($result);
                    $color = '#F9E558';
                    if (empty($result)) {
                        $queryobsodbed = "select * from db_in_standard_support where osname ='".$strdbedition."'";
                        if ($statement = $con->prepare($queryobsodbed)) {
                            // echo $queryobsodbed;
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            if (empty($result)) {
                                $color = '#FFFFFF'; //blanc
                            } else {
                                $color = '#A0F958'; //vert
                            }
                        }
                    }
                }
            }
        }
        $statement->pdo = null;
        // print_r($result);
        // echo "</pre>";
        return $color;
    }

    function status_obso_middlewareversion($strosversion, $host, $dbname, $user, $password)
    {
        $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password)
            or die('Could not connect to the database server'.pdo_connect_error());
        // echo "<pre>";
        $queryobsoos = "select * from middleware_obsolete where osversion ='".$strosversion."'";
        if ($statement = $con->prepare($queryobsoos)) {
            // echo $queryobsoos."\n";
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            // print_r($result);
            $color = '#F95858';
            if (empty($result)) {
                $queryobsoos = "select * from middleware_in_extended_support where osversion ='".$strosversion."'";
                if ($statement = $con->prepare($queryobsoos)) {
                    // echo $queryobsoos;
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    // print_r($result);
                    $color = '#F9E558';
                    if (empty($result)) {
                        $queryobsoos = "select * from middleware_in_standard_support where osversion ='".$strosversion."'";
                        if ($statement = $con->prepare($queryobsoos)) {
                            // echo $queryobsoos;
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            if (empty($result)) {
                                $color = '#FFFFFF'; //blanc
                            } else {
                                $color = '#A0F958'; //vert
                            }
                        }
                    }
                }
            }
        }
        $statement->pdo = null;
        // print_r($result);
        // echo "</pre>";
        return $color;
    }
