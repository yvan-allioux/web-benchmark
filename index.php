<?php
header("Content-Type: text/html; charset=utf-8");

//connection
require("param.inc.php");
$pdo = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
$pdo->query("SET NAMES utf8");
$pdo->query("SET CHARACTER SET 'utf8'");
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags  -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Web Benchmark</title>

    <style>
        h1,
        h2,
        h3,
        a,
        p {
            font-family: Arial, Helvetica, sans-serif;
            text-decoration: none;
            margin: 20px;
        }

        #rgb {
            animation: color-change 1s infinite;
        }

        @keyframes color-change {
            0% {
                color: red;
            }

            25% {
                color: blue;
            }

            50% {
                color: yellowgreen;
            }

            75% {
                color: green;
            }

            100% {
                color: red;
            }
        }

    </style>

</head>

<body class="text-center">


    <h1>Benchmark calculation of a max of prime number in 20 seconds</h1>
    <button type="button" class="btn btn-success m-1" onclick="bench(20000);">Click here to start the benchmark and wait 20 seconds</button>
    <p class="m-1" id="rgb">If this text has stopped changing color <br>don't do anything <br>it loads!</p>
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
            document.getElementById("share").innerHTML = "<button type=\"button\" class=\"btn btn-info\"><a href=\"https://twitter.com/intent/tweet?text=The+computing+power+score+of+my+" + getDeviceType() + "+is+" + r + "%21%F0%9F%9A%80%0D%0A%0D%0A%28test+on+web+benchmark+%F0%9F%94%A5+https%3A%2F%2Fbit.ly%2FWeb-Benchmark%29%20\">Share my results on twitter !</a> <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"30\" height=\"30\" fill=\"currentColor\" class=\"bi bi-twitter\" viewBox=\"0 0 16 16\"> <path d=\"M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z\"/> </svg></button>";
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



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>