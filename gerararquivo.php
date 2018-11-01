<?php
	require 'vendor/autoload.php';
	require 'filtro.class.php';
	
	//Devido ao tamanho do arquivo, foi preciso filtrar apenas as informações importantes
	//Obtendo valores da coluna A
	$filterSubsetA = new MyReadFilter(8,31419, range('A', 'A'));
	$readerA = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
	$readerA->setLoadSheetsOnly('Planilha1');
	$readerA->setReadFilter($filterSubsetA);
	$listA = $readerA->load('testeLog.xls');

	//Obtendo valores da coluna C
	$filterSubsetC = new MyReadFilter(8,31419, range('C', 'C'));
	$readerC = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
	$readerC->setLoadSheetsOnly('Planilha1');
	$readerC->setReadFilter($filterSubsetC);
	$listC = $readerC->load('testeLog.xls');

	//Novo arquivo
	$tempname = tempnam(sys_get_temp_dir(), '18');
	$newfile = fopen($tempname, 'w+');

	for ($i = 8; $i <= 31419; $i ++) {
		$date = $listA->getActiveSheet()->getCellByColumnAndRow(1, $i)->getValue();
		$flow = $listC->getActiveSheet()->getCellByColumnAndRow(3, $i)->getValue();

		fwrite($newfile, "$date;$flow\r\n");
	}
	
	fclose($newfile);

	// Cabeçalho do arquivo para ele baixar
	header("Content-Disposition: attachment; filename='18.txt'");
	header("Content-Type: application/force-download");
	header("Content-Length: " . filesize($tempname));
	header("Connection: close");

	ob_clean();
	flush();
	readfile($tempname);

	unlink($tempname);

?>