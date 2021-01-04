<template>
  <v-sheet
    class="mx-auto"
    :width="width()"
  >
    <v-container>
      <h3>Panduan Pendaftaran</h3>
      <v-timeline>
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
          class="text-right"
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
          class="text-right"
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
          class="text-right"
          fill-dot
        >
          Apabila mahasiswa lulus ujian TKA dan TKJ, calon mahasiswa dapat menentukan jadwal temu ramah
        </v-timeline-item>
      </v-timeline>
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