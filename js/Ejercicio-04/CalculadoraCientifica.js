"use strict";
class CalculadoraBasica {
    constructor() {
        this.pantalla = "";
		this.memoria = 0;
		this.resultado = 0;
		this.resetearPantalla = false;
	}

	añadirAExpresion(caracter) {
		if (this.resetearPantalla || this.pantalla.includes("NaN") || this.pantalla.includes("Infinity"))
			this.borrar();
			
        this.pantalla += caracter;
        document.getElementById("pantalla").value = this.pantalla;
    }
	
	digitos(digito) {
		this.añadirAExpresion(digito);
	}

	punto() {
		this.añadirAExpresion('.');
	}

	suma() {
		this.añadirAExpresion('+');
	}

	resta() {
		this.añadirAExpresion('-');
	}

	multiplicacion() {
		this.añadirAExpresion('*');
	}

	division() {
		this.añadirAExpresion('/');
	}

	borrar() {
		this.pantalla = "";
		this.resultado = 0;
		this.resetearPantalla = false;
		document.getElementById("pantalla").value = this.pantalla;
	}
	
	mrc() {
		this.pantalla += eval(this.memoria);
		document.getElementById("pantalla").value += eval(this.memoria);
	}
	
	mMas() {
		this.memoria += document.getElementById("pantalla").value;
		this.borrar();
	}

	mMenos() {
		this.memoria -= document.getElementById("pantalla").value;
		this.borrar();
	}

	igual() {
		try {
			document.getElementById("pantalla").value = eval(this.pantalla);
			this.resultado = eval(this.pantalla);
			this.pantalla = this.resultado.toString();
		} catch (err) {
			document.getElementById("pantalla").value = "Error";
			this.resetearPantalla = true;
		}
	}
}

class CalculadoraCientifica extends CalculadoraBasica {
	constructor() {
		super();
		this.hayOperador = false;
		this.operandos = [];
	}

	añadirAExpresion(caracter) {
		// Ante un error
		if (this.resetearPantalla || this.pantalla.toString().includes("NaN") || this.pantalla.toString().includes("Infinity"))
			this.borrar();

		// Rellenar el exp
		if (("+-*/^").includes(caracter.toString()) && this.pantalla.toString().endsWith("*10^")) {
			this.digitos(0);
		}
		
		if (this.operandos.length == 0 && ("+-*/^").includes(caracter.toString)) {
			this.operandos.push(this.pantalla);
		}

		// Resolver cada dos operandos
		if (this.hayOperador && this.operandos.length != 0 && ("+-*/^").includes(caracter.toString())) {
			this.igual();
			document.getElementById("pantalla").value = this.resultado;
			this.hayOperador = false;
		}

		// Añadir operador
		if (!this.hayOperador && ("+-*/^").includes(caracter.toString())) {
			this.operandos.push(this.pantalla);
			this.hayOperador = true;

		// Añadir primer operando
		} else if ((this.hayOperador && !(("+-*/^").includes(caracter.toString())))
					|| (!this.hayOperador && !(("+-*/^.").includes(caracter.toString())) && this.operandos.length != 0)) {
			this.operandos.push(caracter);
		}

		// Escribir en pantalla
		this.pantalla += caracter;
		document.getElementById("pantalla").value = this.pantalla;
	}

	suma() {
		if (!this.pantalla.toString().endsWith("+"))
			super.suma();
	}

	resta() {
		if (!this.pantalla.toString().endsWith("-"))
			super.resta();
	}

	multiplicacion() {
		if (!this.pantalla.toString().endsWith("*"))
			super.multiplicacion();
	}

	division() {
		if (!this.pantalla.toString().endsWith("/"))
			super.division();
	}

	borrar() {
		super.borrar();
		this.hayOperador = false;
		this.operandos = [];
	}

	igual() {
		this.pantalla = this.pantalla.toString().replace("^", "**");
		this.pantalla = this.pantalla.toString().replace("e", "Math.E");
		super.igual();
		this.hayOperador = false;
		this.operandos = [this.resultado];
	}

	aplicarFuncion(resultadoFuncion) {
		if (this.operandos.length == 0)
			this.operandos.push(this.pantalla);

		var pos = -1;

		for (let i = this.pantalla.length; i >= 0; i--) {
			if (("+-*/^").includes(this.pantalla[i])) {
				pos = i + 1;
				break;
			}
		}

		if (pos != -1) {
			this.pantalla = this.pantalla.toString().substring(0, pos);
			this.pantalla = this.pantalla.concat(resultadoFuncion.toString());
			document.getElementById("pantalla").value = this.pantalla;
		}
	}

