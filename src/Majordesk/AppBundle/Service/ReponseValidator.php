<?php

namespace Majordesk\AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Majordesk\AppBundle\Entity\Reponse;
use Majordesk\AppBundle\Entity\Rep;
use Majordesk\AppBundle\Entity\MicroRep;
use Majordesk\AppBundle\Service\MathValidator;
 
class ReponseValidator
{
	protected $session;
	protected $em;
	protected $math_validator;
	protected $statut_resolu;
	protected $statut_non_resolu;
	protected $statut_non_commence;
	protected $statut_en_ligne;
	protected $macro_type_tableau;
	protected $macro_type_tableau_analyse;
	protected $macro_type_normal;
	protected $macro_type_indice;
	protected $macro_type_correction;
	protected $element_type_maths;
	protected $element_type_br;
	protected $element_type_jsgbox;
	protected $element_type_jsggraph;
	protected $element_type_text;
	protected $element_type_tr;
	protected $element_type_td;

	public function __construct(Session $session, EntityManager $em, MathValidator $math_validator, $statut_resolu, $statut_non_resolu, $statut_non_commence, $statut_en_ligne, $macro_type_tableau, $macro_type_tableau_analyse, $macro_type_normal, $macro_type_indice, $macro_type_correction, $element_type_maths, $element_type_br, $element_type_jsgbox, $element_type_jsggraph, $element_type_text, $element_type_tr, $element_type_td)
	{
		$this->session = $session;
		$this->em = $em;
		$this->math_validator = $math_validator;
		$this->statut_resolu = $statut_resolu;
		$this->statut_non_resolu = $statut_non_resolu;
		$this->statut_non_commence = $statut_non_commence;
		$this->statut_en_ligne = $statut_en_ligne;
		$this->macro_type_tableau = $macro_type_tableau;
		$this->macro_type_tableau_analyse = $macro_type_tableau_analyse;
		$this->macro_type_normal = $macro_type_normal;
		$this->macro_type_indice = $macro_type_indice;
		$this->macro_type_correction = $macro_type_correction;
		$this->element_type_maths = $element_type_maths;
		$this->element_type_br = $element_type_br;
		$this->element_type_jsgbox = $element_type_jsgbox;
		$this->element_type_jsggraph = $element_type_jsggraph;
		$this->element_type_text = $element_type_text;
		$this->element_type_tr = $element_type_tr;
		$this->element_type_td = $element_type_td;
	}
	
