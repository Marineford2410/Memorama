<h1>Memorama</h1>
<h3>Tematicas</h3>
<ul>
<?php foreach ($tematicas as $key => $tematica) { ?>
    <li>
        <a href="<?php echo '#'; ?>">
            <?php echo $tematica['tematica']; ?>
        </a>
    </li>
<?php } ?>


</ul>
