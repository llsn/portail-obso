/**
 * Lister les d�partements d'une r�gion avec un objet
 * XMLHTTPRequest.
 */
/* Cr�ation de la variable globale qui contiendra l'objet XHR */
var requete = null;
/**
 * Fonction priv�e qui va cr�er un objet XHR.
 * Cette fonction initialisera la valeur dans la variable globale d�finie
 * ci-dessus.
 */
function creerRequete()
{
    try
    {
        /* On tente de cr�er un objet XmlHTTPRequest */
        requete = new XMLHttpRequest();
    }
    catch (microsoft)
    {
        /* Microsoft utilisant une autre technique, on essays de cr�er un objet ActiveX */
        try
        {
            requete = new ActiveXObject('Msxml2.XMLHTTP');
        }
        catch(autremicrosoft)
        {
            /* La premi�re m�thode a �chou�, on en teste une seconde */
            try
            {
                requete = new ActiveXObject('Microsoft.XMLHTTP');
            }
            catch(echec)
            {
                /* � ce stade, aucune m�thode ne fonctionne... mettez donc votre navigateur � jour ;) */
                requete = null;
            }
        }
    }
    if(requete == null)
    {
        alert('Impossible de cr�er l\'objet requ�te,\nVotre navigateur ne semble pas supporter les object XMLHttpRequest.');
    }
}
/**
 * Fonction priv�e qui va mettre � jour l'affichage de la page.
 */
function actualiserDatabase()
{
    var listeDept = requete.responseText;
    var blocListe = document.getElementById('blocDatabase');
    blocListe.innerHTML = listeDB;
}

/**
 * Fonction publique appel�e par la page affich�e.
 * Cette fonction va initialiser la cr�ation de l'objet XHR puis appeler
 * le code serveur afin de r�cup�rer les donn�es � modifier dans la page.
 */
function getDatabase(db)
{
    /* Si il n'y a pas d'identifiant de r�gion, on fait dispara�tre la seconde liste au cas o� elle serait affich�e */
    if(db == 'vide')
    {
        document.getElementById('blocDatabase').innerHTML = '';
    }
    else
    {
        /* � cet endroit pr�cis, on peut faire appara�tre un message d'attente */
        var blocListe = document.getElementById('blocDatabase');
		var params="database="+db;
        blocListe.innerHTML = "Traitement en cours, veuillez patienter...";
        if(xhr.readyState == 4 && xhr.status == 200)
		{
			var leselect = xhr.responseText;
            document.getElementById('blocDatabase').innerHTML = leselect;
		}
        /* On cr�e l'objet XHR */
        creerRequete();
        /* D�finition du fichier de traitement */
        var url = 'set_decommission.php';
        /* Envoi de la requ�te � la page de traitement */
        requete.open('POST', url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		requete.send(params);
        /* On surveille le changement d'�tat de la requ�te qui va passer successivement de 1 � 4 */
        requete.onreadystatechange = function()
        {
            /* Lorsque l'�tat est � 4 */
            if(requete.readyState == 4)
            {
                /* Si on a un statut � 200 */
                if(requete.status == 200)
                {
                    /* Mise � jour de l'affichage, on appelle la fonction apropri�e */
                    actualiserDatabase();
                }
				else
				{
                    alert('Une erreur est survenue !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.statusText);
				}
            }
        };
        requete.send(null);
    }
}// JavaScript Document
