<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));

class DashboardUsersLertecoWallController extends Controller {

    public function view() {
	Loader::model('postings', 'lerteco_wall');

        $type = new PostingType();
        //$type->Register(null, 'friend_add', 'Friend Added', 'is now friends with %1$u', array(1), PostingType::SHAREWITH_ALL);

        $types = new PostingTypeList();

        $this->set('posting_types', $types);
    }

}

?>