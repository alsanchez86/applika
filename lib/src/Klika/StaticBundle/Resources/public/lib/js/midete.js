var talla 	= null;
var indice	= null;
var medidas = [
		{talla: 0, 	pecho: 48, 	cintura: 50, 	cadera: 0, 		largoTotal: 58 , largoFalda: 41},
		{talla: 2, 	pecho: 52, 	cintura: 52, 	cadera: 0, 		largoTotal: 65 , largoFalda: 45},
		{talla: 4, 	pecho: 56, 	cintura: 54, 	cadera: 0, 		largoTotal: 76,  largoFalda: 53},
		{talla: 6, 	pecho: 60, 	cintura: 56, 	cadera: 0, 		largoTotal: 87 , largoFalda: 61},
		{talla: 8, 	pecho: 64, 	cintura: 58, 	cadera: 0, 		largoTotal: 98 , largoFalda: 68},
		{talla: 10, pecho: 68, 	cintura: 60, 	cadera: 0, 		largoTotal: 105, largoFalda: 74},
		{talla: 12, pecho: 72, 	cintura: 62.5, 	cadera: 0, 		largoTotal: 114, largoFalda: 80},
		{talla: 14, pecho: 76, 	cintura: 66, 	cadera: 0, 		largoTotal: 129, largoFalda: 89},
		{talla: 34, pecho: 79, 	cintura: 59 , 	cadera: 88, 	largoTotal: 140, largoFalda: 95},
		{talla: 36, pecho: 83, 	cintura: 63	, 	cadera: 92, 	largoTotal: 140, largoFalda: 95},
		{talla: 38, pecho: 87, 	cintura: 67 , 	cadera: 96, 	largoTotal: 140, largoFalda: 95},
		{talla: 40, pecho: 91, 	cintura: 71 , 	cadera: 100, 	largoTotal: 140, largoFalda: 95},
		{talla: 42, pecho: 95, 	cintura: 76 , 	cadera: 104, 	largoTotal: 140, largoFalda: 95},
		{talla: 44, pecho: 99, 	cintura: 81 , 	cadera: 108, 	largoTotal: 140, largoFalda: 95},
		{talla: 46, pecho: 105, cintura: 88 , 	cadera: 115, 	largoTotal: 140, largoFalda: 95},
		{talla: 48, pecho: 111, cintura: 94 , 	cadera: 121, 	largoTotal: 140, largoFalda: 95},
		{talla: 50, pecho: 117, cintura: 101, 	cadera: 127, 	largoTotal: 140, largoFalda: 95},
		{talla: 52, pecho: 123, cintura: 108, 	cadera: 134, 	largoTotal: 140, largoFalda: 95},
		{talla: 54, pecho: 129, cintura: 115, 	cadera: 140, 	largoTotal: 140, largoFalda: 95}
	];

function probador () {

	$('#pecho, #cintura, #cadera, #largo-total, #largo-falda').keyup (function () {

		calculaTalla ();
	});
}

function calculaTalla () {

	var pecho 		= parseFloat($('#pecho').val());
	var cintura 	= parseFloat($('#cintura').val());
	var cadera 		= parseFloat($('#cadera').val());
	/*var largoTotal 	= parseFloat($('#largo-total').val());
	var largoFalda 	= parseFloat($('#largo-falda').val());*/

	if (pecho) {

		for (var i = 0; i < medidas.length; i++) {

			if (medidas [i]['pecho'] >= pecho) {

				indice = i;
				talla  = medidas[indice].talla;
				$('#resultado').val(talla);
				break;

			}else if (i == (medidas.length - 1)) {

				indice = medidas.length - 1;
				$('#resultado').val(medidas [indice].talla);
			}
		}

		if (cintura && medidas[indice]['cintura'] < cintura) 			buscarTalla ('cintura', cintura);
		if (cadera && medidas[indice]['cadera'] < cadera)	 			buscarTalla ('cadera', cadera);
		/*if (largoTotal && medidas[indice]['largoTotal'] < largoTotal)	buscarTalla ('largoTotal', largoTotal);
		if (largoFalda && medidas[indice]['largoFalda'] < largoFalda)	buscarTalla ('largoFalda', largoFalda);*/
	}

	diferencia ();
}

function buscarTalla (buscar, valor) {

	for (var i = indice; i < medidas.length; i++) {

		if (medidas [i][buscar] >= valor) {

			if (i > indice){
				indice = i;
				talla  = medidas[indice].talla;
				$('#resultado').val(talla);
			}
			break;

		}else if (i == (medidas.length - 1)) {

			indice = medidas.length - 1;
			$('#resultado').val(medidas [indice].talla);
			break;
		}
	}

	diferencia ();
}

function diferencia () {

	var pecho 		= parseFloat($('#pecho').val());
	var cintura 	= parseFloat($('#cintura').val());
	var cadera 		= parseFloat($('#cadera').val());
	var largoTotal 	= parseFloat($('#largo-total').val());
	var largoFalda 	= parseFloat($('#largo-falda').val());

	if (pecho) {

		pintaDiferencia (medidas [indice]['pecho'], 		pecho, 		$('#en-pecho'));
		pintaDiferencia (medidas [indice]['cintura'], 		cintura, 	$('#en-cintura'));
		pintaDiferencia (medidas [indice]['cadera'], 		cadera, 	$('#en-cadera'));
		pintaDiferencia (medidas [indice]['largoTotal'], 	largoTotal, $('#en-largo-total'));
		pintaDiferencia (medidas [indice]['largoFalda'], 	largoFalda, $('#en-largo-falda'));

	}else $('#resultado, #en-pecho, #en-cintura, #en-cadera, #en-largo-total, #en-largo-falda').val (null);
}

function pintaDiferencia (medidaTalla, medidaIntroducida, objeto) {

	if (! medidaIntroducida || medidaIntroducida < 0) return objeto.val (null);

	var diferencia = parseFloat((medidaTalla - medidaIntroducida).toFixed(2));

	if (diferencia < 0) objeto.css ({color: 'red'});
	else 				objeto.css ({color: '#666'});

	objeto.val (diferencia);
}

function cambiaTalla (aumenta) {

	if (aumenta && indice < (medidas.length - 1)) 	indice++;
	if (! aumenta && indice > 0) 					indice--;

	talla = medidas[indice].talla;
	$('#resultado').val(talla);

	diferencia ();
}

function seleccionarTalla () {

	if (indice != null) {

		var talla = medidas[indice].talla;
		$('#talla').val (talla);

	}else alert('Debe seleccionar una talla.');
}