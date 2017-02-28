#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: article
#------------------------------------------------------------

CREATE TABLE prefixTablearticle(
        idArticle       Int NOT NULL AUTO_INCREMENT ,
        titleArticle    Varchar (255) NOT NULL ,
        contentArticle  Text ,
        datetimeArticle Datetime NOT NULL ,
        idCategory      Int NOT NULL ,
        idUser          Int ,
        PRIMARY KEY (idArticle )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: category
#------------------------------------------------------------

CREATE TABLE prefixTablecategory(
        idCategory   Int NOT NULL AUTO_INCREMENT ,
        lblCategory  Varchar (25) NOT NULL ,
        idCategoryParent Int ,
        PRIMARY KEY (idCategory )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: comment
#------------------------------------------------------------

CREATE TABLE prefixTablecomment(
        idComment       Int NOT NULL AUTO_INCREMENT ,
        contentComment  Text NOT NULL ,
        datetimeComment Datetime NOT NULL ,
        idArticle       Int NOT NULL ,
        idUser          Int ,
        PRIMARY KEY (idComment )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: optionsite
#------------------------------------------------------------

CREATE TABLE prefixTableoptionsite(
        idOptionSite    Int NOT NULL AUTO_INCREMENT ,
        nameOptionSite  Varchar (255) NOT NULL ,
        valueOptionSite Varchar (255) ,
        PRIMARY KEY (idOptionSite )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: privatemessage
#------------------------------------------------------------

CREATE TABLE prefixTableprivatemessage(
        idPM        Int NOT NULL AUTO_INCREMENT ,
        titlePM     Varchar (255) NOT NULL ,
        contentPM   Text NOT NULL ,
        datetimePM  Datetime NOT NULL ,
        isRead      Bool NOT NULL ,
        idSender      Int ,
        idReceiver Int ,
        PRIMARY KEY (idPM )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: user
#------------------------------------------------------------

CREATE TABLE prefixTableuser(
        idUser            Int NOT NULL AUTO_INCREMENT ,
        nicknameUser      Varchar (25) NOT NULL ,
        emailUser         Varchar (255) NOT NULL ,
        passwordUser      Varchar (64) NOT NULL ,
        validUser         Varchar (255) ,
        resetPasswordUser Varchar (255) ,
        deleteUser        Varchar (255) ,
        redacArticle      Bool NOT NULL ,
        editOwnArticle    Bool NOT NULL ,
        deleteOwnArticle  Bool NOT NULL ,
        editComment       Bool NOT NULL ,
        deleteComment     Bool NOT NULL ,
        isAdministrator   Bool NOT NULL ,
        PRIMARY KEY (idUser )
)ENGINE=InnoDB;

ALTER TABLE prefixTablearticle ADD CONSTRAINT FK_article_idCategory FOREIGN KEY (idCategory) REFERENCES prefixTablecategory(idCategory) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE prefixTablearticle ADD CONSTRAINT FK_article_idUser FOREIGN KEY (idUser) REFERENCES prefixTableuser(idUser) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE prefixTablecategory ADD CONSTRAINT FK_category_idCategory_1 FOREIGN KEY (idCategoryParent) REFERENCES prefixTablecategory(idCategory) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE prefixTablecomment ADD CONSTRAINT FK_comment_idArticle FOREIGN KEY (idArticle) REFERENCES prefixTablearticle(idArticle) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE prefixTablecomment ADD CONSTRAINT FK_comment_idUser FOREIGN KEY (idUser) REFERENCES prefixTableuser(idUser) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE prefixTableprivatemessage ADD CONSTRAINT FK_privatemessage_idUser FOREIGN KEY (idSender) REFERENCES prefixTableuser(idUser) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE prefixTableprivatemessage ADD CONSTRAINT FK_privatemessage_idUser_user FOREIGN KEY (idReceiver) REFERENCES prefixTableuser(idUser) ON DELETE CASCADE ON UPDATE CASCADE;


INSERT INTO prefixTableoptionsite VALUES(1, "siteName", "siteNameValue");
INSERT INTO prefixTableoptionsite VALUES(2, "siteDescription", "siteDescriptionValue");
INSERT INTO prefixTableoptionsite VALUES(3, "adminEmail", "adminEmailValue");
INSERT INTO prefixTableoptionsite VALUES(4, "articlesPerPage", "10");
INSERT INTO prefixTableoptionsite VALUES(5, "commentsPerPage", "10");
INSERT INTO prefixTableoptionsite VALUES(6, "theme", "default");
INSERT INTO prefixTableoptionsite VALUES(7, "urlSite", "urlSiteValue");

INSERT INTO prefixTableuser VALUES(1, "nicknameUserValue", "adminEmailValue", "passwordUserValue", NULL, NULL, NULL, 1, 1, 1, 1, 1, 1);

INSERT INTO prefixTablecategory VALUES(1, "Non classés", NULL);

INSERT INTO prefixTablearticle VALUES(1, "Mon premier article", '<div class="entry-content">
<h2><strong>Bonjour,</strong></h2>
<p>Bienvenue dans le CMS cr&eacute;&eacute; par <a href="https://github.com/AntoninLefevre/">Antonin Lefevre</a>.</p>
<p>Ceci est votre premier article.</p>
</div>', NOW(), 1, 1);
