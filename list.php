<!DOCTYPE HTML>
<html>
	<head>
		<title>Share</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<?php
			function size_format($size) {
				$endings = ["B", "KB", "MB", "GB", "TB"];
				$i = 0;
				while($size > 1024) {
					$i++;
					$size /= 1024;
				}
				return round($size) . $endings[$i];
			}
			require_once("sharer.php");
			$sharer = new Sharer();
			$hashes = $sharer->listHashes();
			foreach($hashes as $hash) {
				$file = $sharer->retrieveFileInfo($hash);
				?>
					<div class="download listelem">
						<div style="width : 520px;">
							<?php echo($file["filename"]);?>
						</div>
						<div style="width : 300px;">
							<?php echo(date("d.m.y H:i:s", $file["uploaded"]));?>
						</div>
						<div style="width : 90px;">
							<?php echo(size_format($file["size"]));?>
						</div>
						<div style="width : 70px;">
							<a href=download.php?id=<?php echo($hash); ?>>Link</a>
						</div>
					</div>
				<?php
			}
		?>
	</body>
</html>