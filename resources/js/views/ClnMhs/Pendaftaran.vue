<template>
  <!-- v-model="stepper" -->
  <v-sheet
    class="mx-auto"
    :width="width()"
    elevation="10"
  >
    <v-card v-if="!ujianSelected">
      <v-card-text>
        <v-progress-circular indeterminate></v-progress-circular>
      </v-card-text>
    </v-card>
    <v-stepper
      non-linear
      vertical
      v-else
      v-model="stepper"
    >
      <v-stepper-step
        color="green"
        :editable="isJurusanEditable"
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
              v-if="!user.ijazah"
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Scan Ijazah Terakhir"
              @click="$refs.ijazah.$refs.input.click()"
            ></v-text-field>
            <template v-else>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-text-field
                  color="green"
                  filled
                  prepend-inner-icon="mdi-attachment"
                  label="Ubah File Ijazah"
                  readonly
                  @click="$refs.ijazah.$refs.input.click()"
                ></v-text-field>
              </v-col>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-btn
                  block
                  x-large
                  dark
                  @click="link(user.ijazah)"
                  color="green darken-2"
                >lihat File Anda </v-btn>
              </v-col>
            </template>
            <v-file-input
              @change="setIjazah()"
              hide-input
              ref="ijazah"
              class="d-none"
              v-model="ijazahFile"
            ></v-file-input>
          </v-row>

          <v-row>
            <v-text-field
              v-if="!user.pas_photo"
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Upload Pas Foto"
              readonly
              @click="$refs.photoProfile.$refs.input.click()"
            ></v-text-field>
            <template v-else>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-text-field
                  color="green"
                  filled
                  prepend-inner-icon="mdi-attachment"
                  label="Ganti Pas Foto"
                  readonly
                  @click="$refs.photoProfile.$refs.input.click()"
                ></v-text-field>
              </v-col>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-btn
                  block
                  x-large
                  dark
                  @click="link(user.pas_photo)"
                  color="green darken-2"
                >lihat foto anda </v-btn>
              </v-col>
            </template>
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
          <!-- v-if="!ujian.kode_bayar" -->
          <v-card-title>Lakukan Pembayaran</v-card-title>
          <v-card-subtitle>Lakukan pembayaran untuk dapat mengikuti ujian masuk</v-card-subtitle>
          <v-card-text>
            <v-btn
              block
              large
              dark
              :loading="isLoading"
              color="green darken-3"
              v-if="!kodePembayaran"
              @click="generateCode()"
            >Dapatkan Kode Pembayaran</v-btn>
            <div v-if="kodePembayaran && !isPembayaranLunas">
              <span>
                Segera membayar dengan kode berikut
              </span>
              <h1>{{kodePembayaran}}</h1>
            </div>
            <div v-if="isPembayaranLunas">
              <h1>Pembayaran Berhasil!</h1>
              <span>Silahkan melakukan ujian masuk pada tahap selanjutnya</span>
            </div>

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
        :editable="isPembayaranLunas?true:false"
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
import { mapState, mapActions, mapMutations } from "vuex";
export default {
  // first check if this page reloaded before or accessed directly via url
  beforeRouteEnter(to, from, next) {
    if (from.name == null) {
      next((vm) => {
        console.log(vm.initPendaftaran(vm));
      });
    } else {
      next();
    }
  },
  created() {
    if (!this.jurusan) {
      this.initAllDataClnMhs();
    }
  },
  mounted() {
    if (this.user) {
      var ini = this;
      this.checkBiodata(this.user);
      this.setData(ini);
    }
  },
  methods: {
    ...mapMutations(["setUser", "setUser", "setJurusan", "setUjianSelected"]),
    ...mapActions(["initAllDataClnMhs", "updateUser"]),
    setData(ini) {
      ini.jurusanSelected = ini.ujianSelected.jurusan_id;
      ini.ujian_id = ini.ujianSelected.id;
      ini.kodePembayaran = ini.ujianSelected.kode_bayar;
      if (ini.kodePembayaran) {
        ini.isJurusanEditable = false;
      }
      if (ini.ujianSelected.lunas_at)
        ini.isPembayaranLunas = ini.ujianSelected.lunas_at;

      // set stepper position
      if (ini.jurusanSelected != null) ini.stepper = 2;
      if (ini.isBiodataFilled != false && ini.jurusanSelected != null)
        ini.stepper = 3;
      if (ini.isPembayaranLunas != false) ini.stepper = 4;
      if (ini.isLulusUjian != false) ini.stepper = 5;
      console.log("islulus", ini.isLulusUjian);
    },
    initPendaftaran(vm) {
      // this method called if the page get reloaded or direct access via url
      // this method initialize the data that this page needed
      console.log(vm);
      const thePath = window.location.pathname;
      const getLastItem = (thePath) =>
        thePath.substring(thePath.lastIndexOf("/") + 1);
      var payload = { jurusan_id: getLastItem(thePath) };
      axios
        .post("/api/ujian/get-pendaftaran", payload)
        .then((response) => {
          vm.setUser(response.data.user);
          vm.setJurusan(response.data.jurusan);
          vm.setUjianSelected(response.data.ujian);
          vm.setData(vm);
        })
        .catch((error) => {});
    },
    checkBiodata(v) {
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
    generateCode() {
      // this method generate payment code
      this.isLoading = true;
      var payload = { ujian_id: this.ujian_id };
      axios
        .post("/api/ujian/generate-pembayaran", payload)
        .then((response) => {
          console.log(response.data);
          this.isLoading = false;
          this.kodePembayaran = response.data.code;
          this.isJurusanEditable = false;
          this.loopCheckPembayaran();
        })
        .catch((error) => {});
    },
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
    setIjazah() {
      this.progress = 0;
      this.loadingSheet.toggle = true;
      this.loadingSheet.message = "Mengupload File Ijazah...";
      var data = new FormData();
      data.append("file", this.ijazahFile);
      data.append("methodName", "saveIjazahPath");
      this.upload(data, this).then((response) => {
        console.log(response.data);
        this.loadingSheet.message = "File berhasil di upload";
        this.setUser(response.data.user);
        setTimeout(() => {
          this.loadingSheet.toggle = false;
        }, 1000);
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
        this.setUser(response.data.user);
        setTimeout(() => {
          this.loadingSheet.toggle = false;
        }, 1000);
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
    async loopCheckPembayaran() {
      function sleep(ms) {
        return new Promise((res) => setTimeout(res, ms));
      }
      let myAsyncFunc = async function (ini) {
        console.log("Sleeping");
        await sleep(3000);
        console.log("Done");
        // console.log(ini);
        ini.checkPembayaran(ini.ujian_id, ini).then((response) => {
          if (response.data.status) {
            ini.isPembayaranLunas = true;
            return 0;
          }
          ini.loopCheckPembayaran();
        });
      };
      myAsyncFunc(this);
    },
    checkPembayaran: async (ujian_id, ini) => {
      var payload = { ujian_id };
      return new Promise((resolve, reject) => {
        axios
          .post("/api/ujian/check-pembayaran", payload)
          .then((response) => {
            if (response.data.status) ini.isPembayaranLunas = true;
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    link(url) {
      var a = "/" + url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
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
    ...mapState(["jurusan", "user", "periode", "ujianSelected"]),
    PhotoFileName: function () {},
  },
  watch: {
    user: {
      deep: true,
      handler: function (v) {
        this.checkBiodata(v);
      },
    },
  },
  data() {
    return {
      isLoading: false,
      kodePembayaran: null,
      progress: 0,
      photoFile: null,
      ijazahFile: null,
      loadingSheet: { toggle: false, message: null, loading: 0 },
      isJurusanEditable: true,
      isPembayaranLunas: false,
      isLulusUjian: false,
      isBiodataFilled: false,
      ruleTemuRamah: [() => this.isLulusUjian != false],
      ruleUjian: [() => this.isPembayaranLunas != false],
      rulePembayaran: [
        () => this.isBiodataFilled != false && this.jurusanSelected != null,
      ],
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