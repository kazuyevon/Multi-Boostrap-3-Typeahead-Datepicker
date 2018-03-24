<!DOCTYPE html>

<html>
<head>
    <title>Searchbox Boostrap Autocomplete avec DatePIcker et plusieurs catégorie Typeahead.js avec chargement dynamique des données utilisant PHP Ajax</title>
	<!-- Boostrap core CSS-->
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
	<!-- Jquery-ui core CSS-->
	<link href="css/jquery-ui.min.css" rel="stylesheet">
    
	<!-- Jquery Core plugin JavaScript-->
	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<!-- Boostrap Core plugin JavaScript-->
	<script src="js/bootstrap-3.3.7.min"></script>
	<!-- Jquery-ui Core plugin JavaScript-->
	<script src="js/jquery-ui.min.js"></script>
	<!-- Typeahead Core plugin JavaScript-->
    <script src="js/bootstrap3-typeahead.min.js"></script>
	<!-- Custom script for widget Datepicker of Jquery-ui-->
	<script src="js/datepicker-fr.js"></script>
	<style>
	.typeahead { 
		border: 2px solid #FFF;
		border-radius: 4px;
		padding: 8px 12px;max-width: 300px
		min-width: 290px;
		background: rgba(66, 52, 52, 0.5);
		color: #FFF;
	}
	.tt-menu { 
	width:300px; 
	}
	ul.typeahead{
		margin:0px;
		padding:10px 0px;
	}
	ul.typeahead.dropdown-menu li a {
		padding: 10px !important;	
		border-bottom:#CCC 1px solid;
		color:#FFF;
	}
	ul.typeahead.dropdown-menu li:last-child a { 
		border-bottom:0px !important; 
	}
	.bgcolor {
		max-width: 550px;
		min-width: 290px;
		max-height:340px;
		padding: 100px 10px 130px;
		border-radius:4px;
		text-align:center;
		margin:10px;
	}
	.demo-label {
		font-size:1.5em;
		color: #686868;
		font-weight: 500;
		color:#FFF;
	}
	.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover {
		text-decoration: none;
		background-color: #1f3f41;
		outline: 0;
	}
	</style>	
</head>
<?php
/* source : */
/* https://github.com/bassjobsen/Bootstrap-3-Typeahead */
/* https://phppot.com/jquery/bootstrap-autocomplete-with-dynamic-data-load-using-php-ajax/ */
$keyword = "";
$search_param = "{$keyword}%"; //conditionne le $keyword avec le % pour la recherche SQL avec LIKE CLAUSE
$category ="Colonne2"; //par défaut
echo '
<body>
	<div class="bgcolor">
		<label class="demo-label">Recherche :</label><br/> <input type="text" name="searchbox" id="searchbox" class="typeahead"/><br>
		
		<form method="POST" action="'; echo htmlspecialchars($_SERVER["PHP_SELF"]);echo '">
			<!-- On admet que Colonne1 de Table1 est id, pas utile ici. -->
			<input type="radio" name="search" checked value="Colonne2">Colonne2 de Table1
			<input type="radio" name="search" value="Colonne3">Colonne3 de Table1<br>
			<!-- On admet que Colonne1 de Table2 est id. -->
			<input type="radio" name="search" value="Colonne1">Colonne1 de Table2
			<input type="radio" name="search" value="Colonne2">Colonne2 de Table2
			<input type="radio" name="search" value="Colonne3">Colone3 de Table2(date)
			<br><br>
			<input type="submit" name="submit" value="Submit">  
		</form>
		<br>
	</div>
</body>
<script>
	/* Envoie la saisie en recherche sql */
    $(document).ready(function () {
		var category;
        $("#searchbox").typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "server.php",
					data: "query=" +  query + "&category=" + category,
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							return item;
                        }));
                    }
                });
            }
        });
		/* Contrôle la searchbox en fonction du checkbox choisit avec datepicker actif juste pour le checkbox date */
		$("input[name=search]").change(function(){
			if($(this).is(":checked")) {
				category = $(this).val();
				/* stocke la valeur de category dans le php */
				$category = category;
				
				/* Si date séléctionné, on active le datepicker */
				if(category === "date"){
					$( ".typeahead" ).attr("id", "datepicker");
					$( "#datepicker" ).datepicker();
					$( "#datepicker" ).datepicker( $.datepicker.regional[ "fr" ] );
				}
				
				/* Si autre séléctionné, on désactive datepicker */
				if(category !== "date"){
				
					if (document.getElementById("datepicker") !== null){
						
						$( "#datepicker" ).datepicker("destroy");
						$( "#datepicker").removeClass("hasDatepicker");
						
						document.getElementById("datepicker").id = "searchbox";
						$( "#ui-datepicker-div" ).remove();
					}
				
				}
				
				/* On vide la searchbox */
				if ($("input[name=searchbox]").val()) {
					$("input[name=searchbox]").val("");
				}
			}
		});
    });
</script>
</html>';
?>