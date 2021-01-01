<template>
  <!-- v-model="stepper" -->
  <v-sheet
    class="mx-auto"
    :width="width()"
  >

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
        <strong>Pilihlah Jurusan yang anda inginkan.</strong>
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
        :rules="ruleBiodata"
      >
        Isi Biodata
        <strong
          class="text-red"
          v-if="jurusanSelected?false:true"
        >Isi jurusan terlebih dahulu.</strong>
      </v-stepper-step>

      <v-stepper-content step="2">
        <v-container>
          <v-row>
            <v-text-field
              color="green"
              filled
              @change="updateUser(user)"
              prepend-inner-icon="mdi-account"
              label="Nama Lengkap"
              v-model="user.nama"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              @change="updateUser(user)"
              prepend-inner-icon="mdi-phone"
              label="No Telepon"
              v-model="user.hp"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              @change="updateUser(user)"
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
              @change="updateUser(user)"
              prepend-inner-icon="mdi-map-marker"
              label="Alamat Rumah Lengkap"
              v-model="user.alamat"
            ></v-textarea>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              @change="updateUser(user)"
              prepend-inner-icon="mdi-attachment"
              label="Scan Ijazah Terakhir"
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
                @change="updateUser(user)"
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
                @change="updateUser(user)"
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
              readonly
              @click="$refs.photoProfile.$refs.input.click()"
            ></v-text-field>
            <!-- @change="updateUser(user)" -->
            <v-file-input
              @change="setPhoto()"
              hide-input
              ref="photoProfile"
              class="d-none"
              v-model="photoFile"
            ></v-file-input>
          </v-row>
        </v-container>
        <v-btn
          :disabled="isBiodataFilled?false:true"
          color="green darken-2"
          class="text-white"
          @click="stepper = 3"
        >
          Selanjutnya
        </v-btn>
      </v-stepper-content>

      <v-stepper-step
        :editable="isBiodataFilled?true:false"
        color="green"
        :complete="stepper > 3"
        step="3"
        :rules="rulePembayaran"
      >
        Pembayaran
        <strong
          class="text-red"
          v-if="isBiodataFilled?false:true"
        >Lengkapi biodata terlebih dahulu.</strong>
      </v-stepper-step>

      <v-stepper-content step="3">
        <v-card
          color="grey lighten-5"
          class="mb-12 ml-2 mt-2 mr-2"
          elevation="5"
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
          :disabled="isPembayaranLunas?false:true"
          color="green darken-2"
          class="text-white"
          @click="stepper = 4"
        >
          Selanjutnya
        </v-btn>
      </v-stepper-content>

      <v-stepper-step
        :editable="isPembayaranLunas"
        color="green"
        step="4"
        :rules="ruleUjian"
      >
        Ujian
        <strong
          class="text-red"
          v-if="isPembayaranLunas?false:true"
        >Lakukan pembayaran terlebih dahulu.</strong>
      </v-stepper-step>
      <v-stepper-content step="4">
        <v-card
          color="grey lighten-2"
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
        :editable="isLulusUjian?true:false"
        color="green"
        step="5"
        :rules="ruleTemuRamah"
      >
        Temu Ramah
        <strong
          class="text-red"
          v-if="isLulusUjian?false:true"
        >Anda dapat masuk pada tahap Temu Ramah setelah lulus ujian TKA dan TKJ.</strong>
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
    <v-bottom-sheet
      eager
      overlay-color="green darken-4"
      v-model="loadingSheet.toggle"
      inset
    >
      <v-card tile>
        <v-progress-linear
          :value="progress"
          class="my-0"
          :height="5"
        ></v-progress-linear>

        <v-list>
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>{{this.loadingSheet.message}}</v-list-item-title>
              <v-list-item-subtitle>{{this.progress+"%"}}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card>
    </v-bottom-sheet>

  </v-sheet>
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
    ...mapActions(["initAllDataClnMhs", "updateUser"]),
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
    setPhoto() {
      this.progress = 0;
      this.loadingSheet.toggle = true;
      this.loadingSheet.message = "Mengupload File Photo...";
      var data = new FormData();
      data.append("file", this.photoFile);
      data.append("methodName", "savePhotoPath");
      this.upload(data, this).then((response) => {
        console.log(response.data);
        this.loadingSheet.message = "File berhasil di upload";
        setTimeout(() => {
          this.loadingSheet.toggle = false;
        }, 1500);
      });
    },
    upload: async (data, ini) => {
      return axios({
        method: "post",
        url: "/api/user/store-file",
        onUploadProgress: (progressEvent) => {
          var percentCompleted = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          );
          ini.progress = percentCompleted;
        },
        data,
      });
    },
    width() {
      if (this.windowWidth <= 600) {
        return "100%";
      } else if (this.windowWidth <= 960) {
        return "70%";
      } else {
        return "50%";
      }
    },
  },
  computed: {
    ...mapState(["jurusan", "user", "periode"]),
    PhotoFileName: function () {},
  },
  watch: {
    user: {
      deep: true,
      handler: function (v) {
        Object.keys(v).every((element) => {
          if (element == "email_verified_at") {
            return true;
          }
          if (v[element] == null) {
            this.isBiodataFilled = false;
            return false;
          }
          this.isBiodataFilled = true;
          return true;
        });
        console.log(this.isBiodataFilled);
      },
    },
  },
  data() {
    return {
      progress: 0,
      photoFile: null,
      ijazahFile: null,
      loadingSheet: { toggle: false, message: null, loading: 0 },
      isPembayaranLunas: false,
      isLulusUjian: false,
      isBiodataFilled: false,
      ruleTemuRamah: [() => this.isLulusUjian != false],
      ruleUjian: [() => this.isPembayaranLunas != false],
      rulePembayaran: [() => this.isBiodataFilled != false],
      ruleBiodata: [() => this.jurusanSelected != null],
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