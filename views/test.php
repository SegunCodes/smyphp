<?php
use SmyPhp\Core\Application;
?>
<div class="container">
    <div class="row">
        <h1>Login</h1>
        <div class="col-12">
            <form action="<?php Application::$ROOT_DIR."/upload"?>" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="file" name="file" class="form-control">
                </div><br>
                <div class="input-group">
                    <input type="submit" name="submit" class="btn btn-block btn-primary" value="Submit">
                </div>
                <?php
                    echo $error;
                ?>
            </form>
        </div>
    </div>
</div>
