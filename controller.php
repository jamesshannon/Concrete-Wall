<?php

defined('C5_EXECUTE') or die(_("Access Denied."));

/* Installer for the Lerteco Wall Package
 * copyright Lerteco
 *
 * Included icon is from *********
 */

class LertecoWallPackage extends Package {

    protected $pkgHandle = 'lerteco_wall';
    protected $appVersionRequired = '5.4.1.1';
    protected $pkgVersion = '0.8.0';

    public function getPackageDescription() {
	return t("Adds functionality to enable a page where recent site activities are posted. Similar to The Facebook's wall.");
    }

    public function getPackageName() {
	return t("Concrete Wall");
    }

    public function upgrade(){
	$result = parent::upgrade();
        $this->configure();
        
	return $result;
    }

    public function install() {
	$pkg = parent::install();
        
	$this->configure();

        //should only have to install once... not on upgrades
        //BlockType::installBlockTypeFromPackage('lerteco_notify', $pkg);
    }

    public function uninstall() {
	parent::uninstall();
    }

    public function configure() {
	$pkg = Package::getByHandle('lerteco_wall');

        Loader::model('single_page');

        if (Page::getByPath('/dashboard/users/lerteco_wall')->cID == '') {
            // Admin Page
            $page = SinglePage::add('/dashboard/users/lerteco_wall', $pkg);
            $page->update(array('cName'=>t('Concrete Wall')));
        }
    }

}

?>