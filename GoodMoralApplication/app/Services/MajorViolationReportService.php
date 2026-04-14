<?php

namespace App\Services;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Cell;

class MajorViolationReportService
{
    private PhpWord $phpWord;

    // Color constants (RGB hex without #)
    private const COLOR_GREEN = '00B050';
    private const COLOR_YELLOW = 'FFFF00';
    private const COLOR_LIGHT_GREEN = '90EE90';
    private const COLOR_LIGHT_BLUE = '87CEEB';
    private const COLOR_BLACK = '000000';
    private const COLOR_WHITE = 'FFFFFF';

    /**
     * Generate the Major Violation Report as a DOCX file.
     *
     * @param array $reportData
     * @return string Path to the generated temp file
     */
    public function generateDocx(array $reportData): string
    {
        $this->phpWord = new PhpWord();
        $this->setupDefaultStyles();

        $section = $this->phpWord->addSection([
            'paperSize' => 'Letter',
            'orientation' => 'portrait',
            'marginTop' => 500,
            'marginBottom' => 500,
            'marginLeft' => 720,
            'marginRight' => 720,
        ]);

        $this->addHeader($section, $reportData);
        $this->addTwoToneLine($section);
        $this->addReportTitles($section, $reportData);
        $this->addViolationsTable($section, $reportData);
        $this->addOverallReport($section, $reportData);
        $this->addFooter($section);

        $tempFile = tempnam(sys_get_temp_dir(), 'major_report_') . '.docx';
        $writer = IOFactory::createWriter($this->phpWord, 'Word2007');
        $writer->save($tempFile);

        return $tempFile;
    }

    /**
     * Generate the report and convert to PDF.
     *
     * @param array $reportData
     * @return string Path to the generated PDF temp file
     */
    public function generatePdf(array $reportData): string
    {
        $docxPath = $this->generateDocx($reportData);

        // Configure PHPWord to use DomPDF as the PDF renderer
        $domPdfPath = base_path('vendor/dompdf/dompdf');
        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);

        $tempPdf = tempnam(sys_get_temp_dir(), 'major_report_') . '.pdf';

        $phpWord = IOFactory::load($docxPath);
        $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
        $pdfWriter->save($tempPdf);

        // Clean up the temp DOCX
        @unlink($docxPath);

