<?
if(preg_match("/^games\//",$_GET['x'])) {
  include getcwd()."/includes/actions/games.php";
} elseif(preg_match("/^news\//",$_GET['x'])) {
  include getcwd()."/includes/actions/news.php";
}