<?php
// $Revision: 13629 $ $Date:: 2020-09-03 #$ $Author: serge $

require_once '../api.php';
require_once '../credentials.php';
require_once __DIR__.'/../../generic_protocol/str_helper.php';

$error_msg = "";

echo "\n";
echo "TEST: get session info\n";
try
{
    $api = new \generic_api\Api( $host, $port );

    $session_id = NULL;

    if( $api->open_session( $login, $password, $session_id, $error_msg ) == true )
    {
        $session_info = NULL;

        if( $api->get_session_info( $session_id, $session_id, $session_info, $error_msg ) == true )
        {
            echo "OK: session_info: " . \generic_protocol\to_string( $session_info ) . "\n";
        }
        else
        {
            echo "ERROR: $error_msg\n";
        }

        $api->close_session( $session_id, $error_msg );
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
