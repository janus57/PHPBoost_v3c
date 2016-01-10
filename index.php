<?php
/*##################################################
 *                                index.php
 *                            -------------------
 *   begin                : August 23 2007
 *   copyright            : (C) 2007 CrowkaiT
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('PATH_TO_ROOT', './');
@include_once('./kernel/db/config.php'); //Fichier de configuration (pour savoir si PHPBoost est install�)
unset($sql_host, $sql_login, $sql_pass); //Destruction des identifiants bdd (on n'en a pas besoin sur cette page)

require_once('./kernel/framework/functions.inc.php');
$CONFIG = array();
//Chargement manuel de la configuration g�n�rale
@include_once('./cache/config.php');

//Si PHPBoost n'est pas install�, on renvoie vers l'installateur
if (!defined('PHPBOOST_INSTALLED'))
{
    import('util/unusual_functions', INC_IMPORT);
    redirect(get_server_url_page('install/install.php'));
}
elseif (empty($CONFIG))
{   // Si la configuration n'existe pas mais que PHPBoost est install�
    // on renvoie vers la page membre du noyau dont on est s�r qu'elle existe
    import('util/unusual_functions', INC_IMPORT);
    redirect(get_server_url_page('member/member.php'));
}

//Sinon, c'est que tout a bien march�, on renvoie sur la page de d�marrage
define('DIR', $CONFIG['server_path']);
define('HOST', $CONFIG['server_name']);
$start_page = get_start_page();

if ($start_page != HOST . DIR . '/index.php' && $start_page != './index.php') //Emp�che une boucle de redirection.
	redirect($start_page);
else
	redirect(HOST . DIR . '/member/member.php');

?>
