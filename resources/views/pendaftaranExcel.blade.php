<table>
    <thead>
        <tr>
            <th style="height: 70px; font-weight: bold; font-size:20px" colspan="7">

                Laporan Pendaftaran

                <br>
                Periode:
                {{ $data['periode']['nama'] }}


                @if (isset($data['jurusan']))
                    <br>
                    Jurusan:
                    {{ $data['jurusan']['nama'] }}
                @endif

            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold">No.</th>


            @if (!isset($data['jurusan']))
                <th style="font-weight: bold">Jurusan</th>
            @endif
            <th style="font-weight: bold">Nama</th>
            <th style="font-weight: bold">Pembayaran</th>
            <th style="font-weight: bold">Jalur Masuk</th>
            <th style="font-weight: bold">Status Kelulusan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['pendaftaran'] as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                @if (!isset($data['jurusan']))
                    <td>{{ $value['jurusan']['nama'] }}</td>
                @endif
                <td>{{ $value['user_cln_mhs']['nama'] }}</td>
                <td>
                    @if ($value['lunas_at'] == null)
                        Belum Lunas
                    @else
                        Lunas
                    @endif
                </td>
                @if ($value['is_jalur_cumlaude'])
                    <td>
                        Cumlaude
                    </td>
                    @if ($value['is_lulus_tka'] == 1)
                        Lulus
                    @elseif ($value['is_lulus_tka'] == 0)
                        Tidak Lulus
                    @else
                        Belum Verifikasi
                    @endif
                    <td>

                    </td>
                @else
                    <td>
                        Reguler
                    </td>
                    <td>
                        {{ $value['status_kelulusan'] }}
                    </td>
                @endif

            </tr>
        @endforeach
    </tbody>
</table>
