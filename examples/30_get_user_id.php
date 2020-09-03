<?php
// $Revision: 13644 $ $Date:: 2020-09-04 #$ $Author: serge $

require_once '../api.php';
require_once '../credentials.php';
require_once __DIR__.'/../../generic_protocol/str_helper.php';

$error_msg = "";

echo "\n";
echo "TEST: get user id\n";
try
{
    $api = new \generic_api\Api( $host, $port );

    $session_id = NULL;

    if( $api->open_session( $login, $password, $session_id, $error_msg ) == true )
    {
        $user_id = NULL;

        if( $api->get_user_id( $session_id, $login, $user_id, $error_msg ) == true )
        {
            echo "OK: login '$login' --> user_id $user_id\n";
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
