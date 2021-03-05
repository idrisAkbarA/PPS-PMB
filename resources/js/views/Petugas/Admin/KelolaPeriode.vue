<template>
  <v-container>
    <p class="text-muted">Mengolola periode pada Penerimaan Mahasiswa Baru</p>
    <v-card>
      <v-card-title>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="periode"
        :items-per-page="10"
        :search="search"
        :loading="isLoading"
        class="elevation-1"
      >
        <template v-slot:[`item.is_active`]="{ item }">
          <v-switch
            inset
            :color="item.is_active ? 'success' : ''"
            :label="item.is_active ? 'Aktif' : 'Non-Aktif'"
            @change="swithStatus(item)"
            v-model="item.is_active"
          ></v-switch>
        </template>
        <template v-slot:[`item.periode`]="{ item }">
          {{ `${item.awal_periode} - ${item.akhir_periode}` }}
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-btn
            icon
            x-small
            class="mr-2"
            title="Detail"
            @click="
              dialogShow = true;
              form = item;
            "
          >
            <v-icon>mdi-information</v-icon>
          </v-btn>
          <v-btn icon x-small class="mr-2" title="Edit" @click="edit(item)">
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
          </v-btn>
        </template>
      </v-data-table>
    </v-card>
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
          <span>Periode</span>
          <v-spacer></v-spacer>
          <v-btn text class="mr-2" @click="bottomSheet = false">batal</v-btn>
          <v-btn color="#2C3E50" dark @click="submit">Simpan</v-btn>
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
                    label="Nama Periode"
                    hint="*Contoh : 2021/2022 Gelombang II"
                    v-model="form.nama"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-menu
                    :nudge-right="40"
                    transition="scale-transition"
                    min-width="290px"
                    offset-y
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        readonly
                        color="#2C3E50"
                        prepend-icon="mdi-calendar"
                        label="Awal Periode"
                        v-bind="attrs"
                        v-on="on"
                        v-model="form.awal_periode"
                      >
                      </v-text-field>
                    </template>
                    <v-date-picker
                      color="green lighteen-2"
                      locale="id-ID"
                      v-model="form.awal_periode"
                    >
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="6">
                  <v-menu
                    :nudge-right="40"
                    transition="scale-transition"
                    min-width="290px"
                    offset-y
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        readonly
                        color="#2C3E50"
                        prepend-icon="mdi-calendar"
                        label="Akhir Periode"
                        v-bind="attrs"
                        v-on="on"
                        v-model="form.akhir_periode"
                      >
                      </v-text-field>
                    </template>
                    <v-date-picker
                      color="green lighteen-2"
                      locale="id-ID"
                      :allowed-dates="allowedDateAkhirPeriode"
                      v-model="form.akhir_periode"
                    >
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" class="pb-0">
                  <p class="overline text-muted mb-0">Setting Ujian</p>
                </v-col>
                <v-col cols="6">
                  <label class="text-dark">Syarat Mengikuti Ujian</label>
                </v-col>
                <v-col cols="2">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    min="0"
                    max="4"
                    label="IPK"
                    hint="*IPK dalam angka 1 - 4"
                    v-model="form.syarat_ipk"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="2">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    min="0"
                    label="Bahasa Inggris"
                    v-model="form.syarat_bhs_inggris"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="2">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    min="0"
                    label="Bahasa Arab"
                    v-model="form.syarat_bhs_arab"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="6">
                  <label class="text-dark">Jumlah Soal</label>
                </v-col>
                <v-col cols="3">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    min="0"
                    label="TKA"
                    v-model="form.jumlah_tka"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="3">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    min="0"
                    label="TKJ"
                    v-model="form.jumlah_tkj"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="6">
                  <label class="text-dark">Syarat Lulus Ujian</label>
                </v-col>
                <v-col cols="3">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    min="0"
                    label="TKA"
                    hint="*Jumlah soal terjawab"
                    v-model="form.min_lulus_tka"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="3">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    min="0"
                    label="TKJ"
                    hint="*Jumlah soal terjawab"
                    v-model="form.min_lulus_tkj"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="6">
                  <label class="text-dark">Range Ujian</label>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    min="0"
                    label="Range Ujian"
                    hint="Batas ujian setelah pembayaran, dalam hari"
                    v-model="form.range_ujian"
                    suffix="Hari"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="6">
                  <label class="text-dark">Durasi Ujian</label>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    min="0"
                    label="Durasi Ujian"
                    hint="Durasi ujian, dalam menit"
                    v-model="form.durasi_ujian"
                    suffix="Menit"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="6">
                  <label class="text-dark">Durasi Menjawab 1 soal</label>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    min="0"
                    label="Durasi soal"
                    hint="Durasi ujian, dalam detik"
                    v-model="form.durasi_soal"
                    suffix="Detik"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-menu
                    :nudge-right="40"
                    transition="scale-transition"
                    min-width="290px"
                    offset-y
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        readonly
                        color="#2C3E50"
                        prepend-icon="mdi-calendar"
                        label="Awal Temu Ramah"
                        v-bind="attrs"
                        v-on="on"
                        v-model="form.awal_temu_ramah"
                      >
                      </v-text-field>
                    </template>
                    <v-date-picker
                      color="green lighteen-2"
                      locale="id-ID"
                      v-model="form.awal_temu_ramah"
                    >
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="6">
                  <v-menu
                    :nudge-right="40"
                    transition="scale-transition"
                    min-width="290px"
                    offset-y
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        readonly
                        color="#2C3E50"
                        prepend-icon="mdi-calendar"
                        label="Akhir Temu Ramah"
                        v-bind="attrs"
                        v-on="on"
                        v-model="form.akhir_temu_ramah"
                      >
                      </v-text-field>
                    </template>
                    <v-date-picker
                      color="green lighteen-2"
                      locale="id-ID"
                      :allowed-dates="allowedDateAkhirTemuRamah"
                      v-model="form.akhir_temu_ramah"
                    >
                    </v-date-picker>
                  </v-menu>
                </v-col>
              </v-row>
              <v-divider></v-divider>
              <v-row class="px-3 mt-3 mb-3">
                <p class="overline text-muted">Set komposisi soal dan kuota</p>
                <p>
                  Tetapkan komposisi soal ujian serta kuota mahasiswa per kelas
                  untuk setiap jurusan, jika tidak ditetapkan maka digunakan
                  <i> setting default</i>. Ubah <i>setting default</i> di
                  halaman
                  <router-link
                    :to="{ name: 'Kelola Jurusan' }"
                    @click="bottomSheet = false"
                  >
                    Kelola Jurusan</router-link
                  >
                </p>
              </v-row>
              <v-row class="px-3">
                <v-expansion-panels focusable>
                  <v-expansion-panel v-for="(item, i) in form.jurusan" :key="i">
                    <v-expansion-panel-header v-slot="{ open }">
                      <v-row no-gutters>
                        <v-col cols="4">
                          <strong>
                            {{ item.nama }}
                          </strong>
                        </v-col>
                        <v-col cols="8" class="text--secondary">
                          <v-fade-transition leave-absolute>
                            <span v-if="open"
                              >Tetapkan komposisi soal dan kuota</span
                            >
                            <v-row v-else no-gutters style="width: 100%">
                              <v-col cols="4">
                                <span v-if="item.kategori">
                                  <strong> TKA: </strong>
                                  <ul>
                                    <li
                                      v-for="(row, index) in item.kategori"
                                      :key="index"
                                    >
                                      {{ row.nama }} : {{ row.jumlah_tka }} Soal
                                    </li>
                                  </ul>
                                </span>
                              </v-col>
                              <v-col cols="4">
                                <strong> TKJ: </strong>
                                <span v-if="item.kategori">
                                  <ul>
                                    <li
                                      v-for="(row, index) in item.kategori"
                                      :key="index"
                                    >
                                      {{ row.nama }} : {{ row.jumlah_tkj }} Soal
                                    </li>
                                  </ul>
                                </span>
                              </v-col>
                              <v-col cols="4">
                                <span>
                                  <strong> Kelas </strong>
                                </span>
                                <tr></tr>
                                {{ item.kuota_kelas_default }} Mahasiswa/Kelas
                              </v-col>
                            </v-row>
                          </v-fade-transition>
                        </v-col>
                      </v-row>
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                      <v-container>
                        <v-row>
                          <v-col cols="6" v-if="item.kategori">
                            <p class="overline text-muted mb-0">
                              Komposisi TKA
                            </p>
                            <v-row
                              align="center"
                              v-for="(row, index) in item.kategori"
                              :key="index"
                            >
                              <v-col cols="6">{{ row.nama }}</v-col>
                              <v-col cols="6">
                                <v-text-field
                                  suffix="Soal"
                                  dense
                                  type="number"
                                  color="#2C3E50"
                                  v-model="row.jumlah_tka"
                                ></v-text-field>
                              </v-col>
                            </v-row>
                          </v-col>
                          <!-- <v-divider vertical></v-divider> -->
                          <v-col cols="6" v-if="item.kategori">
                            <p class="overline text-muted mb-0">
                              Komposisi TKJ
                            </p>
                            <v-row
                              align="center"
                              v-for="(row, index) in item.kategori"
                              :key="index"
                            >
                              <v-col cols="6">{{ row.nama }}</v-col>
                              <v-col cols="6">
                                <v-text-field
                                  dense
                                  suffix="Soal"
                                  type="number"
                                  color="#2C3E50"
                                  v-model="row.jumlah_tkj"
                                ></v-text-field>
                              </v-col>
                            </v-row>
                          </v-col>
                        </v-row>
                        <v-divider> </v-divider>
                        <v-row class="px-3">
                          <v-row align="center" justify="center">
                            <v-col cols="6">
                              <p class="overline text-muted mb-0">
                                Kuota per Kelas
                              </p>
                            </v-col>
                            <v-col cols="6">
                              <v-text-field
                                dense
                                type="number"
                                color="#2C3E50"
                                suffix="Orang/Kelas"
                                label="Kuota"
                                v-model="form.kuota_kelas_default"
                              ></v-text-field>
                            </v-col>
                          </v-row>
                        </v-row>
                      </v-container>
                    </v-expansion-panel-content>
                  </v-expansion-panel>
                </v-expansion-panels>
              </v-row>
            </v-card-text>
            <!-- </v-card> -->
          </vue-scroll>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <!-- Dialog Show -->
    <v-dialog v-model="dialogShow" width="500">
      <v-card>
        <v-card-title class="headline">
          <v-icon>mdi-trash</v-icon>
        </v-card-title>

        <v-card-text>
          <v-simple-table>
            <template v-slot:default>
              <tbody>
                <tr>
                  <td>Nama</td>
                  <td>{{ form.nama }}</td>
                </tr>
                <tr>
                  <td>Periode</td>
                  <td>{{ `${form.awal_periode} - ${form.akhir_periode}` }}</td>
                </tr>
                <tr>
                  <td>Range Ujian</td>
                  <td>{{ form.range_ujian }} Hari</td>
                </tr>
                <tr>
                  <td>Syarat</td>
                  <td>
                    IPK: {{ form.syarat_ipk }}, Bahasa Arab:
                    {{ form.syarat_bhs_arab }}, Bahasa Inggris:
                    {{ form.syarat_bhs_inggris }}
                  </td>
                </tr>
                <tr>
                  <td>Jumlah Soal</td>
                  <td>
                    TKA {{ form.jumlah_tka }} Soal / TKJ
                    {{ form.jumlah_tkj }} Soal
                  </td>
                </tr>
                <tr>
                  <td>Syarat Lulus</td>
                  <td>
                    TKA: {{ form.min_lulus_tka }} Soal Terjawab / TKJ:
                    {{ form.min_lulus_tkj }} Soal Terjawab
                  </td>
                </tr>
                <tr>
                  <td>Temu Ramah</td>
                  <td>
                    {{ `${form.awal_temu_ramah} - ${form.akhir_temu_ramah}` }}
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-card-text>
      </v-card>
    </v-dialog>
    <!-- Dialog Delete -->
    <v-dialog v-model="dialogDelete" width="500">
      <v-card>
        <v-card-title class="headline">
          <v-icon>mdi-trash</v-icon>
        </v-card-title>

        <v-card-text>
          <p class="text-center">
            Apakah anda yakin ingin menghapus periode ini ?
          </p>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-btn text @click="dialogDelete = false"> Batal </v-btn>
          <v-spacer></v-spacer>
          <v-btn color="#2C3E50" dark @click="destroy"> Ya </v-btn>
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
import { mapMutations, mapState, mapActions } from "vuex";
export default {
  data() {
    return {
      search: "",
      periode: [],
      form: {},
      isLoading: false,
      dialogDelete: false,
      dialogShow: false,
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
          value: "nama",
        },
        { text: "Periode", value: "periode" },
        { text: "Status", value: "is_active" },
        { text: "Actions", value: "actions" },
      ],
    };
  },
  computed: {
    ...mapState([
      "isBottomSheetOpen",
      "urlPeriode",
      "currentPeriode",
      "jurusan",
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
      } else {
        this.form.jurusan = _.cloneDeep(this.jurusan);
        if (!this.form.id) {
          this.form.jurusan.forEach((item) => {
            item.kategori.forEach((el) => {
              let kategori = item.komposisi_tka_default.filter((elem) => {
                return elem.kategori_id == el.id;
              })[0];
              el.jumlah_tka = kategori ? kategori.jumlah : 0;

              kategori = item.komposisi_tkj_default.filter((elem) => {
                return elem.kategori_id == el.id;
              })[0];
              el.jumlah_tkj = kategori ? kategori.jumlah : 0;
            });
          });
        } else {
          this.form.jurusan.forEach((item) => {
            item.kategori.forEach((el) => {
              const jurusan = this.form.kategori.filter((elem) => {
                return elem.jurusan_id == item.id;
              })[0];

              let kategori = jurusan.komposisi_tka.filter((elem) => {
                return elem.kategori_id == el.id;
              })[0];
              el.jumlah_tka = kategori ? kategori.jumlah : 0;

              kategori = jurusan.komposisi_tkj.filter((elem) => {
                return elem.kategori_id == el.id;
              })[0];
              el.jumlah_tkj = kategori ? kategori.jumlah : 0;
            });
          });
        }
      }
    },
    dialogDelete(val) {
      if (!val) {
        this.form = {};
      }
    },
    dialogShow(val) {
      if (!val) {
        this.form = {};
      }
    },
  },
  created() {
    this.getPeriode();
    this.getJurusan();
  },
  methods: {
    ...mapActions(["getJurusan"]),
    ...mapMutations(["toggleBottomSheet", "setCurrentPeriode"]),
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
        .then(() => {
          this.isLoading = false;
          const periode = this.periode.filter((value) => {
            return value.is_active;
          });
          this.setCurrentPeriode(periode[0] ?? {});
        });
    },
    allowedDateAkhirPeriode(val) {
      return val >= this.form.awal_periode;
    },
    allowedDateAkhirTemuRamah(val) {
      return val >= this.form.awal_temu_ramah;
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
    swithStatus(item) {
      this.form = item;
      this.update(item.id);
    },
    store() {
      this.isLoading = true;
      axios
        .post(this.urlPeriode, this.form)
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
          this.getPeriode();
        });
    },
    update(id) {
      const urlPeriode = `${this.urlPeriode}/${id}`;
      this.isLoading = true;
      axios
        .put(urlPeriode, this.form)
        .then((response) => {
          if (response.data.status) {
            this.bottomSheet = false;
            this.form = {};
            this.snackbar = {
              show: true,
              message: response.data.message,
            };
            this.getPeriode();
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
      const urlPeriode = `${this.urlPeriode}/${id}`;
      this.isLoading = true;
      axios
        .delete(urlPeriode)
        .then((response) => {
          if (response.data.status) {
            this.dialogDelete = false;
            this.getPeriode();
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
  },
};
</script>

<style>
</style>
