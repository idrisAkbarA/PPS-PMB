<template>
  <v-container>
    <p class="text-muted">
      Mengolola akun petugas pada aplikasi Penerimaan Mahasiswa Baru
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
        :items="petugas"
        :items-per-page="10"
        :search="search"
        :loading="isLoading"
        class="elevation-1"
      >
        <template v-slot:[`item.role`]="{ item }">
          <v-chip outlined class="ma-2" :color="roles[item.role - 1].color">
            {{ roles[item.role - 1].nama }}
          </v-chip>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-btn icon x-small class="mr-2" title="Detail" @click="show(item)">
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
          <span>Petugas</span>
          <v-spacer></v-spacer>
          <v-btn text class="mr-2" @click="bottomSheet = false">batal</v-btn>
          <v-btn color="#2C3E50" dark @click="submit">Simpan</v-btn>
        </v-card-title>
        <v-card-text>
          <v-card-text>
            <v-row align="center" v-if="!form.id">
              <v-col cols="6">
                <v-text-field
                  color="#2C3E50"
                  label="Nama Petugas"
                  v-model="form.nama"
                >
                </v-text-field>
              </v-col>
              <v-col cols="6">
                <v-select
                  :items="roles"
                  label="Role"
                  item-text="nama"
                  item-value="id"
                  v-model="form.role"
                ></v-select>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  color="#2C3E50"
                  label="Username"
                  hint="username digunakan untuk login"
                  v-model="form.username"
                >
                </v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  type="password"
                  color="#2C3E50"
                  label="Password"
                  hint="password digunakan untuk login"
                  v-model="form.password"
                >
                </v-text-field>
              </v-col>
            </v-row>
            <v-row v-else>
              <v-col cols="6">
                <v-text-field
                  type="password"
                  color="#2C3E50"
                  label="Password Baru"
                  v-model="form.password"
                >
                </v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  type="password"
                  color="#2C3E50"
                  label="Konfirmasi Password"
                  hint="konfirmasi password baru"
                  v-model="form.password2"
                >
                </v-text-field>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <!-- Dialog Info -->
    <!-- <v-dialog v-model="dialogShow" width="500" v-if="dialogShow">
      <v-card>
        <v-card-title class="headline">
          <v-icon>mdi-trash</v-icon>
        </v-card-title>

        <v-card-text>
          <v-row>
            <v-col cols="6">Periode </v-col>
            <v-col cols="6">{{ currentPeriode.nama }}</v-col>
            <v-col cols="6">Waktu Tamu Ramah </v-col>
            <v-col cols="6">
              {{
                `${currentPeriode.awal_temu_ramah} - ${currentPeriode.akhir_temu_ramah}`
              }}
            </v-col>
            <v-col cols="12">
              <v-subheader>Calon Mahasiswa</v-subheader>
              <v-divider class="my-0"></v-divider>
              <v-list
                subheader
                v-if="selectedTamuRamah.calon_mahasiswa.length > 0"
              >
                <template
                  v-for="(row, index) in selectedTamuRamah.calon_mahasiswa"
                >
                  <v-list-item two-line :key="index">
                    <v-list-item-content>
                      <v-list-item-title>{{ row.nama }}</v-list-item-title>
                      <v-list-item-subtitle> Jurusan </v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                  <v-divider
                    v-if="index < selectedTamuRamah.calon_mahasiswa.length - 1"
                    :key="'divider-' + index"
                    class="my-0"
                  ></v-divider>
                </template>
                <v-divider class="mt-0"></v-divider>
              </v-list>
              <p class="text-center mt-2" v-else>
                Belum ada peserta temu ramah
              </p>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-dialog> -->
    <!-- Dialog Delete -->
    <v-dialog v-model="dialogDelete" width="500">
      <v-card>
        <v-card-title class="headline">
          <v-icon>mdi-trash</v-icon>
        </v-card-title>

        <v-card-text>
          <p class="text-center">
            Apakah anda yakin ingin menghapus petugas ini ?
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
      petugas: [],
      form: {},
      selectedPetugas: null,
      isLoading: false,
      dialogShow: false,
      dialogDelete: false,
      snackbar: { show: false },
      roles: [
        { id: 1, nama: "Administrator", color: "success" },
        { id: 2, nama: "Petugas Temu Ramah", color: "purple" },
      ],
      headers: [
        {
          text: "Nama",
          align: "start",
          value: "nama",
        },
        { text: "Username", value: "username" },
        { text: "Role", value: "role" },
        { text: "Actions", value: "actions" },
      ],
    };
  },
  computed: {
    ...mapState(["isBottomSheetOpen", "urlPetugas"]),
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
    this.getPetugas();
  },
  methods: {
    ...mapMutations(["toggleBottomSheet"]),
    getPetugas() {
      this.isLoading = true;
      axios
        .get(this.urlPetugas)
        .then((response) => {
          this.petugas = response.data;
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
      this.selectedPetugas = item;
      this.dialogShow = true;
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
        .post(this.urlPetugas, this.form)
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
          this.getPetugas();
        });
    },
    update(id) {
      const form = this.form;
      if (form.password != form.password2) {
        this.snackbar = {
          show: true,
          message: "Konfirmasi Password tidak sesuai",
          color: "red",
        };
        return;
      }
      const urlPetugas = `${this.urlPetugas}/${id}`;
      this.isLoading = true;
      axios
        .put(urlPetugas, form)
        .then((response) => {
          if (response.data.status) {
            this.bottomSheet = false;
            this.form = {};
            this.snackbar = {
              show: true,
              message: response.data.message,
            };
            this.getPetugas();
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
      const urlPetugas = `${this.urlPetugas}/${id}`;
      this.isLoading = true;
      axios
        .delete(urlPetugas)
        .then((response) => {
          if (response.data.status) {
            this.dialogDelete = false;
          }
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
          this.getPetugas();
        });
    },
  },
};
</script>

<style>
</style>
