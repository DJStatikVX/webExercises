package main;

import java.io.File;
import java.io.FileOutputStream;
import java.io.OutputStreamWriter;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

public class RutasParserHTML {

	public static void main(String[] args) {
		try {
			File inputFile = new File("rutas.xml");
			DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
			Document doc = dBuilder.parse(inputFile);
			doc.getDocumentElement().normalize();
			System.out.println("Root element :" + doc.getDocumentElement().getNodeName());
			NodeList rutas = doc.getElementsByTagName("ruta");
			System.out.println("----------------------------");
			
			StringBuilder html = new StringBuilder("<!DOCTYPE html>\r\n"
					+ "<html lang=\"es\">\r\n"
					+ "    <head>\r\n"
					+ "        <meta charset=\"UTF-8\">\r\n"
					+ "        <meta name=\"author\" content=\"Samuel Rodríguez Ares (UO271612)\">\r\n"
					+ "        <link rel=\"stylesheet\" type=\"text/css\" href=\"./estilo.css\">\r\n"
					+ "        <title>Rutas en Moto</title>\r\n"
					+ "    </head>\r\n"
					+ "    <body>\r\n"
					+ "        <section class=\"encabezado\">\r\n"
					+ "            <h1>Rutas en Moto</h1>\r\n"
					+ "            <p>por Samuel Rodríguez Ares (UO271612)</p>\r\n"
					+ "        </section>\r\n");

			for (int i = 0; i < rutas.getLength(); i++) {
				Node ruta = rutas.item(i);
				
				// Nombre (atributo)
				String nombreRuta = ruta.getAttributes().item(0).getNodeValue();
				System.out.println("\nCurrent Element: " + nombreRuta);
				html.append("        <section class=\"ruta\">\r\n            <h2>Ruta " + (i + 1) + ": " + nombreRuta +  "</h2>\r\n");
				
				// Elemento ruta
				Element elemRuta;
				if (ruta.getNodeType() == Node.ELEMENT_NODE) {
					elemRuta = (Element) ruta;
					
					// Tipo
					String tipoRuta = elemRuta.getElementsByTagName("tipo").
							item(0).getTextContent();
					
					System.out.println("Tipo: " + tipoRuta);
					html.append("            <p>Tipo: " + tipoRuta + "</p>\r\n");
					
					// Dificultad
					String dificultadRuta = elemRuta.getElementsByTagName("dificultad").
							item(0).getTextContent();
					
					System.out.println("Dificultad: " + dificultadRuta);
					html.append("            <p>Dificultad: " + dificultadRuta + "</p>\r\n");
					
					// Fecha de inicio
					Node nFechaInicioRuta = elemRuta.getElementsByTagName("fechainicio").item(0);
					if (nFechaInicioRuta != null) {
						int fechaInicioRuta_dia = Integer.parseInt(
								nFechaInicioRuta.getAttributes().item(1).getTextContent());
						int fechaInicioRuta_mes = Integer.parseInt(
								nFechaInicioRuta.getAttributes().item(2).getTextContent());
						int fechaInicioRuta_año = Integer.parseInt(
								nFechaInicioRuta.getAttributes().item(0).getTextContent());
						
						String strFechaInicioRuta = String.format("%d/%d/%d", 
								fechaInicioRuta_dia, fechaInicioRuta_mes, fechaInicioRuta_año);
						Date fechaInicioRuta = new SimpleDateFormat("dd/MM/yyyy")
								.parse(strFechaInicioRuta);
						
						System.out.println(new SimpleDateFormat("dd/MM/yyyy")
								.format(fechaInicioRuta));
						
						html.append("            <p>" + new SimpleDateFormat("dd/MM/yyyy")
								.format(fechaInicioRuta));
					}
					
					// Hora de inicio
					Node nHoraInicio = elemRuta.getElementsByTagName("horainicio").item(0);
					if (nHoraInicio != null) {
						String horaInicioRuta = nHoraInicio.getTextContent();
						System.out.println(horaInicioRuta);
						html.append(", " + horaInicioRuta + "</p>\r\n");
					} else {
						html.append("</p>\r\n");
					}
					
					// Duración
					Node nDuracion = elemRuta.getElementsByTagName("duracion").item(0);
					String duracionRuta = nDuracion.getAttributes().item(0).getTextContent();
					System.out.println("Duración: " + duracionRuta);
					html.append("            <p>Duración: " + duracionRuta + "</p>\r\n");
					
					// Agencia
					Node nAgencia = elemRuta.getElementsByTagName("agencia").item(0);
					String agenciaRuta = nAgencia.getAttributes().item(0).getTextContent();
					System.out.println("Agencia: " + agenciaRuta);
					html.append("            <p>Agencia: " + agenciaRuta + "</p>\r\n");
					
					// Descripción
					String descripcionRuta = elemRuta.getElementsByTagName("descripcion")
							.item(0).getTextContent();
					System.out.println("Descripción: " + descripcionRuta);
					html.append("            <p>Descripción: " + descripcionRuta + "</p>\r\n");
					
					// Personas
					String personasRuta = elemRuta.getElementsByTagName("personas")
							.item(0).getTextContent();
					System.out.println("Recomendado para: " + personasRuta);
					html.append("            <p>Recomendado para: " + personasRuta + "</p>\r\n");
					
					// Inicio
					Node nInicio = elemRuta.getElementsByTagName("inicio").item(0);
					String ciudadInicioRuta = nInicio.getAttributes().item(0).getTextContent();
					String direccionInicioRuta = nInicio.getAttributes().item(1).getTextContent();
					System.out.println(direccionInicioRuta + ", " + ciudadInicioRuta);
					html.append("            <p>Comienza en: " + direccionInicioRuta 
							+ ", " + ciudadInicioRuta);
					
					// Elementos de inicio
					Element elemInicio;
					String latitudInicioRuta;
					String longitudInicioRuta;
					String altitudInicioRuta;
					if (nInicio.getNodeType() == Node.ELEMENT_NODE) {
						elemInicio = (Element) nInicio;
						Node nCoordenadasInicio = elemInicio.getElementsByTagName(
								"coordenadas").item(0);
						
						// Elementos de las coordenadas de inicio
						Element elemCoordenadasInicio;
						if (nCoordenadasInicio.getNodeType() == Node.ELEMENT_NODE) {
							elemCoordenadasInicio = (Element) nCoordenadasInicio;
							
							// Latitud de inicio
							latitudInicioRuta = elemCoordenadasInicio
									.getElementsByTagName("latitud").item(0).getTextContent();
							longitudInicioRuta = elemCoordenadasInicio
									.getElementsByTagName("longitud").item(0).getTextContent();
							altitudInicioRuta = elemCoordenadasInicio
									.getElementsByTagName("altitud").item(0).getTextContent();
							
							System.out.println(String.format("Coordenadas de inicio: %s, %s, %s",
									latitudInicioRuta, longitudInicioRuta, altitudInicioRuta));
							
							html.append(String.format(" (%s, %s, %s)</p>\r\n", 
									latitudInicioRuta, longitudInicioRuta, altitudInicioRuta));
						}
					}
					
					// Referencias
					Node nReferenciasRuta = elemRuta.getElementsByTagName("referencias").item(0);
					List<String> referenciasRuta = new ArrayList<>();
					System.out.println("Referencias de la ruta:");
					html.append("            <p>Referencias de la ruta</p>\r\n            <ul>\r\n");
					
					Element elemReferenciasRuta;
					if (nReferenciasRuta.getNodeType() == Element.ELEMENT_NODE) {
						elemReferenciasRuta = (Element) nReferenciasRuta;
						NodeList nListReferenciasRuta = elemReferenciasRuta
								.getElementsByTagName("sitioweb");
						
						for (int ref = 0; ref < nListReferenciasRuta.getLength(); ref++) {
							Node referencia = nListReferenciasRuta.item(ref);
							String url = referencia.getAttributes().item(0).getTextContent();
							referenciasRuta.add(url);
							System.out.println("- " + url);
							html.append("                <li><a href=\"" + url + "\">" + "Referencia " + (ref + 1) + "</a></li>\r\n");
						}
						
						html.append("            </ul>\r\n");
					}

					// Valoración
					double valoracionRuta = Integer.parseInt(elemRuta
							.getElementsByTagName("valoracion").item(0).getTextContent());
					System.out.println("Valoración: " + valoracionRuta);
					html.append("            <p>Valoración: " + valoracionRuta + "</p>\r\n"); 
					
					// Hitos
					Node nHitosRuta = elemRuta.getElementsByTagName("hitos").item(0);
					Element elemHitosRuta;
					if (nHitosRuta.getNodeType() == Element.ELEMENT_NODE) {
						elemHitosRuta = (Element) nHitosRuta;
						NodeList nListHitosRuta = elemHitosRuta.getElementsByTagName("hito");
						System.out.println("Hitos registrados:");
						html.append("            <h3>Hitos Registrados:</h3>\r\n");
						for (int h = 0; h < nListHitosRuta.getLength(); h++) {
							Node hito = nListHitosRuta.item(h);
							
							// Nombre del hito
							String nombreHito = hito.getAttributes().item(0).getTextContent();
							System.out.println("\n- Hito " + (h + 1) + ": " + nombreHito);
							html.append("            <h4>Hito " + (h + 1) + ": " + nombreHito + "</h4>\r\n");
							
							// Elementos del hito
							Element elemHito;
							if (hito.getNodeType() == Element.ELEMENT_NODE) {
								elemHito = (Element) hito;
								
								// Descripción
								String descripcionHito = elemHito
										.getElementsByTagName("descripcion")
										.item(0).getTextContent();
								
								System.out.println(descripcionHito);
								html.append("            <p>" + descripcionHito + "</p>\r\n");
								
								String latitudHito;
								String longitudHito;
								String altitudHito;

								Node nCoordenadasHito = elemHito.getElementsByTagName("coordenadas").item(0);

								// Elementos de las coordenadas del hito
								Element elemCoordenadasHito;
								if (nCoordenadasHito.getNodeType() == Node.ELEMENT_NODE) {
									elemCoordenadasHito = (Element) nCoordenadasHito;

									// Latitud de inicio
									latitudHito = elemCoordenadasHito
											.getElementsByTagName("latitud").item(0)
											.getTextContent();
									longitudHito = elemCoordenadasHito
											.getElementsByTagName("longitud").item(0)
											.getTextContent();
									altitudHito = elemCoordenadasHito
											.getElementsByTagName("altitud").item(0)
											.getTextContent();
									
									System.out.println(String.format(
											"Coordenadas: %s, %s, %s", 
											latitudHito, longitudHito, altitudHito));
									
									html.append(String.format("            <p>Coordenadas: (%s, %s, %s)</p>\r\n", 
											latitudHito, longitudHito, altitudHito));
								}
								
								// Distancia desde el anterior hito
								Node nDistanciaHito = elemHito.getElementsByTagName("distancia").item(0);
								String distanciaHito = nDistanciaHito.getTextContent();
								String udsDistanciaHito = nDistanciaHito.getAttributes()
										.item(0).getTextContent();
								
								System.out.println("Distancia desde el anterior hito: " 
										+ distanciaHito + " " + udsDistanciaHito);
								html.append(String.format("            <p>Distancia desde el anterior hito: %s %s</p>\r\n", 
										distanciaHito, udsDistanciaHito));
								
								// Tiempo desde el anterior hito
								Node nTiempoHito = elemHito.getElementsByTagName("tiempo").item(0);
								if (nTiempoHito != null) {
									String tiempoHito = elemHito.getElementsByTagName(
											"tiempo").item(0)
											.getTextContent();

									System.out.println("Tiempo desde el anterior hito: " 
									+ tiempoHito);
									html.append("            <p>Tiempo desde el anterior hito: " + tiempoHito + "</p>\r\n");
								}
								
								// Galerías
								Node nGalerias = elemHito.getElementsByTagName(
										"galerias").item(0);
								
								Element elemGalerias;
								if (nGalerias.getNodeType() == Element.ELEMENT_NODE) {
									elemGalerias = (Element) nGalerias;
									// Fotos
									Node nGaleriaFotos = elemGalerias
											.getElementsByTagName("galeriafotos")
											.item(0);
									System.out.println("Fotos:");
									html.append("            <h5>Fotos</h5>\r\n            <ul>\r\n");
									Element elemGaleriaFotos;
									if (nGaleriaFotos.getNodeType() == Element.ELEMENT_NODE) {
										elemGaleriaFotos = (Element) nGaleriaFotos;
										NodeList nListFotos = elemGaleriaFotos
												.getElementsByTagName("foto");
										for (int f = 0; f < nListFotos.getLength(); f++) {
											Node nFoto = nListFotos.item(f);
											String urlFoto = nFoto.getAttributes()
													.item(0).getTextContent();
											System.out.println("- " + urlFoto);
											html.append("                <li><a href=\"" + urlFoto + "\">" + "Foto " + (f + 1) + "</a></li>\r\n");
										}
										
										html.append("            </ul>\r\n");
									}
									
									// Videos
									Node nGaleriaVideos = elemGalerias
											.getElementsByTagName(
													"galeriavideos").item(0);
									
									if (nGaleriaVideos != null) {
										System.out.println("Vídeos:");
										html.append("            <h5>Vídeos</h5>\r\n            <ul>\r\n");
										Element elemGaleriaVideos;
										if (nGaleriaVideos.getNodeType() 
												== Element.ELEMENT_NODE) {
											elemGaleriaVideos = (Element) nGaleriaVideos;
											NodeList nListVideos = elemGaleriaVideos.getElementsByTagName("video");
											for (int v = 0; v < nListVideos.getLength(); v++) {
												Node nVideo = nListVideos.item(v);
												String urlVideo = nVideo.getAttributes().item(0).getTextContent();
												System.out.println("- " + urlVideo);
												html.append("                <li><a href=\"" + urlVideo + "\">" + "Vídeo " + (v + 1) + "</a></li>\r\n");
											}
											
											html.append("            </ul>\r\n");
										}
									}
								}
							}
						}
					}
				}
				
				html.append("        </section>\r\n");
			}
			
			html.append("    </body>\r\n"
					+ "</html>");
			
			// Escritura en fichero con codificación UTF-8
			OutputStreamWriter osw = null;
			try {
				osw = new OutputStreamWriter(new FileOutputStream("index.html"), "UTF-8");
				osw.write(html.toString());
			} finally {
				osw.close();
			}
			
		} catch (Exception e) {
			e.printStackTrace();
		}

	}

}