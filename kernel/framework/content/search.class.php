<?php



























global $CONFIG;

define('CACHE_TIME', $CONFIG['search_cache_time']);
define('CACHE_TIMES_USED', $CONFIG['search_max_use']);






class Search
{
    
    
    
    








    function Search($search = '', $modules = array())
    {
        global $Sql, $User;
        
        $this->errors = 0;
        $this->search = md5($search); 
        $this->modules = $modules;
        $this->id_search = array();
        $this->cache = array();
        
        $this->id_user = $User->get_attribute('user_id');
        $this->modules_conditions = $this->_get_modules_conditions($this->modules);
                
        
        
        
        
        
        
        
        $reqOldIndex = "SELECT id_search FROM " . PREFIX . "search_index
                        WHERE  last_search_use <= '".(time() - (CACHE_TIME * 60))."'
                            OR times_used >= '".CACHE_TIMES_USED."'";
        
        $nbIdsToDelete = 0;
        $idsToDelete = '';
        $request = $Sql->query_while ($reqOldIndex, __LINE__, __FILE__);
        while ($row = $Sql->fetch_assoc($request))
        {
            if ($nbIdsToDelete > 0)
            {
                $idsToDelete .= ',';
            }
            $idsToDelete .= "'" . $row['id_search'] . "'";
            $nbIdsToDelete++;
        }
        $Sql->query_close($request);
        
        
        if ($nbIdsToDelete > 0)
        {
            $reqDeleteIdx = "DELETE FROM " . DB_TABLE_SEARCH_INDEX . " WHERE id_search IN (".$idsToDelete.")";
            $reqDeleteRst = "DELETE FROM " . DB_TABLE_SEARCH_RESULTS . " WHERE id_search IN (".$idsToDelete.")";
            
            $Sql->query_inject($reqDeleteIdx, __LINE__, __FILE__);
            $Sql->query_inject($reqDeleteRst, __LINE__, __FILE__);
        }
        
        
        
        if ($this->search != '')
        {
            
            $reqCache  = "SELECT id_search, module FROM " . DB_TABLE_SEARCH_INDEX . " WHERE ";
            $reqCache .= "search='" . $this->search . "' AND id_user='" . $this->id_user . "'";
            if ($this->modules_conditions != '')
            {
                $reqCache .= " AND " . $this->modules_conditions;
            }
            
            $request = $Sql->query_while ($reqCache, __LINE__, __FILE__);
            while ($row = $Sql->fetch_assoc($request))
            {   
                array_push($this->cache, $row['module']);
                $this->id_search[$row['module']] = $row['id_search'];
            }
            $Sql->query_close($request);
            
            
            if (count($this->id_search) > 0)
            {
                $reqUpdate  = "UPDATE " . DB_TABLE_SEARCH_INDEX . " SET times_used=times_used+1, last_search_use='" . time() . "' WHERE ";
                $reqUpdate .= "id_search IN (" . implode(',', $this->id_search) . ");";
                $Sql->query_inject($reqUpdate, __LINE__, __FILE__);
            }
            
            
            if (count($modules) > count($this->cache))
            {
                $nbReqInsert = 0;
                $reqInsert = '';
                
                foreach ($modules as $module_name => $options)
                {
                    if (!$this->is_in_cache($module_name))
                    {
                        $reqInsert .= "('" . $this->id_user . "','" . $module_name . "','" . $this->search . "','" . md5(implode('|', $options)) . "','" . time() . "', '0'),";
                        
                        if ($nbReqInsert == 10)
                        {
                            $reqInsert = "INSERT INTO " . DB_TABLE_SEARCH_INDEX .
								" (id_user, module, search, options, last_search_use, times_used) VALUES " . rtrim($reqInsert, ',');
                            $Sql->query_inject($reqInsert, __LINE__, __FILE__);
                            $reqInsert = '';
                            $nbReqInsert = 0;
                        }
                        else
                        {
                            $nbReqInsert++;
                        }
                    }
                }
                
                
                if ($nbReqInsert > 0)
                {
                    $Sql->query_inject("INSERT INTO " . DB_TABLE_SEARCH_INDEX . " (id_user, module, search, options, last_search_use, times_used) VALUES " . substr($reqInsert, 0, strlen($reqInsert) - 1) . "", __LINE__, __FILE__);
                }
                
                
                $reqCache  = "SELECT id_search, module FROM " . DB_TABLE_SEARCH_INDEX . " WHERE ";
                $reqCache .= "search='" . $this->search . "' AND id_user='" . $this->id_user . "'";
                if ($this->modules_conditions != '')
                {
                    $reqCache .= " AND " . $this->modules_conditions;
                }
                
                $request = $Sql->query_while ($reqCache, __LINE__, __FILE__);
                while ($row = $Sql->fetch_assoc($request))
                {   
                    $this->id_search[$row['module']] = $row['id_search'];
                }
                $Sql->query_close($request);
            }
        }
    }
    
    
    









    function get_results_by_id(&$results, $id_search = 0, $nb_lines = 0, $offset = 0)
    {
        global $Sql;
        $results = array();
        
        
        $reqResults = "SELECT module, id_content, title, relevance, link
                        FROM " . DB_TABLE_SEARCH_INDEX . " idx, " . DB_TABLE_SEARCH_RESULTS . " rst
                        WHERE idx.id_search = '" . $id_search . "' AND rst.id_search = '" . $id_search . "'
                        AND id_user = '".$this->id_user."' ORDER BY relevance DESC ";
        if ($nb_lines > 0)
        {
            $reqResults .= $Sql->limit($offset, $nb_lines);
        }
        
        
        $request = $Sql->query_while ($reqResults, __LINE__, __FILE__);
        while ($result = $Sql->fetch_assoc($request))
        {
            $results[] = $result;
        }
        $nbResults = $Sql->num_rows($request, "SELECT COUNT(*) " . DB_TABLE_SEARCH_RESULTS . " WHERE id_search = ".$id_search);
        $Sql->query_close($request);
        
        return $nbResults;
    }
    
    
    









