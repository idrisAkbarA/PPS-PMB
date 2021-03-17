<template>
  <v-container>
    <v-row>
      <v-spacer></v-spacer>
      <div class="mb-5">
        <v-chip
          style="padding-right:0px !important"
          color="green darken-2"
          text-color="#ecf0f1"
        >
          <span class="mr-2">
            Sisa waktu ujian:
          </span>
          <v-chip>
            <vue-countdown-timer
              v-if="soal"
              @start_callback="startCallBack('event started')"
              @end_callback="endCallBack('event ended')"
              :start-time="startTime"
              :end-time="endTime"
              :interval="1000"
              :start-label="'Until start:'"
              label-position="begin"
              :end-text="'Ujian selesai!'"
              :day-txt="''"
              :hour-txt="'Jam'"
              :minutes-txt="'Menit'"
              :seconds-txt="'Detik'"
            >
            </vue-countdown-timer>
          </v-chip>
        </v-chip>
      </div>
    </v-row>
    <v-row>

      <v-card width="100%">
        <v-progress-linear
          height="8"
          :color="shortCountDownColor"
          :value="shortCountDownValue"
        ></v-progress-linear>
        <v-card-title>

          <v-row
            class="mx-0"
            v-if="soal"
          >
            <span class="mr-2">Jumlah soal terjawab </span>
            <v-chip
              color="green darken-2"
              text-color="#ecf0f1"
            >{{soalTerjawab}}/{{
            jumlahSoal
          }}</v-chip>
            <v-spacer></v-spacer>
            <div v-if="isStillCounting">
              <span class="overline text-muted mb-0">
                Mohon jawab soal berikut dalam:

              </span>
              <v-chip
                color="green darken-2"
                text-color="#ecf0f1"
              >
                {{shortCountDownSeconds}} Detik
              </v-chip>
            </div>
          </v-row>
        </v-card-title>
        <v-card-text v-if="soal">
          <v-expand-transition>
            <div v-show="checkPresence">
              {{soal[currentSoal].pertanyaan}}
              <v-radio-group
                column
                v-model="soal[currentSoal].jawaban"
                @change="setJawaban(soal[currentSoal])"
              >
                <v-radio
                  v-for="(pilihan,index) in soal[currentSoal].pilihan_ganda"
                  :key="index"
                  color="green"
                  :label="`${pilihan.pilihan}. ${pilihan.text}`"
                  :value="pilihan.pilihan"
                ></v-radio>
              </v-radio-group>
              <v-btn
                text
                @click="skipSoal()"
              >Lewati Soal Ini <v-icon>mdi-menu-right</v-icon>
              </v-btn>
            </div>
          </v-expand-transition>
          <v-expand-transition>
            <div v-show="!isNewlySelected">
              <h1 class="text-center mb-5 mt-5">Jawaban anda {{soal[currentSoal].jawaban}}</h1>
            </div>
          </v-expand-transition>
          <!-- <v-btn @click="shortCountDown()">test</v-btn> -->
        </v-card-text>
      </v-card>
    </v-row>
    <v-dialog
      persistent
      v-model="dialogHasil"
      :width="windowWidth>600?'40%':'70%'"
    >
      <v-card>
        <v-card-title>
          Hasil Ujian
        </v-card-title>
        <v-card-text>
          <label>Soal terjawab benar: {{this.nilai}}</label><br>
          <strong v-if="isLulus">Selamat anda lulus ujian!</strong>
          <strong v-if="!isLulus">Mohon maaf, anda tidak lulus ujian!</strong>
        </v-card-text>
        <v-card-actions>
          <v-btn
            block
            class="text-white mb-2"
            color="green"
            @click="goToPendaftaran()"
          >Kembali ke Halaman Pendaftaran</v-btn>

        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      persistent
      v-model="dialogReview"
      :width="windowWidth>600?'50%':'80%'"
    >
      <v-card>
        <v-card-title>
          <span v-if="isSemuaSoalTerjawab">
            Semua soal telah dikerjakan!
          </span>
          <span v-else-if="isInRange(startTime,endTime)">Maaf jatah soal sudah habis!</span>
          <span v-else>Waktu mengerjakan soal sudah habis!</span>
        </v-card-title>

        <v-card-text v-if="soalReview">

          <label v-if="isSemuaSoalTerjawab">Anda masih memiliki waktu <v-chip>

              <vue-countdown-timer
                v-if="soal"
                @start_callback="startCallBack('event started')"
                @end_callback="endCallBackRevisi('event ended')"
                :start-time="startTime"
                :end-time="endTimeRevisi"
                :interval="1000"
                :start-label="'Until start:'"
                label-position="begin"
                :end-text="'Ujian selesai!'"
                :day-txt="''"
                :hour-txt="'Jam'"
                :minutes-txt="'Menit'"
                :seconds-txt="'Detik'"
              >
              </vue-countdown-timer>
            </v-chip>
            (Sisa waktu ujian + waktu revisi), silahkan melakukan revisi atau klik "Selesai" untuk menyelesaikan sesi ujian.</label>
          <label v-else>Anda masih memiliki waktu revisi selama <v-chip>
              <vue-countdown-timer
                v-if="soal"
                @start_callback="startCallBack('event started')"
                @end_callback="endCallBackRevisi('event ended')"
                :start-time="startTime"
                :end-time="endTimeRevisi"
                :interval="1000"
                :start-label="'Until start:'"
                label-position="begin"
                :end-text="'Ujian selesai!'"
                :day-txt="''"
                :hour-txt="'Jam'"
                :minutes-txt="'Menit'"
                :seconds-txt="'Detik'"
              >
              </vue-countdown-timer>
            </v-chip>
            silahkan melakukan revisi atau klik "Selesai" untuk menyelesaikan sesi ujian.</label>
          <v-card
            outlined
            color="rgba(46, 204, 113, 0.25)"
            class="blue-grey--text text--darken-4 pa-1"
            style="height: 100%;"
          >
            <v-card-title
              dense
              style="height:25%"
              class="pa-0 ml-1 mb-1"
            >
              Nomor
            </v-card-title>
            <v-card-text
              style="overflow-y: auto;height:75%"
              class="mx-0 pa-0"
            >
              <v-btn
                class="ma-1 pa-0"
                tile
                v-for="(soal,index) in soalReview"
                :key="index+1"
                small
                @click="currentSoal=index"
                :class="setNomorColor(soal,index)"
              >{{ index+1 }}</v-btn>

            </v-card-text>
          </v-card>
          <v-card outlined>
            <v-card-text>
              {{soalReview[currentSoal].pertanyaan}}
              <v-radio-group
                column
                v-model="soalReview[currentSoal].jawaban"
                @change="setJawaban(soalReview[currentSoal])"
              >
                <v-radio
                  v-for="(pilihan,index) in soalReview[currentSoal].pilihan_ganda"
                  :key="index"
                  color="green"
                  :label="`${pilihan.pilihan}. ${pilihan.text}`"
                  :value="pilihan.pilihan"
                ></v-radio>
              </v-radio-group>
            </v-card-text>
          </v-card>
        </v-card-text>
        <v-card-actions>
          <v-btn
            @click="dialog=true"
            class="text-white"
            color="green"
          >Selesai</v-btn>

        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="dialog"
      :width="windowWidth>600?'30%':'60%'"
    >
      <v-card>
        <v-card-title>
          Selesai Ujian
        </v-card-title>
        <v-card-text>
          <label>Apakah anda yakin ingin menyelesaikan sesi ujian?</label>
        </v-card-text>
        <v-card-actions>
          <v-btn
            @click="getHasil()"
            class="text-white"
            color="green"
          >Iya</v-btn>
          <v-btn
            text
            @click="dialog=false"
          >tidak</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  watch: {
    currentSoal(v) {
      var currentIndex = localStorage.getItem("last_soal_index");
      localStorage.setItem("last_soal_index", v);
      console.log("index:", v, "length:", this.soal.length);
      if (this.soal) {
        if (v == this.soal.length) {
          endCallBack();
        }
      }
    },
    shortCountDownSeconds(v) {
      localStorage.setItem("last_soal_time", v);
    },
  },
  methods: {
    shortCountDown(durasiSoal = null) {
      console.log("durasi", durasiSoal);
      let milliseconds = 1000;
      let interval = durasiSoal ? durasiSoal : this.durasiSoal; // in seconds
      let soalTimer = setInterval(() => {
        if (this.shortCountDownValue <= 30) {
          this.shortCountDownColor = "red";
        }
        if (!this.isStillCounting) {
          clearInterval(soalTimer);
        } else if (this.shortCountDownValue < 1) {
          this.currentSoal += 1;
          clearInterval(soalTimer);
          this.shortCountDownColor = "blue";
          this.shortCountDownValue = 100;
          this.shortCountDownSeconds = this.durasiSoal;
          this.shortCountDown();
        } else {
          if (durasiSoal > 0) {
            this.shortCountDownSeconds = durasiSoal;
            durasiSoal = null;
          }
          this.shortCountDownValue -= 100 / interval;
          this.shortCountDownSeconds -= 1;
        }
      }, milliseconds);
    },
    ...mapActions(["getSoal"]),
    getHasil() {
      var payload = {
        type: this.type,
        id: this.soal_id,
        idUjian: this.ujian_id,
      };
      axios
        .post("/api/soal/calc-score", payload)
        .then((response) => {
          this.nilai = response.data.nilai;
          this.isLulus = response.data.status_lulus;
          this.dialogHasil = true;
          this.dialog = false;
          localStorage.setItem("last_soal_index", 0);
          localStorage.setItem("last_soal_time", 0);
          console.log(response.data);
        })
        .catch((error) => {});
    },
    calcSoalRemaining(vm = this) {
      var jumlah_soal = vm.soal.length;
      var terjawab = 0;
      vm.soal.forEach((element) => {
        if (element.jawaban) {
          terjawab += 1;
        }
      });
      vm.belum_terjawab = jumlah_soal - terjawab;
    },
    setNomorColor(soal, index) {
      if (soal.ragu && this.currentSoal == index) return "yellow darken-2";
      if (soal.ragu) return "yellow";
      if (soal.jawaban && this.currentSoal == index)
        return "green darken-2 text-white";
      if (soal.jawaban) return "green lighten-1 text-white";
      if (this.currentSoal == index) return "grey text-white";
      return "white";
    },
    setDuration() {},
    setJawaban(soal) {
      this.isNewlySelected = false;
      console.log(soal);
      let payload = {
        type: this.type,
        rowID: this.soal_id,
        soalID: soal.id,
        jawaban: soal.jawaban,
      };
      axios
        .post("/api/soal/set-jawaban", payload)
        .then((response) => {
          console.log(response.data);
        })
        .catch((error) => {});
      if (this.currentSoal + 1 == this.soal.length) {
        this.endCallBack();
      } else {
        setTimeout(() => {
          this.isNewlySelected = true;
          this.shortCountDownValue = 100;
          this.shortCountDownColor = "blue";
          this.shortCountDownSeconds = this.durasiSoal;
          var currentSoalIndex = parseInt(this.currentSoal);
          this.currentSoal = currentSoalIndex + 1;
        }, 1000);
      }
    },
    initData(vm) {
      // get paramater from url segments
      const thePath = window.location.pathname;
      const segments = thePath.split("/");
      // last segment is soal_id
      // 2nd from last is ujian_id
      // 3rd from last is type
      vm.soal_id = segments[segments.length - 1];
      vm.ujian_id = segments[segments.length - 2];
      vm.type = segments[segments.length - 3];

      // set index soal, continue where did the user left
      // or if it is a new exam then set to 0
      var soalIndex = localStorage.getItem("last_soal_index");
      if (soalIndex > 0) {
        vm.currentSoal = soalIndex;
      } else {
        vm.currentSoal = 0;
      }
    },
    initSoal(vm) {
      // this method called if the page get reloaded or direct access via url
      // this method initialize the data that this page needed
      vm.initData(vm);
      const payload = {
        soal_id: vm.soal_id,
        ujian_id: vm.ujian_id,
        type: vm.type,
      };
      vm.getSoal(payload).then((response) => {
        console.log(response.data.durasi_soal);
        vm.shortCountDownSeconds = response.data.durasi_soal;
        var durasiSoal = localStorage.getItem("last_soal_time");
        if (durasiSoal > 0) {
          vm.shortCountDown(durasiSoal);
        } else {
          vm.shortCountDown();
        }
        // if (vm.isSoalNewlyCreated) {
        //   localStorage.setItem("last_soal_index", 0);
        // }
      });
    },
    skipSoal() {
      this.isNotSkipped = false;
      if (this.currentSoal + 1 == this.soal.length) {
        this.endCallBack();
      } else {
        setTimeout(() => {
          this.isNotSkipped = true;
          this.shortCountDownValue = 100;
          this.shortCountDownSeconds = this.durasiSoal;
          var currentSoalIndex = parseInt(this.currentSoal);
          this.currentSoal = currentSoalIndex + 1;
        }, 500);
      }
      // console.log(this.soal[this.currentSoal].jawaban);
    },
    goToPendaftaran() {
      this.$router.replace({
        name: "Pendaftaran",
        params: { id: this.ujian_id },
      });
    },
    isInRange(start, end) {
      return this.$moment(this.now).isBetween(start, end);
    },
    startCallBack(data) {},
    endCallBack(data) {
      this.currentSoal = 0;
      this.isStillCounting = false;
      this.endTimeRevisi = this.$moment(this.endTime, "YYYY-MM-DD HH:mm:ss")
        .add(1, "minutes")
        .format("YYYY-MM-DD HH:mm:ss");
      var temp = this.soal.filter((item) => item.jawaban != null);
      this.soalReview = temp.length > 0 ? temp : null;
      this.dialogReview = true;
      // this.getHasil();
    },
    endCallBackRevisi(data) {
      this.getHasil();
    },
  },
  data() {
    return {
      isSemuaSoalTerjawab: false,
      isNotSkipped: true,
      isNewlySelected: true,
      isStillCounting: true,
      shortCountDownValue: 100,
      shortCountDownSeconds: null,
      shortCountDownColor: "blue",
      currentSoal: 0,
      isLulus: false,
      nilai: 0,
      endTimeRevisi: null,
      belum_terjawab: null,
      dialogHasil: false,
      dialogReview: false,
      dialog: false,
      currentSoal: 0,
      soal_id: null,
      ujian_id: null,
      type: null,
      soalReview: null,
    };
  },
  beforeRouteEnter(to, from, next) {
    if (from.name == null) {
      next((vm) => {
        vm.initSoal(vm);
      });
    } else {
      next((vm) => {
        vm.shortCountDownSeconds = vm.durasiSoal;
        // vm.shortCountDown();
        var durasiSoal = localStorage.getItem("last_soal_time");
        if (durasiSoal > 0) {
          vm.shortCountDown(durasiSoal);
        } else {
          vm.shortCountDown();
        }
        // if (vm.isSoalNewlyCreated) {
        //   localStorage.setItem("last_soal_index", 0);
        // }
      });
    }
  },
  created() {
    let vm = this;
    this.initData(vm);
  },
  computed: {
    ...mapState([
      "isSoalNewlyCreated",
      "soal",
      "durasi",
      "jumlahSoal",
      "durasiSoal",
      "startTime",
      "endTime",
    ]),
    soalTerjawab() {
      var jumlah = 0;
      this.soal.forEach((element) => {
        if (element.jawaban != null) jumlah += 1;
      });
      this.endTimeRevisi = this.$moment(this.endTime, "YYYY-MM-DD HH:mm:ss")
        .add(1, "minutes")
        .format("YYYY-MM-DD HH:mm:ss");
      if (jumlah == this.jumlahSoal) {
        this.currentSoal = 0;
        this.isStillCounting = false;
        var temp = this.soal.filter((item) => item.jawaban != null);
        this.soalReview = temp.length > 0 ? temp : null;
        this.isSemuaSoalTerjawab = true;
        this.dialogReview = true;
      }
      return jumlah;
    },
    checkPresence() {
      return this.isNewlySelected && this.isNotSkipped;
    },
  },
};
</script>

<style>
</style>