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
            <th style="font-weight: bold">No. HP</th>
            <th style="font-weight: bold">Pembayaran</th>
            <th style="font-weight: bold">Jalur Masuk</th>
            <th style="font-weight: bold">Status Kelulusan</th>
            <th style="font-weight: bold">Nilai TKA</th>
            <th style="font-weight: bold">Nilai TKJ</th>
            <th style="font-weight: bold">Tanggal Bayar</th>
            <th style="font-weight: bold">Tanggal Ujian TKA</th>
            <th style="font-weight: bold">Tanggal Ujian TKJ</th>
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
                <td>{{ $value['user_cln_mhs']['hp'] }}</td>
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
                    @elseif ($value['is_lulus_tka'] === 0)
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
                <td>
                    {{ $value['nilai_tka'] }}
                </td>
                <td>
                    {{ $value['nilai_tkj'] }}
                </td>
                <td>
                    {{ $value['lunas_at'] }}
                </td>
                <td>
                    {{ $value['start_tka'] }}
                </td>
                <td>
                    {{ $value['start_tkj'] }}
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
