<?php

/*
 +-----------------------------------------------------------------------+
 | plugins/moncompte/localization/<lang>.inc                           |
 |                                                                       |
 | Localization file of the Roundcube Webmail Managesieve plugin         |
 | Copyright (C) 2012-2013, The Roundcube Dev Team                       |
 |                                                                       |
 | Licensed under the GNU General Public License version 3 or            |
 | any later version with exceptions for skins & plugins.                |
 | See the README file for a full license statement.                     |
 |                                                                       |
 +-----------------------------------------------------------------------+

 For translation see https://www.transifex.com/projects/p/roundcube-webmail/resource/plugin-managesieve/
*/

$labels = array();

$labels['types'] = 'Types';
$labels['elements'] = 'Liste des ressources';
$labels['elements calendars'] = 'Liste des agendas';
$labels['elements addressbooks'] = 'Liste des carnets d\'adresses';
$labels['elements taskslists'] = 'Liste des listes de tâches';

// Resources
$labels['resources'] = 'Mes ressources';
$labels['manageresources'] = 'Gérez vos ressources Mélanie2';

$labels['menumanageresources'] = 'Gérer les ressources';
$labels['menumanageresources calendars'] = 'Gérer les agendas';
$labels['menumanageresources addressbooks'] = 'Gérer les carnets d\'adresses';
$labels['menumanageresources taskslists'] = 'Gérer les listes de tâches';

$labels['resourcesettings'] = 'Configurez la ressource';

$labels['resourcesbal'] = 'Boites aux lettres';
$labels['manageresourcesbal'] = 'Gérez vos boites aux lettres Mélanie2 (affichage, partages, restaurations)';
$labels['restore_bal'] = "Récupération des courriels";
$labels['imap_select'] = "Récupérer les courriels effacés depuis %%NB_HEURES%% heure(s) (maximum 168 heures) du dossier ";
$labels['restore_bal_expl'] = "MélanieWeb vous offre la possibilité de <b>récupérer un ou plusieurs messages supprimés ou perdus</b> à la suite d'un incident survenu au cours des 7 derniers jours.
<br>
<br><b>La procédure</b> est la suivante :
<br>
<br>- <b>Sélectionner une boite</b> dont vous êtes gestionnaire <b>dans la liste déroulante</b>
<br>- <b>Indiquez depuis combien d'heures</b> les messages ont été supprimés
<br>- <b>Indiquez le dossier</b> contenant initialement les messages <b>dans la liste déroulante</b>
<br>
<br><b>ATTENTION :</b>
<br>- <b>la récupération comprendra tous les messages supprimés ET déplacés du dossier initial (messages filtrés compris)</b>, ce qui peut représenter un volume de messages assez important suivant le nombre d'heures renseigné.
<br>- <b>chaque message récupéré apparaitra</b> dans l'état dans lequel il se trouvait avant sa suppression ou son déplacement <b>(étiqueté - répondu - transféré - commenté ou redirigé)</b>
<br>- un message commenté sera récupéré en double : message original + message étiqueté \"commentaire\"
<br>- <b>les messages</b> récupérés <b>dans une BalI ont un statut \"lus\"</b>
<br>
<br><b>Cas des boites partagées :</b>
<br>
<br>- <b>les messages</b> récupérés <b>dans les boites partagées ont un statut \"non lus\".</b>
<br>- lorsque la récupération des messages est terminée, cliquer sur n'importe quel module du menu de gauche pour revenir dans l'environnement de la boite individuelle.";
$labels['INBOX'] = "Courrier entrant";
$labels['restore_bal_succes'] = 'Votre demande de récupération a bien été prise en compte et sera exécutée dans les plus brefs délais.';
$labels['restore_bal_error'] = 'Echec de votre demande de récupération.';
$labels['resourcesagendas'] = 'Agendas';
$labels['manageresourcesagendas'] = 'Gérez vos agendas Mélanie2 (affichage, synchronisation, partages, restaurations)';
$labels['restore_cal'] = 'Restauration de l\'agenda';
$labels['restore_cal_expl'] = "Cette fonctionnalité permet de récupérer les données d'agendas à j-1 et j-2 (Hier et Avant hier) sous forme de fichier au format ics, il suffit ensuite d'importer ce fichier avec l'outil 'Importer/Exporter' du module 'Agenda'";
$labels['radio_cal_all_label'] = 'Tous les événements';
$labels['radio_cal_some_label'] = 'Début le ';
$labels['radio_cal_time_label'] = 'A';
$labels['radio_cal_end_label'] = 'Fin le ';
$labels['cal_periode_label'] = 'Période à récupérer';
$labels['cal_bdd_label'] = 'Date de la sauvegarde';
$labels['cal_j-1'] = 'Hier';
$labels['cal_j-2'] = 'Avant hier';
$labels['resourcescontacts'] = 'Contacts';
$labels['manageresourcescontacts'] = 'Gérez vos carnets d\'adresses Mélanie2 (affichage, synchronisation, partages, restaurations)';
$labels['resourcestaches'] = 'Tâches';
$labels['manageresourcestaches'] = 'Gérez vos listes de tâches Mélanie2 (affichage, synchronisation, partages, restaurations)';
$labels['restore_contacts'] = 'Restauration du carnet d\'adresses';
$labels['restore_contacts_expl'] = "Cette fonctionnalité permet de récupérer les données de contacts à j-1 et j-2 (Hier et Avant hier) sous forme de fichier au format vcf, il suffit ensuite d'importer ce fichier avec l'outil 'Importer/Exporter' du module 'Carnet d'adresses";

