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
                :items="periode"
                item-text="nama"
                item-value="id"
                filled
                label="Periode"
                v-model="periode_id"
                @change="getData()"
              ></v-select>
            </v-col>
            <v-col cols="6">
              <v-select
                @change="getData()"
                v-model="jurusan_id"
                :items="jurusan"
                item-text="nama"
                item-value="id"
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
          Mohon segera lakukan verifikasi transkip nilai peserta <br>
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
              <h4 class="mt-2">Belum ada data...</h4>
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
              <h4 class="mt-2">Belum ada data...</h4>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-row>
    <v-dialog
      v-if="form"
      :width="width()"
      v-model="dialogVerifikasi"
      scrollable
    >
      <v-card>
        <v-card-title>
          Verifikasi Jalur Cumlaude
        </v-card-title>
        <v-card-subtitle>disini
          Silahkan lakukan pemeriksaan nilai pada transkip peserta. <br>
          Klik <a
            :href="form.link_transkip"
            target="_blank"
          >disini</a> untuk lihat transkip di tab baru
          <!-- atau download disini -->
        </v-card-subtitle>
        <v-card-text>
          <pdf :src="form.link_transkip">

          </pdf>
        </v-card-text>
        <v-card-actions>
          <v-btn
            color="green"
            class="text-white"
            @click="setDialogKonfirmasi(true)"
          >
            <v-icon>mdi-check</v-icon> Lulus
          </v-btn>
          <v-btn
            @click="setDialogKonfirmasi(false)"
            text
          >
            <v-icon>mdi-close</v-icon> tidak lulus
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-if="form"
      width="400"
      v-model="dialogKonfirmasi"
    >
      <v-card>
        <v-card-title>
          Konfirmasi
        </v-card-title>
        <v-card-text>
          Apakah anda yakin <strong>{{form.nama_pendaftar}}</strong> akan
          <strong v-if="status">diluluskan</strong>
          <strong v-else>tidak diluluskan</strong> ?
        </v-card-text>
        <v-card-actions>
          <v-btn
            :loading="loading"
            color="green"
            class="text-white"
            @click="sendStatus(status,form.id)"
          >
            Iya
          </v-btn>
          <v-btn
            @click="dialogKonfirmasi = false"
            text
          >
            batal
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import pdf from "vue-pdf";
import "pdfjs-dist/build/pdf.worker.entry";
export default {
  components: {
    pdf,
  },
  methods: {
    openVerificationDialog(item) {
      this.dialogVerifikasi = true;
      this.form = _.clone(item);
    },
    setDialogKonfirmasi(status) {
      this.dialogKonfirmasi = true;
      this.status = status;
    },
    sendStatus(is_lulus, id) {
      this.loading = true;
      axios
        .post("/api/cumlaude/update", { is_lulus, id })
        .then((response) => {
          console.log(response.data);
          this.setDataStatus(id, is_lulus);
          this.loading = false;
          this.dialogVerifikasi = false;
          this.dialogKonfirmasi = false;
        })
        .catch();
    },
    initData() {
      // get the data that this page needed: periodes,jurusans
      axios
        .get("/api/cumlaude/init-data")
        .then((response) => {
          this.periode = response.data.periode;
          this.jurusan = this.jurusan.concat(response.data.jurusan);
        })
        .catch((error) => {});
    },
    getData() {
      if (this.periode_id) {
        var url = "/api/cumlaude/" + this.periode_id + "/" + this.jurusan_id;
        axios
          .get(url)
          .then((response) => {
            this.cumlaudes = response.data;
          })
          .catch();
      }
    },
    setDataStatus(id, status) {
      // set the data after being verified to passed or fail

      this.cumlaudes.every((item) => {
        console.log(item);
        if (item.id == id) {
          item.status_code = status ? 1 : 0;
          item.status_message = status
            ? "Lulus Verifikasi"
            : "Tidak Lulus Verifikasi";
          return false;
        }
        return true;
      });
    },
    width() {
      if (this.windowWidth <= 600) {
        return "100%";
      } else if (this.windowWidth <= 960) {
        return "70%";
      } else {
        return "60%";
      }
    },
  },
  created() {
    this.initData();
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
        return item.status_code == 2;
      });
    },
    sudahVerifikasi() {
      if (!this.cumlaudes.length > 0) {
        return [];
      }
      return this.cumlaudes.filter((item) => {
        return item.status_code != 2;
      });
    },
  },
  data() {
    return {
      status: null,
      loading: false,
      linkPDF: "/",
      namaPeserta: "",
      form: null,
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
      originalCumlaudes: [],
      periode: [],
      jurusan: [{ nama: "Semua Jurusan", id: "all" }],
      jurusan_id: "all",
      periode_id: null,
      ujian_id: null,
      dialogVerifikasi: false,
      dialogKonfirmasi: false,
    };
  },
};
</script>

<style>
</style>