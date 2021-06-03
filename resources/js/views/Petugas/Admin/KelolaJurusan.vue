<template>
  <v-container>
    <p class="text-muted">
      Mengolola jurusan dan kategori soal jurusan pada Penerimaan Mahasiswa Baru
    </p>
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
        :items="jurusan"
        :items-per-page="10"
        :search="search"
        :loading="isLoading"
        class="elevation-1"
      >
        <template v-slot:[`item.kategori_tka`]="{ item }">
          <span v-if="item.komposisi_tka_default">
            <ul>
              <li
                v-for="(row, index) in item.komposisi_tka_default"
                :key="index"
              >
                {{ row.nama_kategori }} : {{ row.jumlah }} Soal
              </li>
            </ul>
          </span>
        </template>
        <template v-slot:[`item.kategori_tkj`]="{ item }">
          <span v-if="item.komposisi_tkj_default">
            <ul>
              <li
                v-for="(row, index) in item.komposisi_tkj_default"
                :key="index"
              >
                {{ row.nama_kategori }} : {{ row.jumlah }} Soal
              </li>
            </ul>
          </span>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-btn
            icon
            x-small
            class="mr-2"
            title="Edit Kategori"
            @click="manageCategory(item)"
          >
            <v-icon>mdi-bookmark-plus</v-icon>
          </v-btn>
          <v-btn
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
                  <p class="overline text-muted mb-0">Komposisi TKA Default</p>
                  <v-row
                    align="center"
                    v-for="(row, index) in form.kategori"
                    :key="index"
                  >
                    <v-col cols="6">{{ row.nama }}</v-col>
                    <v-col cols="6">
                      <v-text-field
                        dense
                        type="number"
                        color="#2C3E50"
                        v-model="row.jumlah_tka"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>
                <!-- <v-divider vertical></v-divider> -->
                <v-col
                  cols="6"
                  v-if="form.id"
                >
                  <p class="overline text-muted mb-0">Komposisi TKJ Default</p>
                  <v-row
                    align="center"
                    v-for="(row, index) in form.kategori"
                    :key="index"
                  >
                    <v-col cols="6">{{ row.nama }}</v-col>
                    <v-col cols="6">
                      <v-text-field
                        dense
                        type="number"
                        color="#2C3E50"
                        v-model="row.jumlah_tkj"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
              <v-divider>

              </v-divider>
              <v-row class="px-3">
                <v-row
                  align="center"
                  justify="center"
                >
                  <v-col cols="6">
                    <p class="overline text-muted mb-0">Kuota per Kelas Default</p>
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
            </v-card-text>
            <!-- </v-card> -->
          </vue-scroll>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <!-- Dialog Create Category -->
    <v-dialog
      v-model="dialogCategory"
      width="500"
    >
      <v-card>
        <v-card-title class="headline bg-white">
          <h5 class="text-muted">Kategori Soal {{ form.nama }}</h5>
          <v-spacer></v-spacer>
          <v-card-actions>
            <v-btn
              small
              color="green darken-3"
              title="Tambah Kategori"
              @click="addCategory"
            >
              <v-icon color="white">mdi-plus</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card-title>

        <v-card-text class="pb-0">
          <v-alert
            dense
            outlined
            class="mb-0"
            style="height: 100px"
          >
            <v-menu
              v-model="editCategory[i]"
              bottom
              right
              transition="scale-transition"
              :close-on-content-click="false"
              origin="top left"
              v-for="(category, i) in form.kategori"
              :key="i"
            >
              <template v-slot:activator="{ on }">
                <v-chip
                  pill
                  color="teal"
                  class="ma-1"
                  text-color="white"
                  :title="category.deskripsi"
                  v-on="on"
                >
                  {{ category.nama }}
                  <v-icon
                    right
                    small
                  >mdi-pencil</v-icon>
                </v-chip>
              </template>
              <v-card width="300">
                <v-list>
                  <v-list-item>
                    <v-list-item-content>
                      <v-text-field
                        color="#2C3E50"
                        class="mb-2"
                        label="Nama Kategori"
                        hint="*Contoh : Mudah"
                        v-model="category.nama"
                      >
                      </v-text-field>
                      <v-textarea
                        outlined
                        v-model="category.deskripsi"
                        label="Deskripsi"
                      ></v-textarea>
                      <v-btn
                        color="red"
                        dark
                        @click="removeCategory(i)"
                      >
                        <v-icon class="mr-2">mdi-trash-can</v-icon>
                        Hapus
                      </v-btn>
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
              </v-card>
            </v-menu>
          </v-alert>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-btn
            text
            @click="dialogCategory = false"
          > Batal </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="#2C3E50"
            dark
            @click="submitCategory"
          > Simpan </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
      jurusan: [],
      editCategory: [],
      form: {},
      isLoading: false,
      dialogCategory: false,
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
        { text: "TKA Default", value: "kategori_tka" },
        { text: "TKJ Default", value: "kategori_tkj" },
        {
          text: "Kuota per Kelas Default",
          value: "kuota_kelas_default",
        },
        {
          text: "Nominal Bayar Default",
          value: "nominal_bayar_default",
        },
        { text: "Actions", value: "actions" },
      ],
    };
  },
  computed: {
    ...mapState(["isBottomSheetOpen", "urlJurusan", "urlKategori"]),
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
    dialogCategory(val) {
      if (val) {
        const categories = this.form.kategori;
        categories.forEach((element, index) => {
          this.editCategory[index] = false;
        });
      } else {
        this.form = {};
      }
    },
  },
  created() {
    this.getJurusan();
  },
  methods: {
    ...mapMutations(["toggleBottomSheet"]),
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
      this.form.kategori.forEach((el) => {
        let kategori = item.komposisi_tka_default.filter((elem) => {
          return elem.kategori_id == el.id;
        })[0];
        el.jumlah_tka = kategori ? kategori.jumlah : 0;

        kategori = item.komposisi_tkj_default.filter((elem) => {
          return elem.kategori_id == el.id;
        })[0];
        el.jumlah_tkj = kategori ? kategori.jumlah : 0;
      });
      this.bottomSheet = true;
    },
    submit() {
      const form = this.form;
      this.form.komposisi_tka_default = this.form.kategori;
      this.form.komposisi_tkj_default = this.form.kategori;
      if (!form.id) {
        this.store();
        return;
      }
      this.update(form.id);
    },
    store() {
      this.isLoading = true;
      axios
        .post(this.urlJurusan, this.form)
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
      const urlJurusan = `${this.urlJurusan}/${id}`;
      this.isLoading = true;
      axios
        .put(urlJurusan, this.form)
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
    manageCategory(item) {
      this.form = _.cloneDeep(item);
      this.dialogCategory = true;
    },
    addCategory() {
      let categories = this.form.kategori;
      categories.push({});
      this.form.kategori = categories;
      this.editCategory.push(true);
    },
    removeCategory(i) {
      let categories = this.form.kategori;
      categories.splice(i, 1);
      this.form.kategori = categories;
      this.editCategory[i] = false;
    },
    submitCategory() {
      const urlKategori = `${this.urlKategori}/${this.form.id}`;
      const form = {
        categories: this.form.kategori,
      };

      this.isLoading = true;
      axios
        .post(urlKategori, form)
        .then((response) => {
          if (response.data.status) {
            this.dialogCategory = false;
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
  },
};
</script>

<style>
</style>
