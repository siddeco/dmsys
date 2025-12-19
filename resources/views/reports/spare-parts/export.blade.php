<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Spare Part</th>
            <th>Type</th>
            <th>Qty</th>
            <th>Project</th>
            <th>Performed By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $r)
            <tr>
                <td>{{ $r->created_at->format('Y-m-d') }}</td>
                <td>{{ $r->sparePart->name ?? '-' }}</td>
                <td>{{ strtoupper($r->type) }}</td>
                <td>{{ $r->quantity }}</td>
                <td>{{ $r->breakdown->project->name ?? '-' }}</td>
                <td>{{ $r->performer->name ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>