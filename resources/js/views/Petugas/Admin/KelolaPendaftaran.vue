<template>
  <v-container>
    <p class="text-muted">
      Mengolola pendaftaran pada Penerimaan Mahasiswa Baru
    </p>
    <v-card class="mb-3">
      <v-expansion-panels>
        <v-expansion-panel>
          <v-expansion-panel-header>
            <!-- <i class="mdi mdi-filter"></i> -->
            Filter
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <v-row>
              <v-col cols="6">
                <v-select
                  :items="periode"
                  label="Periode"
                  item-text="nama"
                  item-value="id"
                  v-model="filter.periode"
                  @change="filterPendaftaran"
                ></v-select>
              </v-col>
              <v-col cols="6">
                <v-select
                  clearable
                  :items="jurusan"
                  label="Jurusan"
                  item-text="nama"
                  item-value="id"
                  v-model="filter.jurusan"
                  @change="filterPendaftaran"
                ></v-select>
              </v-col>
              <v-col cols="6">
                <v-select
                  clearable
                  :items="['Lunas', 'Belum Lunas']"
                  label="Pembayaran"
                  @change="filterPendaftaran"
                  v-model="filter.pembayaran"
                ></v-select>
              </v-col>
              <v-col cols="6">
                <v-select
                  clearable
                  :items="['Lulus', 'Tidak Lulus', 'Belum Ujian']"
                  label="Status"
                  v-model="filter.status"
                  @change="filterPendaftaran"
                ></v-select>
              </v-col>
            </v-row>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-card>
    <v-card>
      <v-card-title>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
        <v-btn
          :block="windowWidth<600?true:false"
          color="green"
          class="text-white ml-2 mr-2"
          :loading="downloadLoading"
          @click="downloadExcel()"
        >Download Excel</v-btn>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="pendaftaran"
        :items-per-page="10"
        :search="search"
        :loading="isLoading"
        class="elevation-1"
      >
        <template v-slot:[`item.nama`]="{ item }">
          {{ item.user_cln_mhs.nama }}
        </template>
        <template v-slot:[`item.jurusan`]="{ item }">
          {{ item.jurusan.nama }}
        </template>
        <template v-slot:[`item.pembayaran`]="{ item }">
          <v-chip
            outlined
            class="ma-2"
            :color="item.lunas_at ? 'success' : 'red'"
          >
            {{ item.lunas_at ? "Lunas" : "Belum Lunas" }}
          </v-chip>
        </template>
        <template v-slot:[`item.kelulusan`]="{ item }">
          <v-chip
            outlined
            class="ma-2"
            :color="
              item.status_kelulusan == 'Lulus'
                ? 'success'
                : item.status_kelulusan == 'Tidak Lulus'
                ? 'red'
                : 'warning'
            "
          >
            {{ item.status_kelulusan }}
          </v-chip>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-btn
            icon
            x-small
            class="mr-2"
            title="Detail"
            @click="show(item)"
          >
            <v-icon>mdi-information</v-icon>
          </v-btn>
          <!-- <v-btn
            icon
            x-small
            class="mr-2"
            title="Edit"
            @click="edit(item)"
          >
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <v-btn
            icon
            x-small
            class="mr-2"
            title="Hapus"
            @click="
              dialogDelete = true;
              form = item;
            "
          >
            <v-icon>mdi-delete</v-icon>
          </v-btn> -->
        </template>
      </v-data-table>
    </v-card>
    <p class="mt-5">
      Daftar Kelas
    </p>
    <v-expansion-panels>
      <v-expansion-panel
        v-for="(kelas,i) in kelases"
        :key="i"
      >
        <v-expansion-panel-header>
          {{kelas.jurusan}}
        </v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-row>
            <v-col v-if="kelas.kelas.length<1">
              <p class="mt-5 text-center">
                Belum ada kelas
              </p>
            </v-col>
            <v-col
              cols="4"
              v-for="(k,key,index) in kelas.kelas"
              :key="index"
            >
              <v-card
                outlined
                color="green lighten-5"
              >
                <v-card-title>Kelas {{String.fromCharCode(65+index)}}</v-card-title>
                <v-card-text>
                  <v-list-item
                    v-for="(mhs,i2) in k.cln_mhs"
                    :key="i2"
                  >
                    <v-list-item-content>
                      <v-list-item-title>{{mhs.nama}}</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
    <!-- Bottom Sheet -->
    <v-bottom-sheet
      scrollable
      inset
      width="60%"
      overlay-color="#69F0AE"
      v-model="bottomSheet"
    >
      <v-card color="#ecf0f1">
        <v-card-title>
          <span>Jurusan</span>
          <v-spacer></v-spacer>
          <v-btn
            text
            class="mr-2"
            @click="bottomSheet = false"
          >batal</v-btn>
          <v-btn
            color="#2C3E50"
            dark
            @click="submit"
          >Simpan</v-btn>
        </v-card-title>
        <v-card-text>
          <vue-scroll :ops="scrollOps">
            <!-- <v-card> -->
            <v-card-text>
              <v-row align="center">
                <v-col cols="12">
                  <v-text-field
                    clearable
                    color="#2C3E50"
                    label="Nama Jurusan"
                    hint="*Contoh : Pendidikan Agama Islam S3"
                    v-model="form.nama"
                  >
                  </v-text-field>
                </v-col>
                <v-col
                  cols="6"
                  v-if="form.id"
                >
                  <v-select
                    :items="form.kategori"
                    label="Kategori TKA Default"
                    item-text="nama"
                    item-value="id"
                    v-model="form.kat_tka_default"
                  ></v-select>
                </v-col>
                <v-col
                  cols="6"
                  v-if="form.id"
                >
                  <v-select
                    :items="form.kategori"
                    label="Kategori TKJ Default"
                    item-text="nama"
                    item-value="id"
                    v-model="form.kat_tkj_default"
                  ></v-select>
                </v-col>
              </v-row>
            </v-card-text>
            <!-- </v-card> -->
          </vue-scroll>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <!-- Dialog Delete -->
    <v-dialog
      v-model="dialogDelete"
      width="500"
    >
      <v-card>
        <v-card-title class="headline">
          <v-icon>mdi-trash</v-icon>
        </v-card-title>

        <v-card-text>
          <p class="text-center">
            Apakah anda yakin ingin menghapus jurusan ini ?
          </p>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-btn
            text
            @click="dialogDelete = false"
          > Batal </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="#2C3E50"
            dark
            @click="destroy"
          > Ya </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar
      v-model="snackbar.show"
      timeout="2000"
      :color="snackbar.color ? snackbar.color : 'success'"
      outlined
    >
      {{ snackbar.message }}

      <template v-slot:action="{ attrs }">
        <v-btn
          :color="snackbar.color ? snackbar.color : 'success'"
          text
          v-bind="attrs"
          @click="snackbar.show = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
    <v-dialog
      width="400"
      v-model="dialogResetUjian"
      v-if="mhsSelected"
    >
      <v-card>
        <v-card-title>Konfirmasi Reset Ujian</v-card-title>
        <v-card-text>
          Apakah anda yakin akan mereset ujian
          <strong>
            {{mhsSelected.nama}}?
          </strong>
        </v-card-text>
        <v-card-actions>
          <v-btn
            color="green"
            class="text-white"
            :loading="isLoading"
            @click="resetUjian()"
          >Iya</v-btn>
          <v-btn
            text
            @click="dialogResetUjian = false"
          >batal</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-if="mhsSelected"
      width="400"
      v-model="dialogResetPembayaran"
    >
      <v-card>
        <v-card-title>Konfirmasi Reset Pembayaran</v-card-title>
        <v-card-text>
          Apakah anda yakin akan mereset pembayaran
          <strong>
            {{mhsSelected.nama}}?
          </strong>
        </v-card-text>
        <v-card-actions>
          <v-btn
            color="green"
            class="text-white"
            :loading="isLoading"
            @click="resetPembayaran()"
          >Iya</v-btn>
          <v-btn
            text
            @click="dialogResetPembayaran = false"
          >batal</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      scrollable
      width="400"
      v-model="dialogDetail"
    >
      <v-card v-if="mhsSelected">
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
            <v-row>
              <v-text-field
                color="green"
                filled
                @change="
                validationNilai(
                  { obj: mhsSelected, id: 4 },
                  'inggris',
                  mhsSelected.nilai_bhs_inggris
                )
              "
                prepend-inner-icon="mdi-attachment"
                type="number"
                label="Nilai Bahasa Inggris"
                v-model="mhsSelected.nilai_bhs_inggris"
              ></v-text-field>
            </v-row>

            <v-row>
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
            <v-row>
              <v-btn
                block
                x-large
                dark
                @click="link(mhsSelected.sertifikat_bhs_arab)"
                color="green darken-2"
              > Sertifikat Bahasa arab
              </v-btn>
            </v-row>
            <v-row>
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
        <v-card-actions>
          <v-btn
            color="green"
            class="text-white"
            @click="dialogResetPembayaran = true"
          >Reset Pembayaran</v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="green"
            class="text-white"
            @click="dialogResetUjian = true"
          >Reset Ujian</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { mapMutations, mapState } from "vuex";
