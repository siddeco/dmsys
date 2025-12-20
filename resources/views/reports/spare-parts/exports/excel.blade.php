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
        @foreach($usages as $u)
            <tr>
                <td>{{ $u->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $u->sparePart->name ?? '-' }}</td>
                <td>{{ strtoupper($u->type) }}</td>
                <td>{{ $u->quantity }}</td>
                <td>{{ $u->breakdown ? '#' . $u->breakdown->id : '-' }}</td>
                <td>{{ $u->performer->name ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>