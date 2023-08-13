<?php

require_once 'db_connection.php';

global $connection;
$uploads_path = 'images/';
$server_ip = gethostbyname(gethostname());
$upload_url = 'http://'.$server_ip.'/nantou/images/';

$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){


    if(isset($_POST['caption'])){

        
        $caption = $_POST['caption'];
        $degree = $_POST["degree"];
        $humiduty = $_POST["humidity"];
		$tribe_name = $_POST["tribe"];
        $information = $_POST["information"];
        
        $fileinfo = pathinfo($_FILES['image']['name']);
        $extension = $fileinfo['extension'];
        $file_url = $upload_url . getFileName() . '.' . $extension;
        $file_path = $uploads_path  . getFileName() . '.' . $extension;
        $image_name = getFileName() . '.' . $extension;
        

        try{
            move_uploaded_file($_FILES['image']['tmp_name'], $file_path);

            // adding the path and name to database
            $sql = "INSERT INTO tribe(photo_name, photo_url, degree, humidity, caption, tribe_name, information) ";
            $sql .= "VALUES ('{$image_name}', '{$file_url}', '{$degree}', '{$humiduty}', '{$caption}', '{$tribe_name}', '{$information}');";

            if(mysqli_query($connection, $sql)){
                $response['error'] = false;
                $response['photo_name'] = $image_name;
                $response['photo_url'] = $file_url;
                $response['degree'] = $degree;
                $response['humidity'] = $humiduty;
                $response['caption'] = $caption;
				$response['tribe_name'] = $tribe_name;
                $response['information'] = $information;
            }
        }catch(Exception $e){
            $response['error'] = true;
            $response['message'] = $e->getMessage();
        }
        
    }else{
        $response['error'] = true;
        $response['message'] = "Please choose a file.";
    }
    echo json_encode($response);
    mysqli_close($connection);
}


function getFileName(){
    global $connection;

    $sql = "SELECT max(id) as id FROM tribe";
    
    $result = mysqli_fetch_array(mysqli_query($connection, $sql));

    if($result['id'] == null){
        return 1;
    }else{
        $result['id']++;
        return $result['id'];
    }
    mysqli_close($connection);
}

?>