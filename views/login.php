<?php

?>
<div class="container">
    <div class="row">
        <h1>Login</h1>
        <div class="col-12">
        <?php $form = \SmyPhp\Core\Form\Form::start('', 'post')?>
            <?php echo $form->input($model, 'email') ?>
            <?php echo $form->input($model, 'password')->Password() ?>
            <br>
            <div class="input-group">
                <input type="submit" class="btn btn-block btn-primary" value="Submit">
            </div>
        <?php \SmyPhp\Core\Form\Form::stop()?>
        </div>
    </div>
</div>