$labels['wait'] = 'Chargement en cours...';
$labels['modify_error'] = 'Erreur lors de la modification';
$labels['hide_resource_confirm'] = 'Ressource masquée dans le webmail';
$labels['show_resource_confirm'] = 'Ressource affichée dans le webmail';

$labels['synchro_mobile_confirm'] = 'Ressource ajoutée pour la synchronisation mobile';
$labels['no_synchro_mobile_confirm'] = 'Ressource supprimée de la synchronisation mobile';
$labels['set_default_confirm'] = 'Ressource définie par défaut';

$labels['information'] = 'Informations';
$labels['owner'] = 'Propriétaire';
$labels['acls'] = 'Droits';
$labels['none'] = 'Aucun';
$labels['show'] = 'Afficher';
$labels['read_only'] = 'Lecture seule';
$labels['read_write'] = 'Lecture/Écriture';
$labels['write'] = 'Écriture';
$labels['send'] = 'Émission';
$labels['gestionnaire'] = 'Gestionnaire';

$labels['configuration'] = 'Configuration';
$labels['use_as_default'] = 'Utiliser par défaut';
$labels['synchronise_on_mobile'] = 'Synchroniser sur votre mobile';
$labels['show_in_roundcube'] = 'Afficher dans le webmail roundcube';

$labels['options'] = 'Options';

$labels['add_resource'] = 'Créer une nouvelle ressource';
$labels['delete_resource'] = 'Supprimer la ressource sélectionnée';
$labels['add_resource_prompt_agendas'] = 'Saisissez le nom du nouveau calendrier';
$labels['add_resource_prompt_contacts'] = 'Saisissez le nom du nouveau carnet d\'adresses';
$labels['add_resource_prompt_tasks'] = 'Saisissez le nom de la nouvelle liste de tâche';
$labels['delete_resource_confirm_agendas'] = 'Êtes-vous sûr de vouloir supprimer le calendrier sélectionné ?';
$labels['delete_resource_confirm_contacts'] = 'Êtes-vous sûr de vouloir supprimer le carnet d\'adresses sélectionné ?';
$labels['delete_resource_confirm_tasks'] = 'Êtes-vous sûr de vouloir supprimer la liste de tâches sélectionnée ?';
$labels['delete_resource_ok_agendas'] = 'Le calendrier vient d\'être supprimé';
$labels['delete_resource_ok_contacts'] = 'Le carnet d\'adresses vient d\'être supprimé';
$labels['delete_resource_ok_tasks'] = 'La liste de tâches vient d\'être supprimée';
$labels['delete_resource_nok_agendas'] = 'Une erreur s\'est produite, le calendrier n\'a pas pu être supprimé';
$labels['delete_resource_nok_contacts'] = 'Une erreur s\'est produite, le carnet d\'adresses n\'a pas pu être supprimé';
$labels['delete_resource_nok_tasks'] = 'Une erreur s\'est produite, la liste de tâches n\'a pas pu être supprimée';

