<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Spare Parts Consumption Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .meta {
            margin-bottom: 10px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        .type-issue {
            color: #dc3545;
            font-weight: bold;
        }

        .type-return {
            color: #198754;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>{{ $company }}</h2>
        <strong>Spare Parts Consumption Report</strong>
    </div>

    <div class="meta">
        Generated at: {{ $generatedAt->format('Y-m-d H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Spare Part</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Breakdown</th>
                <th>Performed By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usages as $u)
                <tr>
                    <td>{{ $u->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $u->sparePart->name ?? '-' }}</td>
                    <td class="{{ $u->type === 'issue' ? 'type-issue' : 'type-return' }}">
                        {{ strtoupper($u->type) }}
                    </td>
                    <td>{{ $u->quantity }}</td>
                    <td>{{ $u->breakdown ? '#' . $u->breakdown->id : '-' }}</td>
                    <td>{{ $u->performer->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center">
                        No data available
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>