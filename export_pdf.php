<?php
require_once('vendor/autoload.php');
use FPDF;
include 'db.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Liste des interventions',0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,10,'Client',1);
$pdf->Cell(40,10,'Technicien',1);
$pdf->Cell(30,10,'Date',1);
$pdf->Cell(40,10,'Type',1);
$pdf->Cell(30,10,'Statut',1);
$pdf->Ln();

$stmt = $pdo->query("SELECT interventions.date AS date, interventions.type_intervention, interventions.statut,
                            clients.nom_entreprise,
                            techniciens.nom AS nom_tech, techniciens.prenom AS prenom_tech
                     FROM interventions
                     JOIN clients ON interventions.client_id = clients.id
                     JOIN techniciens ON interventions.technicien_id = techniciens.id");

while ($row = $stmt->fetch()) {
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(40,10,$row['nom_entreprise'],1);
    $pdf->Cell(40,10,$row['nom_tech'] . ' ' . $row['prenom_tech'],1);
    $pdf->Cell(30,10,$row['date'],1);
    $pdf->Cell(40,10,$row['type_intervention'],1);
    $pdf->Cell(30,10,$row['statut'],1);
    $pdf->Ln();
}

$pdf->Output();
exit;
?>

