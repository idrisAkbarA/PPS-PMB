<template>
  <v-container>
    <v-card>
      <v-card-title>
        <v-text-field
          prepend-inner-icon="mdi-magnify"
          clearable
          label="Pencarian"
          color="#2C3E50"
        ></v-text-field>
      </v-card-title>
      <v-expansion-panels focusable>
        <v-expansion-panel
          v-for="(data,index) in periode"
          :key="index"
        >
          <v-expansion-panel-header>{{data.nama}}</v-expansion-panel-header>
          <v-expansion-panel-content class="mt-2">
            <v-data-table
              :headers="headers"
              :items="data.ujian"
              :items-per-page="10"
              :loading="isLoading"
              :search="search"
              class="elevation-1"
            >

            </v-data-table>
          </v-expansion-panel-content>
        </v-expansion-panel>
        <!-- <v-expansion-panel>
          <v-expansion-panel-header>Periode 2021/2022</v-expansion-panel-header>
          <v-expansion-panel-content class="mt-2">
            <v-data-table
              :headers="headers"
              :items="items"
              :items-per-page="10"
              :loading="isLoading"
              :search="search"
              class="elevation-1"
            >

            </v-data-table>
          </v-expansion-panel-content>
        </v-expansion-panel> -->
      </v-expansion-panels>
    </v-card>
  </v-container>
</template>

<script>
export default {
  created() {
    this.getData();
  },
  methods: {
    getData() {
      axios
        .get("/api/ujian/laporan")
        .then(response => {
          this.periode = response.data;
          console.log(this.periode);
        })
        .catch();
    }
  },
  data() {
    return {
      periode: null,
      search: "",
      isLoading: false,
      headers: [
        {
          text: "Nama",
          value: "nama_pendaftar"
        },
        {
          text: "Nilai TKA",
          value: "nilai_tka"
        },
        {
          text: "Nilai TKJ",
          value: "nilai_tkj"
        },
        {
          text: "Status Kelulusan",
          value: "status_kelulusan"
        }
      ],
      items: [
        {
          nama_pendaftar: "Naradi Adikara Januar",
          nilai_tka: 80,
          nilai_tkj: 90,
          status_lulus: "Lulus"
        },
        {
          nama_pendaftar: "Baktianto Hakim",
          nilai_tka: 50,
          nilai_tkj: 10,
          status_lulus: "Gagal"
        },
        {
          nama_pendaftar: "Lalita Yulianti",
          nilai_tka: 60,
          nilai_tkj: 40,
          status_lulus: "Gagal"
        },
        {
          nama_pendaftar: "Unjani Salwa Hartati",
          nilai_tka: 70,
          nilai_tkj: 60,
          status_lulus: "Gagal"
        },
        {
          nama_pendaftar: "Ivan Gatot Gunawan S.IP",
          nilai_tka: 80,
          nilai_tkj: 90,
          status_lulus: "Lulus"
        },
        {
          nama_pendaftar: "Salwa Wirda Astuti S.H.",
          nilai_tka: 85,
          nilai_tkj: 90,
          status_lulus: "Lulus"
        },
        {
          nama_pendaftar: "Nardi Winarno",
          nilai_tka: 70,
          nilai_tkj: 40,
          status_lulus: "Gagal"
        }
      ]
    };
  }
};
</script>

<style>
</style>
