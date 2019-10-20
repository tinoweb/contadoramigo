<?

$output = shell_exec('mkdir "/home/contadoramigo2/public_html/tmp" 2>&1');
echo $output;
exit;

session_start();

$_SESSION['teste'] = 'teste123';

header('location: teste_secao2.php');
?>