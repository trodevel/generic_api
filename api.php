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

// $Revision: 4368 $ $Date:: 2016-08-08 #$ $Author: serge $

namespace generic_api;

require_once 'generic_protocol/generic_protocol.php';
require_once 'generic_protocol/response_parser.php';    // parse_response()
require_once 'php_snippets/tcp_send.php';    // tcp_send()

class Api
{
    function __construct( $host, $port )
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function is_session_open()
    {
        return !empty( $this->session_id );
    }

    public function open_session( $login, $password, & $error_msg )
    {
        if( $this->is_session_open() )
        {
            $error_msg = "session is already opened";
            return false;
        }

        $req = new \generic_protocol\AuthenticateRequest( $login, $password );

        $resp = $this->submit_req_and_parse( $req );

        if( get_class ( $resp ) == "generic_protocol\ErrorResponse" )
        {
            $error_msg = $resp->descr;
            return false;
        }
        elseif( get_class( $resp ) == "generic_protocol\AuthenticateResponse" )
        {
            $this->session_id = $resp->session_id;
            return true;
        }

        $error_msg = "unknown response";

        return false;
    }

    function close_session( & $error_msg )
    {
        if( $this->is_session_open() == false )
        {
            $error_msg = "session is not opened";

            return false;
        }

        $req = new \generic_protocol\CloseSessionRequest( $this->session_id );

        $resp = $this->submit_req_and_parse( $req );

        $this->session_id = NULL;

        return true;
    }

    public function submit( $req )
    {
        if( $this->is_session_open() == false )
        {
            return new \generic_protocol\ErrorResponse( \generic_protocol\ErrorResponse::RUNTIME_ERROR, "session is not opened" );
        }

        return $this->submit_session_req_and_parse( $req );
    }

    protected function parse_response( $resp )
    {
        return \generic_protocol\parse_response( $resp );
    }

    protected function get_session_id()
    {
        return $this->session_id;
    }

    private function submit_session_req_and_parse( $req )
    {
        if( $this->is_session_open() == false )
        {
            exit( "FATAL: session is not open" );
        }

        // automatically add session id to all requests
        $req->set_session_id( $this->session_id );
   
        return $this->submit_req_and_parse( $req );
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
    private $session_id; // session_id
}

?>
