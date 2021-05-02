<template>
  <v-container v-if="periode">
    <v-row justify="space-between">
      <v-col cols="12">
        <v-card
          dark
          :width="'100%'"
        >
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            cover
          >
            <v-card-title>
              <span class="body 2">Periode Terbaru</span>
            </v-card-title>
            <v-card-text>
              <h1>{{periode.nama}}</h1>
              <p>Awal Periode: {{parseDate(periode.awal_periode)}} <br>
                Akhir Periode: {{parseDate(periode.akhir_periode)}}
              </p>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
      <v-col>
        <v-card
          dark
          :height="100*1.2"
          :width="'100%'"
        >
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/100?random=3'"
          >
            <v-card-title>
              <span class="body 2">Total Pendaftaran</span>
            </v-card-title>
            <v-card-text>
              <h1>{{total_pendaftaran}}</h1>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
      <v-col>
        <v-card
          dark
          :height="100*1.2"
          :width="'100%'"
        >
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/100?random=2'"
          >
            <v-card-title>
              <span class="body 2">Total Lulus</span>
            </v-card-title>
            <v-card-text>
              <h1>{{total_lulus}}</h1>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
      <v-col>
        <v-card
          dark
          :height="100*1.2"
          :width="'100%'"
        >
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/100?random=1'"
          >
            <v-card-title>
              <span class="body 2">Total Gagal</span>
            </v-card-title>
            <v-card-text>
              <h1>{{total_gagal}}</h1>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
    </v-row>
    <!-- Tabel -->
    <v-card class="mt-10">
      <v-data-table
        :headers="headers"
        :items="final_data"
        :items-per-page="10"
        :loading="isLoading"
        class="elevation-1"
      >
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script>
import { mapActions, mapState, mapMutations } from "vuex";
export default {
  created() {
    this.getData();
  },
  computed: {},
  methods: {
    parseDate(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },
    getData() {
      axios
        .get("/api/ujian/dashboard")
        .then((response) => {
          this.periode = response.data.periode;
          this.total_pendaftaran = response.data.total_pendaftaran;
          this.total_lulus = response.data.total_lulus;
          this.total_gagal = response.data.total_gagal;
          this.final_data = response.data.final_data;
        })
        .catch();
    },
  },
  data() {
    return {
      periode: null,
      total_pendaftaran: null,
      total_lulus: null,
      total_gagal: null,
      isLoading: false,
      headers: [
        {
          text: "Nama Jurusan",
          align: "start",
          value: "nama_jurusan",
        },
        {
          text: "Jumlah Pendaftar",
          align: "center",
          value: "jumlah_pendaftar",
        },
        {
          text: "Lulus",
          align: "center",
          value: "jumlah_lulus",
        },
        {
          text: "Gagal",
          align: "center",
          value: "jumlah_gagal",
        },
        {
          text: "Belum Menyelesaikan Ujian",
          align: "center",
          value: "jumlah_belum_ujian",
        },
        {
          text: "Jumlah Kelas Terisi",
          align: "center",
          value: "jumlah_kelas_terisi",
        },
      ],
    };
  },
};
</script>

<style>
</style>
