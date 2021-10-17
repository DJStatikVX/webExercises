"use strict";
class Calculadora {
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
			document.getElementById("pantalla").value = "Error = " + err;
			this.resetearPantalla = true;
		}
	}
}

var calculadora = new Calculadora();