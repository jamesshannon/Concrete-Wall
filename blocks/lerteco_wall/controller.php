<?php 
defined('C5_EXECUTE') or die("Access Denied.");

class LertecoWallBlockController extends BlockController {

    protected $btTable = 'btLertecoWall';
    protected $btInterfaceWidth = "250";
    protected $btInterfaceHeight = "120";

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
    }

    public function __call($nm, $a) {
    }

    public function add() {
    }
    public function edit() {
    }


}
	
?>