$labels['add_resource_ok_agendas'] = 'Le calendrier vient d\'être créé';
$labels['add_resource_ok_contacts'] = 'Le carnet d\'adresses vient d\'être créé';
$labels['add_resource_ok_tasks'] = 'La liste de tâches vient d\'être créée';
$labels['add_resource_nok_agendas'] = 'Une erreur s\'est produite, le calendrier n\'a pas pu être créé';
$labels['add_resource_nok_contacts'] = 'Une erreur s\'est produite, le carnet d\'adresses n\'a pas pu être créé';
$labels['add_resource_nok_tasks'] = 'Une erreur s\'est produite, la liste de tâches n\'a pas pu être créée';
$labels['restore_mail'] = 'Récupérer';

// Mon compte
$labels['moncompte'] = 'Mon Compte';
$labels['managemoncompte'] = 'Gérer votre compte Mélanie2';
$labels['button_restore'] = 'Restaurer';
$labels['clic_here'] = 'Cliquer ici';

// Mes statistiques
$labels['statistics'] = 'Mes statistiques';
$labels['managestatistics'] = 'Affichez vos statistiques Mélanie2';
$labels['statisticsmobile'] = 'Mobiles';
$labels['managestatisticsmobile'] = 'Affichez les statistiques des synchronisations mobiles Mélanie2';

$labels['mymailbox'] = 'Ma boite';
$labels['mailboxchangetext'] = 'Cliquez ici pour sélectionner la boite mail à afficher';
$labels['boitespart'] = 'Boites partagées';
$labels['gestionabs'] = 'Gestionnaire d\'absence';
$labels['gestionlists'] = 'Gestionnaire de listes';
$labels['modifmdp'] = 'Modification de mot de passe';
$labels['accesinternet'] = 'Accès Internet et Synchronisations';
$labels['infoperso'] = 'Informations personnelles';
$labels['photo'] = 'Publication photographie';
$labels['absence'] = "Gestionnaire d'absence";

$labels['userlbl'] = 'Compte utilisateur';
$labels['oldpasswordlbl'] = 'Ancien mot de passe';
$labels['newpasswordlbl'] = 'Nouveau mot de passe';
$labels['newpasswordconfirmlbl'] = 'Confirmer nouveau mot de passe';

// Mot de passe
$labels['changepassword_confirm'] = 'Votre mot de passe a bien été changé, vous devez redémarrer votre session Windows pour continuer à accéder aux fichiers réseau et aux applications Cerbérisées';
$labels['changepassword_convention_error'] = 'Le nouveau mot de passe ne respecte pas la convention. ';
$labels['changepassword_modification_error'] = 'Echec de la modification du mot de passe. ';
$labels['changepassword_appel_error'] = 'Echec de la modification du mot de passe. ';
$labels['error_password_confirm'] = 'Mots de passe différents';
$labels['changepassword_infos'] = "Cette fenêtre permet le changement du mot de passe d'authentification réseau et d'accès aux applications du Ministère. Vous devez redémarrer votre session Windows afin de conserver votre accès aux partages réseau et aux applications Cerbérisées";
$labels['changepassword_explic'] = 'Le nouveau mot de passe doit être différent de tous les anciens mots de passe et être différent du dernier (au moins 3 caractères). Il ne doit pas contenir le nom ou le prénom de l’utilisateur.
Il doit avoir une longueur d\'au moins 8 caractères, avec au moins un caractère de chaque type : majuscule, minuscule, chiffre, caractère spécial (!"#$%&\'()*+,-./:<=>?@[^]_{|}~). Les accents (â à â é è ê …) ne sont pas autorisés.
Si vous synchronisez un téléphone avec Mél, pensez à mettre à jour le nouveau mot de passe sur les comptes du téléphone.';

