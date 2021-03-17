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
          <v-btn
            icon
            x-small
            class="mr-2"
            title="Detail"
            @click="show(item.id)"
          >
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
    <!-- Dialog Show -->
    <v-dialog
      v-model="dialogShow"
      width="500"
      scrollable
    >
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
                  :src="form.pas_photo ? form.pas_photo : '/images/empty.png'"
                ></v-img>
              </v-col>
              <v-col cols="6">
                <h5>{{ form.nama }}</h5>
                <p class="text-muted">{{ form.email }}</p>
                <v-row class="mt-3">
                  <v-col
                    cols="6"
                    class="mb-0"
                  >HP / WA</v-col>
                  <v-col
                    cols="6"
                    class="mb-0"
                  >{{ form.hp ? form.hp : "-" }} /
                    {{ form.wa ? form.wa : "-" }}</v-col>
                  <v-col
                    cols="6"
                    class="mb-0"
                  >IPK</v-col>
                  <v-col
                    cols="6"
                    class="mb-0"
                  >{{ form.nilai_ipk ? form.nilai_ipk : "-" }}
                  </v-col>
                  <v-col
                    cols="6"
                    class="mb-0"
                  >Bahasa</v-col>
                  <v-col
                    cols="6"
                    class="mb-0"
                  >{{ form.nilai_bhs ? form.nilai_bhs : "-" }}
                  </v-col>
                  <v-col
                    cols="6"
                    class="mb-0"
                  >Alamat</v-col>
                  <v-col
                    cols="6"
                    class="mb-0"
                  >{{ form.alamat ? form.alamat : "-" }}
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="12">
                <v-list subheader>
                  <v-subheader>Pendaftaran</v-subheader>
                  <v-divider class="mt-0"></v-divider>
                  <template v-for="(ujian, index) in form.ujian">
                    <v-list-item
                      three-line
                      :key="index"
                    >
                      <v-list-item-content>
                        <v-list-item-title>{{
                          ujian.jurusan.nama
                        }}</v-list-item-title>
                        <v-list-item-subtitle>
                          Periode {{ ujian.nama_periode }}
                        </v-list-item-subtitle>
                        <v-list-item-subtitle>
                          <span :class="
                              ujian.lulus_at ? 'text-success' : 'text-danger'
                            ">
                            {{ ujian.lulus_at ? "Lulus" : "Tidak Lulus" }}
                            <v-icon
                              small
                              :color="ujian.lulus_at ? 'success' : 'red'"
                            >{{
                                ujian.lulus_at ? "mdi-check" : "mdi-close"
                              }}</v-icon>
                          </span>
                        </v-list-item-subtitle>
                      </v-list-item-content>
                    </v-list-item>
                    <v-divider
                      v-if="index < form.ujian.length - 1"
                      :key="'divider-' + index"
                    ></v-divider>
                  </template>
                  <v-divider></v-divider>
                </v-list>
              </v-col>
            </v-row>
          </vue-scroll>
        </v-card-text>
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
            Apakah anda yakin ingin menghapus pendaftar ini ?
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
      pendaftar: [],
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
        { text: "Email", value: "email" },
        { text: "No HP/WA", value: "hp_wa" },
        { text: "Actions", value: "actions" },
      ],
    };
  },
  computed: {
    ...mapState(["isBottomSheetOpen", "urlPendaftar"]),
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
    dialogShow(val) {
      if (!val) {
        this.form = {};
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
    show(id) {
      const urlPendaftar = `${this.urlPendaftar}/${id}`;
      axios
        .get(urlPendaftar)
        .then((response) => {
          if (response.data.status) {
            this.form = response.data.data;
            this.dialogShow = true;
          }
        })
        .catch((err) => {
          console.error(err);
          this.snackbar = {
            show: true,
            message: err,
            color: "danger",
          };
        });
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
