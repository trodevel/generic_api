<?php
// $Revision: 13629 $ $Date:: 2020-09-03 #$ $Author: serge $

require_once '../api.php';
require_once '../credentials.php';
require_once __DIR__.'/../../generic_protocol/str_helper.php';

$error_msg = "";

echo "\n";
echo "TEST: close unopened session\n";
try
{
    $api = new \generic_api\Api( $host, $port );

    $session_id = "xxx";

    if( $api->close_session( $session_id, $error_msg ) == true )
    {
        echo "OK: session closed\n";
    }
    else
    {
        echo "ERROR: cannot close session: $error_msg\n";
    }
}
catch( \Exception $e )
{
    echo "FATAL: $e\n";
}

?>
