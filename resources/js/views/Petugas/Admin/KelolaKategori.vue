<template>
  <v-container>
    <p class="text-muted">
      Mengolola kategori soal pada Ujian Penerimaan Mahasiswa Baru
    </p>
    <v-card class="mb-3" v-if="!isEmptyObject(currentPeriode)">
      <v-expansion-panels>
        <v-expansion-panel>
          <v-expansion-panel-header>
            <strong>
              Kategori soal periode ini
              <span class="text-muted">( {{ currentPeriode.nama }})</span>
            </strong>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <v-row v-for="(row, i) in jurusan" :key="i" align="center">
              <v-col cols="6">
                {{ row.nama }}
              </v-col>
              <v-col cols="3">
                <v-select
                  :items="row.kategori"
                  label="Kategori TKA"
                  item-text="nama"
                  item-value="id"
                  v-model="currentPeriode.kategori[row.id].kat_tka_id"
                  @change="updateKategoriPeriode(row.id)"
                ></v-select>
              </v-col>
              <v-col cols="3">
                <v-select
                  :items="row.kategori"
                  label="Kategori TKJ"
                  item-text="nama"
                  item-value="id"
                  v-model="currentPeriode.kategori[row.id].kat_tkj_id"
                  @change="updateKategoriPeriode(row.id)"
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
        :items="kategori"
        :items-per-page="10"
        :search="search"
        :loading="isLoading"
        class="elevation-1"
      >
        <template v-slot:[`item.actions`]="{ item }">
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
          <span>Kategori</span>
          <v-spacer></v-spacer>
          <v-btn text class="mr-2" @click="bottomSheet = false">batal</v-btn>
          <v-btn color="#2C3E50" dark @click="submit">Simpan</v-btn>
        </v-card-title>
        <v-card-text>
          <vue-scroll :ops="scrollOps">
            <!-- <v-card> -->
            <v-card-text>
              <v-row align="center">
                <v-col cols="6">
                  <v-text-field
                    clearable
                    color="#2C3E50"
                    label="Nama Kategori"
                    hint="*Contoh : Sulit"
                    v-model="form.nama"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-select
                    :items="jurusan"
                    label="Jurusan"
                    item-text="nama"
                    item-value="id"
                    v-model="form.jurusan_id"
                  ></v-select>
                </v-col>
                <v-col cols="12">
                  <v-textarea
                    outlined
                    :items="form.deskripsi"
                    color="#2C3E50"
                    label="Deskripsi"
                    v-model="form.deskripsi"
                  ></v-textarea>
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
            Apakah anda yakin ingin menghapus kategori ini ?
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
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      search: "",
      kategori: [],
      jurusan: [],
      form: {},
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
          value: "nama",
        },
        { text: "Jurusan", value: "nama_jurusan" },
        { text: "Actions", value: "actions" },
      ],
    };
  },
  computed: {
    ...mapState([
      "isBottomSheetOpen",
      "currentPeriode",
      "urlKategori",
      "urlKategoriPeriode",
      "urlJurusan",
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
    currentPeriode(val) {
      if (!this.isEmptyObject(val)) {
        this.getJurusan();
        let kategori = [];
        val.kategori.forEach((element, index) => {
          kategori[element.jurusan_id] = _.clone(element);
        });
        val.kategori = kategori;
      }
    },
  },
  created() {
    this.getKategori();
    this.getCurrentPeriode();
  },
  methods: {
    ...mapActions(["getCurrentPeriode"]),
    ...mapMutations(["toggleBottomSheet"]),
    getKategori() {
      this.isLoading = true;
      axios
        .get(this.urlKategori)
        .then((response) => {
          this.kategori = response.data;
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
        .post(this.urlKategori, this.form)
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
          this.getKategori();
          this.getJurusan();
        });
    },
    update(id) {
      console.log(id);
      const urlKategori = `${this.urlKategori}/${id}`;
      this.isLoading = true;
      axios
        .put(urlKategori, this.form)
        .then((response) => {
          if (response.data.status) {
            this.bottomSheet = false;
            this.form = {};
            this.getKategori();
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
    destroy() {
      const id = this.form.id;
      const urlKategori = `${this.urlKategori}/${id}`;
      this.isLoading = true;
      axios
        .delete(urlKategori)
        .then((response) => {
          if (response.data.status) {
            this.dialogDelete = false;
            this.getKategori();
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
    updateKategoriPeriode(id) {
      const form = this.currentPeriode.kategori[id];
      this.isLoading = true;
      axios
        .post(this.urlKategoriPeriode, form)
        .then((response) => {
          if (response.data.status) {
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
    isEmptyObject(obj) {
      return _.isEmpty(obj);
    },
  },
};
</script>

<style>
</style>
