	
	/**
	  * CLAVIERS
	  */
		
		/* Claviers popovers
		 * ================= */
		 
		$(document)
		.on('click', '.touche-extend', function() {
			clavierExtend($(this));
		})
		.on('click', '.touche-contract', function() {
			clavierContract($(this));
		})
		.on('click', '.mathscase[data-clavier="general"]', function(){
			clavierGeneral($(this));
		})
		.on('click', '.touche-autre', function() {
			clavierAutre($(this));
		})
		.on('click', '.mathscase[data-clavier="simple"]', function() {
			clavierSimple($(this));
		})
		.on('click', '.mathscase[data-clavier="point"]', function() {
			clavierPoint($(this));
		})
		.on('click', '.mathscase[data-clavier="avancee"]', function() {
			clavierAvancee($(this));
		});
		

		/* Fonctions
		 * ========= */
		
		function clavierExtend(currentCase) {
			currentCase.closest('div.popover-content').html(
							  '<div style="width:740px;">'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Pavé</span><br>'+
									'<button class="btn btn-primary custom-width touche-1" type="button">1</button>'+
									'<button class="btn btn-primary custom-width touche-2" type="button">2</button>'+
									'<button class="btn btn-primary custom-width touche-3" type="button"><mn>3</button><br>'+
									'<button class="btn btn-primary custom-width touche-4" type="button">4</button>'+
									'<button class="btn btn-primary custom-width touche-5" type="button">5</button>'+
									'<button class="btn btn-primary custom-width touche-6" type="button">6</button><br>'+
									'<button class="btn btn-primary custom-width touche-7" type="button">7</button>'+
									'<button class="btn btn-primary custom-width touche-8" type="button">8</button>'+
									'<button class="btn btn-primary custom-width touche-9" type="button">9</button><br>'+
									'<button class="btn btn-primary custom-width touche-virgule" type="button">,</button>'+
									'<button class="btn btn-primary custom-width touche-0" type="button">0</button>'+
									'<button class="btn btn-primary custom-width touche-x" type="button"><em>x</em></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Opérateurs</span><br>'+
									'<button class="btn btn-primary custom-width touche-plus" type="button">+</button>'+
									'<button class="btn btn-primary custom-width touche-moins" type="button">&minus;</button><br>'+
									'<button class="btn btn-primary custom-width touche-multiplie" type="button">&times;</button>'+
									'<button rel="tooltip" data-title="Fraction" data-placement="right" class="btn btn-primary custom-width touche-divise" type="button">&divide;</button><br>'+
									'<button rel="tooltip" data-title="Puissance" data-placement="left" class="btn btn-primary custom-width touche-puissance" type="button">^</button>'+
									'<button class="btn btn-primary custom-width touche-racine" type="button">&#8730;</button><br>'+
									'<button class="btn btn-primary custom-width touche-oparen" type="button">(</button>'+
									'<button class="btn btn-primary custom-width touche-fparen" type="button">)</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Vecteurs</span><br>'+
										'<button class="btn btn-primary custom-width touche-vecteur" type="button"><math><mover><mrow><mi>..</mi></mrow><mo>&rarr;</mo></mover></math></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Angles</span><br>'+
										'<button rel="tooltip" data-title="Angle" data-placement="right" class="btn btn-primary custom-width touche-angle" type="button"><math><mover><mrow><mi>...</mi></mrow><mo>^</mo></mover></math></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Constantes</span><br>'+
										'<button class="btn btn-primary custom-width touche-pi" type="button">&pi;</button>'+						
										'<button class="btn btn-primary custom-width touche-e" type="button"><em>e</em></button><br>'+
								'</span>'+
								'<span style="margin-right:10px;" class="clearfix pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Intervalles</span><br>'+
										'<button class="btn btn-primary custom-width touche-2R" type="button">IR</button>'+
										'<button class="btn btn-primary custom-width touche-point-virgule" type="button">;</button>'+
										'<button class="btn btn-primary custom-width touche-deux-points" type="button">:</button>'+
										'<button class="btn btn-primary custom-width touche-infini" type="button">&infin;</button>'+
										'<button class="btn btn-primary custom-width touche-crochetg" type="button">[</button>'+
										'<button class="btn btn-primary custom-width touche-crochetd" type="button">]</button>'+
								'</span>'+
							'<br><br><br>'+	
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Fonctions usuelles</span><br>'+
										'<button class="btn btn-primary custom-width touche-cosinus font-size-12" type="button">cos</button>'+								
										'<button class="btn btn-primary custom-width touche-sinus font-size-12" type="button">sin</button>'+								
										'<button class="btn btn-primary custom-width touche-tangente font-size-12" type="button">tan</button><br>'+								
										'<button class="btn btn-primary custom-width touche-exp font-size-12" type="button">exp</button>'+								
										'<button class="btn btn-primary custom-width touche-ln font-size-12" type="button">ln</button>'+	
										'<button class="btn btn-primary custom-width touche-log font-size-12" type="button">log</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Etude de fonctions</span><br>'+
										'<button rel="tooltip" data-title="Dérivée" data-placement="left" class="btn btn-primary custom-width touche-prime" type="button">&prime;</button>'+								
										'<button rel="tooltip" data-title="Dérivée seconde" data-placement="right" class="btn btn-primary custom-width touche-seconde" type="button">&prime;&prime;</button>'+
										'<button rel="tooltip" data-title="Valeur interdite" data-placement="left" class="btn btn-primary custom-width touche-valeur-interdite" type="button">||</button>'+	
										'<button class="btn btn-primary custom-width touche-composition" type="button">&compfn;</button><br>'+
										'<button class="btn btn-primary custom-width touche-pointe" type="button">&rarr;</button>'+
										'<button class="btn btn-primary custom-width touche-associe" type="button">&mapsto;</button>'+
										'<button class="btn btn-primary custom-width touche-croissante" type="button">↗</button>'+
										'<button class="btn btn-primary custom-width touche-decroissante" type="button">↘</button>'+
							    '</span>'+
								
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Limites</span><br>'+
										'<button class="btn btn-primary custom-width touche-infini" type="button">&infin;</button><br>'+
										'<button class="btn btn-primary touche-limite custom-width font-size-12" type="button">lim</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Suites</span><br>'+
										'<button rel="tooltip" data-title="Mettre en indice" class="btn btn-primary custom-width touche-indice" type="button">▾</button>'+
										'<button class="btn btn-primary custom-width touche-u-n" type="button">u<sub>n</sub></button>'+
										'<button class="btn btn-primary custom-width touche-v-n" type="button">v<sub>n</sub></button><br>'+
										'<button class="btn btn-primary custom-width touche-n" type="button">n</button>'+
										'<button class="btn btn-primary custom-width touche-2N" type="button">IN</button>'+
										'<button class="btn btn-primary custom-width touche-2Z" type="button">ZZ</button>'+
								'</span>'+
							'<br><br><br><br><br>'+	
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Signes</span><br>'+
										'<button class="btn btn-primary custom-width touche-egal" type="button">=</button>'+
										'<button class="btn btn-primary custom-width touche-different" type="button">&ne;</button>'+
										'<button class="btn btn-primary custom-width touche-sup" type="button">&ge;</button>'+
										'<button class="btn btn-primary custom-width touche-inf" type="button">&le;</button><br>'+
										'<button class="btn btn-primary custom-width touche-stsup" type="button">&gt;</button>'+
										'<button class="btn btn-primary custom-width touche-stinf" type="button">&lt;</button>'+
										'<button class="btn btn-primary custom-width touche-eegal" type="button">&asymp;</button>'+
										'<button class="btn btn-primary custom-width touche-pointvirgule" type="button">;</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Complexes</span><br>'+
										'<button class="btn btn-primary custom-width touche-z" type="button">z</button>'+
										'<button class="btn btn-primary custom-width touche-z-barre" type="button">z_</button>'+
										'<button class="btn btn-primary custom-width touche-i" type="button">i</button>'+
										'<button class="btn btn-primary custom-width touche-re" type="button">Re</button><br>'+
										'<button rel="tooltip" data-title="Module" data-placement="bottom" class="btn btn-primary custom-width touche-abs" type="button">|</button>'+
										'<button rel="tooltip" data-title="Barre" data-placement="bottom" class="btn btn-primary custom-width touche-barre" type="button">_</button>'+
										'<button class="btn btn-primary custom-width touche-arg" type="button">arg</button>'+
										'<button class="btn btn-primary custom-width touche-im" type="button">Im</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Prim/Intég</span><br>'+
										'<button class="btn btn-primary custom-width touche-primitive" type="button">&#x222B;</button>'+
										'<button class="btn btn-primary custom-width touche-integrale" type="button">&#x222B;<sub>a</sub><sup>b</sup></button><br>'+
										'<button class="btn btn-primary custom-width touche-somme" type="button">&sum;</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Notation de fonctions</span><br>'+
										'<button class="btn btn-primary custom-width touche-f-x" type="button">f(x)</button>'+
										'<button class="btn btn-primary custom-width touche-f" type="button">f</button>'+
										'<button class="btn btn-primary custom-width touche-fprime" type="button">f&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-fseconde" type="button">f&prime;&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-F" type="button">F</button><br>'+
										'<button class="btn btn-primary custom-width touche-g-x" type="button">g(x)</button>'+
										'<button class="btn btn-primary custom-width touche-g" type="button">g</button>'+
										'<button class="btn btn-primary custom-width touche-gprime" type="button">g&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-gseconde" type="button">g&prime;&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-G" type="button">G</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Vect/Matr</span><br>'+
										'<button rel="tooltip" data-title="Vecteurs (par leurs coefficients)" class="btn btn-primary custom-width font-size-12 touche-vect" type="button">vect</button><br>'+
										'<button rel="tooltip" data-title="Matrice" class="btn btn-primary custom-width font-size-12 touche-mat" type="button">mat</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Variables/Lettres</span><br>'+
										'<button class="btn btn-primary custom-width touche-a" type="button">a</button>'+
										'<button class="btn btn-primary custom-width touche-b" type="button">b</button>'+
										'<button class="btn btn-primary custom-width touche-c" type="button">c</button>'+
										'<button class="btn btn-primary custom-width touche-d" type="button">d</button>'+
										'<button class="btn btn-primary custom-width touche-e" type="button">e</button>'+
										'<button class="btn btn-primary custom-width touche-f" type="button">f</button>'+
										'<button class="btn btn-primary custom-width touche-g" type="button">g</button>'+
										'<button class="btn btn-primary custom-width touche-h" type="button">h</button>'+
										'<button class="btn btn-primary custom-width touche-i" type="button">i</button><br>'+
										'<button class="btn btn-primary custom-width touche-j" type="button">j</button>'+
										'<button class="btn btn-primary custom-width touche-k" type="button">k</button>'+
										'<button class="btn btn-primary custom-width touche-l" type="button">l</button>'+
										'<button class="btn btn-primary custom-width touche-m" type="button">m</button>'+
										'<button class="btn btn-primary custom-width touche-n" type="button">n</button>'+
										'<button class="btn btn-primary custom-width touche-o" type="button">o</button>'+
										'<button class="btn btn-primary custom-width touche-p" type="button">p</button>'+
										'<button class="btn btn-primary custom-width touche-q" type="button">q</button>'+
										'<button class="btn btn-primary custom-width touche-r" type="button">r</button><br>'+
										'<button class="btn btn-primary custom-width touche-s" type="button">s</button>'+
										'<button class="btn btn-primary custom-width touche-t" type="button">t</button>'+
										'<button class="btn btn-primary custom-width touche-u" type="button">u</button>'+
										'<button class="btn btn-primary custom-width touche-v" type="button">v</button>'+
										'<button class="btn btn-primary custom-width touche-w" type="button">w</button>'+
										'<button class="btn btn-primary custom-width touche-x" type="button">x</button>'+
										'<button class="btn btn-primary custom-width touche-y" type="button">y</button>'+
										'<button class="btn btn-primary custom-width touche-z" type="button">z</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Points/Lettres</span><br>'+
										'<button class="btn btn-primary custom-width touche-A" type="button">A</button>'+
										'<button class="btn btn-primary custom-width touche-B" type="button">B</button>'+
										'<button class="btn btn-primary custom-width touche-C" type="button">C</button>'+
										'<button class="btn btn-primary custom-width touche-D" type="button">D</button>'+
										'<button class="btn btn-primary custom-width touche-E" type="button">E</button>'+
										'<button class="btn btn-primary custom-width touche-F" type="button">F</button>'+
										'<button class="btn btn-primary custom-width touche-G" type="button">G</button>'+
										'<button class="btn btn-primary custom-width touche-H" type="button">H</button>'+
										'<button class="btn btn-primary custom-width touche-I" type="button">I</button><br>'+
										'<button class="btn btn-primary custom-width touche-J" type="button">J</button>'+
										'<button class="btn btn-primary custom-width touche-K" type="button">K</button>'+
										'<button class="btn btn-primary custom-width touche-L" type="button">L</button>'+
										'<button class="btn btn-primary custom-width touche-M" type="button">M</button>'+
										'<button class="btn btn-primary custom-width touche-N" type="button">N</button>'+
										'<button class="btn btn-primary custom-width touche-O" type="button">O</button>'+
										'<button class="btn btn-primary custom-width touche-P" type="button">P</button>'+
										'<button class="btn btn-primary custom-width touche-Q" type="button">Q</button>'+
										'<button class="btn btn-primary custom-width touche-R" type="button">R</button><br>'+
										'<button class="btn btn-primary custom-width touche-S" type="button">S</button>'+
										'<button class="btn btn-primary custom-width touche-T" type="button">T</button>'+
										'<button class="btn btn-primary custom-width touche-U" type="button">U</button>'+
										'<button class="btn btn-primary custom-width touche-V" type="button">V</button>'+
										'<button class="btn btn-primary custom-width touche-W" type="button">W</button>'+
										'<button class="btn btn-primary custom-width touche-X" type="button">X</button>'+
										'<button class="btn btn-primary custom-width touche-Y" type="button">Y</button>'+
										'<button class="btn btn-primary custom-width touche-Z" type="button">Z</button>'+
							    '</span>'+
							'</div>'+
							'<div class="pull-right">'+
								'<button rel="tooltip" data-title="Remettre à zéro" data-placement="bottom" class="btn btn-danger custom-width touche-reset position-relative-left-24" type="button"><i class="icon-remove text-white"></i></button>'+
								'<button rel="tooltip" data-title="Corriger" data-placement="bottom" class="btn btn-warning custom-width touche-corriger" type="button"><i class="icon-undo text-white"></i></button>'+
								'<button rel="tooltip" data-title="Valider" data-placement="right" class="btn btn-success custom-width touche-valider" type="button"><i class="icon-ok text-white"></i></button>'+
							'</div>'
								);
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
		}
		
		function clavierContract(currentCase) {
			if (currentCase.attr('data-extend') == 'simple') {
				currentCase.closest('div.popover-content').html(
								'<div class="pull-left" style="width:220px;">'+
								  '<span style="margin-right:10px;" class="pull-left">'+
									'<button class="btn btn-primary custom-width touche-1" type="button"><math><mn>1</mn></math></button>'+
									'<button class="btn btn-primary custom-width touche-2" type="button"><math><mn>2</mn></math></button>'+
									'<button class="btn btn-primary custom-width touche-3" type="button"><math><mn>3</mn></math></button><br>'+
									'<button class="btn btn-primary custom-width touche-4" type="button"><math><mn>4</mn></math></button>'+
									'<button class="btn btn-primary custom-width touche-5" type="button"><math><mn>5</mn></math></button>'+
									'<button class="btn btn-primary custom-width touche-6" type="button"><math><mn>6</mn></math></button><br>'+
									'<button class="btn btn-primary custom-width touche-7" type="button"><math><mn>7</mn></math></button>'+
									'<button class="btn btn-primary custom-width touche-8" type="button"><math><mn>8</mn></math></button>'+
									'<button class="btn btn-primary custom-width touche-9" type="button"><math><mn>9</mn></math></button><br>'+
									'<button class="btn btn-primary custom-width touche-virgule" type="button"><math><mn>,</mn></math></button>'+
									'<button class="btn btn-primary custom-width touche-0" type="button"><math><mn>0</mn></math></button>'+
									'<button class="btn btn-primary custom-width touche-x" type="button"><math><mi>x</mi></math></button>'+
								  '</span>'+
								  '<span style="margin-right:10px;" class="pull-left">'+
									'<button class="btn btn-primary custom-width touche-plus" type="button"><math><mo>+</mo></math></button>'+
									'<button class="btn btn-primary custom-width touche-moins" type="button"><math><mo>&minus;</mo></math></button><br>'+
									'<button class="btn btn-primary custom-width touche-multiplie" type="button"><math><mo>&times;</mo></math></button>'+
									'<button rel="tooltip" data-title="Fraction" data-placement="right" class="btn btn-primary custom-width touche-divise" type="button"><math><mo>&divide;</mo></math></button><br>'+
									'<button rel="tooltip" data-title="Puissance" data-placement="left" class="btn btn-primary custom-width touche-puissance" type="button"><math><mo>^</mo></math></button>'+
									'<button class="btn btn-primary custom-width touche-racine" type="button">&#8730;</button><br>'+
									'<button class="btn btn-primary custom-width touche-oparen" type="button"><math><mo>(</mo></math></button>'+
									'<button class="btn btn-primary custom-width touche-fparen" type="button"><math><mo>)</mo></math></button>'+
								  '</span>'+
								'</div>'+
								'<div style="margin-left:220px">'+
									'<button class="btn btn-mini btn-inverse touche-extend" data-contract="simple" type="button"><math><mo>+</mo></math></button>'+
								'</div>'+
								'<div style="margin-top:110px;">'+
									'<button rel="tooltip" data-title="Valider" data-placement="right" class="btn btn-success custom-width pull-right touche-valider" type="button"><i class="icon-ok text-white"></i></button>'+
									'<button rel="tooltip" data-title="Corriger" data-placement="bottom" class="btn btn-warning custom-width pull-right touche-corriger" type="button"><i class="icon-undo text-white"></i></button>'+
									'<button rel="tooltip" data-title="Remettre à zéro" data-placement="bottom" class="btn btn-danger custom-width pull-right touche-reset" type="button"><i class="icon-remove text-white"></i></button>'+
								'</div>'	
									);
			}
			else if (currentCase.attr('data-extend') == 'point') {
				currentCase.closest('div.popover-content').html(
							'<div class="pull-left" style="width:250px;">'+
							  '<span style="margin-right:10px;" class="pull-left">'+
								'<button class="btn btn-primary custom-width touche-A" type="button"><math><mtext>A</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-B" type="button"><math><mtext>B</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-C" type="button"><math><mtext>C</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-D" type="button"><math><mtext>D</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-E" type="button"><math><mtext>E</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-F" type="button"><math><mtext>F</mtext></math></button><br>'+
								'<button class="btn btn-primary custom-width touche-G" type="button"><math><mtext>G</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-H" type="button"><math><mtext>H</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-I" type="button"><math><mtext>I</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-J" type="button"><math><mtext>J</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-K" type="button"><math><mtext>K</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-L" type="button"><math><mtext>L</mtext></math></button><br>'+
								'<button class="btn btn-primary custom-width touche-M" type="button"><math><mtext>M</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-N" type="button"><math><mtext>N</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-O" type="button"><math><mtext>O</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-P" type="button"><math><mtext>P</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-Q" type="button"><math><mtext>Q</mtext></math></button>'+
								'<button class="btn btn-primary custom-width touche-R" type="button"><math><mtext>R</mtext></math></button>'+
							  '</span><br><br><br>'+
							  '<span style="margin-right:10px;">'+
								'<button rel="tooltip" data-title="Angle" data-placement="bottom" class="btn btn-primary custom-width touche-angle" type="button"><math><mover><mrow><mi>...</mi></mrow><mo>^</mo></mover></math></button>'+
								'<button class="btn btn-primary custom-width touche-oparen" type="button"><math><mo>(</mo></math></button>'+
								'<button class="btn btn-primary custom-width touche-fparen" type="button"><math><mo>)</mo></math></button>'+
								'<button class="btn btn-primary custom-width touche-crochetg" type="button"><math><mo>[</mo></math></button>'+
								'<button class="btn btn-primary custom-width touche-crochetd" type="button"><math><mo>]</mo></math></button>'+
							  '</span>'+
							'</div>'+
							'<div style="margin-left:250px">'+
								'<button class="btn btn-mini btn-inverse touche-extend" data-contract="point" type="button"><math><mo>+</mo></math></button>'+
							'</div>'+
							'<div style="margin-top:110px;">'+
								'<button rel="tooltip" data-title="Valider" data-placement="right" class="btn btn-success custom-width pull-right touche-valider" type="button"><i class="icon-ok text-white"></i></button>'+
								'<button rel="tooltip" data-title="Corriger" data-placement="bottom" class="btn btn-warning custom-width pull-right touche-corriger" type="button"><i class="icon-undo text-white"></i></button>'+
								'<button rel="tooltip" data-title="Remettre à zéro" data-placement="bottom" class="btn btn-danger custom-width pull-right touche-reset" type="button"><i class="icon-remove text-white"></i></button>'+
							'</div>'	
								);
			
			}
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
		}		
				
		function clavierGeneral(currentCase) {
			$('.popover').each( function() {
				if ( $(this).prev().attr('data-layer') == currentCase.attr('data-layer') ) {
					$(this).prev().not(currentCase).removeClass("case-focused").popover('destroy');
					$(this).remove();  // à enlever quand la méthode destroy de popover fonctionnera correctement
				}
			});
			currentCase.toggleClass("case-focused");
			currentCase.popover({
					trigger: 'manual',
					animation: false,
					placement: 'bottom',
					template: '<div class="popover"><!--<div class="arrow"></div>--><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>',
					html: 'true',
					// delay: { show: 500, hide: 2000 },
					content : <!--simple-->			
							  '<form class="form-inline"><div class="row"><div class="col-lg-6"><div class="pull-left">'+
								'<div style="margin-right:20px;" class="radio"><label><input type="radio" name="mathcolor" value="black" checked /> Noir</label></div>'+
								'<div style="margin-right:20px;" class="radio"><label><input type="radio" name="mathcolor" value="red" /> Rouge</label></div>'+
								'<div style="margin-right:20px;" class="radio"><label><input type="radio" name="mathcolor" value="#08da12" /> Vert</label></div>'+
								'<div style="margin-right:20px;" class="radio"><label><input type="radio" name="mathcolor" value="#0808fe" /> Bleu</label></div>'+
								'<div style="margin-right:20px;" class="radio"><label><input type="radio" name="mathcolor" value="#f6e324" /> Jaune</label></div>'+
							  '</div></div>'+
							  '<div class="col-lg-6"><div class="pull-right">'+
								'<div style="margin-right:20px;" class="radio"><label><input type="radio" name="mathvariant" value="normal" checked /> Normal</label></div>'+
								'<div style="margin-right:20px;" class="radio"><label><input type="radio" name="mathvariant" value="bold" /> <strong>Gras</strong></label></div>'+
							  '</div></div></div></form>'+
							  '<div style="width:740px;">'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Pavé</span><br>'+
									'<button class="btn btn-primary custom-width touche-1" type="button">1</button>'+
									'<button class="btn btn-primary custom-width touche-2" type="button">2</button>'+
									'<button class="btn btn-primary custom-width touche-3" type="button"><mn>3</button><br>'+
									'<button class="btn btn-primary custom-width touche-4" type="button">4</button>'+
									'<button class="btn btn-primary custom-width touche-5" type="button">5</button>'+
									'<button class="btn btn-primary custom-width touche-6" type="button">6</button><br>'+
									'<button class="btn btn-primary custom-width touche-7" type="button">7</button>'+
									'<button class="btn btn-primary custom-width touche-8" type="button">8</button>'+
									'<button class="btn btn-primary custom-width touche-9" type="button">9</button><br>'+
									'<button class="btn btn-primary custom-width touche-virgule" type="button">,</button>'+
									'<button class="btn btn-primary custom-width touche-0" type="button">0</button>'+
									'<button class="btn btn-primary custom-width touche-x" type="button"><em>x</em></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Opérateurs</span><br>'+
									'<button class="btn btn-primary custom-width touche-plus" type="button">+</button>'+
									'<button class="btn btn-primary custom-width touche-moins" type="button">&minus;</button><br>'+
									'<button class="btn btn-primary custom-width touche-multiplie" type="button">&times;</button>'+
									'<button rel="tooltip" data-title="Fraction" data-placement="right" class="btn btn-primary custom-width touche-divise" type="button">&divide;</button><br>'+
									'<button rel="tooltip" data-title="Puissance" data-placement="left" class="btn btn-primary custom-width touche-puissance" type="button">^</button>'+
									'<button class="btn btn-primary custom-width touche-racine" type="button">&#8730;</button><br>'+
									'<button class="btn btn-primary custom-width touche-oparen" type="button">(</button>'+
									'<button class="btn btn-primary custom-width touche-fparen" type="button">)</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Vecteurs</span><br>'+
										'<button class="btn btn-primary custom-width touche-vecteur" type="button"><math><mover><mrow><mi>..</mi></mrow><mo>&rarr;</mo></mover></math></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Angles</span><br>'+
										'<button rel="tooltip" data-title="Angle" data-placement="right" class="btn btn-primary custom-width touche-angle" type="button"><math><mover><mrow><mi>...</mi></mrow><mo>^</mo></mover></math></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Constantes</span><br>'+
										'<button class="btn btn-primary custom-width touche-pi" type="button">&pi;</button>'+						
										'<button class="btn btn-primary custom-width touche-e" type="button"><em>e</em></button><br>'+
								'</span>'+
								'<span style="margin-right:10px;" class="clearfix pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Intervalles</span><br>'+
										'<button class="btn btn-primary custom-width touche-2R" type="button">IR</button>'+
										'<button class="btn btn-primary custom-width touche-point-virgule" type="button">;</button>'+
										'<button class="btn btn-primary custom-width touche-deux-points" type="button">:</button>'+
										'<button class="btn btn-primary custom-width touche-infini" type="button">&infin;</button>'+
										'<button class="btn btn-primary custom-width touche-crochetg" type="button">[</button>'+
										'<button class="btn btn-primary custom-width touche-crochetd" type="button">]</button>'+
								'</span>'+
							'<br><br><br>'+	
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Fonctions usuelles</span><br>'+
										'<button class="btn btn-primary custom-width touche-cosinus font-size-12" type="button">cos</button>'+								
										'<button class="btn btn-primary custom-width touche-sinus font-size-12" type="button">sin</button>'+								
										'<button class="btn btn-primary custom-width touche-tangente font-size-12" type="button">tan</button><br>'+								
										'<button class="btn btn-primary custom-width touche-exp font-size-12" type="button">exp</button>'+								
										'<button class="btn btn-primary custom-width touche-ln font-size-12" type="button">ln</button>'+	
										'<button class="btn btn-primary custom-width touche-log font-size-12" type="button">log</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Etude de fonctions</span><br>'+
										'<button rel="tooltip" data-title="Dérivée" data-placement="left" class="btn btn-primary custom-width touche-prime" type="button">&prime;</button>'+								
										'<button rel="tooltip" data-title="Dérivée seconde" data-placement="right" class="btn btn-primary custom-width touche-seconde" type="button">&prime;&prime;</button>'+
										'<button rel="tooltip" data-title="Valeur interdite" data-placement="left" class="btn btn-primary custom-width touche-valeur-interdite" type="button">||</button>'+	
										'<button class="btn btn-primary custom-width touche-composition" type="button">&compfn;</button><br>'+
										'<button class="btn btn-primary custom-width touche-pointe" type="button">&rarr;</button>'+
										'<button class="btn btn-primary custom-width touche-associe" type="button">&mapsto;</button>'+
										'<button class="btn btn-primary custom-width touche-croissante" type="button">↗</button>'+
										'<button class="btn btn-primary custom-width touche-decroissante" type="button">↘</button>'+
							    '</span>'+
								
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Limites</span><br>'+
										'<button class="btn btn-primary custom-width touche-infini" type="button">&infin;</button><br>'+
										'<button class="btn btn-primary touche-limite custom-width font-size-12" type="button">lim</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Suites</span><br>'+
										'<button rel="tooltip" data-title="Mettre en indice" class="btn btn-primary custom-width touche-indice" type="button">▾</button>'+
										'<button class="btn btn-primary custom-width touche-u-n" type="button">u<sub>n</sub></button>'+
										'<button class="btn btn-primary custom-width touche-v-n" type="button">v<sub>n</sub></button><br>'+
										'<button class="btn btn-primary custom-width touche-n" type="button">n</button>'+
										'<button class="btn btn-primary custom-width touche-2N" type="button">IN</button>'+
										'<button class="btn btn-primary custom-width touche-2Z" type="button">ZZ</button>'+
								'</span>'+
							'<br><br><br><br><br>'+	
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Signes</span><br>'+
										'<button class="btn btn-primary custom-width touche-egal" type="button">=</button>'+
										'<button class="btn btn-primary custom-width touche-different" type="button">&ne;</button>'+
										'<button class="btn btn-primary custom-width touche-sup" type="button">&ge;</button>'+
										'<button class="btn btn-primary custom-width touche-inf" type="button">&le;</button><br>'+
										'<button class="btn btn-primary custom-width touche-stsup" type="button">&gt;</button>'+
										'<button class="btn btn-primary custom-width touche-stinf" type="button">&lt;</button>'+
										'<button class="btn btn-primary custom-width touche-eegal" type="button">&asymp;</button>'+
										'<button class="btn btn-primary custom-width touche-pointvirgule" type="button">;</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Complexes</span><br>'+
										'<button class="btn btn-primary custom-width touche-z" type="button">z</button>'+
										'<button class="btn btn-primary custom-width touche-z-barre" type="button">z_</button>'+
										'<button class="btn btn-primary custom-width touche-i" type="button">i</button>'+
										'<button class="btn btn-primary custom-width touche-re" type="button">Re</button><br>'+
										'<button rel="tooltip" data-title="Module" data-placement="bottom" class="btn btn-primary custom-width touche-abs" type="button">|</button>'+
										'<button rel="tooltip" data-title="Barre" data-placement="bottom" class="btn btn-primary custom-width touche-barre" type="button">_</button>'+
										'<button class="btn btn-primary custom-width touche-arg" type="button">arg</button>'+
										'<button class="btn btn-primary custom-width touche-im" type="button">Im</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Prim/Intég</span><br>'+
										'<button class="btn btn-primary custom-width touche-primitive" type="button">&#x222B;</button>'+
										'<button class="btn btn-primary custom-width touche-integrale" type="button">&#x222B;<sub>a</sub><sup>b</sup></button><br>'+
										'<button class="btn btn-primary custom-width touche-somme" type="button">&sum;</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Notation de fonctions</span><br>'+
										'<button class="btn btn-primary custom-width touche-f-x" type="button">f(x)</button>'+
										'<button class="btn btn-primary custom-width touche-f" type="button">f</button>'+
										'<button class="btn btn-primary custom-width touche-fprime" type="button">f&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-fseconde" type="button">f&prime;&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-F" type="button">F</button><br>'+
										'<button class="btn btn-primary custom-width touche-g-x" type="button">g(x)</button>'+
										'<button class="btn btn-primary custom-width touche-g" type="button">g</button>'+
										'<button class="btn btn-primary custom-width touche-gprime" type="button">g&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-gseconde" type="button">g&prime;&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-G" type="button">G</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Vect/Matr</span><br>'+
										'<button rel="tooltip" data-title="Vecteurs (par leurs coefficients)" class="btn btn-primary custom-width font-size-12 touche-vect" type="button">vect</button><br>'+
										'<button rel="tooltip" data-title="Matrice" class="btn btn-primary custom-width font-size-12 touche-mat" type="button">mat</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Variables/Lettres</span><br>'+
										'<button class="btn btn-primary custom-width touche-a" type="button">a</button>'+
										'<button class="btn btn-primary custom-width touche-b" type="button">b</button>'+
										'<button class="btn btn-primary custom-width touche-c" type="button">c</button>'+
										'<button class="btn btn-primary custom-width touche-d" type="button">d</button>'+
										'<button class="btn btn-primary custom-width touche-e" type="button">e</button>'+
										'<button class="btn btn-primary custom-width touche-f" type="button">f</button>'+
										'<button class="btn btn-primary custom-width touche-g" type="button">g</button>'+
										'<button class="btn btn-primary custom-width touche-h" type="button">h</button>'+
										'<button class="btn btn-primary custom-width touche-i" type="button">i</button><br>'+
										'<button class="btn btn-primary custom-width touche-j" type="button">j</button>'+
										'<button class="btn btn-primary custom-width touche-k" type="button">k</button>'+
										'<button class="btn btn-primary custom-width touche-l" type="button">l</button>'+
										'<button class="btn btn-primary custom-width touche-m" type="button">m</button>'+
										'<button class="btn btn-primary custom-width touche-n" type="button">n</button>'+
										'<button class="btn btn-primary custom-width touche-o" type="button">o</button>'+
										'<button class="btn btn-primary custom-width touche-p" type="button">p</button>'+
										'<button class="btn btn-primary custom-width touche-q" type="button">q</button>'+
										'<button class="btn btn-primary custom-width touche-r" type="button">r</button><br>'+
										'<button class="btn btn-primary custom-width touche-s" type="button">s</button>'+
										'<button class="btn btn-primary custom-width touche-t" type="button">t</button>'+
										'<button class="btn btn-primary custom-width touche-u" type="button">u</button>'+
										'<button class="btn btn-primary custom-width touche-v" type="button">v</button>'+
										'<button class="btn btn-primary custom-width touche-w" type="button">w</button>'+
										'<button class="btn btn-primary custom-width touche-x" type="button">x</button>'+
										'<button class="btn btn-primary custom-width touche-y" type="button">y</button>'+
										'<button class="btn btn-primary custom-width touche-z" type="button">z</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Points/Lettres</span><br>'+
										'<button class="btn btn-primary custom-width touche-A" type="button">A</button>'+
										'<button class="btn btn-primary custom-width touche-B" type="button">B</button>'+
										'<button class="btn btn-primary custom-width touche-C" type="button">C</button>'+
										'<button class="btn btn-primary custom-width touche-D" type="button">D</button>'+
										'<button class="btn btn-primary custom-width touche-E" type="button">E</button>'+
										'<button class="btn btn-primary custom-width touche-F" type="button">F</button>'+
										'<button class="btn btn-primary custom-width touche-G" type="button">G</button>'+
										'<button class="btn btn-primary custom-width touche-H" type="button">H</button>'+
										'<button class="btn btn-primary custom-width touche-I" type="button">I</button><br>'+
										'<button class="btn btn-primary custom-width touche-J" type="button">J</button>'+
										'<button class="btn btn-primary custom-width touche-K" type="button">K</button>'+
										'<button class="btn btn-primary custom-width touche-L" type="button">L</button>'+
										'<button class="btn btn-primary custom-width touche-M" type="button">M</button>'+
										'<button class="btn btn-primary custom-width touche-N" type="button">N</button>'+
										'<button class="btn btn-primary custom-width touche-O" type="button">O</button>'+
										'<button class="btn btn-primary custom-width touche-P" type="button">P</button>'+
										'<button class="btn btn-primary custom-width touche-Q" type="button">Q</button>'+
										'<button class="btn btn-primary custom-width touche-R" type="button">R</button><br>'+
										'<button class="btn btn-primary custom-width touche-S" type="button">S</button>'+
										'<button class="btn btn-primary custom-width touche-T" type="button">T</button>'+
										'<button class="btn btn-primary custom-width touche-U" type="button">U</button>'+
										'<button class="btn btn-primary custom-width touche-V" type="button">V</button>'+
										'<button class="btn btn-primary custom-width touche-W" type="button">W</button>'+
										'<button class="btn btn-primary custom-width touche-X" type="button">X</button>'+
										'<button class="btn btn-primary custom-width touche-Y" type="button">Y</button>'+
										'<button class="btn btn-primary custom-width touche-Z" type="button">Z</button>'+
							    '</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Développeur</span><br>'+
										'<button class="btn btn-primary custom-width touche-espace" type="button">&lt;space&gt;</button>'+
										'<button class="btn btn-primary custom-width touche-retour-ligne" type="button">&lt;br&gt;</button>'+
										'<button class="btn btn-primary custom-width touche-align" type="button">&lt;align&gt;</button>'+
										'<button class="btn btn-primary custom-width touche-otparen" type="button">(</button>'+
										'<button class="btn btn-primary custom-width touche-ftparen" type="button">)</button>'+
										'<button class="btn btn-primary custom-width touche-crochetg" type="button">[</button>'+
										'<button class="btn btn-primary custom-width touche-crochetd-bornes" type="button">]</button>'+
										'<button class="btn btn-primary custom-width touche-acolg" type="button">{</button>'+
										'<button class="btn btn-primary custom-width touche-acold" type="button">}</button>'+
										'<button class="btn btn-primary custom-width touche-etoile" type="button">*</button>'+
										'<button class="btn btn-primary custom-width touche-plusmoins" type="button">±</button><br>'+
										'<button class="btn btn-primary custom-width touche-acol2" type="button">{2</button>'+
										'<button class="btn btn-primary custom-width touche-acol3" type="button">{3</button>'+
										'<button class="btn btn-primary custom-width touche-acol-bas" type="button">&#xFE38;</button>'+
										'<button class="btn btn-primary custom-width touche-3points" type="button">...</button>'+
										'<button class="btn btn-primary custom-width touche-autre" type="button">autre</button>'+
									'</span>'+
							'</div>'+
							'<div class="pull-right">'+
								'<button rel="tooltip" data-title="Remettre à zéro" data-placement="bottom" class="btn btn-danger custom-width touche-reset position-relative-left-24" type="button"><i class="icon-remove text-white"></i></button>'+
								'<button rel="tooltip" data-title="Corriger" data-placement="bottom" class="btn btn-warning custom-width touche-corriger" type="button"><i class="icon-undo text-white"></i></button>'+
								'<button rel="tooltip" data-title="Valider" data-placement="right" class="btn btn-success custom-width touche-valider" type="button"><i class="icon-ok text-white"></i></button>'+
							'</div>'	
			}).popover('toggle');
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
		}
		
		function clavierAutre(currentCase) {
			// currentCase = $(this);
			currentCase.popover({
					trigger: 'manual',
					animation: false,
					placement: 'bottom',
					html: 'true',
					// delay: { show: 500, hide: 2000 },
					content : <!--simple-->
							  '<span>'+
								'<button class="btn btn-primary custom-width touche-abs" type="button"><math><mo>|</mo></math></button>'+
								'<button class="btn btn-primary custom-width touche-degre" type="button">&deg;</button>'+
								'<button class="btn btn-primary custom-width touche-infini" type="button">&infin;</button>'+
								'<button class="btn btn-primary custom-width touche-implication" type="button">&rArr;</button>'+
								'<button class="btn btn-primary custom-width touche-equivalence" type="button">&hArr;</button>'+
								'<button class="btn btn-primary custom-width touche-pourtout" type="button">&forall;</button>'+
								'<button class="btn btn-primary custom-width touche-existence" type="button">&exist;</button>'+
								'<button class="btn btn-primary custom-width touche-ensemble-vide" type="button">&empty;</button>'+
								'<button class="btn btn-primary custom-width touche-isin" type="button">&isin;</button>'+
								'<button class="btn btn-primary custom-width touche-notin" type="button">&notin;</button>'+
								'<button class="btn btn-primary custom-width touche-inclusion" type="button">&sub;</button>'+
								'<button class="btn btn-primary custom-width touche-cap" type="button">&cap;</button>'+
								'<button class="btn btn-primary custom-width touche-cup" type="button">&cup;</button>'+
								'<button class="btn btn-primary custom-width touche-prive" type="button">&ssetmn;</button><br>'+
								'<button class="btn btn-primary custom-width touche-alpha" type="button">&alpha;</button>'+
								'<button class="btn btn-primary custom-width touche-beta" type="button">&beta;</button>'+
								'<button class="btn btn-primary custom-width touche-epsilon" type="button">&epsilon;</button>'+
								'<button class="btn btn-primary custom-width touche-theta" type="button">&theta;</button>'+
								'<button class="btn btn-primary custom-width touche-lambda" type="button">&lambda;</button>'+
								'<button class="btn btn-primary custom-width touche-mu" type="button">&mu;</button>'+
								'<button class="btn btn-primary custom-width touche-pi" type="button">&pi;</button>'+
								'<button class="btn btn-primary custom-width touche-tau" type="button">&tau;</button>'+
								'<button class="btn btn-primary custom-width touche-omega" type="button">&omega;</button>'+
								'<button class="btn btn-primary custom-width touche-delta" type="button">&delta;</button>'+
								'<button class="btn btn-primary custom-width touche-Delta" type="button">&Delta;</button>'+
								'<button class="btn btn-primary custom-width touche-Omega" type="button">&Omega;</button>'+
								'<button class="btn btn-primary custom-width touche-prod" type="button">&prod;</button>'+
							  '</span>'						  
			}).popover('toggle');
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
		}
		
		function clavierSimple(currentCase) {
			$('.popover').each( function() {
				if ( $(this).prev().attr('data-layer') == currentCase.attr('data-layer') ) {
					$(this).prev().not(currentCase).removeClass("case-focused").popover('destroy');
					$(this).remove();  // à enlever quand la méthode destroy de popover fonctionnera correctement
				}
			});
			currentCase.toggleClass("case-focused");
			currentCase.popover({
					trigger: 'manual',
					animation: true,
					placement: 'bottom',
					template: '<div class="popover"><!--<div class="arrow"></div>--><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>',
					html: 'true',
					// delay: { show: 500, hide: 2000 },
					content : <!--simple-->
							'<div style="width:740px;">'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Pavé</span><br>'+
									'<button class="btn btn-primary custom-width touche-1" type="button">1</button>'+
									'<button class="btn btn-primary custom-width touche-2" type="button">2</button>'+
									'<button class="btn btn-primary custom-width touche-3" type="button"><mn>3</button><br>'+
									'<button class="btn btn-primary custom-width touche-4" type="button">4</button>'+
									'<button class="btn btn-primary custom-width touche-5" type="button">5</button>'+
									'<button class="btn btn-primary custom-width touche-6" type="button">6</button><br>'+
									'<button class="btn btn-primary custom-width touche-7" type="button">7</button>'+
									'<button class="btn btn-primary custom-width touche-8" type="button">8</button>'+
									'<button class="btn btn-primary custom-width touche-9" type="button">9</button><br>'+
									'<button class="btn btn-primary custom-width touche-virgule" type="button">,</button>'+
									'<button class="btn btn-primary custom-width touche-0" type="button">0</button>'+
									'<button class="btn btn-primary custom-width touche-x" type="button"><em>x</em></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Opérateurs</span><br>'+
									'<button class="btn btn-primary custom-width touche-plus" type="button">+</button>'+
									'<button class="btn btn-primary custom-width touche-moins" type="button">&minus;</button><br>'+
									'<button class="btn btn-primary custom-width touche-multiplie" type="button">&times;</button>'+
									'<button rel="tooltip" data-title="Fraction" data-placement="right" class="btn btn-primary custom-width touche-divise" type="button">&divide;</button><br>'+
									'<button rel="tooltip" data-title="Puissance" data-placement="left" class="btn btn-primary custom-width touche-puissance" type="button">^</button>'+
									'<button class="btn btn-primary custom-width touche-racine" type="button">&#8730;</button><br>'+
									'<button class="btn btn-primary custom-width touche-oparen" type="button">(</button>'+
									'<button class="btn btn-primary custom-width touche-fparen" type="button">)</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Vecteurs</span><br>'+
										'<button class="btn btn-primary custom-width touche-vecteur" type="button"><math><mover><mrow><mi>..</mi></mrow><mo>&rarr;</mo></mover></math></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Angles</span><br>'+
										'<button rel="tooltip" data-title="Angle" data-placement="right" class="btn btn-primary custom-width touche-angle" type="button"><math><mover><mrow><mi>...</mi></mrow><mo>^</mo></mover></math></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Constantes</span><br>'+
										'<button class="btn btn-primary custom-width touche-pi" type="button">&pi;</button>'+						
										'<button class="btn btn-primary custom-width touche-e" type="button"><em>e</em></button><br>'+
								'</span>'+
								'<span style="margin-right:10px;" class="clearfix pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Intervalles</span><br>'+
										'<button class="btn btn-primary custom-width touche-2R" type="button">IR</button>'+
										'<button class="btn btn-primary custom-width touche-point-virgule" type="button">;</button>'+
										'<button class="btn btn-primary custom-width touche-deux-points" type="button">:</button>'+
										'<button class="btn btn-primary custom-width touche-infini" type="button">&infin;</button>'+
										'<button class="btn btn-primary custom-width touche-crochetg" type="button">[</button>'+
										'<button class="btn btn-primary custom-width touche-crochetd" type="button">]</button>'+
								'</span>'+
							'<br><br><br>'+	
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Fonctions usuelles</span><br>'+
										'<button class="btn btn-primary custom-width touche-cosinus font-size-12" type="button">cos</button>'+								
										'<button class="btn btn-primary custom-width touche-sinus font-size-12" type="button">sin</button>'+								
										'<button class="btn btn-primary custom-width touche-tangente font-size-12" type="button">tan</button><br>'+								
										'<button class="btn btn-primary custom-width touche-exp font-size-12" type="button">exp</button>'+								
										'<button class="btn btn-primary custom-width touche-ln font-size-12" type="button">ln</button>'+	
										'<button class="btn btn-primary custom-width touche-log font-size-12" type="button">log</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Etude de fonctions</span><br>'+
										'<button rel="tooltip" data-title="Dérivée" data-placement="left" class="btn btn-primary custom-width touche-prime" type="button">&prime;</button>'+								
										'<button rel="tooltip" data-title="Dérivée seconde" data-placement="right" class="btn btn-primary custom-width touche-seconde" type="button">&prime;&prime;</button>'+
										'<button rel="tooltip" data-title="Valeur interdite" data-placement="left" class="btn btn-primary custom-width touche-valeur-interdite" type="button">||</button>'+	
										'<button class="btn btn-primary custom-width touche-composition" type="button">&compfn;</button><br>'+
										'<button class="btn btn-primary custom-width touche-pointe" type="button">&rarr;</button>'+
										'<button class="btn btn-primary custom-width touche-associe" type="button">&mapsto;</button>'+
										'<button class="btn btn-primary custom-width touche-croissante" type="button">↗</button>'+
										'<button class="btn btn-primary custom-width touche-decroissante" type="button">↘</button>'+
							    '</span>'+
								
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Limites</span><br>'+
										'<button class="btn btn-primary custom-width touche-infini" type="button">&infin;</button><br>'+
										'<button class="btn btn-primary touche-limite custom-width font-size-12" type="button">lim</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Suites</span><br>'+
										'<button rel="tooltip" data-title="Mettre en indice" class="btn btn-primary custom-width touche-indice" type="button">▾</button>'+
										'<button class="btn btn-primary custom-width touche-u-n" type="button">u<sub>n</sub></button>'+
										'<button class="btn btn-primary custom-width touche-v-n" type="button">v<sub>n</sub></button><br>'+
										'<button class="btn btn-primary custom-width touche-n" type="button">n</button>'+
										'<button class="btn btn-primary custom-width touche-2N" type="button">IN</button>'+
										'<button class="btn btn-primary custom-width touche-2Z" type="button">ZZ</button>'+
								'</span>'+
							'<br><br><br><br><br>'+	
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Signes</span><br>'+
										'<button class="btn btn-primary custom-width touche-egal" type="button">=</button>'+
										'<button class="btn btn-primary custom-width touche-different" type="button">&ne;</button>'+
										'<button class="btn btn-primary custom-width touche-sup" type="button">&ge;</button>'+
										'<button class="btn btn-primary custom-width touche-inf" type="button">&le;</button><br>'+
										'<button class="btn btn-primary custom-width touche-stsup" type="button">&gt;</button>'+
										'<button class="btn btn-primary custom-width touche-stinf" type="button">&lt;</button>'+
										'<button class="btn btn-primary custom-width touche-eegal" type="button">&asymp;</button>'+
										'<button class="btn btn-primary custom-width touche-pointvirgule" type="button">;</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Complexes</span><br>'+
										'<button class="btn btn-primary custom-width touche-z" type="button">z</button>'+
										'<button class="btn btn-primary custom-width touche-z-barre" type="button">z_</button>'+
										'<button class="btn btn-primary custom-width touche-i" type="button">i</button>'+
										'<button class="btn btn-primary custom-width touche-re" type="button">Re</button><br>'+
										'<button rel="tooltip" data-title="Module" data-placement="bottom" class="btn btn-primary custom-width touche-abs" type="button">|</button>'+
										'<button rel="tooltip" data-title="Barre" data-placement="bottom" class="btn btn-primary custom-width touche-barre" type="button">_</button>'+
										'<button class="btn btn-primary custom-width touche-arg" type="button">arg</button>'+
										'<button class="btn btn-primary custom-width touche-im" type="button">Im</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Prim/Intég</span><br>'+
										'<button class="btn btn-primary custom-width touche-primitive" type="button">&#x222B;</button>'+
										'<button class="btn btn-primary custom-width touche-integrale" type="button">&#x222B;<sub>a</sub><sup>b</sup></button><br>'+
										'<button class="btn btn-primary custom-width touche-somme" type="button">&sum;</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Notation de fonctions</span><br>'+
										'<button class="btn btn-primary custom-width touche-f-x" type="button">f(x)</button>'+
										'<button class="btn btn-primary custom-width touche-f" type="button">f</button>'+
										'<button class="btn btn-primary custom-width touche-fprime" type="button">f&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-fseconde" type="button">f&prime;&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-F" type="button">F</button><br>'+
										'<button class="btn btn-primary custom-width touche-g-x" type="button">g(x)</button>'+
										'<button class="btn btn-primary custom-width touche-g" type="button">g</button>'+
										'<button class="btn btn-primary custom-width touche-gprime" type="button">g&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-gseconde" type="button">g&prime;&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-G" type="button">G</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Vect/Matr</span><br>'+
										'<button rel="tooltip" data-title="Vecteurs (par leurs coefficients)" class="btn btn-primary custom-width font-size-12 touche-vect" type="button">vect</button><br>'+
										'<button rel="tooltip" data-title="Matrice" class="btn btn-primary custom-width font-size-12 touche-mat" type="button">mat</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Variables/Lettres</span><br>'+
										'<button class="btn btn-primary custom-width touche-a" type="button">a</button>'+
										'<button class="btn btn-primary custom-width touche-b" type="button">b</button>'+
										'<button class="btn btn-primary custom-width touche-c" type="button">c</button>'+
										'<button class="btn btn-primary custom-width touche-d" type="button">d</button>'+
										'<button class="btn btn-primary custom-width touche-e" type="button">e</button>'+
										'<button class="btn btn-primary custom-width touche-f" type="button">f</button>'+
										'<button class="btn btn-primary custom-width touche-g" type="button">g</button>'+
										'<button class="btn btn-primary custom-width touche-h" type="button">h</button>'+
										'<button class="btn btn-primary custom-width touche-i" type="button">i</button><br>'+
										'<button class="btn btn-primary custom-width touche-j" type="button">j</button>'+
										'<button class="btn btn-primary custom-width touche-k" type="button">k</button>'+
										'<button class="btn btn-primary custom-width touche-l" type="button">l</button>'+
										'<button class="btn btn-primary custom-width touche-m" type="button">m</button>'+
										'<button class="btn btn-primary custom-width touche-n" type="button">n</button>'+
										'<button class="btn btn-primary custom-width touche-o" type="button">o</button>'+
										'<button class="btn btn-primary custom-width touche-p" type="button">p</button>'+
										'<button class="btn btn-primary custom-width touche-q" type="button">q</button>'+
										'<button class="btn btn-primary custom-width touche-r" type="button">r</button><br>'+
										'<button class="btn btn-primary custom-width touche-s" type="button">s</button>'+
										'<button class="btn btn-primary custom-width touche-t" type="button">t</button>'+
										'<button class="btn btn-primary custom-width touche-u" type="button">u</button>'+
										'<button class="btn btn-primary custom-width touche-v" type="button">v</button>'+
										'<button class="btn btn-primary custom-width touche-w" type="button">w</button>'+
										'<button class="btn btn-primary custom-width touche-x" type="button">x</button>'+
										'<button class="btn btn-primary custom-width touche-y" type="button">y</button>'+
										'<button class="btn btn-primary custom-width touche-z" type="button">z</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Points/Lettres</span><br>'+
										'<button class="btn btn-primary custom-width touche-A" type="button">A</button>'+
										'<button class="btn btn-primary custom-width touche-B" type="button">B</button>'+
										'<button class="btn btn-primary custom-width touche-C" type="button">C</button>'+
										'<button class="btn btn-primary custom-width touche-D" type="button">D</button>'+
										'<button class="btn btn-primary custom-width touche-E" type="button">E</button>'+
										'<button class="btn btn-primary custom-width touche-F" type="button">F</button>'+
										'<button class="btn btn-primary custom-width touche-G" type="button">G</button>'+
										'<button class="btn btn-primary custom-width touche-H" type="button">H</button>'+
										'<button class="btn btn-primary custom-width touche-I" type="button">I</button><br>'+
										'<button class="btn btn-primary custom-width touche-J" type="button">J</button>'+
										'<button class="btn btn-primary custom-width touche-K" type="button">K</button>'+
										'<button class="btn btn-primary custom-width touche-L" type="button">L</button>'+
										'<button class="btn btn-primary custom-width touche-M" type="button">M</button>'+
										'<button class="btn btn-primary custom-width touche-N" type="button">N</button>'+
										'<button class="btn btn-primary custom-width touche-O" type="button">O</button>'+
										'<button class="btn btn-primary custom-width touche-P" type="button">P</button>'+
										'<button class="btn btn-primary custom-width touche-Q" type="button">Q</button>'+
										'<button class="btn btn-primary custom-width touche-R" type="button">R</button><br>'+
										'<button class="btn btn-primary custom-width touche-S" type="button">S</button>'+
										'<button class="btn btn-primary custom-width touche-T" type="button">T</button>'+
										'<button class="btn btn-primary custom-width touche-U" type="button">U</button>'+
										'<button class="btn btn-primary custom-width touche-V" type="button">V</button>'+
										'<button class="btn btn-primary custom-width touche-W" type="button">W</button>'+
										'<button class="btn btn-primary custom-width touche-X" type="button">X</button>'+
										'<button class="btn btn-primary custom-width touche-Y" type="button">Y</button>'+
										'<button class="btn btn-primary custom-width touche-Z" type="button">Z</button>'+
							    '</span>'+
							'</div>'+
							'<div class="pull-right">'+
								'<button rel="tooltip" data-title="Remettre à zéro" data-placement="bottom" class="btn btn-danger custom-width touche-reset position-relative-left-24" type="button"><i class="icon-remove text-white"></i></button>'+
								'<button rel="tooltip" data-title="Corriger" data-placement="bottom" class="btn btn-warning custom-width touche-corriger" type="button"><i class="icon-undo text-white"></i></button>'+
								'<button rel="tooltip" data-title="Valider" data-placement="right" class="btn btn-success custom-width touche-valider" type="button"><i class="icon-ok text-white"></i></button>'+
							'</div>'							  
			}).popover('toggle');
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
		}
		
		function clavierPoint(currentCase) {
			$('.popover').each( function() {
				if ( $(this).prev().attr('data-layer') == currentCase.attr('data-layer') ) {
					$(this).prev().not(currentCase).removeClass("case-focused").popover('destroy');
					$(this).remove();  // à enlever quand la méthode destroy de popover fonctionnera correctement
				}
			});
			currentCase.toggleClass("case-focused");
			currentCase.popover({
					trigger: 'manual',
					animation: true,
					placement: 'bottom',
					template: '<div class="popover"><!--<div class="arrow"></div>--><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>',
					html: 'true',
					// delay: { show: 500, hide: 2000 },
					content : <!--points (3 colonnes)-->
							'<div style="width:740px;">'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Pavé</span><br>'+
									'<button class="btn btn-primary custom-width touche-1" type="button">1</button>'+
									'<button class="btn btn-primary custom-width touche-2" type="button">2</button>'+
									'<button class="btn btn-primary custom-width touche-3" type="button"><mn>3</button><br>'+
									'<button class="btn btn-primary custom-width touche-4" type="button">4</button>'+
									'<button class="btn btn-primary custom-width touche-5" type="button">5</button>'+
									'<button class="btn btn-primary custom-width touche-6" type="button">6</button><br>'+
									'<button class="btn btn-primary custom-width touche-7" type="button">7</button>'+
									'<button class="btn btn-primary custom-width touche-8" type="button">8</button>'+
									'<button class="btn btn-primary custom-width touche-9" type="button">9</button><br>'+
									'<button class="btn btn-primary custom-width touche-virgule" type="button">,</button>'+
									'<button class="btn btn-primary custom-width touche-0" type="button">0</button>'+
									'<button class="btn btn-primary custom-width touche-x" type="button"><em>x</em></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Opérateurs</span><br>'+
									'<button class="btn btn-primary custom-width touche-plus" type="button">+</button>'+
									'<button class="btn btn-primary custom-width touche-moins" type="button">&minus;</button><br>'+
									'<button class="btn btn-primary custom-width touche-multiplie" type="button">&times;</button>'+
									'<button rel="tooltip" data-title="Fraction" data-placement="right" class="btn btn-primary custom-width touche-divise" type="button">&divide;</button><br>'+
									'<button rel="tooltip" data-title="Puissance" data-placement="left" class="btn btn-primary custom-width touche-puissance" type="button">^</button>'+
									'<button class="btn btn-primary custom-width touche-racine" type="button">&#8730;</button><br>'+
									'<button class="btn btn-primary custom-width touche-oparen" type="button">(</button>'+
									'<button class="btn btn-primary custom-width touche-fparen" type="button">)</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Vecteurs</span><br>'+
										'<button class="btn btn-primary custom-width touche-vecteur" type="button"><math><mover><mrow><mi>..</mi></mrow><mo>&rarr;</mo></mover></math></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Angles</span><br>'+
										'<button rel="tooltip" data-title="Angle" data-placement="right" class="btn btn-primary custom-width touche-angle" type="button"><math><mover><mrow><mi>...</mi></mrow><mo>^</mo></mover></math></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Constantes</span><br>'+
										'<button class="btn btn-primary custom-width touche-pi" type="button">&pi;</button>'+						
										'<button class="btn btn-primary custom-width touche-e" type="button"><em>e</em></button><br>'+
								'</span>'+
								'<span style="margin-right:10px;" class="clearfix pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Intervalles</span><br>'+
										'<button class="btn btn-primary custom-width touche-2R" type="button">IR</button>'+
										'<button class="btn btn-primary custom-width touche-point-virgule" type="button">;</button>'+
										'<button class="btn btn-primary custom-width touche-deux-points" type="button">:</button>'+
										'<button class="btn btn-primary custom-width touche-infini" type="button">&infin;</button>'+
										'<button class="btn btn-primary custom-width touche-crochetg" type="button">[</button>'+
										'<button class="btn btn-primary custom-width touche-crochetd" type="button">]</button>'+
								'</span>'+
							'<br><br><br>'+	
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Fonctions usuelles</span><br>'+
										'<button class="btn btn-primary custom-width touche-cosinus font-size-12" type="button">cos</button>'+								
										'<button class="btn btn-primary custom-width touche-sinus font-size-12" type="button">sin</button>'+								
										'<button class="btn btn-primary custom-width touche-tangente font-size-12" type="button">tan</button><br>'+								
										'<button class="btn btn-primary custom-width touche-exp font-size-12" type="button">exp</button>'+								
										'<button class="btn btn-primary custom-width touche-ln font-size-12" type="button">ln</button>'+	
										'<button class="btn btn-primary custom-width touche-log font-size-12" type="button">log</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Etude de fonctions</span><br>'+
										'<button rel="tooltip" data-title="Dérivée" data-placement="left" class="btn btn-primary custom-width touche-prime" type="button">&prime;</button>'+								
										'<button rel="tooltip" data-title="Dérivée seconde" data-placement="right" class="btn btn-primary custom-width touche-seconde" type="button">&prime;&prime;</button>'+
										'<button rel="tooltip" data-title="Valeur interdite" data-placement="left" class="btn btn-primary custom-width touche-valeur-interdite" type="button">||</button>'+	
										'<button class="btn btn-primary custom-width touche-composition" type="button">&compfn;</button><br>'+
										'<button class="btn btn-primary custom-width touche-pointe" type="button">&rarr;</button>'+
										'<button class="btn btn-primary custom-width touche-associe" type="button">&mapsto;</button>'+
										'<button class="btn btn-primary custom-width touche-croissante" type="button">↗</button>'+
										'<button class="btn btn-primary custom-width touche-decroissante" type="button">↘</button>'+
							    '</span>'+
								
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Limites</span><br>'+
										'<button class="btn btn-primary custom-width touche-infini" type="button">&infin;</button><br>'+
										'<button class="btn btn-primary touche-limite custom-width font-size-12" type="button">lim</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Suites</span><br>'+
										'<button rel="tooltip" data-title="Mettre en indice" class="btn btn-primary custom-width touche-indice" type="button">▾</button>'+
										'<button class="btn btn-primary custom-width touche-u-n" type="button">u<sub>n</sub></button>'+
										'<button class="btn btn-primary custom-width touche-v-n" type="button">v<sub>n</sub></button><br>'+
										'<button class="btn btn-primary custom-width touche-n" type="button">n</button>'+
										'<button class="btn btn-primary custom-width touche-2N" type="button">IN</button>'+
										'<button class="btn btn-primary custom-width touche-2Z" type="button">ZZ</button>'+
								'</span>'+
							'<br><br><br><br><br>'+	
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Signes</span><br>'+
										'<button class="btn btn-primary custom-width touche-egal" type="button">=</button>'+
										'<button class="btn btn-primary custom-width touche-different" type="button">&ne;</button>'+
										'<button class="btn btn-primary custom-width touche-sup" type="button">&ge;</button>'+
										'<button class="btn btn-primary custom-width touche-inf" type="button">&le;</button><br>'+
										'<button class="btn btn-primary custom-width touche-stsup" type="button">&gt;</button>'+
										'<button class="btn btn-primary custom-width touche-stinf" type="button">&lt;</button>'+
										'<button class="btn btn-primary custom-width touche-eegal" type="button">&asymp;</button>'+
										'<button class="btn btn-primary custom-width touche-pointvirgule" type="button">;</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Complexes</span><br>'+
										'<button class="btn btn-primary custom-width touche-z" type="button">z</button>'+
										'<button class="btn btn-primary custom-width touche-z-barre" type="button">z_</button>'+
										'<button class="btn btn-primary custom-width touche-i" type="button">i</button>'+
										'<button class="btn btn-primary custom-width touche-re" type="button">Re</button><br>'+
										'<button rel="tooltip" data-title="Module" data-placement="bottom" class="btn btn-primary custom-width touche-abs" type="button">|</button>'+
										'<button rel="tooltip" data-title="Barre" data-placement="bottom" class="btn btn-primary custom-width touche-barre" type="button">_</button>'+
										'<button class="btn btn-primary custom-width touche-arg" type="button">arg</button>'+
										'<button class="btn btn-primary custom-width touche-im" type="button">Im</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Prim/Intég</span><br>'+
										'<button class="btn btn-primary custom-width touche-primitive" type="button">&#x222B;</button>'+
										'<button class="btn btn-primary custom-width touche-integrale" type="button">&#x222B;<sub>a</sub><sup>b</sup></button><br>'+
										'<button class="btn btn-primary custom-width touche-somme" type="button">&sum;</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Notation de fonctions</span><br>'+
										'<button class="btn btn-primary custom-width touche-f-x" type="button">f(x)</button>'+
										'<button class="btn btn-primary custom-width touche-f" type="button">f</button>'+
										'<button class="btn btn-primary custom-width touche-fprime" type="button">f&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-fseconde" type="button">f&prime;&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-F" type="button">F</button><br>'+
										'<button class="btn btn-primary custom-width touche-g-x" type="button">g(x)</button>'+
										'<button class="btn btn-primary custom-width touche-g" type="button">g</button>'+
										'<button class="btn btn-primary custom-width touche-gprime" type="button">g&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-gseconde" type="button">g&prime;&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-G" type="button">G</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Vect/Matr</span><br>'+
										'<button rel="tooltip" data-title="Vecteurs (par leurs coefficients)" class="btn btn-primary custom-width font-size-12 touche-vect" type="button">vect</button><br>'+
										'<button rel="tooltip" data-title="Matrice" class="btn btn-primary custom-width font-size-12 touche-mat" type="button">mat</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Variables/Lettres</span><br>'+
										'<button class="btn btn-primary custom-width touche-a" type="button">a</button>'+
										'<button class="btn btn-primary custom-width touche-b" type="button">b</button>'+
										'<button class="btn btn-primary custom-width touche-c" type="button">c</button>'+
										'<button class="btn btn-primary custom-width touche-d" type="button">d</button>'+
										'<button class="btn btn-primary custom-width touche-e" type="button">e</button>'+
										'<button class="btn btn-primary custom-width touche-f" type="button">f</button>'+
										'<button class="btn btn-primary custom-width touche-g" type="button">g</button>'+
										'<button class="btn btn-primary custom-width touche-h" type="button">h</button>'+
										'<button class="btn btn-primary custom-width touche-i" type="button">i</button><br>'+
										'<button class="btn btn-primary custom-width touche-j" type="button">j</button>'+
										'<button class="btn btn-primary custom-width touche-k" type="button">k</button>'+
										'<button class="btn btn-primary custom-width touche-l" type="button">l</button>'+
										'<button class="btn btn-primary custom-width touche-m" type="button">m</button>'+
										'<button class="btn btn-primary custom-width touche-n" type="button">n</button>'+
										'<button class="btn btn-primary custom-width touche-o" type="button">o</button>'+
										'<button class="btn btn-primary custom-width touche-p" type="button">p</button>'+
										'<button class="btn btn-primary custom-width touche-q" type="button">q</button>'+
										'<button class="btn btn-primary custom-width touche-r" type="button">r</button><br>'+
										'<button class="btn btn-primary custom-width touche-s" type="button">s</button>'+
										'<button class="btn btn-primary custom-width touche-t" type="button">t</button>'+
										'<button class="btn btn-primary custom-width touche-u" type="button">u</button>'+
										'<button class="btn btn-primary custom-width touche-v" type="button">v</button>'+
										'<button class="btn btn-primary custom-width touche-w" type="button">w</button>'+
										'<button class="btn btn-primary custom-width touche-x" type="button">x</button>'+
										'<button class="btn btn-primary custom-width touche-y" type="button">y</button>'+
										'<button class="btn btn-primary custom-width touche-z" type="button">z</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Points/Lettres</span><br>'+
										'<button class="btn btn-primary custom-width touche-A" type="button">A</button>'+
										'<button class="btn btn-primary custom-width touche-B" type="button">B</button>'+
										'<button class="btn btn-primary custom-width touche-C" type="button">C</button>'+
										'<button class="btn btn-primary custom-width touche-D" type="button">D</button>'+
										'<button class="btn btn-primary custom-width touche-E" type="button">E</button>'+
										'<button class="btn btn-primary custom-width touche-F" type="button">F</button>'+
										'<button class="btn btn-primary custom-width touche-G" type="button">G</button>'+
										'<button class="btn btn-primary custom-width touche-H" type="button">H</button>'+
										'<button class="btn btn-primary custom-width touche-I" type="button">I</button><br>'+
										'<button class="btn btn-primary custom-width touche-J" type="button">J</button>'+
										'<button class="btn btn-primary custom-width touche-K" type="button">K</button>'+
										'<button class="btn btn-primary custom-width touche-L" type="button">L</button>'+
										'<button class="btn btn-primary custom-width touche-M" type="button">M</button>'+
										'<button class="btn btn-primary custom-width touche-N" type="button">N</button>'+
										'<button class="btn btn-primary custom-width touche-O" type="button">O</button>'+
										'<button class="btn btn-primary custom-width touche-P" type="button">P</button>'+
										'<button class="btn btn-primary custom-width touche-Q" type="button">Q</button>'+
										'<button class="btn btn-primary custom-width touche-R" type="button">R</button><br>'+
										'<button class="btn btn-primary custom-width touche-S" type="button">S</button>'+
										'<button class="btn btn-primary custom-width touche-T" type="button">T</button>'+
										'<button class="btn btn-primary custom-width touche-U" type="button">U</button>'+
										'<button class="btn btn-primary custom-width touche-V" type="button">V</button>'+
										'<button class="btn btn-primary custom-width touche-W" type="button">W</button>'+
										'<button class="btn btn-primary custom-width touche-X" type="button">X</button>'+
										'<button class="btn btn-primary custom-width touche-Y" type="button">Y</button>'+
										'<button class="btn btn-primary custom-width touche-Z" type="button">Z</button>'+
							    '</span>'+
							'</div>'+
							'<div class="pull-right">'+
								'<button rel="tooltip" data-title="Remettre à zéro" data-placement="bottom" class="btn btn-danger custom-width touche-reset position-relative-left-24" type="button"><i class="icon-remove text-white"></i></button>'+
								'<button rel="tooltip" data-title="Corriger" data-placement="bottom" class="btn btn-warning custom-width touche-corriger" type="button"><i class="icon-undo text-white"></i></button>'+
								'<button rel="tooltip" data-title="Valider" data-placement="right" class="btn btn-success custom-width touche-valider" type="button"><i class="icon-ok text-white"></i></button>'+
							'</div>'						  
			}).popover('toggle');
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
		}
		
		function clavierAvancee(currentCase) {
			$('.popover').each( function() {
				if ( $(this).prev().attr('data-layer') == currentCase.attr('data-layer') ) {
					$(this).prev().not(currentCase).removeClass("case-focused").popover('destroy');
					$(this).remove();  // à enlever quand la méthode destroy de popover fonctionnera correctement
				}
			});
			currentCase.toggleClass("case-focused");
			currentCase.popover({
					trigger: 'manual',
					animation: true,
					placement: 'bottom',
					template: '<div class="popover"><!--<div class="arrow"></div>--><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>',
					html: 'true',
					// delay: { show: 500, hide: 2000 },
					content : <!--points (3 colonnes)-->
							  '<div style="width:740px;">'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Pavé</span><br>'+
									'<button class="btn btn-primary custom-width touche-1" type="button">1</button>'+
									'<button class="btn btn-primary custom-width touche-2" type="button">2</button>'+
									'<button class="btn btn-primary custom-width touche-3" type="button"><mn>3</button><br>'+
									'<button class="btn btn-primary custom-width touche-4" type="button">4</button>'+
									'<button class="btn btn-primary custom-width touche-5" type="button">5</button>'+
									'<button class="btn btn-primary custom-width touche-6" type="button">6</button><br>'+
									'<button class="btn btn-primary custom-width touche-7" type="button">7</button>'+
									'<button class="btn btn-primary custom-width touche-8" type="button">8</button>'+
									'<button class="btn btn-primary custom-width touche-9" type="button">9</button><br>'+
									'<button class="btn btn-primary custom-width touche-virgule" type="button">,</button>'+
									'<button class="btn btn-primary custom-width touche-0" type="button">0</button>'+
									'<button class="btn btn-primary custom-width touche-x" type="button"><em>x</em></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Opérateurs</span><br>'+
									'<button class="btn btn-primary custom-width touche-plus" type="button">+</button>'+
									'<button class="btn btn-primary custom-width touche-moins" type="button">&minus;</button><br>'+
									'<button class="btn btn-primary custom-width touche-multiplie" type="button">&times;</button>'+
									'<button rel="tooltip" data-title="Fraction" data-placement="right" class="btn btn-primary custom-width touche-divise" type="button">&divide;</button><br>'+
									'<button rel="tooltip" data-title="Puissance" data-placement="left" class="btn btn-primary custom-width touche-puissance" type="button">^</button>'+
									'<button class="btn btn-primary custom-width touche-racine" type="button">&#8730;</button><br>'+
									'<button class="btn btn-primary custom-width touche-oparen" type="button">(</button>'+
									'<button class="btn btn-primary custom-width touche-fparen" type="button">)</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Vecteurs</span><br>'+
										'<button class="btn btn-primary custom-width touche-vecteur" type="button"><math><mover><mrow><mi>..</mi></mrow><mo>&rarr;</mo></mover></math></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Angles</span><br>'+
										'<button rel="tooltip" data-title="Angle" data-placement="right" class="btn btn-primary custom-width touche-angle" type="button"><math><mover><mrow><mi>...</mi></mrow><mo>^</mo></mover></math></button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Constantes</span><br>'+
										'<button class="btn btn-primary custom-width touche-pi" type="button">&pi;</button>'+						
										'<button class="btn btn-primary custom-width touche-e" type="button"><em>e</em></button><br>'+
								'</span>'+
								'<span style="margin-right:10px;" class="clearfix pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Intervalles</span><br>'+
										'<button class="btn btn-primary custom-width touche-2R" type="button">IR</button>'+
										'<button class="btn btn-primary custom-width touche-point-virgule" type="button">;</button>'+
										'<button class="btn btn-primary custom-width touche-deux-points" type="button">:</button>'+
										'<button class="btn btn-primary custom-width touche-infini" type="button">&infin;</button>'+
										'<button class="btn btn-primary custom-width touche-crochetg" type="button">[</button>'+
										'<button class="btn btn-primary custom-width touche-crochetd" type="button">]</button>'+
								'</span>'+
							'<br><br><br>'+	
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Fonctions usuelles</span><br>'+
										'<button class="btn btn-primary custom-width touche-cosinus font-size-12" type="button">cos</button>'+								
										'<button class="btn btn-primary custom-width touche-sinus font-size-12" type="button">sin</button>'+								
										'<button class="btn btn-primary custom-width touche-tangente font-size-12" type="button">tan</button><br>'+								
										'<button class="btn btn-primary custom-width touche-exp font-size-12" type="button">exp</button>'+								
										'<button class="btn btn-primary custom-width touche-ln font-size-12" type="button">ln</button>'+	
										'<button class="btn btn-primary custom-width touche-log font-size-12" type="button">log</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Etude de fonctions</span><br>'+
										'<button rel="tooltip" data-title="Dérivée" data-placement="left" class="btn btn-primary custom-width touche-prime" type="button">&prime;</button>'+								
										'<button rel="tooltip" data-title="Dérivée seconde" data-placement="right" class="btn btn-primary custom-width touche-seconde" type="button">&prime;&prime;</button>'+
										'<button rel="tooltip" data-title="Valeur interdite" data-placement="left" class="btn btn-primary custom-width touche-valeur-interdite" type="button">||</button>'+	
										'<button class="btn btn-primary custom-width touche-composition" type="button">&compfn;</button><br>'+
										'<button class="btn btn-primary custom-width touche-pointe" type="button">&rarr;</button>'+
										'<button class="btn btn-primary custom-width touche-associe" type="button">&mapsto;</button>'+
										'<button class="btn btn-primary custom-width touche-croissante" type="button">↗</button>'+
										'<button class="btn btn-primary custom-width touche-decroissante" type="button">↘</button>'+
							    '</span>'+
								
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Limites</span><br>'+
										'<button class="btn btn-primary custom-width touche-infini" type="button">&infin;</button><br>'+
										'<button class="btn btn-primary touche-limite custom-width font-size-12" type="button">lim</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Suites</span><br>'+
										'<button rel="tooltip" data-title="Mettre en indice" class="btn btn-primary custom-width touche-indice" type="button">▾</button>'+
										'<button class="btn btn-primary custom-width touche-u-n" type="button">u<sub>n</sub></button>'+
										'<button class="btn btn-primary custom-width touche-v-n" type="button">v<sub>n</sub></button><br>'+
										'<button class="btn btn-primary custom-width touche-n" type="button">n</button>'+
										'<button class="btn btn-primary custom-width touche-2N" type="button">IN</button>'+
										'<button class="btn btn-primary custom-width touche-2Z" type="button">ZZ</button>'+
								'</span>'+
							'<br><br><br><br><br>'+	
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Signes</span><br>'+
										'<button class="btn btn-primary custom-width touche-egal" type="button">=</button>'+
										'<button class="btn btn-primary custom-width touche-different" type="button">&ne;</button>'+
										'<button class="btn btn-primary custom-width touche-sup" type="button">&ge;</button>'+
										'<button class="btn btn-primary custom-width touche-inf" type="button">&le;</button><br>'+
										'<button class="btn btn-primary custom-width touche-stsup" type="button">&gt;</button>'+
										'<button class="btn btn-primary custom-width touche-stinf" type="button">&lt;</button>'+
										'<button class="btn btn-primary custom-width touche-eegal" type="button">&asymp;</button>'+
										'<button class="btn btn-primary custom-width touche-pointvirgule" type="button">;</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Complexes</span><br>'+
										'<button class="btn btn-primary custom-width touche-z" type="button">z</button>'+
										'<button class="btn btn-primary custom-width touche-z-barre" type="button">z_</button>'+
										'<button class="btn btn-primary custom-width touche-i" type="button">i</button>'+
										'<button class="btn btn-primary custom-width touche-re" type="button">Re</button><br>'+
										'<button rel="tooltip" data-title="Module" data-placement="bottom" class="btn btn-primary custom-width touche-abs" type="button">|</button>'+
										'<button rel="tooltip" data-title="Barre" data-placement="bottom" class="btn btn-primary custom-width touche-barre" type="button">_</button>'+
										'<button class="btn btn-primary custom-width touche-arg" type="button">arg</button>'+
										'<button class="btn btn-primary custom-width touche-im" type="button">Im</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Prim/Intég</span><br>'+
										'<button class="btn btn-primary custom-width touche-primitive" type="button">&#x222B;</button>'+
										'<button class="btn btn-primary custom-width touche-integrale" type="button">&#x222B;<sub>a</sub><sup>b</sup></button><br>'+
										'<button class="btn btn-primary custom-width touche-somme" type="button">&sum;</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Notation de fonctions</span><br>'+
										'<button class="btn btn-primary custom-width touche-f-x" type="button">f(x)</button>'+
										'<button class="btn btn-primary custom-width touche-f" type="button">f</button>'+
										'<button class="btn btn-primary custom-width touche-fprime" type="button">f&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-fseconde" type="button">f&prime;&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-F" type="button">F</button><br>'+
										'<button class="btn btn-primary custom-width touche-g-x" type="button">g(x)</button>'+
										'<button class="btn btn-primary custom-width touche-g" type="button">g</button>'+
										'<button class="btn btn-primary custom-width touche-gprime" type="button">g&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-gseconde" type="button">g&prime;&prime;</button>'+
										'<button class="btn btn-primary custom-width touche-G" type="button">G</button>'+	
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Vect/Matr</span><br>'+
										'<button rel="tooltip" data-title="Vecteurs (par leurs coefficients)" class="btn btn-primary custom-width font-size-12 touche-vect" type="button">vect</button><br>'+
										'<button rel="tooltip" data-title="Matrice" class="btn btn-primary custom-width font-size-12 touche-mat" type="button">mat</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Variables/Lettres</span><br>'+
										'<button class="btn btn-primary custom-width touche-a" type="button">a</button>'+
										'<button class="btn btn-primary custom-width touche-b" type="button">b</button>'+
										'<button class="btn btn-primary custom-width touche-c" type="button">c</button>'+
										'<button class="btn btn-primary custom-width touche-d" type="button">d</button>'+
										'<button class="btn btn-primary custom-width touche-e" type="button">e</button>'+
										'<button class="btn btn-primary custom-width touche-f" type="button">f</button>'+
										'<button class="btn btn-primary custom-width touche-g" type="button">g</button>'+
										'<button class="btn btn-primary custom-width touche-h" type="button">h</button>'+
										'<button class="btn btn-primary custom-width touche-i" type="button">i</button><br>'+
										'<button class="btn btn-primary custom-width touche-j" type="button">j</button>'+
										'<button class="btn btn-primary custom-width touche-k" type="button">k</button>'+
										'<button class="btn btn-primary custom-width touche-l" type="button">l</button>'+
										'<button class="btn btn-primary custom-width touche-m" type="button">m</button>'+
										'<button class="btn btn-primary custom-width touche-n" type="button">n</button>'+
										'<button class="btn btn-primary custom-width touche-o" type="button">o</button>'+
										'<button class="btn btn-primary custom-width touche-p" type="button">p</button>'+
										'<button class="btn btn-primary custom-width touche-q" type="button">q</button>'+
										'<button class="btn btn-primary custom-width touche-r" type="button">r</button><br>'+
										'<button class="btn btn-primary custom-width touche-s" type="button">s</button>'+
										'<button class="btn btn-primary custom-width touche-t" type="button">t</button>'+
										'<button class="btn btn-primary custom-width touche-u" type="button">u</button>'+
										'<button class="btn btn-primary custom-width touche-v" type="button">v</button>'+
										'<button class="btn btn-primary custom-width touche-w" type="button">w</button>'+
										'<button class="btn btn-primary custom-width touche-x" type="button">x</button>'+
										'<button class="btn btn-primary custom-width touche-y" type="button">y</button>'+
										'<button class="btn btn-primary custom-width touche-z" type="button">z</button>'+
								'</span>'+
								'<span style="margin-right:10px;" class="pull-left">'+
									'<span style="display:inline-block;height:24px;padding-top:6px;">Points/Lettres</span><br>'+
										'<button class="btn btn-primary custom-width touche-A" type="button">A</button>'+
										'<button class="btn btn-primary custom-width touche-B" type="button">B</button>'+
										'<button class="btn btn-primary custom-width touche-C" type="button">C</button>'+
										'<button class="btn btn-primary custom-width touche-D" type="button">D</button>'+
										'<button class="btn btn-primary custom-width touche-E" type="button">E</button>'+
										'<button class="btn btn-primary custom-width touche-F" type="button">F</button>'+
										'<button class="btn btn-primary custom-width touche-G" type="button">G</button>'+
										'<button class="btn btn-primary custom-width touche-H" type="button">H</button>'+
										'<button class="btn btn-primary custom-width touche-I" type="button">I</button><br>'+
										'<button class="btn btn-primary custom-width touche-J" type="button">J</button>'+
										'<button class="btn btn-primary custom-width touche-K" type="button">K</button>'+
										'<button class="btn btn-primary custom-width touche-L" type="button">L</button>'+
										'<button class="btn btn-primary custom-width touche-M" type="button">M</button>'+
										'<button class="btn btn-primary custom-width touche-N" type="button">N</button>'+
										'<button class="btn btn-primary custom-width touche-O" type="button">O</button>'+
										'<button class="btn btn-primary custom-width touche-P" type="button">P</button>'+
										'<button class="btn btn-primary custom-width touche-Q" type="button">Q</button>'+
										'<button class="btn btn-primary custom-width touche-R" type="button">R</button><br>'+
										'<button class="btn btn-primary custom-width touche-S" type="button">S</button>'+
										'<button class="btn btn-primary custom-width touche-T" type="button">T</button>'+
										'<button class="btn btn-primary custom-width touche-U" type="button">U</button>'+
										'<button class="btn btn-primary custom-width touche-V" type="button">V</button>'+
										'<button class="btn btn-primary custom-width touche-W" type="button">W</button>'+
										'<button class="btn btn-primary custom-width touche-X" type="button">X</button>'+
										'<button class="btn btn-primary custom-width touche-Y" type="button">Y</button>'+
										'<button class="btn btn-primary custom-width touche-Z" type="button">Z</button>'+
										'<button class="btn btn-primary custom-width touche-ensemble-vide" type="button">&empty;</button>'+
										'<button class="btn btn-primary custom-width touche-isin" type="button">&isin;</button>'+
										'<button class="btn btn-primary custom-width touche-notin" type="button">&notin;</button>'+
										'<button class="btn btn-primary custom-width touche-inclusion" type="button">&sub;</button>'+
										'<button class="btn btn-primary custom-width touche-cap" type="button">&cap;</button>'+
										'<button class="btn btn-primary custom-width touche-cup" type="button">&cup;</button>'+
							    '</span>'+
							'</div>'+
							'<div class="pull-right">'+
								'<button rel="tooltip" data-title="Remettre à zéro" data-placement="bottom" class="btn btn-danger custom-width touche-reset position-relative-left-24" type="button"><i class="icon-remove text-white"></i></button>'+
								'<button rel="tooltip" data-title="Corriger" data-placement="bottom" class="btn btn-warning custom-width touche-corriger" type="button"><i class="icon-undo text-white"></i></button>'+
								'<button rel="tooltip" data-title="Valider" data-placement="right" class="btn btn-success custom-width touche-valider" type="button"><i class="icon-ok text-white"></i></button>'+
							'</div>'					  
			}).popover('toggle');
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
		}
		

		// Autres boutons
		$(document)
		.on('click', '.touche-valider', function() {
			// var nextPop = $(this).closest('.popover').parent().find('.mathscase[data-clavier="general"]').first();
			$(this).closest('.popover').prev().toggleClass("case-focused").popover('toggle');
			// clavierGeneral(nextPop);
		})
		.on('click', '.touche-corriger', function() {
			var lastChildLength = $($(this).closest('.popover').next('input[type="hidden"]').val()).last().wrap('<temp />').parent().html().length;
			var newMath = $(this).closest('.popover').next('input[type="hidden"]').val().substring(0, $(this).closest('.popover').next('input[type="hidden"]').val().length - parseInt(lastChildLength));
			$(this).closest('.popover').next('input[type="hidden"]').val(newMath);
			var insertIn = MathJax.Hub.getAllJax($(this).closest('.popover').prev()[0])[0];
			MathJax.Hub.queue.Push(["Text", insertIn,"<math>"+newMath+"</math>"]);
			MathJax.Hub.Queue(["Typeset", MathJax.Hub, $(this).closest('.popover').prev()[0]]);
		})
		.on('click', '.touche-reset', function() {
			$(this).closest('.popover').next('input[type="hidden"]').val('');
			var insertIn = MathJax.Hub.getAllJax($(this).closest('.popover').prev()[0])[0];
			MathJax.Hub.queue.Push(["Text", insertIn,"<math></math>"]);
			MathJax.Hub.Queue(["Typeset", MathJax.Hub, $(this).closest('.popover').prev()[0]]);
		});
		
		/* Functions
		 * ========= */
		 
		// Style

		// var mathstyle = function(thisButton) {
		function mathstyle(thisButton) {
			if ( thisButton.closest('.popover').next('input[type="hidden"]').length > 0 ) {
				var popover = thisButton.closest('.popover');
			}
			else {
				var popover = thisButton.closest('.popover').parent().closest('.popover');
			}
			var mstyle = '';
			popover.find('input[name="mathcolor"]').each(function() {
				if ( $(this).is(':checked') ) {
					if ( $(this).val() != 'black') {
						mstyle += ' mathcolor="'+$(this).val()+'"';
					}
				}
			});
			popover.find('input[name="mathvariant"]').each(function() {
				if ( $(this).is(':checked') ) {
					if ( $(this).val() != 'normal') {
						mstyle += ' mathvariant="'+$(this).val()+'"';
					}
				}
			});
			return mstyle;
		}
		 
		// Modals
		
		function switchModals(modal) {
			modal.on('hidden.bs.modal', function() {
				if ( $(this).prev().length != 0 ) {
					$(this).prev().modal('show'); 
				}
				if ( $(this).next().length == 0 ) {
					$(this).remove();
				}
			}).modal('hide');
		}
		
		// Popovers
		 
		function addEntry(type, currentThis) {
			if ( currentThis.closest('.popover').next('input[type="hidden"]').length > 0 ) {
				var popover = currentThis.closest('.popover');
			}
			else {
				var popover = currentThis.closest('.popover').parent().closest('.popover');
			}
			var newMath = popover.next('input[type="hidden"]').val().concat(type);
			popover.next('input[type="hidden"]').val(newMath);
			var insertIn = MathJax.Hub.getAllJax(popover.prev()[0])[0];
			MathJax.Hub.queue.Push(["Text", insertIn,"<math>"+newMath+"</math>"]);
			
			// popover.prev().popover('show');
			// MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
		}
		
		function delegateToPopover(decrementedLayer, toInsert) {
			$('.popover.in').each( function() {
				if ( $(this).prev().attr('data-layer') !== undefined && $(this).prev().attr('data-layer') == decrementedLayer ) {
					$(this).next().val($(this).next().val().concat(toInsert));
					var insertIn = MathJax.Hub.getAllJax($(this).prev()[0])[0];
					MathJax.Hub.queue.Push(["Text", insertIn,"<math>"+$(this).next().val()+"</math>"]);
					MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
				}				
			});
		}