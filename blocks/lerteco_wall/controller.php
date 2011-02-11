<?php 
defined('C5_EXECUTE') or die("Access Denied.");

Loader::model('postings', 'lerteco_wall');

class LertecoWallBlockController extends BlockController {

    protected $btTable = 'btLertecoWall';
    protected $btInterfaceWidth = "320";
    protected $btInterfaceHeight = "200";

    /**
     * Used for localization. If we want to localize the name/description we have to include this
     */
    public function getBlockTypeDescription() {
            return t("Include a wall or activity feed of recent postings onto a page.");
    }

    public function getBlockTypeName() {
            return t("Concrete Wall");
    }


    public function view() {
        $postinglist = new PostingList();

        if ($this->btDisplayType == 1) {
            // user wall. get the userid of the user whose page this is
            $v = View::getInstance();

            if ($v->controller != null) {
                $postinglist->filterByWall($v->controller->getvar('profile')->uID);
            }
        } elseif ($this->btDisplayType == 2) {
            //activity list. nothing special to do here.
        }

        $postings = $postinglist->get($this->btMaxPostings);

        $this->set('postings', $postings);
    }

    public function add() {
    }
    public function edit() {
    }


}
	
?>