        return $tempPdf;
    }

    private function setupDefaultStyles(): void
    {
        $this->phpWord->setDefaultFontName('Times New Roman');
        $this->phpWord->setDefaultFontSize(11);
    }

    private function addHeader($section, array $reportData): void
    {
        $headerImgPath = public_path('reports/header.png');

        if (file_exists($headerImgPath)) {
            $section->addImage($headerImgPath, [
                'width' => 480,
                'alignment' => Jc::CENTER,
            ]);
        }

        $section->addText(
            'OFFICE OF STUDENT AFFAIRS',
            ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 0]
        );
    }

    private function addTwoToneLine($section): void
    {
        // Yellow line
        $table = $section->addTable([
            'borderSize' => 0,
            'cellMargin' => 0,
            'alignment' => Jc::CENTER,
        ]);
        $table->addRow(40);
        $cell = $table->addCell(9500, [
            'bgColor' => self::COLOR_YELLOW,
            'borderSize' => 0,
        ]);
        $cell->addText('', ['size' => 1]);

        // Green line
        $table2 = $section->addTable([
            'borderSize' => 0,
            'cellMargin' => 0,
            'alignment' => Jc::CENTER,
        ]);
        $table2->addRow(40);
        $cell2 = $table2->addCell(9500, [
            'bgColor' => self::COLOR_GREEN,
            'borderSize' => 0,
        ]);
        $cell2->addText('', ['size' => 1]);
    }

    private function addReportTitles($section, array $reportData): void
    {
        $section->addTextBreak(1);

        $section->addText(
            'ACADEMIC YEAR ' . $reportData['academic_year'],
            ['bold' => true, 'size' => 14, 'name' => 'Times New Roman'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 60]
        );

        $section->addText(
            'LIST OF VIOLATORS',
            ['bold' => true, 'size' => 12, 'name' => 'Times New Roman', 'underline' => 'single'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 60]
        );

        $section->addText(
            '(Major Offense)',
            ['bold' => true, 'size' => 11, 'name' => 'Times New Roman'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 120]
        );

        if (isset($reportData['time_period_info']) && $reportData['time_period'] !== 'all') {
            $section->addText(
                'Time Period: ' . $reportData['time_period_info']['period'],
                ['size' => 9, 'color' => '666666'],
                ['alignment' => Jc::CENTER, 'spaceAfter' => 120]
            );
        }
    }

    private function addViolationsTable($section, array $reportData): void
    {
        $cases = $reportData['cases'];

        // Define table style
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => self::COLOR_BLACK,
            'cellMargin' => 40,
            'alignment' => Jc::CENTER,
        ];

        $headerCellStyle = [
            'bgColor' => self::COLOR_WHITE,
            'valign' => 'center',
            'borderSize' => 8,
            'borderColor' => self::COLOR_BLACK,
        ];

        $headerFontStyle = [
            'bold' => true,
            'size' => 8,
            'name' => 'Times New Roman',
        ];

        $headerParagraphStyle = [
            'alignment' => Jc::CENTER,
            'spaceAfter' => 0,
            'spaceBefore' => 0,
        ];

        // Column widths (twips): CASE NO, NAMES, COURSE, VIOLATION, REMARKS, DATE
        $colWidths = [800, 2400, 1200, 1800, 1200, 1800];

        $table = $section->addTable($tableStyle);

        // Header row
        $table->addRow(null, ['tblHeader' => true]);
        $headers = ['CASE NO.', 'NAME/S', 'COURSE', 'VIOLATION/S or ACCUSATION', 'REMARKS', 'DATE SUBMITTED/ FILED'];

        foreach ($headers as $i => $header) {
            $cell = $table->addCell($colWidths[$i], $headerCellStyle);
            $cell->addText($header, $headerFontStyle, $headerParagraphStyle);
        }

        // Data rows
        foreach ($cases as $index => $case) {
            $bgColor = $case->is_closed ? self::COLOR_LIGHT_BLUE : self::COLOR_LIGHT_GREEN;

            $cellStyle = [
                'bgColor' => $bgColor,
                'valign' => 'center',
                'borderSize' => 6,
                'borderColor' => self::COLOR_BLACK,
            ];

            $fontStyle = ['size' => 9, 'name' => 'Times New Roman'];
            $fontStyleBold = ['size' => 9, 'name' => 'Times New Roman', 'bold' => true];
            $centerParagraph = ['alignment' => Jc::CENTER, 'spaceAfter' => 0, 'spaceBefore' => 0];
            $leftParagraph = ['alignment' => Jc::START, 'spaceAfter' => 0, 'spaceBefore' => 0];

            $table->addRow();

            // CASE NO.
            $cell = $table->addCell($colWidths[0], $cellStyle);
            $cell->addText((string)($index + 1), $fontStyleBold, $centerParagraph);

            // NAME/S
            $cell = $table->addCell($colWidths[1], $cellStyle);
            $cell->addText($case->names, $fontStyleBold, $leftParagraph);

            // COURSE
            $cell = $table->addCell($colWidths[2], $cellStyle);
            $cell->addText($case->courses, $fontStyleBold, $centerParagraph);

            // VIOLATION
            $cell = $table->addCell($colWidths[3], $cellStyle);
            $cell->addText($case->violation, $fontStyle, $centerParagraph);

            // REMARKS
            $cell = $table->addCell($colWidths[4], $cellStyle);
            $cell->addText($case->status_text, $fontStyleBold, $centerParagraph);

            // DATE
            $cell = $table->addCell($colWidths[5], $cellStyle);
            $cell->addText($case->date_filed->format('Y-m-d'), $fontStyle, $centerParagraph);
        }
    }

    private function addOverallReport($section, array $reportData): void
    {
        $cases = $reportData['cases'];

        $section->addTextBreak(1);
        $section->addText(
            'OVERALL REPORT',
            ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 120]
        );

        // Department statistics
        $departmentStats = [];
        $totalCases = 0;
        $totalClosed = 0;
        $totalPending = 0;
        $totalStudents = 0;

        $casesByDept = $cases->groupBy('department');

        foreach ($casesByDept as $dept => $deptCases) {
            $caseCount = $deptCases->count();
            $closed = $deptCases->where('is_closed', true)->count();
            $pending = $caseCount - $closed;
            $uniqueStudents = $deptCases->flatMap(fn($c) => $c->student_ids)->unique()->count();

            $departmentStats[] = [
                'department' => $dept,
                'cases' => $caseCount,
                'closed' => $closed,
                'pending' => $pending,
                'students' => $uniqueStudents,
            ];

            $totalCases += $caseCount;
            $totalClosed += $closed;
            $totalPending += $pending;
            $totalStudents += $uniqueStudents;
        }

        $populationData = $reportData['populationData'] ?? [];
        $totalPopulation = array_sum($populationData);

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => self::COLOR_BLACK,
            'cellMargin' => 40,
            'alignment' => Jc::CENTER,
        ];

        $headerCellStyle = [
            'bgColor' => self::COLOR_WHITE,
            'valign' => 'center',
            'borderSize' => 8,
            'borderColor' => self::COLOR_BLACK,
        ];

        $headerFont = ['bold' => true, 'size' => 7, 'name' => 'Times New Roman'];
        $centerP = ['alignment' => Jc::CENTER, 'spaceAfter' => 0, 'spaceBefore' => 0];
        $dataFont = ['size' => 9, 'name' => 'Times New Roman'];
        $dataFontBold = ['size' => 9, 'name' => 'Times New Roman', 'bold' => true];

        $colWidths = [1300, 1200, 1300, 1300, 1200, 1200, 1700];

        $table = $section->addTable($tableStyle);

        // Header
        $table->addRow(null, ['tblHeader' => true]);
        $overallHeaders = ['DEPARTMENT', 'NUMBER OF CASES', 'NUMBER OF CLOSED CASES', 'NUMBER OF PENDING CASES', 'NUMBER OF STUDENTS', 'TOTAL POPULATION', 'PERCENTAGE FROM TOTAL POPULATION'];

        foreach ($overallHeaders as $i => $header) {
            $cell = $table->addCell($colWidths[$i], $headerCellStyle);
            $cell->addText($header, $headerFont, $centerP);
        }

        // Data rows
        foreach ($departmentStats as $stat) {
            $cellStyle = [
                'valign' => 'center',
                'borderSize' => 6,
                'borderColor' => self::COLOR_BLACK,
            ];

            $table->addRow();

            $cell = $table->addCell($colWidths[0], $cellStyle);
            $cell->addText($stat['department'], $dataFontBold, $centerP);

            $cell = $table->addCell($colWidths[1], $cellStyle);
            $cell->addText((string)$stat['cases'], $dataFont, $centerP);

            $cell = $table->addCell($colWidths[2], $cellStyle);
            $cell->addText((string)$stat['closed'], $dataFont, $centerP);

            $cell = $table->addCell($colWidths[3], $cellStyle);
            $cell->addText((string)$stat['pending'], $dataFont, $centerP);

            $cell = $table->addCell($colWidths[4], $cellStyle);
            $cell->addText((string)$stat['students'], $dataFont, $centerP);

            $population = $populationData[$stat['department']] ?? 0;
            $cell = $table->addCell($colWidths[5], $cellStyle);
            $cell->addText((string)$population, $dataFont, $centerP);

            $percentage = $population > 0 ? ($stat['students'] / $population) * 100 : 0;
            $cell = $table->addCell($colWidths[6], $cellStyle);
            $cell->addText(number_format($percentage, 2) . '%', $dataFont, $centerP);
        }

        // Total row
        $totalCellStyle = [
            'bgColor' => self::COLOR_YELLOW,
            'valign' => 'center',
            'borderSize' => 6,
            'borderColor' => self::COLOR_BLACK,
        ];
        $totalFont = ['size' => 9, 'name' => 'Times New Roman', 'bold' => true];

        $table->addRow();

        $cell = $table->addCell($colWidths[0], $totalCellStyle);
        $cell->addText('TOTAL', $totalFont, $centerP);

        $cell = $table->addCell($colWidths[1], $totalCellStyle);
        $cell->addText((string)$totalCases, $totalFont, $centerP);

        $cell = $table->addCell($colWidths[2], $totalCellStyle);
        $cell->addText((string)$totalClosed, $totalFont, $centerP);

        $cell = $table->addCell($colWidths[3], $totalCellStyle);
        $cell->addText((string)$totalPending, $totalFont, $centerP);

        $cell = $table->addCell($colWidths[4], $totalCellStyle);
        $cell->addText((string)$totalStudents, $totalFont, $centerP);

        $cell = $table->addCell($colWidths[5], $totalCellStyle);
        $cell->addText((string)$totalPopulation, $totalFont, $centerP);

        $overallPercentage = $totalPopulation > 0 ? ($totalStudents / $totalPopulation) * 100 : 0;
        $cell = $table->addCell($colWidths[6], $totalCellStyle);
        $cell->addText(number_format($overallPercentage, 2) . '%', $totalFont, $centerP);
    }

    private function addFooter($section): void
    {
        $section->addTextBreak(1);

        // Two-tone line
        $this->addTwoToneLine($section);

        $footerImgPath = public_path('reports/footer.png');

        if (file_exists($footerImgPath)) {
            $section->addImage($footerImgPath, [
                'width' => 480,
                'alignment' => Jc::CENTER,
            ]);
        }
    }
}
