<?php
    //kurser/ISGB11/uppgift2.php
    
    if( !isset($_COOKIE["counter"] )){
        echo("<p> if is working too </p>");
        setcookie("counter", 0, time() +  3600, "/");
    } else{
        setcookie("counter", ($_COOKIE["counter"] += 5), (time() + (60* 60 * 24 * 365) ) );
    }
?>
<DOCTYPE html>
    <html>
        <head>
            <title>Uppgift 1.</title>
            <style>
                p { font-style: italic; }
                span { font-weight: bold; }
            </style>
        </head>
        <body>
            <?php
                if(isset ($_COOKIE["counter"])){
                    echo("<p>Innehållet i kakan är <span>" . $_COOKIE["counter"] . "</span>!</p>");
                } else {
                    echo("<p>Kakan är ännu inte skapad!</p>" );
                }

                for($i = 0; $i < $_COOKIE["counter"]; $i++){
                    echo("<p><b>" . ++$i . "</b></p>");
                }
            ?>
            <a href="<?php echo($_SERVER["PHP_SELF"]); ?>">Ladda om sidan"</a>
        </body>
    </html>