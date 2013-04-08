<?php

if($_POST['url'] != "")
{
  $recup = array();
  $content = file_get_contents($_POST['url']);
  $content = preg_replace("##", "", $content);
  
  function recup($type, $value) { global $recup; $recup[$type] = "$value"; }
  function recup2($type, $value) { global $recup; $recup[$type][sizeof($recup[$type])] = " $value"; }
  preg_replace("#<meta property=\"og:image\" content=\"(.+)\" />#eU","recup('img', '$1')", $content);                                        // image du film  (img)
  preg_replace("#itemprop=\"duration\" content=\".+\">(.+)</span>#eU","recup('duree', '$1')", $content);                                     // durée
  preg_replace("#itemprop=\"(director|creator)\"(.+) itemprop=\"name\">(.+)</span>#esU","recup2('realisateur', '$3')", $content);            // réalisateur
  preg_replace("#<meta property=\"og:title\" content=\"(.+)\" />#eU","recup('titre', '$1')", $content);                                      // titre du film
  preg_replace("#itemprop=\"genre\">(.+)</span>#eU","recup2('genre', '$1')", $content);                                                      // genres 
  preg_replace("#itemprop=[\"']actors?[\"'](.+) itemprop=\"name\">[^a-zA-Z -]*([^\n]+)[^a-zA-Z -]*</span#esU","recup2('acteur', '$2')", $content);// acteurs
  preg_replace("#<strong><span itemprop=\"datePublished\" content=\"[0-9-]{10}\">(.+)</span></strong>#eU","recup('date', '$1')", $content);  // date de sortie
  preg_replace("#href=\"(.+)\" itemprop=\"trailer\">#eU","recup('video', '$1')", $content);                                                  // lien bande-annonce
  preg_replace("#itemprop=\"description\">(.+)</p>#esU","recup2('synopsis', preg_replace('#<.+>#U', '', '$1'))", $content);                  // synopsis
  preg_replace("#itemprop=\"ratingValue\" content=\"(.+)\"#esU","recup2('note', '$1')", $content);                                           // note
  preg_replace("#<span class=\"note\"><span.*>(.+)</span>#esU","recup2('note2', preg_replace('#,#', '.', '$1'))", $content);                 // note presse
  
  //$recup['realisateur'] = implode(", ", $recup['realisateur']);
  $recup['video'] = ($recup['video'] ? "http://www.allocine.fr$recup[video]":"");                                                            // lien absolu vers la bande annonce
  $recup['synopsis'] = $recup['synopsis'][0];                                                                                                // sélection du "premier" synopsis
  $recup['note'] = ($recup['note'] != "") ? $recup['note'][0] ."/5  [img]http://u.terredefeu.fr/progress?". (round($recup['note'][0], 1)*20) ."[/img]" : "";     // sélection de la "première" note
  $recup['note2'] = ($recup['note2'] != "") ? $recup['note2'][0] ."/5  [img]http://u.terredefeu.fr/progress?". (round($recup['note2'][0], 1)*20) ."[/img]" : ""; // sélection de la "première" note presse
  $recup['img'] = preg_replace("#r_[0-9]{2,4}_[0-9]{2,4}/#", "",  $recup['img']);                                                            // sélection de la grande image
  $links = explode("|%stop%|", $_POST['link']); $array_links = array();
  foreach($links AS $n => $link)
  {
    $shorten_url = $link;
    include("shorten.php");
    $array_links[$n] = $shorten_return;
  }
  $recup['links'] = $array_links;                                                                                                            // lien shortené
  echo json_encode($recup);
}
else
{
  echo "Interdit";
}