	/**
     * @info Récupère le commentaire approprié à la réponse de l'élève
     */
	public function getAppropriateCommentary($question, $valeur_question, $commentaire_special)
	{
		$mod_question = $question->getModQuestion();
		$is_new = $mod_question->getModExercice()->getIsNew();
		$erreur = $commentaire_special != '' ? $commentaire_special : 'Ta réponse est fausse. ';
		$commentaire = '';
		
		if ($is_new == 0) {
			if ($valeur_question === true) {
				$comment = '';
				// $mod_macro = $mod_question->getModMacroByType($this->macro_type_correction);
				$mod_macros = $mod_question->getModMacros();
				$mod_elements = $this->em->getRepository('MajordeskAppBundle:ModElement')
										 ->getOrderedModElementsByModExerciceId($mod_question->getModExercice()->getId());
				
				foreach($mod_macros as $mod_macro) {
					if ($mod_macro->getFond() == 5 || $mod_macro->getType() == $this->macro_type_correction) {
						if ($mod_macro->getType() == $this->macro_type_normal || $mod_macro->getType() == $this->macro_type_correction) {
							foreach($mod_elements as $mod_element) {
								if ($mod_element->getModMacro()->getId() == $mod_macro->getId() ) {
									if ($mod_element->getType() == $this->element_type_maths) {
										$comment .= ' <script type="math/mml"><math>'.$mod_element->getContenu().'</math></script> ';
									}
									else if ($mod_element->getType() == $this->element_type_br) {
										$comment .= '<br>';
									}
									else if ($mod_element->getType() == $this->element_type_jsgbox || $mod_element->getType() == $this->element_type_jsggraph) {
										$comment .= '<div id="'.$mod_element->getClavier().'" ></div><script type="text/javascript">$("#'.$mod_element->getClavier().'").height(300*$("#'.$mod_element->getClavier().'").width()/570); '.$mod_element->getContenu().'</script>';
									}
									else {
										$comment .= $mod_element->getContenu();
									}
								}
							}
						}
						else if ($mod_macro->getType() == $this->macro_type_tableau) {
							$comment .= '<table class="table table-bordered borders-correction" style="border-color: #a0c491;"><thead style="border-color: #a0c491;">';
								$tr_count = 0;
								$td_opened = 0;
							foreach($mod_elements as $mod_element) {
								if ($mod_element->getModMacro()->getId() == $mod_macro->getId() ) {
									if ($mod_element->getType() == $this->element_type_maths) {
										$comment .= ' <script type="math/mml"><math>'.$mod_element->getContenu().'</math></script> ';
									}
									else if ($mod_element->getType() == $this->element_type_tr) {
										$tr_count++;
										if ($tr_count != 1) {
											$comment .= '</td><tr>';
											if ($tr_count == 2) {
												$comment .= '</thead><tbody style="border-color: #a0c491;">';
											}
										}
										$comment .= '<tr style="border-color: #a0c491;">';
									}
									else if ($mod_element->getType() == $this->element_type_td) {
										if ($td_opened == 1) {
											$comment .= '</td>';
											$td_opened = 0;
										}
										$comment .= '<td style="border-color: #a0c491;">';
										$td_opened = 1;
									}
									else if ($mod_element->getType() == $this->element_type_br) {
										$comment .= '<br>';
									}
									else {
										$comment .= $mod_element->getContenu();
									}
								}
							}
							$comment .= '</tr></tbody></table>';
						}
						else if ($mod_macro->getType() == $this->macro_type_tableau_analyse) {
							$comment .= '<div class="table-responsive"><table class="table"><thead>';
								// $td_opened = 1;
								$tr_count = 0;
								$loop = 0;
							foreach($mod_elements as $mod_element) {						
								if ($mod_element->getModMacro()->getId() == $mod_macro->getId() ) {
									$loop++;
									if ($mod_element->getType() == $this->element_type_maths) {
										$comment .= ' <script type="math/mml"><math>'.$mod_element->getContenu().'</math></script> ';
									}
									else if ($mod_element->getType() == $this->element_type_tr) {
										$tr_opened = 1;
										$tr_count++;
										if ($loop != 1) {
											$comment .= '</tr>';
											if ($tr_count == 2) {
												$comment .= '</thead><tbody>';
											}
										}
										$comment .= '<tr>';
									}
									else if ($mod_element->getType() == $this->element_type_td) {
										if ($loop != 2) {
											$comment .= '</div></td>';
										}
										$style ='border-color:#a0c491;';
										if ($tr_opened == 1) {
											$tr_opened = 0;
											if ($tr_count != 2 && $tr_count != 7 && $tr_count != 12) {
												$style .='border-top:none;';
											}
											$style .='border-right: 1px solid #a0c491;';
										}
										else {
											if ($tr_count != 2 && $tr_count != 7 && $tr_count != 12) {
												$style .='border-top:none;';
											}
										}
										$comment .= '<td class="col-lg-1" style="'.$style.'">';
										
										$comment .= '<div class="text-center">';
									}
									else if ($mod_element->getType() == $this->element_type_br) {
										$comment .= '<br>';
									}
									else {
										$comment .= $mod_element->getContenu();
									}
								}
							}
							$comment .= '</tr></tbody></table></div>';
						}
					}
				}
				
				$commentaire = '<div class="alert alert-success"><i class="icon icon-ok-sign"></i> <strong>Correction : </strong><br><br>'.$comment.' </div>';
			}
			else {
				if ($question->getNombreEssais() >= 3) {
					$comment = '';
					// $mod_macro = $mod_question->getModMacroByType($this->macro_type_correction);
					$mod_macros = $mod_question->getModMacros();
					$mod_elements = $this->em->getRepository('MajordeskAppBundle:ModElement')
											 ->getOrderedModElementsByModExerciceId($mod_question->getModExercice()->getId());
					
					foreach($mod_macros as $mod_macro) {
						if ($mod_macro->getFond() == 4 || $mod_macro->getType() == $this->macro_type_indice) {
							if ($mod_macro->getType() == $this->macro_type_normal || $mod_macro->getType() == $this->macro_type_indice) {
								foreach($mod_elements as $mod_element) {
									if ($mod_element->getModMacro()->getId() == $mod_macro->getId() ) {
										if ($mod_element->getType() == $this->element_type_maths) {
											$comment .= ' <script type="math/mml"><math>'.$mod_element->getContenu().'</math></script> ';
										}
										else if ($mod_element->getType() == $this->element_type_br) {
											$comment .= '<br>';
										}
										else if ($mod_element->getType() == $this->element_type_jsgbox || $mod_element->getType() == $this->element_type_jsggraph) {
											$comment .= '<div id="'.$mod_element->getClavier().'" ></div><script type="text/javascript">$("#'.$mod_element->getClavier().'").height(300*$("#'.$mod_element->getClavier().'").width()/570); '.$mod_element->getContenu().'</script>';
										}
										else {
											$comment .= $mod_element->getContenu();
										}
									}
								}
							}
							else if ($mod_macro->getType() == $this->macro_type_tableau) {
								$comment .= '<table class="table table-bordered borders-correction" style="border-color: #e3d27c;"><thead style="border-color: #e3d27c;">';
									$tr_count = 0;
									$td_opened = 0;
								foreach($mod_elements as $mod_element) {
									if ($mod_element->getModMacro()->getId() == $mod_macro->getId() ) {
										if ($mod_element->getType() == $this->element_type_maths) {
											$comment .= ' <script type="math/mml"><math>'.$mod_element->getContenu().'</math></script> ';
										}
										else if ($mod_element->getType() == $this->element_type_tr) {
											$tr_count++;
											if ($tr_count != 1) {
												$comment .= '</td><tr>';
												if ($tr_count == 2) {
													$comment .= '</thead><tbody style="border-color: #e3d27c;">';
												}
											}
											$comment .= '<tr style="border-color: #e3d27c;">';
										}
										else if ($mod_element->getType() == $this->element_type_td) {
											if ($td_opened == 1) {
												$comment .= '</td>';
												$td_opened = 0;
											}
											$comment .= '<td style="border-color: #e3d27c;">';
											$td_opened = 1;
										}
										else if ($mod_element->getType() == $this->element_type_br) {
											$comment .= '<br>';
										}
										else {
											$comment .= $mod_element->getContenu();
										}
									}
								}
								$comment .= '</tr></tbody></table>';
							}
							else if ($mod_macro->getType() == $this->macro_type_tableau_analyse) {
								$comment .= '<table class="table><thead>';
									$tr_opened = -1;
									$td_opened = -1;
									$tr_count = 0;
								foreach($mod_elements as $mod_element) {
									if ($mod_element->getModMacro()->getId() == $mod_macro->getId() ) {
										if ($mod_element->getType() == $this->element_type_maths) {
											$comment .= ' <script type="math/mml"><math>'.$mod_element->getContenu().'</math></script> ';
										}
										else if ($mod_element->getType() == $this->element_type_tr) {
											$tr_opened = 1;
											$tr_count++;
											if ($tr_opened != -1) {
												$comment .= '<tr>';
												if ($tr_count == 2) {
													$comment .= '</thead><tbody>';
												}
											}
											$comment .= '<tr>';
										}
										else if ($mod_element->getType() == $this->element_type_td) {
											if ($td_opened != -1) {
												$comment .= '</div></td>';
												$td_opened = 0;
											}
											$style ='border-color:#a0c491;';
											if ($tr_opened == 1) {
												$tr_opened = 0;
												if ($tr_count != 2 && $tr_count != 7 && $tr_count != 12) {
													$style .='border-top:none;';
												}
												$style .='border-right: 1px solid #a0c491;';
											}
											else {
												if ($tr_count != 2 && $tr_count != 7 && $tr_count != 12) {
													$style .='border-top:none;';
												}
											}
											$comment .= '<td class="col-lg-1" style="'.$style.'">';
											
											$comment .= '<div class="text-center">';
										}
										else if ($mod_element->getType() == $this->element_type_br) {
											$comment .= '<br>';
										}
										else {
											$comment .= $mod_element->getContenu();
										}
									}
								}
								$comment .= '</tr></tbody></table>';
							}
						}
					}
					
					$commentaire = '<div class="alert alert-danger"><i class="icon icon-exclamation-sign"></i> <strong>Erreur : </strong><br>'.$erreur.'</div><div class="alert alert-warning"><i class="icon icon-info-sign"></i> <strong>Indice : </strong><br><br> '.$comment.' </div>';
				}
				else {
					$commentaire = '<div class="alert alert-danger"><i class="icon icon-exclamation-sign"></i> <strong>Erreur : </strong><br>'.$erreur.'</div>';
				}
			}
		} else {
			if ($valeur_question === true) {
				$comment = '';
				$correction = $mod_question->getCorrection();
				if (!empty($correction)) {
					$mod_briques = $this->em->getRepository('MajordeskAppBundle:ModBrique')
											->getOrderedModBriquesInComplement($correction->getId());
					
					foreach($mod_briques as $mod_brique) {
						if ($mod_brique->getType() == 'textnmaths') {
							$comment .= $mod_brique->getContenu();
						} else if ($mod_brique->getType() == 'retour_ligne') {
							$comment .= '<br>';
						} else if ($mod_brique->getType() == 'saut_ligne') {
							$comment .= '<br><br>';
						} else if ($mod_brique->getType() == 'equations') {
							$equations = json_decode($mod_brique->getContenu());
							$comment .= '<br>$\begin{eqnarray*}';
							$i=1;
							foreach($equations as $equation) {
								if ($i > 1) { $comment .= "\\\\"; }
								$eqn = $equation->contenu;
								$eqn = preg_replace('/(=|\\\ne|>|<|\\\geq|\\\leq)/', '&\0&', $eqn, 1);
								$comment .= $eqn;
								$i++;
							}
							$comment .= '\end{eqnarray*}$<br>';
						} else if ($mod_brique->getType() == 'systeme') {
							$systeme = json_decode($mod_brique->getContenu());
							$comment .= '<br>$\begin{cases}';
							$i=1;
							foreach($systeme as $syst) {
								if ($i > 1) { $comment .= "\\\\"; }
								$comment .= $syst->contenu;
								$i++;
							}
							$comment .= '\end{cases}$<br>';
						} else if ($mod_brique->getType() == 'liste') {
							$liste = json_decode($mod_brique->getContenu());
							$comment .= '<br><ul>';
							foreach($liste as $point) {
								$comment .= '<li>'.$point.'</li>';
							}
							$comment .= '</ul><br>';
						} else if ($mod_brique->getType() == 'liste_ordonnee') {
							$liste = json_decode($mod_brique->getContenu());
							$comment .= '<br><ol>';
							foreach($liste as $point) {
								$comment .= '<li>'.$point.'</li>';
							}
							$comment .= '</ol><br>';
						} else if ($mod_brique->getType() == 'tableau') {
							$tableau = json_decode($mod_brique->getContenu());
							$comment .= '<div class="table-responsive"><table class="table table-bordered"><tbody>';
							foreach($tableau as $row) {
								$comment .= '<tr>';
								foreach($row as $cell) {
									$comment .= '<td><div class=" text-center">'.$cell->contenu.'</div></td>';
								}
								$comment .= '</tr>';
							}
							$comment .= '</tbody></table></div>';
						} else if ($mod_brique->getType() == 'tableau analyse') {
							$tableau = json_decode($mod_brique->getContenu());
							$i_max=0; foreach($tableau as $row) { $i_max++; } // car count($tableau) ne fonctionne pas oO
							$comment .= '<div class="table-responsive"><table class="table table-borderless"><tbody>';
							$i=1;
							foreach($tableau as $row) {
								$j=1;
								if ( $row->type == "entete" ) {
									$comment .= '<tr';
									if ($i<$i_max) {$comment .= ' style="border-bottom:1px solid #468847"';} // #468847 #c09852
									$comment .= '>';
									foreach($row->contenu as $cell) {
										$comment .= '<td';
										if ($j==1) {$comment .= ' class="col-lg-2" style="border-right:1px solid #468847"';} // #468847 #c09852
										$comment .= '><div class=" text-center">';
										if ($cell->contenu != '') {
											$comment .= '$'.$cell->contenu.'$';
										}
										$comment .= '</div></td>';
										$j++;
									}
									$comment .= '</tr>';
								} else if ( $row->type == "signe" ) {
									$comment .= '<tr';
									if ($i<$i_max) {$comment .= ' style="border-bottom:1px solid #468847"';} // #468847 #c09852
									$comment .= '>';
									foreach($row->contenu as $cell) {
										if ($cell->contenu == '%valeur-nulle%') {
											$comment .= '<td style="height:100%;background-image: url(\'../img/maths/valeur-nulle-correction.png\');background-repeat:repeat-y;background-position:center;vertical-align:center"><div class="text-center">$0$</div></td>';
										} else if ($cell->contenu == '%valeur-interdite%') {
											$comment .= '<td style="height:100%;background-image: url(\'../img/maths/valeur-interdite-correction.png\');background-repeat:repeat-y;background-position:center;vertical-align:center"><div class="text-center"></div></td>';
										} else {
											$comment .= '<td';
											if ($j==1) {$comment .= ' class="col-lg-2" style="border-right:1px solid #468847"';} // #468847 #c09852
											$comment .= '><div class=" text-center">';
											if ($cell->contenu != '') {
												$comment .= '$'.$cell->contenu.'$';
											}
											$comment .= '</div></td>';
										}
										
										$j++;
									}
									$comment .= '</tr>';
								} else if ( $row->type == "variations" ) {
									$u_max=0; foreach($row->contenu as $u) { $u_max++; }
									$flecheType = $u_max/2-1;
									$comment .= '<tr';
									if ($i<$i_max) {$comment .= ' style="border-bottom:1px solid #468847"';} // #468847 #c09852
									$comment .= '>';
									foreach($row->contenu as $cell) {
										if ($cell->contenu == '%vide%') {
											$comment .= '<td style="padding-left:0;padding-right:0;"></td>';
										} else if ($cell->contenu == '%asc%') {
											$comment .= '<td style="padding-left:0;padding-right:0;">';
											if ($flecheType==1) {
												$comment .= '<div class="text-center"><img src="../img/maths/asc-1.png"/></div>';
											} else if ($cell->position == 'haut') {
												$comment .= '<div class="text-center"><img src="../img/maths/asc-haut-'.$flecheType.'.png"/></div>';
											} else if ($cell->position == 'milieu') {
												$comment .= '<div class="text-center"><img src="../img/maths/asc-milieu-'.$flecheType.'.png"/></div>';
											} else if ($cell->position == 'bas') {
												$comment .= '<div class="text-center"><img src="../img/maths/asc-bas-'.$flecheType.'.png"/></div>';
											}
											$comment .= '</td>';
										} else if ($cell->contenu == '%desc%') {
											$comment .= '<td style="padding-left:0;padding-right:0;">';
											if ($flecheType==1) {
												$comment .= '<div class="text-center"><img src="../img/maths/desc-1.png"/></div>';
											} else if ($cell->position == 'haut') {
												$comment .= '<div class="text-center"><img src="../img/maths/desc-haut-'.$flecheType.'.png"/></div>';
											} else if ($cell->position == 'milieu') {
												$comment .= '<div class="text-center"><img src="../img/maths/desc-milieu-'.$flecheType.'.png"/></div>';
											} else if ($cell->position == 'bas') {
												$comment .= '<div class="text-center"><img src="../img/maths/desc-bas-'.$flecheType.'.png"/></div>';
											}
											$comment .= '</td>';
										} else if ($cell->contenu == '%valeur-interdite%') {
											$comment .= '<td style="padding:0;height:100%;background-image: url(\'../img/maths/valeur-interdite-correction.png\');background-repeat:repeat-y;background-position:center;font-size:10px;">';
											$comment .= '<div class="text-center"><div class="display-inline-block" style="width:22px;';
											if ($cell->positiong == 'bas') {
												$comment .= 'position:relative;';
												if ($flecheType == 1) { $comment .= 'top:80px'; }
												else if ($flecheType == 2) { $comment .= 'top:80px'; }
												else if ($flecheType == 3) { $comment .= 'top:60px'; }
												else if ($flecheType == 4) { $comment .= 'top:40px'; }
											}
											$comment .= '">$'.$cell->borneg.'$</div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;';
											if ($cell->positiond == 'bas') {
												$comment .= 'position:relative;';
												if ($flecheType == 1) { $comment .= 'top:80px'; }
												else if ($flecheType == 2) { $comment .= 'top:80px'; }
												else if ($flecheType == 3) { $comment .= 'top:60px'; }
												else if ($flecheType == 4) { $comment .= 'top:40px'; }
											}
											$comment .= '">$'.$cell->borneg.'$</div></div></td>';
										} else if ($cell->contenu == '%bg-interdite%') {
											$comment .= '<td style="padding:0;height:100%;background-image: url(\'../img/maths/valeur-interdite-correction.png\');background-repeat:repeat-y;background-position:left;font-size:10px;">';
											$comment .= '<div class="text-center"><div class="display-inline-block" style="width:12px;"></div><div class="display-inline-block" style="width:22px;';
											if ($cell->positiond == 'bas') {
												$comment .= 'position:relative;';
												if ($flecheType == 1) { $comment .= 'top:80px'; }
												else if ($flecheType == 2) { $comment .= 'top:80px'; }
												else if ($flecheType == 3) { $comment .= 'top:60px'; }
												else if ($flecheType == 4) { $comment .= 'top:40px'; }
											}
											$comment .= '">$'.$cell->borneg.'$</div></div></td>';
										} else if ($cell->contenu == '%bd-interdite%') {
											$comment .= '<td style="padding:0;height:100%;background-image: url(\'../img/maths/valeur-interdite-correction.png\');background-repeat:repeat-y;background-position:right;font-size:10px;">';
											$comment .= '<div class="text-center"><div class="display-inline-block" style="width:22px;';
											if ($cell->positiond == 'bas') {
												$comment .= 'position:relative;';
												if ($flecheType == 1) { $comment .= 'top:80px'; }
												else if ($flecheType == 2) { $comment .= 'top:80px'; }
												else if ($flecheType == 3) { $comment .= 'top:60px'; }
												else if ($flecheType == 4) { $comment .= 'top:40px'; }
											}
											$comment .= '">$'.$cell->borneg.'$</div><div class="display-inline-block" style="width:8px;"></div></div></td>';
										} else {
											$comment .= '<td';
											if ($j==1) {$comment .= ' class="col-lg-2" style="border-right:1px solid #468847"';} // #468847 #c09852
											$comment .= '><div class=" text-center" style="';
											if ($cell->position == 'milieu') {
												$comment .= 'position:relative;';
												if ($flecheType == 1) { $comment .= 'top:40px'; }
												else if ($flecheType == 2) { $comment .= 'top:40px'; }
												else if ($flecheType == 3) { $comment .= 'top:30px'; }
												else if ($flecheType == 4) { $comment .= 'top:20px'; }
											} else if ($cell->position == 'bas') {
												$comment .= 'position:relative;';
												if ($flecheType == 1) { $comment .= 'top:80px'; }
												else if ($flecheType == 2) { $comment .= 'top:80px'; }
												else if ($flecheType == 3) { $comment .= 'top:60px'; }
												else if ($flecheType == 4) { $comment .= 'top:40px'; }
											}
											$comment .= '">';
											if ($cell->contenu != '') {
												$comment .= '$'.$cell->contenu.'$';
											}
											$comment .= '</div></td>';
										}
										$j++;
									}
									$comment .= '</tr>';
								}
								$i++;
							}
							$comment .= '</tbody></table></div>';
						}
					}
					
				} else {
					$comment = '<em>Un problème est survenu lors du chargement de la correction</em>';
				}

				$commentaire = '<div class="alert alert-success"><i class="icon icon-ok-sign"></i> <strong>Correction : </strong><br><br>'.$comment.' </div>';
			}
			else {
				if ($question->getNombreEssais() >= 3) {
					$comment = '';
					
					$indice = $mod_question->getIndice();
					if (!empty($indice)) {
						$mod_briques = $this->em->getRepository('MajordeskAppBundle:ModBrique')
												->getOrderedModBriquesInComplement($indice->getId());
						
						foreach($mod_briques as $mod_brique) {
							if ($mod_brique->getType() == 'textnmaths') {
								$comment .= $mod_brique->getContenu();
							} else if ($mod_brique->getType() == 'retour_ligne') {
								$comment .= '<br>';
							} else if ($mod_brique->getType() == 'saut_ligne') {
								$comment .= '<br><br>';
							} else if ($mod_brique->getType() == 'equations') {
								$equations = json_decode($mod_brique->getContenu());
								$comment .= '<br>$\begin{eqnarray*}';
								$i=1;
								foreach($equations as $equation) {
									if ($i > 1) { $comment .= "\\\\"; }
									$eqn = $equation->contenu;
									$eqn = preg_replace('/(=|\\\ne|>|<|\\\geq|\\\leq)/', '&\0&', $eqn, 1);
									$comment .= $eqn;
									$i++;
								}
								$comment .= '\end{eqnarray*}$<br>';
							} else if ($mod_brique->getType() == 'systeme') {
								$systeme = json_decode($mod_brique->getContenu());
								$comment .= '<br>$\begin{cases}';
								$i=1;
								foreach($systeme as $syst) {
									if ($i > 1) { $comment .= "\\\\"; }
									$comment .= $syst->contenu;
									$i++;
								}
								$comment .= '\end{cases}$<br>';
							} else if ($mod_brique->getType() == 'liste') {
								$liste = json_decode($mod_brique->getContenu());
								$comment .= '<br><ul>';
								foreach($liste as $point) {
									$comment .= '<li>'.$point->contenu.'</li>';
								}
								$comment .= '</ul><br>';
							} else if ($mod_brique->getType() == 'liste ordonnee') {
								$liste = json_decode($mod_brique->getContenu());
								$comment .= '<br><ol>';
								foreach($liste as $point) {
									$comment .= '<li>'.$point->contenu.'</li>';
								}
								$comment .= '</ol><br>';
							} else if ($mod_brique->getType() == 'tableau') {
								$tableau = json_decode($mod_brique->getContenu());
								$comment .= '<div class="table-responsive"><table class="table table-bordered"><tbody>';
								foreach($tableau as $row) {
									$comment .= '<tr>';
									foreach($row as $cell) {
										$comment .= '<td><div class=" text-center">'.$cell->contenu.'</div></td>';
									}
									$comment .= '</tr>';
								}
								$comment .= '</tbody></table></div>';
							} else if ($mod_brique->getType() == 'tableau analyse') {
								$tableau = json_decode($mod_brique->getContenu());
								$i_max=0; foreach($tableau as $row) { $i_max++; } // car count($tableau) ne fonctionne pas oO
								$comment .= '<div class="table-responsive"><table class="table table-borderless"><tbody>';
								$i=1;
								foreach($tableau as $row) {
									$j=1;
									if ( $row->type == "entete" ) {
										$comment .= '<tr';
										if ($i<$i_max) {$comment .= ' style="border-bottom:1px solid #c09852"';} // #468847 #c09852
										$comment .= '>';
										foreach($row->contenu as $cell) {
											$comment .= '<td';
											if ($j==1) {$comment .= ' class="col-lg-2" style="border-right:1px solid #c09852"';} // #468847 #c09852
											$comment .= '><div class=" text-center">';
											if ($cell->contenu != '') {
												$comment .= '$'.$cell->contenu.'$';
											}
											$comment .= '</div></td>';
											$j++;
										}
										$comment .= '</tr>';
									} else if ( $row->type == "signe" ) {
										$comment .= '<tr';
										if ($i<$i_max) {$comment .= ' style="border-bottom:1px solid #c09852"';} // #468847 #c09852
										$comment .= '>';
										foreach($row->contenu as $cell) {
											if ($cell->contenu == '%valeur-nulle%') {
												$comment .= '<td style="height:100%;background-image: url(\'../img/maths/valeur-nulle-indice.png\');background-repeat:repeat-y;background-position:center;vertical-align:center"><div class="text-center">$0$</div></td>';
											} else if ($cell->contenu == '%valeur-interdite%') {
												$comment .= '<td style="height:100%;background-image: url(\'../img/maths/valeur-interdite-indice.png\');background-repeat:repeat-y;background-position:center;vertical-align:center"><div class="text-center"></div></td>';
											} else {
												$comment .= '<td';
												if ($j==1) {$comment .= ' class="col-lg-2" style="border-right:1px solid #c09852"';} // #468847 #c09852
												$comment .= '><div class=" text-center">';
												if ($cell->contenu != '') {
													$comment .= '$'.$cell->contenu.'$';
												}
												$comment .= '</div></td>';
											}
											
											$j++;
										}
										$comment .= '</tr>';
									} else if ( $row->type == "variations" ) {
										$u_max=0; foreach($row->contenu as $u) { $u_max++; }
										$flecheType = $u_max/2-1;
										$comment .= '<tr';
										if ($i<$i_max) {$comment .= ' style="border-bottom:1px solid #c09852"';} // #468847 #c09852
										$comment .= '>';
										foreach($row->contenu as $cell) {
											if ($cell->contenu == '%vide%') {
												$comment .= '<td style="padding-left:0;padding-right:0;"></td>';
											} else if ($cell->contenu == '%asc%') {
												$comment .= '<td style="padding-left:0;padding-right:0;">';
												if ($flecheType==1) {
													$comment .= '<div class="text-center"><img src="../img/maths/asc-1.png"/></div>';
												} else if ($cell->position == 'haut') {
													$comment .= '<div class="text-center"><img src="../img/maths/asc-haut-'.$flecheType.'.png"/></div>';
												} else if ($cell->position == 'milieu') {
													$comment .= '<div class="text-center"><img src="../img/maths/asc-milieu-'.$flecheType.'.png"/></div>';
												} else if ($cell->position == 'bas') {
													$comment .= '<div class="text-center"><img src="../img/maths/asc-bas-'.$flecheType.'.png"/></div>';
												}
												$comment .= '</td>';
											} else if ($cell->contenu == '%desc%') {
												$comment .= '<td style="padding-left:0;padding-right:0;">';
												if ($flecheType==1) {
													$comment .= '<div class="text-center"><img src="../img/maths/desc-1.png"/></div>';
												} else if ($cell->position == 'haut') {
													$comment .= '<div class="text-center"><img src="../img/maths/desc-haut-'.$flecheType.'.png"/></div>';
												} else if ($cell->position == 'milieu') {
													$comment .= '<div class="text-center"><img src="../img/maths/desc-milieu-'.$flecheType.'.png"/></div>';
												} else if ($cell->position == 'bas') {
													$comment .= '<div class="text-center"><img src="../img/maths/desc-bas-'.$flecheType.'.png"/></div>';
												}
												$comment .= '</td>';
											} else if ($cell->contenu == '%valeur-interdite%') {
												$comment .= '<td style="padding:0;height:100%;background-image: url(\'../img/maths/valeur-interdite-indice.png\');background-repeat:repeat-y;background-position:center;font-size:10px;">';
												$comment .= '<div class="text-center"><div class="display-inline-block" style="width:22px;';
												if ($cell->positiong == 'bas') {
													$comment .= 'position:relative;';
													if ($flecheType == 1) { $comment .= 'top:80px'; }
													else if ($flecheType == 2) { $comment .= 'top:80px'; }
													else if ($flecheType == 3) { $comment .= 'top:60px'; }
													else if ($flecheType == 4) { $comment .= 'top:40px'; }
												}
												$comment .= '">$'.$cell->borneg.'$</div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;';
												if ($cell->positiond == 'bas') {
													$comment .= 'position:relative;';
													if ($flecheType == 1) { $comment .= 'top:80px'; }
													else if ($flecheType == 2) { $comment .= 'top:80px'; }
													else if ($flecheType == 3) { $comment .= 'top:60px'; }
													else if ($flecheType == 4) { $comment .= 'top:40px'; }
												}
												$comment .= '">$'.$cell->borneg.'$</div></div></td>';
											} else if ($cell->contenu == '%bg-interdite%') {
												$comment .= '<td style="padding:0;height:100%;background-image: url(\'../img/maths/valeur-interdite-indice.png\');background-repeat:repeat-y;background-position:left;font-size:10px;">';
												$comment .= '<div class="text-center"><div class="display-inline-block" style="width:12px;"></div><div class="display-inline-block" style="width:22px;';
												if ($cell->positiond == 'bas') {
													$comment .= 'position:relative;';
													if ($flecheType == 1) { $comment .= 'top:80px'; }
													else if ($flecheType == 2) { $comment .= 'top:80px'; }
													else if ($flecheType == 3) { $comment .= 'top:60px'; }
													else if ($flecheType == 4) { $comment .= 'top:40px'; }
												}
												$comment .= '">$'.$cell->borneg.'$</div></div></td>';
											} else if ($cell->contenu == '%bd-interdite%') {
												$comment .= '<td style="padding:0;height:100%;background-image: url(\'../img/maths/valeur-interdite-indice.png\');background-repeat:repeat-y;background-position:right;font-size:10px;">';
												$comment .= '<div class="text-center"><div class="display-inline-block" style="width:22px;';
												if ($cell->positiond == 'bas') {
													$comment .= 'position:relative;';
													if ($flecheType == 1) { $comment .= 'top:80px'; }
													else if ($flecheType == 2) { $comment .= 'top:80px'; }
													else if ($flecheType == 3) { $comment .= 'top:60px'; }
													else if ($flecheType == 4) { $comment .= 'top:40px'; }
												}
												$comment .= '">$'.$cell->borneg.'$</div><div class="display-inline-block" style="width:8px;"></div></div></td>';
											} else {
												$comment .= '<td';
												if ($j==1) {$comment .= ' class="col-lg-2" style="border-right:1px solid #c09852"';} // #468847 #c09852
												$comment .= '><div class=" text-center" style="';
												if ($cell->position == 'milieu') {
													$comment .= 'position:relative;';
													if ($flecheType == 1) { $comment .= 'top:40px'; }
													else if ($flecheType == 2) { $comment .= 'top:40px'; }
													else if ($flecheType == 3) { $comment .= 'top:30px'; }
													else if ($flecheType == 4) { $comment .= 'top:20px'; }
												} else if ($cell->position == 'bas') {
													$comment .= 'position:relative;';
													if ($flecheType == 1) { $comment .= 'top:80px'; }
													else if ($flecheType == 2) { $comment .= 'top:80px'; }
													else if ($flecheType == 3) { $comment .= 'top:60px'; }
													else if ($flecheType == 4) { $comment .= 'top:40px'; }
												}
												$comment .= '">';
												if ($cell->contenu != '') {
													$comment .= '$'.$cell->contenu.'$';
												}
												$comment .= '</div></td>';
											}
											$j++;
										}
										$comment .= '</tr>';
									}
									$i++;
								}
								$comment .= '</tbody></table></div>';
							}
						}
						
					} else {
						$comment = '<em>Un problème est survenu lors du chargement de l\'indice</em>';
					}
					
					$commentaire = '<div class="alert alert-danger"><i class="icon icon-exclamation-sign"></i> <strong>Erreur : </strong><br>'.$erreur.'</div><div class="alert alert-warning"><i class="icon icon-info-sign"></i> <strong>Indice : </strong><br><br> '.$comment.' </div>';
				}
				else {
					$commentaire = '<div class="alert alert-danger"><i class="icon icon-exclamation-sign"></i> <strong>Erreur : </strong><br>'.$erreur.'</div>';
				}
			}
		}
		
		return $commentaire;
	}
	
