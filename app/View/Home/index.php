<div class="container px-4 py-5">

    <div class="row align-items-center">

        <?php  if (isset($data['user'])) { ?>
            <div class="col">

                <div class="display-1">Welcome <?= $data['user']['name']?></div>
            </div>
            <div class="col-auto">

                <a class="btn btn-primary" href="/users/logout" role="button">Logout</a>    
            </div>
        <?php } else if (isset($data['error'])) { ?>
            <div class="display-1"><?= $data['error']?></div>
        <?php } else { ?>
            <div class="display-1">Not Login</div>
        <?php } ?>


    </div>
    
</div>