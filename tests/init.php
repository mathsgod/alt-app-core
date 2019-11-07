<?

declare(strict_types=1);
error_reporting(E_ALL && ~E_WARNING);
$config = parse_ini_file(__DIR__ . "/config.ini", true);
$db = new R\DB\Schema($config["database"]["database"], $config["database"]["hostname"], $config["database"]["username"], $config["database"]["password"]);
App\Core\Model::$_db = $db;

require_once(__DIR__ . "/Testing.php");
