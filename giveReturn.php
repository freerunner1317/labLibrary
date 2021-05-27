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

			$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));

			if ($result) {
		      echo '<p>Данные успешно добавлены в таблицу.</p>';
		    } else {
		      echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
		    }
		}

		if (isset($_POST['student'])){
			$date = date("Y-m-d");
			$book_name_split = explode("-", $_POST['book_name']);
			$quantity = (int)$book_name_split[1];
			$quantity--;
			//echo $quantity;

			if ($quantity >= 0){
				$query = "UPDATE `library`.`books` SET `quantity` = '$quantity' WHERE (`id` = '$book_name_split[0]');";

				//echo $query;		 
				$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));

				$query ="INSERT INTO `library`.`books_out` (`data`, `id_book`, `id_students`) 
						 VALUES ('$date', '$book_name_split[0]', '{$_POST['student']}');";	 
				$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
			}else{
				echo "Книги закончилась, выдача невозможна";
			}			 
		}	

		if (isset($_POST['takeBook'])){
			
			$book_name_split = explode("-", $_POST['takeBook']);
			$quantity = (int)$book_name_split[1];
			$quantity++;

			//var_dump($book_name_split);

			$query = "UPDATE `library`.`books` SET `quantity` = '$quantity' WHERE (`id` = '$book_name_split[2]');";

			//echo $query;		 
			$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
			
			$query = "DELETE FROM `library`.`books_out` WHERE (`id` = '$book_name_split[0]');";	 
			$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 

			if ($result){ echo "Книга принята";}
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

		$query ="SELECT books_out.id, books_out.data, books.name, students.first_name, students.second_name, students.group, books.quantity, books_out.id_book 
				FROM library.books_out, library.books, library.students
				WHERE books_out.id_book = books.id AND books_out.id_students = students.id";
		$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));

		foreach ($result as $key => $value) {
			$books_out[$key][0] = $value['id'];
			$books_out[$key][1] = $value['data'];
			$books_out[$key][2] = $value['name'];
			$books_out[$key][3] = $value['first_name'];
			$books_out[$key][4] = $value['second_name'];
			$books_out[$key][5] = $value['group'];
			$books_out[$key][6] = $value['quantity'];
			$books_out[$key][7] = $value['id_book'];
		}

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
				    	echo "<option value='$value[0]-$value[2]'>$value[1]</option>";	
				    }	   	 		   		
				?>
			</select>
			
			<p style="text-align: center">
				<input style="width: 200px; height: 30px;" type="submit" value="Выдать">
			</p>
		</form>
	</div>

	<div class="selectBoxOut">
		<form method="POST" action="giveReturn.php">
			<select class="boxOut" id="box" name="takeBook" value="">
				<?
				    foreach ($books_out as $key => $value) {
				    	echo "<option value='$value[0]-$value[6]-$value[7]'>$value[1]; $value[2]; $value[3] $value[4]; $value[5]гр</option>";	
				    }	   	 		   		
				?>
			</select>			
			<p style="text-align: center">
				<input style="width: 200px; height: 30px;" type="submit" value="Принять">
			</p>
		</form>
	</div>
</html>
