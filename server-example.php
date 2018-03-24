<?php		
	// if (isset($_POST['query']) && isset($_POST['category']) 
	// {
		
		
		function utf8json($inArray) { 
		/* Fonction utf8json($inArray) qui encode tout en UTF8 pour prendre en compte les accents des caractères*/
		/* source : http://php.net/manual/fr/function.json-encode.php#99837 */
			static $depth = 0; 

			/* our return object */ 
			$newArray = array(); 

			/* safety recursion limit */ 
			$depth ++; 
			if($depth >= '30') { 
				return false; 
			} 

			/* step through inArray */ 
			foreach($inArray as $key=>$val) { 
				if(is_array($val)) { 
				/* recurse on array elements */ 
				$newArray[$key] = utf8json($val); 
			} else { 
				/* encode string values */ 
				$newArray[$key] = utf8_encode($val); 
			} 
		} 

		/* return utf8 encoded array */ 
		return $newArray; 
		} 
		
		$keyword = strval($_POST['query']);
		$search_param = "{$keyword}%";
		
		if (isset($_POST['category'])) 
		{
			$category = strval($_POST['category']);
		}
		
		if ($category == "Colonne2")
		{
			$query = "SELECT * FROM Table1 WHERE Colonne2 LIKE ?;";
		}
		elseif ($category == "Colonne3")
		{
			$query = "SELECT * FROM Table1 WHERE Colonne3 LIKE ?;";
		}
		elseif ($category == "Colonne1")
		{	
			$keyword = strval($_POST['query']);
			$query = "SELECT * FROM Table2 WHERE Colonne1 LIKE ?;";
		}
		elseif ($category == "Colonne2")
		{	
			$query = "SELECT * FROM Table2 WHERE Colonne2 LIKE ?;";
		}
		elseif ($category == "Colonne3") // ici date
		{
			$query = "SELECT * FROM Table2 WHERE Colonne3 LIKE ?;";
		}
		else // par défaut
		{
			$query = "SELECT * FROM Table1 WHERE Colonne1 LIKE ?;";
		}
	
		$conn = new mysqli('127.0.0.1:3388', 'root', 'root' , 'database');

		$sql = $conn->prepare($query);
		$sql->bind_param("s",$search_param);
	
		$sql->execute();
		$result = $sql->get_result();
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if (($category == "Colonne2")) // Colonne2 Table1
				{
					$nomResult[] = $row["Colonne2"].' '.$row["Colonne3"];
				}
				elseif (($category == "Colonne2")) // Colonne3 Table1
				{
					$nomResult[] = $row["Colonne3"].' '.$row["Colonne2"];
				}
				elseif (($category == "Colonne1")) // Colonne1 Table2
				{
					$nomResult[] = utf8_decode('id n° ').$row["Colonne1"].' '.$row["Colonne2"].' Euros '.$row['Colonne3'];
				}
				elseif (($category == "Colonne2")) // Colonne2 de Table2
				{
					/* utf8_decode(' Euros n°') juste pour décodé ° */
					$nomResult[] = $row["Colonne2"].utf8_decode(' id n°').$row["Colonne1"].' '.$row['Colonne3'];
				}
				
				elseif (($category == "Colonne3")) //Colonne3 de Table2, date
				{ 
					$nomResult[] = $row['Colonne3'].utf8_decode(' id n°').$row["Colonne1"].' '.$row["Colonne2"].'€';
				}
				else
				{
					$nomResult[] = $row["Colonne2"].' '.$row["Colonne3"]; // Colonne2 et Colonne3 de Table1
				}
			}
			/* Ancienne méthode ne prenant pas en compte les accents sur les charactères. */
			/* echo echo json_encode($nomResult); */
			/* Fonction utf8json($inArray) qui encode tout en UTF8 */
			/* source http://php.net/manual/fr/function.json-encode.php#99837 */
			echo json_encode(utf8json($nomResult));
			
		}
		$conn->close();
	// }
?>

