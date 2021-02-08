<template>
  <v-container>
    Kelola Soal dan hal terkait
    <v-expansion-panels class="mb-2">
      <v-expansion-panel>
        <v-expansion-panel-header color="green">
          <div>
            <v-icon dark>mdi-filter</v-icon>
            <strong class="text-white"> Filter </strong>
          </div>
        </v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-row>
            <v-col cols="6">
              <v-select
                clearable
                :items="jurusan"
                label="Jurusan"
                item-text="nama"
                item-value="id"
                v-model="selectedJurusan"
              ></v-select>
            </v-col>
            <v-col cols="6">
              <v-select
                clearable
                :items="kategori"
                label="Kategori"
                item-text="nama"
                item-value="id"
                v-model="selectedKategori"
              ></v-select>
            </v-col>
          </v-row>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
    <v-card>
      <v-tabs
        v-model="tab"
        centered
      >
        <!-- background-color="green" -->
        <v-tabs-slider></v-tabs-slider>

        <v-tab href="#tab-1"> SOAL TKA </v-tab>

        <v-tab href="#tab-2"> SOAL tkj </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item value="tab-1">
          <!-- ttka -->
          <v-card flat>
            <v-card-text>
              <v-container>
                <v-row class="px-8">
                  <v-text-field
                    prepend-inner-icon="mdi-magnify"
                    clearable
                    label="Pencarian"
                    color="#2C3E50"
                    v-model="search.tka"
                  ></v-text-field>
                </v-row>
                <v-card
                  outlined
                  v-for="soal in soalTKA.data"
                  :key="soal.id"
                  color="rgba(46, 204, 113, 0.25)"
                  class="mt-5"
                >
                  <v-card-title>
                    <v-chip
                      color="#2C3E50"
                      dark
                    >
                      {{ soal.nama_kategori }}
                    </v-chip>
                    <v-spacer></v-spacer>
                    <v-text-field
                      label="Jurusan"
                      dense
                      :full-width="false"
                      hide-details="auto"
                      readonly
                      v-model="soal.nama_jurusan"
                    ></v-text-field>
                    <v-btn
                      color="#2C3E50"
                      dark
                      class="rounded-0"
                      @click="edit(soal)"
                    >
                      <v-icon small>mdi-pencil</v-icon>
                    </v-btn>
                    <v-btn
                      color="#2C3E50"
                      dark
                      class="rounded-0"
                      @click="
                        dialogDelete = true;
                        form = soal;
                      "
                    >
                      <v-icon small>mdi-delete</v-icon>
                    </v-btn>
                  </v-card-title>
                  <v-card-text class="black--text">
                    {{ soal.pertanyaan }}
                    <v-col cols="6">
                      <v-radio-group
                        hide-details
                        v-model="soal.jawaban"
                      >
                        <v-radio
                          v-for="pilihan in soal.pilihan_ganda"
                          :key="pilihan.pilihan"
                          :value="pilihan.pilihan"
                          :label="pilihan.text"
                          readonly
                          color="#2C3E50"
                        ></v-radio>
                      </v-radio-group>
                    </v-col>
                  </v-card-text>
                </v-card>
                <p
                  class="text-muted text-center"
                  v-if="!soalTKA.data.length"
                >
                  No data available
                </p>
                <v-row
                  align="center"
                  class="mt-3"
                  v-if="soalTKA.data.length"
                >
                  <v-col
                    cols="12"
                    class="text-center"
                  >
                    <v-pagination
                      color="#2C3E50"
                      class="mx-auto"
                      :length="soalTKA.lastPage"
                      :total-visible="7"
                      v-model="soalTKA.currentPage"
                    ></v-pagination>
                    <p class="mb-0 mt-2">
                      Showing {{ soalTKA.from }} - {{ soalTKA.to }} from
                      {{ soalTKA.total }} data
                    </p>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>
          </v-card>
        </v-tab-item>
        <v-tab-item value="tab-2">
          <v-card flat>
            <v-card-text>
              <v-container v-if="soalTKJ.data">
                <v-row class="px-8">
                  <v-text-field
                    prepend-inner-icon="mdi-magnify"
                    clearable
                    label="Pencarian"
                    color="#2C3E50"
                    v-model="search.tkj"
                  ></v-text-field>
                </v-row>
                <v-card
                  v-for="soal in soalTKJ.data"
                  :key="soal.id"
                  color="rgba(46, 204, 113, 0.25)"
                  class="mt-5"
                >
                  <v-card-title>
                    <v-chip
                      color="#2C3E50"
                      dark
                    >
                      {{ soal.nama_kategori }}
                    </v-chip>
                    <v-spacer></v-spacer>
                    <v-text-field
                      filled
                      dense
                      :full-width="false"
                      hide-details="auto"
                      readonly
                      v-model="soal.nama_jurusan"
                    ></v-text-field>
                    <v-btn
                      color="#2C3E50"
                      dark
                      class="rounded-0"
                      @click="edit(soal)"
                    >
                      <v-icon small>mdi-pencil</v-icon>
                    </v-btn>
                    <v-btn
                      color="#2C3E50"
                      dark
                      class="rounded-0"
                      @click="
                        dialogDelete = true;
                        form = soal;
                      "
                    >
                      <v-icon small>mdi-delete</v-icon>
                    </v-btn>
                  </v-card-title>
                  <v-card-text class="black--text">
                    {{ soal.pertanyaan }}
                    <v-col cols="6">
                      <v-radio-group
                        hide-details="auto"
                        v-model="soal.jawaban"
                      >
                        <v-radio
                          v-for="pilihan in soal.pilihan_ganda"
                          :key="pilihan.pilihan"
                          :value="pilihan.pilihan"
                          :label="pilihan.text"
                          readonly
                          color="#2C3E50"
                        ></v-radio>
                      </v-radio-group>
                    </v-col>
                  </v-card-text>
                </v-card>
                <p
                  class="text-muted text-center"
                  v-if="!soalTKJ.data.length"
                >
                  No data available
                </p>
                <v-row
                  align="center"
                  class="mt-3"
                  v-if="soalTKJ.data.length"
                >
                  <v-col cols="3">
                    Showing {{ soalTKJ.from }} - {{ soalTKJ.to }} from
                    {{ soalTKJ.total }} data
                  </v-col>
                  <v-col cols="6">
                    <v-pagination
                      color="#2C3E50"
                      class="text-center mx-auto"
                      :length="soalTKJ.lastPage"
                      :total-visible="7"
                      v-model="soalTKJ.currentPage"
                    ></v-pagination>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
    <v-bottom-sheet
      scrollable
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="bottomSheet"
    >
      <v-card color="#ecf0f1">
        <v-card-title>
          <span>Soal</span>
          <v-spacer></v-spacer>
          <v-btn
            text
            class="mr-2"
            @click="bottomSheet = false"
          >batal</v-btn>
        </v-card-title>
        <v-card-subtitle>
          <v-container>
            <v-row>
              <v-col cols="6">
                <v-select
                  :items="jurusan"
                  label="Jurusan"
                  item-text="nama"
                  item-value="id"
                  v-model="form.jurusan_id"
                ></v-select>
              </v-col>
              <v-col cols="3">
                <v-select
                  :items="kategori"
                  label="Kategori"
                  item-text="nama"
                  item-value="id"
                  v-model="form.kategori_id"
                ></v-select>
              </v-col>
              <v-col cols="3">
                <v-select
                  :items="['TKA', 'TKJ']"
                  label="Type"
                  v-model="form.type"
                ></v-select>
              </v-col>
            </v-row>
          </v-container>
        </v-card-subtitle>
        <v-card-text>
          <vue-scroll :ops="scrollOps">
            <v-card-text>
              <v-overlay
                absolute
                :value="!(form.type && form.kategori_id && form.jurusan_id)? true:false"
              >
                <v-card>
                  <v-card-text>
                    Mohon pilih <strong>jurusan, kategori, dan tipe soal</strong> terlebih dahulu
                  </v-card-text>
                </v-card>
              </v-overlay>
              <v-row>
                <v-col>
                  <v-card
                    outlined
                    v-if="cardSoal"
                    class="pa-2"
                  >
                    <v-container>
                      <v-row>
                        <v-col
                          cols="12"
                          class="pb-0"
                        >
                          <p class="overline text-muted mb-0">Pertanyaan</p>
                          <v-textarea
                            hide-details="auto"
                            outlined
                            color="#2C3E50"
                            :row-height="2"
                            auto-grow
                            label="Pertanyaan"
                            v-model="form.pertanyaan"
                          >
                          </v-textarea>
                        </v-col>
                        <v-col cols="12">
                          <p class="overline text-muted mb-0">Pilihan Ganda</p>
                          <v-radio-group
                            hide-details="auto"
                            v-model="form.jawaban"
                          >
                            <v-radio
                              v-for="pilihan in form.pilihan_ganda"
                              :key="pilihan.pilihan"
                              :value="pilihan.pilihan"
                              color="green darken-2"
                              readonly
                            >
                              <template v-slot:label>
                                <v-row align="center">
                                  <v-text-field
                                    hide-details
                                    class="ma-2"
                                    color="green"
                                    filled
                                    dense
                                    :label="pilihan.label"
                                    v-model="pilihan.text"
                                  ></v-text-field>
                                </v-row>
                              </template>
                            </v-radio>
                          </v-radio-group>
                        </v-col>
                        <v-col cols="12">
                          <p class="overline text-muted mb-0">Jawaban</p>
                          <v-select
                            label="Pilihan Jawaban"
                            :items="['A', 'B', 'C', 'D', 'E']"
                            v-model="form.jawaban"
                          ></v-select>
                        </v-col>
                        <v-col>
                          <v-btn
                            :loading="buttonLoading"
                            color="#2C3E50"
                            dark
                            @click="submit"
                            class="green darken-2 text-white"
                            :disabled="!(form.jawaban && form.pertanyaan)?true:false"
                          >
                            Simpan
                          </v-btn>
                        </v-col>

                      </v-row>
                    </v-container>
                  </v-card>
                  <v-card
                    v-else
                    outlined
                    class="mb-10"
                  >
                    <v-card-title>Soal Berhasil Disimpan!</v-card-title>
                  </v-card>
                </v-col>
              </v-row>
            </v-card-text>
          </vue-scroll>
        </v-card-text>
        <v-row justify="center">
        </v-row>
      </v-card>
    </v-bottom-sheet>
    <v-bottom-sheet v-model="bottomSheet2">
      <v-card>
        <v-card-title>Import Soal</v-card-title>
        <v-card-subtitle>Import soal dari file excel</v-card-subtitle>
        <v-card-text>
          <v-timeline
            align-top
            dense
          >
            <v-timeline-item
              color="pink"
              small
            >
              <v-row class="pt-1">
                <v-col cols="12">
                  <strong>Isi soal kedalam template file excel yang sudah di sediakan</strong>
                  <div>
                    <p class="text-muted">Pastikan selalu menggunakan template terbaru apabila ada perubahan pada kategori soal dan jurusan</p>
                    <v-btn
                      color="green darken-2"
                      dark
                      class="mt-1"
                      :loading="downloadLoading"
                      @click="downloadTemplate"
                    >
                      <v-icon left> mdi-download</v-icon>
                      Download template excel
                    </v-btn>
                  </div>
                </v-col>
              </v-row>
            </v-timeline-item>

            <v-timeline-item
              color="teal lighten-3"
              small
            >
              <v-row class="pt-1">
                <v-col cols="12">
                  <strong>Upload file excel yang sudah di isi</strong>
                  <div>
                    <div v-if="file">
                      <p class="text-muted">File terlampir: <br>
                        <strong>
                          {{file.name}}
                        </strong>
                        <i
                          class="mdi mdi-close"
                          @click="file=null"
                        ></i>
                      </p>
                    </div>
                    <v-btn
                      class="mt-1"
                      color="green darken-2"
                      @click="attachTemplate()"
                      :text="file?true:false"
                      dark
                    ><i class="mdi mdi-attachment mr-2"></i> <span v-if="!file">Lampirkan file excel</span> <span v-else>Ganti file</span> </v-btn>
                    <v-file-input
                      accept=".xls, .xlsx"
                      id="upload"
                      v-model="file"
                      hide-input
                      truncate-length="1"
                      class="d-none"
                    ></v-file-input>
                  </div>
                </v-col>
              </v-row>
            </v-timeline-item>
            <v-timeline-item
              color="teal lighten-3"
              small
            >
              <v-row>
                <v-col>
                  <strong>Upload dan simpan file excel terlampir</strong>
                  <div>
                    <v-btn
                      color="green darken-2"
                      class="mt-1 text-white"
                      :disabled="!file"
                      @click="uploadTemplate()"
                      :loading="btnLoading"
                    >
                      <v-icon left>
                        mdi-upload
                      </v-icon>
                      Upload file
                    </v-btn>
                  </div>
                </v-col>
              </v-row>
            </v-timeline-item>
          </v-timeline>
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
            Apakah anda yakin ingin menghapus soal ini ?
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
    <!-- Snackbar -->
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
  </v-container>
