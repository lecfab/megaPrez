<?php header('Content-Type: text/html; charset=iso-8859-1');  ?>

<form onsubmit="valider(); return false" name="formulaire">
  <div class="categorie infos">
    <div class="titre">Informations sur l'album</div>
    <div class="champs">
      <div class="champ champ_deezer obligatoire">
        <label for="deezer">Lien de la fiche <a href="http://www.deezer.com/" target="_blank">Deezer</a></label>
        <input type="url" name="deezer" id="deezer" placeholder="Nécessaire !" />
      </div>
      <div class="champ champ_no_deezer">
        <label for="no_deezer" style="color:red">L'album n'est pas sur Deezer ?</label><input type="checkbox" id="no_deezer" onchange="no_infos()" />
      </div>
      <div class="champ champ_wikipedia">
        <label for="wikipedia">Lien Wikipédia (optionnel)</label>
        <input type="url" name="wikipedia" id="wikipedia" placeholder="Facultatif">
      </div>
      <div class="champ champ_genre">
        <label for="genre">Genre</label>
        <input type="text" name="genre" id="genre" />
      </div>
    </div>
  </div>
  <div class="categorie upload">
    <div class="titre">Informations sur l'Upload</div>
    <div class="champs">
      <div class="champ champ_format">
        <label for="format">Format</label>
        <input type="text" name="format" id="format" placeholder="mp3, wav, ogg, wma..." />
      </div>
      <div class="champ champ_taille">
        <label for="taille">Taille totale</label>
        <input type="text" name="taille" id="taille" placeholder="En ko, Mo, Go..." />
      </div>
      <div class="champ champ_hebergeur">
        <label for="hebergeur">Hébergeur</label>
        <input type="text" name="hebergeur" id="hebergeur" value="MEGA" />
      </div>
      <div class="champ champ_lien obligatoire">
        <label for="lien">Lien</label>
        <input type="text" name="lien" id="lien" placeholder="Lien du fichier à télécharger" size="20" />
      </div>
    </div>
  </div>
  <div class="categorie infos_sup" style="display:none">
    <div class="titre">Informations supplémentaires</div>
    <div class="champs">
      <div class="champ champ_artiste obligatoire">
        <label for="artiste">Artiste</label>
        <input type="text" name="artiste" id="artiste" size="50" placeholder="Groupe ou chanteur de l'album" />
      </div>
      <div class="champ champ_album obligatoire">
        <label for="album">Titre de l'album</label>
        <input type="text" name="album" id="album" size="50" placeholder="Nom de l'album" />
      </div>
      <div class="champ champ_annee">
        <label for="annee">Date de sortie</label>
        <input type="text" name="annee" id="annee" size="50" placeholder="Année de parution" />
      </div>
      <div class="champ champ_image obligatoire">
        <label for="image">Pochette de l'album</label>
        <input type="text" name="image" id="image" size="50" placeholder="Lien URL de l'image" />
      </div>
      <div class="champ champ_titres obligatoire">
        <label for="titres">Chansons</label>
        <textarea name="titres" id="titres" placeholder="Entrez tous les titres des chansons, revenez à la ligne entre chacun" style="width:600px;height:200px"></textarea>
      </div>
    </div>
  </div><br />
  <input type="submit" value="Générer" />
</form>
<div id="reponse" style="display:none">
  Titre : <input type="text" name="messagetitre" placeholder="Titre du message dans le forum" size="50" /><button onclick="$('#reponse textarea').select();">Sélectionner tout</button>
  <textarea></textarea>
</div>

