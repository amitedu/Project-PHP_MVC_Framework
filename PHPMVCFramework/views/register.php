<h2>Register Page</h2>

<?php $form = \app\core\form\Form::begin('', 'POST'); ?>

<div class="row">
    <div class="col">
        <?php echo $form->feild($model, 'firstname'); ?>
    </div>
    <div class="col">
        <?php echo $form->feild($model, 'lastname'); ?>
    </div>
</div>
<?php echo $form->feild($model, 'email', 'email'); ?>
<?php echo $form->feild($model, 'password', 'password'); ?>
<?php echo $form->feild($model, 'confirmPassword', 'password'); ?>
<button type="submit" class="btn btn-primary">Submit</button>

<?php \app\core\form\Form::end(); ?>
