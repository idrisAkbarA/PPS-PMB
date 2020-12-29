<template>
  <v-container fluid>
    <div style="height:340px">
      <h1 class="text-white mt-6">Selamat Datang <br> di Aplikasi Pendaftaran Pascasarjana
      </h1>
      <h4 class="text-white font-weight-light">Universitas Islam Negeri Sultan Syarif Kasim Riau</h4>
      <h4 class="mt-10 text-white font-weight-light">Lihat panduan pendaftaran
        <a
          href="#"
          class="font-weight-black text-white"
          style="text-decoration:none"
        >disini</a>. Silahkan mendaftar!
      </h4>
    </div>
    <div>
      <h4 class="font-weight-light">Pendaftaran anda</h4>
    </div>
    <v-container style="padding:0px !important">
      <v-row>
        <v-col cols="12">
          <v-card
            class="bg-with-overlay"
            width="100%"
            flat
            outlined
            color="green darken-2"
          >
            <v-container>
              <v-row
                align="center"
                justify="center"
              >
                <v-col
                  cols="12"
                  md="6"
                  lg="6"
                >
                  <div class="ml-10">
                    <h1 class="text-white">Hmm.. sepertinya anda belum mendaftar</h1>
                    <v-btn large>Daftar Sekarang</v-btn>
                  </div>
                </v-col>
                <v-col
                  cols="12"
                  md="6"
                  lg="6"
                >
                  <v-img
                    class="mx-auto"
                    max-width="300"
                    :src="'/images/empty.png'"
                  ></v-img>

                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    <!-- <v-sheet color="transparent">
      <h2 class="mx-auto text-center">Selamat Datang</h2>
    </v-sheet>
    <v-card
      class="mt-5 mx-auto"
      elevation="10"
      :width="width()"
    >
      <v-card-title>Isi Biodata</v-card-title>
      <v-card-subtitle>Isilah biodata anda dengan benar</v-card-subtitle>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col
              cols="12"
              sm="12"
              md="6"
              lg="6"
            >
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
                <v-file-input
                  hide-input
                  ref="photoProfile"
                  class="d-none"
                  v-model="form.paspoto"
                ></v-file-input>
              </v-row>
            </v-col>
            <v-col
              cols="12"
              sm="12"
              md="6"
              lg="6"
            >
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
                  <v-col
                    class="mr-1"
                    style="padding: 0 !important"
                  >
                    <v-text-field
                      outlined
                      color="green"
                      prepend-inner-icon="mdi-attachment"
                      label="Nilai IPK"
                      v-model="form.nilai_ipk"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    class="ml-1"
                    style="padding: 0 !important"
                  >
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
    <v-card
      class="mt-5 mx-auto"
      elevation="10"
      :width="width()"
    >
      <v-card-title>Pendaftaran Ujian Masuk</v-card-title>
      <v-card-subtitle>Lakukan pendaftaran</v-card-subtitle>
    </v-card>
    <v-card
      class="mt-5 mx-auto"
      elevation="10"
      :width="width()"
    >
      <v-card-title>Tentukan Jadwal Temu Ramah</v-card-title>
      <v-card-subtitle>Pilih Jadwal Temu Ramah</v-card-subtitle>
    </v-card> -->
  </v-container>
</template>

<script>
export default {
  created() {
    this.getUser();
  },
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
    },
    getUser() {
      axios
        .get(`/api/user/cln_mahasiswa`)
        .then((response) => {
          console.log(response);
        })
        .catch((error) => console.error(error));
    },
  },
  data() {
    return {
      item: null,
      form: {},
    };
  },
};
</script>

<style>
.bg-with-overlay {
  background: rgb(0, 36, 15);
  background: linear-gradient(
      0deg,
      rgb(5, 94, 42) 0%,
      rgba(6, 76, 90, 0.377) 100%
    ),
    url("/images/bg.jpg");
  background-size: contain;
}
</style>