//$labels['changepassword_explic_agri'] = 'Pour les agents dépendant du Ministère de l\'Agriculture et de l\'Alimentation, le changement de mot de passe se fait à partir de l\'annuaire Agricoll au lien suivant : ';
$labels['changepassword_explic_agri_0'] = "Pour les agents dépendant du Ministère de l'Agriculture et de l'Alimentation, le changement de mot de passe s'effectue comme suit :"; 
$labels['changepassword_explic_agri_1'] = "Sur votre navigateur Firefox, cliquez sur Outils | Options | Sécurité | Identifiants enregistrés puis sur Afficher les mots de passe. Supprimez toutes les lignes où votre mot de passe Agricoll est enregistré.";
$labels['changepassword_explic_agri_2'] = 'Si vous possédez un téléphone professionnel synchronisé avec la messagerie, ouvrez l\'application e-mail et supprimez le mot de passe dans le paramétrage de votre compte @agriculture.gouv.fr.';
$labels['changepassword_explic_agri_3'] = 'Changez ensuite votre mot de passe dans l\'annuaire Agricoll à partir du lien suivant : ';
$labels['changepassword_explic_agri_4'] = 'Si vous possédez un téléphone professionnel synchronisé avec la messagerie, ouvrez l\'application e-mail et renseignez le nouveau mot de passe dans le paramétrage de votre compte @agriculture.gouv.fr.';
//$labels['changepassword_lien_agri'] = 'https://annuaire.agricoll.national.agri/agricoll-liniddm/entry/edit/agentpassword/uid=prenom.nom,ou=people,ou=agriculture,o=gouv,c=fr';

//--------------

$labels['users_shares'] = 'Partager à un utilisateur';
$labels['groups_shares'] = 'Partager à un groupe';

//Infos perso
$labels['data_deny'] = "Vous ne pouvez pas modifier les informations de cette boite à lettres. Consultez votre gestionnaire Amédée.";
$labels['info_title'] = "Informations spécifiques à mon compte";
$labels['info_matricule'] = "Matricule :";
$labels['info_inter_auto'] = "Accès internet autorisé :";
$labels['info_admin'] = "Attachement administratif :";
$labels['info_inter_active'] = "Accès internet activé :";
$labels['info_nomade'] = "Accès nomade :";
$labels['info_burotic'] = "Compte bureautique activé :";
$labels['info_texte'] = "En cas d'erreur, d'omission ou modifications souhaitées, contacter votre gestionnaire Amédée";
$labels['info_anais'] = "Informations publiées dans Anaïs";
$labels['info_adresse'] = "Adresse :";
$labels['info_cp'] = "Code postal :";
$labels['info_ville'] = "Ville :";
$labels['info_tel'] = "Numéro de téléphone :";
$labels['info_fax'] = "Numéro de fax :";
$labels['info_mobile'] = "Numéro de mobile :";
$labels['info_bur'] = "Bureau :";
$labels['info_obs'] = "Observation :";
$labels['info_desc'] = "Description :";
$labels['info_hiera'] = "Fonction hiérarchique :";
$labels['info_metier'] = "Fonction métiers";
$labels['info_mission'] = "Missions :";
$labels['info_mess_gestamedee'] = "En cas d'erreur, d'omission ou modifications souhaitées, contacter votre gestionnaire Amédée.";
$labels['info_err_postal'] = "Mauvais format du code postal";
$labels['info_modif_ok'] = "Les données personnelles ont été modifiées.";
$labels['info_modif_nok'] = "Echec de la modification des données personnelles : ";
$labels['info_err_tel'] = "Mauvais format du numéro de ";


//Acces internet
$labels['inter_acces_title'] = "Accès à Mélanie2Web depuis Internet";
$labels['inter_acces_label'] = "Activer l'accès par Internet à Mélanie2 (actif si coché)";
$labels['inter_acces_deny'] = "Vous n'êtes pas autorisé à accéder à Mélanie 2 depuis l'extérieur du ministère.";
$labels['inter_acces_ok'] = "L'accès par internet est activé.";
$labels['inter_acces_nok'] = "L'accès par internet est désactivé.";

