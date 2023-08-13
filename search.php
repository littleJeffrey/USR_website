<html>
    
    <head>
        <meta charset="UTF-8">
        <title>逢甲大學USR查詢上傳相片</title>
    </head>
	

    <body>
		<form action="search.php" method="post">
		<fieldset>
			開始日期:<input type="date" name="startday"><br>
			結束日期:<input type="date" name="endday"><br>
			部落:
			<input type="radio" name="tribe" value="1" checked>眉溪部落
				 <input type="radio" name="tribe" value="2" >山美部落
				 <input type="radio" name="tribe" value="3" >古樓部落
				 <input type="radio" name="tribe" value="4" >清流部落<br>
			<input type='submit'>
		</fieldset>
    
    </body>

</html>
<?php
	require_once 'db_connection.php';
    $tribe='';
	global $connection;
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
	if ($_POST["startday"] !=null){
		$sql = sprintf("select * from tribe where time between '".$_POST["startday"]."' and '".$_POST["endday"]."' AND tribe_name='".$tribe."'");
		$result = mysqli_query($connection,$sql);
		$numbers = 0;
		
		
		
		echo "<fieldset>";
		while($row = mysqli_fetch_array($result)){
		#header("Content-type: image/jpeg");
		
		echo "<img src=".$row['photo_url']." width=100 height=100/>";
		echo "時間:".$row['time']."  ";
		echo "溫度:".$row['degree']."   ";
		echo "濕度:".$row['humidity']."  ";
		
		if($numbers == 3){
			echo "</fieldset>";
			//echo "<fieldset>";
			$numbers=0;
		}else{
			
			$numbers = $numbers + 1;
		}
		}
		
	}
	//mysql_close($connection);
?>

