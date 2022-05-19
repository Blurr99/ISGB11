<!doctype html>
<html lang="en" >

	<head>
		<meta charset="utf-8" />
		<title> <?php echo( $_SERVER["PHP_SELF"] ); ?>Roll the dice...</title>	
		<link href="style/style.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>

	<body>
	
		<div>
			<?php

				$disabled = false;

			 	//När det finns inga kakor
				if( !isset($_COOKIE["nbrOfRounds"]) && !isset($_COOKIE["sumOfAllRounds"])) {
					$disabled = true; //knapparna ska inte vara användbara
				}

				//När man klickar på knappen "New Game"
				if( isset( $_POST["btnNewGame"] ) ) {
					
					echo("<h2> New Game! </h2>");
					
					//skapar kakor
					setcookie( "nbrOfRounds", 0, time() + 3600, "/" );
					setcookie( "sumOfAllRounds", 0, time() + 3600, "/");

					$disabled = false; //knapparna ska kunna användas
				}
				
				//När ingen av knapparna har tryckts men det finns kakor 
				if( !isset($_POST["btnNewGame"]) && !isset( $_POST["btnRoll"] )  && !isset( $_POST["btnExit"]) && isset($_COOKIE["nbrOfRounds"]) && isset($_COOKIE["sumOfAllRounds"])){

					$counter = $_COOKIE["nbrOfRounds"];
					$totalSum = $_COOKIE["sumOfAllRounds"];

					echo("<p>" . "Antal kast: " . $counter . "</p>");
					echo("<p>" . "Summan av alla kast: " . $totalSum . "</p>");

					//för att undvika körtidsfel när man inte har kastat tärningar men det finns sessionsvariabler 
					if($totalSum != 0 && $counter != 0) {
						$mean_value = $totalSum / $counter;
						echo("<p>" . "Medelvärde: " . $mean_value . "</p>");
					}
					
					else{
						echo("<p> Det finns inget Medelvärde <p>");
					}
				
				}

				//När knappen "Roll six dices" trycks och det finns kakor
				if( isset( $_POST["btnRoll"]) && isset($_COOKIE["nbrOfRounds"]) && isset($_COOKIE["sumOfAllRounds"] ) ){
					echo("<p>" . $_POST["btnRoll"] . "</p>");
					
					include("include/OneDice.php");
					include("include/SixDices.php");

					//kallar på funktionen som kastar tärningar
					$oSixDices = new SixDices();
					$oSixDices->rollDices();
					
					echo($oSixDices->svgDices());
					
					//värdet i $sum ska vara returnvärdet från sumDices()
					$sum = $oSixDices-> sumDices();

					$totalSum = $_COOKIE["sumOfAllRounds"];
					$totalSum = $totalSum + $sum;

					$counter = $_COOKIE["nbrOfRounds"];
					$counter++;

					$mean_value = $totalSum / $counter;

					echo("<p>" . "Medelvärde: " . $mean_value . "</p>");
					echo("<p>" . "Antal kast: " . $counter . "</p>");
					echo("<p>" . "Summan av alla kast: " . $totalSum . "</p>");

					setcookie("nbrOfRounds", $counter, time() + 3600, "/");
					setcookie("sumOfAllRounds", $totalSum, time() + 3600, "/");
				}


				//När knappen "Exit" trycks och det finns kakor
				if(isset($_POST["btnExit"]) && isset($_COOKIE["nbrOfRounds"]) && isset($_COOKIE["sumOfAllRounds"])) {
					$disabled = true;

					//kakor tas bort
					setcookie("nbrOfRounds", null, -1, "/");
					setcookie("sumOfAllRounds", null, -1, "/");
				}

				
			?>
		</div>
		<form action="<?php echo( $_SERVER["PHP_SELF"] );?>" method="post">
			<input type="submit" name="btnRoll" class="btn btn-primary" value="Roll six dices" <?php if( $disabled ) { echo( "disabled='disabled'" ); }  ?>/>
			<input type="submit" name="btnNewGame" class="btn btn-primary" value="New Game" />
			<input type="submit" name="btnExit" class="btn btn-primary" value="Exit" <?php if($disabled) {echo("disabled='disabled'");}?>/>
		</form>

		<script src="script/animation.js"></script>
	</body>

</html>