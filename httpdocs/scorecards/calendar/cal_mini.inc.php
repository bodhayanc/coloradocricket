<?PHP
/*
ExtCalendar v2
Copyright (C) 2003 Mohamed Moujami (SimoAmi)

This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by 
the Free Software Foundation; either version 2 of the License, or 
(at your option) any later version. 
This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
GNU General Public License for more details. 

You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

Date Started : 21/08/2002
Date Last Updated : 11/01/2005
Author(s) : Mohamed Moujami (SimoAmi.com), Kristof De Jaeger
Description : Mini calendar module

Get the latest version of ExtCalendar at http://extcal.sourceforge.net//
*/

@require_once "init.inc.php";
if(!isset($CONFIG['calendar_url'])) $CONFIG['calendar_url'] = "/calendar";

if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
  if (!isset($extCal_params)) $extCal_params = array();
  $extCal_params = array(
    'navigation_controls' => '1',
    'target' => '_blank',
    'picture' => 'default',
    'date' => $date
  );
    $extCal_view = invoke_code ('minicalendar', $extCal_params);
  // Assign the $extCal_view['html'] variable to your template
  echo $extCal_view['html'];
}
?>