<?php

/*

Generic API IO.

Copyright (C) 2020 Sergey Kolevatov

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

// $Revision: 13626 $ $Date:: 2020-09-03 #$ $Author: serge $

namespace generic_api;

class ApiException extends \Exception
{
}

class IOException extends ApiException
{
}

class ParseException extends ApiException
{
}

class PermissionException extends ApiException
{
}

class InternalException extends ApiException
{
}

?>
