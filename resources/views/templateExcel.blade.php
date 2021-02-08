<table>
    <thead>
        <tr>
            <th style="font-weight: bold" >Tipe Soal</th>
            <th style="font-weight: bold">Jurusan</th>
            <th style="font-weight: bold">Kategori</th>
            <th style="font-weight: bold">Pertanyaan</th>
            <th style="font-weight: bold">Pilihan A</th>
            <th style="font-weight: bold">Pilihan B</th>
            <th style="font-weight: bold">Pilihan C</th>
            <th style="font-weight: bold">Pilihan D</th>
            <th style="font-weight: bold">Pilihan E</th>
            <th style="font-weight: bold">Jawaban</th>
            <th></th>
            <th style="font-weight: bold; background:yellow; border: 1px solid black;">Gunakan daftar berikut untuk mengisi nama jurusan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jurusan as $item)
             <tr>
                @for ($i = 0; $i < 11; $i++)
                    <td></td>
                @endfor
                <td style="border: 1px solid black;">
                    {{$item['nama']}}
                </td>
            </tr>
        @endforeach
        <tr></tr>
        <tr>    
            @for ($i = 0; $i < 11; $i++)
                <td></td>
            @endfor
            <td style="font-weight: bold; background:yellow; border: 1px solid black;">Tipe Soal</td>
        </tr>
        <tr>    
            @for ($i = 0; $i < 11; $i++)
                <td></td>
            @endfor
            <td style="border: 1px solid black;">TKA</td>
        </tr>
        <tr>    
            @for ($i = 0; $i < 11; $i++)
                <td></td>
            @endfor
            <td style="border: 1px solid black;">TKJ</td>
        </tr>
        @foreach ($jurusan as $item)
        <tr></tr>
            <tr>
                @for ($i = 0; $i < 11; $i++)
                    <td></td>
                @endfor
                <td style="font-weight: bold; background:yellow; border: 1px solid black;">Kategori pada jurusan {{$item['nama']}}:</td>
            </tr>
            @foreach ($item['kategori'] as $kategori)
                <tr>
                    @for ($i = 0; $i < 11; $i++)
                        <td></td>
                    @endfor
                    <td style="border: 1px solid black;">
                        {{$kategori['nama']}}
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>