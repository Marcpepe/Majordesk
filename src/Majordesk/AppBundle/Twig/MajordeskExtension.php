<?php
namespace Majordesk\AppBundle\Twig;

class MajordeskExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'shuffle' => new \Twig_Filter_Method($this, 'shuffleFilter'),
            'json_decode' => new \Twig_Filter_Method($this, 'jsonDecodeFilter'),
            'json_encode' => new \Twig_Filter_Method($this, 'jsonEncodeFilter'),
            'equationize' => new \Twig_Filter_Method($this, 'equationizeFilter'),
            'initialize_tableau' => new \Twig_Filter_Method($this, 'initializeTableauFilter')
        );
    }

    public function shuffleFilter($list)
    {
		shuffle($list);
		return $list;
    }
	
	public function jsonDecodeFilter($str) {
		return json_decode($str, true);
	}
	
	public function jsonEncodeFilter($str) {
		return json_encode($str, true);
	}
	
	public function equationizeFilter($eqnArr) {
		$newArr = array();
		foreach($eqnArr as $key => $eqn) {
			$eqn = preg_replace('/(=|\\\ne|>|<|\\\geq|\\\leq)/', '&\0&', $eqn, 1);
			$newArr[$key] = $eqn;
		}
		return $newArr;
	}
	
	public function initializeTableauFilter($tableau) {
		foreach($tableau as $row_key => $row) {
			if ($row['type'] == 'signe') {
				foreach($row['contenu'] as $cell_key => $cell) {
					if ($cell['input'] == 1) {
						if ($cell_key%2 == 0 || $cell_key == 1) {
							$tableau[$row_key]['contenu'][$cell_key]['contenu'] = "";
						} else {
							$tableau[$row_key]['contenu'][$cell_key]['contenu'] = "+";
						}
					}
				}
			} else if ($row['type'] == 'variations') {
				foreach($row['contenu'] as $cell_key => $cell) {
					if ($cell['input'] == 1) {
						if ($cell['contenu'] == "%asc%" || $cell['contenu'] == "%desc%") {
							$tableau[$row_key]['contenu'][$cell_key]['contenu'] = "%asc%";
							$tableau[$row_key]['contenu'][$cell_key]['position'] = "milieu";
						} else {
							$tableau[$row_key]['contenu'][$cell_key]['contenu'] = "%vide%";
							$tableau[$row_key]['contenu'][$cell_key]['position'] = "haut";
							$tableau[$row_key]['contenu'][$cell_key]['positiong'] = "haut";
							$tableau[$row_key]['contenu'][$cell_key]['positiond'] = "haut";
							$tableau[$row_key]['contenu'][$cell_key]['borneg'] = "";
							$tableau[$row_key]['contenu'][$cell_key]['borned'] = "";
						}
					}
				}
			}
		}
		return $tableau;
	}
	

    public function getName()
    {
        return 'majordesk_extension';
    }
}