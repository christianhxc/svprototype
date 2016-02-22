<?php
    require_once ('libs/Pdf/html2pdf.class.php');
    $content = $_POST["datos_a_enviar"];

    $html2pdf = new HTML2PDF('L','A4','es');
    $html2pdf->WriteHTML($content);
    $html2pdf->Output('reportePdf.pdf');