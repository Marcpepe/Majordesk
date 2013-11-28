<?php

namespace Majordesk\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModExercice
 *
 * @ORM\Table(name="modexercice")
 * @ORM\Entity(repositoryClass="Majordesk\AppBundle\Entity\ModExerciceRepository")
 */
class ModExercice
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
     * @var string
     *
     * @ORM\Column(name="flag", type="string", length=1)
     */
    private $flag;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="is_new", type="boolean")
     */
    private $is_new;
	
	/**
     * @var string
     *
     * @ORM\Column(name="niveau", type="string", length=1)
     */
    private $niveau;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="date_enregistrement", type="datetime")
     */
    private $date_enregistrement;
	
	/**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=1)
     */
    private $statut;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModQuestion", mappedBy="mod_exercice", cascade={"persist", "remove"})
	*/
	private $mod_questions;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\ModMacro", mappedBy="mod_exercice", cascade={"persist", "remove"})
	*/
	private $mod_macros;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Exercice", mappedBy="mod_exercice", cascade={"persist", "remove"})
	*/
	private $exercices;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\ModType", inversedBy="mod_exercices", cascade={"persist"})
	*/
	private $mod_type;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Programme", inversedBy="mod_exercices", cascade={"persist"})
	*/
	private $programme;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Matiere", inversedBy="mod_exercices", cascade={"persist"})
	*/
	private $matiere;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Chapitre", inversedBy="mod_exercices", cascade={"persist"})
	*/
	private $chapitre;
	
	/**
	* @ORM\ManyToOne(targetEntity="Majordesk\AppBundle\Entity\Partie", inversedBy="mod_exercices", cascade={"persist"})
	*/
	private $partie;
	
	/**
	* @ORM\OneToMany(targetEntity="Majordesk\AppBundle\Entity\Feedback", mappedBy="mod_exercice", cascade={"persist", "remove"})
	*/
	private $feedback;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	
	/**
     * Set flag
     *
     * @param string $flag
     * @return ModExercice
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    
        return $this;
    }

    /**
     * Get flag
     *
     * @return string 
     */
    public function getFlag()
    {
        return $this->flag;
    }
	
	/**
     * Set niveau
     *
     * @param string $niveau
     * @return ModExercice
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
    
        return $this;
    }

    /**
     * Get niveau
     *
     * @return string 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }
	
	/**
     * Set date_enregistrement
     *
     * @param \DateTime $date_enregistrement
     * @return Reponse
     */
    public function setDateEnregistrement($date_enregistrement)
    {
        $this->date_enregistrement = $date_enregistrement;
    
        return $this;
    }

    /**
     * Get dateEnregistrement
     *
     * @return \DateTime 
     */
    public function getDateEnregistrement()
    {
        return $this->date_enregistrement;
    }

	/**
     * update dateEnregistrement To Current Date
     */
    public function updateDateEnregistrementToCurrentDate()
    {
        $this->date_enregistrement = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
    }
	
	/**
     * Set statut
     *
     * @param string $statut
     * @return ModExercice
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }
	
	/**
     * Set is_new
     *
     * @param boolean $is_new
     * @return ModExercice
     */
    public function setIsNew($is_new)
    {
        $this->is_new = $is_new;
    
        return $this;
    }

    /**
     * Get is_new
     *
     * @return string 
     */
    public function getIsNew()
    {
        return $this->is_new;
    }
	
	/**
     * Set feedback
     *
     * @param string $feedback
     * @return Feedback
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;
    
        return $this;
    }

    /**
     * Get feedback
     *
     * @return string 
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * Add mod_question
     *
     * @param \Majordesk\AppBundle\Entity\ModQuestion $modQuestions
     * @return ModExercice
     */
    public function addModQuestion(\Majordesk\AppBundle\Entity\ModQuestion $modQuestion)
    {
        $this->mod_questions[] = $modQuestion;
		$modQuestion->setModExercice($this);
    
        return $this;
    }

    /**
     * Remove mod_question
     *
     * @param \Majordesk\AppBundle\Entity\ModQuestion $modQuestions
     */
    public function removeModQuestion(\Majordesk\AppBundle\Entity\ModQuestion $modQuestion)
    {
        $this->mod_questions->removeElement($modQuestion);
		$modQuestion->setModExercice(null);
    }

    /**
     * Get mod_questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModQuestions()
    {
        return $this->mod_questions;
    }
	
	/**
     * Get mod_questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModQuestionsOrderedByNumero()
    {
		$modQuestions = $this->mod_questions->toArray();
		usort($modQuestions, function($a, $b)
		{
			if ($a->getNumero() == $b->getNumero()) {
				return 0;
			}
			return ($a->getNumero() < $b->getNumero()) ? -1 : 1;
		});
		
		$newModQuestions = new \Doctrine\Common\Collections\ArrayCollection();
		foreach($modQuestions as $modQuestion)
		{
			$newModQuestions->add($modQuestion);
		}
		
		foreach($newModQuestions as $newModQuestion)
		{
			$newModQuestion->sortModElements();
		}
		
		return $newModQuestions;
    }
	
	/**
     * Get mod_questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrderedModQuestions()
    {
		$modQuestions = $this->mod_questions->toArray();
		usort($modQuestions, function($a, $b)
		{
			if ($a->getNumero() == $b->getNumero()) {
				return 0;
			}
			return ($a->getNumero() < $b->getNumero()) ? -1 : 1;
		});
		return $modQuestions;
	}
	
	/**
     * Add mod_macro
     *
     * @param \Majordesk\AppBundle\Entity\ModMacro $modMacro
     * @return ModExercice
     */
    public function addModMacro(\Majordesk\AppBundle\Entity\ModMacro $modMacro)
    {
        $this->mod_macros[] = $modMacro;
		$modMacro->setModExercice($this);
    
        return $this;
    }

    /**
     * Remove mod_macro
     *
     * @param \Majordesk\AppBundle\Entity\ModMacro $modMacro
     */
    public function removeModMacro(\Majordesk\AppBundle\Entity\ModMacro $modMacro)
    {
        $this->mod_macros->removeElement($modMacro);
		$modMacro->setModExercice(null);
    }

    /**
     * Get mod_macros
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModMacros()
    {
        return $this->mod_macros;
    }

    /**
     * Add exercice
     *
     * @param \Majordesk\AppBundle\Entity\Exercice $exercice
     * @return ModExercice
     */
    public function addExercice(\Majordesk\AppBundle\Entity\Exercice $exercice)
    {
        $this->exercices[] = $exercice;
    
        return $this;
    }

    /**
     * Remove exercice
     *
     * @param \Majordesk\AppBundle\Entity\Exercice $exercice
     */
    public function removeExercice(\Majordesk\AppBundle\Entity\Exercice $exercice)
    {
        $this->exercices->removeElement($exercice);
    }

    /**
     * Get exercices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExercices()
    {
        return $this->exercices;
    }
	
	/**
     * Set mod_type
     *
     * @param \Majordesk\AppBundle\Entity\ModType $mod_type
     * @return ModExercice
     */
    public function setModType(\Majordesk\AppBundle\Entity\ModType $mod_type = null)
    {
        $this->mod_type = $mod_type;
		$mod_type->addModExercice($this);
    
        return $this;
    }

    /**
     * Get mod_type
     *
     * @return \Majordesk\AppBundle\Entity\ModType 
     */
    public function getModType()
    {
        return $this->mod_type;
    }

    /**
     * Set programme
     *
     * @param \Majordesk\AppBundle\Entity\Programme $programme
     * @return ModExercice
     */
    public function setProgramme(\Majordesk\AppBundle\Entity\Programme $programme = null)
    {
        $this->programme = $programme;
		$programme->addModExercice($this);
    
        return $this;
    }

    /**
     * Get programme
     *
     * @return \Majordesk\AppBundle\Entity\Programme 
     */
    public function getProgramme()
    {
        return $this->programme;
    }

    /**
     * Set matiere
     *
     * @param \Majordesk\AppBundle\Entity\Matiere $matiere
     * @return ModExercice
     */
    public function setMatiere(\Majordesk\AppBundle\Entity\Matiere $matiere = null)
    {
        $this->matiere = $matiere;
		$matiere->addModExercice($this);
    
        return $this;
    }

    /**
     * Get matiere
     *
     * @return \Majordesk\AppBundle\Entity\Matiere 
     */
    public function getMatiere()
    {
        return $this->matiere;
    }

    /**
     * Set chapitre
     *
     * @param \Majordesk\AppBundle\Entity\Chapitre $chapitre
     * @return ModExercice
     */
    public function setChapitre(\Majordesk\AppBundle\Entity\Chapitre $chapitre = null)
    {
        $this->chapitre = $chapitre;
		$chapitre->addModExercice($this);
    
        return $this;
    }

    /**
     * Get chapitre
     *
     * @return \Majordesk\AppBundle\Entity\Chapitre 
     */
    public function getChapitre()
    {
        return $this->chapitre;
    }

    /**
     * Set partie
     *
     * @param \Majordesk\AppBundle\Entity\Partie $partie
     * @return ModExercice
     */
    public function setPartie(\Majordesk\AppBundle\Entity\Partie $partie = null)
    {
        $this->partie = $partie;
		$partie->addModExercice($this);
    
        return $this;
    }

    /**
     * Get partie
     *
     * @return \Majordesk\AppBundle\Entity\Partie 
     */
    public function getPartie()
    {
        return $this->partie;
    }
	
	/**
     * Get preview
     *
     * @return un aperçu de l'exercice 
     */
    public function getPreview($options)
    {
		$preview = '';
		$is_new = $this->is_new;
		
		if ($is_new == 0) {
			$preview .= '<div class="col-lg-11"><div class="text-center"><br><br>Visualisation non disponible</div></div><div class="col-lg-1">'.$options.'</div>';
		} else {
			$preview .= '<div class="col-lg-11">';
			foreach($this->getOrderedModQuestions() as $mod_question) {
				if ($mod_question->getType() != 'tutocours') {
					$preview .= '<div class="superbrique">';
					if ($mod_question->getType() == 'question') {
						$preview .= '<div class="col-xs-12 col-lg-1"><i class="icon-remove icon-large text-light-grey"></i> <span class="badge">0</span> <span class="visible-xs"><br><br></span> </div> <div class="col-lg-11">';
					} else if ($mod_question->getType() == 'entete') {
						$preview .= '<div class="col-md-offset-1 col-lg-11">';
					} else {
						$preview .= '<div class="col-lg-12">';
					}
					$mod_briques = $mod_question->getOrderedModBriques();
					foreach($mod_briques as $mod_brique) {
						if ($mod_brique->getType() == 'textnmaths') {
							$preview .= $mod_brique->getContenu();
						} else if ($mod_brique->getType() == 'retour_ligne') {
							$preview .= '<br>';
						} else if ($mod_brique->getType() == 'saut_ligne') {
							$preview .= '<br><br>';
						} else if ($mod_brique->getType() == 'case') {
							$preview .= ' <div class="case"></div> ';
						} else if ($mod_brique->getType() == 'case maths') {
							$preview .= ' <span class="mathquill-embedded-latex mathquill-update">'.$mod_brique->getContenu().'</span> ';
						} else if ($mod_brique->getType() == 'equations') {
							$equations = json_decode($mod_brique->getContenu());
							$preview .= '<br>$\begin{eqnarray*}';
							$i=1;
							foreach($equations as $equation) {
								if ($i > 1) { $preview .= "\\\\"; }
								$eqn = $equation->contenu;
								$eqn = preg_replace('/(=|\\\ne|>|<|\\\geq|\\\leq)/', '&\0&', $eqn, 1);
								$preview .= $eqn;
								$i++;
							}
							$preview .= '\end{eqnarray*}$<br>';
						} else if ($mod_brique->getType() == 'systeme') {
							$systeme = json_decode($mod_brique->getContenu());
							$preview .= '<br>$\begin{cases}';
							$i=1;
							foreach($systeme as $syst) {
								if ($i > 1) { $preview .= "\\\\"; }
								$preview .= $syst->contenu;
								$i++;
							}
							$preview .= '\end{cases}$<br>';
						} else if ($mod_brique->getType() == 'liste') {
							$liste = json_decode($mod_brique->getContenu());
							$preview .= '<br><ul>';
							foreach($liste as $point) {
								$preview .= '<li>'.$point->contenu.'</li>';
							}
							$preview .= '</ul><br>';
						} else if ($mod_brique->getType() == 'liste_ordonnee') {
							$liste = json_decode($mod_brique->getContenu());
							$preview .= '<br><ol>';
							foreach($liste as $point) {
								$preview .= '<li>'.$point->contenu.'</li>';
							}
							$preview .= '</ol><br>';
						} else if ($mod_brique->getType() == 'liste deroulante') {
							$liste = json_decode($mod_brique->getContenu());
							$preview .= '<select class="form-special"><option value=""> </option>';
							foreach($liste as $point) {
								$preview .= '<option>'.$point->contenu.'</option>';
							}
							$preview .= '</select>';
						} else if ($mod_brique->getType() == 'radio') {
							$radios = json_decode($mod_brique->getContenu());
							$preview .= '<br>';
							foreach($radios as $radio) {
								$preview .= '<div class="radio"><label><input type="radio" name="radio-'.$mod_brique->getId().'" />'.$radio->contenu.'</label></div>';
							}
							$preview .= '<br>';
						} else if ($mod_brique->getType() == 'checkbox') {
							$checkboxes = json_decode($mod_brique->getContenu());
							$preview .= '<br>';
							foreach($checkboxes as $checkbox) {
								$preview .= '<div class="checkbox"><label><input type="checkbox" />'.$checkbox->contenu.'</label></div>';
							}
							$preview .= '<br>';
						} else if ($mod_brique->getType() == 'vignettes') {
							$vignettes = json_decode($mod_brique->getContenu());
							$preview .= '<br><br>';
							foreach($vignettes as $vignette) {
								$preview .= '<div class="well cursor-move">'.$vignette->contenu.'</div>';
							}
							$preview .= '<br>';
						} else if ($mod_brique->getType() == 'tableau') {
							$tableau = json_decode($mod_brique->getContenu());
							$preview .= '<div class="table-responsive"><table class="table table-bordered"><tbody>';
							foreach($tableau as $row) {
								$preview .= '<tr>';
								foreach($row as $cell) {
									$preview .= '<td><div class=" text-center">';
									if ($cell->input == 1) {
										$preview .= '<div class="case is-input"></div>';
									} else {
										$preview .= $cell->contenu;
									}
									$preview .= '</div></td>';
								}
								$preview .= '</tr>';
							}
							$preview .= '</tbody></table></div>';
						} else if ($mod_brique->getType() == 'tableau analyse') {
							$tableau = json_decode($mod_brique->getContenu());
							$i_max=0; foreach($tableau as $row) { $i_max++; } // car count($tableau) ne fonctionne pas oO
							$preview .= '<div class="table-responsive"><table class="table table-borderless"><tbody>';
							$i=1;
							foreach($tableau as $row) {
								$j=1;
								if ( $row->type == "entete" ) {
									$preview .= '<tr';
									if ($i<$i_max) {$preview .= ' style="border-bottom:1px solid black"';} // #468847 #c09852
									$preview .= '>';
									foreach($row->contenu as $cell) {
										$preview .= '<td';
										if ($j==1) {$preview .= ' class="col-lg-2" style="border-right:1px solid black"';} // #468847 #c09852
										$preview .= '><div class=" text-center">';
										if ($cell->contenu != '') {
											$preview .= '$'.$cell->contenu.'$';
										}
										$preview .= '</div></td>';
										$j++;
									}
									$preview .= '</tr>';
								} else if ( $row->type == "signe" ) {
									$preview .= '<tr';
									if ($i<$i_max) {$preview .= ' style="border-bottom:1px solid black"';} // #468847 #c09852
									$preview .= '>';
									foreach($row->contenu as $cell) {
										if ($cell->contenu == '%valeur-nulle%') {
											$preview .= '<td style="height:100%;background-image: url(\'../img/maths/valeur-nulle-correction.png\');background-repeat:repeat-y;background-position:center;vertical-align:center"><div class="text-center">$0$</div></td>';
										} else if ($cell->contenu == '%valeur-interdite%') {
											$preview .= '<td style="height:100%;background-image: url(\'../img/maths/valeur-interdite-correction.png\');background-repeat:repeat-y;background-position:center;vertical-align:center"><div class="text-center"></div></td>';
										} else {
											$preview .= '<td';
											if ($j==1) {$preview .= ' class="col-lg-2" style="border-right:1px solid black"';} // #468847 #c09852
											$preview .= '><div class=" text-center">';
											if ($cell->contenu != '') {
												$preview .= '$'.$cell->contenu.'$';
											}
											$preview .= '</div></td>';
										}
										
										$j++;
									}
									$preview .= '</tr>';
								} else if ( $row->type == "variations" ) {
									$u_max=0; foreach($row->contenu as $u) { $u_max++; }
									$flecheType = $u_max/2-1;
									$preview .= '<tr';
									if ($i<$i_max) {$preview .= ' style="border-bottom:1px solid black"';} // #468847 #c09852
									$preview .= '>';
									foreach($row->contenu as $cell) {
										if ($cell->contenu == '%vide%') {
											$preview .= '<td style="padding-left:0;padding-right:0;"></td>';
										} else if ($cell->contenu == '%asc%') {
											$preview .= '<td style="padding-left:0;padding-right:0;">';
											if ($flecheType==1) {
												$preview .= '<div class="text-center"><img src="../img/maths/asc-1.png"/></div>';
											} else if ($cell->position == 'haut') {
												$preview .= '<div class="text-center"><img src="../img/maths/asc-haut-'.$flecheType.'.png"/></div>';
											} else if ($cell->position == 'milieu') {
												$preview .= '<div class="text-center"><img src="../img/maths/asc-milieu-'.$flecheType.'.png"/></div>';
											} else if ($cell->position == 'bas') {
												$preview .= '<div class="text-center"><img src="../img/maths/asc-bas-'.$flecheType.'.png"/></div>';
											}
											$preview .= '</td>';
										} else if ($cell->contenu == '%desc%') {
											$preview .= '<td style="padding-left:0;padding-right:0;">';
											if ($flecheType==1) {
												$preview .= '<div class="text-center"><img src="../img/maths/desc-1.png"/></div>';
											} else if ($cell->position == 'haut') {
												$preview .= '<div class="text-center"><img src="../img/maths/desc-haut-'.$flecheType.'.png"/></div>';
											} else if ($cell->position == 'milieu') {
												$preview .= '<div class="text-center"><img src="../img/maths/desc-milieu-'.$flecheType.'.png"/></div>';
											} else if ($cell->position == 'bas') {
												$preview .= '<div class="text-center"><img src="../img/maths/desc-bas-'.$flecheType.'.png"/></div>';
											}
											$preview .= '</td>';
										} else if ($cell->contenu == '%valeur-interdite%') {
											$preview .= '<td style="padding:0;height:100%;background-image: url(\'../img/maths/valeur-interdite-correction.png\');background-repeat:repeat-y;background-position:center;font-size:10px;">';
											$preview .= '<div class="text-center"><div class="display-inline-block" style="width:22px;';
											if ($cell->positiong == 'bas') {
												$preview .= 'position:relative;';
												if ($flecheType == 1) { $preview .= 'top:80px'; }
												else if ($flecheType == 2) { $preview .= 'top:80px'; }
												else if ($flecheType == 3) { $preview .= 'top:60px'; }
												else if ($flecheType == 4) { $preview .= 'top:40px'; }
											}
											$preview .= '">$'.$cell->borneg.'$</div><div class="display-inline-block" style="width:5px;"></div><div class="display-inline-block" style="width:22px;';
											if ($cell->positiond == 'bas') {
												$preview .= 'position:relative;';
												if ($flecheType == 1) { $preview .= 'top:80px'; }
												else if ($flecheType == 2) { $preview .= 'top:80px'; }
												else if ($flecheType == 3) { $preview .= 'top:60px'; }
												else if ($flecheType == 4) { $preview .= 'top:40px'; }
											}
											$preview .= '">$'.$cell->borneg.'$</div></div></td>';
										} else if ($cell->contenu == '%bg-interdite%') {
											$preview .= '<td style="padding:0;height:100%;background-image: url(\'../img/maths/valeur-interdite-correction.png\');background-repeat:repeat-y;background-position:left;font-size:10px;">';
											$preview .= '<div class="text-center"><div class="display-inline-block" style="width:12px;"></div><div class="display-inline-block" style="width:22px;';
											if ($cell->positiond == 'bas') {
												$preview .= 'position:relative;';
												if ($flecheType == 1) { $preview .= 'top:80px'; }
												else if ($flecheType == 2) { $preview .= 'top:80px'; }
												else if ($flecheType == 3) { $preview .= 'top:60px'; }
												else if ($flecheType == 4) { $preview .= 'top:40px'; }
											}
											$preview .= '">$'.$cell->borneg.'$</div></div></td>';
										} else if ($cell->contenu == '%bd-interdite%') {
											$preview .= '<td style="padding:0;height:100%;background-image: url(\'../img/maths/valeur-interdite-correction.png\');background-repeat:repeat-y;background-position:right;font-size:10px;">';
											$preview .= '<div class="text-center"><div class="display-inline-block" style="width:22px;';
											if ($cell->positiond == 'bas') {
												$preview .= 'position:relative;';
												if ($flecheType == 1) { $preview .= 'top:80px'; }
												else if ($flecheType == 2) { $preview .= 'top:80px'; }
												else if ($flecheType == 3) { $preview .= 'top:60px'; }
												else if ($flecheType == 4) { $preview .= 'top:40px'; }
											}
											$preview .= '">$'.$cell->borneg.'$</div><div class="display-inline-block" style="width:8px;"></div></div></td>';
										} else {
											$preview .= '<td';
											if ($j==1) {$preview .= ' class="col-lg-2" style="border-right:1px solid black"';} // #468847 #c09852
											$preview .= '><div class=" text-center" style="';
											if ($cell->position == 'milieu') {
												$preview .= 'position:relative;';
												if ($flecheType == 1) { $preview .= 'top:40px'; }
												else if ($flecheType == 2) { $preview .= 'top:40px'; }
												else if ($flecheType == 3) { $preview .= 'top:30px'; }
												else if ($flecheType == 4) { $preview .= 'top:20px'; }
											} else if ($cell->position == 'bas') {
												$preview .= 'position:relative;';
												if ($flecheType == 1) { $preview .= 'top:80px'; }
												else if ($flecheType == 2) { $preview .= 'top:80px'; }
												else if ($flecheType == 3) { $preview .= 'top:60px'; }
												else if ($flecheType == 4) { $preview .= 'top:40px'; }
											}
											$preview .= '">';
											if ($cell->contenu != '') {
												$preview .= '$'.$cell->contenu.'$';
											}
											$preview .= '</div></td>';
										}
										$j++;
									}
									$preview .= '</tr>';
								}
								$i++;
							}
							$preview .= '</tbody></table></div>';
						}
					}
					$preview .= '</div><div class="clearfix"></div><br></div>';
				}
			}
			// $preview .= '</div>';
			$preview .= '</div><div class="col-lg-1">'.$options.'</div>';
		}
        return $preview;;
    }
	
	/**
     * Constructor
     */
    public function __construct()
    {
		$this->flag = '0';
		$this->niveau = 0;
		$this->statut = '1';
		$this->is_new = true;
		$this->date_enregistrement = new \Datetime("now", new \DateTimeZone('Europe/Paris'));
        $this->mod_questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mod_macros = new \Doctrine\Common\Collections\ArrayCollection();
        $this->exercices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->competences = new \Doctrine\Common\Collections\ArrayCollection();
    }
}