<h1>Juego</h1>

<?php echo form_open('api/partidas/terminar_juego/'); ?>
<div><input type="submit" value="Terminar Juego" /></div>
</form>

<?php echo (form_open('api/partidas/juego/')); ?>

<?php foreach ($cartas as $key => $carta) { ?>
    <?php if (!empty($click1)) { ?>
        <?php if (($click1 - 1) == $key) { ?>
            <input type="image" name="carta[<?php echo $key?>]" value="<?php echo $carta ?>" src="./../../../../images/<?php echo ($tematica_id . '/' . $carta . '.jpg');?>" alt="Submit"  width="auto" height="200">
        <?php } else {?>
            <?php if (!empty($click2)) { ?>
                <?php if (($click2 - 1) == $key) { ?>
                    <input type="image" name="carta[<?php echo $key?>]" value="<?php echo $carta ?>" src="./../../../../images/<?php echo ($tematica_id . '/' . $carta . '.jpg');?>" alt="Submit"  width="auto" height="200">
                <?php } else {?>
                    <input type="image" name="carta[<?php echo $key?>]" src="./../../../../images/carta.jpg" alt="Submit"  width="auto" height="200">
                <?php }?>
            <?php } else {?>
                <input type="image" name="carta[<?php echo $key?>]" src="./../../../../images/carta.jpg" alt="Submit"  width="auto" height="200">
            <?php } }?>
    <?php } else {?>
        <input type="image" name="carta[<?php echo $key?>]"  src="./../../../images/carta.jpg" alt="Submit"  width="auto" height="200">
<?php } } ?>

</form>

   