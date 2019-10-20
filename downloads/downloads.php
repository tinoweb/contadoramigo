<?
$arquivo = $_GET['arquivo'];

$file = $arquivo;

if (file_exists($file)) {

		header("Content-type: application/save");
		header("Content-Length:" . filesize($file));
		header('Content-Disposition: attachment; filename=' . $file);
		header("Content-Transfer-Encoding: binary");
		header('Expires: 0');
		header('Pragma: no-cache');
		
		echo preg_replace('/(\\r)?\\n(\\r)?/', "\r\n", file_get_contents($file));

    exit;

}
?>