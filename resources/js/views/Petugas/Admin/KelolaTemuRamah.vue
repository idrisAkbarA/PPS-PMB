<template>
  <v-container>
    <p class="text-muted">
      Mengolola Temu Ramah pada setiap periode pada Penerimaan Mahasiswa Baru
    </p>
    <v-card class="mb-3">
      <v-expansion-panels>
        <v-expansion-panel>
          <v-expansion-panel-header> Periode </v-expansion-panel-header>
          <v-expansion-panel-content>
            <v-row>
              <v-col cols="6" v-if="currentPeriode">
                <v-row>
                  <v-col cols="6" class="pb-0"> Nama Periode </v-col>
                  <v-col cols="6" class="pb-0">
                    {{ currentPeriode.nama }}
                  </v-col>
                  <v-col cols="6" class="pb-0"> Awal Tamu Ramah </v-col>
                  <v-col cols="6" class="pb-0">
                    {{ currentPeriode.awal_temu_ramah }}
                  </v-col>
                  <v-col cols="6"> Akhir Tamu Ramah </v-col>
                  <v-col cols="6">
                    {{ currentPeriode.akhir_temu_ramah }}
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="6">
                <v-select
                  :items="periode"
                  label="Periode"
                  item-text="nama"
                  item-value="id"
                  v-model="filter.periode"
                  @change="filterTemuRamah"
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
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="temuRamah"
        :items-per-page="10"
        :search="search"
        :loading="isLoading"
        class="elevation-1"
      >
        <template v-slot:[`item.quota`]="{ item }">
          {{ `${item.ids_cln_mhs.length} / ${item.quota}` }}
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-btn icon x-small class="mr-2" title="Detail">
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
          <span>Jadwal Temu Ramah</span>
          <v-spacer></v-spacer>
          <v-btn text class="mr-2" @click="bottomSheet = false">batal</v-btn>
          <v-btn color="#2C3E50" dark @click="submit">Simpan</v-btn>
        </v-card-title>
        <v-card-text>
          <vue-scroll :ops="scrollOps">
            <!-- <v-card> -->
            <v-card-text>
              <v-row align="center">
                <v-col cols="5">
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
                        label="Tanggal"
                        v-bind="attrs"
                        v-on="on"
                        v-model="form.tanggal"
                      >
                      </v-text-field>
                    </template>
                    <v-date-picker
                      color="green lighteen-2"
                      locale="id-ID"
                      :allowed-dates="allowedDateTemuRamah"
                      v-model="form.tanggal"
                    >
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="5">
                  <v-text-field
                    color="#2C3E50"
                    label="Dosen"
                    v-model="form.nama_dosen"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="2">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    label="Kuota"
                    :min="0"
                    v-model="form.quota"
                  >
                  </v-text-field>
                </v-col>
              </v-row>
            </v-card-text>
            <!-- </v-card> -->
          </vue-scroll>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <!-- Dialog Delete -->
    <v-dialog v-model="dialogDelete" width="500">
      <v-card>
        <v-card-title class="headline">
          <v-icon>mdi-trash</v-icon>
        </v-card-title>

        <v-card-text>
          <p class="text-center">
            Apakah anda yakin ingin menghapus jadwal ini ?
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
import { mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      search: "",
      periode: [],
      temuRamah: [],
      form: {},
      filter: {},
      currentPeriode: null,
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
    ...mapState(["isBottomSheetOpen", "urlPeriode", "urlTemuRamah"]),
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
    this.getPeriode();
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
          this.filter.periode = response.data.currentPeriode?.id;
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
    allowedDateTemuRamah(val) {
      return (
        val >= this.currentPeriode.awal_temu_ramah &&
        val <= this.currentPeriode.akhir_temu_ramah
      );
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
      this.form.periode_id = this.currentPeriode.id;
      axios
        .post(this.urlTemuRamah, this.form)
        .then((response) => {
          this.bottomSheet = false;
          this.snackbar = {
            show: true,
            message: response.data.message,
            color: response.data.status ? "success" : "danger",
          };
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
          this.getTemuRamah();
        });
    },
    update(id) {
      const urlTemuRamah = `${this.urlTemuRamah}/${id}`;
      this.isLoading = true;
      axios
        .put(urlTemuRamah, this.form)
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
    destroy() {
      const id = this.form.id;
      const urlTemuRamah = `${this.urlTemuRamah}/${id}`;
      this.isLoading = true;
      axios
        .delete(urlTemuRamah)
        .then((response) => {
          if (response.data.status) {
            this.dialogDelete = false;
            this.getTemuRamah();
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
    filterTemuRamah() {
      const form = this.filter;
      this.getTemuRamah(form);
    },
  },
};
</script>

<style>
</style>
