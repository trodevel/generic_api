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

// $Revision: 6435 $ $Date:: 2017-04-04 #$ $Author: serge $

namespace generic_api;

require_once __DIR__.'/../generic_protocol/generic_protocol.php';
require_once __DIR__.'/../generic_protocol/response_parser.php';    // parse_response()
require_once __DIR__.'/../php_snippets/tcp_send.php';  // tcp_send()

class Api
{
    function __construct( $host, $port )
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function open_session( $login, $password, & $session_id, & $error_msg )
    {
        $req = new \generic_protocol\AuthenticateRequest( $login, $password );

        $resp = $this->submit_req_and_parse( $req );

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

        $error_msg = "unknown response";

        return false;
    }

    public function close_session( $session_id, & $error_msg )
    {
        $req = new \generic_protocol\CloseSessionRequest( $session_id );

        $resp = $this->submit_req_and_parse( $req );

        if( get_class ( $resp ) == "generic_protocol\ErrorResponse" )
        {
            $error_msg = $resp->descr;
            return false;
        }
        elseif( get_class( $resp ) == "generic_protocol\CloseSessionResponse" )
        {
            return true;
        }

        $error_msg = "unknown response";

        return false;
    }

    public function submit( $req )
    {
        return $this->submit_req_and_parse( $req );
    }

    protected function parse_response( $resp )
    {
        return \generic_protocol\parse_response( $resp );
    }

    private function submit_req_and_parse( $req )
    {
        return $this->submit_raw_and_parse( $req->to_generic_request() );
    }

    private function submit_raw_and_parse( $command )
    {
        $resp = "";
        $error_msg = "";

        $b = $this->submit_raw( $command, $resp, $error_msg );

        if( $b == true )
        {
            $parsed = $this->parse_response( $resp );
        }
        else
        {
            $parsed = new \generic_protocol\ErrorResponse( \generic_protocol\ErrorResponse::RUNTIME_ERROR, $error_msg );
        }

        return $parsed;
    }

    private function submit_raw( $command, & $res, & $error_msg )
    {
        return \tcp_send( $this->host, $this->port, $command . "<EOM>", $res, $error_msg );
    }

    private $host;       // host
    private $port;       // port
}

?>
