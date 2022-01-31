<?php
session_start();
include('helpers.php');



if(isset($_POST['login'])){
    $username = clearInput($_REQUEST['username']);
    $password = clearInput($_REQUEST['password']);

    $connection = dbConnect();
    $sql = "SELECT * FROM users WHERE username = '$username' ";
    $result = $connection->query($sql);
    if ($result->num_rows  < 1) {
        return errorBack('نام کاربری یا کلمه عبور اشتباه است');
    }

    $user = $result -> fetch_assoc();

    if(!password_verify($password, $user['password']))
        return errorBack('نام کاربری یا کلمه عبور اشتباه است');

    $_SESSION['user'] = $user;

    return redirect('index.php');

}


elseif(isset($_POST['signup'])){

    $username = clearInput($_REQUEST['username']);
    $password = clearInput($_REQUEST['password']);



    $connection = dbConnect();
    $sql = "SELECT id, username FROM users WHERE username = '$username' ";
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        return errorBack('این نام کاربری در حال حاضر وجود دارد');
    }

    
    $res = insert('users',[
        'username'=>$username,
        'password'=>password_hash($password,PASSWORD_DEFAULT),
    ]);

    
    if(!$res){
        return errorBack('خطایی رخ داد');
    }
    else{
        $_SESSION['user'] = [
            'id'=>$res,
            'username'=>$username
        ];

        return redirect('index.php');
    }

}



elseif(isset($_POST['folderStore'])){
    checkAuth();
    
    $name = clearInput($_REQUEST['name']);

    $res = insert('folders',[
        'user_id'=>$_SESSION['user']['id'],
        'name'=>$name,
    ]);

    
    if(!$res){
        return errorBack('خطایی رخ داد');
    }
    else{
        return redirect('index.php');
    }

}





elseif(isset($_POST['fileStore'])){
    checkAuth();

    $file = $_FILES['file'];
    $name = clearInput($_REQUEST['name']);
  

    $destinationDir = "files/";
    $fileName = basename($file["name"]);  
    $fileExtension = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
    $fileName = md5($fileName);
    $targetDestination = $destinationDir.$fileName.".".$fileExtension;



    $notAllowedExtensions= array("php");
    if(in_array($fileExtension,$notAllowedExtensions))
        return errorBack('فرمت فایل مجاز نیست');
    
    // if($file['size'] > 2097152)
    //     return errorBack('حجم فایل حداکثر باید 2 مگابایت باشد');
    

    move_uploaded_file($file['tmp_name'],$targetDestination);


    $res = insert('files',[
        'folder_id'=>$_REQUEST['folder_id'],
        'name'=>$name,
        'url'=>"http://".$_SERVER['SERVER_NAME'].'/'.$targetDestination,
    ]);

    
    if(!$res){
        return errorBack('خطایی رخ داد');
    }
    else{
        return redirect($_SERVER['HTTP_REFERER']);
    }

    return;

}



elseif(isset($_POST['fileCreate'])){
    checkAuth();

    $folderId = clearInput($_REQUEST['folder_id']);
    $name = clearInput($_REQUEST['name']);
    $content = $_POST['content'];


    $targetDestination = 'files/'.md5($name).".txt";
    file_put_contents($targetDestination, $content , FILE_APPEND );

    $res = insert('files',[
        'folder_id'=>$folderId,
        'name'=>$name,
        'url'=>"http://".$_SERVER['SERVER_NAME'].'/'.$targetDestination,
    ]);

    
    if(!$res){
        return errorBack('خطایی رخ داد');
    }
    else{
        return sucBack('فایل با موفقیت ساخته شد','folder.php?id='.$folderId);
    }

    return;

}


//..
elseif(isset($_POST['fileDelete'])){
    checkAuth();
    $userId = $_SESSION['user']['id'];

    $id = $_REQUEST['id'];

    $file = getFile($id);
    
    $folder = getFolder($file['folder_id']);

    if($folder['user_id'] != $userId)
        return errorBack('این فایل متعلق به شما نیست');



    $res = delete('files',$id);
    if(!$res){
        return errorBack('خطایی رخ داد');
    }
    else{
        deleteFile($file["url"],'files/');
        return redirect($_SERVER['HTTP_REFERER']);
    }

}


elseif(isset($_POST['folderDelete'])){
    checkAuth();
    $userId = $_SESSION['user']['id'];

    $id = $_REQUEST['id'];

    $folder = getFolder($id);

    if($folder['user_id'] != $userId)
        return errorBack('این فایل متعلق به شما نیست');


    $connection = dbConnect();
    $sql = "SELECT * FROM files WHERE folder_id = $id  ";
    $files = $connection->query($sql);
    if ($files->num_rows > 0) {
        while($file = $files->fetch_assoc()) {
            $res = delete('files',$file['id']);
            deleteFile($file["url"],'files/');
        }
    } 

    delete('folders',$id);

    $connection->close();

    return sucBack('حذف شد');
}



elseif(isset($_POST['fileUpdate'])){
    checkAuth();
    $userId = $_SESSION['user']['id'];

    $id = $_REQUEST['id'];
    $content = $_REQUEST['content'];

    $file = getFile($id);

    $folder = getFolder($file['folder_id']);

    if($folder['user_id'] != $userId)
        return errorBack('این فایل متعلق به شما نیست');


    
    file_put_contents('files/'.pathinfo($file['url'])['basename'], $content);

    return sucBack('ثبت شد');

}


elseif(isset($_POST['logout'])){
    checkAuth();
    
    unset($_SESSION['user']);

    return redirect('login.php');

}


else{
    exit();
}
