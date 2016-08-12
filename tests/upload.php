<?php

function upload($index, $destination)
{
  return move_uploaded_file($_FILES[$index]['tmp_name'], $destination.$_FILES[$index]['name']);
}

if(!empty($_POST['submit']))
{
  var_dump($_POST);
  var_dump($_FILES);
  if(upload('affiche', 'imgTest/'))
  {
    echo "L'upload s'est bien déroulé !";
  }
}


?>
