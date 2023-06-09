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
          <span>Setting Ujian</span>
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
                <v-col
                  cols="12"
                  class="pb-0"
                >
                  <p class="overline text-muted mb-0">Setting Ujian</p>
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
    <!-- Dialog Show -->
    <v-dialog
      v-model="dialogShow"
      width="500"
    >
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
                    IPK: {{ form.syarat_ipk }}, Bahasa: {{ form.syarat_bhs }}
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
                  <td>Wawancara</td>
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
            Apakah anda yakin ingin menghapus periode ini ?
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
          scrollingX: false
        },
        vuescroll: {
          mode: "native",
          wheelScrollDuration: 0,
          locking: true
        }
      },
      headers: [
        {
          text: "Nama",
          align: "start",
          value: "nama"
        },
        { text: "Periode", value: "periode" },
        { text: "Status", value: "is_active" },
        { text: "Actions", value: "actions" }
      ]
    };
  },
  computed: {
    ...mapState(["isBottomSheetOpen", "urlPeriode"]),
    bottomSheet: {
      get: function() {
        return this.isBottomSheetOpen;
      },
      set: function(data) {
        this.toggleBottomSheet(data);
      }
    }
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
    }
  },
  created() {
    this.getPeriode();
  },
  methods: {
    ...mapMutations(["toggleBottomSheet"]),
    getPeriode() {
      this.isLoading = true;
      axios
        .get(this.urlPeriode)
        .then(response => {
          this.periode = response.data;
        })
        .catch(err => {
          console.error(err);
        })
        .then((this.isLoading = false));
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
        .then(response => {
          if (response.data.status) {
            this.bottomSheet = false;
            this.snackbar = {
              show: true,
              message: response.data.message
            };
          }
        })
        .catch(err => {
          console.error(err);
          this.snackbar = {
            show: true,
            message: err,
            color: "danger"
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
        .then(response => {
          if (response.data.status) {
            this.bottomSheet = false;
            this.form = {};
            this.snackbar = {
              show: true,
              message: response.data.message
            };
            this.getPeriode();
          }
        })
        .catch(err => {
          console.error(err);
          this.snackbar = {
            show: true,
            message: err,
            color: "danger"
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
        .then(response => {
          if (response.data.status) {
            this.dialogDelete = false;
            this.getPeriode();
            this.snackbar = {
              show: true,
              message: response.data.message
            };
          }
        })
        .catch(err => {
          console.error(err);
          this.snackbar = {
            show: true,
            message: err,
            color: "danger"
          };
        })
        .then((this.isLoading = false));
    }
  }
};
</script>

<style>
</style>
