<html>
    
    <head>
        <meta charset="UTF-8">
        <title>逢甲大學USR查詢上傳相片</title>
    </head>
	

    <body>
		<form action="download.php" method="post">
		<fieldset>
			開始日期:<input type="date" name="startday" value="<?php echo date('Y-m-d'); ?>"><br>
			結束日期:<input type="date" name="endday" value="<?php echo date('Y-m-d'); ?>"><br>
			部落:
			<input type="radio" name="tribe" value="1" checked>眉溪部落
				 <input type="radio" name="tribe" value="2" >山美部落
				 <input type="radio" name="tribe" value="3" >古樓部落
				 <input type="radio" name="tribe" value="4" >清流部落<br>
			<input type='submit' value="查詢" name='search'>
			<input type="submit" value="下載" name='search'>
		</fieldset>
		</form>
    
    </body>
	
</html>
<?php
	require_once 'db_connection.php';
    $tribe='';
	global $connection;
	if ($_POST['search'] == "查詢"){
		if($_POST['tribe'] == 1){
			$tribe = "眉溪部落";
		}elseif($_POST['tribe'] == 2){
			$tribe = "山美部落";
		}elseif($_POST['tribe'] == 3){
			$tribe = "古樓部落";
		}elseif($_POST['tribe'] == 4){
			$tribe = "清流部落";
		}
		echo "<br>起始時間:".$_POST["startday"]."  結束時間:".$_POST["endday"]." 部落:".$tribe."</br>";
		echo "<a href='download_sub.php?download=true&tribe=".$_POST['tribe']."&startday=".$_POST['startday']."&endday=".$_POST['endday']."'><button>Download</button></a>";
		echo "</br>";
		if ($_POST["startday"] !=null){
			$sql = sprintf("select * from tribe where time between '".$_POST["startday"]."' and '".$_POST["endday"]."' AND tribe_name='".$tribe."'");
			$result = mysqli_query($connection,$sql);
			$numbers = 0;
			
			echo "<fieldset>";
			while($row = mysqli_fetch_array($result)){
			#header("Content-type: image/jpeg");
			echo "<div>";
			echo "<div style='float:left'><img style='vertical-align:text-top;' src=images/".$row['photo_name']." width=100 height=100/></div>";
			echo "<div style='width:150;  float:left;  white-space: nowrap; overflow-x:scroll;'>時間:".$row['time']."  <br>";
			echo "溫度:".$row['degree']."   <br>";
			echo "濕度:".$row['humidity']."  <br>";
			echo "資訊:".$row['information']."  </div>";
			echo "</div>";
			if($numbers == 3){
				echo "</fieldset>";
				echo "<fieldset>";
				$numbers=0;
			}else{
				
				$numbers = $numbers + 1;
			}
			}
			
		}
	}else{
		if($_POST['tribe'] == 1){
			$tribe = "眉溪部落";
		}elseif($_POST['tribe'] == 2){
			$tribe = "山美部落";
		}elseif($_POST['tribe'] == 3){
			$tribe = "古樓部落";
		}elseif($_POST['tribe'] == 4){
			$tribe = "清流部落";
		}
		//echo "<br>起始時間:".$_POST["startday"]."  結束時間:".$_POST["endday"]." 部落:".$tribe."</br>";
		
		if ($_POST["startday"] !=null){
			$sql = sprintf("select * from tribe where time between '".$_POST["startday"]."' and '".$_POST["endday"]."' AND tribe_name='".$tribe."'");
			$result = mysqli_query($connection,$sql);
			$numbers = 0;
			
			$zip = new ZipArchive;
			$temp="123.zip";
			$zip->open($temp, ZipArchive::CREATE);
			$zipname= $_POST["startday"]." to ".$_POST["endday"]."_".$tribe.".zip";
			
			
			while($row = mysqli_fetch_array($result)){
				$zip->open($temp, ZipArchive::CREATE);
				//echo "<br>檔案:".$row["photo_name"]."</br>";
				$new_filename = $row["time"]."_".$row["photo_name"];//substr("images/".$row["photo_name"], strrpos("images/".$row["photo_name"],'/')+1);
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
	//mysql_close($connection);
?>

