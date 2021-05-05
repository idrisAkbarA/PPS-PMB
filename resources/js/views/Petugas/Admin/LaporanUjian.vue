<template>
  <v-container>
    <v-card>
      <v-card-title>
        <v-text-field
          prepend-inner-icon="mdi-magnify"
          clearable
          label="Pencarian"
          v-model="pencarian"
          @keyup="findName()"
          color="#2C3E50"
        ></v-text-field>
      </v-card-title>
      <v-expansion-panels focusable>
        <v-expansion-panel
          v-for="(data,index) in periode"
          :key="index"
        >
          <v-expansion-panel-header>
            <v-row align="center">
              {{data.nama}}
              <v-spacer></v-spacer>
              <v-chip
                color="primary"
                v-if="data.record_found"
              > {{data.record_found}} Data ditemukan</v-chip>

            </v-row>
          </v-expansion-panel-header>
          <v-expansion-panel-content class="mt-2">
            <v-data-table
              :headers="headers"
              :items="data.ujian"
              :items-per-page="10"
              :loading="isLoading"
              :search="search"
              class="elevation-1"
            >
              <template v-slot:item.start_tka="{ item }">
                {{ item.start_tka ? parseDate(item.start_tka):"" }}
              </template>
              <template v-slot:item.start_tkj="{ item }">
                {{ item.start_tkj? parseDate(item.start_tkj):"" }}
              </template>
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
    parseDate(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },
    findName() {
      if (this.pencarian) {
        var params = { name: this.pencarian };
        axios
          .get("/api/ujian/laporan", {
            params,
          })
          .then((response) => {
            this.periode = response.data;
            console.log(this.periode);
          })
          .catch();
      } else {
        this.getData();
      }
    },
    getData() {
      axios
        .get("/api/ujian/laporan")
        .then((response) => {
          this.periode = response.data;
          console.log(this.periode);
        })
        .catch();
    },
  },
  data() {
    return {
      pencarian: null,
      periode: null,
      search: "",
      isLoading: false,
      headers: [
        {
          text: "Nama",
          value: "nama_pendaftar",
        },
        {
          text: "Nilai TKA",
          value: "nilai_tka",
        },
        {
          text: "Nilai TKJ",
          value: "nilai_tkj",
        },
        {
          text: "Tanggal Ujian TKA",
          value: "start_tka",
        },
        {
          text: "Tanggal Ujian TKJ",
          value: "start_tkj",
        },
        {
          text: "Status Kelulusan",
          value: "status_kelulusan",
        },
      ],
      items: [
        {
          nama_pendaftar: "Naradi Adikara Januar",
          nilai_tka: 80,
          nilai_tkj: 90,
          status_lulus: "Lulus",
        },
        {
          nama_pendaftar: "Baktianto Hakim",
          nilai_tka: 50,
          nilai_tkj: 10,
          status_lulus: "Gagal",
        },
        {
          nama_pendaftar: "Lalita Yulianti",
          nilai_tka: 60,
          nilai_tkj: 40,
          status_lulus: "Gagal",
        },
        {
          nama_pendaftar: "Unjani Salwa Hartati",
          nilai_tka: 70,
          nilai_tkj: 60,
          status_lulus: "Gagal",
        },
        {
          nama_pendaftar: "Ivan Gatot Gunawan S.IP",
          nilai_tka: 80,
          nilai_tkj: 90,
          status_lulus: "Lulus",
        },
        {
          nama_pendaftar: "Salwa Wirda Astuti S.H.",
          nilai_tka: 85,
          nilai_tkj: 90,
          status_lulus: "Lulus",
        },
        {
          nama_pendaftar: "Nardi Winarno",
          nilai_tka: 70,
          nilai_tkj: 40,
          status_lulus: "Gagal",
        },
      ],
    };
  },
};
</script>

<style>
</style>
