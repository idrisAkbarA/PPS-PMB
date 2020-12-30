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
          {{ item.kat_tka ? item.kat_tka.nama : "-" }}
        </template>
        <template v-slot:[`item.kategori_tkj`]="{ item }">
          {{ item.kat_tkj ? item.kat_tkj.nama : "-" }}
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-btn
            icon
            x-small
            class="mr-2"
            title="Tambah Kategori"
            @click="manageCategory(item)"
          >
            <v-icon>mdi-bookmark-plus</v-icon>
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
          <span>Jurusan</span>
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
                    label="Nama Jurusan"
                    hint="*Contoh : Pendidikan Agama Islam S3"
                    v-model="form.nama"
                  >
                  </v-text-field>
                </v-col>
                <v-col cols="6" v-if="form.id">
                  <v-select
                    :items="form.kategori"
                    label="Kategori TKA Default"
                    item-text="nama"
                    item-value="id"
                    v-model="form.kat_tka_default"
                  ></v-select>
                </v-col>
                <v-col cols="6" v-if="form.id">
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
    <!-- Dialog Create Category -->
    <v-dialog v-model="dialogCategory" width="500">
      <v-card>
        <v-card-title class="headline bg-white">
          <h5 class="text-muted">Kategari Soal {{ form.nama }}</h5>
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
          <v-alert dense outlined class="mb-0" style="height: 100px">
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
                  <v-icon right small>mdi-pencil</v-icon>
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
                      <v-btn color="red" dark @click="removeCategory(i)">
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
          <v-btn text @click="dialogCategory = false"> Batal </v-btn>
          <v-spacer></v-spacer>
          <v-btn color="#2C3E50" dark @click="submitCategory"> Simpan </v-btn>
        </v-card-actions>
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
            Apakah anda yakin ingin menghapus jurusan ini ?
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
      jurusan: [],
      editCategory: [],
      form: {},
      isLoading: false,
      dialogCategory: false,
      dialogDelete: false,
      urlJurusan: "/api/jurusan",
      urlKategori: "/api/kategori",
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
        { text: "Kategori TKA", value: "kategori_tka" },
        { text: "Kategori TKJ", value: "kategori_tkj" },
        { text: "Actions", value: "actions" },
      ],
    };
  },
  computed: {
    ...mapState(["isBottomSheetOpen"]),
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
        this.urlJurusan = "/api/jurusan";
      }
    },
    dialogDelete(val) {
      if (!val) {
        this.form = {};
        this.urlJurusan = "/api/jurusan";
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
        this.urlJurusan = "/api/jurusan";
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
      const form = {
        jurusan_id: this.form.id,
        categories: this.form.kategori,
      };

      this.isLoading = true;
      axios
        .post(this.urlKategori, form)
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