</template>

<script>
const FileDownload = require("js-file-download");
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      downloadLoading: false,
      btnLoading: false,
      file: null,
      buttonLoading: false,
      cardSoal: true,
      tab: null,
      selectedJurusan: null,
      selectedKategori: null,
      isLoading: false,
      dialogDelete: false,
      jurusan: [],
      kategori: [],
      search: {},
      form: {},
      snackbar: { show: false },
      soalTKJ: [],
      soalTKA: {
        data: [
          {
            id: 0,
            pertanyaan: "Ma Rabbuka?",
            pilihan_ganda: [
              { pilihan: "A", text: "Allah" },
              { pilihan: "B", text: "Allah" },
            ],
            jawaban: "A",
            jurusan_id: 0,
            kategori_id: 0,
            type: "tka",
          },
          {
            id: 1,
            pertanyaan: "Ma Rabbukaa?",
            pilihan_ganda: [
              { pilihan: "A", text: "Allah" },
              { pilihan: "B", text: "Allah" },
            ],
            jawaban: "A",
            jurusan_id: 0,
            kategori_id: 0,
            type: "tka",
          },
        ],
      },
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
    };
  },
  computed: {
    ...mapState([
      "isBottomSheetOpen",
      "isBottomSheetOpen2",
      "urlBankSoal",
      "urlJurusan",
      "urlKategori",
    ]),
    bottomSheet: {
      get: function () {
        return this.isBottomSheetOpen;
      },
      set: function (data) {
        this.toggleBottomSheet(data);
      },
    },
    bottomSheet2: {
      get: function () {
        return this.isBottomSheetOpen2;
      },
      set: function (data) {
        this.toggleBottomSheet2(data);
      },
    },
  },
  watch: {
    bottomSheet: function (val) {
      if (val && !this.form.id) {
        this.setPilihanGanda();
      } else if (!val) {
        this.form = {};
      }
    },
    selectedJurusan: function (val) {
      if (!val) {
        this.kategori = [];
      } else {
        this.getKategori(val);
      }
      this.selectedKategori = null;
      this.getSoal();
    },
    selectedKategori: function (val) {
      this.getSoal();
    },
    "soalTKA.currentPage": function (val) {
      this.getSoal("tka");
    },
    "soalTKJ.currentPage": function (val) {
      this.getSoal("tkj");
    },
    "form.jurusan_id": function (val) {
      if (val) {
        const urlKategori = `${this.urlKategori}/${val}`;
        this.isLoading = true;
        axios
          .get(urlKategori)
          .then((response) => {
            this.kategori = response.data;
          })
          .catch((err) => {
            console.error(err);
          })
          .then((this.isLoading = false));
        console.log(this.kategori);
      }
    },
  },
  created() {
    this.getJurusan();
    this.getSoal();
  },
  methods: {
    ...mapMutations(["toggleBottomSheet", "toggleBottomSheet2"]),
    ...mapActions(["importExcel"]),
    attachTemplate() {
      document.getElementById("upload").click();
    },
    downloadTemplate() {
      this.downloadLoading = true;
      axios
        .get("/api/bank-soal/download-template", {
          responseType: "blob",
        })
        .then((response) => {
          FileDownload(response.data, "Beasiswa.xlsx");
          this.downloadLoading = false;
        });
    },
    uploadTemplate() {
      this.btnLoading = true;
      var formData = new FormData();
      var file = this.file;
      formData.append("file", file);
      this.importExcel(formData)
        .then((response) => {
          this.btnLoading = false;
          this.bottomSheet2 = false;
          this.snackbar.message = `${response.data.total} Soal berhasil disimpan`;
          this.snackbar.show = true;
        })
        .catch((error) => {
          this.btnLoading = false;
          this.snackbar.color = "red";
          this.snackbar.message = `Maaf terjadi kesalahan, mohon periksa file kembali.`;
          this.snackbar.show = true;
        });
    },
    getSoal(type = null) {
      let urlBankSoal = this.urlBankSoal;
      if (type) {
        urlBankSoal = `${this.urlBankSoal}/${type}`;
      }

      const form = {
        jurusan: this.selectedJurusan,
        kategori: this.selectedKategori,
        page: type ? this[`soal${type.toUpperCase()}`].currentPage : null,
      };
      this.isLoading = true;
      axios
        .get(urlBankSoal, {
          params: form,
        })
        .then((response) => {
          if (type) {
            this[`soal${type.toUpperCase()}`] = {
              data: response.data.data,
              currentPage: response.data.current_page,
              lastPage: response.data.last_page,
              from: response.data.from,
              to: response.data.to,
              total: response.data.total,
            };
            return;
          }
          this.soalTKA = {
            data: response.data.tka.data,
            currentPage: response.data.tka.current_page,
            lastPage: response.data.tka.last_page,
            from: response.data.tka.from,
            to: response.data.tka.to,
            total: response.data.tka.total,
          };
          this.soalTKJ = {
            data: response.data.tkj.data,
            currentPage: response.data.tkj.current_page,
            lastPage: response.data.tkj.last_page,
            from: response.data.tkj.from,
            to: response.data.tkj.to,
            total: response.data.tkj.total,
          };
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
    getKategori(id) {
      const urlKategori = `${this.urlKategori}/${id}`;
      this.isLoading = true;
      axios
        .get(urlKategori)
        .then((response) => {
          this.kategori = response.data;
          if (this.kategori.some((e) => !(e.id == this.form.kategori_id))) {
            this.form.kategori_id = null;
          }
        })
        .catch((err) => {
          console.error(err);
        })
        .then((this.isLoading = false));
    },
    submit() {
      this.buttonLoading = true;
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
        .post(this.urlBankSoal, this.form)
        .then((response) => {
          if (response.data.status) {
            this.buttonLoading = false;
            this.cardSoal = false;
            this.form.pilihan_ganda = {};
            this.form.jawaban = null;
            this.form.pertanyaan = null;
            this.setPilihanGanda();
            setInterval(() => {
              this.cardSoal = true;
            }, 2000);
            // this.bottomSheet = false;
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
          this.getSoal();
        });
    },
    edit(item) {
      console.log(item);
      this.form = _.cloneDeep(item);
      this.bottomSheet = true;
    },
    update(id) {
      const urlBankSoal = `${this.urlBankSoal}/${id}`;
      this.isLoading = true;
      axios
        .put(urlBankSoal, this.form)
        .then((response) => {
          if (response.data.status) {
            this.bottomSheet = false;
            this.snackbar = {
              show: true,
              message: response.data.message,
            };
            this.getSoal();
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
      const urlBankSoal = `${this.urlBankSoal}/${id}`;
      this.isLoading = true;
      axios
        .delete(urlBankSoal)
        .then((response) => {
          if (response.data.status) {
            this.dialogDelete = false;
            this.getSoal();
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
    setPilihanGanda() {
      this.form.pilihan_ganda = [
        { pilihan: "A", label: "Pilihan A", text: "" },
        { pilihan: "B", label: "Pilihan B", text: "" },
        { pilihan: "C", label: "Pilihan C", text: "" },
        { pilihan: "D", label: "Pilihan D", text: "" },
        { pilihan: "E", label: "Pilihan E", text: "" },
      ];
    },
  },
};
</script>

<style>
</style>