	/**
     * @info Traite les réponses données par l'élève à une question
     */
	public function evaluateAndPersistReponses($question, $reponses_, $temps_, $isLastCouche)
	{
		$exercice = $question->getExercice();
		$mod_question = $question->getModQuestion();
		$mod_exercice = $mod_question->getModExercice();
		$is_new = $mod_exercice->getIsNew();
		$questionAnalysis = array(true, '', 0);
		$CR = array(); // DEBUG
		$rep = new Rep();	
		
		foreach( $mod_question->getModMappings() as $mod_mapping ) {
			$reponses = array();
			$mod_reponses = array();
			foreach($reponses_ as $reponse_) {
				if ( $mod_mapping->hasModReponse($reponse_->numero) ) {
					if ($is_new) {	
						$micro_rep = new MicroRep();		
						$micro_rep->setNumero($reponse_->numero);
						$micro_rep->setContenu($reponse_->contenu);		
						$rep->addMicroRep($micro_rep);
						$question->addRep($rep);
						$reponses[] = $micro_rep;
						$mod_reponse_s = $mod_mapping->selectAllModReponseByNumero($reponse_->numero);
						$mod_reponses = array_merge($mod_reponses, $mod_reponse_s);
					} else {
						$reponse = new Reponse();		
						$reponse->setNumero($reponse_->numero);
						$reponse->setContenu($reponse_->contenu);		
						$question->addReponse($reponse);
						$reponses[] = $reponse;
						$mod_reponse_s = $mod_mapping->selectAllModReponseByNumero($reponse_->numero);
						$mod_reponses = array_merge($mod_reponses, $mod_reponse_s);
					}
				}
			}
			if (!empty($mod_reponses)) {
				$mappingAnalysis = $this->analyzeMapping($reponses, $mod_reponses, $mod_mapping->getType());
				$CR[] = array($mod_mapping->getId(), $mod_mapping->getType(), $mappingAnalysis); // DEBUG
				if ($mappingAnalysis[0] === false) {
					$questionAnalysis[0] = false;
				}
				if ($mappingAnalysis[1] != '') {
					$questionAnalysis[1] = $mappingAnalysis[1];
				}
			}
		}
		if ($isLastCouche == 1) {
			$question->setNombreEssais($question->getNombreEssais() + 1);
			$question->setStatut($questionAnalysis[0]);
			$rep->setValeur($questionAnalysis[0]);
			$questionAnalysis[1] = $this->getAppropriateCommentary($question, $questionAnalysis[0], $questionAnalysis[1]);
			
			if ($is_new) {
				$rep->setCommentaire($questionAnalysis[1]);
			} else {
				$question->setCommentaire($questionAnalysis[1]);
			}
			if ($exercice->getStatut() == 2) {	
				$exercice->setQueue(0);
				$questionAnalysis[2] = 1;
			}
		}
		else {
			if ($questionAnalysis[0] === true) {
				$question->setStatut(false);
				$rep->setValeur(false);
				$question->setCouche($question->getCouche()+1);
				if ($is_new) {
					$rep->setCommentaire('');
				} else {
					$question->setCommentaire('');
				}
			}
			else {
				$question->setStatut($questionAnalysis[0]);
				$rep->setValeur($questionAnalysis[0]);
				$question->setNombreEssais($question->getNombreEssais() + 1);
				$questionAnalysis[1] = '<div class="alert alert-danger"><i class="icon icon-exclamation-sign"></i> <strong>Erreur : </strong>Ta réponse est fausse. </div>';
				if ($is_new) {
					$rep->setCommentaire('<div class="alert alert-danger"><i class="icon icon-exclamation-sign"></i> <strong>Erreur : </strong>Ta réponse est fausse. </div>');
				} else {
					$question->setCommentaire('<div class="alert alert-danger"><i class="icon icon-exclamation-sign"></i> <strong>Erreur : </strong>Ta réponse est fausse. </div>');
				}
			}
		}
		
		$question->updateDateEnregistrementToCurrentDate();
		$exercice->setTemps($temps_);
		$etape_cours = $this->session->get('etape_cours');
		if ($etape_cours == 1 || $etape_cours == 2) {
			$exercice->setAutonomie(false);
		}
		
		$this->em->persist($exercice);
		$this->em->flush();	
		
		$questionAnalysis[] = $CR;  // DEBUG
		
		return $questionAnalysis;
	}
	
