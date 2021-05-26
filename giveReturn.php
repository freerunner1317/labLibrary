<!doctype html>
<html>

<head>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="/style.css">
</head>
<body id="body"> 
	<?php
		
	  	$name_colums = array('Дата','Название книги','Имя','Фамилия');

	  	$link = mysqli_connect('localhost', 'root', 'root','library') 
		    or die("Ошибка " . mysqli_error($link));
		 
		if (isset($_POST['first_name']) && ($_POST['first_name'] != "Имя")){

			$query ="INSERT INTO `library`.`students` (`first_name`, `second_name`, `group`, `record_book`) VALUES ('{$_POST['first_name']}', '{$_POST['second_name']}', '{$_POST['group']}', '{$_POST['record_book']}');";

			//$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));

			if ($result) {
		      echo '<p>Данные успешно добавлены в таблицу.</p>';
		    } else {
		      echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
		    }
		}

		if (isset($_POST['student'])){
			echo $_POST['book_name'];
			$date = date("Y-m-d");

			$query ="INSERT INTO `library`.`books_out` (`data`, `id_book`, `id_students`) 
					 VALUES ('$date', '2', '{$_POST['student']}');";

			echo $query;		 
			//$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));

			if ($result) {
		      echo '<p>Данные успешно добавлены в таблицу.</p>';
		    } else {
		      echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
		    }
		}	
		// выполняем операции с базой данных
		$query ="SELECT id, first_name, second_name, students.group 
				 FROM library.students;";
		$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 		

		foreach ($result as $key => $value) {
			$students[$key][0] = $value['id'];
			$students[$key][1] = $value['first_name'];
			$students[$key][2] = $value['second_name'];
			$students[$key][3] = $value['group'];
		}

		$query ="SELECT * FROM library.books";
		$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));

		foreach ($result as $key => $value) {
			$books[$key][0] = $value['id'];
			$books[$key][1] = $value['name'];
			$books[$key][2] = $value['quantity'];
		}
		//var_dump($books);

		// foreach ($students as $key => $value) {
		// 	echo $value[1];
		// 	echo "</br>";
		// }

		mysqli_close($link);	  	
	?>
	<form method="POST" action="giveReturn.php">
		<div class="insertStudent">
			<input style="width: 230px; height: 20px;" type="text" name="first_name" value="Имя">
			<input style="width: 230px; height: 20px;" type="text" name="second_name" value="Фамилия">
			<input style="width: 70px; height: 20px;" type="text" name="group" value="Группа">
			<input style="width: 110px; height: 20px;" type="text" name="record_book" value="Номер зачетки">
			<p style="text-align: center">
				<input style="width: 200px; height: 30px;" type="submit" value="Внести студента">
			</p>
		</div>	
	</form>

	<div class="selectBox">
		<form method="POST" action="giveReturn.php">
			<select class="box" id="box" name="student" value="">
				<?
				    foreach ($students as $key => $value) {
				    	echo "<option value='$value[0]'>$value[1] $value[2]; $value[3] Группа</option>";	
				    }	   	 		   		
				?>
			</select>
			<select class="box" id="box" name="book_name" value="">
				<?
				    foreach ($books as $key => $value) {
				    	echo "<option value='$value[0]/$value[2]'>$value[1]</option>";	
				    }	   	 		   		
				?>
			</select>
			
			<p style="text-align: center">
				<input style="width: 200px; height: 30px;" type="submit" value="Выдать">
			</p>
		</form>
	</div>
</html>
