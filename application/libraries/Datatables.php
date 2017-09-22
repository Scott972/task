<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
  /**
  * Ignited Datatables
  *
  * This is a wrapper class/library based on the native Datatables server-side implementation by Allan Jardine
  * found at http://datatables.net/examples/data_sources/server_side.html for CodeIgniter
  *
  * @package    CodeIgniter
  * @subpackage libraries
  * @category   library
  * @version    0.7
  * @author     Vincent Bambico <metal.conspiracy@gmail.com>
  *             Yusuf Ozdemir <yusuf@ozdemir.be>
  * @link       http://codeigniter.com/forums/viewthread/160896/
  */
  class Datatables
  {
    /**
    * Global container variables for chained argument results
    *
    */
    protected $ci;
    protected $table;
    protected $distinct;
    protected $group_by;
    protected $having;
    protected $select         = array();
    protected $joins          = array();
    protected $columns        = array();
    protected $where          = array();
    protected $filter         = array();
    protected $add_columns    = array();
    protected $edit_columns   = array();
    protected $unset_columns  = array();
    protected $where_in = array();
    protected $ar_limit;
    protected $ar_offset;
    protected $order_by;

    /**
    * Copies an instance of CI
    */
    public function __construct()
    {
      $this->ci =& get_instance();
    }

    /**
     * Generates the ORDER_BY portion of the query
     *
     * @param string $column
     * @param string $order
     * @return mixed
     */
    public function order_by($column, $order) {

      $this->order_by = $column;
      $this->ci->db->order_by($column, $order);
      return $this;
    }

    
    /**
    * If you establish multiple databases in config/database.php this will allow you to
    * set the database (other than $active_group) - more info: http://codeigniter.com/forums/viewthread/145901/#712942
    */
    public function set_database($db_name)
    {
			$db_data = $this->ci->load->database($db_name, TRUE);
			$this->ci->db = $db_data;
		}

    /**
    * Generates the SELECT portion of the query
    *
    * @param string $columns
    * @param bool $backtick_protect
    * @return mixed
    */
    public function select($columns, $backtick_protect = TRUE)
    {
      foreach($this->explode(',', $columns) as $val)
      {
        $column = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $val));
        $this->columns[] =  $column;
        $this->select[$column] =  trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $val));
      }

      $this->ci->db->select($columns, $backtick_protect);
      return $this;
    }

    public function limit($value, $offset = '')
    {
      $this->ar_limit = (int) $value;

      if ($offset != '')
      {
        $this->ar_offset = (int) $offset;
      }
      $this->ci->db->limit($value, $offset);

      return $this;
    }
    /**
    * Generates the DISTINCT portion of the query
    *
    * @param string $column
    * @return mixed
    */
    public function distinct($column)
    {
      $this->distinct = $column;
      $this->ci->db->distinct($column);
      return $this;
    }
    
    public function having($column) {
        $this->having= $column;
        $this->ci->db->having($column);
        return $this;
    }

    /**
    * Generates the GROUP_BY portion of the query
    *
    * @param string $column
    * @return mixed
    */
    public function group_by($column)
    {
      $this->group_by = $column;
      $this->ci->db->group_by($column);
      return $this;
    }

    /**
    * Generates the FROM portion of the query
    *
    * @param string $table
    * @return mixed
    */
    public function from($table)
    {
      $this->table = $table;
      $this->ci->db->from($table);
      return $this;
    }

    /**
    * Generates the JOIN portion of the query
    *
    * @param string $table
    * @param string $fk
    * @param string $type
    * @return mixed
    */
    public function join($table, $fk, $type = NULL)
    {
      $this->joins[] = array($table, $fk, $type);
      $this->ci->db->join($table, $fk, $type);
      return $this;
    }

    /**
    * Generates the WHERE portion of the query
    *
    * @param mixed $key_condition
    * @param string $val
    * @param bool $backtick_protect
    * @return mixed
    */
    public function where($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
      $this->where[] = array($key_condition, $val, $backtick_protect);
      $this->ci->db->where($key_condition, $val, $backtick_protect);
      return $this;
    }

    public function where_in($key_condition, $val = NULL)
    {
      $this->where_in[] = array($key_condition, $val);
      $this->ci->db->where_in($key_condition, $val);
      return $this;
    }



    /**
    * Generates the WHERE portion of the query
    *
    * @param mixed $key_condition
    * @param string $val
    * @param bool $backtick_protect
    * @return mixed
    */
    public function or_where($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
      $this->where[] = array($key_condition, $val, $backtick_protect);
      $this->ci->db->or_where($key_condition, $val, $backtick_protect);
      return $this;
    }

    /**
    * Generates the WHERE portion of the query
    *
    * @param mixed $key_condition
    * @param string $val
    * @param bool $backtick_protect
    * @return mixed
    */
    public function like($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
      $this->where[] = array($key_condition, $val, $backtick_protect);
      $this->ci->db->like($key_condition, $val, $backtick_protect);
      return $this;
    }

    /**
    * Generates the WHERE portion of the query
    *
    * @param mixed $key_condition
    * @param string $val
    * @param bool $backtick_protect
    * @return mixed
    */
    public function filter($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
      $this->filter[] = array($key_condition, $val, $backtick_protect);
      return $this;
    }

    /**
    * Sets additional column variables for adding custom columns
    *
    * @param string $column
    * @param string $content
    * @param string $match_replacement
    * @return mixed
    */
    public function add_column($column, $content, $match_replacement = NULL)
    {
      $this->add_columns[$column] = array('content' => $content, 'replacement' => $this->explode(',', $match_replacement));
      return $this;
    }

    /**
    * Sets additional column variables for editing columns
    *
    * @param string $column
    * @param string $content
    * @param string $match_replacement
    * @return mixed
    */
    public function edit_column($column, $content, $match_replacement)
    {
      $this->edit_columns[$column][] = array('content' => $content, 'replacement' => $this->explode(',', $match_replacement));
      return $this;
    }

    /**
    * Unset column
    *
    * @param string $column
    * @return mixed
    */
    public function unset_column($column)
    {
      $this->unset_columns[] = $column;
      return $this;
    }

    /**
    * Builds all the necessary query segments and performs the main query based on results set from chained statements
    *
    * @param string charset
    * @return string
    */
    public function generate($charset = 'UTF-8')
    {
      $this->get_paging();
      $this->get_ordering();
      $this->get_filtering();
      return $this->produce_output($charset);
    }

    /**
    * Generates the LIMIT portion of the query
    *
    * @return mixed
    */
    protected function get_paging()
    {
      if(is_null($this->ar_limit)){
        $iStart = $this->ci->input->post('iDisplayStart');
        $iLength = $this->ci->input->post('iDisplayLength');
        $this->ci->db->limit(($iLength != '' && $iLength != '-1')? $iLength : 100, ($iStart)? $iStart : 0);
      }

    }

    /**
    * Generates the ORDER BY portion of the query
    *
    * @return mixed
    */
    protected function get_ordering()
    {
      
      if($this->check_mDataprop())
        $mColArray = $this->get_mDataprop();
      elseif($this->ci->input->post('sColumns'))
        $mColArray = explode(',', $this->ci->input->post('sColumns'));
      else
        $mColArray = $this->columns;

      $mColArray = array_values(array_diff($mColArray, $this->unset_columns));
      $columns = array_values(array_diff($this->columns, $this->unset_columns));
 
      for($i = 0; $i < intval($this->ci->input->post('iSortingCols')); $i++)
        if(isset($mColArray[intval($this->ci->input->post('iSortCol_' . $i))]) && in_array($mColArray[intval($this->ci->input->post('iSortCol_' . $i))], $columns) && $this->ci->input->post('bSortable_'.intval($this->ci->input->post('iSortCol_' . $i))) == 'true')
          $this->ci->db->order_by($mColArray[intval($this->ci->input->post('iSortCol_' . $i))], $this->ci->input->post('sSortDir_' . $i));
    }

    /**
    * Generates the LIKE portion of the query
    *
    * @return mixed
    */
    protected function get_filtering()
    {
      if($this->check_mDataprop())
        $mColArray = $this->get_mDataprop();
      elseif($this->ci->input->post('sColumns'))
        $mColArray = explode(',', $this->ci->input->post('sColumns'));
      else
        $mColArray = $this->columns;

      $sWhere = '';
 
      $sSearch = $this->ci->db->escape_like_str($this->ci->input->post('sSearch'));
      $mColArray = array_values(array_diff($mColArray, $this->unset_columns));
      $columns = array_values(array_diff($this->columns, $this->unset_columns));

      if($sSearch != '')
        for($i = 0; $i < count($mColArray); $i++)
          if($this->ci->input->post('bSearchable_' . $i) == 'true' && in_array($mColArray[$i], $columns))
            $sWhere .= $this->select[$mColArray[$i]] . " LIKE '%" . $sSearch . "%' OR ";

      $sWhere = substr_replace($sWhere, '', -3);

      if($sWhere != '')
        $this->ci->db->where('(' . $sWhere . ')');

      for($i = 0; $i < intval($this->ci->input->post('iColumns')); $i++)
      {
        if(isset($_POST['sSearch_' . $i]) && $this->ci->input->post('sSearch_' . $i) != '' && in_array($mColArray[$i], $columns))
        {
          $miSearch = explode(',', $this->ci->input->post('sSearch_' . $i));

          foreach($miSearch as $val)
          {
            if(preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", trim($val), $matches))
              $this->ci->db->where($this->select[$mColArray[$i]].' '.$matches[1], $matches[3]);
            else
              $this->ci->db->where($this->select[$mColArray[$i]].' LIKE', '%'.$val.'%');
          }
        }
      }

      foreach($this->filter as $val)
        $this->ci->db->where($val[0], $val[1], $val[2]);
    }

    /**
    * Compiles the select statement based on the other functions called and runs the query
    *
    * @return mixed
    */
    protected function get_display_result()
    {
      $data = $this->ci->db->get();
      return $data;
    }

    /**
    * Builds a JSON encoded string data
    *
    * @param string charset
    * @return string
    */
    protected function produce_output($charset)
    {
      $aaData = array();
      $rResult = $this->get_display_result();
      $iTotal = $this->get_total_results();
      
      //only run another query to get the filtered count if filters have been applied
      //otherwise just use the iTotal count
      $iFilteredTotal = $iTotal;

      if(is_array($this->ci->input->post())) {      
        foreach($this->ci->input->post() as $key => $value) {
            if(strpos($key,'sSearch') === 0 && strlen($value)) {
              $iFilteredTotal = $this->get_total_results(TRUE);
              break;
            }
        }
      }

      foreach($rResult->result_array() as $row_key => $row_val)
      {
        $aaData[$row_key] = ($this->check_mDataprop())? $row_val : array_values($row_val);

        foreach($this->add_columns as $field => $val)
          if($this->check_mDataprop())
            $aaData[$row_key][$field] = $this->exec_replace($val, $aaData[$row_key]);
          else
            $aaData[$row_key][] = $this->exec_replace($val, $aaData[$row_key]);

        foreach($this->edit_columns as $modkey => $modval)
          foreach($modval as $val)
            $aaData[$row_key][($this->check_mDataprop())? $modkey : array_search($modkey, $this->columns)] = $this->exec_replace($val, $aaData[$row_key]);

        $aaData[$row_key] = array_diff_key($aaData[$row_key], ($this->check_mDataprop())? $this->unset_columns : array_intersect($this->columns, $this->unset_columns));

        if(!$this->check_mDataprop())
          $aaData[$row_key] = array_values($aaData[$row_key]);
      }

      $sColumns = array_diff($this->columns, $this->unset_columns);
      $sColumns = array_merge_recursive($sColumns, array_keys($this->add_columns));

      $sOutput = array
      (
        'sEcho'                => intval($this->ci->input->post('sEcho')),
        'iTotalRecords'        => $iTotal,
        'iTotalDisplayRecords' => $iFilteredTotal,
        'aaData'               => $aaData,
        'sColumns'             => implode(',', $sColumns)
      );
      

      if(strtolower($charset) == 'utf-8')
        return json_encode($sOutput);
      else
        return $this->jsonify($sOutput);
    }

    /**
    * Get result count
    *
    * @return integer
    */
    protected function get_total_results($filtering = FALSE)
    {
      if($filtering)
        $this->get_filtering();

      foreach($this->joins as $val)
        $this->ci->db->join($val[0], $val[1], $val[2]);

      foreach($this->where as $val)
        $this->ci->db->where($val[0], $val[1], $val[2]);
      
      if($this->group_by) {
          $this->ci->db->group_by($this->group_by);
      }
      
      if($this->having) {
          $this->ci->db->having($this->having);
      }

      return $this->ci->db->count_all_results($this->table);
    }

    /**
    * Runs callback functions and makes replacements
    *
    * @param mixed $custom_val
    * @param mixed $row_data
    * @return string $custom_val['content']
    */
    protected function exec_replace($custom_val, $row_data)
    {
      $replace_string = '';

      if(isset($custom_val['replacement']) && is_array($custom_val['replacement']))
      {
        foreach($custom_val['replacement'] as $key => $val)
        {
          $sval = preg_replace("/(?<!\w)([\'\"])(.*)\\1(?!\w)/i", '$2', trim($val));

          if(preg_match('/(\w+)\((.*)\)/i', $val, $matches) && function_exists($matches[1]))
          {
            $func = $matches[1];
            $args = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[,]+/", $matches[2], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            foreach($args as $args_key => $args_val)
            {
              $args_val = preg_replace("/(?<!\w)([\'\"])(.*)\\1(?!\w)/i", '$2', trim($args_val));
              $args[$args_key] = (in_array($args_val, $this->columns))? ($row_data[($this->check_mDataprop())? $args_val : array_search($args_val, $this->columns)]) : $args_val;
            }

            $replace_string = call_user_func_array($func, $args);
          }
          elseif(in_array($sval, $this->columns))
            $replace_string = $row_data[($this->check_mDataprop())? $sval : array_search($sval, $this->columns)];
          else
            $replace_string = $sval;

          $custom_val['content'] = str_ireplace('$' . ($key + 1), $replace_string, $custom_val['content']);
        }
      }

      return $custom_val['content'];
    }

    /**
    * Check mDataprop
    *
    * @return bool
    */
    protected function check_mDataprop()
    {
      if(!$this->ci->input->post('mDataProp_0'))
        return FALSE;

      for($i = 0; $i < intval($this->ci->input->post('iColumns')); $i++)
        if(!is_numeric($this->ci->input->post('mDataProp_' . $i)))
          return TRUE;

      return FALSE;
    }

    /**
    * Get mDataprop order
    *
    * @return mixed
    */
    protected function get_mDataprop()
    {
      $mDataProp = array();

      for($i = 0; $i < intval($this->ci->input->post('iColumns')); $i++)
        $mDataProp[] = $this->ci->input->post('mDataProp_' . $i);

      return $mDataProp;
    }

    /**
    * Return the difference of open and close characters
    *
    * @param string $str
    * @param string $open
    * @param string $close
    * @return string $retval
    */
    protected function balanceChars($str, $open, $close)
    {
      $openCount = substr_count($str, $open);
      $closeCount = substr_count($str, $close);
      $retval = $openCount - $closeCount;
      return $retval;
    }

    /**
    * Explode, but ignore delimiter until closing characters are found
    *
    * @param string $delimiter
    * @param string $str
    * @param string $open
    * @param string $close
    * @return mixed $retval
    */
    protected function explode($delimiter, $str, $open = '(', $close=')')
    {
      $retval = array();
      $hold = array();
      $balance = 0;
      $parts = explode($delimiter, $str);

      foreach($parts as $part)
      {
        $hold[] = $part;
        $balance += $this->balanceChars($part, $open, $close);

        if($balance < 1)
        {
          $retval[] = implode($delimiter, $hold);
          $hold = array();
          $balance = 0;
        }
      }

      if(count($hold) > 0)
        $retval[] = implode($delimiter, $hold);

      return $retval;
    }

    /**
    * Workaround for json_encode's UTF-8 encoding if a different charset needs to be used
    *
    * @param mixed result
    * @return string
    */
    protected function jsonify($result = FALSE)
    {
      if(is_null($result))
        return 'null';

      if($result === FALSE)
        return 'false';

      if($result === TRUE)
        return 'true';

      if(is_scalar($result))
      {
        if(is_float($result))
          return floatval(str_replace(',', '.', strval($result)));

        if(is_string($result))
        {
          static $jsonReplaces = array(array('\\', '/', '\n', '\t', '\r', '\b', '\f', '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
          return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $result) . '"';
        }
        else
          return $result;
      }

      $isList = TRUE;

      for($i = 0, reset($result); $i < count($result); $i++, next($result))
      {
        if(key($result) !== $i)
        {
          $isList = FALSE;
          break;
        }
      }

      $json = array();

      if($isList)
      {
        foreach($result as $value)
          $json[] = $this->jsonify($value);

        return '[' . join(',', $json) . ']';
      }
      else
      {
        foreach($result as $key => $value)
          $json[] = $this->jsonify($key) . ':' . $this->jsonify($value);

        return '{' . join(',', $json) . '}';
      }
    }


      function query($sql, $binds = FALSE, $return_object = TRUE)
      {
          if ($sql == '')
          {
              if ($this->db_debug)
              {
                  log_message('error', 'Invalid query: '.$sql);
                  return $this->display_error('db_invalid_query');
              }
              return FALSE;
          }

          // Verify table prefix and replace if necessary
          if ( ($this->dbprefix != '' AND $this->swap_pre != '') AND ($this->dbprefix != $this->swap_pre) )
          {
              $sql = preg_replace("/(\W)".$this->swap_pre."(\S+?)/", "\\1".$this->dbprefix."\\2", $sql);
          }

          // Compile binds if needed
          if ($binds !== FALSE)
          {
              $sql = $this->compile_binds($sql, $binds);
          }

          // Is query caching enabled?  If the query is a "read type"
          // we will load the caching class and return the previously
          // cached query if it exists
          if ($this->cache_on == TRUE AND stristr($sql, 'SELECT'))
          {
              if ($this->_cache_init())
              {
                  $this->load_rdriver();
                  if (FALSE !== ($cache = $this->CACHE->read($sql)))
                  {
                      return $cache;
                  }
              }
          }

          // Save the  query for debugging
          if ($this->save_queries == TRUE)
          {
              $this->queries[] = $sql;
          }

          // Start the Query Timer
          $time_start = list($sm, $ss) = explode(' ', microtime());

          // Run the Query
          if (FALSE === ($this->result_id = $this->simple_query($sql)))
          {
              if ($this->save_queries == TRUE)
              {
                  $this->query_times[] = 0;
              }

              // This will trigger a rollback if transactions are being used
              $this->_trans_status = FALSE;

              if ($this->db_debug)
              {
                  // grab the error number and message now, as we might run some
                  // additional queries before displaying the error
                  $error_no = $this->_error_number();
                  $error_msg = $this->_error_message();

                  // We call this function in order to roll-back queries
                  // if transactions are enabled.  If we don't call this here
                  // the error message will trigger an exit, causing the
                  // transactions to remain in limbo.
                  $this->trans_complete();

                  // Log and display errors
                  log_message('error', 'Query error: '.$error_msg);
                  return $this->display_error(
                      array(
                          'Error Number: '.$error_no,
                          $error_msg,
                          $sql
                      )
                  );
              }

              return FALSE;
          }

          // Stop and aggregate the query time results
          $time_end = list($em, $es) = explode(' ', microtime());
          $this->benchmark += ($em + $es) - ($sm + $ss);

          if ($this->save_queries == TRUE)
          {
              $this->query_times[] = ($em + $es) - ($sm + $ss);
          }

          // Increment the query counter
          $this->query_count++;

          // Was the query a "write" type?
          // If so we'll simply return true
          if ($this->is_write_type($sql) === TRUE)
          {
              // If caching is enabled we'll auto-cleanup any
              // existing files related to this particular URI
              if ($this->cache_on == TRUE AND $this->cache_autodel == TRUE AND $this->_cache_init())
              {
                  $this->CACHE->delete();
              }

              return TRUE;
          }

          // Return TRUE if we don't need to create a result object
          // Currently only the Oracle driver uses this when stored
          // procedures are used
          if ($return_object !== TRUE)
          {
              return TRUE;
          }

          // Load and instantiate the result driver

          $driver			= $this->load_rdriver();
          $RES			= new $driver();
          $RES->conn_id	= $this->conn_id;
          $RES->result_id	= $this->result_id;

          if ($this->dbdriver == 'oci8')
          {
              $RES->stmt_id		= $this->stmt_id;
              $RES->curs_id		= NULL;
              $RES->limit_used	= $this->limit_used;
              $this->stmt_id		= FALSE;
          }

          // oci8 vars must be set before calling this
          $RES->num_rows	= $RES->num_rows();

          // Is query caching enabled?  If so, we'll serialize the
          // result object and save it to a cache file.
          if ($this->cache_on == TRUE AND $this->_cache_init())
          {
              // We'll create a new instance of the result object
              // only without the platform specific driver since
              // we can't use it with cached data (the query result
              // resource ID won't be any good once we've cached the
              // result object, so we'll have to compile the data
              // and save it)
              $CR = new CI_DB_result();
              $CR->num_rows		= $RES->num_rows();
              $CR->result_object	= $RES->result_object();
              $CR->result_array	= $RES->result_array();

              // Reset these since cached objects can not utilize resource IDs.
              $CR->conn_id		= NULL;
              $CR->result_id		= NULL;

              $this->CACHE->write($sql, $CR);
          }

          return $RES;
      }
  }
/* End of file Datatables.php */
/* Location: ./application/libraries/Datatables.php */