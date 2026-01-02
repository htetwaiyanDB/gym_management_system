<?php

namespace App\Support;

use App\Models\AttendanceScan;
use App\Models\MemberMembership;
use App\Models\TrainerBooking;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Support\Collection;

class DashboardReportExporter
{
    public function buildReportData(): array
    {
        return [
            'Users' => User::query()->latest()->get(),
            'Subscriptions' => MemberMembership::with(['member', 'plan'])
                ->latest('start_date')
                ->get(),
            'Trainer Bookings' => TrainerBooking::with(['member', 'trainer'])
                ->latest('session_datetime')
                ->get(),
            'Attendance Scans' => AttendanceScan::with('user')
                ->latest('scanned_at')
                ->get(),
        ];
    }

    public function buildExcelDocument(array $reportData): string
    {
        $sections = collect($reportData)->map(function (Collection $rows, string $title) {
            $normalizedRows = $this->normalizeRows($rows);

            return $this->renderTable($title, $normalizedRows);
        })->implode("\n");

        return <<<HTML
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard Report</title>
    </head>
    <body>
        {$sections}
    </body>
</html>
HTML;
    }

    private function normalizeRows(Collection $rows): array
    {
        return $rows->map(function ($row) {
            $data = is_array($row) ? $row : $row->toArray();

            return collect($data)->map(function ($value) {
                return $this->stringifyValue($value);
            })->all();
        })->all();
    }

    private function stringifyValue($value): string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) ($value ?? '');
    }

    private function renderTable(string $title, array $rows): string
    {
        $escapedTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

        if (count($rows) === 0) {
            return <<<HTML
<h2>{$escapedTitle}</h2>
<p>No data available.</p>
HTML;
        }

        $headers = array_keys($rows[0]);
        $headerCells = collect($headers)
            ->map(fn ($header) => '<th>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</th>')
            ->implode('');

        $bodyRows = collect($rows)->map(function (array $row) use ($headers) {
            $cells = collect($headers)->map(function (string $header) use ($row) {
                $value = $row[$header] ?? '';

                return '<td>' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</td>';
            })->implode('');

            return "<tr>{$cells}</tr>";
        })->implode('');

        return <<<HTML
<h2>{$escapedTitle}</h2>
<table border="1" cellpadding="4" cellspacing="0">
    <thead>
        <tr>{$headerCells}</tr>
    </thead>
    <tbody>
        {$bodyRows}
    </tbody>
</table>
<br />
HTML;
    }
}
