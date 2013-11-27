fos.Router.setData({"base_url":"","routes":{"majordesk_app_inscription_paiement":{"tokens":[["text","\/inscription-paiement"]],"defaults":[],"requirements":[],"hosttokens":[]},"majordesk_app_annuler_abonnement":{"tokens":[["variable","-","\\d{1,6}","id_elevematiere"],["text","\/parents\/annuler-abonnement"]],"defaults":[],"requirements":{"id_elevematiere":"\\d{1,6}"},"hosttokens":[]},"majordesk_app_populate_matieres":{"tokens":[["variable","-","\\d{1,5}","id_eleve"],["text","\/parents\/populate-matieres"]],"defaults":[],"requirements":{"id_eleve":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_display_eleve_stats":{"tokens":[["variable","-","1|2","period"],["variable","-","\\d{1,5}","id_eleve"],["text","\/parents\/display-eleve-stats"]],"defaults":[],"requirements":{"id_eleve":"\\d{1,5}","period":"1|2"},"hosttokens":[]},"majordesk_app_parties":{"tokens":[["variable","-","\\d{1,3}","id_chapitre"],["text","\/eleve\/liste-des-exercices"]],"defaults":[],"requirements":{"id_chapitre":"\\d{1,3}"},"hosttokens":[]},"majordesk_app_exercice":{"tokens":[["variable","-","\\d{1,8}","id_exercice"],["text","\/eleve\/exercice-en-cours"]],"defaults":[],"requirements":{"id_exercice":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_exercice_next_in_queue":{"tokens":[["variable","-","\\d{1,3}","id_chapitre"],["variable","-","\\d{1,2}","id_matiere"],["text","\/eleve\/exercice-next-in-queue"]],"defaults":{"id_chapitre":0},"requirements":{"id_matiere":"\\d{1,2}","id_chapitre":"\\d{1,3}"},"hosttokens":[]},"majordesk_app_chapitre_selection_queue":{"tokens":[["variable","-","\\d{1,2}","id_matiere"],["text","\/eleve\/selection-chapitre"]],"defaults":[],"requirements":{"id_matiere":"\\d{1,2}"},"hosttokens":[]},"majordesk_app_exercice_aleatoire_partie":{"tokens":[["variable","-","\\d{1,4}","id_partie"],["text","\/eleve\/exercice-en-cours-partie"]],"defaults":[],"requirements":{"id_partie":"\\d{1,4}"},"hosttokens":[]},"majordesk_app_calendrier_des_cours":{"tokens":[["variable","-","\\d{1}","etape"],["text","\/eleve\/calendrier-des-cours"]],"defaults":{"etape":0},"requirements":{"etape":"\\d{1}"},"hosttokens":[]},"majordesk_app_calendrier_annuler_event":{"tokens":[["variable","-","\\d{1,6}","id_event"],["text","\/eleve\/calendrier-annuler-event"]],"defaults":[],"requirements":{"id_event":"\\d{1,6}"},"hosttokens":[]},"majordesk_app_calendrier_prof_event":{"tokens":[["variable","-","0|1|2|3","reservation"],["variable","-","\\d{1,6}","id_event"],["text","\/eleve\/calendrier-prof-event"]],"defaults":[],"requirements":{"id_event":"\\d{1,6}","reservation":"0|1|2|3"},"hosttokens":[]},"majordesk_app_verification_devoirs":{"tokens":[["text","\/eleve\/verification-devoirs"]],"defaults":[],"requirements":[],"hosttokens":[]},"majordesk_app_donner_devoirs":{"tokens":[["text","\/eleve\/donner-devoirs"]],"defaults":[],"requirements":[],"hosttokens":[]},"majordesk_app_preview_exercice":{"tokens":[["variable","-","\\d{1,8}","id_exercice"],["text","\/eleve\/preview-exercice"]],"defaults":[],"requirements":{"id_exercice":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_recherche_parties_chapitre":{"tokens":[["variable","-","\\d{1,8}","id_chapitre"],["text","\/eleve\/recherche-parties-chapitre"]],"defaults":[],"requirements":{"id_chapitre":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_recherche_exercices_partie":{"tokens":[["variable","-","\\d{1,8}","id_partie"],["text","\/eleve\/recherche-exercices-partie"]],"defaults":[],"requirements":{"id_partie":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_update_chapitre_only":{"tokens":[["variable","-","0|1","chapitre_only"],["text","\/eleve\/update-chapitre-only"]],"defaults":[],"requirements":{"chapitre_only":"0|1"},"hosttokens":[]},"majordesk_app_update_partie_only":{"tokens":[["variable","-","0|1","partie_only"],["text","\/eleve\/update-partie-only"]],"defaults":[],"requirements":{"partie_only":"0|1"},"hosttokens":[]},"majordesk_app_display_chapitre_stats":{"tokens":[["variable","-","\\d{1,3}","id_chapitre"],["variable","-","\\d{1,5}","id_eleve"],["text","\/eleve\/display-chapitre-stats"]],"defaults":[],"requirements":{"id_eleve":"\\d{1,5}","id_chapitre":"\\d{1,3}"},"hosttokens":[]},"majordesk_app_display_partie_stats":{"tokens":[["variable","-","\\d{1,4}","id_partie"],["variable","-","\\d{1,5}","id_eleve"],["text","\/eleve\/display-partie-stats"]],"defaults":[],"requirements":{"id_eleve":"\\d{1,5}","id_partie":"\\d{1,4}"},"hosttokens":[]},"majordesk_app_validate_reponses":{"tokens":[["variable","-","0|1","isLastCouche"],["variable","-","\\d{1,6}","id_question"],["text","\/eleve\/validate-reponses"]],"defaults":{"isLastCouche":0},"requirements":{"id_question":"\\d{1,6}","isLastCouche":"0|1"},"hosttokens":[]},"majordesk_app_update_temps_exercice":{"tokens":[["variable","-","\\d{1,5}","id_exercice"],["text","\/eleve\/update-temps-exercice"]],"defaults":[],"requirements":{"id_exercice":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_check_professeur_password":{"tokens":[["variable","-","\\d{1,2}","id_matiere"],["variable","-","[^\/\\-]++","password"],["variable","-","[^\/\\-]++","id_professeur"],["text","\/eleve\/check-professeur-password"]],"defaults":[],"requirements":{"id_prof":"\\d{1,5}","id_matiere":"\\d{1,2}"},"hosttokens":[]},"majordesk_app_exercice_en_favoris":{"tokens":[["variable","-","\\d{1,5}","id_exercice"],["text","\/eleve\/exercice-en-favoris"]],"defaults":[],"requirements":{"id_exercice":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_envoi_feedback":{"tokens":[["variable","-","\\d{1,5}","id_exercice"],["text","\/eleve\/envoi-feedback"]],"defaults":[],"requirements":{"id_exercice":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_editor_add_superbrique":{"tokens":[["variable","-","[^\/]++","type"],["variable","-","\\d{1,3}","numero"],["variable","-","\\d{1,6}","id_exercice"],["text","\/admin\/add-superbrique"]],"defaults":[],"requirements":{"id_exercice":"\\d{1,6}","numero":"\\d{1,3}"},"hosttokens":[]},"majordesk_app_editor_remove_superbrique":{"tokens":[["variable","-","\\d{1,8}","id_superbrique"],["text","\/admin\/remove-superbrique"]],"defaults":[],"requirements":{"id_superbrique":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_add_brique_to_superbrique":{"tokens":[["variable","-","\\d{1,3}","couche"],["variable","-","[^\/\\-]++","type"],["variable","-","\\d{1,3}","numero"],["variable","-","\\d{1,8}","id_superbrique"],["text","\/admin\/add-brique-to-superbrique"]],"defaults":[],"requirements":{"id_superbrique":"\\d{1,8}","numero":"\\d{1,3}","couche":"\\d{1,3}"},"hosttokens":[]},"majordesk_app_editor_remove_brique_from_superbrique":{"tokens":[["variable","-","\\d{1,8}","id_brique"],["text","\/admin\/remove-brique-from-superbrique"]],"defaults":[],"requirements":{"id_brique":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_add_brique_to_complement":{"tokens":[["variable","-","\\d{1,3}","couche"],["variable","-","[^\/\\-]++","type"],["variable","-","\\d{1,3}","numero"],["variable","-","\\d{1,8}","id_complement"],["text","\/admin\/add-brique-to-complement"]],"defaults":[],"requirements":{"id_complement":"\\d{1,8}","numero":"\\d{1,3}","couche":"\\d{1,3}"},"hosttokens":[]},"majordesk_app_editor_remove_brique_from_complement":{"tokens":[["variable","-","\\d{1,8}","id_brique"],["text","\/admin\/remove-brique-from-complement"]],"defaults":[],"requirements":{"id_brique":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_update_brique":{"tokens":[["variable","-","\\d{1,8}","id_brique"],["text","\/admin\/update-brique"]],"defaults":[],"requirements":{"id_brique":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_add_reponse_to_superbrique":{"tokens":[["variable","-","[^\/]++","clavier"],["variable","-","\\d{1,8}","numero"],["variable","-","\\d{1,8}","id_brique"],["variable","-","\\d{1,8}","id_superbrique"],["text","\/admin\/add-reponse-to-superbrique"]],"defaults":[],"requirements":{"id_superbrique":"\\d{1,8}","id_brique":"\\d{1,8}","numero":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_add_reponse_to_mapping":{"tokens":[["variable","-","\\d{1,8}","id_reponse"],["text","\/admin\/add-reponse-to-mapping"]],"defaults":[],"requirements":{"id_reponse":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_merge_reponses_to_mapping":{"tokens":[["variable","-","\\d{1,8}","id_superbrique"],["text","\/admin\/merge-reponses-to-mapping"]],"defaults":[],"requirements":{"id_superbrique":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_unmerge_reponse_from_mapping":{"tokens":[["variable","-","\\d{1,8}","id_reponse"],["variable","-","\\d{1,8}","id_superbrique"],["text","\/admin\/unmerge-reponse-from-mapping"]],"defaults":[],"requirements":{"id_superbrique":"\\d{1,8}","id_reponse":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_update_mapping_type":{"tokens":[["variable","-","\\d{1,8}","id_reponse"],["text","\/admin\/update-mapping-type"]],"defaults":[],"requirements":{"id_reponse":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_reset_mapping_type":{"tokens":[["variable","-","\\d{1,8}","id_reponse"],["text","\/admin\/reset-mapping-type"]],"defaults":[],"requirements":{"id_reponse":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_remove_brique_and_reponses":{"tokens":[["variable","-","\\d{1,8}","id_brique"],["text","\/admin\/remove-brique-and-reponses"]],"defaults":[],"requirements":{"id_brique":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_remove_reponse":{"tokens":[["variable","-","\\d{1,8}","id_reponse"],["text","\/admin\/remove-reponse"]],"defaults":[],"requirements":{"id_reponse":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_update_reponse_contenu":{"tokens":[["variable","-","\\d{1,8}","id_reponse"],["text","\/admin\/update-reponse-contenu"]],"defaults":[],"requirements":{"id_reponse":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_update_reponse_type":{"tokens":[["variable","-","\\d{1,8}","id_reponse"],["text","\/admin\/update-reponse-type"]],"defaults":[],"requirements":{"id_reponse":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_editor_update_reponse_clavier":{"tokens":[["text","\/admin\/update-reponse-clavier"]],"defaults":[],"requirements":[],"hosttokens":[]},"majordesk_app_update_feedback":{"tokens":[["variable","-","\\d{1,6}","id_feedback"],["text","\/admin\/update-feedback"]],"defaults":[],"requirements":{"id_feedback":"\\d{1,6}"},"hosttokens":[]},"majordesk_app_toggle_flag_eleve":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/toggle-flag-eleve"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_toggle_actif_eleve":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/toggle-actif-eleve"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_reinitialiser_exercice":{"tokens":[["variable","-","\\d{1,5}","id_exercice"],["text","\/admin\/reinitialiser-exercice"]],"defaults":[],"requirements":{"id_exercice":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_toggle_flag_famille":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/toggle-flag-famille"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_toggle_actif_famille":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/toggle-actif-famille"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_toggle_actif_parent":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/toggle-actif-parent"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_toggle_flag_professeur":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/toggle-flag-professeur"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_toggle_actif_professeur":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/toggle-actif-professeur"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_ajouter_exercice":{"tokens":[["text","\/admin\/ajouter-exercice"]],"defaults":[],"requirements":[],"hosttokens":[]},"majordesk_app_modifier_exercice":{"tokens":[["variable","-","\\d{1,8}","id"],["text","\/admin\/modifier-exercice"]],"defaults":[],"requirements":{"id":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_dupliquer_exercice":{"tokens":[["variable","-","\\d{1,8}","id"],["text","\/admin\/dupliquer-exercice"]],"defaults":[],"requirements":{"id":"\\d{1,8}"},"hosttokens":[]},"majordesk_app_afficher_exercice":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/afficher-exercice"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_delete_exercice":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/delete-exercice"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_delete_famille":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/delete-famille"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_upload_exercice":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/upload-exercice"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_download_exercice":{"tokens":[["variable","-","\\d{1,5}","id"],["text","\/admin\/download-exercice"]],"defaults":[],"requirements":{"id":"\\d{1,5}"},"hosttokens":[]},"majordesk_app_get_all_tags":{"tokens":[["text","\/admin\/get-all-tags"]],"defaults":[],"requirements":[],"hosttokens":[]},"majordesk_app_assign_tag":{"tokens":[["variable","-","\\d{1,7}","id_mod_reponse"],["variable","-","[^\/\\-]++","nom_tag"],["text","\/admin\/assign-tag"]],"defaults":[],"requirements":{"id_mod_reponse":"\\d{1,7}"},"hosttokens":[]},"majordesk_app_unassign_tag":{"tokens":[["variable","-","\\d{1,7}","id_mod_reponse"],["variable","-","[^\/\\-]++","nom_tag"],["text","\/admin\/unassign-tag"]],"defaults":[],"requirements":{"id_mod_reponse":"\\d{1,7}"},"hosttokens":[]},"majordesk_app_populate_chapitres":{"tokens":[["variable","-","\\d{1,2}","id_programme"],["variable","-","\\d{1,2}","id_matiere"],["text","\/admin\/populate-chapitres"]],"defaults":[],"requirements":{"id_matiere":"\\d{1,2}","id_programme":"\\d{1,2}"},"hosttokens":[]},"majordesk_app_populate_parties":{"tokens":[["variable","-","\\d{1,3}","id_chapitre"],["text","\/admin\/populate-chapitres"]],"defaults":[],"requirements":{"id_chapitre":"\\d{1,3}"},"hosttokens":[]},"majordesk_app_filter_exercices":{"tokens":[["variable","-","\\d{1,4}","id_partie"],["text","\/admin\/filter-exercices"]],"defaults":[],"requirements":{"id_partie":"\\d{1,4}"},"hosttokens":[]}},"prefix":"","host":"localhost","scheme":"http"});