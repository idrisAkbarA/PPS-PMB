<template>
  <v-container>
    <v-card class="mb-2" v-if="currentPeriode">
      <v-card-title> Periode {{ currentPeriode.nama }}</v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols="6" class="pb-0"> Awal Tamu Ramah </v-col>
          <v-col cols="6" class="pb-0">
            {{ currentPeriode.awal_temu_ramah }}
          </v-col>
          <v-col cols="6"> Akhir Tamu Ramah </v-col>
          <v-col cols="6">
            {{ currentPeriode.akhir_temu_ramah }}
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-card class="mb-3">
      <v-expansion-panels v-if="temuRamah.length > 0">
        <v-expansion-panel v-for="(row, i) in temuRamah" :key="i">
          <v-expansion-panel-header>
            <span>
              {{ row.nama_dosen }}
              <small class="text-muted"> ({{ row.tanggal }})</small>
            </span>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <v-row>
              <v-col cols="12">
                <v-btn-toggle
                  v-model="isEdit"
                  mandatory
                  rounded
                  class="float-right"
                >
                  <v-btn small>
                    <v-icon small class="mr-2">mdi-eye</v-icon>
                    View Mode
                  </v-btn>
                  <v-btn small>
                    <v-icon small class="mr-2">mdi-pencil</v-icon>
                    Edit Mode
                  </v-btn>
                </v-btn-toggle>
                <v-subheader>Calon Mahasiswa</v-subheader>
                <v-divider class="my-0"></v-divider>
                <v-list subheader>
                  <template v-for="(row, index) in row.calon_mahasiswa">
                    <v-list-item
                      two-line
                      :key="index"
                      v-on="isEdit ? {} : { click: () => show(row) }"
                    >
                      <v-list-item-content>
                        <v-list-item-title>{{ row.nama }}</v-list-item-title>
                        <v-list-item-subtitle> Jurusan </v-list-item-subtitle>
                      </v-list-item-content>
                      <v-list-item-action v-if="!isEdit">
                        <v-chip
                          outlined
                          :color="
                            row.is_verified !== null
                              ? row.is_verified
                                ? 'success'
                                : 'red'
                              : 'warning'
                          "
                          >{{
                            row.is_verified !== null
                              ? row.is_verified
                                ? "Lulus"
                                : "Tidak Lulus"
                              : "Belum Diverifikasi"
                          }}</v-chip
                        >
                      </v-list-item-action>
                      <v-list-item-action v-else class="d-inline">
                        <v-btn
                          small
                          outlined
                          color="red"
                          v-if="row.is_verified == null"
                          @click="update(row.id, false)"
                          >Tdk Lulus</v-btn
                        >
                        <v-btn
                          small
                          outlined
                          color="success"
                          v-if="row.is_verified == null"
                          @click="update(row.id, true)"
                          >Lulus</v-btn
                        >
                        <v-chip
                          close
                          outlined
                          close-icon="mdi-close"
                          :color="row.is_verified ? 'success' : 'red'"
                          v-if="row.is_verified !== null"
                          @click:close="update(row.id, null)"
                          >{{ row.is_verified ? "Lulus" : "Tdk Lulus" }}</v-chip
                        >
                      </v-list-item-action>
                    </v-list-item>
                    <v-divider
                      class="my-0"
                      :key="'divider' + index"
                    ></v-divider>
                  </template>
                </v-list>
                <p class="text-center mt-2" v-if="!row.ids_cln_mhs.length">
                  Belum ada peserta temu ramah
                </p>
              </v-col>
            </v-row>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-card>
    <!-- Dialog Show -->
    <v-dialog v-model="dialogShow" width="500" scrollable v-if="dialogShow">
      <v-card>
        <v-card-title class="headline">
          <v-icon>mdi-trash</v-icon>
        </v-card-title>

        <v-card-text>
          <vue-scroll :ops="scrollOps">
            <v-row>
              <v-col cols="6">
                <v-img
                  class="mx-auto"
                  max-width="150"
                  :src="
                    selectedCalonMahasiswa.pas_photo
                      ? `/${selectedCalonMahasiswa.pas_photo}`
                      : '/images/empty.png'
                  "
                ></v-img>
              </v-col>
              <v-col cols="6">
                <h5>{{ selectedCalonMahasiswa.nama }}</h5>
                <p class="text-muted">{{ selectedCalonMahasiswa.email }}</p>
                <v-row class="mt-3">
                  <v-col cols="6" class="mb-0">HP / WA</v-col>
                  <v-col cols="6" class="mb-0"
                    >{{
                      selectedCalonMahasiswa.hp
                        ? selectedCalonMahasiswa.hp
                        : "-"
                    }}
                    /
                    {{
                      selectedCalonMahasiswa.wa
                        ? selectedCalonMahasiswa.wa
                        : "-"
                    }}</v-col
                  >
                  <v-col cols="6" class="mb-0">IPK</v-col>
                  <v-col cols="6" class="mb-0"
                    >{{
                      selectedCalonMahasiswa.nilai_ipk
                        ? selectedCalonMahasiswa.nilai_ipk
                        : "-"
                    }}
                  </v-col>
                  <v-col cols="6" class="mb-0">Bahasa</v-col>
                  <v-col cols="6" class="mb-0"
                    >{{
                      selectedCalonMahasiswa.nilai_bhs
                        ? selectedCalonMahasiswa.nilai_bhs
                        : "-"
                    }}
                  </v-col>
                  <v-col cols="6" class="mb-0">Alamat</v-col>
                  <v-col cols="6" class="mb-0"
                    >{{
                      selectedCalonMahasiswa.alamat
                        ? selectedCalonMahasiswa.alamat
                        : "-"
                    }}
                  </v-col>
                  <v-col cols="6" class="mb-0">
                    <v-btn
                      small
                      outlined
                      color="#2C3E50"
                      :disabled="selectedCalonMahasiswa.ijazah === null"
                      @click="filePath(selectedCalonMahasiswa.ijazah)"
                      >Ijazah</v-btn
                    >
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="12">
                <v-list subheader>
                  <v-subheader>Pendaftaran</v-subheader>
                  <v-divider class="my-0"></v-divider>
                  <template
                    v-for="(ujian, index) in selectedCalonMahasiswa.ujian"
                  >
                    <v-list-item three-line :key="index">
                      <v-list-item-content>
                        <v-list-item-title>{{
                          ujian.nama_jurusan
                        }}</v-list-item-title>
                        <v-list-item-subtitle>
                          Periode {{ ujian.nama_periode }}
                        </v-list-item-subtitle>
                        <v-list-item-subtitle>
                          <span
                            :class="
                              ujian.lulus_at ? 'text-success' : 'text-danger'
                            "
                          >
                            {{ ujian.lulus_at ? "Lulus" : "Tidak Lulus" }}
                            <v-icon
                              small
                              :color="ujian.lulus_at ? 'success' : 'red'"
                              >{{
                                ujian.lulus_at ? "mdi-check" : "mdi-close"
                              }}</v-icon
                            >
                          </span>
                        </v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    <v-divider
                      :key="'divider-' + index"
                      class="my-0"
                    ></v-divider>
                  </template>
                </v-list>
              </v-col>
            </v-row>
          </vue-scroll>
        </v-card-text>
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
import { mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      search: "",
      temuRamah: [],
      form: {},
      currentPeriode: null,
      selectedCalonMahasiswa: null,
      isLoading: false,
      isEdit: false,
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
          text: "Tanggal",
          align: "start",
          value: "tanggal",
        },
        { text: "Dosen", value: "nama_dosen" },
        { text: "Kuota", value: "quota" },
        { text: "Actions", value: "actions" },
      ],
    };
  },
  computed: {
    ...mapState(["isBottomSheetOpen", "urlTemuRamah", "urlPendaftar"]),
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
    this.getTemuRamah();
  },
  methods: {
    ...mapMutations(["toggleBottomSheet"]),
    getTemuRamah(params = {}) {
      this.isLoading = true;
      axios
        .get(this.urlTemuRamah, {
          params: params,
        })
        .then((response) => {
          this.currentPeriode = response.data.currentPeriode;
          this.temuRamah = response.data.temuRamah;
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
    show(item) {
      this.selectedCalonMahasiswa = item;
      this.dialogShow = true;
    },
    edit(item) {
      this.form = _.clone(item);
      this.bottomSheet = true;
    },
    update(id, val) {
      const urlPendaftar = `${this.urlPendaftar}/${id}`;
      const form = {
        is_verified: val,
      };
      this.isLoading = true;
      axios
        .put(urlPendaftar, form)
        .then((response) => {
          if (response.data.status) {
            this.bottomSheet = false;
            this.form = {};
            this.snackbar = {
              show: true,
              message: response.data.message,
            };
            this.getTemuRamah();
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
    filePath(file) {
      var a = "/" + file;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
    },
  },
};
</script>

<style>
</style>
