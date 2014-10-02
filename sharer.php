<?php
	require_once("config.php");
	class Sharer {
		public function __construct() {
			$this->dba = new mysqli($GLOBALS["config"]["Host"],$GLOBALS["config"]["User"],$GLOBALS["config"]["Password"], $GLOBALS["config"]["Database"]);
			$this->checkAndInstallDatabase();
		}

		public function getSalt() {
			return $GLOBALS["config"]["Salt"];
		}

		private function checkAndInstallDatabase() {
			$this->db()->query("CREATE TABLE IF NOT EXISTS Files(
				id			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				mime		VARCHAR(20) NOT NULL,
				filename	VARCHAR(64) NOT NULL,
				uploaded	INT NOT NULL,
				ip 			VARCHAR(64) NOT NULL,
				size		INT NOT NULL,
				password	VARCHAR(64))");
			$this->db()->query("CREATE TABLE IF NOT EXISTS IDs(
				id 			INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				hash 		VARCHAR(64) NOT NULL,
				file 		INT NOT NULL,
				FOREIGN KEY(file) REFERENCES Files(id)
			)");
		}

		public function registerFile($name, $type, $size, $ip, $time, $password) {
			$mysqli = $this->db();
			$query = $mysqli->prepare("INSERT INTO Files(mime, filename, uploaded, ip, size, password) VALUES(?, ?, ?, ?, ?, ?)");
			$query->bind_param("ssisis", $type, $name, $time, $ip, $size, $password);
			$query->execute();
			$query->close();
			$id = $mysqli->insert_id;
			$hash = hash("sha256", $id);
			$query = $this->db()->prepare("INSERT INTO IDs(hash, file) VALUES(?, ?)");
			$query->bind_param("si", $hash, $id);
			$query->execute();
			$query->close();
			return $hash;
		}

		public function retrieveFileInfo($hash)  {
			$query = $this->db()->prepare("SELECT f.mime, f.filename, f.uploaded, f.ip, f.size, f.password FROM IDs i LEFT JOIN Files f ON f.id = i.file WHERE i.hash = ?");
			$query->bind_param("s", $hash);
			$query->execute();
			$query->bind_result($mime, $filename, $uploaded, $ip, $size, $password);
			if($query->fetch()) {
				$arr = Array("id" => $hash, "mime" => $mime, "filename" => $filename, "uploaded" => $uploaded, "ip" => $ip, "size" => $size, "password" => $password);
				$query->close();
				return $arr;
			}
			else {
				$query->close();
				return null;
			}
		}

		public function db() {
			return $this->dba;
		}

		public function __destruct() {
			$this->dba->close();
		}
	}
?>
