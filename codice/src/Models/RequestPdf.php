<?php
namespace FilippoFinke\Models;

use Fpdf\Fpdf;

class RequestPdf extends Fpdf
{
    public function __construct($totalReasons, $reasons, $request, $hours, $user)
    {
        parent::__construct('L', 'mm', 'letter');
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(20, 8, "Cognome e nome:");
        $this->SetFont('Arial', '', 13);
        $this->Cell(30);
        $this->Cell(100, 8, $user["last_name"]." ".$user["name"]);
        $this->Ln(8);
        $this->SetFont('Arial', 'BI', 11);
        $this->Cell(210, 7, 'Congedo pagato (art.27, 46, 47, 48, 49 LORD 1995 + art. 18, 19, 25, 26, 30, 31, 32, 33, 34, 35, 36, 38, 40 R. dip. 2017)', 'B');
        $this->Ln(10);

        $newLine = 0;
        $width = ($this->GetPageWidth() - 20) / 2;
        foreach ($totalReasons as $reason) {
            $y = $this->GetY();
            $x = $this->GetX();
            $this->Multicell($width, 6, $reason["name"]."\n".$reason["description"], 1);
            $this->SetXY($x + $width, $y);
            $newLine++;
            if ($newLine % 2 == 0) {
                $this->SetX(10);
                $this->SetY($y + 6 * 2);
            }
        }
        $this->ln(40);


        foreach ($totalReasons as $reason) {
            foreach ($reason as $key => $value) {
                $this->Cell(40, 10, $key);
                $this->Cell(40, 10, $value);
                $this->Ln(10);
            }
        }

        foreach ($reasons as $reason) {
            foreach ($reason as $key => $value) {
                $this->Cell(40, 10, $key);
                $this->Cell(40, 10, $value);
                $this->Ln(10);
            }
        }

        foreach ($request as $key => $value) {
            $this->Cell(40, 10, $key);
            $this->Cell(40, 10, $value);
            $this->Ln(10);
        }

        foreach ($hours as $hour) {
            foreach ($hour as $key => $value) {
                $this->Cell(40, 10, $key);
                $this->Cell(40, 10, $value);
                $this->Ln(10);
            }
        }

        $this->Output();
    }

    public function Header()
    {
        $this->Image(__DIR__ . '/cpt-logo.jpeg', 10, 10, -300);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(260, 16, 'ASSENZE E CONGEDI', 'B', 0, 'R');
        $this->Ln(16);
    }

    public function Footer()
    {
        $this->SetY(-17);
        $this->Cell(($this->GetPageWidth() - 20), 0, '', 'T');
        $this->Image(__DIR__ . '/ti-logo.png', ($this->GetPageWidth() - 20) / 2, 200, 15, 15);
        $this->SetFont('Arial', '', 10);
        $this->Cell(-18, 10, 'Pagina '.$this->PageNo().' di {nb}', 0, 0, 'C');
    }
}
