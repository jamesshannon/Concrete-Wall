<?php
    defined('C5_EXECUTE') or die(_("Access Denied."));

    $wall = Loader::helper('wall', 'lerteco_wall');
    $valt = Loader::helper('validation/token');

    $last_pkg = false;
?>

<h1><span><?php echo t('Concrete Wall Configuration')?></span></h1>

<div class="ccm-dashboard-inner">
    <p>
        <?php echo t("Concrete Wall provides a mechanism for the core and other add-ons to add notifications to a user's wall, similar to The Facebook.")?>
    </p>
    <p>
        <?php echo ('This page allows you to see all registered notification types (they must be registered by an add-on and activated by your before being used), grouped by the add-on (or core) that will create these notifications of a type, an example notification, and the ability to configure whether notifications of that type will be displayed and who they\'ll be displayed to [coming soon]. Notification types are things like "new friend added" or "posted an article" while a notification would be "James is now friends with Jill" or "You posted the article \'C5 Tips &amp; Tricks\'".')?>
    </p>
    <p>


    <form method="POST" action="<?php echo $this->url('/dashboard/users/lerteco_wall', 'save') ?>">
        <?php echo $valt->output('wall_admin_update'); ?>

        <table width="50%">
            <thead>
                <tr>
                    <th><?php echo t('Type')?></th>
                    <th><?php echo t('Example')?></th>
                    <th><?php echo t('Shared With')?></th>
                    <th><?php echo t('Active')?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posting_types->get() as $type) {
                      if ($type->ptPkgID !== $last_pkg) {
                          $last_pkg = $type->ptPkgID;
                ?>
                        <tr>
                            <td colspan="4"><h2><?php echo $type->getPackageName() ?></h2></td>
                        </tr>
                <?php }
                ?>
                    <tr>
                        <td><?php echo $type->ptName ?></td>
                        <td><a href="#">James</a> <?php echo $wall->getGraffiti($type->ptTemplate, $type->ptExampleData, false) ?></td>
                        <td>
                            <?php
                            if ($type->ptShareWith == PostingType::SHAREWITH_FRIENDS) {
                                echo t("User's Friends");
                            } else {
                                echo ("Everyone");
                            }
                            ?>
                        </td>
                        <td><input type="checkbox" name="active:<?php echo $type->ptID ?>" value="1" <?php if ($type->ptActive) { ?>checked<?php } ?> /></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <input type="submit" value="<?php echo t('Update')?>" />
    </form>
</div>