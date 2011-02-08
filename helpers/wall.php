    <?php
/**
 * Prints the posting, using external elements when necessary
 * @package lerteco_wall
 * @category Helpers
 * @author James Shannon <james@jamesshannon.com>
 * @copyright  Lerteco 2011
 */

defined('C5_EXECUTE') or die("Access Denied.");

class WallHelper {
    static function getGraffiti($template, $data, $time, $touser = false) {
        $element_test = array();
        $data = unserialize($data);

        $regexp = '/%(\d)\$([u|p])/';

        $matches = preg_match_all($regexp, $template, $element_test);

        if ($matches) {
            //at least one match of one of our special type specifiers
            $conversions = $element_test[0];
            $data_locs = $element_test[1];
            $type_specifiers = $element_test[2];

            for ($i = 0; $i < $matches; $i++) {
                //where in the array is the $arg?
                $loc = $data_locs[$i] - 1;
                $output = '';

                //which one of our special types is it?
                switch ($type_specifiers[$i]) {
                    //user
                    case 'u':
                        $ui = UserInfo::getByID($data[$loc]);

                        // we try to load an element and pass it a userinfo object. if we get something back, we go with that, otherwise a default user link
                        ob_start();
                            Loader::element('wall_user', array('userinfo' => $ui));
                            $output = ob_get_contents();
                        ob_end_clean();

                        if (! $output) {
                            $output = "<a href=\"" . BASE_URL . View::url('/profile', 'view', $ui->getUserID()) . "\">" . $ui->getUserName() . "</a>";
                        }
                        break;

                    case 'p':
                        $page = Page::getByID($data[$loc]);
                        
                        // we try to load an element and pass it a page object. if we get something back, we go with that, otherwise a default page link
                        ob_start();
                            Loader::element('wall_page', array('page' => $page));
                            $output = ob_get_contents();
                        ob_end_clean();

                        if (! $output) {
                            $output = "<a href=\"" . BASE_URL . View::url($page->getcollectionpath()) . "\">" . $page->getCollectionName() . "</a>";
                        }
                        break;
                }

                $data[$loc] = $output;
            }

            $template = preg_replace($regexp, '%${1}\$s', $template);
        }

        //do a selection based on person (2nd or 3rd person). This format "keyword:option/option/" can be extended in the future
        $person_backref = ($touser ? '${1}' : '${2}');
        $template = preg_replace('#person:([^/]*)/([^/]*)/#', $person_backref, $template);

        return vsprintf($template, $data);
    }
}