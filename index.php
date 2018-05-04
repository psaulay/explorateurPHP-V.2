<?php
//Chargement de twig
require_once __DIR__ . '/vendor/autoload.php';
$loader = new Twig_Loader_Filesystem('templates'); // Dossier contenant les templates
$twig = new Twig_Environment($loader, array(
'cache' => false
));
//j'appel le fichier fonction.php
include('functions.php');
//indiquer le nom du dossier à explorer
$directories = "root";
//si dir existe attribuer sa valeur à la variable directories
if (isset($_GET['dir'])) {
  $directories = $_GET['dir'];
}
//si directories contient la string root et ne contient pas la string '..' et si la cible existe
if(substr($directories, 0, 4) === 'root' && !strpos($directories, '..') && file_exists($directories)){
//attribuer à la variable le resultat de scandir en retirant les éléments '.' et '..'
  $a = array_diff(scandir($directories), array('..', '.'));
//créer les variable en indiquant que ce sont des tableaux
  $folders = $files= [];
//pour chaque éléments inclus dans la variable '$a'
  foreach ($a as $dir) {
//créer la variable '$path'qui correspond au chemin d'accés de chaque éléments
    $path = $directories.'/'.$dir;
//si l'élément est un dossier on envoit dans l'array folders le résultat de la fonction sinon en envoi dans l'array files le résultat de la fonction
    is_dir($path) ? array_push($folders, getDatas($path, $dir)) : array_push($files, getDatas($path, $dir));
   }
//indiquer à twig vers quel template html on veut envoyer les données
  echo $twig->render("template1.html", array(
//nommage des variables pour permettre à twig de les utiliser
    'folders' => $folders,
    'files'   => $files,
    'path'    => $directories,
  ));
//si directories ne contient pas la string root ou contient la string '..' ou si la cible n'existe pas
} else {
//indiquer à twig vers quel template html on veut envoyer les données
  echo $twig->render("template1.html", array(
//créer une string erreur
    'error' => 'dossier inexistant',
    'path'  => $directories,
  ));
}
?>
