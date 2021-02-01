<template>
  <v-sheet
    class="mx-auto"
    :width="width()"
  >
    <v-container>
      <h3>Panduan Pendaftaran</h3>
      <v-timeline dense clipped>
        <v-timeline-item
          color="#d35400"
          icon="mdi-login-variant"
          fill-dot
        >
          Calon mahasiswa melakukan pendaftaran akun dan login di "www.pps-pmb.uin-suska.ac.id"
        </v-timeline-item>
        <v-timeline-item
          color="#f1c40f"
          icon="mdi-file-document-outline"
          fill-dot
        >
          Calon mahasiswa membuat pendaftaran baru dengan memilih jurusan dan mengisi biodata diri
        </v-timeline-item>
        <v-timeline-item
          color="#d35400"
          icon="mdi-bank"
          fill-dot
        >
          Calon mahasiswa melakukan pembayaran uang pendaftaran di kantor Bank BRI Syariah
        </v-timeline-item>
        <v-timeline-item
          color="#2980b9"
          icon="mdi-file-document-edit-outline"
          fill-dot
        >
          Calon mahasiswa dapat menyelesaikan ujian CAT dan TKJ secara online dimanapun dan kapanpun dengan batas waktu 10 hari setelah melakukan pembayaran
        </v-timeline-item>
        <v-timeline-item
          color="#f1c40f "
          icon="mdi-bullhorn"
          fill-dot
        >
          Pengumuman Kelulusan
        </v-timeline-item>
        <v-timeline-item
          color="#3498db"
          icon="mdi-account-group"
          fill-dot
        >
          Apabila mahasiswa lulus ujian TKA dan TKJ, calon mahasiswa dapat menentukan jadwal temu ramah
        </v-timeline-item>
      </v-timeline>
      <h3>Syarat dan Ketentuan</h3>
      <div class="heading 5">
        <tr>
          <td>1. </td>
          <td> Calon mahasiswa wajib mengisi seluruh informasi pendaftaran dengan benar dan valid.</td>
        </tr>
        <tr>
          <td>2. </td>
          <td> Calon mahasiswa wajib melengkapi dokumen-dokumen yang diperlukan untuk
            pendaftaran dengan benar dan valid.</td>
        </tr>
        <tr>
          <td>3. </td>
          <td> Dengan menerima Syarat dan Ketentuan serta berpartisipasi dalam program ini,
            calon mahasiswa menyatakan informasi beserta dokumen yang diberikan dapat dipertanggungjawabkan.
            Calon mahasiswa menanggung semua risiko rusak, hilang, terlambat, tidak lengkap, tidak sah,
            benar atau salah dalam melakukan seluruh proses pendaftaran.</td>
        </tr>
        <tr>
          <td>4. </td>
          <td> Pascasarjana UIN SUSKA RIAU berhak untuk mendiskualifikasi peserta yang dianggap melanggar aturan program atau ketentuan hukum negara Republik Indonesia.</td>
        </tr>
        <tr>
          <td>5. </td>
          <td> Keputusan dengan semua hal yang berkaitan dengan kelulusan mahasiswa bersifat final, mengikat dan tidak dapat diganggu gugat.
</td>
        </tr>
      </div>
    </v-container>
  </v-sheet>
</template>

<script>
export default {
  methods: {
    width() {
      if (this.windowWidth <= 600) {
        return "100%";
      } else if (this.windowWidth <= 960) {
        return "70%";
      } else {
        return "50%";
      }
    }
  }
};
</script>