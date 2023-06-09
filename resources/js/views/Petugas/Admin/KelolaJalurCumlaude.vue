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
                @click="openVerificationDialog(item)"
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
            :href="'/'+form.link_transkip"
            target="_blank"
          >disini</a> untuk lihat transkip di tab baru
          <!-- atau download disini -->
        </v-card-subtitle>
        <v-card-text>
          <pdf :src="'/'+form.link_transkip">

          </pdf>
          <v-card
            outlined
            v-if="mhsSelected"
          >
            <v-card-title>
              Rincian
            </v-card-title>
            <v-card-text>
              <v-container>
                <v-row>
                  <v-text-field
                    color="green"
                    filled
                    readonly
                    prepend-inner-icon="mdi-account"
                    label="Kode Bayar"
                    v-model="mhsSelected.kode_bayar"
                  ></v-text-field>
                </v-row>
                <v-row>
                  <v-text-field
                    color="green"
                    filled
                    readonly
                    prepend-inner-icon="mdi-account"
                    label="Nama Lengkap"
                    v-model="mhsSelected.nama"
                  ></v-text-field>
                </v-row>
                <v-row>
                  <v-select
                    color="green"
                    filled
                    readonly
                    prepend-inner-icon="mdi-account"
                    label="Jenis Kelamin"
                    :items="jk"
                    v-model="mhsSelected.jenis_kelamin"
                  ></v-select>
                </v-row>
                <v-row>
                  <v-text-field
                    color="green"
                    filled
                    readonly
                    prepend-inner-icon="mdi-city"
                    label="Tempat Lahir"
                    type="text"
                    v-model="mhsSelected.tempat_lahir"
                  ></v-text-field>
                </v-row>
                <v-row>

                  <v-text-field
                    color="green"
                    filled
                    v-model="mhsSelected.tgl_lahir"
                    label="Tanggal Lahir"
                    prepend-inner-icon="mdi-calendar"
                    readonly
                  ></v-text-field>

                </v-row>
                <v-row>
                  <v-text-field
                    color="green"
                    filled
                    readonly
                    prepend-inner-icon="mdi-card-bulleted"
                    label="NIK"
                    type="number"
                    v-model="mhsSelected.nik"
                  ></v-text-field>
                </v-row>
                <v-row>
                  <v-text-field
                    color="green"
                    filled
                    readonly
                    prepend-inner-icon="mdi-phone"
                    label="No Telepon"
                    type="number"
                    v-model="mhsSelected.hp"
                  ></v-text-field>
                </v-row>
                <v-row>
                  <v-text-field
                    color="green"
                    filled
                    readonly
                    prepend-inner-icon="mdi-whatsapp"
                    label="No Whatsapp"
                    type="number"
                    v-model="mhsSelected.wa"
                  ></v-text-field>
                </v-row>
                <v-row>
                  <v-textarea
                    rows="1"
                    auto-grow
                    color="green"
                    filled
                    readonly
                    prepend-inner-icon="mdi-map-marker"
                    label="Alamat Rumah Lengkap"
                    v-model="mhsSelected.alamat"
                  ></v-textarea>
                </v-row>
                <v-row v-if="selectedPeriode.syarat_bhs_inggris">
                  <v-text-field
                    color="green"
                    filled
                    prepend-inner-icon="mdi-attachment"
                    type="number"
                    label="Nilai Bahasa Inggris"
                    v-model="mhsSelected.nilai_bhs_inggris"
                  ></v-text-field>
                </v-row>

                <v-row v-if="selectedPeriode.syarat_bhs_arab">
                  <v-text-field
                    color="green"
                    type="number"
                    filled
                    prepend-inner-icon="mdi-attachment"
                    label="Nilai Bahasa Arab"
                    v-model="mhsSelected.nilai_bhs_arab"
                  ></v-text-field>
                </v-row>

                <v-row>
                  <v-text-field
                    required
                    color="green"
                    filled
                    readonly
                    prepend-inner-icon="mdi-attachment"
                    label="Nilai IPK"
                    type="number"
                    v-model="mhsSelected.nilai_ipk"
                  ></v-text-field>
                  <!-- @change="sendUser(mhsSelected,6)" -->
                </v-row>
                <v-row v-if="selectedPeriode.syarat_bhs_arab">
                  <v-btn
                    block
                    x-large
                    dark
                    @click="link(mhsSelected.sertifikat_bhs_arab)"
                    color="green darken-2"
                  > Sertifikat Bahasa arab
                  </v-btn>
                </v-row>
                <v-row v-if="selectedPeriode.syarat_bhs_inggris">
                  <v-btn
                    block
                    x-large
                    dark
                    @click="link(mhsSelected.sertifikat_bhs_inggris)"
                    color="green darken-2"
                  >Sertifikat Bahasa Inggris
                  </v-btn>
                </v-row>
                <v-row>
                  <v-btn
                    block
                    x-large
                    dark
                    @click="link(mhsSelected.ijazah)"
                    color="green darken-2"
                  > File Ijazah
                  </v-btn>
                </v-row>
                <v-row>
                  <v-btn
                    block
                    x-large
                    dark
                    @click="link(mhsSelected.transkip)"
                    color="green darken-2"
                  > File Transkip
                  </v-btn>
                </v-row>
                <v-row>
                  <v-btn
                    block
                    x-large
                    dark
                    @click="link(mhsSelected.surat_rekomendasi)"
                    color="green darken-2"
                  > File surat rekomendasi
                  </v-btn>
                </v-row>
                <v-row>
                  <v-btn
                    block
                    x-large
                    dark
                    @click="link(mhsSelected.pas_photo)"
                    color="green darken-2"
                  >Pas Photo
                  </v-btn>
                </v-row>
                <v-row>
                  <v-btn
                    block
                    x-large
                    dark
                    @click="link(mhsSelected.ktp)"
                    color="green darken-2"
                  >File KTP
                  </v-btn>
                </v-row>
              </v-container>
            </v-card-text>
          </v-card>
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
      this.mhsSelected = item.user_cln_mhs;
      this.mhsSelected["ujianID"] = item.id;
      this.mhsSelected["kode_bayar"] = item.kode_bayar;
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
    link(url) {
      var a = "/" + url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
    },
  },
  created() {
    this.initData();
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
  watch: {
    periode_id(val){
        if (val){
            this.selectedPeriode = this.periode.find(f => f.id == val);
        }
    }
  },
  data() {
    return {
      jk: ["Laki-laki", "Perempuan"],
      mhsSelected: null,
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
      selectedPeriode: null,
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
