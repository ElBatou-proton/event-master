<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EventExcelExporter
{
    public function generate(array $event, array $providers, array $tasks): string
    {
        $spreadsheet = new Spreadsheet();

        // -------------------------------------------------------------
        // SHEET 1: OVERVIEW
        // -------------------------------------------------------------
        $sheetOverview = $spreadsheet->getActiveSheet();
        $sheetOverview->setTitle(mb_substr(lang('App.export_sheet_overview'), 0, 31));

        // Header Title
        $sheetOverview->mergeCells('A1:D1');
        $sheetOverview->setCellValue('A1', $event['title']);
        $sheetOverview->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setARGB('FFFFFFFF');
        $sheetOverview->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E293B');
        $sheetOverview->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheetOverview->getRowDimension(1)->setRowHeight(36);

        $sheetOverview->mergeCells('A2:D2');
        $sheetOverview->setCellValue('A2', lang('App.export_event_summary') . ' • ' . date('Y-m-d H:i'));
        $sheetOverview->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->getColor()->setARGB('FF64748B');
        $sheetOverview->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
        $sheetOverview->getRowDimension(2)->setRowHeight(20);

        // Section: Basic Info
        $row = 4;
        $sheetOverview->mergeCells("A{$row}:D{$row}");
        $sheetOverview->setCellValue("A{$row}", strtoupper(lang('App.export_basic_info')));
        $this->styleSectionHeader($sheetOverview, "A{$row}:D{$row}");
        $row++;

        $typeKey = 'App.type_' . ($event['event_type'] ?? 'other');
        $translatedType = lang($typeKey);
        if ($translatedType === $typeKey) {
            $translatedType = ucfirst($event['event_type'] ?? '');
        }

        $statusKey = 'App.status_' . ($event['status'] ?? 'draft');
        $translatedStatus = lang($statusKey);
        if ($translatedStatus === $statusKey) {
            $translatedStatus = ucwords(str_replace('_', ' ', $event['status'] ?? ''));
        }

        $basicFields = [
            lang('App.event_title')       => $event['title'] ?? '',
            lang('App.event_type')        => $translatedType,
            lang('App.status')            => $translatedStatus,
            lang('App.event_date')        => $event['event_date'] ?? '',
            lang('App.start_time')        => ($event['start_time'] ?? '') . ($event['end_time'] ? ' - ' . $event['end_time'] : ''),
            lang('App.location_venue')    => $event['location_venue'] ?: lang('App.venue_tba'),
            lang('App.expected_guests')   => (int) ($event['guest_count'] ?? 0),
        ];

        foreach ($basicFields as $label => $val) {
            $sheetOverview->setCellValue("A{$row}", $label);
            $sheetOverview->setCellValue("B{$row}", $val);
            $sheetOverview->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setARGB('FF475569');
            $row++;
        }

        // Section: Client Details
        $row++;
        $sheetOverview->mergeCells("A{$row}:D{$row}");
        $sheetOverview->setCellValue("A{$row}", strtoupper(lang('App.export_client_info')));
        $this->styleSectionHeader($sheetOverview, "A{$row}:D{$row}");
        $row++;

        $clientFields = [
            lang('App.client_name')  => $event['client_name'] ?? '',
            lang('App.client_email') => $event['client_email'] ?? '',
            lang('App.client_phone') => $event['client_phone'] ?? '',
        ];

        foreach ($clientFields as $label => $val) {
            $sheetOverview->setCellValue("A{$row}", $label);
            $sheetOverview->setCellValue("B{$row}", $val);
            $sheetOverview->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setARGB('FF475569');
            $row++;
        }

        // Section: Financial Breakdown
        $row++;
        $sheetOverview->mergeCells("A{$row}:D{$row}");
        $sheetOverview->setCellValue("A{$row}", strtoupper(lang('App.export_financial_summary')));
        $this->styleSectionHeader($sheetOverview, "A{$row}:D{$row}");
        $row++;

        $financialFields = [
            lang('App.budget_allocated')     => (float) ($event['budget'] ?? 0),
            lang('App.total_committed_cost') => (float) ($event['total_provider_cost'] ?? 0),
            lang('App.deposits_paid')        => (float) ($event['total_deposit_paid'] ?? 0),
            lang('App.remaining_balance')    => (float) ($event['remaining_balance'] ?? 0),
            lang('App.budget_variance')      => (float) ($event['budget_variance'] ?? 0),
        ];

        foreach ($financialFields as $label => $val) {
            $sheetOverview->setCellValue("A{$row}", $label);
            $sheetOverview->setCellValue("B{$row}", $val);
            $sheetOverview->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setARGB('FF475569');
            $sheetOverview->getStyle("B{$row}")->getNumberFormat()->setFormatCode('$#,##0.00');
            $row++;
        }

        // Section: Checklist Progress
        $row++;
        $sheetOverview->mergeCells("A{$row}:D{$row}");
        $sheetOverview->setCellValue("A{$row}", strtoupper(lang('App.export_tasks_summary')));
        $this->styleSectionHeader($sheetOverview, "A{$row}:D{$row}");
        $row++;

        $taskSummary = [
            lang('App.checklist_milestones') => ($event['completed_tasks'] ?? 0) . ' / ' . ($event['total_tasks'] ?? 0) . ' ' . lang('App.done') . ' (' . ($event['task_percentage'] ?? 0) . '%)',
        ];

        foreach ($taskSummary as $label => $val) {
            $sheetOverview->setCellValue("A{$row}", $label);
            $sheetOverview->setCellValue("B{$row}", $val);
            $sheetOverview->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setARGB('FF475569');
            $row++;
        }

        // Section: Notes & Description
        if (!empty($event['description']) || !empty($event['notes'])) {
            $row++;
            $sheetOverview->mergeCells("A{$row}:D{$row}");
            $sheetOverview->setCellValue("A{$row}", strtoupper(lang('App.export_notes_description')));
            $this->styleSectionHeader($sheetOverview, "A{$row}:D{$row}");
            $row++;

            if (!empty($event['description'])) {
                $sheetOverview->setCellValue("A{$row}", lang('App.description'));
                $sheetOverview->setCellValue("B{$row}", $event['description']);
                $sheetOverview->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setARGB('FF475569');
                $sheetOverview->getStyle("B{$row}")->getAlignment()->setWrapText(true);
                $row++;
            }

            if (!empty($event['notes'])) {
                $sheetOverview->setCellValue("A{$row}", lang('App.internal_notes'));
                $sheetOverview->setCellValue("B{$row}", $event['notes']);
                $sheetOverview->getStyle("A{$row}")->getFont()->setBold(true)->getColor()->setARGB('FF475569');
                $sheetOverview->getStyle("B{$row}")->getAlignment()->setWrapText(true);
                $row++;
            }
        }

        foreach (['A', 'B', 'C', 'D'] as $col) {
            $sheetOverview->getColumnDimension($col)->setAutoSize(true);
        }

        // -------------------------------------------------------------
        // SHEET 2: VENDORS & PROVIDERS
        // -------------------------------------------------------------
        $sheetProviders = $spreadsheet->createSheet();
        $sheetProviders->setTitle(mb_substr(lang('App.export_sheet_providers'), 0, 31));

        // Header Title
        $sheetProviders->mergeCells('A1:K1');
        $sheetProviders->setCellValue('A1', lang('App.assigned_providers') . ' - ' . $event['title']);
        $sheetProviders->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FFFFFFFF');
        $sheetProviders->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F46E5');
        $sheetProviders->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheetProviders->getRowDimension(1)->setRowHeight(32);

        // Column Headers
        $provHeaders = [
            'A3' => lang('App.col_provider'),
            'B3' => lang('App.col_category'),
            'C3' => lang('App.contact_person'),
            'D3' => lang('App.phone'),
            'E3' => lang('App.email'),
            'F3' => lang('App.col_service_scope'),
            'G3' => lang('App.col_agreed_cost'),
            'H3' => lang('App.col_deposit'),
            'I3' => lang('App.col_balance'),
            'J3' => lang('App.col_deposit_status'),
            'K3' => lang('App.col_contract'),
        ];

        foreach ($provHeaders as $cell => $headerText) {
            $sheetProviders->setCellValue($cell, $headerText);
        }

        $sheetProviders->getStyle('A3:K3')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheetProviders->getStyle('A3:K3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF312E81');
        $sheetProviders->getStyle('A3:K3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheetProviders->getRowDimension(3)->setRowHeight(24);

        $pRow = 4;
        if (!empty($providers)) {
            foreach ($providers as $ep) {
                $agreedCost  = (float) ($ep['agreed_cost'] ?? 0);
                $depositPaid = (float) ($ep['deposit_paid'] ?? 0);
                $balance     = $agreedCost - $depositPaid;

                $payKey = 'App.payment_' . ($ep['payment_status'] ?? 'pending');
                $payText = lang($payKey);
                if ($payText === $payKey) {
                    $payText = ucfirst($ep['payment_status'] ?? 'pending');
                }

                $contractKey = 'App.contract_' . ($ep['contract_status'] ?? 'draft');
                $contractText = lang($contractKey);
                if ($contractText === $contractKey) {
                    $contractText = ucfirst($ep['contract_status'] ?? 'draft');
                }

                $sheetProviders->setCellValue("A{$pRow}", $ep['provider_name'] ?? '');
                $sheetProviders->setCellValue("B{$pRow}", $ep['category_name'] ?? lang('App.category_general'));
                $sheetProviders->setCellValue("C{$pRow}", $ep['contact_person'] ?? '');
                $sheetProviders->setCellValue("D{$pRow}", $ep['provider_phone'] ?? '');
                $sheetProviders->setCellValue("E{$pRow}", $ep['provider_email'] ?? '');
                $sheetProviders->setCellValue("F{$pRow}", $ep['service_description'] ?? '');
                $sheetProviders->setCellValue("G{$pRow}", $agreedCost);
                $sheetProviders->setCellValue("H{$pRow}", $depositPaid);
                $sheetProviders->setCellValue("I{$pRow}", $balance);
                $sheetProviders->setCellValue("J{$pRow}", $payText);
                $sheetProviders->setCellValue("K{$pRow}", $contractText);

                // Number formatting for currency columns
                $sheetProviders->getStyle("G{$pRow}:I{$pRow}")->getNumberFormat()->setFormatCode('$#,##0.00');

                // Alternating row color
                if ($pRow % 2 === 0) {
                    $sheetProviders->getStyle("A{$pRow}:K{$pRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
                }

                $pRow++;
            }

            // Totals Row
            $sheetProviders->setCellValue("A{$pRow}", lang('App.export_total'));
            $sheetProviders->setCellValue("G{$pRow}", "=SUM(G4:G" . ($pRow - 1) . ")");
            $sheetProviders->setCellValue("H{$pRow}", "=SUM(H4:H" . ($pRow - 1) . ")");
            $sheetProviders->setCellValue("I{$pRow}", "=SUM(I4:I" . ($pRow - 1) . ")");

            $sheetProviders->getStyle("A{$pRow}:K{$pRow}")->getFont()->setBold(true);
            $sheetProviders->getStyle("G{$pRow}:I{$pRow}")->getNumberFormat()->setFormatCode('$#,##0.00');
            $sheetProviders->getStyle("A{$pRow}:K{$pRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');
            $sheetProviders->getStyle("A{$pRow}:K{$pRow}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
            $sheetProviders->getStyle("A{$pRow}:K{$pRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
        } else {
            $sheetProviders->mergeCells("A{$pRow}:K{$pRow}");
            $sheetProviders->setCellValue("A{$pRow}", lang('App.no_providers_assigned'));
            $sheetProviders->getStyle("A{$pRow}")->getFont()->setItalic(true)->getColor()->setARGB('FF94A3B8');
            $sheetProviders->getStyle("A{$pRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        foreach (range('A', 'K') as $col) {
            $sheetProviders->getColumnDimension($col)->setAutoSize(true);
        }

        // -------------------------------------------------------------
        // SHEET 3: CHECKLIST & TASKS
        // -------------------------------------------------------------
        $sheetTasks = $spreadsheet->createSheet();
        $sheetTasks->setTitle(mb_substr(lang('App.export_sheet_tasks'), 0, 31));

        // Header Title
        $sheetTasks->mergeCells('A1:E1');
        $sheetTasks->setCellValue('A1', lang('App.checklist_milestones') . ' - ' . $event['title']);
        $sheetTasks->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('FFFFFFFF');
        $sheetTasks->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF059669');
        $sheetTasks->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheetTasks->getRowDimension(1)->setRowHeight(32);

        // Column Headers
        $taskHeaders = [
            'A3' => lang('App.status'),
            'B3' => lang('App.task_title'),
            'C3' => lang('App.col_provider'),
            'D3' => lang('App.priority'),
            'E3' => lang('App.due_date'),
        ];

        foreach ($taskHeaders as $cell => $headerText) {
            $sheetTasks->setCellValue($cell, $headerText);
        }

        $sheetTasks->getStyle('A3:E3')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheetTasks->getStyle('A3:E3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF065F46');
        $sheetTasks->getStyle('A3:E3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheetTasks->getRowDimension(3)->setRowHeight(24);

        $tRow = 4;
        if (!empty($tasks)) {
            foreach ($tasks as $task) {
                $isCompleted = ($task['status'] === 'completed');
                $statusText  = $isCompleted ? lang('App.status_completed') : lang('App.task_todo');

                $priorityKey = 'App.priority_' . ($task['priority'] ?? 'medium');
                $priorityText = lang($priorityKey);
                if ($priorityText === $priorityKey) {
                    $priorityText = ucfirst($task['priority'] ?? 'medium');
                }

                $sheetTasks->setCellValue("A{$tRow}", $statusText);
                $sheetTasks->setCellValue("B{$tRow}", $task['title'] ?? '');
                $sheetTasks->setCellValue("C{$tRow}", $task['provider_name'] ?: '-');
                $sheetTasks->setCellValue("D{$tRow}", $priorityText);
                $sheetTasks->setCellValue("E{$tRow}", $task['due_date'] ? date('Y-m-d', strtotime($task['due_date'])) : '-');

                if ($isCompleted) {
                    $sheetTasks->getStyle("A{$tRow}")->getFont()->getColor()->setARGB('FF059669');
                    $sheetTasks->getStyle("B{$tRow}")->getFont()->getColor()->setARGB('FF94A3B8');
                }

                if ($tRow % 2 === 0) {
                    $sheetTasks->getStyle("A{$tRow}:E{$tRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
                }

                $tRow++;
            }
        } else {
            $sheetTasks->mergeCells("A{$tRow}:E{$tRow}");
            $sheetTasks->setCellValue("A{$tRow}", lang('App.no_tasks_added'));
            $sheetTasks->getStyle("A{$tRow}")->getFont()->setItalic(true)->getColor()->setARGB('FF94A3B8');
            $sheetTasks->getStyle("A{$tRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        foreach (range('A', 'E') as $col) {
            $sheetTasks->getColumnDimension($col)->setAutoSize(true);
        }

        // Set active sheet to Overview
        $spreadsheet->setActiveSheetIndex(0);

        // Generate binary content
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        return ob_get_clean();
    }

    private function styleSectionHeader($sheet, string $range): void
    {
        $sheet->getStyle($range)->getFont()->setBold(true)->setSize(11)->getColor()->setARGB('FF1E293B');
        $sheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');
        $sheet->getStyle($range)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFCBD5E1');
    }
}
