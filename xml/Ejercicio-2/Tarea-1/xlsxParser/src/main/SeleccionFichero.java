package main;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Font;
import java.awt.Image;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.io.IOException;

import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JDialog;
import javax.swing.JFileChooser;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.SwingConstants;
import javax.swing.border.EmptyBorder;
import javax.swing.filechooser.FileNameExtensionFilter;

public class SeleccionFichero extends JDialog {

	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	
	private final JPanel contentPanel = new JPanel();
	private JFileChooser selector;
	private JButton buttonAbrir;
	private JPanel panelBotones;
	private JButton btnSalir;

	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		try {
	        for (javax.swing.UIManager.LookAndFeelInfo info : javax.swing.UIManager.getInstalledLookAndFeels()) {
	            if ("Nimbus".equals(info.getName())) {
	                javax.swing.UIManager.setLookAndFeel(info.getClassName());
	                break;
	            }
	        }
			SeleccionFichero dialog = new SeleccionFichero();
			dialog.setDefaultCloseOperation(JDialog.DISPOSE_ON_CLOSE);
			dialog.setVisible(true);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	/**
	 * Create the dialog.
	 */
	public SeleccionFichero() {
		setIconImage(Toolkit.getDefaultToolkit().getImage(SeleccionFichero.class.getResource("/img/xlsx_icon.png")));
		setBounds(100, 100, 350, 200);
		setTitle("XLSX Parser by Samuel Rodríguez Ares (UO271612)");
		setLocationRelativeTo(null);
		getContentPane().setLayout(new BorderLayout());
		contentPanel.setBorder(new EmptyBorder(5, 5, 5, 5));
		getContentPane().add(contentPanel, BorderLayout.CENTER);
		contentPanel.setLayout(new BorderLayout(50, 20));
		{
			JLabel lblSeleccionaFichero = new JLabel("Seleccione un fichero de extensi\u00F3n .xlsx:");
			lblSeleccionaFichero.setHorizontalAlignment(SwingConstants.CENTER);
			lblSeleccionaFichero.setFont(new Font("Verdana", Font.PLAIN, 15));
			contentPanel.add(lblSeleccionaFichero, BorderLayout.NORTH);
		}
		contentPanel.add(getButtonAbrir(), BorderLayout.CENTER);
		contentPanel.add(getPanelBotones(), BorderLayout.SOUTH);
	}
	
	private JFileChooser getSelector() {
		if (selector == null) {
			selector = new JFileChooser();
			selector.setMultiSelectionEnabled(false);
			selector.setVisible(true);
			selector.setFileFilter(new FileNameExtensionFilter("Archivos xlsx", "xlsx"));
			selector.setCurrentDirectory(new File(System.getProperty("user.home") + "\\Documents"));
		}
		
		return selector;
	}

	private JButton getButtonAbrir() {
		if (buttonAbrir == null) {
			buttonAbrir = new JButton("Abrir...");
			buttonAbrir.setIconTextGap(15);
			buttonAbrir.setBackground(new Color(70, 130, 180));
			buttonAbrir.setIcon(new ImageIcon(new ImageIcon(SeleccionFichero.class.getResource("/img/file_chooser.png")).getImage().getScaledInstance(50, 50, Image.SCALE_SMOOTH)));
			buttonAbrir.setFont(new Font("Arial Black", Font.BOLD, 24));
			buttonAbrir.setFocusPainted(false);
			buttonAbrir.addActionListener(new ActionListener() {
				public void actionPerformed(ActionEvent e) {
					cargarFichero();
				}
			});
		}
		return buttonAbrir;
	}
	private JPanel getPanelBotones() {
		if (panelBotones == null) {
			panelBotones = new JPanel();
			panelBotones.setLayout(new BorderLayout(0, 0));
			panelBotones.add(getBtnSalir(), BorderLayout.EAST);
		}
		return panelBotones;
	}
	private JButton getBtnSalir() {
		if (btnSalir == null) {
			btnSalir = new JButton("Salir");
			btnSalir.setFont(new Font("Verdana", Font.PLAIN, 12));
			btnSalir.setForeground(new Color(255, 255, 255));
			btnSalir.setBackground(new Color(128, 0, 0));
			btnSalir.setFocusPainted(false);
			btnSalir.addActionListener(new ActionListener() {
				public void actionPerformed(ActionEvent e) {
					dispose();
				}
			});
		}
		return btnSalir;
	}
	
	private void cargarFichero() {
		int respuesta = getSelector().showOpenDialog(null);
		
		if (respuesta == JFileChooser.APPROVE_OPTION) {
			File xlsx = getSelector().getSelectedFile();
			try {
				XLSXParser.parse(xlsx);
				dispose();
			} catch (Exception e) {
				JOptionPane.showMessageDialog(null, 
						"Se ha producido un error interpretando el archivo " 
				+ xlsx.getName() + ".", "Error", JOptionPane.ERROR_MESSAGE);
			}
		}
	}
}
