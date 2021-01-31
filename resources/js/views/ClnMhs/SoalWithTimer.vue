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

          <v-row class="mx-0">
            <span class="mr-2">Jumlah soal terjawab </span>
            <v-chip
              color="green darken-2"
              text-color="#ecf0f1"
            >0/{{
            jumlahSoal
          }}</v-chip>
            <v-spacer></v-spacer>
            <div v-if="isStillCounting">
              <span class="overline text-muted mb-0">
                Jawab soal ini dalam:

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
          <!-- <v-btn @click="shortCountDown()">test</v-btn> -->
        </v-card-text>
      </v-card>
    </v-row>
  </v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  methods: {
    shortCountDown() {
      let milliseconds = 1000;
      let interval = this.durasiSoal; // in seconds
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
          this.shortCountDownSeconds = 15;
          this.shortCountDown();
        } else {
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

          console.log(response.data);
        })
        .catch((error) => {});
    },
    calcSoalRemaining() {
      var jumlah_soal = this.soal.length;
      var terjawab = 0;
      this.soal.forEach((element) => {
        if (element.jawaban) {
          terjawab += 1;
        }
      });
      this.belum_terjawab = jumlah_soal - terjawab;
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
        vm.shortCountDown();
        vm.shortCountDownSeconds = response.data.durasi_soal;
      });
    },
    skipSoal() {
      this.shortCountDownValue = 100;
      this.shortCountDownSeconds = this.durasiSoal;
      this.currentSoal += 1;
    },
    goToPendaftaran() {
      this.$router.push({ name: "Pendaftaran", params: { id: this.ujian_id } });
    },
    startCallBack(data) {},
    endCallBack(data) {
      this.isStillCounting = false;
      this.getHasil();
    },
  },
  data() {
    return {
      isStillCounting: true,
      shortCountDownValue: 100,
      shortCountDownSeconds: 15,
      shortCountDownColor: "blue",
      currentSoal: 0,
      isLulus: false,
      nilai: 0,
      dialogHasil: false,
      belum_terjawab: null,
      dialog: false,
      currentSoal: 0,
      soal_id: null,
      ujian_id: null,
      type: null,
    };
  },
  beforeRouteEnter(to, from, next) {
    if (from.name == null) {
      console.log("anjing");
      next((vm) => {
        vm.initSoal(vm);
      });
    } else {
      console.log("abjing");
      next((vm) => {
        vm.shortCountDown(vm);
      });
    }
  },
  created() {
    let vm = this;
    this.initData(vm);
  },
  computed: {
    ...mapState([
      "soal",
      "durasi",
      "jumlahSoal",
      "durasiSoal",
      "startTime",
      "endTime",
    ]),
  },
};
</script>

<style>
</style>