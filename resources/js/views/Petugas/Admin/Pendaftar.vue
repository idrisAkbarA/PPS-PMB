<template>
  <v-container>
    <p class="text-muted">Mengolola pendaftar pada Penerimaan Mahasiswa Baru</p>
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
        :items="pendaftar"
        :items-per-page="10"
        :search="search"
        :loading="isLoading"
        class="elevation-1"
      >
        <template v-slot:[`item.hp_wa`]="{ item }">
          {{ `${item.hp} / ${item.wa}` }}
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-btn icon x-small class="mr-2" title="Detail">
            <v-icon>mdi-information</v-icon>
          </v-btn>
          <v-btn
            icon
            x-small
            class="mr-2"
            title="Ubah Password"
            @click="edit(item)"
          >
            <v-icon>mdi-textbox-password</v-icon>
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
        </template></v-data-table
      ></v-card
    ></v-container
  >
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
          <span>Pendaftar</span>
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
    <!-- Dialog Delete -->
    <v-dialog v-model="dialogDelete" width="500">
      <v-card>
        <v-card-title class="headline">
          <v-icon>mdi-trash</v-icon>
        </v-card-title>

        <v-card-text>
          <p class="text-center">
            Apakah anda yakin ingin menghapus pendaftar ini ?
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
      pendaftar: [],
      form: {},
      isLoading: false,
      dialogDelete: false,
      urlPendaftar: "/api/pendaftar",
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
        { text: "Email", value: "email" },
        { text: "No HP/WA", value: "hp_wa" },
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
        this.urlPendaftar = "/api/pendaftar";
      }
    },
    dialogDelete(val) {
      if (!val) {
        this.form = {};
        this.urlPendaftar = "/api/pendaftar";
      }
    },
  },
  created() {
    this.getPendaftar();
  },
  methods: {
    ...mapMutations(["toggleBottomSheet"]),
    getPendaftar() {
      this.isLoading = true;
      axios
        .get(this.urlPendaftar)
        .then((response) => {
          this.pendaftar = response.data;
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
        .post(this.urlPendaftar, this.form)
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
  },
};
</script>

<style>
</style>
