drop database ICS;
create database ICS;

	create table users (
			id_user integer NOT NULL,
			nom char(50) NOT NULL,
			prenom char(50) NOT NULL,
			mdp char(40) NOT NULL,
			pseudo_cas char(30) NOT NULL,
			mail char(100) NOT NULL,
			url_pdp char(200) NOT NULL,
			promo integer NOT NULL,

			#constraint CK_promo CHECK (promo >= 1 AND promo <= 8),
			constraint PK_id_user PRIMARY KEY(id_user)
		);

	create table matieres (
			id_matiere integer NOT NULL,
			nom_matiere char(50) NOT NULL,
			diminutif char(8) NOT NULL,
			promo integer NOT NULL,


			#constraint CK_promo CHECK (promo >= 1 AND promo <= 8),
			constraint PK_id_matiere PRIMARY KEY(id_matiere)
		);

	create table chapitres (
			id_chapitre integer NOT NULL,
			id_matiere integer NOT NULL,
			rang integer,

			constraint PK_id_chapitre PRIMARY KEY(id_chapitre),
			constraint FK_id_matiere FOREIGN KEY(id_matiere) REFERENCES matieres(id_matiere)
		);

	create table documents(
			id_doc integer NOT NULL,
			id_auteur integer NOT NULL,
			id_matiere integer NOT NULL,
			id_chapitre integer NOT NULL,
			type integer NOT NULL, # 1:cours, 2:exercice, 3:correction, 4:annale
			nom char(50) NOT NULL,
			url char(200) NOT NULL,
			date_creation date NOT NULL,

			constraint PK_id_doc PRIMARY KEY(id_doc),
			constraint FK_id_auteur FOREIGN KEY(id_auteur) REFERENCES users(id_user),
			constraint FK_id_matiere FOREIGN KEY(id_matiere) REFERENCES matieres(id_matiere),
			constraint FK_id_chapitre FOREIGN KEY(id_chapitre) REFERENCES chapitres(id_chapitre)
		);

	create table commentaires(
			id_com integer NOT NULL,
			id_auteur integer NOT NULL,
			id_doc integer NOT NULL,
			contenu text NOT NULL,
			date_creation date,

			constraint PK_id_com PRIMARY KEY(id_com),
			constraint FK_id_auteur FOREIGN KEY(id_auteur) REFERENCES users(id_user),
			constraint FK_id_doc FOREIGN KEY(id_doc) REFERENCES documents(id_doc)
		);