	/**
     * @info Evalue la valeur (vrai/faux) d'un mapping
     */
	public function analyzeMapping($reponses, $mod_reponses, $type)
	{
		$mappingAnalysis = array(true, '');

		if ($type == null) {
			for( $i=0 ; $i<=count($reponses)-1 ; $i++ ) {
				$reponseAnalysis = $this->analyzeReponse($reponses[$i], $mod_reponses, $type);
				$reponses[$i]->setValeur($reponseAnalysis[0]);
				if ($reponseAnalysis[0] === false) {
					$mappingAnalysis[0] = false;
				}
				if ($reponseAnalysis[1] != '') {
					$mappingAnalysis[1] = $reponseAnalysis[1];
				}
			}
		}
		else if ($type == 'association') {
			$i=0;
			$reponseAnalysis = array(true, '');
			
			while( $reponseAnalysis[0] === true && $i<=count($mod_reponses)-1 ) {
				$reponseAnalysis = $this->analyzeReponse($reponses[$i], $mod_reponses, $type);
				if ($reponseAnalysis[1] != '') {
					$mappingAnalysis[1] = $reponseAnalysis[1];
				}
				$i++;
			}
			foreach($reponses as $reponse) {
				$reponse->setValeur($reponseAnalysis[0]);
			}
			$mappingAnalysis[0] = $reponseAnalysis[0];
		}
		else if ($type == 'association groupe') { // cas réponse 1 1bis 2 2bis et les réponses autorisées sont 1-2 ou 1bis-2bis
			$reponseAnalysis = array(true, '');

			usort($mod_reponses, function($a, $b)
			{
				if ($a->getNumero() == $b->getNumero()) {
					return 0;
				}
				return ($a->getNumero() < $b->getNumero()) ? -1 : 1;
			});
			
			$numeros = array();
			$mods = array();
			$ind = 0;
			foreach($mod_reponses as $mod_reponse) {
				if (!in_array($mod_reponse->getNumero(), $numeros)) {
					$numeros[] = $mod_reponse->getNumero();
					$ind = 0;
				}		
				$mods[$ind][] = $mod_reponse;
				$ind++;
			}
			$alts = floor(count($mod_reponses)/count($numeros));
			
			for($j=0;$j<=$alts-1;$j++) {
				$reponseAnalysis = $this->analyzeReponse($reponses[0], $mods[$j], $type);
				if ($reponseAnalysis[0] === true) {
					$selected = $j;
				}
			}
			
			if ( isset($selected) ) {
				$reponseAnalysis[0] = true;
				$i=0;
				while( $reponseAnalysis[0] === true && $i<=count($mods[$selected])-1 ) {
					$reponseAnalysis = $this->analyzeReponse($reponses[$i], $mods[$selected], $type);
					if ($reponseAnalysis[1] != '') {
						$mappingAnalysis[1] = $reponseAnalysis[1];
					}
					$i++;
				}
			}
			
			foreach($reponses as $reponse) {
				$reponse->setValeur($reponseAnalysis[0]);
			}
			$mappingAnalysis[0] = $reponseAnalysis[0];
		}
		else if ($type == 'permut tot') { // fonctionne avec les réponses alternatives aussi
			$numeros_indisponibles = array();
			for( $i=0 ; $i<=count($reponses)-1 ; $i++ ) {
				for( $j=0 ; $j<= count($mod_reponses)-1 ; $j++) {
					if (!in_array($mod_reponses[$j]->getNumero(),$numeros_indisponibles)) {
						$reponseAnalysis = $this->analyzeReponse($reponses[$i], array($mod_reponses[$j]), $type);
						
						if ($reponseAnalysis[0] === true) {
							$reponses[$i]->setValeur($reponseAnalysis[0]);
							$numeros_indisponibles[] = $mod_reponses[$j]->getNumero();
							break;
						}	
					}
				}

				if ($reponses[$i]->getValeur() === false || $reponses[$i]->getValeur() == '') {
					$mappingAnalysis[0] = false;
				}
				if ($reponseAnalysis[1] != '') {
					$mappingAnalysis[1] = $reponseAnalysis[1];
				}
			}
		}
		else {
			$mappingAnalysis = array(false, '');
		}
		
		return $mappingAnalysis;
	}
	
