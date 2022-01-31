<?php
    include('header.php');
    checkAuth();

    $userId = $_SESSION['user']['id'];
    $fileId = $_GET['id'];

    if(!isset($fileId)){
        return redirct('index.php');
    }


    $file = getFile($fileId);

    if($file == null){
        return errorBack('یافت نشد','index.php');
        exit();
    }

    $folder = getFolder($file['folder_id']);
    
   
    if($folder['user_id'] != $userId)
        return errorBack('این فایل متعلق به شما نیست');

    $fileContent = file_get_contents('files/'.pathinfo($file['url'])['basename']);

?>

<div class="row mt-3 p-1">
    <div class="col-sm-8 mx-auto text-center">

        <a href="folder.php?id=<?php echo $folder['id'] ?>" class="btn btn-warning btn-sm mx-auto ">بازگشت به پوشه</a>
        <br>
        <br>

        <h3 class="text-center"> <?php echo $file['name'] ?> </h3>
        <br>

        <form action="action.php" method="post" >
            <input type="hidden" name="id" value="<?php echo $fileId ?>">
            <textarea name="content" class="form-control" dir="auto" style="min-height:500px">
                <?php echo ($fileContent); ?>
            </textarea>
            <br>
            <input type="submit" class="btn btn-dark form-control" name="fileUpdate" value="ثبت">
        </form>


   


    </div>
</div>
</div>

</body>
</html>