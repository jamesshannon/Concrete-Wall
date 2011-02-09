<?php 
defined('C5_EXECUTE') or die("Access Denied.");
?>

<h2><?php echo t('Display Type')?></h2>
<div class="lerteco_wall_edit">
    <p>
        <?php echo t('Should we display a wall (ie, postings related to one user) or an activity feed (ie, postings related to the currently logged-in users\'s network)?') ?>
    </p>

    <?php echo $form->radio('btDisplayType', '1', $btDisplayType) ?> User-specific Wall <?php echo $form->radio('btDisplayType', '2', $btDisplayType) ?> Site-wide Activity Feed<br />
    <em>Note that the first time a wall is displayed while adding the block, it'll behave like an activity feed.</em><br />
    <br />
    Max Postings: <?php echo $form->text('btMaxPostings', $btMaxPostings) ?>
</div>