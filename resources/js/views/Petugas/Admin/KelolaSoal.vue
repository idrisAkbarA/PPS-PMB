<template>
  <v-container>
    Kelola Soal dan hal terkait
    <v-card>
      <v-tabs
        v-model="tab"
        background-color="green"
        centered
        dark
      >
        <v-tabs-slider></v-tabs-slider>

        <v-tab href="#tab-1">
          SOAL TKA

        </v-tab>

        <v-tab href="#tab-2">
          SOAL TKD

        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item value="tab-1">
          <!-- ttka -->
          <v-card flat>
            <v-card-text>
              <v-container>
                <v-row class="pl-8 pr-8">
                  <v-text-field
                    prepend-inner-icon="mdi-magnify"
                    clearable
                    label="Pencarian"
                    color="#2C3E50"
                  ></v-text-field>
                </v-row>
                <v-card
                  v-for="soal in soalTKA"
                  :key="soal.id"
                  color="rgba(46, 204, 113, 0.25)"
                  class="mt-5"
                >
                  <v-card-title>
                    <v-chip
                      color="#2C3E50"
                      dark
                    >
                      No. 1
                    </v-chip>
                    <v-spacer></v-spacer>
                    <v-select
                      filled
                      :full-width="false"
                      dense
                      hide-details="auto"
                    ></v-select>
                    <v-btn
                      color="#2C3E50"
                      dark
                      class="rounded-0"
                    >
                      <v-icon small>mdi-pencil</v-icon>
                    </v-btn>
                    <v-btn
                      color="#2C3E50"
                      dark
                      class="rounded-0"
                    >
                      <v-icon small>mdi-delete</v-icon>
                    </v-btn>
                  </v-card-title>
                  <v-card-text class="black--text">
                    {{soal.pertanyaan}}
                    <v-radio-group
                      hide-details="auto"
                      v-model="soal.jawaban"
                    >
                      <v-radio
                        v-for="(pilihan) in soal.pilihan_ganda"
                        :key="pilihan.pilihan"
                        :value="pilihan.pilihan"
                        :label="pilihan.text"
                        color="#2C3E50"
                      ></v-radio>
                    </v-radio-group>
                  </v-card-text>
                </v-card>
              </v-container>
            </v-card-text>
          </v-card>
        </v-tab-item>
        <v-tab-item value="tab-2">
          <v-card flat>
            <v-card-text>huy</v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
    <v-bottom-sheet
      scrollable
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="toggleDialogSoal"
    >
      <v-card color="#ecf0f1">
        <v-card-title> <span>Buat Soal</span>
          <v-spacer></v-spacer>
          <v-btn text>batal</v-btn>
          <v-btn
            color="#2C3E50"
            dark
          >Simpan</v-btn>
          <!-- v-model="" -->
        </v-card-title>
        <v-card
          color="rgba(46, 204, 113, 0.25)"
          class="ma-2"
          style="padding-bottom:0"
        >
          <v-card-text>
            <v-text-field
              clearable
              color="#2C3E50"
            >
              <template v-slot:label>
                <div class="black--text">Pertanyaan</div>
              </template>
            </v-text-field>
            <v-radio
              value="n"
              color="#2C3E50"
            >
              <template v-slot:label>
                <div class="black--text">Definitely Duckduckgo</div>
              </template>
            </v-radio>
            <v-btn
              class="mt-2 grey darken-3"
              fab
              dark
              small
            >
              <v-icon dark>
                mdi-plus
              </v-icon>
            </v-btn>
          </v-card-text>
        </v-card>
        <v-row justify="center">
          <v-btn
            class="mt-2"
            fab
            dark
            small
            color="#2C3E50"
          >
            <v-icon dark>
              mdi-plus
            </v-icon>
          </v-btn>
        </v-row>
      </v-card>
    </v-bottom-sheet>
  </v-container>
</template>

<script>
import { mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      tab: null,
      soalTKA: [
        {
          id: 0,
          pertanyaan: "Ma Rabbuka?",
          pilihan_ganda: [
            { pilihan: "A", text: "Allah" },
            { pilihan: "B", text: "Allah" },
          ],
          jawaban: "A",
          jurusan_id: 0,
          kategori_id: 0,
          type: "tka",
        },
        {
          id: 0,
          pertanyaan: "Ma Rabbukaa?",
          pilihan_ganda: [
            { pilihan: "A", text: "Allah" },
            { pilihan: "B", text: "Allah" },
          ],
          jawaban: "A",
          jurusan_id: 0,
          kategori_id: 0,
          type: "tka",
        },
      ],
    };
  },
  computed: {
    ...mapState(["isTambahSoal"]),
    toggleDialogSoal: {
      get: function () {
        return this.isTambahSoal;
      },
      set: function (data) {
        this.toggleTambahSoal(data);
      },
    },
  },
  methods: {
    ...mapMutations(["toggleTambahSoal"]),
  },
};
</script>

<style>
</style>