<?php
    include('header.php');
    checkAuth();

    $userId = $_SESSION['user']['id'];
    $folderId = $_GET['id'];

    if(!isset($folderId)){
        return redirct('index.php');
    }

    $folder = getFolder($folderId);

    if($folder == null){
        return errorBack('یافت نشد','index.php');
        exit();
    }
   
    if($folder['user_id'] != $userId)
        return errorBack('این فایل متعلق به شما نیست');


    $files = getUserFolderFiles($folderId);

?>

<div class="row mt-3 p-1">
    <div class="col-sm-8 mx-auto">
        <h3 class="text-center ">  <?php echo $folder['name'] ?>   </h3>
        <br>



        <div class="row">
            <div class="col-sm-9 p-2">

                <form action="action.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="folder_id" value="<?php echo $folderId ?>">
                    <div class="row text-center">
                        <div class="col-sm-12 mx-auto">
                            <div class="row text-center">
                                <div class="col-sm-4">
                                    <input name="name" class="form-control p-1" placeholder="نام فایل" required>
                                </div>
                                <div class="col-sm-7">
                                    <input type="file" name="file" class="form-control p-1" required>
                                </div>
                                <div class="col-sm-1 ">
                                    <button class="btn btn-warning" name="fileStore" type="submit">آپلود</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>



            </div>

            <div class="col-sm-3 text-center p-2">
                یا
                <a class="btn btn-dark" href="file_create.php?folder_id=<?php echo $folderId ?>">ساخت فایل</a>
            </div>


        </div>
        


        <br>
        <br>


        <?php

            if($files != []){
                while($file = $files->fetch_assoc()) {
                    echo "
                    
                        <div class='row text-center border mb-4 p-2 rounded shadow-sm'>
                            <div class='col-sm-8'>  <p><b>".$file['name']." </b> </p> </div>
                            <div class='col-sm-4 text-center'>
                                <form action='action.php' method='post' class='d-inline'>
                                    <input type='hidden' name='id' value='".$file['id']."'>
                                    <input class='btn btn-danger' type='submit' name='fileDelete' value='حذف'>
                                </form>
                                <a href='".$file['url']."' class='btn btn-primary' download>دانلود</a>";

                        
                            if(in_array(fileExtension($file['url']) , ["txt","docs"] ) ){
                                echo "<a href='file_update.php?id=".$file['id']."' class='btn btn-warning mr-1 ml-1' >ویرایش</a>";
                            }
                                
                            
                    echo "
                            </div>
                        </div>
                    
                    ";

                }
            }
            else{
                echo "<p class='alert alert-info text-center'> فایلی برای این پوشه ثبت نشده است </p>";
            }

        ?>




    </div>
</div>
</div>

</body>
</html>