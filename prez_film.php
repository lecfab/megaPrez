<?php header('Content-Type: text/html; charset=iso-8859-1');  ?>
<p style="display:none">Si votre film n'est pas sur allociné, c'est compliqué ... mais je suis en train de programmer une solution comme pour la Musique ;-)</p>
<p style="font-size:1.3em;color:red;      display:none     ">NE PAS UTILISER, JE SUIS EN TRAIN DE L'AMÉLIORER, J'EN AI POUR QUELQUES MINUTES !</p>
<form onsubmit="valider(); return false" name="formulaire">
  <div class="categorie infos">
    <div class="titre">Informations sur le film</div>
    <div class="champs">
      <div class="champ champ_allocine obligatoire">
        <label for="allocine">Lien de la fiche <a href="http://www.allocine.fr" target="_blank">AlloCiné</a></label>
        <input type="url" name="allocine" id="allocine" placeholder="Nécessaire !">
      </div>
      <div class="champ champ_no_allocine">
        <label for="no_allocine" style="color:red">Le film n'est pas sur Allociné ?</label><input type="checkbox" id="no_allocine" onchange="no_infos()" />
      </div>
      <div class="champ champ_wikipedia">
        <label for="wikipedia">Lien Wikipédia (optionnel)</label>
        <input type="url" name="wikipedia" id="wikipedia" placeholder="Facultatif">
      </div>
      <div class="champ champ_video">
        <label for="video">Bande-annonce (optionnel)</label>
        <input type="url" name="video" id="video" placeholder="URL da la bande-annonce (ex: youtube)">
      </div>
    </div>
  </div>
  <div class="categorie upload">
    <div class="titre">Informations sur l'Upload</div>
    <div class="champs">
      <div class="champ champ_langue">
        <label for="langue">Langue</label>
        <input type="text" name="langue" id="langue" placeholder="VO, VF, VOSTFR..." />
      </div>
            <div class="champ champ_format">
        <label for="format">Format</label>
        <input type="text" name="format" id="format" placeholder="avi, mp4, mkv..." />
      </div>
      <div class="champ champ_qualite">
        <label for="qualite">Qualité</label>
        <input type="text" name="qualite" id="qualite" placeholder="HD, DVDRIP, DiVX..." />
      </div>
      <div class="champ champ_taille">
        <label for="taille">Taille</label>
        <input type="text" name="taille" id="taille" placeholder="En ko, Mo, Go..." />
      </div>
      <div class="champ champ_hebergeur">
        <label for="hebergeur">Hébergeur</label>
        <input type="text" name="hebergeur" id="hebergeur" value="MEGA" />
      </div>
      <div class="champ champ_lien obligatoire">
        <label for="lien1"><button type="button" onclick="add_link();return false" id="bouton_add_link">Ajouter un lien</button> <span>Lien</span></label>
        <input type="text" name="lien1" id="lien1" placeholder="Lien du fichier à télécharger" size="20" />
      </div>
    </div>
  </div>
  <div class="categorie infos_sup" style="display:none">
    <div class="titre">Informations supplémentaires</div>
    <div class="champs">
      <div class="champ champ_titre obligatoire">
        <label for="titre">Titre du film</label>
        <input type="text" name="titre" id="titre" size="50" placeholder="Nom du film" />
      </div>
      <div class="champ champ_realisateur">
        <label for="realisateur">Réalisateur</label>
        <input type="text" name="realisateur" id="realisateur" size="50" placeholder="Nom du réalisateur" />
      </div>
      <div class="champ champ_duree">
        <label for="duree">Durée</label>
        <input type="text" name="duree" id="duree" size="50" placeholder="Durée du film" />
      </div>
      <div class="champ champ_genre">
        <label for="genre">Genre</label>
        <input type="text" name="genre" id="genre" size="50" placeholder="Comédie, drame, action, thriller, horreur, adulte..." />
      </div>
      <div class="champ champ_acteur">
        <label for="acteur">Acteurs importants</label>
        <input type="text" name="acteur" id="acteur" size="50" placeholder="Noms des principaux acteurs" />
      </div>
      <div class="champ champ_date">
        <label for="date">Date de sortie</label>
        <input type="text" name="date" id="date" size="50" placeholder="Année de parution" />
      </div>
      <div class="champ champ_img obligatoire">
        <label for="img">Image du film</label>
        <input type="text" name="img" id="img" size="50" placeholder="Lien URL de l'image" />
      </div>
      <div class="champ champ_synopsis obligatoire">
        <label for="synopsis">Synopsis</label>
        <textarea name="synopsis" id="synopsis" placeholder="Entrez ici un résumé du film, qui donne envie de le voir" style="width:600px;height:100px"></textarea>
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
var f, img, nb_link = 1;
  function escapeHtml(str) {return $("<span />", { html: str}).text();};
  
  function add_link()
  { nb_link ++;
    if(nb_link == 2)
    {
      $(".champ_lien input").css("width", "245px");
      $(".champ_lien label span").html("Liens").parent().after("<br /><input type='text' name='lien1nom' value='Episode 1' style='width:130px' />");
    }
    else if(nb_link >= 30)
    {
      $("#bouton_add_link").hide();
    }
    
    $(".champ_lien").append("<br /><input type='text' name='lien"+nb_link+"nom' value='Episode "+nb_link+"' style='width:130px' />  <input type='text' name='lien"+nb_link+"' id='lien"+nb_link+"' placeholder='Autre lien' style='width:245px' />");
  }
  
  function no_infos()
  { f = document.formulaire;
    if(f.no_allocine.checked)
    {
      $(".champ_allocine").slideUp(200);
      $(".infos_sup").slideDown(200);  
    }
    else
    {
      $(".champ_allocine").slideDown(200);
      $(".infos_sup").slideUp(200);  
    }
  }

  
  function getAllo()
  { var returne = true;
    if(f.no_allocine.checked)
    {
      if(!f.titre.value) { returne = "Veuillez entrer un titre de film."; }
      else if(!f.img.value) { returne = "Veuillez entrer une image pour ce film."; }
      else if(!f.synopsis.value) { returne = "Veuillez inscrire un synopsis (même bref)."; }
      else
      {
        returne = {
          "titre": f.titre.value,
          "video": f.video.value,
          "img": f.img.value,
          "synopsis": "\n"+ f.synopsis.value +"\n"
        };
        var liens = encodeURIComponent(f.lien1.value);
        for(i=2; f["lien"+i] && f["lien"+i].value != ""; i++) { liens += "|%stop%|"+encodeURIComponent(f["lien"+i].value); }
        $.ajax({
            url: "prez_index.php?shorten",
            type: "post",
            data: "link="+liens, 
            complete: function(data, txt) { returne['links'] = data.responseText.split("|%stop%|"); },// */alert(returne['links'][0]); },
            async: false  
        });
      }
    }
    else if(!f.allocine.value) { returne = "Veuillez entre l'adresse de la fiche Allocine.fr !"; }
    else
    {
      var liens = encodeURIComponent(f.lien1.value);
      for(i=2; f["lien"+i] && f["lien"+i].value != ""; i++) { liens += "|%stop%|"+encodeURIComponent(f["lien"+i].value); }
      $.ajax({
          url: "prez_index.php?getInfos=film",
          type: "post",
          data: "url="+encodeURIComponent(f.allocine.value)+"&link="+liens, 
          complete: function(data, txt) { returne = JSON.parse(data.responseText); }, // */alert(data.responseText); },
          async: false  
      });
    }
    return returne;
	}
  
  
  function valider()
  { f = document.formulaire;
    var allo = getAllo();
    if(typeof allo != "object") { alert(allo); }
    else if(f["lien1"].value != "" || confirm("Voulez-vous réellement valider cette présentation sans lien de téléchargement ?"))
    {
      var wiki = (f.wikipedia.value != "")? "  [sup]  [url="+f.wikipedia.value+"]Wikipédia[/url]  [/sup]  " : "";
      var allocine = (f.allocine.value != "")? "  [sup]  [url="+f.allocine.value+"] AlloCiné[/url]  [/sup]  " : "";
      var video = (f.video.value != "" || allo['video'] != "") ? "  [sup]  [url="+(f.video.value || allo['video'])+"]Bande annonce[/url]  [/sup]  " : "";
      var champs = {"realisateur": "Réalisateur", "duree": "Durée", "genre": "Genre", "acteur": "Acteurs", "date": "Date de sortie", "note": "Note des spectateurs", "note2": "Note de la presse"};
      var texte = center[0]+"[table][tr]\n[td][img height=450]"+allo['img']+"[/img]\n[center][i](fiche réalisée sur [url=http://rannios.free.fr/_?prez]rannios.free.fr[/url])[/i][/center][/td]\n[td]  [/td][td]  [/td][td]  [/td]\n"
      texte += "[td]\n[shadow=silver,left][b][size=24pt][font=times new roman][color=black]"+allo['titre']+" [/color] [/font] [/size] [/b] [/shadow]"+video+wiki;
      for(var i in champs)
      {
        if(allo[i] || (f[i] && f[i].value != ""))
        {
          texte += "\n[b]"+champs[i]+" :[/b] "+ (allo[i] || f[i].value);
        }
      }
      texte += "\n\n[b][size=22pt][font=times new roman][color=black]Synopsis [/color] [/font] [/size] [/b] "+ allocine + allo["synopsis"];
      texte += "\n[b][size=22pt][font=times new roman][color=black]Informations sur l'upload [/color] [/font] [/size] [/b]";
      var champs2 = {"langue": "Langue", "format": "Format", "qualite": "Qualité", "taille": "Taille"};
      for(var i in champs2)
      {
        if(f[i].value != "")
        {
          texte += "\n[b]"+champs2[i]+" :[/b] "+ (f[i].value);
        }
      }

      texte += "\n\n[b][size=22pt][font=times new roman][color=black]Lien "+f['hebergeur'].value+"[/color][/font][/size][/b]";
      for(var i in allo['links'])
      { var j = 1+parseInt(i);
        var lien_reduce = (f['lien'+j].value.length-10 > 40) ? f['lien'+j].value.substr(0,25) +"[...]"+ f['lien'+j].value.substr(f['lien'+j].value.length-10) : f['lien'+j].value.replace(/adf\.ly/i, "v.gd");     
        texte += "\n"+ ((f['lien'+j+'nom'] && f['lien'+j+'nom'].value != "") ? "[b]"+f['lien'+j+'nom'].value+"[/b] : " : "") +"[url="+allo["links"][i]+"]"+allo["links"][i]+"[/url]";
      }
      texte += "\n[/td][/tr][/table]"+center[1];
      $("#reponse input").val(escapeHtml(allo['titre']/*+" "+(f['qualite'].value != "" ? "["+f['qualite'].value+"]":"")+(f['langue'].value != "" ? "["+f['langue'].value+"]":"")*/));
      $("#reponse textarea").html("").append(texte);
      $("#reponse").slideDown(300);
    }
    
    var allo = "";
  }
</script>
