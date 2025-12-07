<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fallback</title>
    <meta http-equiv="refresh" content="5" >
    <style>
        .fallhead{
            text-align:center;
            font-weight:600;
            font-size: 50px;
            color: #330066
        }
        .fallimg
        {
            width:100%;
            max-width:400px;
        }
        .imgdiv
        {
            text-align:center;
            padding:20px;
            text-align:center;
        }
        .fallmain
        {
            text-align:center;
            font-size: 1.5rem;
            color:#444444;
        }
        .fallpara
        {
            text-align:center
        }
        @media only screen and (max-width: 900px) {
            .fallimg {
                width:100%;
                max-width:600px;
            }
            .fallhead{
                font-size: 1.2rem;
                font-size: 6vw;
            }
            .fallmain{
                font-size:1rem;
                font-size: 3.5vw;
            }
        }
    </style>
</head>
<body style="background-color:#ffffff">
    <br><br><br>
    <h1 class="fallhead">Welcome to RKElectricalGrid</h1>
    <p class="fallmain">Please <b>TURN ON</b> your WiFi or mobile data to continue</p>
    <div class="imgdiv"><img src="/image/no-internet.svg" class="fallimg"></div>
</body>
<script>
    window.addEventListener('online', () => {
        window.location.reload();
    });
</script>
</html>