	/**
     * @info Evalue la valeur (vrai/faux) d'une réponse
	 * @info $reponseAnalysis[0] contient la valeur de la réponse (défaut: false)
	 * @info $reponseAnalysis[1] contient un éventuel message d'erreur (défaut: '')
     */
	public function analyzeReponse($reponse, $mod_reponses, $mapping_type)
	{
		$reponseAnalysis = array(false, '');
	
		foreach($mod_reponses as $mod_reponse) {
			if ( $reponse->getNumero() == $mod_reponse->getNumero() || $mapping_type == 'permut tot' ) {
				$type = $mod_reponse->getType();
				// throw new \Exception($reponse->getContenu().' et '.$mod_reponse->getContenu());
				$preparedReponse = $this->prepareForAnalysis($reponse->getContenu());
				$preparedModReponse = $this->prepareForAnalysis($mod_reponse->getContenu());
				// throw new \Exception($preparedReponse.' et '.$preparedModReponse);
				
				if ($type == 'expression' || $type == 'expression exacte') {
					$variables = array();
					$mod_variables = array();
					$variables_communes = array();
					$variables_usuelles = array('x', 'y', 'z');
					foreach($variables_usuelles as $variable_usuelle) {
						if (strpos($preparedReponse, $variable_usuelle) !== false) {
							$variables[] = $variable_usuelle;
						}
						if (strpos($preparedModReponse, $variable_usuelle) !== false) {
							$mod_variables[] = $variable_usuelle;
						}
						if (strpos($preparedReponse, $variable_usuelle) !== false && strpos($preparedModReponse, $variable_usuelle) !== false) {
							$variables_communes[] = $variable_usuelle;
						}
					}
					
					if ( $type == 'expression' ) {
						if ( $preparedReponse == $preparedModReponse ) {
							$reponseAnalysis[0] = true;
							break;
						}
						else {					
							if (count($variables) == 0 && count($mod_variables) == 0) {
								$reponseAnalysis[0] = $this->math_validator->evaluate($preparedReponse) == $this->math_validator->evaluate($preparedModReponse);
							}
							else if (count($variables) == 1 && count($mod_variables) == 1 && count($variables_communes) == 1) {
								$reponseAnalysis[0] = $this->analyzeExpression($preparedReponse, $preparedModReponse, $variables[0]);
							}
							else if (count($variables) == 1 && count($mod_variables) == 1 && count($variables_communes) == 0) {
								$reponseAnalysis[1] = 'Tu t\'es peut-être trompé de variable. Ta réponse doit probablement dépendre de <script type="math/mml"><math><mi>'.$mod_variables[0].'</mi></math></script> et non de <script type="math/mml"><math>'.$variables[0].'</math></script>. ';
							}
							else if (count($variables) == 0 && count($mod_variables) == 1 && count($variables_communes) == 0) {
								$reponseAnalysis[1] = 'Ta réponse doit probablement dépendre de <script type="math/mml"><math><mi>'.$mod_variables[0].'</mi></math></script>. ';
							}
						}
					}
					else if ($type == 'expression exacte') {	
						if ( $preparedReponse == $preparedModReponse ) {
							$reponseAnalysis[0] = true;
							break;
						}
						else {
							if (count($variables) == 0 && count($mod_variables) == 0) {
								$chars_allowed = array('+','-',',','/','*','s','q','r','t','(',')','^','0','1','2','3','4','5','6','7','8','9','l','n','e','p','i');
								$check_allowed = true;
								$reponse_split = str_split($preparedReponse);
								$mod_reponse_split = str_split($preparedModReponse);
								foreach($reponse_split as $char) {
									if (!in_array($char,$chars_allowed)) {
										$check_allowed = false;
										break;
									}
								}
								if ($check_allowed) {
									foreach($mod_reponse_split as $mod_char) {
										if (!in_array($mod_char,$chars_allowed)) {
											$check_allowed = false;
											break;
										}
									}
								}
								if ($check_allowed) {
									if ($this->math_validator->evaluate($preparedReponse) == $this->math_validator->evaluate($preparedModReponse)) {
										$reponseAnalysis[1] = 'Tu es proche, mais ta réponse ne semble pas être présentée sous la forme attendue. ';
										if (strpos($preparedReponse, '/') !== false) {
											$reponseAnalysis[1] = $reponseAnalysis[1].'Pense à vérifier que les fractions sont sous forme irréductible. ';
										}
									}
								}
							}
							else if (count($variables) == 1 && count($mod_variables) == 1 && count($variables_communes) == 1) {
								$chars_allowed = array('+','-',',','/','*','s','q','r','t','(',')','^','0','1','2','3','4','5','6','7','8','9','l','n','e','x','y','z','t','p','i');
								$check_allowed = true;
								$reponse_split = str_split($preparedReponse);
								$mod_reponse_split = str_split($preparedModReponse);
								foreach($reponse_split as $char) {
									if (!in_array($char,$chars_allowed)) {
										$check_allowed = false;
										break;
									}
								}
								if ($check_allowed) {
									if ( $this->analyzeExpression($preparedReponse, $preparedModReponse, $variables[0]) ) {
										$reponseAnalysis[1] = 'Tu es proche, mais ta réponse ne semble pas être présentée sous la forme attendue. ';
										if (strpos($preparedReponse, '/') !== false) {
											$reponseAnalysis[1] = $reponseAnalysis[1].'Pense à vérifier que les fractions sont sous forme irréductible. ';
										}
									}
								}
							}
							else if (count($variables) == 1 && count($mod_variables) == 1 && count($variables_communes) == 0) {
								$reponseAnalysis[1] = 'Tu t\'es peut-être trompé de variable. Ta réponse doit probablement dépendre de <script type="math/mml"><math><mi>'.$mod_variables[0].'</mi></math></script> et non de <script type="math/mml"><math>'.$variables[0].'</math></script>. ';
							}
							else if (count($variables) == 0 && count($mod_variables) == 1 && count($variables_communes) == 0) {
								$reponseAnalysis[1] = 'Ta réponse doit probablement dépendre de <script type="math/mml"><math><mi>'.$mod_variables[0].'</mi></math></script>. ';
							}
							else {
								$reponseAnalysis[1] = false;
							}
						}
					}
				}
				else if ($type == 'triangle') {
					if (strlen($preparedModReponse) != 3) {
						throw new \Exception('Une erreur est survenue : la réponse attendue est le nom d\'un triangle, mais la correction automatique ne propose pas une telle réponse.');
					}
					
					if (strlen($preparedReponse) != 3) {
						$reponseAnalysis[1] = 'Un triangle comprend 3 noms de sommets !';
						return $reponseAnalysis;
					}
					
					$permut = array();
					$permut[] = $preparedModReponse[0].$preparedModReponse[1].$preparedModReponse[2];
					$permut[] = $preparedModReponse[0].$preparedModReponse[2].$preparedModReponse[1];
					$permut[] = $preparedModReponse[1].$preparedModReponse[2].$preparedModReponse[0];
					$permut[] = $preparedModReponse[1].$preparedModReponse[0].$preparedModReponse[2];
					$permut[] = $preparedModReponse[2].$preparedModReponse[1].$preparedModReponse[0];
					$permut[] = $preparedModReponse[2].$preparedModReponse[0].$preparedModReponse[1];
					
					if ( in_array($preparedReponse, $permut) ) {
						$reponseAnalysis[0] = true;
						break;
					}
				}
				else if ($type == 'distance') {
					if (strlen($preparedModReponse) != 2 && strlen($preparedModReponse) != 4) {
						throw new \Exception('Une erreur est survenue : la réponse attendue est le nom d\'une distance, d\'une (demi-)droite ou d\'un segment, mais la correction automatique ne propose pas une telle réponse.');
					}
					
					$permut = array();
					if (strlen($preparedModReponse) == 2) {
						$permut[] = $preparedModReponse;
						$permut[] = $preparedModReponse[1].$preparedModReponse[0];
						
						if (strlen($preparedReponse) == 4 ) {
							$reponseAnalysis[1] = 'Une distance se note simplement par le nom de deux points. Pas besoin de () ou [].';
							return $reponseAnalysis;
						}
					}
					else if (strlen($preparedModReponse) == 4) {
						if ( ($preparedModReponse[0] == '(' && $preparedModReponse[3] == ')') || ($preparedModReponse[0] == '[' && $preparedModReponse[3] == ']') ) {
							$permut[] = $preparedModReponse;
							$permut[] = $preparedModReponse[0].$preparedModReponse[2].$preparedModReponse[1].$preparedModReponse[3];
						}
						else if ( $preparedModReponse[0] == '[' && $preparedModReponse[3] == ')' ) { 
							$permut[] = $preparedModReponse;
							$permut[] = '('.$preparedModReponse[2].$preparedModReponse[1].']';
						}
						else if ( $preparedModReponse[0] == '(' && $preparedModReponse[3] == ']' ) { 
							$permut[] = $preparedModReponse;
							$permut[] = '['.$preparedModReponse[2].$preparedModReponse[1].')';
						}
						
						if ( $preparedModReponse[0] == '(' && $preparedModReponse[3] == ')' ) { // droite
							if (strlen($preparedReponse) == 2) {
								$reponseAnalysis[1] = 'Un nom de droite se note entre parenthèses.';
								return $reponseAnalysis;
							}
							else if (strlen($preparedReponse) == 4 && $preparedReponse[0] == '[' && $preparedReponse[3] == ']' ) {
								$reponseAnalysis[1] = 'La réponse attendue n\'est pas un segment, mais une droite.';
								return $reponseAnalysis;
							}
						}
						else if ( $preparedModReponse[0] == '[' && $preparedModReponse[3] == ']' ) { // segment
							if (strlen($preparedReponse) == 2) {
								$reponseAnalysis[1] = 'Un nom de segment se note entre crochets.';
								return $reponseAnalysis;
							}
							else if (strlen($preparedReponse) == 4 && $preparedReponse[0] == '(' && $preparedReponse[3] == ')' ) {
								$reponseAnalysis[1] = 'La réponse attendue n\'est pas une droite, mais un segment.';
								return $reponseAnalysis;
							}
						}
						else if ( ($preparedModReponse[0] == '(' && $preparedModReponse[3] == ']') || ($preparedModReponse[0] == '[' && $preparedModReponse[3] == ')') ) { // demi-droite
							if (strlen($preparedReponse) == 2) {
								$reponseAnalysis[1] = 'Une demi-droite se note entre un crochet et une parenthèse.';
								return $reponseAnalysis;
							}
							else if (strlen($preparedReponse) == 4 && $preparedReponse[0] == '(' && $preparedReponse[3] == ')' ) {
								$reponseAnalysis[1] = 'Une demi-droite se note entre un crochet et une parenthèse.';
								return $reponseAnalysis;
							}
						}
					}
					
					if ( in_array($preparedReponse, $permut) ) {
						$reponseAnalysis[0] = true;
						break;
					}
				}
				else if ($type == 'angle') {
					if (strlen($preparedModReponse) != 8) {
						throw new \Exception('Une erreur est survenue : la réponse attendue est un angle, mais la correction automatique ne propose pas une telle réponse.');
					}
					
					$permut = array();
					$permut[] = 'ang('.$preparedModReponse[4].$preparedModReponse[5].$preparedModReponse[6].')';
					$permut[] = 'ang('.$preparedModReponse[6].$preparedModReponse[5].$preparedModReponse[4].')';
					
					if (strlen($preparedReponse) != 8) {
						if (strlen($preparedReponse) == 6) {
							if ($preparedReponse[4] == $preparedModReponse[5]) {
								$reponseAnalysis[1] = 'Ta réponse semble juste, mais tu ne l\'as pas exprimée sous la bonne forme. La forme attendue est par exemple: <script type="math/mml"><math><mover><mrow><mtext>ABC</mtext></mrow><mo>^</mo></mover></math></script>.';
								return $reponseAnalysis;
							}
							else {
								$reponseAnalysis[1] = 'Un angle ne doit pas être désigné par le nom d\'un sommet, mais de 3. Par exemple : <script type="math/mml"><math><mover><mrow><mtext>ABC</mtext></mrow><mo>^</mo></mover></math></script>.';
								return $reponseAnalysis;
							}
						}
						else if (strlen($preparedReponse) == 3) {
							if ( in_array('ang('.$preparedReponse.')', $permut) ) {
								$reponseAnalysis[1] = 'Ta réponse semble juste, mais tu ne l\'as pas exprimée sous forme d\'angle. Utilise la touche "Angle".';
								return $reponseAnalysis;
							}
							else {
								$reponseAnalysis[1] = 'Ta réponse est fausse, et tu ne l\'as pas exprimée sous forme d\'angle. Utilise la touche "Angle".';
								return $reponseAnalysis;
							}
						}
					}
					else {
						if ( in_array($preparedReponse, $permut) ) {
							$reponseAnalysis[0] = true;
							break;
						}
					}
				}
				else if ($type == 'radio' || $type == 'checkbox' || $type == 'vignette' || $type == 'liste') {
					if ( $reponse->getContenu() == $mod_reponse->getContenu() ) {
						$reponseAnalysis[0] = true;
						break;
					}
				}
				else if ($type == 'tableau analyse') {
					if ( $reponse->getContenu() == $mod_reponse->getModBrique()->getContenu() ) {
						$reponseAnalysis[0] = true;
						break;
					}
				}
			}
		}
		return $reponseAnalysis;
	}
	
