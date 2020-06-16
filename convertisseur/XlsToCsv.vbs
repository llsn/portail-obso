if wscript.arguments.length > 0 then

	path_file = lcase( wscript.arguments(0) )
	
	 nomfichier = mid(path_file,1,len(path_file) - 3) & "csv"


	'load excel
	set ex = Wscript.createobject("excel.application")
	'ouvre le doc excel
	ex.Workbooks.Open path_file
	ex.visible = false
	'supprime la première ligne


	'va mettre toutes les entetes et compter le nombre de colonnes	
	
	nbcol = 1
	
	while not ex.ActiveSheet.cells(1,nbcol).value = "" 
		
		
		fichier = fichier & ex.ActiveSheet.cells(1,nbcol).value & ";" 
		nbcol = nbcol +1
	wend
	nbcol = nbcol - 1
	fichier = mid(fichier,1, len(fichier)-1)
	fichier = fichier & vbcrlf
	
	'va enregistrer tous les champ de chaque ligne dans la variable fichier
	nbligne = 2
	
	while not  ex.ActiveSheet.cells(nbligne,1).value = "" 
		for i = 1 to nbcol
			fichier = fichier & ex.ActiveSheet.cells(nbligne,i).value
			if i < nbcol then fichier = fichier & ";"
		next
			nbligne = nbligne +1
		fichier = fichier & vbcrlf
	wend 
	
	msgbox fichier
	'ferme excel
	ex.Application.DisplayAlerts = False 
	'on va ouvrir un fichier texte et ecrire dedans la variable fichier
	Set fso = CreateObject("Scripting.FileSystemObject")
	'Creation du fichier texte et ecriture dans ce fichier
	Set fichierTexte = fso.CreateTextFile(nomfichier , True)
	fichierTexte.WriteLine(fichier)
	fichierTexte.close
	

	ex.quit
	msgbox ".csv créé"
	







else
	msgbox "Déposer le fichier à convertir sur l'icone du programme"

       continue = false

end if

 
