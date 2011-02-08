<?php 
defined('C5_EXECUTE') or die("Access Denied.");
?>

<h2><?php echo t('Display Type')?></h2>
<p>
    <?php echo t('Should we display a wall (ie, postings related to one user) or an activity feed (ie, postings related to the currently logged-in users\'s network)?') ?>
</p>

<?php echo $form->radio('btDisplayType', '1', $btDisplayType) ?> Wall <?php echo $form->radio('btDisplayType', '2', $btDisplayType) ?> Activity Feed