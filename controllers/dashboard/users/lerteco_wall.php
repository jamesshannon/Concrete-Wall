<?php 
defined('C5_EXECUTE') or die(_("Access Denied."));

class DashboardUsersLertecoWallController extends Controller {

    public function view($action = null) {
	Loader::model('postings', 'lerteco_wall');
        $valt = Loader::helper('validation/token');

        $type = new PostingType();
        $type->LoadOrUpdateOrRegister(null, 'friend_add', 'Friend Added', 'person:are/is/ now friends with %1$u', 1, PostingType::SHAREWITH_ALL);

        //$type = new PostingType();
        //$type->LoadOrUpdateOrRegister(null, 'like_add', 'Likes', 'person:like/likes/ the [link here]', 1, PostingType::SHAREWITH_ALL);

        $types = new PostingTypeList();

        if ($this->isPost() && $valt->validate('wall_admin_update')) {
            foreach ($types->get() as $type) {
                $type->ptActive = $this->post('active:' . $type->ptID);
                
                $type->Save();
            }

            $this->redirect('/dashboard/users/lerteco_wall', 'saved');
        } elseif ($action == 'saved') {
            $this->set('message', t("Updated Posting Types"));
        }

        $this->set('posting_types', $types);
    }

}

?>