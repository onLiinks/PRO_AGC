#! /bin/sh
# Script de migration de prosper

# Date du jour
  jour=`date +%d-%m-%Y`

#--------- Fin de d�claration des variables ---------#

# On effectue une sauvegarde de gescom
  mysqldump -u root gescom > "gescom_$jour.sql"
  
# On execute les scripts sql g�n�r�s avec xlsmagique � partir des csv de PROSPER

  adr_IP=`ifconfig eth0 | grep -Eo 'adr:([0-9]{1,3}\.){3}[0-9]{1,3}' | cut -d: -f2`

  chown www-data candidat_prosper1.sql

  php supp_lien_hyper.php >> "candidat_prosper1.1.sql"

  sed -e 's/\\/\\\\/g' "candidat_prosper1.1.sql" > "candidat_prosper1.2.sql"
  mysql -u root gescom < "candidat_prosper1.2.sql"
  mysql -u root gescom < "candidat_prosper2.sql"
  mysql -u root gescom < "candidat_prosper3.sql"
  
  wget --tries=1 http://"$adr_IP"/gescom/migration/index.php?a=migration_prosper