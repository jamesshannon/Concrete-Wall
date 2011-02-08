<?php 
    defined('C5_EXECUTE') or die("Access Denied.");
    
    $wall = Loader::helper('wall', 'lerteco_wall');
?>

<style type="text/css">
ul.lerteco_wall {
   list-style-type: none;
   margin: 0;
   padding: 0;
}

ul.lerteco_wall li {
    background-repeat: no-repeat;
    background-position: 2px 0px;
    padding-left: 50px;
    min-height: 50px;
    border-bottom: 1px #cecece;
}

</style>

<ul class="lerteco_wall"">
    <li style="background-image: url('http://www.gravatar.com/avatar/d0aefe63903a58e7dcab3a7fb8a30637.jpg?s=40&d=http%3A%2F%2Fc5fciab.localhost%2Fconcrete%2Fimages%2Fspacer.gif')">
        testing
    </li>
    <?php //echo $wall->getGraffiti($type->ptTemplate, $type->ptExampleData, 'now', true) ?>
</ul>