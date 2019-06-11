<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Memorama</h1>
<h3>Tematicas</h3>
<p>Escoge un tema: </p>
<ul>
<?php foreach ($tematicas as $key => $tematica) { ?>
    <li>
        <a href="<?php echo (base_url() . 'index.php/api/partidas/' . $tematica['id']); ?>">
            <?php echo $tematica['tematica']; ?>
        </a>
    </li>
<?php } ?>
</ul>
