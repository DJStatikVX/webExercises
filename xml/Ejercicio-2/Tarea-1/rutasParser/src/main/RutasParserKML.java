package main;

import java.io.File;
import java.io.FileOutputStream;
import java.io.OutputStreamWriter;
import java.util.HashMap;
import java.util.Map;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

public class RutasParserKML {
	
	private static Map<String, String> idColores = new HashMap<String, String>() {
		private static final long serialVersionUID = 1L;

		{
			put("#FF0000FF", "lineaRoja");
			put("#FFF05A14", "lineaAzul");
			put("#FF00A014", "lineaVerde");
			put("#FF14F0FF", "lineaAmarilla");
			put("#FF0078F0", "lineaNaranja");
			put("#FF780078", "lineaMorada");
		}
	};
	
	private static Map<String, Boolean> idUsados = new HashMap<String, Boolean>() {
		private static final long serialVersionUID = 1L;

		{
			put("#FF0000FF", false);
			put("#FFF05A14", false);
			put("#FF00A014", false);
			put("#FF14F0FF", false);
			put("#FF0078F0", false);
			put("#FF780078", false);
		}
	};
		
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
			
			StringBuilder kml = new StringBuilder("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n"
					+ "<kml xmlns=\"http://www.opengis.net/kml/2.2\">\r\n<Document>\r\n");
			
			for (int i = 0; i < rutas.getLength(); i++) {
				Node ruta = rutas.item(i);
				
				// Nombre
				String nombreRuta = ruta.getAttributes().item(0).getNodeValue();
				System.out.println("\nCurrent Element: " + nombreRuta);
				kml.append("  <Placemark>\r\n    <name>" + nombreRuta 
						+ "</name>\r\n");
				
				Element elemRuta;
				if (ruta.getNodeType() == Element.ELEMENT_NODE) {
					elemRuta = (Element) ruta;
					String descripcionRuta = elemRuta.getElementsByTagName(
							"descripcion").item(0).getTextContent();
					kml.append("    <description>" + descripcionRuta 
							+ "</description>\r\n    <LineString>\r\n      "
							+ "<extrude>1</extrude>\r\n      <tessellate>1</tessellate>\r\n      "
							+ "<altitudeMode>absolute</altitudeMode>\r\n");
					
					// Coordenadas de inicio
					Node nInicio = elemRuta.getElementsByTagName("inicio").item(0);
					Element elemInicio;
					double latitudInicioRuta;
					double longitudInicioRuta;
					double altitudInicioRuta;
					if (nInicio.getNodeType() == Node.ELEMENT_NODE) {
						elemInicio = (Element) nInicio;
						Node nCoordenadasInicio = elemInicio.getElementsByTagName(
								"coordenadas").item(0);
						
						// Elementos de las coordenadas de inicio
						Element elemCoordenadasInicio;
						if (nCoordenadasInicio.getNodeType() == Node.ELEMENT_NODE) {
							elemCoordenadasInicio = (Element) nCoordenadasInicio;
							
							// Latitud de inicio
							latitudInicioRuta = Double.parseDouble(elemCoordenadasInicio
									.getElementsByTagName("latitud").item(0).getTextContent());
							longitudInicioRuta = Double.parseDouble(elemCoordenadasInicio
									.getElementsByTagName("longitud").item(0).getTextContent());
							altitudInicioRuta = Double.parseDouble(elemCoordenadasInicio
									.getElementsByTagName("altitud").item(0).getTextContent());
							
							kml.append("      <coordinates>\r\n");
							
							String coordenadasInicio = String.format("%f,%f,%.1f", 
									longitudInicioRuta, latitudInicioRuta, 
									altitudInicioRuta);
							
							kml.append("        " + coordenadasInicio + "\r\n");
						}
					}
					
					// Hitos
					Node nHitosRuta = elemRuta.getElementsByTagName("hitos").item(0);
					Element elemHitosRuta;
					if (nHitosRuta.getNodeType() == Element.ELEMENT_NODE) {
						elemHitosRuta = (Element) nHitosRuta;
						NodeList nListHitosRuta = elemHitosRuta.getElementsByTagName("hito");

						for (int h = 0; h < nListHitosRuta.getLength(); h++) {
							Node hito = nListHitosRuta.item(h);

							Element elemHito;
							if (hito.getNodeType() == Element.ELEMENT_NODE) {
								elemHito = (Element) hito;
								Node nCoordenadasHito = elemHito
										.getElementsByTagName("coordenadas").item(0);

								// Elementos de las coordenadas del hito
								Element elemCoordenadasHito;
								if (nCoordenadasHito.getNodeType() == Node.ELEMENT_NODE) {
									elemCoordenadasHito = (Element) nCoordenadasHito;

									// Latitud de inicio
									double latitudHito = Double.parseDouble(
											elemCoordenadasHito
											.getElementsByTagName("latitud")
											.item(0).getTextContent());
									double longitudHito = Double.parseDouble(
											elemCoordenadasHito
											.getElementsByTagName("longitud")
											.item(0).getTextContent());
									double altitudHito = Double.parseDouble(
											elemCoordenadasHito
											.getElementsByTagName("altitud")
											.item(0).getTextContent());
									
									String coordenadasHito = String.format("%f,%f,%.1f", 
											longitudHito, latitudHito, 
											altitudHito);
									
									kml.append("        " + coordenadasHito + "\r\n");
								}
							}
						}
						
						kml.append("      </coordinates>\r\n    </LineString>\r\n");
												
						// Selección de color para la ruta
						String color = buscarColorRuta();
													
						// Si no hay ningún color disponible, se vuelve a empezar
						if (color == null) {
							for (String id : idUsados.keySet())
								idUsados.put(id, true);
							color = buscarColorRuta();
						}
						
						kml.append("    <Style id=\"" + idColores.get(color) 
						+ "\">\r\n      <LineStyle>\r\n        <color>" + color 
						+ "</color>\r\n        <width>5</width>\r\n      "
						+ "</LineStyle>\r\n    </Style>\r\n  </Placemark>\r\n");
					}
				}
			}
			
			kml.append("</Document>\r\n</kml>");
			
			// Escritura en fichero con codificación UTF-8
			OutputStreamWriter osw = null;
			try {
				osw = new OutputStreamWriter(new FileOutputStream("rutas.kml"), "UTF-8");
				osw.write(kml.toString());
			} finally {
				osw.close();
			}
			
		} catch (Exception e) {
			e.printStackTrace();
		}

	}
	
	private static String buscarColorRuta() {
		for (String id : idUsados.keySet()) {
			if (idUsados.get(id).equals(false)) {
				idUsados.put(id, true);
				return id;
			}
		}
		
		return null;
	}

}