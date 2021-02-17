<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>League</title>
    <link rel="stylesheet" href="public/style.css" type="text/css">
    <link rel="stylesheet" href="public/fonts.css" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

	<header>

    <nav>
      <ul>
        <li><a href="/leagueoflegends/play">Playrates</a></li>
        <li><a href="/leagueoflegends/play/league-win.php">Winrates</a></li>
        <li><a href="/leagueoflegends/play/league-ban.php">Banrates</a></li>
      </ul>
    </nav>

	</header>

  <main>

		<section class="page-top-league">

			<h2>Champion Banrate</h2>

      <select onChange="window.location.href=this.value">
        <option selected value="leagueplayrate">All</option>
        <option value="lolplaytop">Top</option>
        <option value="lolplayjungle">Jungle</option>
        <option value="lolplaymid">Mid</option>
        <option value="lolplaycarry">AD Carry</option>
        <option value="lolplaysupport">Support</option>
      </select>

    </section>

      <section class="page-table">
      <table>
      <tr>
        <th>Champion</th>
        <th>Banrate</th>
        <th>Playrate</th>
        <th>Winrate</th>
      </tr>

      <?php

        //Get database connection
        $servername = "";
        $username = "";
        $password = "";
        $dbname = "";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //Api key
        $apiKey = 'cf85ae4bd8a7e93a41f2b11775d04033';

        //Get playrate data
        $playcontents = file_get_contents('http://api.champion.gg/v2/champions?elo=PLATINUM&champData=playRate,winRate,banRate&sort=banRate-desc&limit=20&api_key='. $apiKey);
        $playdata = json_decode($playcontents, false);

        //Loop for twenty champions
        for ($x = 0; $x <= 19; $x++) {

        //Get champion id
        $championid = $playdata[$x]->championId;
        $sql = "SELECT id, name, img FROM users WHERE id=". $championid;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            $champion = $row["name"];
            $championimg = $row["img"];
          }
        }

        //Get champion data
        $playrate = round($playdata[$x]->playRate * 100,1);
        $winrate = round($playdata[$x]->winRate * 100,1);
        $banrate = round($playdata[$x]->banRate * 100,1);

        //display stats
        echo '<tr>';
          echo '<td><img class="image-champion" src=images-league/'. $championimg .' /><a class="champlink" target="_blank" href="http://champion.gg/champion/'. $champion .'">'. $champion .'</a></td>';
          echo '<td>'. $banrate .'%</td>';
          echo '<td>'. $playrate .'%</td>';
          echo '<td>'. $winrate .'%</td>';
        echo '</tr>';

        }

        $conn->close();

      ?>

      </table>
      </section>

	</main>

</body>

</html>
