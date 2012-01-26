<?php 
    defined('C5_EXECUTE') or die("Access Denied.");
    
    $wall = Loader::helper('wall', 'lerteco_wall');
    $av = Loader::helper('concrete/avatar');
    $date = Loader::helper('date');

    $aspectratio = $wall->getAvatarAspectRatio(40);
?>

<div class="lerteco_wall">
    <h3>Recent Activity</h3>
    <ul class="lerteco_wall">
        <?php foreach ($postings as $posting) {
            $user = $posting->getUserInfo();
            $type = $posting->getType();
            $profile_url = $this->url('/profile','view', $user->getUserID());
        ?>
            <li class="<?php echo $type->getPackageHandle() . " " . $type->getPackageHandle() . "-" . $type->ptCode ?>">
                <a href="<?php echo $profile_url ?>"><?php echo $av->outputUserAvatar($user, false, $aspectratio) ?></a>
                <p class="posting">
                    <a href="<?php echo $profile_url ?>"><?php echo $posting->getUserInfo()->getUserName() ?></a> <?php echo $wall->getGraffiti($type->ptTemplate, $posting->pData, $posting->pCreateDate, false) ?>
                </p>
                <p class="time">
                    <?php echo $date->timeSince(strtotime($posting->pCreateDate)) ?> ago
                </p>
            </li>
        <?php } ?>
    </ul>
</div>