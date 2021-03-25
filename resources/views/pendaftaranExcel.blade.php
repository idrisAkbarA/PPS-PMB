<table>
    <thead>
        <tr>
            <th>
                Laporan Pendaftaran
            </th>
            @if ($data['periode'])
                <th>
                    Laporan Pendaftaran {{ $data['periode'] }}
                </th>
            @endif
            @if ($data['jurusan'])
                <th>
                    Laporan Pendaftaran {{ $data['periode'] }} {{ $data['jurusan'] }}
                </th>
            @endif
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold">No.</th>
            @if ($data['periode'])
                <th style="font-weight: bold">Periode</th>
            @endif
            @if ($data['jurusan'])
                <th style="font-weight: bold">Jurusan</th>
            @endif
            <th style="font-weight: bold">Nama</th>
            <th style="font-weight: bold">Jalur Masuk</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['pendaftaran'] as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                @if (!$data['periode'])
                    <td>{{ $value['periode']['nama'] }}</td>
                @endif
                @if (!$data['jurusan'])
                    <td>{{ $$value['jurusan']['nama'] }}</td>
                @endif
                <td>{{ $value['nama'] }}</td>
                <td>
                    @if ($value['is_jalur_cumlaude'])
                        Cumlaude
                    @else
                        Reguler
                    @endif
                </td>
                <td>{{ $user->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
