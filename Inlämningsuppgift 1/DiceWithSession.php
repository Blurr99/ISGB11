<?php
	session_start();
?>

<!doctype html>
<html lang="en" >

	<head>
		<meta charset="utf-8" />
		<title> <?php echo( $_SERVER["PHP_SELF"] ); ?> Roll the dice...</title>
		<link href="style/style.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>

	<body>
	
		<div>
			<?php

				session_regenerate_id(true);
				$disabled = true; //länkar "Exit" och "Roll Dices" ska inte kunna användas
				
				//Var uppmärksam på att PHP-tolken används på ett flertal ställen i filen!
				//Krav 1 – När man trycker på länken skapas sessionsvariabler och och texten "New Game" visas för användaren
				if(isset($_GET["linkNewGame"])) {
					echo("<h2> New Game </h2>" );
					$_SESSION["nbrOfRounds"] = 0;
					$_SESSION["sumOfAllRounds"] = 0;
					
					$disabled = false; //länkar "Exit och "Roll Dices" ska kunna användas
				}

				//Krav 2 - tar bort sessionsvariabler och avslutar session när man klickar på "Exit" och det finns sessionsvariabler
				if(isset($_GET["linkExit"]) && isset($_SESSION["nbrOfRounds"]) && isset($_SESSION["sumOfAllRounds"]))  {
					deleteSession();
				}
				
				//Krav 3 – tar bort sessionsvariabler och avslutar session när man har inte klickat på någon knapp och det finns inga sessionsvariabler
				 if(!isset($_GET["alinkNewGame"]) && !isset($_GET["linkRoll"]) && !isset($_GET["linkExit"]) && !isset($_SESSION["nbrOfRounds"]) && !isset($_SESSION["sumOfAllRounds"])) {
					deleteSession();
				}

				//Krav 4 – Om användaren inte har klickat på någon länk men sessionsvariablerna existerar, presenteras information för användaren	
				if(!isset($_GET["linkNewGame"]) && !isset($_GET["linkRoll"]) && !isset($_GET["linkExit"]) && isset($_SESSION["nbrOfRounds"]) && isset($_SESSION["sumOfAllRounds"])) {
					$counter = $_SESSION["nbrOfRounds"];
					$totalSum = $_SESSION["sumOfAllRounds"];
					
					echo("<p>" . "Summan av alla kast: " . $totalSum . "</p>");
					echo("<p>" . "Antal kast: " . $counter . "</p>");
					
					//för att undvika körtidsfel när man inte har kastat tärningar men det finns sessionsvariabler 
					if($totalSum != 0 && $counter != 0) {
						$mean_value = $totalSum / $counter;
						echo("<p>" . "Medelvärde: " . $mean_value . "</p>");
					}
					
					else{
						echo("<p> Det finns inget Medelvärde <p>");
					}
				
				}

				//Krav 5 - rullar tärningar när användaren klickar på "Roll"
				if(isset($_GET["linkRoll"]) && isset($_SESSION["nbrOfRounds"]) && isset($_SESSION["sumOfAllRounds"])) {
					
					$disabled = false;
					
					include("include/OneDice.php");
					include("include/SixDices.php");

					//kallar på funktionen som kastar tärningar
					$oSixDices = new SixDices();
					$oSixDices->rollDices();
					
					echo($oSixDices->svgDices());

					//värdet i sum ska vara returvärdet från sumDices();
					$sum = $oSixDices-> sumDices();

					$totalSum = $_SESSION["sumOfAllRounds"];
					$totalSum = $totalSum + $sum;

					$counter = $_SESSION["nbrOfRounds"];
					$counter++;

					$mean_value = $totalSum / $counter;

					echo("<p>" . "Medelvärde: " . $mean_value . "</p>");
					echo("<p>" . "Antal kast: " . $counter . "</p>");
					echo("<p>" . "Summan av alla kast: " . $totalSum . "</p>");
					
					//tilldela nya värden ll sessionsvariablerna
					$_SESSION["nbrOfRounds"] = $counter;
					$_SESSION["sumOfAllRounds"] = $totalSum;
					}

				
				//Krav 6 – Om sessionsvariablerna inte finns ska länkarna linkRoll och linkExit inte vara användbara	
				if(!isset($_SESSION["nbrOfRounds"]) && !isset($_SESSION["sumOfAllRounds"])){
					$disabled = true;
				}
				
				//Funktion från Föreläsning 4
				function deleteSession() {

					session_unset();
					
					if(ini_get("session.use_cookies")){

						$sessionCookieData = session_get_cookie_params();

						$path = $sessionCookieData["path"];
						$domain = $sessionCookieData["domain"];
						$secure = $sessionCookieData["secure"];
						$httponly = $sessionCookieData["httponly"];
	
						$name = session_name();
	
						setcookie($name, "", time() - 3600, $path, $domain, $secure, $httponly);
					}
					
					session_destroy();
				}



			?>
		</div>
		
		<a href="<?php ?>?linkRoll=true" class="btn btn-primary <?php if ($disabled) { echo("disabled");} ?>">Roll six dices</a>
		<a href="<?php ?>?linkNewGame=true" class="btn btn-primary">New game</a>
		<a href="<?php ?>?linkExit=true" class="btn btn-primary <?php if ($disabled) { echo("disabled");}?>">Exit</a>
		
		<script src="script/animation.js"></script>
		
	</body>

</html>