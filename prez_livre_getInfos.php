<?php

if($_POST['link'] != "")
{
  $shorten_url = $_POST['link'];
  include("shorten.php");
  $recup['link'] = $shorten_return;
  echo json_encode($recup);
}
else
{
  echo "Interdit";
}
