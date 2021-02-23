<template>
  <!-- v-model="stepper" -->
  <v-sheet
    class="mx-auto"
    :width="width()"
  >
    <v-card>
      <v-card-title>Pendaftaran</v-card-title>
      <v-card-subtitle>Tahap {{stepper}} dari 5</v-card-subtitle>
    </v-card>
    <v-stepper
      non-linear
      vertical
      v-model="stepper"
    >
      <v-stepper-step
        color="green"
        :editable="isJurusanEditable"
        step="1"
        :complete="jurusanSelected ? true : false"
        v-if="stepper == 1"
      >
        Pilih Jurusan
        <strong>Pilihlah program studi/konsentrasi yang anda inginkan.</strong>
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
          :loading="pilihJurusanLoading"
          @click="stepper = 2"
        >
          Selanjutnya
        </v-btn>
      </v-stepper-content>

      <v-stepper-step
        :editable="jurusanSelected ? true : false"
        color="green"
        :complete="stepper > 2"
        step="2"
        :rules="ruleBiodata"
        v-if="stepper == 2"
      >
        Isi Biodata
        <strong
          class="text-red"
          v-if="jurusanSelected ? false : true"
        >Isi jurusan terlebih dahulu.</strong>
      </v-stepper-step>

      <v-stepper-content step="2">
        <v-container>
          <v-row>
            <v-text-field
              color="green"
              filled
              :loading="biodata.field0"
              :disabled="biodataDisabled.field0"
              @change="sendUser(user,0)"
              prepend-inner-icon="mdi-account"
              label="Nama Lengkap"
              v-model="user.nama"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-select
              color="green"
              filled
              :loading="biodata.field7"
              :disabled="biodataDisabled.field7"
              @change="sendUser(user,7)"
              prepend-inner-icon="mdi-account"
              label="Jenis Kelamin"
              :items="jk"
              v-model="user.jenis_kelamin"
            ></v-select>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              :loading="biodata.field1"
              :disabled="biodataDisabled.field1"
              @change="sendUser(user,1)"
              prepend-inner-icon="mdi-phone"
              label="No Telepon"
              type="number"
              v-model="user.hp"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-text-field
              :loading="biodata.field2"
              :disabled="biodataDisabled.field2"
              color="green"
              filled
              @change="sendUser(user,2)"
              prepend-inner-icon="mdi-whatsapp"
              label="No Whatsapp"
              type="number"
              v-model="user.wa"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-textarea
              rows="1"
              auto-grow
              color="green"
              filled
              :loading="biodata.field3"
              :disabled="biodataDisabled.field3"
              @change="sendUser(user,3)"
              prepend-inner-icon="mdi-map-marker"
              label="Alamat Rumah Lengkap"
              v-model="user.alamat"
            ></v-textarea>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              :loading="biodata.field4"
              :disabled="biodataDisabled.field4"
              @change="validationNilai({obj:user, id:4},'inggris',user.nilai_bhs_inggris)"
              prepend-inner-icon="mdi-attachment"
              type="number"
              label="Nilai Bahasa Inggris"
              v-model="user.nilai_bhs_inggris"
              :rules="ruleBahasaInggrisValidation"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              type="number"
              filled
              :loading="biodata.field5"
              :disabled="biodataDisabled.field5"
              @change="validationNilai({obj:user, id:5},'arab',user.nilai_bhs_arab)"
              prepend-inner-icon="mdi-attachment"
              label="Nilai Bahasa Arab"
              :rules="ruleBahasaArabValidation"
              v-model="user.nilai_bhs_arab"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-text-field
              required
              :loading="biodata.field6"
              :disabled="biodataDisabled.field6"
              color="green"
              filled
              @change="validationNilai({obj:user, id:6},'ipk',user.nilai_ipk)"
              :rules="ruleIPKValidation"
              prepend-inner-icon="mdi-attachment"
              label="Nilai IPK"
              type="number"
              v-model="user.nilai_ipk"
            ></v-text-field>
            <!-- @change="sendUser(user,6)" -->
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
                >lihat File Anda
                </v-btn>
              </v-col>
            </template>
            <v-file-input
              accept=".pdf"
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
              accept="images/*, .pdf"
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
                >lihat foto anda
                </v-btn>
              </v-col>
            </template>
            <!-- @change="sendUser(user,)" -->
            <v-file-input
              @change="setPhoto()"
              hide-input
              accept="images/*, .pdf"
              ref="photoProfile"
              class="d-none"
              v-model="photoFile"
            ></v-file-input>
          </v-row>
          <v-row v-if="ujianSelected">
            <v-checkbox
              @click="setTnC()"
              v-model="ujianSelected.is_agree"
              color="green"
              label="Setuju dengan syarat dan ketentuan pendaftaran Pascasarjana UIN Suska Riau"
            ></v-checkbox>
          </v-row>
          <v-row v-if="isSyaratLulus">
            <h4>
              Maaf Nilai IPK/Bahasa anda tidak mencukupi syarat pendaftaran
            </h4>
          </v-row>
        </v-container>
        <v-btn
          :disabled="!isTnCAgreednBiodataFilled"
          color="green darken-2"
          class="text-white"
          @click="stepper = 3"
          :loading="biodataLoading"
        >
          Selanjutnya
        </v-btn>
      </v-stepper-content>

      <v-stepper-step
        :editable="isBiodataFilled ? true : false"
        color="green"
        :complete="stepper > 3"
        step="3"
        :rules="rulePembayaran"
        v-if="stepper == 3"
      >
        Pembayaran
        <strong
          class="text-red"
          v-if="isBiodataFilled ? false : true"
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
          <v-card-subtitle>Lakukan pembayaran untuk dapat mengikuti ujian
            masuk</v-card-subtitle>
          <v-card-text>
            <v-btn
              block
              large
              dark
              color="green darken-3"
              :loading="isLoading"
              v-if="!kodePembayaran"
              @click="generateCode()"
            >Dapatkan Kode Pembayaran</v-btn>
            <div v-if="kodePembayaran && !isPembayaranLunas">
              <span> Segera membayar dengan kode berikut </span>
              <h1>{{ kodePembayaran }}</h1>
            </div>
            <div v-if="isPembayaranLunas">
              <h1>Pembayaran Berhasil!</h1>
              <span>Silahkan melakukan ujian masuk pada tahap selanjutnya</span>
            </div>
          </v-card-text>
        </v-card>
        <v-btn
          :disabled="isPembayaranLunas ? false : true"
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
        v-if="stepper == 4"
      >
        Ujian
        <strong
          class="text-red"
          v-if="isPembayaranLunas ? false : true"
        >Lakukan pembayaran terlebih dahulu.</strong>
      </v-stepper-step>
      <v-stepper-content step="4">
        <v-card
          v-if="ujianSelected"
          color="grey lighten-4"
          class="mb-12"
        >
          <v-card-title>Ujian Masuk</v-card-title>
          <v-card-subtitle>Lakukan ujian Tes Kemampuan Akademik (TKA) dan Tes Kemampuan
            Keilmuan (TKK)</v-card-subtitle>
          <v-card-text>
            <p>Waktu tersisa untuk menyelesaikan ujian TKA dan TKK</p>
            <span>
              <!-- :end-label="''" -->
              <vue-countdown-timer
                @start_callback="startCallBack('event started')"
                @end_callback="endCallBack('event ended')"
                :start-time="now"
                :end-time="ujianSelected.batas_ujian + ' 23:59:59'"
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
              dark
              block
              @click="ujian('tka')"
            >Mulai Ujian TKA</v-btn>
            <v-divider></v-divider>
            <v-btn
              block
              color="green darken-2"
              dark
              @click="ujian('tkj')"
            >Mulai Ujian TKK</v-btn>
          </v-card-text>
        </v-card>
      </v-stepper-content>
      <v-stepper-step
        :editable="isLulusUjian ? true : false"
        color="green"
        step="5"
        :rules="ruleTemuRamah"
        v-if="stepper == 5"
      >
        Temu Ramah
        <strong
          class="text-red"
          v-if="isLulusUjian ? false : true"
        >Anda dapat masuk pada tahap Temu Ramah setelah lulus ujian TKA dan
          TKJ.</strong>
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
        > Selanjutnya </v-btn>
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
              <v-list-item-title>{{
                this.loadingSheet.message
              }}</v-list-item-title>
              <v-list-item-subtitle>{{
                this.progress + "%"
              }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card>
    </v-bottom-sheet>
    <v-snackbar
      v-model="snackbar.show"
      :color="snackbar.color"
    >{{snackbar.message}}
      <template v-slot:action="{ attrs }">
        <v-btn
          text
          v-bind="attrs"
          @click="snackbar.show = false"
        >
          Tutup
        </v-btn>
      </template>
    </v-snackbar>
  </v-sheet>
</template>

<script>
import _ from "lodash";
import { mapState, mapActions, mapMutations } from "vuex";
export default {
  created() {
    this.sendUser = _.debounce(this.sendUser, 500);
    if (!this.jurusan) {
      this.initAllDataClnMhs().then((response) => {});
    }
    this.checkBiodata(this.user);
  },
  methods: {
    ...mapMutations(["setUser", "setUjianSelected"]),
    ...mapActions(["initAllDataClnMhs", "updateUser", "getSoal"]),
    validationNilai(obj, field, value) {
      console.log("IM VALIDATION");
      // field: arab, inggris, ipk
      if (field == "ipk") {
        var isValid = true;
        for (let index = 0; index < this.ruleIPKValidation.length; index++) {
          const element = this.ruleIPKValidation[index];
          console.log("loop", element(value));
          if (element(value) != true) {
            isValid = false;
          }
        }
        console.log("response", isValid);
        if (isValid) {
          this.sendUser(obj.obj, obj.id);
        }
        return 0;
      }
      if (field == "arab") {
        var isValid = true;
        for (
          let index = 0;
          index < this.ruleBahasaArabValidation.length;
          index++
        ) {
          const element = this.ruleBahasaArabValidation[index];
          console.log("loop", element(value));
          if (element(value) != true) {
            isValid = false;
          }
        }
        console.log("response", isValid);
        if (isValid) {
          this.sendUser(obj.obj, obj.id);
        }
        return 0;
      }
      if (field == "inggris") {
        var isValid = true;
        for (
          let index = 0;
          index < this.ruleBahasaInggrisValidation.length;
          index++
        ) {
          const element = this.ruleBahasaInggrisValidation[index];
          console.log("loop", element(value));
          if (element(value) != true) {
            isValid = false;
          }
        }
        console.log("response", isValid);
        if (isValid) {
          this.sendUser(obj.obj, obj.id);
        }
        return 0;
      }
    },
    sendUser(user, id) {
      // id is biodara's property number, see at data property section
      console.log("im called");
      this.biodata["field" + id] = true;
      this.biodataLoading = true;
      // this.setFieldToDisabled(id, true);
      this.updateUser(user)
        .then((response) => {
          this.biodataLoading = false;
          setTimeout(() => {
            // this.setFieldToDisabled(id, false);
            this.biodata["field" + id] = false;
            this.snackbar.message = "Biodata berhasil disimpan!";
            this.snackbar.color = "green";
            this.snackbar.show = true;
          }, 300);
        })
        .error((error) => {
          this.biodataLoading = false;
          setTimeout(() => {
            // this.setFieldToDisabled(id, false);
            this.snackbar.message =
              "Maaf terjadi kesalahan! Silahkan coba lagi";
            this.snackbar.color = "red";
            this.snackbar.show = true;
            this.biodata["field" + id] = false;
          }, 300);
        });
    },
    setFieldToDisabled(id, value) {
      Object.keys(this.biodataDisabled).forEach((key) => {
        if (key != "field" + id) {
          this.biodataDisabled[key] = value;
        }
      });
    },
    setTnC() {
      console.log(this.ujianSelected);
      this.biodataLoading = true;
      axios
        .put("/api/ujian/" + this.ujianSelected.id, this.ujianSelected)
        .then((response) => {
          this.biodataLoading = false;
          console.log(response.data);
        });
    },
    ujian(type) {
      // console.log("ID", this.ujianSelected.id);
      var ujian_id = this.ujianSelected.id;
      var soal_id = this.ujianSelected.soal_id;
      var payload = { ujian_id, type, soal_id };
      // console.log(payload);
      this.getSoal(payload).then((response) => {
        this.$router.push({
          name: "Soal",
          params: { type, ujian_id, soal_id: this.ujianSelected.soal_id },
        });
      });
    },
    checkBiodata(v) {
      Object.keys(v).every((element) => {
        if (element == "email_verified_at") {
          return true;
        }
        if (element == "is_verified") {
          return true;
        }
        if (v[element] == null || v[element] == "") {
          this.isBiodataFilled = false;
          return false;
        }
        this.isBiodataFilled = true;
        return true;
      });
      console.log("biodata", this.isBiodataFilled);
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
      var periode_id = this.activePeriode.id;
      var jurusan_id = this.jurusanSelected;
      var payload = { periode_id, jurusan_id };
      this.pilihJurusanLoading = true;
      if (this.ujian_id) payload["ujian_id"] = this.ujian_id;
      axios.post("/api/ujian/init", payload).then((response) => {
        this.ujian_id = response.data.ujian_id;
        this.setUjianSelected(response.data.ujian_selected);
        console.log(response.data);
        this.pilihJurusanLoading = false;

        //update rule for biodata's fields
        var batasIPK = this.ujianSelected.periode.syarat_ipk;
        var batasArab = this.ujianSelected.periode.syarat_bhs_arab;
        var batasInggris = this.ujianSelected.periode.syarat_bhs_inggris;
        this.ruleIPKValidation.push(
          (v) =>
            v >= batasIPK ||
            `Maaf, syarat minimal ipk untuk mendaftar adalah ${batasIPK}`
        );
        this.ruleBahasaArabValidation.push(
          (v) =>
            v >= batasArab ||
            `Maaf, syarat minimal Bahasa Arab untuk mendaftar adalah ${batasArab}`
        );
        this.ruleBahasaInggrisValidation.push(
          (v) =>
            v >= batasInggris ||
            `Maaf, syarat minimal Bahasa Inggris untuk mendaftar adalah ${batasInggris}`
        );
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
        }, 1500);
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
    async loopCheckPembayaran() {
      function sleep(ms) {
        return new Promise((res) => setTimeout(res, ms));
      }
      //check is still in the pendaftaran page,
      // if not then stop the loop
      console.log("route now:", this.$route.name);
      if (this.$route.name != "Pendaftaran Baru") {
        console.log("Check Stopped");
        return 0;
      }
      let myAsyncFunc = async function (ini) {
        console.log("Sleeping");
        await sleep(3000);
        console.log("Done");
        // console.log(ini);
        ini.checkPembayaran(ini.ujian_id, ini).then((response) => {
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
          .then((response) => {
            if (response.data.status) ini.isPembayaranLunas = true;
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
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
    },
  },
  computed: {
    ...mapState([
      "jurusan",
      "user",
      "periode",
      "ujianSelected",
      "activePeriode",
    ]),
    isTnCAgreednBiodataFilled() {
      if (this.ujianSelected) {
        return this.isBiodataFilled && this.ujianSelected.is_agree;
      } else {
        return false;
      }
    },
    PhotoFileName: function () {},
    isSyaratLulus: function () {
      if (!this.user && !this.ujianSelected) {
        return false;
      }
      if (this.user.nilai_ipk > this.periode.syarat_ipk) {
      }
    },
    now: function () {
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
  },
  watch: {
    user: {
      deep: true,
      handler: function (v) {
        this.checkBiodata(v);
        // console.log(_);
      },
    },
  },
  data() {
    return {
      // these biodara properties is for biodatas loading and disabled state
      pilihJurusanLoading: false,
      biodataLoading: false,
      snackbar: { show: false, message: null },
      biodata: {
        field0: false,
        field1: false,
        field2: false,
        field3: false,
        field4: false,
        field5: false,
        field6: false,
        field7: false,
      },
      biodataDisabled: {
        field0: false,
        field1: false,
        field2: false,
        field3: false,
        field4: false,
        field5: false,
        field6: false,
        field7: false,
      },
      jk: ["Laki-laki", "Perempuan"],
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
      ruleIPKValidation: [
        (v) => !!v || "IPK wajib diisi",
        (v) => v <= 4 || "IPK hanya 0 - 4",
      ],
      ruleBahasaArabValidation: [(v) => !!v || "Nilai wajib diisi"],
      ruleBahasaInggrisValidation: [(v) => !!v || "Nilai wajib diisi"],
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