//CGU inter
$labels['inter_CGU_legend'] = "Conditions Générales d'Utilisation pour le surf internet";
$labels['inter_CGU_texte'] = "La présente charte – Conditions Générales d'Utilisation (CGU) – a pour objet de définir les modalités de mise à disposition de l'accès internet et les conditions générales d'utilisation de ce service par les utilisateurs du ministère.<br /><br />
Dans le cadre des textes en vigueur, le ministère met en œuvre tous les moyens pour assurer un accès sécurisé et de qualité aux services internet.<br /><br />
A ce titre, des outils de filtrage des connexions sont mis en place au regard des besoins des utilisateurs dans l'exercice de leurs fonctions. Les utilisateurs se voient désormais attribuer un profil fixant les accès aux sites autorisés.<br /><br />
Par ailleurs, en application de la réglementation, la collecte et la conservation des fichiers journaux sont rendues obligatoires dans le but d'être en capacité de fournir les éléments de preuves en cas d'enquêtes administratives ou judiciaires.<br /><br />
Le ministère s'engage à ce que l'exploitation des journaux soit strictement encadrée conformément à la loi du 6 janvier 1978 modifiée afin de respecter la vie privée de ses agents.<br /><br />
A la première connexion sur le service internet, l'utilisateur doit avoir pris connaissance et validé les conditions générales d'utilisation (CGU).<br /><br />
<b>Avertissement : ce dispositif est actuellement en phase de test sur un panel de services. Le présent texte ainsi que la charte d’utilisation d'internet (CGU) seront soumis à la validation des autorités compétentes avant généralisation de la solution à l'ensemble des services du ministère.</b>";
$labels['inter_CGU-DDI_texte'] = "La charte des usages de l’Internet en DDI a pour objet de définir les modalités de mise à disposition de l'accès Internet et les conditions générales d'utilisation de ce service par les utilisateurs des Directions Départementales Interministérielles (DDI) relevant du Premier ministre et placées sous l'autorité des directeurs départementaux.<br /><br />
Dans le cadre des textes en vigueur, les services du Premier ministre mettent en œuvre tous les moyens pour que soit assuré un accès sécurisé et de qualité aux services de l’Internet.<br /><br />
A ce titre, des outils de filtrage des connexions sont mis en place au regard des besoins des utilisateurs dans l'exercice de leurs fonctions. Les utilisateurs se voient désormais attribuer un profil fixant les accès aux sites autorisés.<br /><br />
Par ailleurs, en application de la réglementation, la collecte et la conservation des fichiers journaux sont rendues obligatoires dans le but d’être en capacité de fournir les éléments de preuves en cas d'enquêtes administratives ou judiciaires.<br /><br />
Les services du Premier ministre s’engagent à ce que l'exploitation des journaux soit strictement encadrée conformément à la loi du 6 janvier 1978 modifiée afin de respecter la vie privée des agents.<br /><br />
A la première connexion sur le service Internet, l’utilisateur doit avoir pris connaissance et validé les conditions générales d’utilisation (CGU) dont le détail est accessible en téléchargement ci-dessous.";
$labels['inter_CGU-DDI_texte2'] = "<b>Avertissement : ce dispositif est actuellement en phase de test sur un panel de DDI. Le présent texte ainsi que la charte des usages de l’Internet en DDI seront soumis à la validation des autorités compétentes avant généralisation de la solution à l'ensemble des DDI.</b>";
$labels['inter_CGU_dl'] = "Téléchargement de la charte d'utilisation de l'internet";
$labels['inter_CGU_dl2'] = "Téléchargement de la charte des usages de l'Internet en DDI";
$labels['inter_CGU_accept'] = "En cochant cette case, je reconnais avoir téléchargé la charte internet et pris connaissance des conditions générales d'utilisation et les accepter sans réserve.<br />(Profil: <b>%%getProfil%%</b>)<br />";
$labels['inter_CGU_date'] = "Les conditions générales d'utilisation de la navigation internet ont été validées le %%getTS%%. Profil actuel: <b>%%getProfil%%</b>";
$labels['inter_CGU_validee'] = "Les CGU pour le surf authentifié sont validées.";
$labels['inter_CGU_unvalidee'] = "Les CGU pour le surf authentifié ne sont pas validées, cocher la case pour les accepter";

