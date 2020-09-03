<?php
// $Revision: 13643 $ $Date:: 2020-09-04 #$ $Author: serge $

require_once '../api.php';
require_once '../credentials.php';
require_once __DIR__.'/../../generic_protocol/str_helper.php';

$error_msg = "";

echo "\n";
echo "TEST: double close session\n";
try
{
    $api = new \generic_api\Api( $host, $port );

    $session_id = NULL;

    if( $api->open_session( $login, $password, $session_id, $error_msg ) == true )
    {
        echo "OK: opened session\n";

        echo "1\n";

        if( $api->close_session( $session_id, $error_msg ) == true )
        {
            echo "OK: session closed\n";
        }
        else
        {
            echo "ERROR: cannot close session: $error_msg\n";
        }

        echo "2\n";

        if( $api->close_session( $session_id, $error_msg ) == true )
        {
            echo "ERROR: session closed unexpectedly\n";
        }
        else
        {
            echo "OK: cannot close session: $error_msg\n";
        }
    }
    else
    {
        echo "ERROR: cannot open session: $error_msg\n";
    }
}
catch( \Exception $e )
{
    echo "FATAL: $e\n";
}

?>
