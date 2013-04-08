<?php

if(preg_match("#http://adf\.ly#", $shorten_url)) // c'est un adf.ly
{ $shorten_recup = "";
  function shorten_recup($value) { global $shorten_recup; $shorten_recup = "$value"; }
  $shorten_content = file_get_contents($shorten_url);
  preg_replace("#var zzz = '(.+)';#eU","shorten_recup('$1')", $shorten_content);
  $shorten_url = $shorten_recup;  
}

if(preg_match("#^https?://mega\.co\.nz/\#!.{8}!.{43}$#", $shorten_url))
{ // c'est un mega.co.nz
  $shorten_curl = curl_init("http://curl.mega-search.ws/API/write/post?". urlencode($shorten_url));
  curl_setopt($shorten_curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($shorten_curl, CURLOPT_POST, true);
  curl_setopt($shorten_curl, CURLOPT_POSTFIELDS, array("url" => urldecode($shorten_url), "type"=>"json"));
  $shorten_json = curl_exec($shorten_curl);
  curl_close($shorten_curl);
  
  $shorten_decode = json_decode($shorten_json, true);
  if($shorten_decode['success'] == "true")
  {
    $shorten_return = $shorten_decode['data']['url'];
  }
  else
  { // curl.mega-search.ws n'a pas marché
    $shorten_curl = curl_init("http://v.gd/create.php?format=simple&url=". urlencode($shorten_url));
    curl_setopt($shorten_curl, CURLOPT_RETURNTRANSFER, true);
    $shorten_return = curl_exec($shorten_curl);
    curl_close($shorten_curl);
    if(preg_match("#^Error#", $shorten_return)) { $shorten_return = $shorten_url; }
  }
}
elseif(!preg_match("#goo\.gl#", $shorten_url))
{ // ce n'est pas une adresse Mega
  $shorten_curl = curl_init("http://v.gd/create.php?format=simple&url=". urlencode($shorten_url));
  curl_setopt($shorten_curl, CURLOPT_RETURNTRANSFER, true);
  $shorten_return = curl_exec($shorten_curl);
  curl_close($shorten_curl);
  if(preg_match("#^Error#", $shorten_return)) { $shorten_return = $shorten_url; }
}
else
{ // c'est un goo.gl
  $shorten_return = $shorten_url;  unset($shorten_url);
}

unset($shorten_url);
