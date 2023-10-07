<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the content from the POST request
    $content = $_POST["content"];

    // Include the PDF generation library (e.g., mPDF or TCPDF)
    require 'vendor/autoload.php'; // Include Composer autoload (if you're using libraries like mPDF or TCPDF)

    // Create a PDF instance
    $mpdf = new \Mpdf\Mpdf();

    // Write the HTML content to the PDF
    $mpdf->WriteHTML($content);

    // Output the PDF for download
    $mpdf->Output('output.pdf', 'D');
}
?>
