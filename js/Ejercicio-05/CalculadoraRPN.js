"use strict";
class CalculadoraRPN {
	constructor() {
		this.pila = new Array();
		this.entrada = "0";
		this.nuevaEntrada = true;
	}

	añadirAEntrada(caracter) {
		if (this.nuevaEntrada) {
			this.entrada = caracter.toString();
			this.nuevaEntrada = false;
		} else {
			this.entrada += caracter.toString();
		}

		document.getElementById("entrada").innerText = this.entrada;
	}

	enter() {
		this.pila.push(this.entrada);
		this.refrescarPantalla();
		this.nuevaEntrada = true;
		this.entrada = "0";
		document.getElementById("entrada").innerText = this.entrada;
	}

	refrescarPantalla() {
		var elementosPila = "";

		for (var i in this.pila)
			elementosPila += ("<p>" + this.pila[i] + "</p>");

		document.getElementById("pila").innerHTML = elementosPila;
	}

	punto() {
		this.añadirAEntrada('.');
	}

	digitos(digito) {
		this.añadirAExpresion(digito);
	}

	suma() {
		if (this.pila.length > 1) {
			var resultado = parseFloat(this.pila.pop()) + parseFloat(this.pila.pop());
			this.pila.push(resultado);
			this.refrescarPantalla();
		}
	}

	resta() {
		if (this.pila.length > 1) {
			var sustraendo = this.pila.pop();
			var minuendo = this.pila.pop();
			var resultado = minuendo - sustraendo;

			this.pila.push(resultado);
			this.refrescarPantalla();
		}
	}

	multiplicacion() {
		if (this.pila.length > 1) {
			var resultado = this.pila.pop() * this.pila.pop();
			this.pila.push(resultado);
			this.refrescarPantalla();
		}
	}

	division() {
		if (this.pila.length > 1) {
			var divisor = this.pila.pop();
			var dividendo = this.pila.pop();
			var resultado = dividendo / divisor;

			this.pila.push(resultado);
			this.refrescarPantalla();
		}
	}

	borrar() {
		this.pila = new Array();
		this.entrada = "0";
		document.getElementById("entrada").innerText = this.entrada;
		this.nuevaEntrada = true;
		this.refrescarPantalla();
	}

	raizCuadrada() {
		var ultimoOperando = parseFloat(this.pila.pop());
		var raiz = Math.sqrt(ultimoOperando);
		this.pila.push(raiz);
		this.refrescarPantalla();
	}

	numeroE() {
		this.añadirAEntrada(Math.E);
	}

	log() {
		var ultimoOperando = parseFloat(this.pila.pop());
		var logaritmo = Math.log(ultimoOperando);
		this.pila.push(logaritmo);
		this.refrescarPantalla();
	}

	cuadrado() {
		var ultimoOperando = parseFloat(this.pila.pop());
		var cuadrado = Math.pow(ultimoOperando, 2);
		this.pila.push(cuadrado);
		this.refrescarPantalla();
	}

	arcoSeno() {
		var ultimoOperando = parseFloat(this.pila.pop());
		var arcoSeno = Math.asin(ultimoOperando);
		this.pila.push(arcoSeno);
		this.refrescarPantalla();
	}

	arcoCoseno() {
		var ultimoOperando = parseFloat(this.pila.pop());
		var arcoCoseno = Math.acos(ultimoOperando);
		this.pila.push(arcoCoseno);
		this.refrescarPantalla();
	}

	arcoTangente() {
		var ultimoOperando = parseFloat(this.pila.pop());
		var arcoTangente = Math.atan(ultimoOperando);
		this.pila.push(arcoTangente);
		this.refrescarPantalla();
	}

	potencial() {
		var exponente = parseFloat(this.pila.pop());
		var base = parseFloat(this.pila.pop());
		var potencia = Math.pow(base, exponente);
		this.pila.push(potencia);
		this.refrescarPantalla();
	}

	seno() {
		var ultimoOperando = parseFloat(this.pila.pop());
		var seno = Math.sin(ultimoOperando);
		this.pila.push(seno);
		this.refrescarPantalla();
	}

	coseno() {
		var ultimoOperando = parseFloat(this.pila.pop());
		var coseno = Math.cos(ultimoOperando);
		this.pila.push(coseno);
		this.refrescarPantalla();
	}

	tangente() {
		var ultimoOperando = parseFloat(this.pila.pop());
		var tangente = Math.tan(ultimoOperando);
		this.pila.push(tangente);
		this.refrescarPantalla();
	}
}

var calculadora = new CalculadoraRPN();