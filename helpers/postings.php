<?php
/**
 * Provides helper functions
 * @package lerteco_wall
 * @category Helpers
 * @author James Shannon <james@jamesshannon.com>
 * @copyright  Lerteco 2011
 */

defined('C5_EXECUTE') or die("Access Denied.");

Loader::model('package');

class PostingsHelper {
    static function pkg($pkg) {
        if (is_null($pkg) || $pkg === 0) {
            return null;
        } elseif (is_object($pkg)) {
            return $pkg;
        } elseif (is_numeric($pkg)) {
            return Package::getByID($pkg);
        } else {
            return Package::getByHandle($pkg);
        }
    }

    static function prepData($data) {
        if (! is_array($data)) {
            $data = array($data);
        }

        return serialize($data);
    }
}