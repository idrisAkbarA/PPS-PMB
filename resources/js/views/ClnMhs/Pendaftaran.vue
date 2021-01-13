<template>
  <!-- v-model="stepper" -->
  <v-sheet
    class="mx-auto"
    :width="width()"
    elevation="10"
  >
    <v-card>
      <v-card-title>Pendaftaran</v-card-title>
    </v-card>
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

      <!-- :editable="isBiodataFilled?true:false" -->
      <v-stepper-step
        :editable="!isPembayaranLunas"
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
          color="grey lighten-4"
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

      <!-- :editable="isPembayaranLunas?true:false" -->
      <v-stepper-step
        :complete="stepper>4"
        :editable="!isLulusUjian"
        color="green"
        step="4"
        :rules="ruleUjian"
      >
        Ujian
        <strong
          class="text-red"
          v-if="isPembayaranLunas?false:true"
        >Lakukan pembayaran terlebih dahulu.</strong>
        <strong v-if="isLulusUjian">Ujian lulus!</strong>
      </v-stepper-step>
      <v-stepper-content step="4">
        <v-card
          color="grey lighten-4"
          class="mb-12"
        >
          <v-card-title>Ujian Masuk</v-card-title>
          <v-card-subtitle>Lakukan ujian Tes Kemampuan Akademik (TKA) dan Tes Kemampuan Jurusan (TKJ)</v-card-subtitle>
          <v-card-text>

            <p>Waktu tersisa untuk menyelesaikan ujian TKA dan TKJ</p>
            <span>
              <!-- :end-label="''" -->
              <vue-countdown-timer
                @start_callback="startCallBack('event started')"
                @end_callback="endCallBack('event ended')"
                :start-time="now"
                :end-time="ujianSelected.batas_ujian+' 00:00:00'"
                :interval="1000"
                :start-label="'Until start:'"
                label-position="begin"
                :end-text="'Event ended!'"
                :day-txt="'hari'"
                :hour-txt="'jam'"
                :minutes-txt="'menit'"
                :seconds-txt="'detik'"
              >
              </vue-countdown-timer>
            </span>
            <v-divider></v-divider>
            <v-btn
              color="green darken-2"
              block
              class="text-white"
              :disabled="checkButtonMulaiUjian('tka')"
              @click="ujian('tka')"
            >Mulai Ujian TKA</v-btn>
            <div
              v-if="ujianSelected.is_lulus_tka"
              class="text-center"
            ><strong> Status Ujian TKA lulus.</strong></div>
            <div
              v-if="ujianSelected.is_lulus_tka==false"
              class="text-center"
            ><strong> Status Ujian TKA gagal.</strong></div>
            <v-divider></v-divider>
            <v-btn
              block
              color="green darken-2"
              class="text-white"
              :disabled="checkButtonMulaiUjian('tkj')"
              @click="ujian('tkj')"
            >Mulai Ujian TKJ</v-btn>
            <div
              v-if="ujianSelected.is_lulus_tkj"
              class="text-center"
            ><strong> Status Ujian TKJ lulus.</strong></div>
            <div
              v-if="ujianSelected.is_lulus_tkj==false"
              class="text-center"
            ><strong> Status Ujian TKJ gagal.</strong></div>
          </v-card-text>
        </v-card>
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
        <template v-if="jadwalTR!=null">
          <v-card
            v-if="jadwalTR.length>0"
            color="grey lighten-4"
          >
            <v-card-title>Pilih Jadwal Temu Ramah</v-card-title>
            <v-card-text>
              <v-card
                class="mb-2"
                :color="!(jadwalSelected==jadwal.id)?'grey lighten-2':'white' "
                outlined
                v-for="(jadwal,index) in jadwalTR"
                :key="index"
              >
                <v-card-title>{{parseDate(jadwal.tanggal)}}</v-card-title>
                <v-card-subtitle>
                  Dosen {{jadwal.nama_dosen}} <br>
                  Kuota {{calcQuota(jadwal)}}
                </v-card-subtitle>
                <v-card-text>
                  <div v-if="jadwalSelected==jadwal.id">
                    <strong>Tanggal temu ramah anda sudah ditetapkan</strong> Silahkan datang pada waktu yang
                    ditentukan untuk melakukan temu ramah
                  </div>
                  <v-btn
                    v-if="jadwalSelected!=jadwal.id"
                    block
                    color="green"
                    large
                    class="text-white"
                    :disabled="jadwalSelected?true:false"
                    @click="setJadwal(jadwal)"
                  >{{jadwalSelected?'Anda telah memiliki jadwal':'Pilih tanggal ini'}}</v-btn>
                </v-card-text>
              </v-card>
            </v-card-text>
          </v-card>
          <v-card v-else>
            <v-card-title>
              Maaf belum ada jadwal, mohon menunggu atau menghubungi admin
            </v-card-title>
          </v-card>
        </template>
        <v-card v-else>
          <v-progress-circular
            class="mx-auto"
            indeterminate
          ></v-progress-circular>
        </v-card>
        <!-- class="mb-12" -->
        <!-- <v-btn
          color="green"

        >
          Selanjutnya
        </v-btn> -->
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
    if (from.name == null || from.name == "Soal") {
      next(vm => {
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
    ...mapActions(["initAllDataClnMhs", "updateUser", "getSoal"]),
    checkButtonMulaiUjian(type) {
      if (this.ujianSelected["is_lulus_" + type] == false) return true;
      if (this.ujianSelected["is_lulus_" + type] == true) return true;
    },
    setJadwal(jadwal) {
      jadwal.ids_cln_mhs.push(this.user.id);
      axios
        .put(`/api/temu-ramah/${jadwal.id}`, jadwal)
        .then(response => {
          this.jadwalTR = response.data.temuRamah;
        })
        .catch(error => {});
    },
    ujian(type) {
      // console.log("ID", this.ujianSelected.id);
      var ujian_id = this.ujianSelected.id;
      var soal_id = this.ujianSelected.soal_id;
      var payload = { ujian_id, type, soal_id };
      console.log(payload);
      this.getSoal(payload).then(response => {
        this.$router.push({
          name: "Soal",
          params: { type, ujian_id, soal_id: this.ujianSelected.soal_id }
        });
      });
    },
    calcQuota(jadwal) {
      return jadwal.ids_cln_mhs.length + "/" + jadwal.quota;
    },
    setData(ini) {
      // this method set the data after initial data get fetched
      ini.jurusanSelected = ini.ujianSelected.jurusan_id;
      ini.ujian_id = ini.ujianSelected.id;
      ini.kodePembayaran = ini.ujianSelected.kode_bayar;
      ini.getTemuRamah(ini);
      if (ini.kodePembayaran) {
        ini.isJurusanEditable = false;
      }
      if (ini.ujianSelected.lunas_at)
        ini.isPembayaranLunas = ini.ujianSelected.lunas_at;
      ini.isLulusUjian = ini.ujianSelected.lulus_at ? true : false;
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
      const getLastItem = thePath =>
        thePath.substring(thePath.lastIndexOf("/") + 1);
      var payload = { jurusan_id: getLastItem(thePath) };
      axios
        .post("/api/ujian/get-pendaftaran", payload)
        .then(response => {
          vm.setUser(response.data.user);
          vm.setJurusan(response.data.jurusan);
          vm.setUjianSelected(response.data.ujian);
          vm.setData(vm);
        })
        .catch(error => {});
    },
    getTemuRamah(ini) {
      var payload = { periode: ini.ujianSelected.periode_id };
      var user_id = ini.ujianSelected.user_cln_mhs_id;
      axios
        .get("/api/temu-ramah", payload)
        .then(response => {
          // check if is there a date have been booked already
          response.data.temuRamah.forEach(element => {
            element.ids_cln_mhs.every(id => {
              if (id == user_id) {
                ini.jadwalSelected = element.id;
                return false;
              }
              return true;
            });
          });
          ini.jadwalTR = response.data.temuRamah;
          console.log("temu ramah", ini.jadwalTR);
        })
        .catch(error => {});
    },
    checkBiodata(v) {
      Object.keys(v).every(element => {
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
        .then(response => {
          console.log(response.data);
          this.isLoading = false;
          this.kodePembayaran = response.data.code;
          this.isJurusanEditable = false;
          this.loopCheckPembayaran();
        })
        .catch(error => {});
    },
    initUjian() {
      var periode_id = this.periode[0].id;
      var jurusan_id = this.jurusanSelected;
      var payload = { periode_id, jurusan_id };
      if (this.ujian_id) payload["ujian_id"] = this.ujian_id;
      axios.post("/api/ujian/init", payload).then(response => {
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
      this.upload(data, this).then(response => {
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
      this.upload(data, this).then(response => {
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
        onUploadProgress: progressEvent => {
          var percentCompleted = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          );
          ini.progress = percentCompleted;
        },
        data
      });
    },
    async loopCheckPembayaran() {
      function sleep(ms) {
        return new Promise(res => setTimeout(res, ms));
      }
      let myAsyncFunc = async function(ini) {
        console.log("Sleeping");
        await sleep(3000);
        console.log("Done");
        // console.log(ini);
        ini.checkPembayaran(ini.ujian_id, ini).then(response => {
          if (response.data.status) {
            ini.isPembayaranLunas = true;
            ini.setUjianSelected(response.data.ujian);
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
          .then(response => {
            if (response.data.status) ini.isPembayaranLunas = true;
            resolve(response);
          })
          .catch(error => {
            reject(error);
          });
      });
    },
    parseDate(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },
    startCallBack(data) {},
    endCallBack(data) {},
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
    }
  },
  computed: {
    ...mapState(["jurusan", "user", "periode", "ujianSelected"]),
    now: function() {
      var today = new Date();
      var date =
        today.getFullYear() +
        "-" +
        (today.getMonth() + 1) +
        "-" +
        today.getDate();
      var time =
        today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
      var dateTime = date + " " + time;
      return dateTime;
    },
    PhotoFileName: function() {}
  },
  watch: {
    user: {
      deep: true,
      handler: function(v) {
        this.checkBiodata(v);
      }
    }
  },
  data() {
    return {
      jadwalSelected: null,
      jadwalTR: null,
      isLoading: false,
      kodePembayaran: null,
      progress: 0,
      photoFile: null,
      ijazahFile: null,
      loadingSheet: { toggle: false, message: null, loading: 0 },
      isPembayaranEditable: true,
      isJurusanEditable: true,
      isPembayaranLunas: false,
      isLulusUjian: false,
      isBiodataFilled: false,
      ruleTemuRamah: [() => this.isLulusUjian != false],
      ruleUjian: [() => this.isPembayaranLunas != false],
      rulePembayaran: [
        () => this.isBiodataFilled != false && this.jurusanSelected != null
      ],
      ruleBiodata: [() => this.jurusanSelected != null],
      ujian_id: null,
      jurusanSelected: null,
      stepper: 1,
      form: {}
    };
  }
};
</script>

<style>
</style>
