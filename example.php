<?php
// $Revision: 6462 $ $Date:: 2017-04-05 #$ $Author: serge $

require_once 'api.php';
require_once 'credentials.php';

$error_msg = "";

echo "\n";
echo "TEST: open and close session\n";
{
    $api = new \generic_api\Api( $host, $port );

    $session_id = NULL;

    if( $api->open_session( $login, $password, $session_id, $error_msg ) == true )
    {
        echo "OK: opened session\n";

        if( $api->close_session( $session_id, $error_msg ) == true )
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

echo "\n";
echo "TEST: close unopened session\n";
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

echo "\n";
echo "TEST: double close session\n";
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

echo "\n";
echo "TEST: get user id\n";
{
    $api = new \generic_api\Api( $host, $port );

    $session_id = NULL;

    if( $api->open_session( $login, $password, $session_id, $error_msg ) == true )
    {
        echo "OK: opened session\n";

        $req = new \generic_protocol\GetUserIdRequest( $session_id, $login );

        echo "REQ = " . $req->to_generic_request() . "\n";
        $resp = $api->submit( $req );
        echo "RESP = " . $resp->to_html() . "\n\n";

        if( $api->close_session( $session_id, $error_msg ) == true )
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


echo "\n";
echo "TEST: get user id of another user\n";
echo "\n";
{
    $api = new \generic_api\Api( $host, $port );

    $session_id = NULL;

    if( $api->open_session( $login, $password, $session_id, $error_msg ) == true )
    {
        echo "OK: opened session\n";

        $req = new \generic_protocol\GetUserIdRequest( $session_id, "test2" );

        echo "REQ = " . $req->to_generic_request() . "\n";
        $resp = $api->submit( $req );
        echo "RESP = " . $resp->to_html() . "\n\n";

        if( $api->close_session( $session_id, $error_msg ) == true )
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