	/**
     * @info prépare un contenu pour une analyse par le math_validator
     */
	public function prepareForAnalysis($contenu)
	{
		// mathsml
		$contenu = preg_replace('#<msup><mrow>(.*)</mrow><mrow>(.*)</mrow></msup>#', '($1)^($2)', $contenu);
		$contenu = preg_replace('#<msub><mrow>(.*)</mrow><mrow>(.*)</mrow></msub>#', 'ind($1,$2)', $contenu);
		$contenu = preg_replace('#<mfrac><mrow>(.*)</mrow><mrow>(.*)</mrow></mfrac>#', '($1)/($2)', $contenu);
		$contenu = preg_replace('#<mover><mrow>(.*)</mrow><mo>\^</mo></mover>#', 'ang($1)', $contenu);
		$contenu = preg_replace('#<mover><mrow>(.*)</mrow><mo>-</mo></mover>#', 'barre($1)', $contenu);
		$contenu = preg_replace('#<mover><mrow>(.*)</mrow><mo>→</mo></mover>#', 'vect($1)', $contenu);
		$contenu = str_replace("×", '*', $contenu);
		$contenu = str_replace(',', '.', $contenu);
		$contenu = str_replace('<mn>', '', $contenu);
		$contenu = str_replace('</mn>', '', $contenu);
		$contenu = str_replace('<mi>', '', $contenu);
		$contenu = str_replace('</mi>', '', $contenu);
		$contenu = str_replace('<mo>', '', $contenu);
		$contenu = str_replace('</mo>', '', $contenu);
		$contenu = str_replace('<mtext>', '', $contenu);
		$contenu = str_replace('</mtext>', '', $contenu);
		$contenu = str_replace('<msqrt>', 'sqrt(', $contenu);
		$contenu = str_replace('</msqrt>', ')', $contenu);	
		
		// latex
		$contenu = preg_replace('#\\text{(.*)}#', '$1', $contenu);		
		$contenu = preg_replace('#\\\frac{(.*)}{(.*)}#', '($1)/($2)', $contenu);	
		$contenu = preg_replace('#\\\widehat{(.*)}#', 'ang($1)', $contenu);
		$contenu = preg_replace('#\\\overline{(.*)}#', 'barre($1)', $contenu);
		$contenu = preg_replace('#\\\vec{(.*)}#', 'vect($1)', $contenu);		
		$contenu = preg_replace('#\\\sqrt{(.*)}#', 'sqrt($1)', $contenu);		
		
		for($i=0;$i<=strlen($contenu)-1;$i++){
			if ($contenu[$i] == '*') {
				if ($i>0 && $i<strlen($contenu)-1) {	
					if (is_numeric($contenu[$i-1]) && ctype_alpha($contenu[$i+1])) {
						$contenu[$i] = ' ';
						$contenu = str_replace(' ','',$contenu);	
					}
					else if ( $contenu[$i-1] == ')' && $contenu[$i+1] == '(' ) {
						$contenu[$i] = ' ';
						$contenu = str_replace(' ','',$contenu);	
					}
					else if ( ctype_alpha($contenu[$i-1]) && $contenu[$i+1] == '(' ) {
						$contenu[$i] = ' ';
						$contenu = str_replace(' ','',$contenu);	
					}
					else if ( is_numeric($contenu[$i-1]) && $contenu[$i+1] == '(' ) {
						$contenu[$i] = ' ';
						$contenu = str_replace(' ','',$contenu);	
					}
				}
			}
		}
		
		return $contenu;
	}
	
