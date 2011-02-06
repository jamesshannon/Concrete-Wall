<?php
    defined('C5_EXECUTE') or die(_("Access Denied."));

    $wall = Loader::helper('wall', 'lerteco_wall');

    $last_pkg = false;
?>

<h1><span>Concrete Wall Configuration</span></h1>

<div class="ccm-dashboard-inner">
    Lerteco's Concrete Wall is....

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Example</th>
                <th>Share With</th>
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
                    <td><?php echo $wall->getGraffiti($type->ptTemplate, $type->ptExampleData, 'now') ?></td>
                    <td>
                        <?php if ($type->ptShareWith == PostingType::SHAREWITH_FRIENDS) { ?>
                            User's Friends
                        <?php } else { ?>
                            Everyone
                        <?php } ?>
                    </td>
                    <td><input type="checkbox"></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>