//CGU mobiles
$labels['inter_CGUmob_texte'] = "Le ministère offre la possibilité à ses agents de pouvoir synchroniser <b>leur messagerie, leur agenda et leurs contacts</b> professionnels depuis leurs équipements mobiles de type ordiphone. Ce service <b>requiert la souscription aux présentes Conditions Générales d’Utilisation (CGU)</b> que nous vous invitons à consulter. Ces CGU traitent notamment des points suivants :<br /><br/>
<ul>
<li>L’offre est <b>ouverte à tout agent</b> en exprimant la demande après accord du RSSI. Des <b>contrôles</b> pourront être effectués pour vérifier la bonne application des CGU.</li>
<li>Le service est accessible <b>7j/7 et 24h/24, sans engagement</b> de la part de l'utilisateur à consulter ou à répondre à un courriel.
</li>
<li>Seuls les <b>terminaux professionnels sont supportés.</b> Les terminaux personnels ont toutefois accès au service, sous réserve de compatibilité.</li>
<li>En cas de vol ou de perte, des <b>mesures de sécurité sont à mettre en œuvre par l'utilisateur</b> : changement du mot de passe de messagerie, réinitialisation du terminal <i>(obligatoire pour les terminaux professionnels, facultatif pour les terminaux personnels)</i>, etc.</li>
<li><b>Deux profils</b> de sécurité – standard et sensible – ont été définis. Ils fixent les <b>règles à respecter</b> pour l’utilisation du service (complexité du code de verrouillage, délai de verrouillage automatique, conditions d’utilisation des services Bluetooth, Wi-Fi ou de cartes de stockage amovibles).
</li>
</ul>
<br /><br />";
$labels['inter_CGUmob_dl'] = "Téléchargement de la charte d'utilisation de mobiles";
$labels['inter_CGUmob_legend'] = "Conditions Générales d'Utilisation de la synchronisation des appareils mobiles";
$labels['inter_CGUmob_profil_change'] = "Vous avez maintenant le profil <b>%%getProfilMob%%</b> , vous devez revalider les conditions générales d'utilisation";
$labels['inter_CGUmob_accept'] = "En cochant cette case, je reconnais avoir téléchargé la charte pour le service de mobilité et pris connaissance des conditions générales d'utilisation et les accepter sans réserve. (<b>Profil: %%getProfilMob%%</b>).";
$labels['inter_CGUmob_ok'] = "Les conditions générales d'utilisation de la synchronisation pour les mobiles ont été validées le %%getTSMob%% pour le profil <b>%%getProfilMobU%%</b>.";
$labels['inter_CGUmob_no'] = "Vous n'êtes pas autorisé &agrave; synchroniser vos périphériques avec la future solution de synchronisation de Mélanie 2. Si vous souhaitez l'utiliser, contactez votre cellule informatique.";
$labels['inter_CGUmob_success'] = "Les CGU ont été acceptées.";
$labels['inter_CGUmob_error'] = "L'opération a échoué : ";
$labels['inter_CGUmob_fake'] = "Echec, cocher la case pour accepter les CGU.";

//Gestionnaire absence
$labels['abs_date'] = 'Dates de début et de fin';
$labels['abs_date_debut'] = 'À partir du : ';
$labels['abs_date_fin'] = "Jusqu'au : ";
$labels['abs_intra_legend'] = "Réponse interne";
$labels['abs_texte_intra'] = "Activer la réponse automatique pour les correspondants internes (déclarés dans l'annuaire).";
$labels['abs_inter_legend'] = "Réponse externe";
$labels['abs_texte_inter'] = "Activer la réponse automatique pour les correspondants externes (interministériel, internet ...)";
$labels['abs_texte_intra_diff'] = "Message spécifique pour les correspondants externes";
$labels['abs_texte_intra_nodiff'] = "Envoyer le même message qu'en interne";
$labels['abs_texte_mot_cle'] = 'En tapant les mots clés "jusqu\'au 01/01/1970" dans le texte, la date sera automatiquement modifiée en changeant la date de fin.';

