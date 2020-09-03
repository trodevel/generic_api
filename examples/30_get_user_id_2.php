<?php
// $Revision: 13629 $ $Date:: 2020-09-03 #$ $Author: serge $

require_once '../api.php';
require_once '../credentials.php';
require_once __DIR__.'/../../generic_protocol/str_helper.php';

$error_msg = "";

echo "\n";
echo "TEST: get user id of another user\n";
try
{
    $api = new \generic_api\Api( $host, $port );

    $session_id = NULL;

    if( $api->open_session( $login, $password, $session_id, $error_msg ) == true )
    {
        $user_id = NULL;

        if( $api->get_user_id( $session_id, "test2", $user_id, $error_msg ) == false )
        {
            echo "OK: cannot obtain user id: $error_msg\n";
        }
        else
        {
            echo "ERROR: unexpected $login --> user_id $user_id\n";
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
