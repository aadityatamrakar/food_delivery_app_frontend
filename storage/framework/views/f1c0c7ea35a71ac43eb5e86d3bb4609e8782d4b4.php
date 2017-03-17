<?php if(Session::has('info')): ?>
    <div class="container">
        <div class="alert alert-<?php echo e(session('type')); ?>">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo session('info'); ?>

        </div>
    </div>
<?php endif; ?>
