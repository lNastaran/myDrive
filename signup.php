<?php
    include('header.php');

    if(isset($_SESSION['user'])){
        header("location: index.php");
        exit();
    }
?>



<div class="row mt-5">
    <div class="col-sm-4 mx-auto text-center mt-5">
        <h3 class="text-center mb-3">ثبت نام</h3>
        <form action="action.php" method="post">
            <div class="form-group">
                <input name="username" class="form-control" placeholder="نام کاربری">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="کلمه عبور">
            </div>
            <div class="form-group">
                <input type="submit" name="signup" value="ثبت نام" class="btn btn-dark form-control">
            </div>
        </form>


        <br>
        <br>
        <a href="login.php" class="alert alert-info w-100"> اکانت دارید؟ وارد شوید </a>


    </div>
</div>



</div>

</body>
</html>