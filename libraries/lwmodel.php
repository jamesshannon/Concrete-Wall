<?php 

defined('C5_EXECUTE') or die("Access Denied.");

/**
* (Lerteco) Concrete Model Class
* The model class extends the ADOdb active record class, allowing items that inherit from it to use the automatic create, updating, read and delete functionality it provides.
* Modified by James Shannon to combine with the Object class
* @link http://phplens.com/lens/adodb/docs-active-record.htm
* @author Andrew Embler <andrew@concrete5.org>
* @link http://www.concrete5.org
* @package Utilities
* @license http://www.opensource.org/licenses/mit-license.php MIT
*
*/
class LWModel extends ADOdb_Active_Record {
    var $error = '';

    public function __construct() {
        $db = Loader::db();
        parent::__construct();
    }

    function loadError($error) {
        $this->error = $error;
    }

    function isError() {
        $args = func_get_args();
        if ($args[0]) {
            return $this->error == $args[0];
        } else {
            return $this->error;
        }
    }

    function getError() {
        return $this->error;
    }

    public function setPropertiesFromArray($arr) {
        foreach($arr as $key => $prop) {
            $this->{$key} = $prop;
        }
    }

    public function setGetFromProperties() {
        foreach ($this->GetAttributeNames() as $key) {
            $_GET[$key] = $this->{$key};
        }
    }
}

?>