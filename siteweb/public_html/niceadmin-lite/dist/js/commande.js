(function () {
    getPostePrelevement();

})();

// déclaration des variables
var prmId;
var mesArticles = null;
var articles;

// vérifier le code barre et afficher en couleur l'article correspondant
document.querySelector('input#CodeBarre').addEventListener("change", function () {
    var codeBarre = getCodeBarreArtScanne();
    var i = 0;
    for (i = 0; i < 100; i++) {
        if (mesArticles != null && mesArticles[i].code == codeBarre && codeBarre != "") {
            $("." + codeBarre).css("color", "#5cb85c");
        }
    }
});

// permet de récupérer les commandes
function getArticleCommande(prmId) {
    const req = new XMLHttpRequest();
    req.onreadystatechange = function (event) {
        // si le statut de la requête est OK !
        if (this.readyState === XMLHttpRequest.DONE) {
            if (this.status === 200) {
                mesArticles = JSON.parse(this.responseText).articles_commande;
                // on appelle la fonction qui va permettre d’afficher les résultats
                appendArticles(mesArticles);
            } else {
                // sinon afficher le statut de la réponse
                console.log("Status de la réponse: %d (%s)", this.status, this.statusText);
            }
        }
    };
    setInterval("getArticleCommande(prmId)", 60 * 1000); // permet d'exécuter la fonction toutes les 60 secondes
    req.open('GET', 'http://172.16.128.114:82/rest/articlesCommandes/' + prmId, true); 
    req.send(null);
}

//prmJson c'est ce que l'API renvoit
function appendArticles(prmJson) {
    //On récupère le tableau présent dans le DOM avec l'id Commandes
    var tableDOM = document.querySelector('table#Commandes');
    //On efface le contenu précédent du tableau
    tableDOM.children[1].innerHTML = '';
    for (var i = 0; i < prmJson.length; i++) {
        //On construit le contenu HTML d'une ligne d'un tableau
        var html = '<tr data-id="' + prmJson[i].idArtComm + '"><td class="' + prmJson[i].code + '">' + prmJson[i].designation + '</td>';
        html += '<td class="' + prmJson[i].code + '">' + prmJson[i].code + '</td>';
        var qt = prmJson[i].quantite - prmJson[i].quantitePrelevee;
        html += '<td class="' + prmJson[i].code + '">' + qt + '</td>';
        ;
        //On affiche la ligne à la fin du tableau
        tableDOM.children[1].insertAdjacentHTML(
                'beforeend',
                html
                );
    }
}

function getPostePrelevement() {
    
    const req = new XMLHttpRequest();
    req.onreadystatechange = function (event) {
        // si le statut de la requête est OK !
        if (this.readyState === XMLHttpRequest.DONE) {
            if (this.status === 200) {
                // on appelle la fonction qui va permettre d’afficher les résultats
                appendPostePrelevement(JSON.parse(this.responseText).postePrelevement);
            } else {
                // sinon, afficher le statut de la réponse
                console.log("Status de la réponse: %d (%s)", this.status, this.statusText);
            }
        }
    };
    req.open('GET', 'http://172.16.128.114:82/rest/postePrelevement/', true); // exécuter la requête vers notre serveur REST
    req.send(null);
}


// afficher les postes de prélèvements
function appendPostePrelevement(prmJson) {
    // on va venir se situer dans notre navbar
    var sidebar = document.querySelector('ul#sidebarnav');
    for (var i = 0; i < prmJson.length; i++) { // tant que i est supérieur à notre nombre de résultat
        var html = '<li class="sidebar-item" data-operateur=' + prmJson[i].idPPrel + '>'; // on stocke le numéro du poste de prélèvement
        html += '<a class="sidebar-link waves-effect waves-dark sidebar-link" href="#" aria-expanded="false">';
        html += '<i class="mdi mdi-av"><img src="../../assets/images/perso.png" alt=""/></i>';
        html += '<span class="hide-menu">' + prmJson[i].nomRayonnage + '</span></a></li>' ;// on affiche le nom du rayon dans le navbar
        sidebar.insertAdjacentHTML(
                'beforeend',
                html
                );
        sidebar.children[i].addEventListener('click', function () {
            // on récupère la valeur dans date opérateur pour exécuter notre fonction getArticleCommande(prmId)
            prmId = this.getAttribute('data-operateur'); 
            console.log(prmId);
            getArticleCommande(prmId); // Exécuter la fonction qui permet d'afficher les articles en fonction de l'emplacement
            document.querySelector('h4.page-title').innerText = '';  // enlever le message "Accueil"
            setInterval("document.getElementById('CodeBarre').focus()", 1 * 1000);   // faire l'autofocus dans la zone de texte        
        });
    }
}

// récupérer le code barre
function getCodeBarreArtScanne() {
    var input = document.querySelector('input#CodeBarre');
    try {
        return parseInt(input.value);
    } catch (error) {
        alert('Code barre non valide');
    }
}

// mettre à jour la quantité
function updateQuantite(prmCode){
    const req = new XMLHttpRequest();
    var params =  'code=' + prmCode; // on récupére le code barre de l'article
    req.onreadystatechange = function(event) {
        // si le statut de la requête est OK !
        if (this.readyState === XMLHttpRequest.DONE) {
            if (this.status === 200) {
                console.log(this.response); 
            } else {
                // sinon, afficher le statut de la réponse
                console.log("Status de la réponse: %d (%s)", this.status, this.statusText);
            }
        }
    };  
    req.open('POST', 'http://172.16.128.114:82/rest/updateQuantite/' , true); // url vers notre serveur REST
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
    req.send(params);   // on passe en paramètre dans le body de notre requête POST 
}


// appui sur le bouton 'Confimer le prélèvement'
function executer(element) {
  var code = getCodeBarreArtScanne() ; // on prends le code barre
  console.log(code);
  updateQuantite(code); // on passe en paramètre le code barre
  element.value = ''; // la MAJ de la BDD étant faîte, on vide la zone de texte
  setInterval("getArticleCommande(prmId)", 1 * 1000); // on exécute notre fonction pour rafraîchir le tableau
}



