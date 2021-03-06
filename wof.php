<?php

session_start();

include "includes/shortcuts.php";

$ordering = [
	"time" => "time ASC",
	"attempts" => "attempts ASC",
	"difficulty" => "difficulty DESC"
];

$order = $_GET["order"] ?? "time";

$stmt = $db->prepare("SELECT users.login, users.avatar, id_user, time, attempts, difficulty FROM games
	INNER JOIN users ON users.id = games.id_user
	ORDER BY games." . $ordering[$order] ?? $ordering["time"] . "
	LIMIT 10
");
$stmt->execute();
$leaderboard = $stmt->fetchAll();

function createRank($data, $rank) {
	extract($data); ?>
	<tr class="scored" id="rank-<?= $rank ?>">
		<td><a href="profil.php?id=<?= $data["id_user"] ?>" class="user-link"><img src="<?= $avatar ?>" class="wof-image"/><span>&nbsp;<?= $login ?></span></a></td>
		<td><?= $time ?></td>
		<td><?= $attempts ?></td>
		<td><?= $difficulty ?></td>
	</tr>
<?php } ?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<link rel="stylesheet" href="style.css">
		<title>Wall of Fame</title>
	</head>

	<body>
		<header>
			<?php include("includes/header.php"); ?>
		</header>

		<main>
			<table class="leaderboards" id="ez">
				<thead>
					<tr>
						<th>Login</th>
						<th><a href="wof.php?order=time">Temps</a></th>
						<th><a href="wof.php?order=attempts">Essais</a></th>
						<th><a href="wof.php?order=difficulty">Difficulté</a></th>
					</tr>
				</thead>
				<tbody>
					<?php
						for ($i = 0; $i < count($leaderboard); $i++) {
							createRank($leaderboard[$i], $i);
						}
					?>
				</tbody>
			</table>
		</main>

		<footer>
		</footer>
	</body>
</html>