	/**
     * @info analyse une expression contenant une variable en évaluant cette expression
     */
	public function analyzeExpression($preparedReponse, $preparedModReponse, $variable)
	{
		// throw new \Exception($preparedReponse.' et '.$preparedModReponse);
	
		$iterator_max = 15;
		$errors = array('divisionbyzero', 'internalerror', 'undefinedvariable', 'expectingaclosingbracket', 'operatorlacksoperand', 'anunexpectederroroccured', 'wrongnumberofarguments', 'unexpectedoperator', 'unexpectedcomma');
		
		if ( strpos($preparedReponse, 'e') !== false || strpos($preparedModReponse, 'e') !== false ) {
			$iterator = 0;
			for ($j = 0; $j<=100; $j++) {
				$var = rand(-100, 100)/10;
				$this->math_validator->evaluate($variable.' = '.$var);
				$evaluatedReponse = $this->math_validator->evaluate($preparedReponse);
				$evaluatedModReponse = $this->math_validator->evaluate($preparedModReponse);
				if ( !in_array($evaluatedReponse, $errors) && !in_array($evaluatedModReponse, $errors) ) {
					if ($evaluatedReponse == $evaluatedModReponse) {
						$iterator++;
						if ($iterator == $iterator_max) {
							break;
						}
					}
				}
			}
		}
		else {
			$iterator = 0;
			for ($j = 0; $j<=100; $j++) {
				$var = rand(-1000, 1000)/10;
				$this->math_validator->evaluate($variable.' = '.$var);
				$evaluatedReponse = $this->math_validator->evaluate($preparedReponse);
				$evaluatedModReponse = $this->math_validator->evaluate($preparedModReponse);
				if ( !in_array($evaluatedReponse, $errors) && !in_array($evaluatedModReponse, $errors) ) {
					if ($evaluatedReponse == $evaluatedModReponse) {
						$iterator++;
						if ($iterator == $iterator_max) {
							break;
						}
					}
				}
			}
		}
		
		return $iterator == $iterator_max;
	}
	
	
}