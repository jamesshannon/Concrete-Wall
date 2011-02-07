<?php
    defined('C5_EXECUTE') or die(_("Access Denied."));

    $wall = Loader::helper('wall', 'lerteco_wall');
    $valt = Loader::helper('validation/token');

    $last_pkg = false;
?>

<h1><span>Concrete Wall Configuration</span></h1>

<div class="ccm-dashboard-inner">
    Lerteco's Concrete Wall is....

    <form method="POST" action="<?php echo $this->url('/dashboard/users/lerteco_wall', 'save') ?>">
        <?php echo $valt->output('wall_admin_update'); ?>

        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Example</th>
                    <th>Shared With</th>
                    <th>Active</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posting_types->get() as $type) {
                      if ($type->ptPkgID !== $last_pkg) {
                          $last_pkg = $type->ptPkgID;
                ?>
                        <tr>
                            <td colspan="4"><?php echo $type->getPackageName() ?></td>
                        </tr>
                <?php }
                ?>
                    <tr>
                        <td><?php echo $type->ptName ?></td>
                        <td>You <?php echo $wall->getGraffiti($type->ptTemplate, $type->ptExampleData, 'now', true) ?></td>
                        <td>
                            <?php if ($type->ptShareWith == PostingType::SHAREWITH_FRIENDS) { ?>
                                User's Friends
                            <?php } else { ?>
                                Everyone
                            <?php } ?>
                        </td>
                        <td><input type="checkbox" name="active:<?php echo $type->ptID ?>" value="1" <?php if ($type->ptActive) { ?>checked<?php } ?> /></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <input type="submit" value="Update" />
    </form>
</div>