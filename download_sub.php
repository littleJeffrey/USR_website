<?php
require_once 'db_connection.php';
$tribe='';
global $connection;
if(isset($_GET['download'])){
    if($_GET['tribe'] == 1){
        $tribe = "眉溪部落";
    }elseif($_GET['tribe'] == 2){
        $tribe = "山美部落";
    }elseif($_GET['tribe'] == 3){
        $tribe = "古樓部落";
    }elseif($_GET['tribe'] == 4){
        $tribe = "清流部落";
    }
    //echo "<br>起始時間:".$_POST["startday"]."  結束時間:".$_POST["endday"]." 部落:".$tribe."</br>";
    
    if ($_GET["startday"] !=null){
        $sql = sprintf("select * from tribe where time between '".$_GET["startday"]."' and '".$_GET["endday"]."' AND tribe_name='".$tribe."'");
        $result = mysqli_query($connection,$sql);
        $numbers = 0;
        
        $zip = new ZipArchive;
        $temp="123.zip";
        $zip->open($temp, ZipArchive::CREATE);
        $zipname="test.zip";
        
        
        while($row = mysqli_fetch_array($result)){
            $zip->open($temp, ZipArchive::CREATE);
            //echo "<br>檔案:".$row["photo_name"]."</br>";
            $new_filename = substr("images/".$row["photo_name"], strrpos("images/".$row["photo_name"],'/')+1);
            $zip->addFile("images/".$row["photo_name"], $new_filename);
            $zip->close();
        }
        
        
        
        
        header('Content-Type: application/zip');
        //指定類型
        header("Content-type: ".filetype("$temp"));
        //指定下載時的檔名
        header("Content-Disposition: attachment; filename=".$zipname."");

        //輸出下載的內容。
        readfile($temp);
        unlink($temp); //刪除暫存檔
        
    }
}
?>