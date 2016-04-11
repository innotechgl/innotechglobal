<?php

class sCMS_db
{

//    use mainEngine;

    //protected $engine;
    public $rows = array();
    public $total_num_rows = 0;
    public $limit = 0;
    public $page_no = 1;
    public $num_of_pages = 0;
    /**
     *
     * @var mysqli
     */
    protected $base = '';
    private $dbaseName = '';
    private $host = 'localhost';
    private $port = 3306;
    private $username = '';
    private $password = '';

    public function __construct($dbname = '', $host = '', $port = 0, $user = '', $pass = '')
    {
        global $engine;
        $this->engine = &$engine;
        $this->dbaseName = $dbname;
        $this->host = $host;
        $this->port = $port;
        $this->username = $user;
        $this->password = $pass;
        $this->limit = $this->engine->settings->general->limit;
    }

    /**
     * Set page Number
     * @param int $no
     */
    public function set_page_no($no = 1)
    {
        $num = (int)$no;
        if ($num <= 0) {
            $this->page_no = 1;
        } else {
            $this->page_no = $num;
        }
    }

    public function db_open()
    {
        if (!is_resource($this->base)) {
            $this->base =
                mysqli_connect(
                    $this->host,
                    $this->username,
                    $this->password,
                    $this->dbaseName)
            or trigger_error(mysqli_error(), E_USER_ERROR);
            $res = mysqli_query($this->base, "SET NAMES 'utf8'");
        }
    }

    public function db_close()
    {
        mysqli_close($this->base);
    }

    public function query($query = '', $addopt = false, $num_rows = false)
    {
        global $engine;
        // Set deault limit
        $limit = '';
        // Calculate limit if exists
        if ($this->limit > 0 && $addopt == true) {
            $start_limit = ($this->page_no - 1) * $this->limit;
            $limit = ' LIMIT ' . $start_limit . ', ' . $this->limit;
        }
        $this->rows = array();
        // Add LIMIT to query
        $query .= $limit;
        try {
            // Start time of query
            $starttime = $this->microtime_float();
            // echo $query;
            $res = mysqli_query($this->base, $query) or die(mysqli_error($this->base));
            // End time of query
            $endtime = $this->microtime_float();
            $total_time = ($endtime - $starttime);
            // Add query to LOG
            $engine->log->addQueryToLog($query . " > Total time: " . $total_time);
            if (!$res) {
                throw new Exception(mysqli_error());
                exit();
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        $i = 0;
        if ($res !== false) {
            while ($record = mysqli_fetch_assoc($res)) {
                foreach ($record as $row_key => $row_val) {
                    $this->rows[$i][$row_key] = $row_val;
                }
                $i++;
            }
            mysqli_free_result($res);
        }
        // Get num_of_rows
        if ($num_rows == true && $this->limit > 0) {
            // change NUM ROWS
            $query = preg_replace('/(?<=SELECT)(?:[A-Z a-z 0-9.*,`])+(?=FROM)/', ' COUNT(*) AS num_of_rows ', $query);
            $query = preg_replace('/LIMIT ([0-9]+,.[0-9]+)/', '', $query);
            $res = mysqli_query($this->base, $query) or die($engine->log->write("sutija", "mysql", mysqli_error(), 1));
            $rec = mysqli_fetch_assoc($res);
            if (!isset($rec['num_of_rows'])) {
                $this->total_num_rows = mysqli_num_rows($res);
            } else {
                $this->total_num_rows = $rec['num_of_rows'];
            }
            $this->num_of_pages = ceil($this->total_num_rows / $this->limit);
            mysqli_free_result($res);
        }
        $this->limit = 0;
    }

    protected function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
     * @param string $query
     */
    public function callProcedure($query)
    {
        $this->rows = array();
        $result = $this->base->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->rows[] = $row;
        }
        mysqli_free_result($result);
        $this->base->next_result();
    }

    /**
     *
     * @global sCMS_engine $engine
     * @param string $query
     * @return boolean
     */
    public function insertQuery($query = '')
    {
        global $engine;
        $engine->log->write("sutija", "mysqli_query", $query, 1);
        $res = $this->base->query($query) or die($this->printQuery($query));
        if ($res) {
            if (preg_match("/INSERT/", $query)) {
                return mysqli_insert_id($this->base);
            } else {
                return true;
            }
        } else {
            return false;
        }
        mysqli_free_result($res);
    }

    protected function printQuery($query){
        echo "ERROR!";
        echo $query;
    }

    /*
     * MICROTIME FLOAT
     * We use it for time calculation in performance debuging 
     */

    public function realEscape($val = '')
    {
        return $this->base->real_escape_string($val);
    }

    public function backupDb()
    {
        $dump = 'mysqldump -u' . $this->username . ' -p' . $this->password . ' --default-character-set=utf8 ' . $this->dbaseName . ' > ../backup/mm_' . time() . '.sql';
        exec($dump, $eback, $backi);
        print_R($eback);
    }

    /**
     * @param Int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = (int)$limit;
    }

    public function __destruct()
    {
    }
}

?>