<script>
  function urldecode(val){val=val.replace(/\+/g, '%20');var str=val.split("%");var cval=str[0];for (var i=1;i<str.length;i++){cval+=String.fromCharCode(parseInt(str[i].substring(0,2),16))+str[i].substring(2);}return cval;}
  var f, img;
  
  function no_infos()
  { f = document.formulaire;
    if(f.no_deezer.checked)
    {
      $(".champ_deezer").slideUp(200);
      $(".infos_sup").slideDown(200);  
    }
    else
    {
      $(".champ_deezer").slideDown(200);
      $(".infos_sup").slideUp(200);  
    }
  }
  
  function getDeezer()
  { var returne = true;
    if(f.no_deezer.checked)
    {
      if(!f.artiste.value) { returne = "Veuillez indiquer l'artiste de l'album."; }
      else if(!f.album.value) { returne = "Veuillez entrer le titre de l'album."; }
      else if(!f.image.value) { returne = "Veuillez trouver une image de l'album."; }
      else if(!f.titres.value) { returne = "Veuillez indiquer les chansons présentes dans cet album."; }
      else
      {
        returne = {
          "artiste": f.artiste.value,
          "artiste_link": "[url=http://fr.wikipedia.org/wiki/"+f.artiste.value+"]"+f.artiste.value+"[/url]",
          "album": f.album.value,
          "image": f.image.value,
          "titres": f.titres.value.split("\n")
        };
        $.ajax({
            url: "prez_index.php?shorten",
            type: "post",
            data: "link="+encodeURIComponent(f.lien.value), 
            complete: function(data, txt) { returne['link'] = data.responseText; },// */alert(data.responseText); },
            async: false  
        });
      }
    }
    else if(!f["deezer"].value) { returne = "Veuillez entre l'adresse de la fiche Deezer.com !"; }
    else
    {
      $.ajax({
          url: "prez_index.php?getInfos=musique",
          type: "post",
          data: "url="+encodeURIComponent(f.deezer.value)+"&link="+encodeURIComponent(f.lien.value), 
          complete: function(data, txt) { returne = JSON.parse(data.responseText); },// */alert(data.responseText); },
          async: false  
      });
    }
    return returne;
	}
  
  function valider()
  { f = document.formulaire;
    var deez = getDeezer();
    if(typeof deez != "object") { alert(deez); }
    else if(f["lien"].value != "" || confirm("Voulez-vous réellement valider cette présentation sans lien de téléchargement ?"))
    {
      var wiki = (f.wikipedia.value != "")? "  [sup]  [url="+f.wikipedia.value+"]Wikipédia[/url]  [/sup]  " : "";
      var deezer = (!f.no_deezer.checked)?   "[sup] [url="+f.deezer.value+"] Deezer[/url][/sup]" : "";
      var champs = {"artiste_link": "Artiste", "genre": "Genre", "annee": "Date de sortie", "note": "Note moyenne"};
      var texte = center[0]+"[table][tr]\n[td][img height=450]"+deez['image']+"[/img]\n[center][i](fiche réalisée sur [url=http://rannios.free.fr/_?prez]rannios.free.fr[/url])[/i][/center][/td]\n[td]  [/td][td]  [/td][td]  [/td]\n"
      texte += "[td]\n[shadow=silver,left][b][size=24pt][font=times new roman][color=black]"+deez['album']+" [/color] [/font] [/size] [/b] [/shadow]"+wiki;
      for(var i in champs)
      { //alert(i +" "+ (deez[i]))
        if(deez[i] || (f[i] && f[i].value != ""))
        {
          texte += "\n[b]"+champs[i]+" :[/b] "+ (deez[i] || f[i].value);
        }
      }
      texte += "\n\n[b][size=22pt][font=times new roman][color=black]Titres [/color] [/font] [/size] [/b]"+ deezer +"\n[table]\n";
      for(var i in deez['titres']) { texte += "[tr][td]"+(parseInt(i)+1)+".  [/td][td]  "+urldecode(deez['titres'][i])+"  [/td][td]  "+(deez['durees'] ? deez['durees'][i]:"")+"  [/td][/tr]\n"; }
      texte += "[/table]\n[/td][/tr]\n[tr][td]\n[b][size=22pt][font=times new roman][color=black]Informations sur l'upload[/color][/font][/size][/b]";
      var champs2 = {"format": "Format", "taille": "Taille"};
      for(var i in champs2)
      {
        if(f[i].value != "")
        {
          texte += "\n[b]"+champs2[i]+" :[/b] "+ (f[i].value);
        }
      }
      var lien_reduce = (f['lien'].value.length-10 > 40) ? f['lien'].value.substr(0,25) +"[...]"+ f['lien'].value.substr(f['lien'].value.length-10) : f['lien'].value.replace(/adf\.ly/i, "v.gd");
      texte += "\n[/td][td]  [/td][td]  [/td][td]  [/td][td]\n[b][size=22pt][font=times new roman][color=black]Lien "+f['hebergeur'].value+"[/color][/font][/size][/b]\n[url="+deez['link']+"]"+lien_reduce+"[/url]";
      texte += "\n[/td][/tr][/table]"+ center[1];
      $("#reponse input").val(deez['artiste']+" - "+deez['album']);
      $("#reponse textarea").html("").append(texte);
      $("#reponse").slideDown(300);
    }
    
    var deez = "";
  }
</script>
