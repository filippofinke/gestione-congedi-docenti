<?php
namespace FilippoFinke\Models;

use Fpdf\Fpdf;

class RequestPdf extends Fpdf
{
    public function __construct($reasons, $request, $hours, $user)
    {
        parent::__construct('L', 'mm', 'letter');
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(12, 8, "Cognome e nome:");
        $this->SetFont('Arial', '', 13);
        $this->Cell(30);
        $full_name = iconv('UTF-8', 'windows-1252', $user["last_name"]." ".$user["name"]);
        $this->Cell(40, 8, $full_name);
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(45, 8, "Periodo di assenza:");
        $this->SetFont('Arial', '', 12);
        $dates = [];
        for ($i = 0; $i < count($hours); $i++) {
            $hour = explode(" ", $hours[$i]["from_date"])[0];
            $date = strtotime($hour);
            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }
        }
        sort($dates);
        $str = "";
        for ($i = 0; $i < count($dates); $i++) {
            $str .= date("d.m.Y", $dates[$i]);
            if ($i != count($dates) - 1) {
                $str .= ",";
            }
        }
        $this->Cell(50, 8, $str);
        $this->Ln(8);
        $this->SetFont('Arial', 'BI', 11);
        $this->Cell(210, 7, 'Congedo pagato (art.27, 46, 47, 48, 49 LORD 1995 + art. 18, 19, 25, 26, 30, 31, 32, 33, 34, 35, 36, 38, 40 R. dip. 2017)', 'B');
        $this->Ln(10);
        $this->Cell(100, 8, "Motivazioni relative al congedo:");
        $this->Ln(10);
        foreach ($reasons as $reason) {
            $this->Multicell($this->GetPageWidth() - 20, 6, $reason["name"]."\n".$reason["description"], 1);
        }
        /*$newLine = 0;
        $width = ($this->GetPageWidth() - 20) / 2;
        foreach ($reasons as $reason) {
            $y = $this->GetY();
            $x = $this->GetX();
            $this->Multicell($width, 6, $reason["name"]."\n".$reason["description"], 1);
            $this->SetXY($x + $width, $y);
            $newLine++;
            if ($newLine % 2 == 0) {
                $this->SetX(10);
                $this->SetY($y + 6 * 2);
            }
        }*/
        $currentY = $this->GetY();
        $this->Ln($this->GetPageHeight() - $currentY - 50);
        $status = RequestStatus::get($request["status"]);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(100, 10, 'Decisione della direzione: '.$status);
        $this->Ln(6);
        $observations = iconv('UTF-8', 'windows-1252', $request["observations"]);
        $this->SetFont('Arial', '', 11);
        $this->Cell(100, 10, 'Osservazioni: '.$observations);
        $this->Ln(6);
        $auditor = iconv('UTF-8', 'windows-1252', $request["auditor"]);
        $this->Cell(100, 10, 'Firma: '.$auditor);
        $this->Ln(6);
        $this->Cell(50, 10, 'Data richiesta: '.date("d.m.Y", strtotime($request["created_at"])));
        $this->Cell(100, 10, 'Data revisione: '.date("d.m.Y", strtotime($request["updated_at"])));
        $text = iconv('UTF-8', 'windows-1252', "Il congedo è stato creato dall'account di rete: ".$user["username"]);
        $this->Cell(100, 10, $text, 0, 0, 'R');

