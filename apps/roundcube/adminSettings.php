<?php

/**
 * ownCloud - roundcube mail plugin
 *
 * @author Martin Reinhardt and David Jaedke
 * @copyright 2012 Martin Reinhardt contact@martinreinhardt-online.de
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

// ensure that only admin user access this page
OCP\User::checkAdminUser();

// CSRF checks
if ($_POST) {
  OCP\JSON::callCheck();
}

$params = array('maildir', 'encryptstring1', 'encryptstring2', 'removeHeaderNav', 'removeControlNav', 'autoLogin', 'rcHost');

OCP\Util::addscript('roundcube', 'settings');

if ($_POST) {
  foreach ($params as $param) {
    if (isset($_POST[$param])) {
      if ($param === 'removeHeaderNav') {
        OCP\Config::setAppValue('roundcube', 'removeHeaderNav', true);
      }
      if ($param === 'removeControlNav') {
        OCP\Config::setAppValue('roundcube', 'removeControlNav', true);
      }
      if ($param === 'autoLogin') {
        OCP\Config::setAppValue('roundcube', 'autoLogin', true);
      } else {
        if ($param === 'rcHost') {
          if (strlen($_POST[$param]) > 3) {
            OCP\Config::setAppValue('roundcube', $param, $_POST[$param]);
          }
        } else {
          OCP\Config::setAppValue('roundcube', $param, $_POST[$param]);
        }
      }
    } else {
      if ($param === 'removeHeaderNav') {
        OCP\Config::setAppValue('roundcube', 'removeHeaderNav', false);
      }
      if ($param === 'removeControlNav') {
        OCP\Config::setAppValue('roundcube', 'removeControlNav', false);
      }
      if ($param === 'autoLogin') {
        OCP\Config::setAppValue('roundcube', 'autoLogin', false);
      }
    }
  }
}

// fill template
$tmpl = new OCP\Template('roundcube', 'adminSettings');
foreach ($params as $param) {
  $value = OCP\Config::getAppValue('roundcube', $param, '');
  $tmpl -> assign($param, $value);
}

return $tmpl -> fetchPage();
?>