    function get_results(&$results, &$module_ids, $nb_lines = 0, $offset = 0 )
    {
        global $Sql;

        $results = array();
        $num_modules = 0;
        $modules_conditions = '';
        
        
        foreach ($module_ids as $module_id)
        {
            
            if (in_array($module_id, array_keys($this->id_search)))
            {
                
                if ($num_modules > 0)
                {
                    $modules_conditions .= ", ";
                }
                $modules_conditions .= $this->id_search[$module_id];
                $num_modules++;
            }
        }
        
        
        $reqResults  = "SELECT module, id_content, title, relevance, link
                        FROM " . DB_TABLE_SEARCH_INDEX . " idx, " . DB_TABLE_SEARCH_RESULTS . " rst
                        WHERE (idx.id_search = rst.id_search) ";
        if ($modules_conditions != '')
        {
            $reqResults .= " AND rst.id_search  IN (" . $modules_conditions . ")";
        }
        $reqResults .= " ORDER BY relevance DESC ";
        if ( $nb_lines > 0 )
        {
            $reqResults .= $Sql->limit($offset, $nb_lines);
        }
        
        
        $request = $Sql->query_while ($reqResults, __LINE__, __FILE__);
        while ($result = $Sql->fetch_assoc($request))
        {
            $results[] = $result;
        }
        $nbResults = $Sql->num_rows($request, __LINE__, __FILE__  );
        
        $Sql->query_close($request);
        
        return $nbResults;
    }
    
    
    





    function insert_results(&$requestAndResults)
    {
        global $Sql;
        
        $nbReqSEARCH = 0;
        $reqSEARCH = "";
        $results = array();
        
        
        foreach ($requestAndResults as $module_name => $request)
        {
            if (!is_array($request))
            {
                if (!$this->is_in_cache($module_name))
                {   
                    if ($nbReqSEARCH > 0)
                    {
                        $reqSEARCH .= " UNION ";
                    }
                    
                    $reqSEARCH .= "(".trim( $request, ' ;' ).")";
                    $nbReqSEARCH++;
                }
            }
            else
            {
                $results += $requestAndResults[$module_name];
            }
        }
        
        $nbResults = count($results);
        
        if (($nbReqSEARCH > 0) || ($nbResults > 0))
        {
            $nbReqInsert = 0;
            $reqInsert = '';
            
            
            for ($nbReqInsert = 0; $nbReqInsert < $nbResults; $nbReqInsert++)
            {
                $row = $results[$nbReqInsert];
                if ($nbReqInsert > 0)
                {
                    $reqInsert .= ',';
                }
                $reqInsert .= " ('".$row['id_search']."','".$row['id_content']."','".addslashes($row['title'])."',";
                $reqInsert .= "'".$row['relevance']."','".$row['link']."')";
            }

            if (!empty($reqSEARCH))
            {   
                $request = $Sql->query_while($reqSEARCH, __LINE__, __FILE__);
                while ($row = $Sql->fetch_assoc($request))
                {
                    if ($nbReqInsert > 0)
                    {
                        $reqInsert .= ',';
                    }
                    $reqInsert .= " ('".$row['id_search']."','".$row['id_content']."','".addslashes($row['title'])."',";
                    $reqInsert .= "'".$row['relevance']."','".$row['link']."')";
                    $nbReqInsert++;
                }
            }
            
            
            if ($nbReqInsert > 0)
            {
                $Sql->query_inject("INSERT INTO " . DB_TABLE_SEARCH_RESULTS . " VALUES ".$reqInsert, __LINE__, __FILE__);
            }
        }
    }
    
    
    




    function is_search_id_in_cache($id_search)
    {
        if (in_array($id_search, $this->id_search))
        {
            return true;
        }

        global $Sql;
        $id = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_SEARCH_INDEX . " WHERE id_search = '" . $id_search . "' AND id_user = '" . $this->id_user . "';", __LINE__, __FILE__);
        if ($id == 1)
        {
            
            $reqUpdate  = "UPDATE " . DB_TABLE_SEARCH_INDEX . " SET times_used=times_used+1, last_search_use='" . time() . "' WHERE ";
            $reqUpdate .= "id_search = '" . $id_search . "' AND id_user = '" . $this->id_user . "';";
            $Sql->query_inject($reqUpdate, __LINE__, __FILE__);
            
            return true;
        }
        return false;
    }
    
    
    




    function is_in_cache($module_id)
    {
        return in_array($module_id, $this->cache);
    }
    
    
    



    function modules_in_cache()
    {
        return array_keys($this->id_search);
    }
    
    



    function get_ids()
    {
        return $this->id_search;
    }
    
    
    





    function _get_modules_conditions(&$modules)
    



    {
        $nbModules = count($modules);
        $modules_conditions = '';
        if ($nbModules > 0)
        {
            $modules_conditions .= " ( ";
            $i = 0;
            foreach ($modules as $module_id => $options)
            {
                $modules_conditions .= "( module='" . $module_id . "' AND options='" . md5(implode('|', $options)) . "' )";
                
                if ($i < ($nbModules - 1))
                {
                    $modules_conditions .= " OR ";
                }
                else
                {
                    $modules_conditions .= " ) ";
                }
                $i++;
            }
        }
        
        return $modules_conditions;
    }
    
    
    var $id_search;
    var $search;
    var $modules;
    var $modules_conditions;
    var $id_user;
    var $errors;

}

?>

