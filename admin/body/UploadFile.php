
<?php
if (isset($_POST["submit"])) {
    $target_dir = "uploads/";
    
    // Create directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    $_SESSION["target_file"] = $target_file;
    
    $encoded_target_file = base64_encode($target_file);
    
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.<br><br>";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    
    header('Location: /admin/default.php?p=ZGFzaGJvYXJkLnBocA==&f='.$encoded_target_file); exit;
}
?>
          
     