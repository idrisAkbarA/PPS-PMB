<template>
  <v-sheet
    class="mx-auto"
    :width="width()"
  >
    <v-container v-if="currentPeriode">
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
          Calon mahasiswa melakukan pembayaran uang pendaftaran di kantor Bank BSI
        </v-timeline-item>
        <v-timeline-item
          color="#2980b9"
          icon="mdi-file-document-edit-outline"
          fill-dot
        >
          Calon mahasiswa dapat menyelesaikan ujian CAT dan TKJ secara online dimanapun dan kapanpun dengan batas waktu {{currentPeriode.range_ujian}} hari setelah melakukan pembayaran
        </v-timeline-item>
        <v-timeline-item
          color="#3498db"
          icon="mdi-account-group"
          fill-dot
        >
          Apabila mahasiswa dinyatakan lulus, calon mahasiswa dapat melihat jadwal wawancara dan informasi selanjutnya pada web Pascasarjana pada link berikut  <a href="https://pasca.uin-suska.ac.id/" target="_blank">https://pasca.uin-suska.ac.id/</a>
        </v-timeline-item>
      </v-timeline>
      <div class="my-2">
          <h3>Berkas dan Kelengkapan</h3>
          <div class="heading 5">
            <tr>
              <td>1. </td>
              <td> Pas Photo, KTP dan Kartu Keluarga.</td>
            </tr>
            <tr>
              <td>2. </td>
              <td> Ijazah Terakhir (dapat menggunakan SKL bila Ijazah belum keluar).</td>
            </tr>
            <tr>
              <td>3. </td>
              <td> Transkip nilai (Akhir/Sementara).</td>
            </tr>
            <tr>
              <td>4. </td>
              <td> Surat rekomendasi. Format surat rekomendasi dapat didownload <a href="#" class="font-weight-black">DISINI</a></td>
            </tr>
            <tr v-if="currentPeriode.syarat_bhs_inggris || currentPeriode.syarat_bhs_arab">
              <td>5. </td>
              <td> Sertifikat TOEFL dan TOAFL.
                </td>
            </tr>
          </div>
      </div>
      <div class="my-2">
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
      </div>
    </v-container>
  </v-sheet>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  computed: {
    ...mapState(["currentPeriode"])
  },
  created() {
    this.getCurrentPeriode();
  },
  methods: {
    ...mapActions(["getCurrentPeriode"]),
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
