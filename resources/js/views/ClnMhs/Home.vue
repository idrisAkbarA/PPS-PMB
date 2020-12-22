<template>
  <v-container>
    <v-sheet color="transparent">
      <h2 class="mx-auto text-center text-white">Selamat Datang</h2>
    </v-sheet>
    <v-card class="mt-5 mx-auto" elevation="10" :width="width()">
      <v-card-title>Isi Biodata</v-card-title>
      <v-card-subtitle>Isilah biodata anda dengan benar</v-card-subtitle>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12" sm="12" md="6" lg="6">
              <v-row>
                <v-img
                  class="mx-auto"
                  :width="300*0.7"
                  :height="400*0.7"
                  :max-width="300*0.7"
                  :max-height="400*0.7"
                  :src="'https://picsum.photos/200/300?random=5'"
                ></v-img>
              </v-row>
              <v-row>
                <v-btn
                  max-width="400"
                  class="mt-2 mx-auto"
                  dark
                  color="green"
                  @click="$refs.photoProfile.$refs.input.click()"
                >
                  <v-icon>mdi-pencil</v-icon>
                  <span>Upload Pas Foto</span>
                </v-btn>
                <v-file-input hide-input ref="photoProfile" class="d-none" v-model="form.paspoto"></v-file-input>
              </v-row>
            </v-col>
            <v-col cols="12" sm="12" md="6" lg="6">
              <v-container>
                <v-row>
                  <v-text-field
                    outlined
                    color="green"
                    prepend-inner-icon="mdi-account"
                    label="Nama Lengkap"
                    v-model="form.nama"
                  ></v-text-field>
                </v-row>
                <v-row>
                  <v-text-field
                    outlined
                    color="green"
                    prepend-inner-icon="mdi-phone"
                    label="No Telepon"
                    v-model="form.hp"
                  ></v-text-field>
                </v-row>
                <v-row>
                  <v-text-field
                    outlined
                    color="green"
                    prepend-inner-icon="mdi-whatsapp"
                    label="No Whatsapp"
                    v-model="form.wa"
                  ></v-text-field>
                </v-row>
                <v-row>
                  <v-textarea
                    rows="1"
                    auto-grow
                    outlined
                    color="green"
                    prepend-inner-icon="mdi-map-marker"
                    label="Alamat Rumah Lengkap"
                    v-model="form.alamat"
                  ></v-textarea>
                </v-row>
                <v-row>
                  <v-text-field
                    outlined
                    color="green"
                    prepend-inner-icon="mdi-attachment"
                    label="Scan Ijazah"
                    v-model="form.ijazah"
                  ></v-text-field>
                </v-row>
                <v-row>
                  <v-col class="mr-1" style="padding: 0 !important">
                    <v-text-field
                      outlined
                      color="green"
                      prepend-inner-icon="mdi-attachment"
                      label="Nilai IPK"
                      v-model="form.nilai_ipk"
                    ></v-text-field>
                  </v-col>
                  <v-col class="ml-1" style="padding: 0 !important">
                    <v-text-field
                      outlined
                      color="green"
                      prepend-inner-icon="mdi-attachment"
                      label="Nilai Bahasa"
                      v-model="form.nilai_bhs"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
    <v-card class="mt-5 mx-auto" elevation="10" :width="width()">
      <v-card-title>Pendaftaran Ujian Masuk</v-card-title>
      <v-card-subtitle>Lakukan pendaftaran</v-card-subtitle>
    </v-card>
    <v-card class="mt-5 mx-auto" elevation="10" :width="width()">
      <v-card-title>Tentukan Jadwal Temu Ramah</v-card-title>
      <v-card-subtitle>Pilih Jadwal Temu Ramah</v-card-subtitle>
    </v-card>
  </v-container>
</template>

<script>
export default {
  methods: {
    link() {},
    width() {
      if (this.windowWidth <= 600) {
        return "100%";
      } else if (this.windowWidth <= 960) {
        return "70%";
      } else {
        return "70%";
      }
    }
  },
  data() {
    return {
      item: null,
      form: {}
    };
  }
};
</script>

<style>
</style>
