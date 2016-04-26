<?php
// $Revision: 3839 $ $Date:: 2016-04-26 #$ $Author: serge $

require_once 'api.php';

$host = "localhost";
$port = 1234;

$login    = "test";
$password = "xxx";

$error_msg = "";

/*
echo "TEST: open session\n";
{
    $api = new generic_api\Api( $host, $port );

    if( $api->open_session( $login, $password, $error_msg ) == true )
    {
        echo "OK: opened session\n";
    }
    else
    {
        echo "ERROR: cannot open session: $error_msg\n";
    }
}
*/

echo "TEST: open and close session\n";
{
    $api = new generic_api\Api( $host, $port );

    if( $api->open_session( $login, $password, $error_msg ) == true )
    {
        echo "OK: opened session\n";

        if( $api->close_session( $error_msg ) == true )
        {
            echo "OK: session closed\n";
        }
        else
        {
            echo "ERROR: cannot close session: $error_msg\n";
        }
    }
    else
    {
        echo "ERROR: cannot open session: $error_msg\n";
    }
}

echo "TEST: close unopened session\n";
{
    $api = new generic_api\Api( $host, $port );

    if( $api->close_session( $error_msg ) == true )
    {
        echo "OK: session closed\n";
    }
    else
    {
        echo "ERROR: cannot close session: $error_msg\n";
    }
}

echo "TEST: double close session\n";
{
    $api = new generic_api\Api( $host, $port );

    if( $api->open_session( $login, $password, $error_msg ) == true )
    {
        echo "OK: opened session\n";

        echo "1\n";

        if( $api->close_session( $error_msg ) == true )
        {
            echo "OK: session closed\n";
        }
        else
        {
            echo "ERROR: cannot close session: $error_msg\n";
        }

        echo "2\n";

        if( $api->close_session( $error_msg ) == true )
        {
            echo "OK: session closed\n";
        }
        else
        {
            echo "ERROR: cannot close session: $error_msg\n";
        }
    }
    else
    {
        echo "ERROR: cannot open session: $error_msg\n";
    }
}

?>