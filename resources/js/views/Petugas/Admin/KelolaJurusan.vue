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
        :items-per-page="15"
        :search="search"
        :loading="isLoading"
        class="elevation-1"
      >
        <template v-slot:[`item.is_active`]="{ item }">
          <v-chip :color="item.is_active ? 'green' : 'red'" class="px-4" dark>
            {{ item.is_active ? "Aktif" : "Non-Aktif" }}
          </v-chip>
        </template>
        <template v-slot:[`item.periode`]="{ item }">
          {{ `${item.awal_periode} - ${item.akhir_periode}` }}
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-btn icon x-small class="mr-2" title="Detail">
            <v-icon>mdi-information</v-icon>
          </v-btn>
          <v-btn icon x-small class="mr-2" title="Edit" @click="edit(item)">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <v-btn icon x-small class="mr-2" title="Hapus">
            <v-icon>mdi-delete</v-icon>
          </v-btn>
        </template>
      </v-data-table>
    </v-card>
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
        <v-card
          color="rgba(46, 204, 113, 0.25)"
          class="ma-2"
          style="padding-bottom: 0"
        >
          <vue-scroll :ops="scrollOps">
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
                      :allowed-dates="allowedDateAkhir"
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
                <v-col cols="3">
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
                <v-col cols="3">
                  <v-text-field
                    type="number"
                    color="#2C3E50"
                    min="0"
                    label="Bahasa"
                    v-model="form.syarat_bhs"
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
                    hint="*Nilai dalam angka 1-10"
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
                    hint="*Nilai dalam angka 1-10"
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
                    v-model="form.range_ujian"
                  >
                  </v-text-field>
                </v-col>
              </v-row>
            </v-card-text>
          </vue-scroll>
        </v-card>
      </v-card>
    </v-bottom-sheet>
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
      urlPeriode: "/api/periode",
      scrollOps: {
        scrollPanel: {
          easing: "easeInQuad",
          speed: 800,
          scrollingX: false,
        },
        vuescroll: {
          wheelScrollDuration: 0,
          wheelDirectionReverse: true,
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
      }
    },
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
        .then((response) => {
          this.periode = response.data;
        })
        .catch((err) => {
          console.error(err);
        })
        .then((this.isLoading = false));
    },
    allowedDateAkhir(val) {
      return val >= this.form.awal_periode;
    },
    edit(item) {
      this.form = _.clone(item);
      this.urlPeriode = `${this.urlPeriode}/${this.form.id}`;
      this.bottomSheet = true;
    },
    submit() {
      const form = this.form;
      this.isLoading = true;
      axios
        .post(this.urlPeriode, form)
        .then((response) => {
          console.log(response);
        })
        .catch((err) => {
          console.error(err);
        })
        .then((this.isLoading = false));
    },
  },
};
</script>

<style>
</style>
