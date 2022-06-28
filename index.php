<?php
header("Content-Type: text/html; charset=utf-8");

//connection
require("param.inc.php");
$pdo = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
$pdo->query("SET NAMES utf8");
$pdo->query("SET CHARACTER SET 'utf8'");

?>

<!DOCTYPE html>


<html lang="en">
<header>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Benchmark</title>
    <style>
	h1{
            font-family: Arial, Helvetica, sans-serif;
        }
        h2{
            font-family: Arial, Helvetica, sans-serif;
        }
        h3{
            font-family: Arial, Helvetica, sans-serif;
        }

        p{
            font-family: Arial, Helvetica, sans-serif;
        }
        a{
            font-family: Arial, Helvetica, sans-serif;
        }

        body>* {
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }

        #imageLoading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        #start {
            display: block;
            background-color: green;
            border-radius: 10px;
            border: 4px double #cccccc;
            color: #eeeeee;
            font-size: 28px;
            padding: 20px;
            width: 10cm;
            transition: all 0.5s;
            cursor: pointer;
        }

        #rgb {
            animation: color-change 1s infinite;
        }

        @keyframes color-change {
            0% {
                color: red;
            }

            50% {
                color: blue;
            }

            100% {
                color: red;
            }
        }
    </style>
</header>

<body>

    <h1>Benchmark calculation of a max of prime number in 20 seconds</h1>
    <button id="start" onclick="bench(20000);">Click here to start the benchmark and wait 20 seconds</button>
    <p id="rgb">If this text has stopped changing color<br>
        don't do anything<br>
        it loads!</p>
    <h2 id="result">-</h2>

    <?php
        $requete = $pdo -> prepare("SELECT AVG(scorGrab) FROM `GRAB` WHERE device = 'mobile'");
        $requete -> execute();
        $ligne = $requete -> fetch();
        echo "<p>Average score on mobile : " . $ligne['AVG(scorGrab)'] . "</p>";

        $requete = $pdo -> prepare("SELECT AVG(scorGrab) FROM `GRAB` WHERE device = 'desktop computer' LIMIT 1");
        $requete -> execute();
        $ligne = $requete -> fetch();
        echo "<p>Average score on desktop PC : " . $ligne['AVG(scorGrab)'] . "</p>";
    ?>

    <h3 id="share"></h3>
    <p id="result2" style="color:lightgray"></p>
    <p style="color:lightgray">by yvan allioux</p>
    <?php
        //$moyenne = $pdo->query("SELECT AVG(scorGrab) FROM `GRAB` WHERE device = 'mobile' LIMIT 1;")->fetch();

        /* $moyenne2 = $pdo->prepare("SELECT AVG(scorGrab) FROM `GRAB` WHERE device = 'mobile' LIMIT 1"); 
        $moyenne2->execute(); 
        $row = $moyenne2->fetch();

        echo "- ".$row */
    ?>

    <script>
        const getDeviceType = () => {
            const ua = navigator.userAgent;
            if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
                return "tablet";
            }
            if (
                /Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(
                    ua
                )
            ) {
                return "mobile";
            }
            return "desktop computer";
        };

        function bench(tempsMs) {


            var start = window.performance.now();
            i = 3; //compteur
            r = 0;
            mostHie = 0;

            //while (i != 500000+1){
            while (window.performance.now() - start < tempsMs) {
                //while (1){
                //printf("i %d", i);

                for (j = 2; j < i; j++) {
                    //printf("- j %d ", j);
                    if (i % j == 0) {
                        //printf("%d pas premier\n", i);
                        break;
                    }
                    if (j == i - 1) {
                        r++;
                        mostHie = i;
                        //console.log('est premier : ', i);
                        //printf("premier");
                    }
                }

                i++;
            }
            var end = window.performance.now();
            var time = end - start;
            //console.log('time: ', time);

            
            document.getElementById("result").innerHTML = "Score of my " + getDeviceType() + " : " + r + " prime number finds";
            document.getElementById("share").innerHTML = "<a href=\"https://twitter.com/intent/tweet?text=The+computing+power+score+of+my+" + getDeviceType() + "+is+" + r + "%21%F0%9F%9A%80%0D%0A%0D%0A%28test+on+web+benchmark+%F0%9F%94%A5+https%3A%2F%2Fbit.ly%2FWeb-Benchmark%29%20\">Share my results on twitter !</a>";
            document.getElementById("result2").innerHTML = mostHie + " is the largest prime number found";
	        httpGet("http://141.94.206.18/GetGrab.php?device=" + getDeviceType() + "&scor=" + r);
	    }

        function httpGet(theUrl) {
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open("GET", theUrl, false); // false for synchronous request
            xmlHttp.send(null);
            return 0;
        }

    </script>

</body>

</html>
