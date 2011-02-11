<?php
    defined('C5_EXECUTE') or die("Access Denied.");

    $args = array('btDisplayType' => '1', 'btMaxPostings' => '25', 'adding' => true);
    
    $this->inc('edit.php', $args);
?>