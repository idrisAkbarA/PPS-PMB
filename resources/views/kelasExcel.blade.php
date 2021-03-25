<table>
    <thead>
        <tr>
            <th style="font-weight: bold">No.</th>
            <th style="font-weight: bold">Tipe Soal</th>
            <th style="font-weight: bold">Jurusan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
