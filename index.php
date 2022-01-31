<?php
    include('header.php');
    checkAuth();
    $folders = getUserFolders();

?>

<div class="row mt-3 p-1">
    <div class="col-sm-8 mx-auto">
        <h3 class="text-center">پوشه های شما</h3>
        <br>


        <form action="action.php" method="post">
            <div class="row text-center">
                <div class="col-sm-8 mx-auto">
                    <div class="row text-center">
                        <div class="col-sm-9">
                            <input name="name" class="form-control" placeholder="نام پوشه جدید" required>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-warning" name="folderStore" type="submit">ساخت پوشه</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <br>
        <br>


        <?php

            if($folders != []){
                while($folder = $folders->fetch_assoc()) {
                    echo "
                    
                        <div class='row text-center border mb-4 p-2 rounded shadow-sm'>
                            <div class='col-sm-8'>  <p><b>".$folder['name']." </b> </p> </div>
                            <div class='col-sm-4 text-center'>
                                <form action='action.php' method='post' class='d-inline'>
                                    <input type='hidden' name='id' value='".$folder['id']."'>
                                    <input class='btn btn-danger' type='submit' name='folderDelete' value='حذف'>
                                </form>
                                <a href='folder.php?id=".$folder['id']."&name=".$folder['name']." ' class='btn btn-info '>فایل ها</a>
                            </div>
                        </div>
                    
                    ";

                }
            }
            else{
                echo "<p class='alert alert-info text-center'>پوشه ای ندارید</p>";
            }

        ?>




    </div>
</div>
</div>

</body>
</html>