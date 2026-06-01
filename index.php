<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Maintenance</title>

    <style>

        body{
            margin:0;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:#0A4EA3;
            color:white;
            font-family:Arial,sans-serif;
        }

        .box{
            text-align:center;
            max-width:700px;
            padding:40px;
        }

        h1{
            font-size:3rem;
        }

        p{
            font-size:1.2rem;
        }

    </style>
</head>
<body>

<div class="box">

    <h1>🛠 Maintenance en cours</h1>

    <p>
        Amazing USA Canada est actuellement en maintenance.
    </p>

    <p>
        Merci de revenir un peu plus tard.
    </p>

</div>
<script>
    async function checkSite() {
        try {
            const res = await fetch('http://amazing-usa-canada.local:8080/API/status.php');
            const data = await res.json();

            if (data.online === true) {
                window.location.href = 'http://amazing-usa-canada.local:8080';
            }
        } catch (e) {
            return "";
        }
    }

    setInterval(checkSite, 30000);
</script>
</body>
</html>