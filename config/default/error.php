<?php

/**
 * @var $exception \Exception
 */

?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="mt-5"><?php echo $exception->getCode() ?> - <?php echo $exception->getMessage()?></h3>
        </div>
    </div>
</div>
