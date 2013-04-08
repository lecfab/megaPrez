<?php header('Content-Type: text/html; charset=iso-8859-1');  ?>

<style>
  .categorie.infos { padding-bottom: 0; }
  .categorie.infos_sup { padding-top: 0; margin-top: -1px; }
  .categorie .champs .champ label { width: 170px; }
  .categorie.upload .champs .champ label { width: 100px; }
</style>
<form onsubmit="valider(); return false" name="formulaire">
  <div class="categorie infos">
    <div class="titre">Informations sur le livre</div>
    <div class="champs">
      <div class="champ champ_titre obligatoire">
        <label for="titre">Titre du livre</label>
        <input type="text" name="titre" id="titre" size="50" placeholder="Nom du livre" />
      </div>
      <div class="champ champ_auteur obligatoire">
        <label for="auteur">Auteur</label>
        <input type="text" name="auteur" id="auteur" size="50" placeholder="Nom de l'auteur" />
      </div>
      <div class="champ champ_date">
        <label for="date">Date</label>
        <input type="text" name="date" id="date" size="50" placeholder="Année d'écriture ou de parution" />
      </div>
      <div class="champ champ_genre">
        <label for="genre">Genre</label>
        <input type="text" name="genre" id="genre" size="50" placeholder="Policier, historique, fantasy, recettes..." />
      </div>
      <div class="champ champ_pages">
        <label for="pages">Nombre de pages</label>
        <input type="text" name="pages" id="pages" size="50" placeholder="Ou durée pour un livre audio" />
      </div>
      <div class="champ champ_img obligatoire">
        <label for="img">Image du livre</label>
        <input type="text" name="img" id="img" size="50" placeholder="Lien URL de l'image" />
      </div>
    </div>
  </div>
  <div class="categorie upload">
    <div class="titre">Informations sur l'Upload</div>
    <div class="champs">
      <div class="champ champ_langue">
        <label for="langue">Langue</label>
        <input type="text" name="langue" id="langue" placeholder="Français, Anglais..." />
      </div>
      <div class="champ champ_format">
        <label for="format">Format</label>
        <input type="text" name="format" id="format" placeholder="pdf, epub, azw, txt, doc..." />
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
        <label for="lien">Lien</label>
        <input type="text" name="lien" id="lien" placeholder="Lien du fichier à télécharger" size="20" />
      </div>
    </div>
  </div>
  <div class="categorie infos_sup">
    <div class="champs">
      <div class="champ champ_resume obligatoire">
        <label for="resume" style="width:70px">Résumé</label>
        <textarea name="resume" id="resume" placeholder="Entrez ici un résumé du livre, qui donne envie de le lire" style="width:800px;height:100px"></textarea>
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
var f, img;
  
  function getInfos()
  { var returne;
    if(!f.titre.value) { returne = "Veuillez entrer le titre du livre."; }
    else if(!f.auteur.value) { returne = "Veuillez inscrire l'auteur du livre."; }
    else if(!f.img.value) { returne = "Veuillez entrer une image pour ce livre."; }
    else if(!f.resume.value) { returne = "Veuillez inscrire un résumé (même bref)."; }
    else
    {
      returne = {
        "titre": f.titre.value,
        "auteur": f.auteur.value,
        "auteur_link": "[url=http://fr.wikipedia.org/wiki/"+f.auteur.value+"]"+f.auteur.value+"[/url]",
        "img": f.img.value,
        "resume": "\n"+ f.resume.value +"\n"
      };
      $.ajax({
          url: "prez_index.php?shorten",
          type: "post",
          data: "link="+encodeURIComponent(f.lien.value), 
          complete: function(data, txt) { returne['link'] = data.responseText; },// */alert(data.responseText); },
          async: false  
      });
    }
    return returne;
	}
  
  
  function valider()
  { f = document.formulaire;
    if(f["lien"].value != "" || confirm("Voulez-vous réellement valider cette présentation sans lien de téléchargement ?"))
    {
      var info = getInfos();
      if(typeof info != "object") { alert(info); }
      else 
      {
        var champs = {"auteur_link": "Auteur", "date": "Date de sortie", "genre": "Genre", "pages": "Nombre de pages"};
        var texte = center[0]+"[table][tr]\n[td][img height=450]"+info['img']+"[/img]\n[center][i](fiche réalisée sur [url=http://rannios.free.fr/_?prez]rannios.free.fr[/url])[/i][/center][/td]\n[td]  [/td][td]  [/td][td]  [/td]\n"
        texte += "[td]\n[shadow=silver,left][b][size=24pt][font=times new roman][color=black]"+info['titre']+" [/color] [/font] [/size] [/b] [/shadow]";
        for(var i in champs)
        {
          if(info[i] || (f[i] && f[i].value != ""))
          {
            texte += "\n[b]"+champs[i]+" :[/b] "+ (info[i] || f[i].value);
          }
        }
        texte += "\n\n[b][size=22pt][font=times new roman][color=black]Résumé [/color] [/font] [/size] [/b] " + info["resume"];
        texte += "\n[b][size=22pt][font=times new roman][color=black]Informations sur l'upload [/color] [/font] [/size] [/b]";
        var champs2 = {"langue": "Langue", "format": "Format", "taille": "Taille"};
        for(var i in champs2)
        {
          if(f[i].value != "")
          {
            texte += "\n[b]"+champs2[i]+" :[/b] "+ (f[i].value);
          }
        }
        var lien_reduce = (f['lien'].value.length-10 > 40) ? f['lien'].value.substr(0,25) +"[...]"+ f['lien'].value.substr(f['lien'].value.length-10) : f['lien'].value.value.replace(/adf\.ly/i, "v.gd");        
        texte += "\n\n[b][size=22pt][font=times new roman][color=black]Lien "+f['hebergeur'].value+"[/color][/font][/size][/b]\n[url="+info['link']+"]"+lien_reduce+"[/url]";
        texte += "\n[/td][/tr][/table]"+center[1];
        $("#reponse input").val(info['auteur']+" - "+info['titre']);
        $("#reponse textarea").html("").append(texte);
        $("#reponse").slideDown(300);
      }
      
      var info = "";
    }
  }
</script>
