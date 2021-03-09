<template>
  <v-container>
    <p>Kelola Pendaftaran Jalur Cumlaude</p>
    <v-row>
      <v-card
        width="100%"
        outlined
      >
        <v-card-title>
          Pilih Periode dan Jurusan
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col cols="6">
              <v-select
                :items="items"
                filled
                label="Periode"
              ></v-select>
            </v-col>
            <v-col cols="6">
              <v-select
                :items="items"
                filled
                label="Jurusan"
              ></v-select>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-row>
    <v-row class="mt-5">
      <v-card width="100%">
        <v-card-title>
          Belum Verifikasi
        </v-card-title>
        <v-card-subtitle>
          Mohon segera lakukan verifikasi transkip nilai peserta
        </v-card-subtitle>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="belumVerifikasi"
            :items-per-page="5"
            class="elevation-1"
          >
            <template v-slot:item.actions="{ item }">
              <v-btn
                block
                color="green"
                class="text-white"
                @click="openVerificationDialog(item)"
              >Verifikasi</v-btn>
            </template>
            <template v-slot:no-data>
              <h4>Belum ada data...</h4>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-row>
    <v-row class="mt-5">
      <v-card width="100%">
        <v-card-title>
          Sudah Verifikasi
        </v-card-title>
        <v-card-subtitle>
          Berikut daftar peserta yang sudah diverifikasi

        </v-card-subtitle>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="sudahVerifikasi"
            :items-per-page="5"
            class="elevation-1"
          >
            <template v-slot:item.actions="{ item }">
              <v-icon
                small
                class="mr-2"
                @click="editItem(item)"
              >
                mdi-pencil
              </v-icon>
              <v-icon small>
                <!-- @click="deleteItem(item)" -->
                mdi-delete
              </v-icon>
            </template>
            <template v-slot:no-data>
              <h4>Belum ada data...</h4>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-row>
    <v-dialog v-model="dialogVerifikasi">

    </v-dialog>
  </v-container>
</template>

<script>
export default {
  methods: {
    openVerificationDialog(item) {
      this.dialogVerifikasi = true;
    },
  },
  created() {
    axios
      .get("/api/cumlaude")
      .then((response) => {
        this.cumlaudes = response.data;
      })
      .catch();
  },
  computed: {
    belumVerifikasi() {
      if (!this.cumlaudes.length > 0) {
        return [];
      }
      return this.cumlaudes.filter((item) => {
        return item.status_code == 3;
      });
    },
    sudahVerifikasi() {
      if (!this.cumlaudes.length > 0) {
        return [];
      }
      return this.cumlaudes.filter((item) => {
        return item.status_code != 3;
      });
    },
  },
  data() {
    return {
      items: [],
      headers: [
        {
          text: "Nama",
          align: "start",
          sortable: false,
          value: "nama_pendaftar",
        },
        { text: "Jurusan", value: "nama_jurusan" },
        { text: "Status", value: "status_message" },
        { text: "Aksi", value: "actions", sortable: false },
      ],
      dessert: {},
      cumlaudes: [],
      periode: {},
      jurusan: {},
      jurusan_id: null,
      periode_id: null,
      ujian_id: null,
      dialogVerifikasi: false,
    };
  },
};
</script>

<style>
</style>