        // Pagina del calendario
        $this->AddPage();
        $this->SetFont('Arial', 'BI', 13);
        $this->Cell(20, 10, "Piano di supplenza:");
        $this->SetFont('Arial', '', 13);
        $this->Cell(30);
        $this->Cell(50, 10, 'Settimana '.$request["week"]);
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 10, 'Nella casella "classe" indicare la sigla della professione e la classe');
        $this->Ln(7);
        $this->SetFont('Arial', 'UI', 9);
        $this->Cell(30, 10, 'Possibili soluzioni');
        $this->SetFont('Arial', '', 9);
        $this->Cell(100, 10, 'Indicare a fianco del nome del supplente la sigla corrispondente: supplenza interna (SI), scambio d\'orario (SO), sorveglianza parallela (SP), supplente esterno (SE)');
        $this->Ln(10);

        $this->Cell(10, 10, '', 1);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(13, 10, 'Ora', 1, 0, 'C');
        $this->SetFont('Arial', '', 8);
        $hoursWidth = 13;
        $this->SetFillColor(219, 219, 219);
        foreach (CALENDAR_HOURS as $hour) {
            if (isset($hour["space"])) {
                $this->Cell(5);
                continue;
            }

            $fill = false;
            if (!$hour["allow"]) {
                $fill = true;
            }
            $y = $this->GetY();
            $x = $this->getX();
            $this->MultiCell($hoursWidth, 5, $hour["start"]."\n".$hour["end"], 1, 'C', $fill);
            $this->SetY($y);
            $this->SetX($x + $hoursWidth);
        }
        $this->ln(10);

        $rowHeight = 23;
        foreach (CALENDAR_LABELS as $index => $label) {
            $this->Cell(5, $rowHeight, '', 1);
            $label = iconv('UTF-8', 'windows-1252', strtoupper($label));
            $width = round($this->GetStringWidth($label));
            $margin = $rowHeight - (($rowHeight - $width) / 2);
            $this->TextWithDirection($this->GetX() - 1.7, $this->GetY() + $margin, $label, 'U');
            $this->Cell(5, $rowHeight, '', 1);
            foreach ($hours as $hour) {
                $date = strtotime($hour["from_date"]);
                $i = date("N", $date) - 1;
                if ($i == $index) {
                    $string = date("d.m.Y", $date);
                    $width = round($this->GetStringWidth($string));
                    $margin = $rowHeight - (($rowHeight - $width) / 2);
                    $this->TextWithDirection($this->GetX() - 1.7, $this->GetY() + $margin, $string, 'U');
                    break;
                }
            }

            $this->SetFont('Arial', '', 7);
            $h = $rowHeight / 3;
            $this->Cell(13, $h, 'Classe', 1, 0, 'C');
            $this->SetY($this->GetY() + $h);
            $this->Cell(10);
            $this->Cell(13, $h, 'Aula', 1, 0, 'C');
            $this->SetY($this->GetY() + $h);
            $this->Cell(10);
            $this->Cell(13, $h, 'Supplente', 1, 0, 'C');
            $this->Ln();

            $this->SetY($this->GetY() - $rowHeight);
            $this->Cell(23);
            // Classe
            foreach (CALENDAR_HOURS as $hour) {
                if (isset($hour["space"])) {
                    $this->Cell(5);
                    continue;
                }

                $fill = false;
                if (!$hour["allow"]) {
                    $fill = true;
                }

                $block = $this->getCurrentBlock($index, $hour, $hours);
                $class = $block["class"];
                $this->Cell($hoursWidth, $h, $class, 1, 0, 'C', $fill);
            }

            $this->SetY($this->GetY() + $rowHeight / 3);
            $this->Cell(23);
            // Aula
            foreach (CALENDAR_HOURS as $hour) {
                if (isset($hour["space"])) {
                    $this->Cell(5);
                    continue;
                }

                $fill = false;
                if (!$hour["allow"]) {
                    $fill = true;
                }
                $block = $this->getCurrentBlock($index, $hour, $hours);
                $room = $block["room"];
                $this->Cell($hoursWidth, $h, $room, 1, 0, 'C', $fill);
            }

            $this->SetY($this->GetY() + $rowHeight / 3);
            $this->Cell(23);
            // Supplente
            foreach (CALENDAR_HOURS as $hour) {
                if (isset($hour["space"])) {
                    $this->Cell(5);
                    continue;
                }

                $fill = false;
                if (!$hour["allow"]) {
                    $fill = true;
                }
                $block = $this->getCurrentBlock($index, $hour, $hours);
                $substitute = $block["substitute"];
                $type = $block["type"];
                $string = $type." ".$substitute;
                $this->Cell($hoursWidth, $h, $string, 1, 0, 'C', $fill);
            }

            $this->Ln();
        }
    }

    public function getContent($type) {
        return $this->Output($type);
    } 

    private function getCurrentBlock($day, $hour, $blocks)
    {
        $start = strtotime($hour["start"].":00");
        $end = strtotime($hour["end"].":00");
        foreach ($blocks as $block) {
            $bDay = date("N", strtotime($block["from_date"])) - 1;
            if ($day != $bDay) {
                continue;
            } else {
                $bStart = explode(" ", $block["from_date"])[1];
                $startB = strtotime($bStart);
                $bEnd = explode(" ", $block["to_date"])[1];
                $endB = strtotime($bEnd);
                if (($startB <= $start && $end <= $endB) || $start == $startB || $end == $endB) {
                    return $block;
                }
            }
        }
    }

    public function TextWithDirection($x, $y, $txt, $direction='R')
    {
        if ($direction=='R') {
            $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 1, 0, 0, 1, $x*$this->k, ($this->h-$y)*$this->k, $this->_escape($txt));
        } elseif ($direction=='L') {
            $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', -1, 0, 0, -1, $x*$this->k, ($this->h-$y)*$this->k, $this->_escape($txt));
        } elseif ($direction=='U') {
            $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, 1, -1, 0, $x*$this->k, ($this->h-$y)*$this->k, $this->_escape($txt));
        } elseif ($direction=='D') {
            $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, -1, 1, 0, $x*$this->k, ($this->h-$y)*$this->k, $this->_escape($txt));
        } else {
            $s=sprintf('BT %.2F %.2F Td (%s) Tj ET', $x*$this->k, ($this->h-$y)*$this->k, $this->_escape($txt));
        }
        if ($this->ColorFlag) {
            $s='q '.$this->TextColor.' '.$s.' Q';
        }
        $this->_out($s);
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
