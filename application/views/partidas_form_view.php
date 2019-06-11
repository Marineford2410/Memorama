<h1>Tema: <?php echo $tematica; ?></h1>

<?php echo validation_errors(); ?>

<?php echo form_open(); ?>

<label>Nombre: </label>
<input type="text" name="username"/><br>
<br>
<label >Numero de Cartas:</label><br>
<input type="radio" name="num_cartas" value="8" >8<br>
<input type="radio" name="num_cartas" value="16">16<br>
<input type="radio" name="num_cartas" value="32">32<br>
<br>
<div><input type="submit" value="Iniciar" /></div>

</form>