//Publication photo
$labels['photo_none'] = "Ce type d'entrée ne possède pas de photo.";
$labels['photo_intra'] = "Publication de la photo sur l'Intranet (actif si coché)";
$labels['photo_ader'] = "Publication de la photo sur MAIA (actif si coché)";
$labels['photo_pub_ok'] = "Les informations de publication de la photo ont été modifiées.";
$labels['photo_pub_nok'] = "Echec de la modification des informations de publication de la photo : ";

//Stats ZP
$labels['statszp'] = "Informations de synchronisation";
$labels['zpstats_legend'] = "Liste des appareils mobiles (cliquer sur un identifiant d'appareil pour voir ses détails)";
$labels['zpstats_deviceid'] = "Identifiant appareil";
$labels['zpstats_useragent'] = "Appareil";
$labels['zpstats_devicetype'] = "Nom du client";
$labels['zpstats_deviceuser'] = "Utilisateur";
$labels['zpstats_firstsynctime'] = "Premi&egrave;re synchro";
$labels['zpstats_lastupdatetime'] = "Premi&egrave;re synchro";
$labels['zpstats_zpushversion'] = "Version de ZPush";
$labels['zpstats_deviceos'] = "Version d'OS";
$labels['zpstats_deviceimei'] = "IMEI";

// Gestion des listes
$labels['listes_titre'] = "Listes";
$labels['listes_membres'] = "Membres";
$labels['listes_membres_dyn'] = "Membres (Cette liste est dynamique et n'est pas modifiable. Si vous souhaitez y ajouter des membres, contactez vos administrateurs Amédée)";
$labels['listes_ajouter'] = "Choisir dans l'annuaire";
$labels['listes_retirer'] = "Retirer un membre";
$labels['listes_saisir'] = "Saisir une adresse email";
$labels['listes_purger'] = "Retirer tous les membres";
$labels['listes_exporter'] = "Exporter les membres";
$labels['listes_importer'] = "Importer des membres";
$labels['listes_memb_externe'] = "Entrer l'adresse du nouveau membre";
$labels['listes_addr_nok'] = "L'adresse %%newSMTP%% n'est pas valide !";
$labels['listes_addr_del'] = "Confirmez-vous la suppression de l'adresse\n %%addr_supp%% ?";
$labels['listes_noselect'] = "Aucune liste sélectionnée.";
$labels['listes_member_noselect'] = "Aucune adresse sélectionnée";
$labels['listes_addr_del_all'] = "Confirmez-vous la suppression de toutes les adresses ?";
$labels['listes_upload'] = "Envoyer le fichier .csv";
$labels['listes_addr_error'] = "Adresse(s) invalide(s) : \n";
$labels['listes_import_success'] = "L'import s'est correctement effectué";
$labels['listes_nolist'] = "Vous ne gérez aucune liste serveur (visible par le service) depuis cette boite à lettres. <p>La création de ces listes est assurée par les opérateurs Mélanie.<p>";

