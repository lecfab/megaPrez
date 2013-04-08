<?php

/*
  Ce script réalisé en février 2013 par Rannios (http://rannios.free.fr) a pour but de réaliser automatiquement
  des fiches de présentations de films, musiques, logiciels ou livres, à poster sur le forum http://forum.mega-search.ws
  qui référence un maximum de liens Mega (http://mega.co.nz).
  Rannios a fait don de cet ensemble de programmes le 8 avril 2013. Il peut en faire ce qu'il veut, c'est-à-dire
  l'utiliser, le modifier, l'implémenter, le compléter, etc.
*/

header('Content-Type: text/html; charset=ISO-8859-1');
$file_affluence = fopen('affluence.txt', 'r+');
$affluence = fgets($file_affluence) + 1;
fseek($file_affluence, 0);
fputs($file_affluence, $affluence);
fclose($file_affluence);

if(isset($_GET['shorten'])) { $links = explode("|%stop%|", $_POST['link']); $a=0; foreach($links AS $link) { $shorten_url = $link; include("shorten.php"); $return.=($a?"|%stop%|":"").$shorten_return; $a=1; } echo $return; }
elseif($_GET['getInfos'] == "musique") { include("prez_musique_getInfos.php"); }
elseif($_GET['getInfos'] == "film") { include("prez_film_getInfos.php"); }
elseif($_GET['getInfos'] == "livre") { include("prez_livre_getInfos.php"); }
else
{
?>

<script src="http://rannios.free.fr/js/jquery.js" type="text/javascript"></script>
<script src="http://rannios.free.fr/js/jquery.draggable.js" type="text/javascript"></script>
<script src="http://rannios.free.fr/js/jquery.alerts.js" type="text/javascript"></script>
<script src="http://rannios.free.fr/js/js.js" type="text/javascript"></script>
<script src="http://www.terredefeu.fr/javascript/base_functions.js" type="text/javascript"></script>
<style>
  html { background: -webkit-radial-gradient(top center, ellipse, rgba(44, 150, 255, 0.6) -1%, white 12%); }
  body { max-width: 950px; margin: 0 auto; }
  form { display: inline-block; border: 1px solid silver; width: 100%; }
  .categorie { display: inline-block; margin: 0 10px; padding: 10px; vertical-align: top; }
  .categorie .titre { font-size: 1.5em; font-weight: bold; }
  .categorie .champs { padding: 10px; border: 1px solid silver; }
  .categorie .champs .champ label { display:inline-block; width: 200px; margin-right: 10px; text-align: right; vertical-align: top; }
  .categorie .champs .champ.obligatoire label:after { content: "*"; color: red; font-weight: bold; }
  input:not([type=submit]), textarea { outline: 0; border: 1px solid silver; padding: 3px 7px; }
  input:not([type=submit]):focus, textarea:focus { box-shadow: 0px 0px 10px #2CA8FF; border-color: rgba(44, 117, 255, 0.5);  }
  input[type=submit] { margin: 0; width: 100%; }
  #reponse textarea { width: 98%; height: 300px; margin: 0 auto; }
  </style>
  <h1 align="center">Mega Présa</h1>
  <h4>
    Les présentations créées ici doivent être utilisées dans le forum <a href="http://www.forum-mega.eu" target="_blank">Mega Search</a>. Nombre de visites : <?php echo $affluence?> <br />
    Les champs marqués d'un <span style="color:red">*</span> sont obligatoires.
  </h4> 
  <button onclick="charger('film')">Film/Série</button>
  <button onclick="charger('musique')">Musique</button>
  <button onclick="charger('livre')">Livre</button>
  <button onclick="charger('logiciel')">Logiciel</button>
  <div id="contenu"></div>

  <script type="text/javascript">
    function charger(partie)
    {
      $('#contenu').slideUp(300).load("prez_"+ partie +".php").slideDown(500, function() { $(this).find("input:first").focus(); });
    }
    
    var center = ["", ""];
  </script> <?php
}
