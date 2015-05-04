<?php include_once('LevelGenerator.php');

$tiles = new LevelGenerator();

?>

<!DOCTYPE html>
<html lang="en">

<head>

	<style>
		#level {
			position: relative;
			width: 2000px;
			height: 1600px;
		}
		.tile {
			position: absolute;
			height: 400px;
			width: 500px;
			background: #ccc;
		}

		.tile.row-1 { top: 0; }
		.tile.row-2 { top: 400px; }
		.tile.row-3 { top: 800px; }
		.tile.row-4 { top: 1200px; }

		.tile.col-1 { left: 0; }
		.tile.col-2 { left: 500px; }
		.tile.col-3 { left: 1000px; }
		.tile.col-4 { left: 1500px; }
	</style>

</head>

<body>
	
	<div id="level">
		
		<?php $r = 0; foreach($tiles->generateLevel() as $row): $r++; ?>
			<?php $c = 0; foreach($row as $column): $c++; ?>
				<div class="tile row-<?= $r; ?> col-<?= $c; ?>">
					<img src="/images/<?= $column; ?>.png" alt="Image">
				</div>
			<?php endforeach; ?>
		<?php endforeach; ?>

	</div>

</body>

</html>