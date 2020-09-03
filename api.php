<?php

/*

Generic API.

Copyright (C) 2016 Sergey Kolevatov

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.

*/

// $Revision: 13645 $ $Date:: 2020-09-04 #$ $Author: serge $

namespace generic_api;

require_once __DIR__.'/../generic_protocol/object_initializer.php';
require_once __DIR__.'/apiio.php';  // ApiIO

class Api
{
    function __construct( $host, $port )
    {
        $this->apiio = new ApiIO( $host, $port );
    }

    public function open_session( $login, $password, & $session_id, & $error_msg )
    {
        //echo "TRACE: login $login, password $password\n";

        $req = \generic_protocol\create__AuthenticateRequest( $login, $password );

        $resp = $this->apiio->submit( $req );

        if( get_class ( $resp ) == "generic_protocol\ErrorResponse" )
        {
            $error_msg = $resp->descr;
            return false;
        }
        elseif( get_class( $resp ) == "generic_protocol\AuthenticateResponse" )
        {
            $session_id = $resp->session_id;
            return true;
        }

        throw new InternalException( "unexpected response: " . get_class( $resp ) );
    }

    public function close_session( $session_id, & $error_msg )
    {
        $req = \generic_protocol\create__CloseSessionRequest( $session_id );

        $resp = $this->apiio->submit( $req );

        if( get_class ( $resp ) == "generic_protocol\ErrorResponse" )
        {
            $error_msg = $resp->descr;
            return false;
        }
        elseif( get_class( $resp ) == "generic_protocol\CloseSessionResponse" )
        {
            return true;
        }

        throw new InternalException( "unexpected response: " . get_class( $resp ) );
    }

    public function get_user_id( $session_id, $login, & $user_id, & $error_msg )
    {
        $req = \generic_protocol\create__GetUserIdRequest( $session_id, $login );

        $resp = $this->apiio->submit( $req );

        if( get_class ( $resp ) == "generic_protocol\ErrorResponse" )
        {
            $error_msg = $resp->descr;

            return false;
        }
        elseif( get_class( $resp ) == "generic_protocol\GetUserIdResponse" )
        {
            $user_id = $resp->user_id;

            return true;
        }

        throw new InternalException( "unexpected response: " . get_class( $resp ) );
    }

    public function get_session_info( $session_id, $session_id_2, & $session_info, & $error_msg )
    {
        $req = \generic_protocol\create__GetSessionInfoRequest( $session_id, $session_id_2 );

        $resp = $this->apiio->submit( $req );

        if( get_class ( $resp ) == "generic_protocol\ErrorResponse" )
        {
            $error_msg = $resp->descr;

            return false;
        }
        elseif( get_class( $resp ) == "generic_protocol\GetSessionInfoResponse" )
        {
            $session_info = $resp->session_info;

            return true;
        }

        throw new InternalException( "unexpected response: " . get_class( $resp ) );
    }


    private $apiio;  // ApiIO
}

?>
