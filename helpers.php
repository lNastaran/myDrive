<?php



function checkAuth(){
    if(!isset($_SESSION['user'])){
        header('location: login.php');
        exit();
    }
}



function errorBack($message, $location = null){
    $_SESSION['error'] = $message;

    $redirect = $location == null ? $_SERVER['HTTP_REFERER'] : $location;

    return header('Location: '.$redirect);
    exit();
}

function sucBack($message, $location = null){
    $_SESSION['success'] = $message;

    $redirect = $location == null ? $_SERVER['HTTP_REFERER'] : $location;

    return header('Location: '.$redirect);
    exit();
}



function redirect($location){
    return header('Location: '.$location);
    exit();
}



function dbConnect(){
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "db";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}



function clearInput($input){
    $connection = dbConnect();
    return mysqli_real_escape_string($connection, $input); 
}



function deleteFile($fileAddress, $path){
    $file = pathinfo($fileAddress);
    $filename = $file['basename'];

    if($filename != "" && $filename != "default.png" && file_exists($path.$filename))
        \unlink($path.$filename);

    return;
}



function fileExtension($fileName){
    $fileExtension = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
    return $fileExtension;
}



function insert($table, $colValues){
    $connection = dbConnect();

    $cols = implode(",",array_keys($colValues));
    $values = implode("','",array_values($colValues));


    $sql = "INSERT INTO $table ($cols) VALUES ('$values')";

    if($connection->query($sql)){

        return $connection->insert_id;;
        $connection->close();

    }else{

        return false;

    }
    exit();
 

}



function delete($table, $id){
    $connection = dbConnect();

    $sql = "DELETE FROM $table WHERE id=$id ";

    if($connection->query($sql)){
        return true;
        $connection->close();

    }else{
        return false;
    }
    exit();
 

}



function getUserFolders(){
    checkAuth();
    
    $userId = $_SESSION['user']['id'];

    $connection = dbConnect();

    $sql = "SELECT id,name,user_id FROM folders WHERE user_id = '$userId'  ";

    $result = $connection->query($sql);


    if ($result->num_rows > 0) {
        return $result;
    } else {
        return [];
    }



    $connection->close();
   
    exit();
}



function getUserFolderFiles($folderId){
    checkAuth();
    
    $userId = $_SESSION['user']['id'];

    $connection = dbConnect();

    $folderSql = "SELECT id,user_id FROM folders WHERE id = '$folderId';";
    $folderGet = $connection->query($folderSql);
    $folder = $folderGet->fetch_assoc();

    if($folder == null || $folder['user_id'] != $userId){
        return errorBack('پوشه یافت نشد','index.php');
    }



    $sql = "SELECT id,name,url FROM files WHERE folder_id = '$folderId'  ";

    $result = $connection->query($sql);


    if ($result->num_rows > 0) {
        return $result;
    } else {
        return [];
    }


    $connection->close();
   
    exit();
}




function getFile($id){
    $connection = dbConnect();

    $fileSql = "SELECT * FROM files WHERE id = '$id';";
    $fileGet = $connection->query($fileSql);
    $file = $fileGet->fetch_assoc();

    return $file;
}



function getFolder($id){
    $connection = dbConnect();

    $folderSql = "SELECT * FROM folders WHERE id =$id";
    $folderGet = $connection->query($folderSql);
    $folder = $folderGet->fetch_assoc();


    return $folder;
}


