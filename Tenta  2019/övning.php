<?php
    session_start();
    session_regenerate_id(true);

    $disabled = true;
    if(isset($_POST["btnBegin"])){
        $_SESSION["counter"] = 0;
        $disabled = false;
    }

    if(isset($_POST["btnEnd"])){
        session_unset();
        session_destroy();
    }

    if(isset($_POST["btnTest"])){
        $_SESSION["counter"]++;
        $disabled = false;
    }

    if(isset($_SESSION["counter"]) && 
        !isset($_POST["btnBegin"]) && 
        !isset($_POST["btnEnd"]) && 
        !isset($_POST["btnTest"])) {
            $_SESSION["counter"]++;
            $disabled = false;
    }
?>
<!DOCTYPE html>
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
            if( !$disabled ){
                echo("<p>Du har besökt sidan <span>" . $_SESSION["counter"] . "</span> gånger!</p>");
            } else {
                echo("<p>Du har aldrig besökt sidan!</p>");
            }
        ?>

        <form action="<?php echo( $_SERVER["PHP_SELF"] );?>" method="post">
            <input type="submit" name="btnBegin" value="Begin" />
            <input type="submit" name="btnTest" value="Test"
                <?php
                    if( $disabled ) { echo("disabled= 'disabled'"); }
                ?>/>
            <input type="submit" name="btnEnd" value="End"
                <?php
                    if($disabled){echo("disabled = 'disabled'" );}
                ?>/>
        </form>
    </body>
</html>
