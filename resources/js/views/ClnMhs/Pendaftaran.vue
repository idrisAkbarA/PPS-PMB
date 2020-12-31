<template>
  <!-- v-model="stepper" -->
  <v-stepper
    non-linear
    vertical
    v-model="stepper"
  >
    <v-stepper-step
      color="green"
      editable
      step="1"
      :complete="jurusanSelected?true:false"
    >
      Pilih Jurusan
      <small>Pilihlah Jurusan yang anda inginkan.</small>
    </v-stepper-step>

    <v-stepper-content step="1">
      <v-radio-group
        v-model="jurusanSelected"
        column
        @change="initUjian()"
      >
        <v-radio
          color="green"
          v-for="item in jurusan"
          :key="item.id"
          :label="item.nama"
          :value="item.id"
        ></v-radio>
      </v-radio-group>
      <v-btn
        class="text-white"
        color="green darken-3"
        :disabled="!jurusanSelected"
        @click="stepper = 2"
      >
        Selanjutnya
      </v-btn>
    </v-stepper-content>

    <v-stepper-step
      :editable="jurusanSelected?true:false"
      color="green"
      :complete="stepper > 2"
      step="2"
    >
      Isi Biodata
    </v-stepper-step>

    <v-stepper-content step="2">
      <v-container>
        <v-row>
          <v-text-field
            color="green"
            filled
            prepend-inner-icon="mdi-account"
            label="Nama Lengkap"
            v-model="user.nama"
          ></v-text-field>
        </v-row>
        <v-row>
          <v-text-field
            color="green"
            filled
            prepend-inner-icon="mdi-phone"
            label="No Telepon"
            v-model="user.hp"
          ></v-text-field>
        </v-row>
        <v-row>
          <v-text-field
            color="green"
            filled
            prepend-inner-icon="mdi-whatsapp"
            label="No Whatsapp"
            v-model="user.wa"
          ></v-text-field>
        </v-row>
        <v-row>
          <v-textarea
            rows="1"
            auto-grow
            color="green"
            filled
            prepend-inner-icon="mdi-map-marker"
            label="Alamat Rumah Lengkap"
            v-model="user.alamat"
          ></v-textarea>
        </v-row>
        <v-row>
          <v-text-field
            color="green"
            filled
            prepend-inner-icon="mdi-attachment"
            label="Scan Ijazah"
            v-model="user.ijazah"
          ></v-text-field>
        </v-row>
        <v-row>
          <v-col
            class="mr-1"
            style="padding: 0 !important"
          >
            <v-text-field
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Nilai IPK"
              v-model="user.nilai_ipk"
            ></v-text-field>
          </v-col>
          <v-col
            class="ml-1"
            style="padding: 0 !important"
          >
            <v-text-field
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Nilai Bahasa"
              v-model="user.nilai_bhs"
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-text-field
            color="green"
            filled
            prepend-inner-icon="mdi-attachment"
            label="Upload Pas Foto"
            v-model="user.nilai_bhs"
            @click="$refs.photoProfile.$refs.input.click()"
          ></v-text-field>
          <v-file-input
            hide-input
            ref="photoProfile"
            class="d-none"
            v-model="user.paspoto"
          ></v-file-input>
        </v-row>
      </v-container>
      <v-btn
        color="primary"
        @click="stepper = 3"
      >
        Continue
      </v-btn>
      <v-btn text>
        Cancel
      </v-btn>
    </v-stepper-content>

    <v-stepper-step
      editable
      color="green"
      :complete="stepper > 3"
      step="3"
    >
      Pembayaran
    </v-stepper-step>

    <v-stepper-content step="3">
      <v-card
        color="grey lighten-5"
        class="mb-12"
      >
        <v-card-title>Lakukan Pembayaran</v-card-title>
        <v-card-subtitle>Lakukan pembayaran untuk dapat mengikuti ujian masuk</v-card-subtitle>
        <v-card-text>
          <v-btn
            block
            large
            dark
            color="green darken-3"
          >Dapatkan Kode Pembayaran</v-btn>
        </v-card-text>
      </v-card>
      <v-btn
        color="primary"
        @click="stepper = 4"
      >
        Continue
      </v-btn>
      <v-btn text>
        Cancel
      </v-btn>
    </v-stepper-content>

    <v-stepper-step
      editable
      color="green"
      step="4"
    >
      Ujian
    </v-stepper-step>
    <v-stepper-content step="4">
      <v-card
        color="grey lighten-1"
        class="mb-12"
        height="200px"
      ></v-card>
      <v-btn
        color="primary"
        @click="stepper = 1"
      >
        Continue
      </v-btn>
      <v-btn text>
        Cancel
      </v-btn>
    </v-stepper-content>
    <v-stepper-step
      editable
      color="green"
      step="5"
    >
      Temu Ramah
    </v-stepper-step>
    <v-stepper-content step="5">
      <v-card
        color="grey lighten-1"
        class="mb-12"
        height="200px"
      ></v-card>
      <v-btn
        color="primary"
        @click="stepper = 1"
      >
        Continue
      </v-btn>
      <v-btn text>
        Cancel
      </v-btn>
    </v-stepper-content>
  </v-stepper>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  created() {
    if (!this.jurusan) {
      this.initAllDataClnMhs();
    }
  },
  methods: {
    ...mapActions(["initAllDataClnMhs"]),
    initUjian() {
      var periode_id = this.periode[0].id;
      var jurusan_id = this.jurusanSelected;
      var payload = { periode_id, jurusan_id };
      if (this.ujian_id) payload["ujian_id"] = this.ujian_id;
      axios.post("/api/ujian/init", payload).then((response) => {
        this.ujian_id = response.data.ujian_id;
        console.log(response.data);
      });
    },
    width() {
      if (this.windowWidth <= 600) {
        return "100%";
      } else if (this.windowWidth <= 960) {
        return "70%";
      } else {
        return "70%";
      }
    },
  },
  computed: {
    ...mapState(["jurusan", "user", "periode"]),
  },
  data() {
    return {
      ujian_id: null,
      jurusanSelected: null,
      stepper: 1,
      form: {},
    };
  },
};
</script>

<style>
</style>