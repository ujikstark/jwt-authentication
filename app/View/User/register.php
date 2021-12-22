
<div class="container px-4 py-5">

    <?php if (isset($data['error'])) {?>
            <div class="row">
            <div class="alert alert-danger" role="alert">
                <?= $data['error'] ?>
            </div>
            </div>
    <?php }?>

    <div class="row">
        <div class="col-sm">
            <div class="display-1">Register</div>
        </div>
   
    
        <div class="col-sm">
            <form class="p-4 p-md-5 border rounded-3 bg-light" method="post" action="/users/register">
                <div class="mb-3 row">
                    <label for="inputName" class="col-sm-3 col-form-label">Full Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="inputName" name="name">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputUsername" class="col-sm-3 col-form-label">Username</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="inputUsername" name="username">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword1" class="col-sm-3 col-form-label">Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="inputPassword1" name="password">
                    </div>
                </div>
                <div class="mb-4 row">
                    <label for="inputPassword2" class="col-sm-3 col-form-label">Re-enter Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="inputPassword2">
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>


        </div>
    </div>
    
    
    
</div>