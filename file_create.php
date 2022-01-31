<?php
    include('header.php');
    checkAuth();

    $userId = $_SESSION['user']['id'];

    $folderId = $_GET['folder_id'];

    $folder = getFolder($folderId);

    if($folder == null){
        return errorBack('یافت نشد','index.php');
        exit();
    }
    
   
    if($folder['user_id'] != $userId)
        return errorBack('این پوشه متعلق به شما نیست');


?>

<div class="row mt-3 p-1">
    <div class="col-sm-8 mx-auto text-center">

        <a href="folder.php?id=<?php echo $folderId ?>" class="btn btn-warning btn-sm mx-auto ">بازگشت به پوشه</a>
        <br>
        <br>
        

        <form action="action.php" method="post" required>
            <input type="hidden" name="folder_id" value="<?php echo $folderId ?>">
            <input class="form-control" type="text" name="name" placeholder="نام فایل">
            <br>
            <textarea placeholder="....." name="content" class="form-control" dir="auto" style="min-height:400px" required></textarea>
            <br>
            <input type="submit" class="btn btn-dark form-control" name="fileCreate" value="ثبت">
        </form>


   


    </div>
</div>
</div>

</body>
</html>