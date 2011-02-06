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
    static function getGraffiti($template, $data, $time) {
        $element_test = array();
        $data = unserialize($data);

        preg_match_all('/%(\b)\$u/', $template, $element_test);

        if (count($element_test[0])) {
            $conversions = $element_test[0];
            $data_locs = $element_test[1];

            foreach ($data_locs as $loc) {
                
            }
        }
        

        print_r($element_test);

    }
}