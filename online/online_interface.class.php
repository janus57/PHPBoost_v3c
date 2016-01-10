<?php
/*##################################################
 *                              online_interface.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 R�gis Viarre
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

// Inclusion du fichier contenant la classe ModuleInterface
import('modules/module_interface');

// Classe ForumInterface qui h�rite de la classe ModuleInterface
class OnlineInterface extends ModuleInterface
{
    ## Public Methods ##
    function OnlineInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('online');
    }
    
    //R�cup�ration du cache.
	function get_cache()
	{
		global $Sql;
			
		$online_config = 'global $CONFIG_ONLINE;' . "\n";
		
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG_ONLINE = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'online'", __LINE__, __FILE__));
		$CONFIG_ONLINE = is_array($CONFIG_ONLINE) ? $CONFIG_ONLINE : array();
		
		$online_config .= '$CONFIG_ONLINE = ' . var_export($CONFIG_ONLINE, true) . ';' . "\n";
		
		return $online_config;	
	}

	//Actions journali�re.
	/*
	function on_changeday()
	{
	}
	*/
}

?>