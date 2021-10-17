package main;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.GridLayout;
import java.awt.Point;
import java.awt.Toolkit;
import java.util.List;

import javax.swing.BorderFactory;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JTabbedPane;
import javax.swing.SwingConstants;
import javax.swing.border.LineBorder;
import javax.swing.border.TitledBorder;

public class VentanaTabla extends JFrame {

	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	
	private JPanel contentPane;
	private List<Object[][]> datos;
	private List<String> sheetNames;
	private JTabbedPane tabbedPane;
	

	/**
	 * Create the frame.
	 */
	public VentanaTabla(List<Object[][]> datos, List<String> sheetNames) {
		this.datos = datos;
		this.sheetNames = sheetNames;
		setBounds(100, 100, 450, 300);
		setIconImage(Toolkit.getDefaultToolkit().getImage(VentanaTabla.class.getResource("/img/xlsx_icon.png")));
		setTitle("XLSXParser by Samuel Rodr\u00EDguez Ares (UO271612)");
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		Dimension screenSize = Toolkit.getDefaultToolkit().getScreenSize();
		Point middle = new Point(screenSize.width / 2, screenSize.height / 2);
		Point newLocation = new Point(middle.x - (getWidth() / 2), 
		                              middle.y - (getHeight() / 2));
		setLocation(newLocation);

		contentPane = new JPanel();
		contentPane.setBackground(Color.WHITE);
		setContentPane(contentPane);
		contentPane.setLayout(new BorderLayout());
		contentPane.add(getTabbedPane(), BorderLayout.CENTER);
		
		rellenarPantalla();
	}
	
	private void rellenarPantalla() {
		for (int s = 0; s < sheetNames.size(); s++) {
			tabbedPane.addTab(sheetNames.get(s), rellenarHoja(s));
		}
	}
	
	private JPanel rellenarHoja(int s) {
		JPanel panel = new JPanel();
		panel.setBackground(Color.WHITE);
		panel.setBorder(new TitledBorder(new LineBorder(new Color(
				0, 0, 0), 3, true), "Contenido del fichero", 
				TitledBorder.LEADING, TitledBorder.TOP, null, null));
		
		if (datos.get(s).length != 0) {
			panel.setLayout(new GridLayout(datos.get(s).length, datos.get(s)[0].length, 0, 0));

			for (int i = 0; i < datos.get(s).length; i++) {
				for (int j = 0; j < datos.get(s)[i].length; j++) {
					JLabel etiqueta = new JLabel(datos.get(s)[i][j].toString());
					etiqueta.setBorder(BorderFactory.createLineBorder(Color.BLACK));
					etiqueta.setFont(new Font("Verdana", Font.PLAIN, 12));
					etiqueta.setHorizontalAlignment(SwingConstants.CENTER);
					panel.add(etiqueta);
				}
			}

		}
		
		return panel;
	}
	
	private JTabbedPane getTabbedPane() {
		if (tabbedPane == null) {
			tabbedPane = new JTabbedPane(JTabbedPane.TOP);
		}
		
		return tabbedPane;
	}
	
}