package main;

import java.util.Date;
import java.util.List;

public class RutaEnMoto {
	
	private String nombre;
	private String tipo;
	private String dificultad;
	private Date fechaInicio;
	private Date fechaFin;
	private String duracion;
	private String agencia;
	private String descripcion;
	private String personas;
	private String inicioCiudad;
	private String inicioDireccion;
	private PuntoMapa inicioCoordenadas;
	private List<String> referencias;
	private double valoracion;
	private List<Hito> hitos;
	
	public RutaEnMoto(String nombre, String tipo, String dificultad, Date fechaInicio, 
			Date fechaFin, String duracion, String agencia, String descripcion, 
			String personas, String inicioCiudad, String inicioDireccion,
			PuntoMapa inicioCoordenadas, List<String> referencias, 
			double valoracion, List<Hito> hitos) {

		setNombre(nombre);
		setTipo(tipo);
		setDificultad(dificultad);
		setFechaInicio(fechaInicio);
		setFechaFin(fechaFin);
		setDuracion(duracion);
		setAgencia(agencia);
		setDescripcion(descripcion);
		setPersonas(personas);
		setInicioCiudad(inicioCiudad);
		setInicioDireccion(inicioDireccion);
		setInicioCoordenadas(inicioCoordenadas);
		setReferencias(referencias);
		setValoracion(valoracion);
		setHitos(hitos);
	}

	public String getNombre() {
		return nombre;
	}

	private void setNombre(String nombre) {
		this.nombre = nombre;
	}

	public String getTipo() {
		return tipo;
	}

	private void setTipo(String tipo) {
		this.tipo = tipo;
	}

	public String getDificultad() {
		return dificultad;
	}

	private void setDificultad(String dificultad) {
		this.dificultad = dificultad;
	}

	public Date getFechaInicio() {
		return fechaInicio;
	}

	private void setFechaInicio(Date fechaInicio) {
		this.fechaInicio = fechaInicio;
	}

	public Date getFechaFin() {
		return fechaFin;
	}

	private void setFechaFin(Date fechaFin) {
		this.fechaFin = fechaFin;
	}

	public String getDuracion() {
		return duracion;
	}

	private void setDuracion(String duracion) {
		this.duracion = duracion;
	}

	public String getAgencia() {
		return agencia;
	}

	private void setAgencia(String agencia) {
		this.agencia = agencia;
	}

	public String getDescripcion() {
		return descripcion;
	}

	private void setDescripcion(String descripcion) {
		this.descripcion = descripcion;
	}

	public String getPersonas() {
		return personas;
	}

	private void setPersonas(String personas) {
		this.personas = personas;
	}

	public String getInicioCiudad() {
		return inicioCiudad;
	}

	private void setInicioCiudad(String inicioCiudad) {
		this.inicioCiudad = inicioCiudad;
	}

	public String getInicioDireccion() {
		return inicioDireccion;
	}

	private void setInicioDireccion(String inicioDireccion) {
		this.inicioDireccion = inicioDireccion;
	}

	public PuntoMapa getInicioCoordenadas() {
		return inicioCoordenadas;
	}

	private void setInicioCoordenadas(PuntoMapa inicioCoordenadas) {
		this.inicioCoordenadas = inicioCoordenadas;
	}

	public List<String> getReferencias() {
		return referencias;
	}

	private void setReferencias(List<String> referencias) {
		this.referencias = referencias;
	}

	public double getValoracion() {
		return valoracion;
	}

	private void setValoracion(double valoracion) {
		this.valoracion = valoracion;
	}

	public List<Hito> getHitos() {
		return hitos;
	}

	private void setHitos(List<Hito> hitos) {
		this.hitos = hitos;
	}

	public class PuntoMapa {
		private double latitud;
		private double longitud;
		private String altitud;
		
		public PuntoMapa(double latitud, double longitud, String altitud) {
			setLatitud(latitud);
			setLongitud(longitud);
			setAltitud(altitud);
		}

		public double getLatitud() {
			return latitud;
		}

		private void setLatitud(double latitud) {
			this.latitud = latitud;
		}

		public double getLongitud() {
			return longitud;
		}

		private void setLongitud(double longitud) {
			this.longitud = longitud;
		}

		public String getAltitud() {
			return altitud;
		}

		private void setAltitud(String altitud) {
			this.altitud = altitud;
		}
		
	}
	
	public class Hito {
		private String nombre;
		private String descripcion;
		private PuntoMapa coordenadas;
		private Distancia distancia;
		private String tiempo;
		private List<String> galeriaFotos;
		private List<String> galeriaVideos;
		
		public Hito(String nombre, String descripcion, PuntoMapa coordenadas, 
				Distancia distancia, String tiempo, List<String> galeriaFotos, 
				List<String> galeriaVideos) {

			setNombre(nombre);
			setDescripcion(descripcion);
			setCoordenadas(coordenadas);
			setDistancia(distancia);
			setTiempo(tiempo);
			setGaleriaFotos(galeriaFotos);
			setGaleriaVideos(galeriaVideos);
		}
		
		public String getNombre() {
			return nombre;
		}
		
		private void setNombre(String nombre) {
			this.nombre = nombre;
		}
		
		public String getDescripcion() {
			return descripcion;
		}
		
		private void setDescripcion(String descripcion) {
			this.descripcion = descripcion;
		}
		
		public PuntoMapa getCoordenadas() {
			return coordenadas;
		}
		
		private void setCoordenadas(PuntoMapa coordenadas) {
			this.coordenadas = coordenadas;
		}
		
		public Distancia getDistancia() {
			return distancia;
		}
		
		private void setDistancia(Distancia distancia) {
			this.distancia = distancia;
		}
		
		public String getTiempo() {
			return tiempo;
		}
		
		private void setTiempo(String tiempo) {
			this.tiempo = tiempo;
		}
		
		public List<String> getGaleriaFotos() {
			return galeriaFotos;
		}
		
		private void setGaleriaFotos(List<String> galeriaFotos) {
			this.galeriaFotos = galeriaFotos;
		}
		
		public List<String> getGaleriaVideos() {
			return galeriaVideos;
		}
		
		private void setGaleriaVideos(List<String> galeriaVideos) {
			this.galeriaVideos = galeriaVideos;
		}
		
	}
	
	public class Distancia {
		private String unidades;
		private double recorrido;
		
		public Distancia(String unidades, double recorrido) {
			setUnidades(unidades);
			setRecorrido(recorrido);
		}

		public String getUnidades() {
			return unidades;
		}

		private void setUnidades(String unidades) {
			this.unidades = unidades;
		}

		public double getRecorrido() {
			return recorrido;
		}

		private void setRecorrido(double recorrido) {
			this.recorrido = recorrido;
		}	
	}
	
}