// Statistiques mobiles
$labels['mobile'] = 'Statistiques mobiles';
$labels['mobiles_list'] = 'Liste des appareils';
$labels['deviceid'] = 'Identifiant';
$labels['type'] = 'Type';
$labels['z-push'] = 'Z-Push';
$labels['last_sync'] = 'Dernière synchro';
$labels['device_info'] = 'Informations sur l\'appareil';
$labels['imei'] = 'IMEI';
$labels['devicetype'] = 'Appareil';
$labels['devicemodel'] = 'Modèle';
$labels['operator'] = 'Opérateur';
$labels['user-agent'] = 'User-agent';
$labels['version'] = 'Version';
$labels['language'] = 'Langage';
$labels['name'] = 'Nom';
$labels['No type'] = 'Pas de type';
$labels['No version'] = 'Pas de version';
$labels['No IMEI'] = 'Pas d\'IMEI';
$labels['asversion'] = 'Version ActiveSync';
$labels['wipestatus'] = 'Statut RAZ';
$labels['wiperequestedby'] = 'demandée par';
$labels['wiperequestedon'] = 'demandée le';
$labels['wipeactionon'] = 'RAZ effectuée le';
$labels['first_sync'] = 'Première synchro';
$labels['user'] = 'Utilisateur';
$labels['resync'] = 'Resync';
$labels['remove'] = 'Enlever';
$labels['remaining'] = 'Statut';
$labels['device_info'] = 'Informations sur l\'appareil';
$labels['activesync_info'] = 'Informations sur ActiveSync';
$labels['accounts_list'] = 'Liste des comptes configurés sur l\'appareil';
$labels['sync_details'] = 'Détails des synchronisation sur l\'appareil';
$labels['device_actions'] = 'Actions sur l\'appareil mobile';
$labels['mailbox'] = 'Boite liée';
$labels['foldersync'] = 'Dossier synchronisé';

$labels['delete_device'] = 'Supprimer l\'appareil de la base de données (ré-initialisation complète ou appareil ne servant plus)';
$labels['resync_device'] = 'Ré-initialiser la synchronisation (en cas de problème)';
$labels['wipe_device'] = 'Effacer les données de l\'appareil à distance (en cas de perte ou de vol de l\'appareil, nécessite le changement du mot de passe)';
$labels['unwipe_device'] = 'Annuler l\'effacement des données de l\'appareil à distance (si l\'appareil est retrouvé)';

$labels['confirm_Wipe'] = "Souhaitez-vous effacer toutes les données de l'appareil ?";
$labels['confirm_Unwipe'] = "Souhaitez-vous annuler l'effacement de toutes les données de l'appareil";
$labels['confirm_Delete'] = "Souhaitez-vous supprimer l'appareil de la base de données ?";
$labels['confirm_DeleteZP1'] = "Souhaitez-vous supprimer l'appareil de la base de données ?";
$labels['confirm_Resync'] = "Souhaitez-vous ré-initialiser la synchronisation pour cet appareil ?";

$labels['confirm_ResyncUserDevice'] = "Souhaitez-vous ré-initialiser la synchronisation pour ce compte sur cet appareil ?";
$labels['confirm_ResyncFolderId'] = "Souhaitez-vous ré-initialiser la synchronisation pour ce dossier sur cet appareil ?";
$labels['confirm_DeleteUserDevice'] = "Souhaitez-vous enlever ce compte de la base de données pour cet appareil ?";
$labels['confirm_DeleteUserDeviceZP1'] = "Souhaitez-vous enlever ce compte de la base de données pour cet appareil ?";

$labels['success_Wipe'] = "La requête de suppression des données de l'appareil a bien été envoyée";
$labels['success_Unwipe'] = "La requête d'annulation de suppression des données de l'appareil a bien été envoyée";
$labels['success_Delete'] = "L'appareil vient d'être supprimé de la base de données";
$labels['success_DeleteZP1'] = "L'appareil vient d'être supprimé de la base de données";
$labels['success_Resync'] = "La requête de ré-initialisation de la synchronisation de l'appareil a bien été envoyée";

$labels['success_ResyncUserDevice'] = "La requête de ré-initialisation de la synchronisation du compte a bien été envoyée";
$labels['success_ResyncFolderId'] = "La requête de ré-initialisation de la synchronisation du dossier a bien été envoyée";
$labels['success_DeleteUserDevice'] = "Le compte vient d'être supprimé de la base de données";
$labels['success_DeleteUserDeviceZP1'] = "Le compte vient d'être supprimé de la base de données";

$labels['Webservice error'] = "Une erreur s'est produite, il est possible que le serveur de synchronisation mobile soit injoignable. Veuillez réessayer.";
$labels['Missing arguments'] = "Une erreur s'est produite, essayez de vous déconnecter/reconnecter.";







?>