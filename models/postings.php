<?php
defined('C5_EXECUTE') or die("Access Denied.");

/**
 * The postings class represents postings and postingtypes, along with the list classes
 *
 * @package lerteco_wall
 * @category Models
 * @author James Shannon <james@jamesshannon.com>
 * @copyright  Copyright (c) Lerteco
 *
 */

Loader::model('package');
Loader::library('lwmodel', 'lerteco_wall');
Loader::helper('postings', 'lerteco_wall');

class PostingType extends LWModel {
    const SHAREWITH_FRIENDS = 1;
    const SHAREWITH_ALL = 2;

    var $_table = 'LWPostingTypes';

    public $pkg = null;

    function PostingType($id = null) {
        parent::__construct();

        if ($id != null) {
            $this->Load($id);
        }
    }

    function Load($id, $bindarr = false) {
        parent::Load("ptID = $id", $bindarr);
        $this->pkg = PostingsHelper::pkg($this->ptPkgID);
    }

    function LoadByPkgTypeKey($pkg, $type) {
        $this->pkg = PostingsHelper::pkg($pkg);
        
        parent::Load("ptPkgID = " . (is_null($this->pkg) ? "0" : $this->pkg->getPackageID()) . " AND ptCode = '" . mysql_real_escape_string($type) . "'");
    }

    function LoadOrUpdateOrRegister($pkg, $type, $name, $post_template, $post_template_example_arr, $share_with) {
        $this->pkg = PostingsHelper::pkg($pkg);
        $type = strtolower($type);

        $post_template_example_arr = PostingsHelper::prepData($post_template_example_arr);

        // bug where a too-long type would get truncated into the db and then not get pulled back when we look for the full thing
        // we could truncate now, but if the truncated length is ambiguous, it'll run into problems down the road
        if (strlen($type) > 50) {
            throw new Exeption("Type cannot be longer than 50 characters");
        }

        // first we look in the db for an existing entry and load that if found.
        // if found, we might update (but only the name, template, and example
        // if not found, we create a new one for them

        $this->LoadByPkgTypeKey($this->pkg, $type);
        
        if ($this->ptID) {
            //should we update?
            if ($this->ptName != $name || $this->ptTemplate != $post_template || $this->ptExampleData != $post_template_example_arr) {
                $this->ptName = $name;
                $this->ptTemplate = $post_template;
                $this->ptExampleData = $post_template_example_arr;

                $this->Save();
            }
        } else {
            //or make a new entry
            $this->ptPkgID = is_null($this->pkg) ? 0 : $this->pkg->getPackageID();
            $this->ptCode = $type;
            $this->ptName = $name;
            $this->ptTemplate = $post_template;
            $this->ptExampleData = $post_template_example_arr;
            $this->ptShareWith = $share_with;
            $this->ptUserOverrideShare = true;
            $this->ptActive = true;

            $this->Save();
        }
    }

    function getPackageName() {
        if (is_null($this->ptPkgID) || $this->ptPkgID == 0) {
            return "concrete5 Core";
        } else {
            if (is_null($this->pkg)) {
                $this->pkg = PostingsHelper::pkg($this->ptPkgID);
            }

            return $this->pkg->getPackageName();
        }
    }

    
}

class Posting extends LWModel {
    var $_table = 'LWPostings';

    public $ui = null;
    public $type = null;

    function Posting($id = null) {
        parent::__construct();

        if ($id != null) {
            $this->Load($id);
        }
    }

    function Load($id, $bindarr = false) {
        parent::Load("pID = $id", $bindarr);
    }

    function Add($pkg, $type, $uID, $data) {
        $type = new PostingType();
        $type->LoadByPkgTypeKey($pkg, $type);

        $this->AddWithType($type, $uID, $data);
    }

    function AddWithType($posting_type, $uID, $data) {
        $this->pptID = $posting_type->ptID;
        $this->pUID = $uID;
        $this->pData = PostingsHelper::prepData($data);

        $this->Save();
    }

    function getUserInfo() {
        if ($this->pUID == null) {
            return null;
        } elseif ($this->ui == null) {
            $this->ui = UserInfo::getByID($this->pUID);
        }

        return $this->ui;
    }

    function getType() {
        if ($this->pptID == null) {
            return null;
        } elseif ($this->type == null) {
            $this->type = new PostingType();
            $this->type->Load($this->pptID);
        }

        return $this->type;
    }
}

class PostingTypeList extends DatabaseItemList {
    public $sets = array();
    protected $itemsPerPage = 10;

    function __construct() {
        $this->setQuery("select ptID FROM LWPostingTypes");
        $this->filter('ptDeleteDate', null, 'IS');
        $this->sortBy('ptPkgID', 'ASC');
    }

    public function getByPkg($pkg, $itemsToGet = 0, $offset = 0) {
        $this->filter("ptPkgID", PostingsHelper::pkg($pkg)->getPackageID());
    }

    public function get($itemsToGet = 0, $offset = 0) {
        if (! count($this->sets)) {

            $r = parent::get($itemsToGet, $offset);

            foreach($r as $row) {
                $postingtype = new PostingType($row['ptID']);
                $this->sets[] = $postingtype;
            }
        }
        return $this->sets;
    }
}

class PostingList extends DatabaseItemList {
    public $sets = array();
    protected $itemsPerPage = 25;

    function __construct($viewerUID = null) {
        $this->setQuery("select pID FROM LWPostings p");
        $this->addToQuery("INNER JOIN LWPostingTypes pt ON p.pptID = pt.ptID");

        $this->filter('ptActive', true, '=');
        $this->filter('ptDeleteDate', null, 'IS');

        if ($viewerUID == null) {
            $viewer = new User();
            if ($viewer && intval($viewer->uID)) {
                $viewerUID = $viewer->uID;
            }
        }

        $permFilter = "(pt.ptShareWith = 2";
        if ($viewerUID != null) {
            $permFilter .= " || p.pUID = $viewerUID || p.pUID IN (SELECT uID FROM UsersFriends WHERE friendUID = $viewerUID)";
        }
        $permFilter .= ")";
        $this->filter(null, $permFilter);

        $this->sortBy('pCreateDate', 'DESC');
    }

    public function filterByWall($uID) {
        $this->filter("pUID", $uID);
    }

    public function filterByFeed($uID) {
    }

    public function filterByKeywords($kw) {
        //$db = Loader::db();
        //$this->filter(false, "(FileSets.fsName like " . $db->qstr('%' . $kw . '%') . ")");
    }

    public function getByPkg($pkg, $itemsToGet = 0, $offset = 0) {
        $this->filter("ptPkgID", PostingsHelper::pkg($pkg)->getPackageID());
    }

    public function get($itemsToGet = 0, $offset = 0) {
        if (! count($this->sets)) {
            $r = parent::get($itemsToGet, $offset);

            foreach($r as $row) {
                $posting = new Posting($row['pID']);
                $this->sets[] = $posting;
            }
        }
        return $this->sets;
    }
}



?>
