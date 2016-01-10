<?php
/*##################################################
 *                              web_interface.class.php
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
class WebInterface extends ModuleInterface
{
    ## Public Methods ##
    function WebInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('web');
    }
    
    //R�cup�ration du cache.
	function get_cache()
	{
		global $Sql;
	
		$code = 'global $CAT_WEB;' . "\n" . 'global $CONFIG_WEB;' . "\n";
			
		//R�cup�ration du tableau lin�aris� dans la bdd.
		$CONFIG_WEB = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'web'", __LINE__, __FILE__));
		$CONFIG_WEB = is_array($CONFIG_WEB) ? $CONFIG_WEB : array();
		
		$code .= '$CONFIG_WEB = ' . var_export($CONFIG_WEB, true) . ';' . "\n";
		$code .= "\n";
		
		$result = $Sql->query_while("SELECT id, name, secure
		FROM " . PREFIX . "web_cat
		WHERE aprob = 1", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{		
			$code .= '$CAT_WEB[\'' . $row['id'] . '\'][\'secure\'] = ' . var_export($row['secure'], true) . ';' . "\n";
			$code .= '$CAT_WEB[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
		}
		
		return $code;	
	}

	//Actions journali�re.
	/*
	function on_changeday()
	{
	}
	*/
}

?>