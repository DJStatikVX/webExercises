package main;

import java.io.File;
import java.io.FileInputStream;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;

import org.apache.poi.hssf.usermodel.HSSFDateUtil;
import org.apache.poi.ss.usermodel.Cell;
import org.apache.poi.ss.usermodel.CellValue;
import org.apache.poi.ss.usermodel.FormulaEvaluator;
import org.apache.poi.ss.usermodel.Row;
import org.apache.poi.xssf.usermodel.XSSFSheet;
import org.apache.poi.xssf.usermodel.XSSFWorkbook;

public class XLSXParser {

	@SuppressWarnings("deprecation")
	protected static void parse(File xlsx) throws Exception {
		FileInputStream fis = new FileInputStream(xlsx);
		XSSFWorkbook wb = new XSSFWorkbook(fis);
		List<Object[][]> datos = new ArrayList<Object[][]>();
		List<String> sheetNames = new ArrayList<String>();

		FormulaEvaluator formulaEvaluator = wb.getCreationHelper().createFormulaEvaluator();

		for (int i = 0; i < wb.getNumberOfSheets(); i++) {
			XSSFSheet sheet = wb.getSheetAt(i);
			sheetNames.add(wb.getSheetName(i));

			int nFilas = sheet.getPhysicalNumberOfRows();
			int maxNColumnas = 0;

			for (int r = 0; r < sheet.getPhysicalNumberOfRows(); r++) {
				if (sheet.getRow(r).getPhysicalNumberOfCells() > maxNColumnas)
					maxNColumnas = sheet.getRow(r).getPhysicalNumberOfCells();
			}

			datos.add(new Object[nFilas][maxNColumnas]);

			for (Row row : sheet) {
				for (int j = 0; j < row.getLastCellNum(); j++) {
					Cell cell = row.getCell(j, Row.MissingCellPolicy.RETURN_BLANK_AS_NULL);
					if (cell == null)
						datos.get(i)[row.getRowNum()][j] = "";
					else if (formulaEvaluator.evaluateInCell(cell).getCellType() == Cell.CELL_TYPE_NUMERIC
							&& HSSFDateUtil.isCellDateFormatted(cell))
						datos.get(i)[row.getRowNum()][j] = new SimpleDateFormat("dd/MM/yyyy").format(cell.getDateCellValue());
					else {
						switch (formulaEvaluator.evaluateInCell(cell).getCellType()) {
						case Cell.CELL_TYPE_NUMERIC:
							datos.get(i)[row.getRowNum()][j] = cell.getNumericCellValue();
							break;
						case Cell.CELL_TYPE_STRING:
							datos.get(i)[row.getRowNum()][j] = cell.getStringCellValue();
							break;
						case Cell.CELL_TYPE_BLANK:
							datos.get(i)[row.getRowNum()][j] = "";
							break;
						case Cell.CELL_TYPE_BOOLEAN:
							datos.get(i)[row.getRowNum()][j] = cell.getBooleanCellValue();
							break;
						case Cell.CELL_TYPE_ERROR:
							datos.get(i)[row.getRowNum()][j] = cell.getErrorCellValue();
							break;
						case Cell.CELL_TYPE_FORMULA:
							CellValue valor = formulaEvaluator.evaluate(cell);
							datos.get(i)[row.getRowNum()][j] = valor.getStringValue();
							break;
						}
					}
				}

			}
		}

		wb.close();
		fis.close();

		mostrarTabla(datos, sheetNames);
	}

	private static void mostrarTabla(List<Object[][]> datos, List<String> sheetNames) {
		VentanaTabla vt = new VentanaTabla(datos, sheetNames);
		vt.setVisible(true);
	}

}
