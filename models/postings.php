<?php
defined('C5_EXECUTE') or die("Access Denied.");

/**
 * The resource class represents a postings and postingtypes, along with the list classes
 *
 * @package lerteco_wall
 * @category Models
 * @author James Shannon <james@jamesshannon.com>
 * @copyright  Copyright (c) Lerteco
 *
 */

//ADODB_Active_Record::ClassHasMany('Resource', 'locations', 'rlrID', 'ResourceLocation');
//ADODB_Active_Record::ClassHasMany('Resource', 'files', 'rfrID', 'ResourceFile');

Loader::model('package');

class PostingType extends LModel {
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

    function  Load($id, $bindarr = false) {
        parent::Load("ptID=$id", $bindarr);
    }

    function Register($pkg, $type, $name, $post_template, $post_template_example_arr, $share_with) {
        $this->pkg = PostingType::pkg($pkg);

        $this->ptPkgID = is_null($this->pkg) ? null : $this->pkg->getPackageID();
        $this->ptCode = $type;
        $this->ptName = $name;
        $this->ptTemplate = $post_template;
        $this->ptExampleData = serialize($post_template_example_arr);
        $this->ptShareWith = $share_with;
        $this->ptUserOverrideShare = true;
        $this->ptActive = false;

        $this->Save();

        //need to update when pkg and type already exist
    }

    function getPackageName() {
        if (is_null($this->ptPkgID)) {
            return "Core";
        } else {
            if (is_null($this->pkg)) {
                $this->pkg = PostingType::pkg($this->ptPkgID);
            }

            return $pkg->getPackageName();
        }
    }

    static function pkg($pkg) {
        if (is_null($pkg)) {
            return null;
        } elseif (is_object($pkg)) {
            return $pkg;
        } elseif (is_numeric($pkg)) {
            return Package::getByID($pkg);
        } else {
            return Package::getByHandle($pkg);
        }
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

    public function filterByKeywords($kw) {
        //$db = Loader::db();
        //$this->filter(false, "(FileSets.fsName like " . $db->qstr('%' . $kw . '%') . ")");
    }

    public function getByPkg($pkg, $itemsToGet = 0, $offset = 0) {
        $this->filter("ptPkgID", PostingType::pkg($pkg)->getPackageID());
    }

    public function get($itemsToGet = 0, $offset = 0) {
        $r = parent::get($itemsToGet, $offset);
        
        foreach($r as $row) {
            $postingtype = new PostingType($row['ptID']);
            $this->sets[] = $postingtype;
        }

        return $this->sets;
    }
}



?>
