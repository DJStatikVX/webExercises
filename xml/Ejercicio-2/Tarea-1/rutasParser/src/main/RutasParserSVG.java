package main;

import java.io.File;
import java.io.FileOutputStream;
import java.io.OutputStreamWriter;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

public class RutasParserSVG {

	public static void main(String[] args) {
		try {
			File inputFile = new File("rutas.xml");
			DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
			Document doc = dBuilder.parse(inputFile);
			doc.getDocumentElement().normalize();
			
			NodeList rutas = doc.getElementsByTagName("ruta");
			
			StringBuilder svg;
			Map<String, Integer> altitudes;
			List<String> orden;
			
			// Recorremos las rutas (un SVG por cada ruta)
			for (int i = 0; i < rutas.getLength(); i++) {
				Node ruta = rutas.item(i);
				altitudes = new HashMap<>();
				orden = new ArrayList<String>();
				svg = new StringBuilder();
				String nombreRuta = "undefined";
				
				Element elemRuta;
				if (ruta.getNodeType() == Element.ELEMENT_NODE) {
					elemRuta = (Element) ruta;
					nombreRuta = elemRuta.getAttribute("nombre");
					
					// Obtenemos nombre y altitud de inicio
					Node nInicio = elemRuta.getElementsByTagName("inicio").item(0);
					Element elemInicio;
					if (nInicio.getNodeType() == Node.ELEMENT_NODE) {
						elemInicio = (Element) nInicio;
						String nombreInicio = elemInicio.getAttribute("ciudad");
						orden.add(nombreInicio);
						Node nCoordenadasInicio = elemInicio.getElementsByTagName(
								"coordenadas").item(0);
						
						Element elemCoordenadasInicio;
						if (nCoordenadasInicio.getNodeType() == Node.ELEMENT_NODE) {
							elemCoordenadasInicio = (Element) nCoordenadasInicio;
							
							// Anota altitud en diccionario
							altitudes.put(nombreInicio, Integer.parseInt(
									elemCoordenadasInicio
									.getElementsByTagName("altitud").item(0)
									.getTextContent()));		
						}
					}
					
					// Recorremos los hitos para obtener sus altitudes
					Node nHitosRuta = elemRuta.getElementsByTagName("hitos").item(0);
					Element elemHitos;
					
					if (nHitosRuta.getNodeType() == Element.ELEMENT_NODE) {
						elemHitos = (Element) nHitosRuta;
						NodeList nListHitos = elemHitos.getElementsByTagName("hito");
						
						for (int h = 0; h < nListHitos.getLength(); h++) {
							Node nHito = nListHitos.item(h);
							Element elemHito;
							if (nHito.getNodeType() == Element.ELEMENT_NODE) {
								elemHito = (Element) nHito;
								String nombreHito = elemHito.getAttribute("nombre");
								orden.add(nombreHito);
								Node nCoordenadasHito = elemHito
										.getElementsByTagName("coordenadas").item(0);

								// Elementos de las coordenadas del hito
								Element elemCoordenadasHito;
								if (nCoordenadasHito.getNodeType() == Node.ELEMENT_NODE) {
									elemCoordenadasHito = (Element) nCoordenadasHito;

									// Anota altitud en diccionario
									altitudes.put(nombreHito, Integer.parseInt(
											elemCoordenadasHito
											.getElementsByTagName("altitud")
											.item(0).getTextContent()));
								}
							}
						}
						
						// Cálculo de altitud máxima
						Map.Entry<String, Integer> max = null;
						
						for (Map.Entry<String, Integer> ent : altitudes.entrySet()) {
							if (max == null || ent.getValue().compareTo(max.getValue()) > 0)
								max = ent;
						}
						
						// Comienzo de la cadena SVG
						svg.append("<?xml version=\"1.0\" encoding=\"utf-8\"?><svg xmlns=\"http://www.w3.org/2000/svg\" "
								+ "viewBox=\"-15 70 500 100\">\r\n");
						
						// Añadimos etiquetas de hitos
						int posY = 205;
						for (int t = 0; t < orden.size(); t++) {
							svg.append("\t<text x=\"105\" y=\"" + posY 
									+ "\" transform=\"rotate(90,100,100)\">" 
									+ orden.get(t) + "</text>\r\n");
							posY -= 30;
						}
						
						// Se añade la polilínea
						svg.append("\t<polyline\r\n\t\tfill=\"none\"\r\n\t\t"
								+ "stroke=\"red\"\r\n\t\tstroke-width=\"3\"\r\n"
								+ "\t\tpoints=\"0,101.5\r\n");
						
						// Se añaden todos los puntos de los hitos
						int posX = 0;
						for (int h = 0; h < orden.size(); h++) {
							double y = 100 - (((altitudes.get(orden.get(h)) * 1.0) 
									/ max.getValue()) * 100);
							
							svg.append("\t\t\t\t" + posX + "," + y + "\r\n");
							
							if (h < orden.size() - 1)
								posX += 30;
						}
						
						// Añado puntos finales para la línea inferior y cierro SVG
						svg.append("\t\t\t\t" + posX + ",100\r\n\t\t\t\t0,100\"/>\r\n</svg>");
					}
				}
				
				// Escritura en fichero SVG con codificación UTF-8
				OutputStreamWriter osw = null;
				try {
					osw = new OutputStreamWriter(new FileOutputStream(
							nombreRuta.strip() + ".svg"), "UTF-8");
					
					osw.write(svg.toString());
				} finally {
					osw.close();
				}

			}
			
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
}