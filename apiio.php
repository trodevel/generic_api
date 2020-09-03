<?php

/*

Generic API IO.

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

// $Revision: 13640 $ $Date:: 2020-09-04 #$ $Author: serge $

namespace generic_api;

require_once __DIR__.'/../generic_protocol/parser.php';    // Parser::parse()
require_once __DIR__.'/../generic_protocol/request_encoder.php';    // \to_generic_request()
require_once __DIR__.'/../php_snippets/https_send.php';  // https_post()
require_once __DIR__.'/hostconf.php';  // HostConf
require_once __DIR__.'/errorcodes.php';  // IOException

class ApiIO
{
    function __construct( $host, $port )
    {
        $this->host_conf = new HostConf( $host, $port );
    }

    public function submit( $req )
    {
        return $this->submit_req_and_parse( $req );
    }

    // @descr to be overridden
    protected function parse_response( $resp )
    {
        $res = \generic_protocol\Parser::parse( $resp );

        return $res;
    }

    // @descr to be overridden
    protected function to_generic_request( $req )
    {
        $res = \generic_protocol\to_generic_request( $req );

        return $res;
    }

    private function submit_req_and_parse( $req )
    {
        $encoded_req = $this->to_generic_request( $req );

        //var_dump( $req );

        //echo "DEBUG: submit_req_and_parse: encoded_req $encoded_req\n";

        return $this->submit_raw_and_parse( $encoded_req );
    }

    private function submit_raw_and_parse( $command )
    {
        $resp = "";
        $error_msg = "";

        $b = $this->submit_raw( $command, $resp, $error_msg );

        if( $b != true )
        {
            throw new IOException( $error_msg );
        }

        $parsed = $this->parse_response( $resp );

        if( $parsed == NULL )
        {
            throw new ParseException( 'cannot parse: ' . $resp );
        }

        if( get_class ( $parsed ) == "generic_protocol\ErrorResponse" )
        {
            if( $parsed->type == \generic_protocol\ErrorResponse_type_e__INVALID_OR_EXPIRED_SESSION )
            {
                throw new PermissionException( $parsed->descr );
            }
        }

        return $parsed;
    }

    private function submit_raw( $command, & $res, & $error_msg )
    {
        return \https_post( $this->host_conf->host, $this->host_conf->port, $command, $res, $error_msg );
    }

    private $host_conf;  // HostConf
}

?>
