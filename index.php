<!doctype html>
<html>

<head>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="/style.css">
</head>
<body id="body"> 
	<table class='table' id="table">
	<?php
		
	  	$name_colums = array('Дата','Название книги','Имя','Фамилия');

	  	$link = mysqli_connect('localhost', 'root', 'root','library') 
		    or die("Ошибка " . mysqli_error($link));
		 
		// выполняем операции с базой данных
		$query ="SELECT books_out.data, books.name, students.first_name, students.second_name  
				FROM library.books_out, library.students, library.books
				WHERE books_out.id_students = students.id 
				AND books_out.id_book = books.id";
		$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 

		 
		// закрываем подключение
		mysqli_close($link);
	  	
	?>
		<thead id="tHead">
			<tr>
			<?			
			  	foreach ($name_colums as $key => $value) {
			   		echo "<th>$value</th>";
				}
			?>	
				
			</tr>
		
		</thead>
		<tbody id="tBody">
		<?
		foreach ($result as $key => $value) {
			echo "<tr>";			
			foreach ($value as $keyInner => $valueInner) {
				echo "<td>".$valueInner."</td>";
			}
			echo "</tr>";
		}
		?>		
		</tbody>
	</table>
	<a href="giveReturn.php">Страница выдачи добавления</a>	
	
</html>
