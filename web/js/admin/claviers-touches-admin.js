	/**
	  * CLAVIER ADMIN TOUCHES
	  */
	  
		/* Modal events
		 * ============ */
		
		$(document)
		
		.on('click', '.close-modal', function() { 
			switchModals( $(this).closest('.modal') );
		});
		
		/* Popover events
		 * ============== */
		 
		$(document)
		
		.on('click', '.touche-1', function() {
			addEntry('<mn'+mathstyle($(this))+'>1</mn>', $(this));
		})
		.on('click', '.touche-2', function() {
			addEntry('<mn'+mathstyle($(this))+'>2</mn>', $(this));
		})
		.on('click', '.touche-3', function() {
			addEntry('<mn'+mathstyle($(this))+'>3</mn>', $(this));
		})
		.on('click', '.touche-4', function() {
			addEntry('<mn'+mathstyle($(this))+'>4</mn>', $(this));
		})
		.on('click', '.touche-5', function() {
			addEntry('<mn'+mathstyle($(this))+'>5</mn>', $(this));
		})
		.on('click', '.touche-6', function() {
			addEntry('<mn'+mathstyle($(this))+'>6</mn>', $(this));
		})
		.on('click', '.touche-7', function() {
			addEntry('<mn'+mathstyle($(this))+'>7</mn>', $(this));
		})
		.on('click', '.touche-8', function() {
			addEntry('<mn'+mathstyle($(this))+'>8</mn>', $(this));
		})
		.on('click', '.touche-9', function() {
			addEntry('<mn'+mathstyle($(this))+'>9</mn>', $(this));
		})
		.on('click', '.touche-virgule', function() {
			addEntry('<mn'+mathstyle($(this))+'>,</mn>', $(this));
		})
		.on('click', '.touche-3points', function() {
			addEntry('<mtext'+mathstyle($(this))+'>.</mtext>', $(this));
		})
		.on('click', '.touche-0', function() {
			addEntry('<mn'+mathstyle($(this))+'>0</mn>', $(this));
		})
		.on('click', '.touche-plus', function() {
			addEntry('<mo'+mathstyle($(this))+'>+</mo>', $(this));
		})
		.on('click', '.touche-moins', function() {
			addEntry('<mo'+mathstyle($(this))+'>-</mo>', $(this));
		})
		.on('click', '.touche-plusmoins', function() {
			addEntry('<mo'+mathstyle($(this))+'>±</mo>', $(this));
		})
		.on('click', '.touche-multiplie', function() {
			addEntry('<mo'+mathstyle($(this))+'>×</mo>', $(this));
		})
		.on('click', '.touche-oparen', function() {
			addEntry('<mo'+mathstyle($(this))+'>(</mo>', $(this));
		})
		.on('click', '.touche-fparen', function() {
			addEntry('<mo'+mathstyle($(this))+'>)</mo>', $(this));
		})
		.on('click', '.touche-otparen', function() {
			addEntry('<mtext'+mathstyle($(this))+'>(</mtext>', $(this));
		})
		.on('click', '.touche-ftparen', function() {
			addEntry('<mtext'+mathstyle($(this))+'>)</mtext>', $(this));
		})
		.on('click', '.touche-f-x', function() {
			addEntry('<mi'+mathstyle($(this))+'>f</mi><mtext'+mathstyle($(this))+'>(</mtext><mi'+mathstyle($(this))+'>x</mi><mtext'+mathstyle($(this))+'>)</mtext>', $(this));
		})
		.on('click', '.touche-fprime', function() {
			addEntry('<mi'+mathstyle($(this))+'>f</mi><mo'+mathstyle($(this))+'>′</mo>', $(this));
		})
		.on('click', '.touche-fseconde', function() {
			addEntry('<mi'+mathstyle($(this))+'>f</mi><mo'+mathstyle($(this))+'>′′</mo>', $(this));
		})
		.on('click', '.touche-g-x', function() {
			addEntry('<mi'+mathstyle($(this))+'>g</mi><mtext'+mathstyle($(this))+'>(</mtext><mi'+mathstyle($(this))+'>x</mi><mtext'+mathstyle($(this))+'>)</mtext>', $(this));
		})
		.on('click', '.touche-gprime', function() {
			addEntry('<mi'+mathstyle($(this))+'>g</mi><mo'+mathstyle($(this))+'>′</mo>', $(this));
		})
		.on('click', '.touche-gseconde', function() {
			addEntry('<mi'+mathstyle($(this))+'>g</mi><mo'+mathstyle($(this))+'>″</mo>', $(this));
		})
		.on('click', '.touche-pointe', function() {
			addEntry('<mo'+mathstyle($(this))+'>→</mo>', $(this));
		})
		.on('click', '.touche-associe', function() {
			addEntry('<mo'+mathstyle($(this))+'>↦</mo>', $(this));
		})
		.on('click', '.touche-composition', function() {
			addEntry('<mo'+mathstyle($(this))+'>∘</mo>', $(this));
		})
		.on('click', '.touche-2R', function() {
			addEntry('<mi mathvariant="double-struck" style="position:relative;top:2px;">R</mi>', $(this));
		})
		.on('click', '.touche-etoile', function() {
			addEntry('<mo'+mathstyle($(this))+'>*</mo>', $(this));
		})
		.on('click', '.touche-point-virgule', function() {
			addEntry('<mo'+mathstyle($(this))+'>;</mo>', $(this));
		})
		.on('click', '.touche-deux-points', function() {
			addEntry('<mo '+mathstyle($(this))+'>:</mo>', $(this));
		})
		.on('click', '.touche-crochetg', function() {
			addEntry('<mo'+mathstyle($(this))+'>[</mo>', $(this));
		})
		.on('click', '.touche-crochetd', function() {
			addEntry('<mo'+mathstyle($(this))+'>]</mo>', $(this));
		})
		.on('click', '.touche-acolg', function() {
			addEntry('<mo'+mathstyle($(this))+'>{</mo>', $(this));
		})
		.on('click', '.touche-acold', function() {
			addEntry('<mo'+mathstyle($(this))+'>}</mo>', $(this));
		})
		.on('click', '.touche-valeur-interdite', function() {
			addEntry('<mo'+mathstyle($(this))+'>||</mo>', $(this));
		})
		.on('click', '.touche-arg', function() {
			addEntry('<mi'+mathstyle($(this))+'>arg</mi><mo'+mathstyle($(this))+'>(</mo>', $(this));
		})
		.on('click', '.touche-cosinus', function() {
			addEntry('<mi'+mathstyle($(this))+'>cos</mi><mo'+mathstyle($(this))+'>(</mo>', $(this));
		})
		.on('click', '.touche-sinus', function() {
			addEntry('<mi'+mathstyle($(this))+'>sin</mi><mo>(</mo>', $(this));
		})
		.on('click', '.touche-tangente', function() {
			addEntry('<mi'+mathstyle($(this))+'>tan</mi><mo'+mathstyle($(this))+'>(</mo>', $(this));
		})
		.on('click', '.touche-ln', function() {
			addEntry('<mi'+mathstyle($(this))+'>ln</mi><mo'+mathstyle($(this))+'>(</mo>', $(this));
		})
		.on('click', '.touche-prime', function() {
			addEntry('<mo'+mathstyle($(this))+'>′</mo>', $(this));
		})
		.on('click', '.touche-seconde', function() {
			addEntry('<mo'+mathstyle($(this))+'>′′</mo>', $(this));
		})
		.on('click', '.touche-croissante', function() {
			addEntry('<mo'+mathstyle($(this))+'>↗</mo>', $(this));
		})
		.on('click', '.touche-decroissante', function() {
			addEntry('<mo'+mathstyle($(this))+'>↘</mo>', $(this));
		})
		.on('click', '.touche-u-n', function() {
			addEntry('<msub><mrow><mi'+mathstyle($(this))+'>u</mi></mrow><mrow><mi'+mathstyle($(this))+'>n</mi></mrow></msub>', $(this));
		})
		.on('click', '.touche-v-n', function() {
			addEntry('<msub><mrow><mi'+mathstyle($(this))+'>v</mi></mrow><mrow><mi'+mathstyle($(this))+'>n</mi></mrow></msub>', $(this));
		})
		.on('click', '.touche-2N', function() {
			addEntry('<mi mathvariant="double-struck" style="position:relative;top:2px;">N</mi>', $(this));
		})
		.on('click', '.touche-2Z', function() {
			addEntry('<mi mathvariant="double-struck" style="position:relative;top:2px;">Z</mi>', $(this));
		})
		.on('click', '.touche-egal', function() {
			addEntry('<mo'+mathstyle($(this))+'>=</mo>', $(this));
		})
		.on('click', '.touche-different', function() {
			addEntry('<mo'+mathstyle($(this))+'>≠</mo>', $(this));
		})
		.on('click', '.touche-sup', function() {
			addEntry('<mo'+mathstyle($(this))+'>≥</mo>', $(this));
		})
		.on('click', '.touche-inf', function() {
			addEntry('<mo'+mathstyle($(this))+'>≤</mo>', $(this));
		}) 
		.on('click', '.touche-stsup', function() {
			addEntry('<mo'+mathstyle($(this))+'>&gt;</mo>', $(this));
		})
		.on('click', '.touche-stinf', function() {
			addEntry('<mo'+mathstyle($(this))+'>&lt;</mo>', $(this));
		})
		.on('click', '.touche-eegal', function() {
			addEntry('<mo'+mathstyle($(this))+'>≈</mo>', $(this));
		})
		.on('click', '.touche-pointvirgule', function() {
			addEntry('<mo'+mathstyle($(this))+'>;</mo>', $(this));
		})
		.on('click', '.touche-re', function() {
			addEntry('<mtext'+mathstyle($(this))+'>Re</mtext><mo>(</mo>', $(this));
		})
		.on('click', '.touche-im', function() {
			addEntry('<mtext'+mathstyle($(this))+'>Im</mtext><mo>(</mo>', $(this));
		})
		.on('click', '.touche-A', function() {
			addEntry('<mtext'+mathstyle($(this))+'>A</mtext>', $(this));
		})
		.on('click', '.touche-B', function() {
			addEntry('<mtext'+mathstyle($(this))+'>B</mtext>', $(this));
		})
		.on('click', '.touche-C', function() {
			addEntry('<mtext'+mathstyle($(this))+'>C</mtext>', $(this));
		})
		.on('click', '.touche-D', function() {
			addEntry('<mtext'+mathstyle($(this))+'>D</mtext>', $(this));
		})
		.on('click', '.touche-E', function() {
			addEntry('<mtext'+mathstyle($(this))+'>E</mtext>', $(this));
		})
		.on('click', '.touche-F', function() {
			addEntry('<mtext'+mathstyle($(this))+'>F</mtext>', $(this));
		})
		.on('click', '.touche-G', function() {
			addEntry('<mtext'+mathstyle($(this))+'>G</mtext>', $(this));
		})
		.on('click', '.touche-H', function() {
			addEntry('<mtext'+mathstyle($(this))+'>H</mtext>', $(this));
		})
		.on('click', '.touche-I', function() {
			addEntry('<mtext'+mathstyle($(this))+'>I</mtext>', $(this));
		})
		.on('click', '.touche-J', function() {
			addEntry('<mtext'+mathstyle($(this))+'>J</mtext>', $(this));
		})
		.on('click', '.touche-K', function() {
			addEntry('<mtext'+mathstyle($(this))+'>K</mtext>', $(this));
		})
		.on('click', '.touche-L', function() {
			addEntry('<mtext'+mathstyle($(this))+'>L</mtext>', $(this));
		})
		.on('click', '.touche-M', function() {
			addEntry('<mtext'+mathstyle($(this))+'>M</mtext>', $(this));
		})
		.on('click', '.touche-N', function() {
			addEntry('<mtext'+mathstyle($(this))+'>N</mtext>', $(this));
		})
		.on('click', '.touche-O', function() {
			addEntry('<mtext'+mathstyle($(this))+'>O</mtext>', $(this));
		})
		.on('click', '.touche-P', function() {
			addEntry('<mtext'+mathstyle($(this))+'>P</mtext>', $(this));
		})
		.on('click', '.touche-Q', function() {
			addEntry('<mtext'+mathstyle($(this))+'>Q</mtext>', $(this));
		})
		.on('click', '.touche-R', function() {
			addEntry('<mtext'+mathstyle($(this))+'>R</mtext>', $(this));
		})
		.on('click', '.touche-S', function() {
			addEntry('<mtext'+mathstyle($(this))+'>S</mtext>', $(this));
		})
		.on('click', '.touche-T', function() {
			addEntry('<mtext'+mathstyle($(this))+'>T</mtext>', $(this));
		})
		.on('click', '.touche-U', function() {
			addEntry('<mtext'+mathstyle($(this))+'>U</mtext>', $(this));
		})
		.on('click', '.touche-V', function() {
			addEntry('<mtext'+mathstyle($(this))+'>V</mtext>', $(this));
		})
		.on('click', '.touche-W', function() {
			addEntry('<mtext'+mathstyle($(this))+'>W</mtext>', $(this));
		})
		.on('click', '.touche-X', function() {
			addEntry('<mtext'+mathstyle($(this))+'>X</mtext>', $(this));
		})
		.on('click', '.touche-Y', function() {
			addEntry('<mtext'+mathstyle($(this))+'>Y</mtext>', $(this));
		})
		.on('click', '.touche-Z', function() {
			addEntry('<mtext'+mathstyle($(this))+'>Z</mtext>', $(this));
		})
		.on('click', '.touche-a', function() {
			addEntry('<mi'+mathstyle($(this))+'>a</mi>', $(this));
		})
		.on('click', '.touche-b', function() {
			addEntry('<mi'+mathstyle($(this))+'>b</mi>', $(this));
		})
		.on('click', '.touche-c', function() {
			addEntry('<mi'+mathstyle($(this))+'>c</mi>', $(this));
		})
		.on('click', '.touche-d', function() {
			addEntry('<mi'+mathstyle($(this))+'>d</mi>', $(this));
		})
		.on('click', '.touche-e', function() {
			addEntry('<mi'+mathstyle($(this))+'>e</mi>', $(this));
		})
		.on('click', '.touche-f', function() {
			addEntry('<mi'+mathstyle($(this))+'>f</mi>', $(this));
		})
		.on('click', '.touche-g', function() {
			addEntry('<mi'+mathstyle($(this))+'>g</mi>', $(this));
		})
		.on('click', '.touche-h', function() {
			addEntry('<mi'+mathstyle($(this))+'>h</mi>', $(this));
		})
		.on('click', '.touche-i', function() {
			addEntry('<mi'+mathstyle($(this))+'>i</mi><mo></mo>', $(this));
		})
		.on('click', '.touche-j', function() {
			addEntry('<mi'+mathstyle($(this))+'>j</mi>', $(this));
		})
		.on('click', '.touche-k', function() {
			addEntry('<mi'+mathstyle($(this))+'>k</mi>', $(this));
		})
		.on('click', '.touche-l', function() {
			addEntry('<mi'+mathstyle($(this))+'>l</mi>', $(this));
		})
		.on('click', '.touche-m', function() {
			addEntry('<mi'+mathstyle($(this))+'>m</mi>', $(this));
		})
		.on('click', '.touche-n', function() {
			addEntry('<mi'+mathstyle($(this))+'>n</mi>', $(this));
		})
		.on('click', '.touche-o', function() {
			addEntry('<mi'+mathstyle($(this))+'>o</mi>', $(this));
		})
		.on('click', '.touche-p', function() {
			addEntry('<mi'+mathstyle($(this))+'>p</mi>', $(this));
		})
		.on('click', '.touche-q', function() {
			addEntry('<mi'+mathstyle($(this))+'>q</mi>', $(this));
		})
		.on('click', '.touche-r', function() {
			addEntry('<mi'+mathstyle($(this))+'>r</mi>', $(this));
		})
		.on('click', '.touche-s', function() {
			addEntry('<mi'+mathstyle($(this))+'>s</mi>', $(this));
		})
		.on('click', '.touche-t', function() {
			addEntry('<mi'+mathstyle($(this))+'>t</mi>', $(this));
		})
		.on('click', '.touche-u', function() {
			addEntry('<mi'+mathstyle($(this))+'>u</mi>', $(this));
		})
		.on('click', '.touche-v', function() {
			addEntry('<mi'+mathstyle($(this))+'>v</mi>', $(this));
		})
		.on('click', '.touche-w', function() {
			addEntry('<mi'+mathstyle($(this))+'>w</mi>', $(this));
		})
		.on('click', '.touche-x', function() {
			addEntry('<mi'+mathstyle($(this))+'>x</mi>', $(this));
		})
		.on('click', '.touche-y', function() {
			addEntry('<mi'+mathstyle($(this))+'>y</mi>', $(this));
		})
		.on('click', '.touche-z', function() {
			addEntry('<mi'+mathstyle($(this))+'>z</mi>', $(this));
		})
		.on('click', '.touche-z-barre', function() {
			addEntry('<mover><mrow><mi'+mathstyle($(this))+'>z</mi></mrow><mo'+mathstyle($(this))+'>-</mo></mover>', $(this));
		})
		.on('click', '.touche-abs', function() {
			addEntry('<mo'+mathstyle($(this))+'>|</mo>', $(this));
		})
		.on('click', '.touche-degre', function() {
			addEntry('<mtext'+mathstyle($(this))+'>°</mtext>', $(this));
		})
		.on('click', '.touche-infini', function() {
			addEntry('<mtext'+mathstyle($(this))+'>∞</mtext>', $(this));
		})
		.on('click', '.touche-implication', function() {
			addEntry('<mtext'+mathstyle($(this))+'>⇒</mtext>', $(this));
		})
		.on('click', '.touche-equivalence', function() {
			addEntry('<mtext'+mathstyle($(this))+'>⇔</mtext>', $(this));
		})
		.on('click', '.touche-pourtout', function() {
			addEntry('<mtext'+mathstyle($(this))+'>∀</mtext>', $(this));
		})
		.on('click', '.touche-existence', function() {
			addEntry('<mtext'+mathstyle($(this))+'>∃</mtext>', $(this));
		})
		.on('click', '.touche-ensemble-vide', function() {
			addEntry('<mtext'+mathstyle($(this))+'>∅</mtext>', $(this));
		})
		.on('click', '.touche-isin', function() {
			addEntry('<mtext'+mathstyle($(this))+'>∈</mtext><mspace width=".1em" />', $(this));
		})
		.on('click', '.touche-notin', function() {
			addEntry('<mtext'+mathstyle($(this))+'>∉</mtext><mspace width=".1em" />', $(this));
		})
		.on('click', '.touche-inclusion', function() {
			addEntry('<mtext'+mathstyle($(this))+'>⊂</mtext>', $(this));
		})
		.on('click', '.touche-cap', function() {
			addEntry('<mtext'+mathstyle($(this))+'>∩</mtext>', $(this));
		})
		.on('click', '.touche-cup', function() {
			addEntry('<mtext'+mathstyle($(this))+'>∪</mtext>', $(this));
		})
		.on('click', '.touche-prive', function() {
			addEntry('<mtext'+mathstyle($(this))+'>∖</mtext>', $(this));
		})
		.on('click', '.touche-alpha', function() {
			addEntry('<mtext'+mathstyle($(this))+'>α</mtext>', $(this));
		})
		.on('click', '.touche-beta', function() {
			addEntry('<mtext'+mathstyle($(this))+'>β</mtext>', $(this));
		})
		.on('click', '.touche-epsilon', function() {
			addEntry('<mtext'+mathstyle($(this))+'>ε</mtext>', $(this));
		})
		.on('click', '.touche-theta', function() {
			addEntry('<mtext'+mathstyle($(this))+'>θ</mtext>', $(this));
		})
		.on('click', '.touche-theta-prime', function() {
			addEntry('<mtext'+mathstyle($(this))+'>θ</mtext><mo>′</mo>', $(this));
		})
		.on('click', '.touche-lambda', function() {
			addEntry('<mtext'+mathstyle($(this))+'>λ</mtext>', $(this));
		})
		.on('click', '.touche-mu', function() {
			addEntry('<mtext'+mathstyle($(this))+'>μ</mtext>', $(this));
		})
		.on('click', '.touche-pi', function() {
			addEntry('<mtext'+mathstyle($(this))+'>π</mtext>', $(this));
		})
		.on('click', '.touche-tau', function() {
			addEntry('<mtext'+mathstyle($(this))+'>τ</mtext>', $(this));
		})
		.on('click', '.touche-omega', function() {
			addEntry('<mtext'+mathstyle($(this))+'>ω</mtext>', $(this));
		})
		.on('click', '.touche-delta', function() {
			addEntry('<mtext'+mathstyle($(this))+'>δ</mtext>', $(this));
		})
		.on('click', '.touche-Delta', function() {
			addEntry('<mtext'+mathstyle($(this))+'>Δ</mtext>', $(this));
		})
		.on('click', '.touche-Omega', function() {
			addEntry('<mtext'+mathstyle($(this))+'>Ω</mtext>', $(this));
		})
		.on('click', '.touche-prod', function() {
			addEntry('<mtext'+mathstyle($(this))+'>∏</mtext>', $(this));
		})
		.on('click', '.touche-espace', function() {
			addEntry('<mspace width=".1em"></mspace>', $(this));
		})
		.on('click', '.touche-retour-ligne', function() {
			addEntry('<mspace linebreak="newline"></mspace>', $(this));
		})
		.on('click', '.touche-align', function() {
			var mtable = $('<mtable columnalign="right left" columnspacing="0.28em" displaystyle="true"></mtable>');
			var mtr = $('<mtr></mtr>');
			var mtd = $('<mtd></mtd>');
			var nl = 0;
			$($(this).closest('.popover').next('input[type="hidden"]').val()).each(function() {
				if ($(this).text() == '=' || ( $(this).attr('width')=='.1em' && nl == 1 )) {
					mtr.append(mtd);
					mtd = $('<mtd></mtd>').append($(this));
					nl = 0;
				}
				else if ( $(this).attr('linebreak')=='newline' ) {
					mtr.append(mtd);
					mtable.append(mtr);
					mtr = $('<mtr></mtr>');
					mtd = $('<mtd></mtd>');
					nl = 1;
				}
				else {
					mtd.append($(this));
					nl = 0;
				}
			});
			mtr.append(mtd);
			mtable.append(mtr);
			
			$(this).closest('.popover').next('input[type="hidden"]').val('');
			
			addEntry(mtable.wrap('<temp />').parent().html(), $(this));
		})
		
		// Vecteur
		.on('click', '.touche-vecteur', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer un vecteur</h3> </div> <div class="modal-body"> <div style="margin-left:40%;" class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden" /> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-vecteur"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-vecteur', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<mover><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow><mo>→</mo></mover>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Angle
		.on('click', '.touche-angle', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer un angle</h3> </div> <div class="modal-body"> <div style="margin-left:40%;" class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden" /> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-angle"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-angle', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<mover><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow><mo>^</mo></mover>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Limite
		.on('click', '.touche-limite', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer une limite</h3> </div> <div class="modal-body"> <div class="pull-left" style="margin-left:30%;"><br> Limite de <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math> </div> <input type="hidden" value=""/> </div> <span class="position-relative-down-20"> quand <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/></span> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-limite"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-limite', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<munder><mo>lim</mo><mrow> ' + $(this).closest('.modal').find('input[type="hidden"]').last().val() + ' </mrow></munder><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Crochet avec bornes
		.on('click', '.touche-crochetd-bornes', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer un crochet (bornes)</h3> </div> <div class="modal-body"> <div style="margin-left:30%;"><br> Borne haute : <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math> </div> <input type="hidden" value=""/> </div> <div style="margin-left:30%;"><span class="position-relative-down-20"> Borne basse : <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/></span></div> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-crochetd-bornes"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-crochetd-bornes', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<munderover><mrow><mo>]</mo><mspace width=".3em"></mspace></mrow><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').last().val() + '</mrow><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow></munderover>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Accolade 2
		.on('click', '.touche-acol2', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer un système (2)</h3> </div> <div class="modal-body"> <div style="margin-left:30%;"><br> Ligne 1 : <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math> </div> <input type="hidden" value=""/> </div> <div style="margin-left:30%;"><span class="position-relative-down-20"> Ligne 2 : <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/></span></div> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-acol2"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-acol2', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<mrow><mo>{</mo><mtable><mtr><mtd>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mtd></mtr><mtr><mtd>' + $(this).closest('.modal').find('input[type="hidden"]').last().val() + '</mtd></mtr></mtable></mrow>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Accolade 3
		.on('click', '.touche-acol3', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer un système (3)</h3> </div> <div class="modal-body"> <div style="margin-left:30%;"><br> Ligne 1 : <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math> </div> <input type="hidden" value=""/> </div> <div style="margin-left:30%;"> Ligne 2 : <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/> </div> <div style="margin-left:30%;"> Ligne 3 : <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/></div> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-acol3"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-acol3', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<mrow><mo>{</mo><mtable><mtr><mtd>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mtd></mtr><mtr><mtd>' + $(this).closest('.modal').find('input[type="hidden"]').eq(1).val() + '</mtd></mtr><mtr><mtd>' + $(this).closest('.modal').find('input[type="hidden"]').last().val() + '</mtd></mtr></mtable></mrow>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Accolade bas
		.on('click', '.touche-acol-bas', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer une accolade (dessous)</h3> </div> <div class="modal-body"> <div style="margin-left:30%;"><br> Au-dessus : <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math> </div> <input type="hidden" value=""/> </div> <div style="margin-left:30%;"><span class="position-relative-down-20"> En-dessous : <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/></span></div> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-acol-bas"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-acol-bas', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<munder><munder><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow><mo style="font-size:smaller">︸</mo></munder><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').last().val() + '</mrow></munder>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Vect
		.on('click', '.touche-vect', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Taille du vecteur</h3> </div> <div class="modal-body"> <div style="margin-left:30%;"><br> Nombre de coefficients : <select name="nb_lignes" class="span1"><option value="1">1</option><option value="2">2</option><option value="3" selected="selected">3</option></select> </div> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success create-vect"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.create-vect', function() {
			var nb_lignes = $(this).closest('.modal').find('select[name="nb_lignes"]').first().val();
			var layer = $(this).closest('.modal').attr('data-layer');
			layer = parseInt(layer) + 1;
			var matContent = '<div class="modal hide fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer une matrice</h3> </div> <div class="modal-body">';
			for (var i=1;i<=nb_lignes;i++) {
				matContent = matContent + '<div style="margin-left:30%;">';
					matContent = matContent + '<div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math> </div> <input type="hidden" value=""/>';
				matContent = matContent + '</div>';
			}
			matContent = matContent + '<br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-vect" data-nb-lignes="'+nb_lignes+'"><i class="icon icon-ok"></i></button> </div> </div></div> </div>'
			$('#modal-stack').append(matContent);
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-vect', function() {
			var nb_lignes = $(this).attr('data-nb-lignes');
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 2;
			var toInsert = '<mfenced open="(" close=")" separators=""><mtable>';
			for (var i=1;i<=nb_lignes;i++) {
				toInsert = toInsert + '<mtr>';
					toInsert = toInsert + '<mtd>' + $(this).closest('.modal').find('input[type="hidden"]').eq(i-1).val() + '</mtd>';
				toInsert = toInsert + '</mtr>';
			}
			toInsert = toInsert + '</mtable></mfenced>';
			$(this).closest('.modal').prev().remove();
			$(this).closest('.modal').on('hidden.bs.modal', function() { $(this).remove(); }).modal('hide');
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Matrice
		.on('click', '.touche-mat', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Taille de la matrice</h3> </div> <div class="modal-body"> <div style="margin-left:30%;"><br> Nombre de lignes : <select name="nb_lignes" class="span1"><option value="1">1</option><option value="2" selected="selected">2</option><option value="3">3</option><option value="4">4</option></select> </div> <div style="margin-left:30%;"> Nombre de colonnes : <select name="nb_colonnes" class="span1"><option value="1">1</option><option value="2" selected="selected">2</option><option value="3">3</option><option value="4">4</option></select> </div> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success create-mat"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.create-mat', function() {
			var nb_lignes = $(this).closest('.modal').find('select[name="nb_lignes"]').first().val();
			var nb_colonnes = $(this).closest('.modal').find('select[name="nb_colonnes"]').last().val();
			var layer = $(this).closest('.modal').attr('data-layer');
			layer = parseInt(layer) + 1;
			var matContent = '<div class="modal hide fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer une matrice</h3> </div> <div class="modal-body">';
			for (var i=1;i<=nb_lignes;i++) {
				matContent = matContent + '<div style="margin-left:30%;">';
				for (var j=1;j<=nb_colonnes;j++) {
					matContent = matContent + '<div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math> </div> <input type="hidden" value=""/>';
				}
				matContent = matContent + '</div>';
			}
			matContent = matContent + '<br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-mat" data-nb-lignes="'+nb_lignes+'" data-nb-colonnes="'+nb_colonnes+'"><i class="icon icon-ok"></i></button> </div> </div></div> </div>'
			$('#modal-stack').append(matContent);
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-mat', function() {
			var nb_lignes = $(this).attr('data-nb-lignes');
			var nb_colonnes = $(this).attr('data-nb-colonnes');
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 2;
			var toInsert = '<mfenced open="(" close=")" separators=""><mtable>';
			for (var i=1;i<=nb_lignes;i++) {
				toInsert = toInsert + '<mtr>';
				for (var j=1;j<=nb_colonnes;j++) {
					var coef = (i-1)*nb_colonnes + j - 1;
					toInsert = toInsert + '<mtd>' + $(this).closest('.modal').find('input[type="hidden"]').eq(coef).val() + '</mtd>';
				}
				toInsert = toInsert + '</mtr>';
			}
			toInsert = toInsert + '</mtable></mfenced>';
			$(this).closest('.modal').prev().remove();
			$(this).closest('.modal').on('hidden.bs.modal', function() { $(this).remove(); }).modal('hide');
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Intégrale
		.on('click', '.touche-integrale', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer une intégrale</h3> </div> <div class="modal-body"> <div ><br> Intégrale de <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math> </math> </div> <input type="hidden" value=""/> </div> <span class="position-relative-down-20"> entre <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/> et <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/> (variable : <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/> )    </span> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-integrale"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-integrale', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<munderover><mo>∫</mo><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').eq(1).val() + '</mrow><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').eq(2).val() + '</mrow></munderover><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow><mi>d</mi>' + $(this).closest('.modal').find('input[type="hidden"]').last().val();
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Primitive
		.on('click', '.touche-primitive', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer une intégrale</h3> </div> <div class="modal-body"> <div ><br> Primitive de <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math> </math> </div> <input type="hidden" value=""/> </div> <span class="position-relative-down-20"> (variable : <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/> )  </span> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-primitive"><i class="icon icon-ok"></i></button> </div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-primitive', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<mo>∫</mo><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow><mi>d</mi><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').last().val() + '</mrow>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Somme
		.on('click', '.touche-somme', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer une somme</h3> </div> <div class="modal-body"> <div ><br> Somme de <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math> </math> </div> <input type="hidden" value=""/> </div> <span class="position-relative-down-20"> de <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/> à <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/> </span> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-somme"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-somme', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<munderover><mo>∑</mo><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').eq(1).val() + '</mrow><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').last().val() + '</mrow></munderover><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Division
		.on('click', '.touche-divise', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;	
			var numerator = '';
			var beforeFrac = $(this).closest('.popover').next('input[type="hidden"]').val();
			if ( beforeFrac != '') {
				if (beforeFrac.substring(beforeFrac.length-10) == '<mo>)</mo>') {
					beforeFrac.split('');
					var oparen = 0;
					var fparen = 0;
					for(var i= beforeFrac.length-1;i>=0;i--) {
						if ( beforeFrac[i] == ')' ) {
							fparen++;
						}
						else if ( beforeFrac[i] == '(' ) {
							oparen++;
						}
						if ( fparen > 0 && oparen == fparen ) {
							var numerator = beforeFrac.substring(i+6, beforeFrac.length-10);
							break;
						}
					}
					$(this).closest('.popover').next('input[type="hidden"]').val($(this).closest('.popover').next('input[type="hidden"]').val().substring(0, $(this).closest('.popover').next('input[type="hidden"]').val().length - parseInt(numerator.length)-20));
				}
				else {
					beforeFracArray = beforeFrac.split('</mo>');
					var numerator = beforeFracArray[beforeFracArray.length-1];
					$(this).closest('.popover').next('input[type="hidden"]').val($(this).closest('.popover').next('input[type="hidden"]').val().substring(0, $(this).closest('.popover').next('input[type="hidden"]').val().length - parseInt(numerator.length)));
				}			
			}
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel">&divide; Insérer une fraction</h3> </div> <div class="modal-body"> <div style="margin-left:40%;" class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden" value="' + numerator + '"/> <hr> <div style="margin-left:40%;" class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/><br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" data-dismiss="modal"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-division"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
			var numeratorCase = $('#modal-stack').children().last().find('.mathscase').first();
			var insertIn = MathJax.Hub.getAllJax(numeratorCase[0])[0];
			MathJax.Hub.queue.Push(["Text", insertIn, "<math>"+numeratorCase.next().val()+"</math>"]);
		})
		.on('click', 'button.insert-division', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<mfrac><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').last().val() + '</mrow></mfrac>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Barre
		.on('click', '.touche-barre', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer un conjugué</h3> </div> <div class="modal-body"> <div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden" /> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-barre"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-barre', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<mover><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow><mo>_</mo></mover>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Exponentielle
		.on('click', '.touche-exp', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;	
			var numerator = '<mi>e</mi>';	
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel">^ Insérer une puissance</h3> </div> <div class="modal-body"> <div class="pull-left" style="margin-left:40%;"><br><div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden" value="' + numerator + '"/></div> <span class="position-relative-down-12"><div class="mathscase case-indice simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/></span> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-exp"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
			var numeratorCase = $('#modal-stack').children().last().find('.mathscase').first();
			var insertIn = MathJax.Hub.getAllJax(numeratorCase[0])[0];
			MathJax.Hub.queue.Push(["Text", insertIn, "<math>"+numeratorCase.next().val()+"</math>"]);
		})
		.on('click', 'button.insert-exp', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<msup><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').last().val() + '</mrow></msup>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Puissance
		.on('click', '.touche-puissance', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;	
			var numerator = '';
			var beforeFrac = $(this).closest('.popover').next('input[type="hidden"]').val();
			if ( beforeFrac != '') {
				if (beforeFrac.substring(beforeFrac.length-10) == '<mo>)</mo>') {
					beforeFrac.split('');
					var oparen = 0;
					var fparen = 0;
					for(var i= beforeFrac.length-1;i>=0;i--) {
						if ( beforeFrac[i] == ')' ) {
							fparen++;
						}
						else if ( beforeFrac[i] == '(' ) {
							oparen++;
						}
						if ( fparen > 0 && oparen == fparen ) {
							var numerator = beforeFrac.substring(i-4, beforeFrac.length);
							break;
						}
					}
					$(this).closest('.popover').next('input[type="hidden"]').val($(this).closest('.popover').next('input[type="hidden"]').val().substring(0, $(this).closest('.popover').next('input[type="hidden"]').val().length - parseInt(numerator.length)));
				}
				else {
					beforeFracArray = beforeFrac.split('</mo>');
					var numerator = beforeFracArray[beforeFracArray.length-1];
					$(this).closest('.popover').next('input[type="hidden"]').val($(this).closest('.popover').next('input[type="hidden"]').val().substring(0, $(this).closest('.popover').next('input[type="hidden"]').val().length - parseInt(numerator.length)));
				}			
			}	
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel">^ Insérer une puissance</h3> </div> <div class="modal-body"> <div class="pull-left" style="margin-left:40%;"><br><div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden" value="' + numerator + '"/></div> <span class="position-relative-down-12"><div class="mathscase case-indice simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/></span> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-puissance"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
			var numeratorCase = $('#modal-stack').children().last().find('.mathscase').first();
			var insertIn = MathJax.Hub.getAllJax(numeratorCase[0])[0];
			MathJax.Hub.queue.Push(["Text", insertIn, "<math>"+numeratorCase.next().val()+"</math>"]);
		})
		.on('click', 'button.insert-puissance', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<msup><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').last().val() + '</mrow></msup>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Indice
		.on('click', '.touche-indice', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;	
			var numerator = '';
			var beforeFrac = $(this).closest('.popover').next('input[type="hidden"]').val();
			if ( beforeFrac != '') {
				if (beforeFrac.substring(beforeFrac.length-10) == '<mo>)</mo>') {
					beforeFrac.split('');
					var oparen = 0;
					var fparen = 0;
					for(var i= beforeFrac.length-1;i>=0;i--) {
						if ( beforeFrac[i] == ')' ) {
							fparen++;
						}
						else if ( beforeFrac[i] == '(' ) {
							oparen++;
						}
						if ( fparen > 0 && oparen == fparen ) {
							var numerator = beforeFrac.substring(i-4, beforeFrac.length);
							console.log(numerator);
							break;
						}
					}
					$(this).closest('.popover').next('input[type="hidden"]').val($(this).closest('.popover').next('input[type="hidden"]').val().substring(0, $(this).closest('.popover').next('input[type="hidden"]').val().length - parseInt(numerator.length)));
				}
				else {
					beforeFracArray = beforeFrac.split('</mo>');
					var numerator = beforeFracArray[beforeFracArray.length-1];
					$(this).closest('.popover').next('input[type="hidden"]').val($(this).closest('.popover').next('input[type="hidden"]').val().substring(0, $(this).closest('.popover').next('input[type="hidden"]').val().length - parseInt(numerator.length)));
				}			
			}	
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel">Insérer un indice</h3> </div> <div class="modal-body"> <div class="pull-left" style="margin-left:40%;"><br><div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden" value="' + numerator + '"/></div> <span class="position-relative-down-35"><div class="mathscase case-indice simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden"/></span> <br><br> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-indice"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
			MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
			var numeratorCase = $('#modal-stack').children().last().find('.mathscase').first();
			var insertIn = MathJax.Hub.getAllJax(numeratorCase[0])[0];
			MathJax.Hub.queue.Push(["Text", insertIn, "<math>"+numeratorCase.next().val()+"</math>"]);
		})
		.on('click', 'button.insert-indice', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<msub><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</mrow><mrow>' + $(this).closest('.modal').find('input[type="hidden"]').last().val() + '</mrow></msub>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		})
		
		// Racine
		.on('click', '.touche-racine', function() {
			var layer = $(this).closest('.popover').prev().attr('data-layer');
			layer = parseInt(layer) + 1;
			$('#modal-stack').append('<div class="modal fade" data-layer="' + layer + '" data-backdrop="static" data-keyboard="false" > <div class="modal-dialog"><div class="modal-content"> <div class="modal-header"> <h3 id="myModalLabel"> Insérer une racine</h3> </div> <div class="modal-body"> <span style="margin-left:40%;font-size:30px;position:relative;top:5px;">&#8730;</span><div class="mathscase case simple cursor" data-clavier="general" data-layer="' + layer + '"><math></math></div><input type="hidden" /> </div> <div class="modal-footer"> <button type="button" class="btn btn-danger close-modal" aria-hidden="true"><i class="icon icon-remove"></i></button> <button type="button" class="btn btn-success insert-racine"><i class="icon icon-ok"></i></button> </div> </div></div> </div>');
			$('#modal-stack').children().last().prev().modal('hide');
			$('#modal-stack').children().last().modal('show');
		})
		.on('click', 'button.insert-racine', function() {
			var layer = $(this).closest('.modal').attr('data-layer');
			var decrementedLayer = parseInt(layer) - 1;
			var toInsert = '<msqrt>' + $(this).closest('.modal').find('input[type="hidden"]').first().val() + '</msqrt>';
			switchModals( $(this).closest('.modal') );
			delegateToPopover(decrementedLayer, toInsert);
		});