<?php
//$_POST['url'] = "http://www.deezer.com/fr/album/72572";
if($_POST['url'] != "" or 1)
{ header('Content-Type: text/html; charset=ISO-8859-1');
  function unicode_conv($originalString) {
  // The four \\\\ in the pattern here are necessary to match \u in the original string
  $replacedString = preg_replace("#\\\\u(\w{4})#", "&#x$1;", $originalString);
  $unicodeString = mb_convert_encoding($replacedString, 'ISO-8859-1', 'HTML-ENTITIES');
  return $unicodeString;
}
  
  $recup = array();
  $content = file_get_contents($_POST['url']);

  function recup($type, $value) { global $recup; $recup[$type] = ((("$value"))); }
  function recup2($type, $value) { global $recup; $recup[$type][sizeof($recup[$type])] = " $value"; }
  //preg_replace("#playercontrol\.init\('album_page', (.+);#eU", "recup('content', '$1')", $content);
  preg_replace("#createDatagrid(.+);#esU", "recup('content', ('$1'))", $content);                           // js important
  $recup['content'] = stripslashes(unicode_conv($recup['content'])); 
  preg_replace("#SNG_TITLE\":\"(.+)\"#eU","recup2('titres', ('$1'))", $recup['content']);                   // titres chansons
  preg_replace("#\"total\":(.+)\}#eU", "recup('nb_chanson', '$1')", $recup['content']);                     // nombre chansons
  preg_replace("#DURATION\":\"(.+)\"#eU","recup2('durees', '$1')", $recup['content']);                      // durées chansons
  preg_replace("#ART_NAME\":\"(.+)\"#eU", "recup('artiste', '$1')", $recup['content']);                     // artiste
  preg_replace("#RANK_SNG\":\"(.+)\"#eU","recup2('notes', '$1')", $recup['content']);                       // notes des chansons
  //preg_replace("#ALB_TITLE\":\"(.+)\"#eU", "recup('album', '$1')", $recup['content']);
  preg_replace("#property=\"og:title\" content=\"(.+)\"#eU", "recup('album', '$1')", $content);             // titre de l'album
  preg_replace("#ALB_PICTURE\":\"(.+)\"#eU", "recup('image', 'http://cdn-images.deezer.com/images/cover/$1/600x600-000000-99-0-0.jpg')", $recup['content']);  // image de l'album
  preg_replace("#property=\"music:release_date\" content=\"(.+)-#eU", "recup('annee', '$1')", $content);    // date de sortie
  if($recup['durees']) { foreach($recup['durees'] AS $key => $value)
  {
    $recup['durees'][$key] = round($value/60).":".substr("0".($value%60), -2);                              // mise en mm:ss des durées
  }}
  if($recup['titres']) { foreach($recup['titres'] AS $key => $value)
  {
    $recup['titres'][$key] = preg_replace("#\+#", "%20", urlencode($value));
  }}
  $shorten_url = $_POST['link'];
  include("shorten.php");
  $recup['link'] = $shorten_return;                                                                         // lien shortené v.gd
  $recup['artiste_link'] = "[url=http://fr.wikipedia.org/wiki/$recup[artiste]]$recup[artiste][/url]";       // lien wikipédia de l'artiste
  $recup['note'] = $recup['notes'] ? round(array_sum($recup['notes'])/$recup['nb_chanson']/100000, 2) ."/10" : "";// note moyenne de l'album
  unset($recup['content']);
  //echo $recup['titres'][0];
  echo json_encode($recup);
  //
}elseif(0){
  ?>
  <h1><?=$recup['album']?> de <?=$recup['artiste']?> en <?=$recup['annee']?></h1>
  <?=json_encode($recup['titres']) . "<br />".json_encode($recup['durees']) . "<br />".$recup['note']?>
  <?php
}
else
{
  echo "Interdit";
}