const FileDownload = require("js-file-download");
export default {
  data() {
    return {
      kelases: null,
      downloadLoading: false,
      dialogResetPembayaran: false,
      dialogResetUjian: false,
      dialogDetail: false,
      jk: ["Laki-laki", "Perempuan"],
      mhsSelected: null,
      search: "",
      periode: [],
      jurusan: [],
      pendaftaran: [],
      form: {},
      filter: {},
      isLoading: false,
      dialogDelete: false,
      snackbar: { show: false },
      scrollOps: {
        scrollPanel: {
          easing: "easeInQuad",
          speed: 800,
          scrollingX: false,
        },
        vuescroll: {
          mode: "native",
          wheelScrollDuration: 0,
          locking: true,
        },
      },
      headers: [
        {
          text: "Nama",
          align: "start",
          value: "nama_pendaftar",
        },
        { text: "Jurusan", value: "jurusan" },
        { text: "Pembayaran", value: "pembayaran" },
        { text: "Kelulusan", value: "kelulusan" },
        { text: "Actions", value: "actions" },
      ],
    };
  },
  computed: {
    ...mapState([
      "isBottomSheetOpen",
      "urlPeriode",
      "urlJurusan",
      "urlPendaftaran",
    ]),
    bottomSheet: {
      get: function () {
        return this.isBottomSheetOpen;
      },
      set: function (data) {
        this.toggleBottomSheet(data);
      },
    },
  },
  watch: {
    bottomSheet(val) {
      if (!val) {
        this.form = {};
      }
    },
    dialogDelete(val) {
      if (!val) {
        this.form = {};
      }
    },
  },
  created() {
    this.getPendaftaran();
    this.getPeriode();
    this.getJurusan();
  },
  methods: {
    ...mapMutations(["toggleBottomSheet"]),
    downloadExcel() {
      var url = "/api/ujian/export";
      this.downloadLoading = true;
      var params = this.filter;
      axios
        .get(url, {
          params,
          responseType: "blob",
        })
        .then((response) => {
          FileDownload(response.data, "report.xlsx");
          this.downloadLoading = false;
        });
    },
    resetPembayaran() {
      this.isLoading = true;
      var id = this.mhsSelected.ujianID;
      var url = "/api/ujian/reset-payment/" + id;
      axios.get(url).then((response) => {
        console.log(response.data);
        if (response.data.status == 1 || response.data.status == true) {
          this.dialogResetPembayaran = false;
          this.snackbar.message = response.data.message;
          this.snackbar.color = "green";
          this.snackbar.show = true;
        }
        this.isLoading = false;
      });
    },
    resetUjian() {
      this.isLoading = true;
      var id = this.mhsSelected.ujianID;
      var url = "/api/ujian/reset-ujian/" + id;
      axios.get(url).then((response) => {
        console.log(response.data);
        if (response.data.status == 1 || response.data.status == true) {
          this.dialogResetUjian = false;
          this.snackbar.message = response.data.message;
          this.snackbar.color = "green";
          this.snackbar.show = true;
        }
        this.isLoading = false;
      });
    },
    link(url) {
      var a = "/" + url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
    },
    show(item) {
      this.mhsSelected = item.user_cln_mhs;
      this.mhsSelected["ujianID"] = item.id;
      this.mhsSelected["kode_bayar"] = item.kode_bayar;
      this.dialogDetail = true;
    },
    getPendaftaran(params = {}) {
      this.isLoading = true;
      axios
        .get(this.urlPendaftaran, {
          params: params,
        })
        .then((response) => {
          this.filter.periode = response.data.currentPeriode?.id;
          this.pendaftaran = response.data.pendaftaran;
          this.kelases = response.data.kelas;
        })
        .catch((err) => {
          console.error(err);
        })
        .then((this.isLoading = false));
    },
    getPeriode() {
      this.isLoading = true;
      axios
        .get(this.urlPeriode)
        .then((response) => {
          this.periode = response.data;
        })
        .catch((err) => {
          console.error(err);
        })
        .then((this.isLoading = false));
    },
    getJurusan() {
      this.isLoading = true;
      axios
        .get(this.urlJurusan)
        .then((response) => {
          this.jurusan = response.data;
        })
        .catch((err) => {
          console.error(err);
        })
        .then((this.isLoading = false));
    },
    edit(item) {
      this.form = _.clone(item);
      this.bottomSheet = true;
    },
    submit() {
      const form = this.form;
      if (!form.id) {
        this.store();
        return;
      }
      this.update(form.id);
    },
    store() {
      this.isLoading = true;
      axios
        .post(this.urlPendaftaran, this.form)
        .then((response) => {
          if (response.data.status) {
            this.bottomSheet = false;
            this.snackbar = {
              show: true,
              message: response.data.message,
            };
          }
        })
        .catch((err) => {
          console.error(err);
          this.snackbar = {
            show: true,
            message: err,
            color: "danger",
          };
        })
        .then(() => {
          this.isLoading = false;
          this.getJurusan();
        });
    },
    update(id) {
      const urlPendaftaran = `${this.urlPendaftaran}/${id}`;
      this.isLoading = true;
      axios
        .put(urlPendaftaran, this.form)
        .then((response) => {
          if (response.data.status) {
            this.bottomSheet = false;
            this.form = {};
            this.snackbar = {
              show: true,
              message: response.data.message,
            };
            this.getJurusan();
          }
        })
        .catch((err) => {
          console.error(err);
          this.snackbar = {
            show: true,
            message: err,
            color: "danger",
          };
        })
        .then((this.isLoading = false));
    },
    destroy() {
      const id = this.form.id;
      const urlJurusan = `${this.urlJurusan}/${id}`;
      this.isLoading = true;
      axios
        .delete(urlJurusan)
        .then((response) => {
          if (response.data.status) {
            this.dialogDelete = false;
            this.getJurusan();
            this.snackbar = {
              show: true,
              message: response.data.message,
            };
          }
        })
        .catch((err) => {
          console.error(err);
          this.snackbar = {
            show: true,
            message: err,
            color: "danger",
          };
        })
        .then((this.isLoading = false));
    },
    filterPendaftaran() {
      const form = this.filter;
      this.getPendaftaran(form);
    },
  },
};
</script>

<style>
</style>