	raizCuadrada() {
		if (this.operandos.length == 0) {
			this.resultado = Math.sqrt(this.pantalla);
			this.pantalla = this.resultado;
			document.getElementById("pantalla").value = this.resultado;
		} else {
			var ultimoOperando = this.buscarUltimoOperando();
			var raiz = Math.sqrt(ultimoOperando);
			this.aplicarFuncion(raiz);
		}
	}

	seno() {
		if (this.operandos.length == 0) {
			this.resultado = Math.sin(this.pantalla);
			this.pantalla = this.resultado;
			document.getElementById("pantalla").value = this.resultado;
		} else {
			var ultimoOperando = this.buscarUltimoOperando();
			var seno = Math.sin(ultimoOperando);
			this.aplicarFuncion(seno);
		}
	}

	arcoSeno() {
		if (this.operandos.length == 0) {
			this.resultado = Math.asin(this.pantalla);
			this.pantalla = this.resultado;
			document.getElementById("pantalla").value = this.resultado;
		} else {
			var ultimoOperando = this.buscarUltimoOperando();
			var arcoSeno = Math.asin(ultimoOperando);
			this.aplicarFuncion(arcoSeno);
		}
	}

	coseno() {
		if (this.operandos.length == 0) {
			this.resultado = Math.cos(this.pantalla);
			this.pantalla = this.resultado;
			document.getElementById("pantalla").value = this.resultado;
		} else {
			var ultimoOperando = this.buscarUltimoOperando();
			var coseno = Math.cos(ultimoOperando);
			this.aplicarFuncion(coseno);
		}
	}

	arcoCoseno() {
		if (this.operandos.length == 0) {
			this.resultado = Math.acos(this.pantalla);
			this.pantalla = this.resultado;
			document.getElementById("pantalla").value = this.resultado;
		} else {
			var ultimoOperando = this.buscarUltimoOperando();
			var arcoCoseno = Math.acos(ultimoOperando);
			this.aplicarFuncion(arcoCoseno);
		}
	}
	
	tangente() {
		if (this.operandos.length == 0) {
			this.resultado = Math.tan(this.pantalla);
			this.pantalla = this.resultado;
			document.getElementById("pantalla").value = this.resultado;
		} else {
			var ultimoOperando = this.buscarUltimoOperando();
			var tangente = Math.tan(ultimoOperando);
			this.aplicarFuncion(tangente);
		}
	}

	arcoTangente() {
		if (this.operandos.length == 0) {
			this.resultado = Math.atan(this.pantalla);
			this.pantalla = this.resultado;
			document.getElementById("pantalla").value = this.resultado;
		} else {
			var ultimoOperando = this.buscarUltimoOperando();
			var arcoTangente = Math.atan(ultimoOperando);
			this.aplicarFuncion(arcoTangente);
		}
	}

	cuadrado() {
		if (this.operandos.length == 0) {
			this.resultado = Math.pow(this.pantalla, 2);
			this.pantalla = this.resultado;
			document.getElementById("pantalla").value = this.resultado;
		} else {
			var ultimoOperando = this.buscarUltimoOperando();
			var cuadrado = Math.pow(ultimoOperando, 2);
			this.aplicarFuncion(cuadrado);
		}
	}

	buscarUltimoOperando() {
		var pos = -1;

		for (let i = this.pantalla.length; i >= 0; i--) {
			if (("+-*/^").includes(this.pantalla[i])) {
				pos = i + 1;
				break;
			}
		}

		if (pos != -1) {
			return this.pantalla.substring(pos, this.pantalla.length);
		}
	}

	potencial() {
		if (!this.pantalla.toString().endsWith("^"))
			this.añadirAExpresion("^");
	}

	numeroE() {
		if (!this.pantalla.toString().endsWith("e"))
			this.añadirAExpresion("e");
	}

	exp() {
		if (!this.pantalla.toString().endsWith("*10^"))
			this.añadirAExpresion("*10^");
	}

	log() {
		if (this.operandos.length == 0) {
			this.resultado = Math.log(this.pantalla);
			this.pantalla = this.resultado;
			document.getElementById("pantalla").value = this.resultado;
		} else {
			var ultimoOperando = this.buscarUltimoOperando();
			var logaritmo = Math.log(ultimoOperando);
			this.aplicarFuncion(logaritmo);
		}
	}
}

var calculadora = new CalculadoraCientifica();