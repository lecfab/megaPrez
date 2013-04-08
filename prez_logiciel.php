<?php header('Content-Type: text/html; charset=iso-8859-1');  ?>

<style>
  .categorie.infos { padding-bottom: 0; }
  .categorie.infos_sup { padding-top: 0; margin-top: -1px; }
  .categorie .champs .champ label { width: 170px; }
  .categorie.upload .champs .champ label { width: 100px; }
</style>
<form onsubmit="valider(); return false" name="formulaire">
  <div class="categorie infos">
    <div class="titre">Informations sur le logiciel</div>
    <div class="champs">
      <div class="champ champ_titre obligatoire">
        <label for="titre">Nom du logiciel</label>
        <input type="text" name="titre" id="titre" size="50" placeholder="Nom du logiciel" />
      </div>
      <div class="champ champ_editeur obligatoire">
        <label for="editeur">Editeur / Développeur</label>
        <input type="text" name="editeur" id="editeur" size="50" placeholder="Nom du développeur ou de l'entreprise" />
      </div>
      <div class="champ champ_plateforme obligatoire">
        <label for="plateforme">Plateformes</label>
        <input type="text" name="plateforme" id="plateforme" size="50" placeholder="Windows 8, Max OS, Linux, Windows 7, Vista, XP ..." />
      </div>
      <div class="champ champ_date">
        <label for="date">Date de sortie</label>
        <input type="text" name="date" id="date" size="50" placeholder="Année de sortie" />
      </div>
      <div class="champ champ_langue">
        <label for="langue">Langues</label>
        <input type="text" name="langue" id="langue" size="50" placeholder="Français, Anglais, Allemand, Espagnol, Italien..." />
      </div>
      <div class="champ champ_img obligatoire">
        <label for="img">Image du logiciel</label>
        <input type="text" name="img" id="img" size="50" placeholder="Lien URL de l'image" />
      </div>
    </div>
  </div>
  <div class="categorie upload">
    <div class="titre">Informations sur l'Upload</div>
    <div class="champs">
      <div class="champ champ_format">
        <label for="format">Format</label>
        <input type="text" name="format" id="format" placeholder="exe, iso, nrg..." />
      </div>
      <div class="champ champ_decoupage">
        <label for="decoupage">Découpage</label>
        <input type="text" name="decoupage" id="decoupage" placeholder="WinRar, 7zip... Laissez vide si non découpé" />
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
        <label for="lien1">Lien</label>
        <input type="text" name="lien1" id="lien1" placeholder="Lien du fichier à télécharger" size="20" />
      </div>
        <button onclick="add_link();return false" id="bouton_add_link">Ajouter un lien</button>
    </div>
  </div>
  <div class="categorie infos_sup">
    <div class="champs">
      <div class="champ champ_resume obligatoire">
        <label for="resume" style="width:70px">Description</label>
        <textarea name="resume" id="resume" placeholder="Entrez ici la description des fonctionnalités du logiciel" style="width:800px;height:100px"></textarea>
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
  
  function add_link()
  { nb_link ++;
    if(nb_link == 2)
    {
      $(".categorie .champs .champ label").css("width", "150px");
      $(".categorie.upload .champs .champ label").css("width", "120px");
      $(".champ_lien label").html("Liens").after("<br /><input type='text' name='lien1nom' value='Lien 1' style='width:130px' />");
    }
    else if(nb_link >= 10)
    {
      $("#bouton_add_link").hide();
    }
    
    $(".champ_lien").append("<br /><input type='text' name='lien"+nb_link+"nom' value='Lien "+nb_link+"' style='width:130px' />  <input type='text' name='lien"+nb_link+"' id='lien"+nb_link+"' placeholder='Autre lien' size='20' />");
  }
  
  function getInfos()
  { var returne;
    if(!f.titre.value) { returne = "Veuillez entrer le nom du logiciel."; }
    else if(!f.editeur.value) { returne = "Veuillez indiquer l'éditeur (entreprise ou développeur)."; }
    else if(!f.plateforme.value) { returne = "Veuillez renseigner les plateformes (ex: Windows XP)."; }
    else if(!f.img.value) { returne = "Veuillez entrer une image pour ce logiciel."; }
    else if(!f.resume.value) { returne = "Veuillez inscrire une description du logiciel."; }
    else
    {
      returne = {
        "titre": f.titre.value,
        "editeur": f.editeur.value,
        "editeur_link": "[url=http://fr.wikipedia.org/wiki/"+f.editeur.value+"]"+f.editeur.value+"[/url]",
        "img": f.img.value,
        "resume": "\n"+ f.resume.value +"\n"
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
    return returne;
	}
  

  function valider()
  { f = document.formulaire;
    if(f["lien1"].value != "" || confirm("Voulez-vous réellement valider cette présentation sans lien de téléchargement ?"))
    {
      var info = getInfos();
      if(typeof info != "object") { alert(info); }
      else 
      {
        var champs = {"editeur_link": "Editeur", "date": "Date de sortie", "plateforme": "Plateformes", "langue": "Langues"};
        var texte = center[0]+"[table][tr]\n[td][img height=450]"+info['img']+"[/img]\n[center][i](fiche réalisée sur [url=http://rannios.free.fr/_?prez]rannios.free.fr[/url])[/i][/center][/td]\n[td]  [/td][td]  [/td][td]  [/td]\n"
        texte += "[td]\n[shadow=silver,left][b][size=24pt][font=times new roman][color=black]"+info['titre']+" [/color] [/font] [/size] [/b] [/shadow]";
        for(var i in champs)
        {
          if(info[i] || (f[i] && f[i].value != ""))
          {
            texte += "\n[b]"+champs[i]+" :[/b] "+ (info[i] || f[i].value);
          }
        }
        texte += "\n\n[b][size=22pt][font=times new roman][color=black]Description [/color] [/font] [/size] [/b] " + info["resume"];
        texte += "\n[b][size=22pt][font=times new roman][color=black]Informations sur l'upload [/color] [/font] [/size] [/b]";
        var champs2 = {"format": "Format", "decoupage": "Découpage", "taille": "Taille"};
        for(var i in champs2)
        {
          if(f[i].value != "")
          {
            texte += "\n[b]"+champs2[i]+" :[/b] "+ (f[i].value);
          }
        }
        texte += "\n\n[b][size=22pt][font=times new roman][color=black]Lien "+f['hebergeur'].value+"[/color][/font][/size][/b]";
        for(var i in info['links'])
        { var j = 1+parseInt(i);
          var lien_reduce = (f['lien'+j].value.length-10 > 40) ? f['lien'+j].value.substr(0,25) +"[...]"+ f['lien'+j].value.substr(f['lien'+j].value.length-10) : f['lien'+j].value.replace(/adf\.ly/i, "v.gd");     
          texte += "\n"+ ((f['lien'+j+'nom'] && f['lien'+j+'nom'].value != "") ? "[b]"+f['lien'+j+'nom'].value+"[/b] : " : "") +"[url="+info["links"][i]+"]"+lien_reduce+"[/url]";
        }
        texte += "\n[/td][/tr][/table]"+center[1];
        $("#reponse input").val(info['titre']);
        $("#reponse textarea").html("").append(texte);
        $("#reponse").slideDown(300);
      }
      
      var info = "";
    }
  }
</script>
