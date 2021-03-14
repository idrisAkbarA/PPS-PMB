<template>
  <v-container style="height:calc(100vh - 80px); position:relative; overflow-y:hidden !important;">
    <v-row
      class="fill-height"
      v-if="windowWidth>995&&soal"
    >
      <v-col
        class="fill-height"
        md="8"
        lg="8"
      >
        <v-card
          color="#ecf0f1"
          class="blue-grey--text text--darken-4"
          height="100%"
          style="border: 2px solid green darken-2; border-radius: 5px;"
        >
          <v-card-title style="height: 10%">
            <v-row class="mx-0">
              <span class="mr-2">Soal No.</span>
              <v-chip
                color="green darken-2"
                text-color="#ecf0f1"
              >{{currentSoal+1}}</v-chip>
              <v-spacer></v-spacer>
              <div>
                <v-chip
                  color="green darken-2"
                  text-color="#ecf0f1"
                >Sisa Waktu</v-chip>
                <v-chip
                  color="green darken-2"
                  text-color="#ecf0f1"
                >
                  <vue-countdown-timer
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
              </div>
            </v-row>
          </v-card-title>
          <v-card-text
            class="blue-grey--text text--darken-4"
            style="overflow-y: auto; height: 75%;"
          >
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
          </v-card-text>
          <v-card-actions style="height: 15%">
            <v-btn
              color="green darken-2"
              style="color: #ecf0f1"
              @click="currentSoal-=1"
              text
            >
              <v-icon>mdi-menu-left</v-icon>SOAL SEBELUMNYA
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn
              text
              color="green darken-2"
              style="color: #ecf0f1"
              @click="soal[currentSoal].ragu = !soal[currentSoal].ragu"
            >RAGU - RAGU</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              v-if="currentSoal+1==soal.length"
              color="green darken-2"
              style="color: #ecf0f1"
              @click="dialog=true;calcSoalRemaining()"
              elevation="10"
            >Selesai Ujian</v-btn>
            <v-spacer v-if="currentSoal+1!=soal.length"></v-spacer>
            <v-btn
              color="green darken-2"
              style="color: #ecf0f1"
              @click="currentSoal+=1"
              v-if="currentSoal+1!=soal.length"
              text
            >
              SOAL BERIKUTNYA <v-icon>mdi-menu-right</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col
        class="fill-height"
        md="4"
        lg="4"
      >
        <v-card
          outlined
          color="rgba(46, 204, 113, 0.25)"
          class="blue-grey--text text--darken-4 pa-3 pt-1"
          style="height: 100%;"
        >
          <v-card-title
            dense
            style="height:8%"
            class="ma-0 pa-0 ml-1 text-white"
          >
            Nomor
          </v-card-title>
          <v-card-text
            style="overflow-y: auto;height:90%"
            class="mx-auto pa-0"
          >
            <v-container>
              <v-row
                align="center"
                justify="center"
              >
                <v-btn
                  class="ma-1 pa-0"
                  v-for="(soal,index) in soal"
                  :key="index+1"
                  small
                  :outlined="true"
                  @click="currentSoal=index;setNomorColor(soal,index);"
                  :class="setNomorColor(soal,index)"
                >{{ index+1 }}</v-btn>

              </v-row>
            </v-container>
          </v-card-text>
          <!-- style="color: #ecf0f1;" -->
        </v-card>
      </v-col>
    </v-row>

    <!-- Mobile -->
    <v-row
      class="fill-height"
      v-if="windowWidth<996&&soal"
    >
      <v-col
        style="height: 80%;"
        cols="12"
        class="pa-0"
      >
        <v-card
          color="#ecf0f1"
          class="blue-grey--text text--darken-4"
          width="100%"
          style="position:absolute; height: 100%; border: 2px solid green darken-2; border-radius: 5px;"
        >
          <v-card-title style="height: 20%;">
            <v-row class="mx-0">
              <span>Soal No.</span>
              <v-chip
                color="green darken-2"
                text-color="#ecf0f1"
              >{{currentSoal+1}}</v-chip>
            </v-row>
            <v-row
              class="mx-0"
              justify="end"
            >
              <v-chip
                color="green darken-2"
                text-color="#ecf0f1"
              >Sisa Waktu</v-chip>
              <v-chip
                color="green darken-2"
                text-color="#ecf0f1"
              >01:00:00</v-chip>
            </v-row>
          </v-card-title>

          <v-card-text
            class=" blue-grey--text text--darken-4"
            style="overflow-y: auto; height: 50%;"
          >
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
          </v-card-text>
          <v-card-actions style="height: 30%;">
            <v-row
              justify="center"
              no-gutters
            >
              <v-col cols="12">
                <v-btn
                  small
                  block
                  class="mx-auto ma-1"
                  color="green darken-2"
                  style="color: #ecf0f1"
                  @click="currentSoal-=1"
                >
                  <v-icon>mdi-menu-left</v-icon> SOAL SEBELUMNYA
                </v-btn>
              </v-col>
              <v-col cols="12">
                <v-btn
                  small
                  text
                  block
                  class="mx-auto ma-1"
                  color="green darken-2"
                  style="color: #ecf0f1"
                  @click="soal[currentSoal].ragu = !soal[currentSoal].ragu"
                >RAGU - RAGU</v-btn>
              </v-col>
              <v-col cols="12">
                <v-btn
                  v-if="currentSoal+1==soal.length"
                  color="green darken-2"
                  style="color: #ecf0f1"
                  @click="dialog=true;calcSoalRemaining()"
                  elevation="10"
                  block
                >Selesai Ujian</v-btn>
                <v-spacer v-if="currentSoal+1!=soal.length"></v-spacer>
                <v-btn
                  color="green darken-2"
                  style="color: #ecf0f1"
                  @click="currentSoal+=1"
                  v-if="currentSoal+1!=soal.length"
                  text
                >
                  SOAL BERIKUTNYA <v-icon>mdi-menu-right</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col
        cols="12"
        class="pa-0 mt-2"
        style="height: 20%;"
      >
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
              v-for="(soal,index) in soal"
              :key="index+1"
              small
              @click="currentSoal=index"
              :class="setNomorColor(soal,index)"
            >{{ index+1 }}</v-btn>

          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row v-else>
      <v-progress-circular
        class="mx-auto mt-10"
        :size="100"
        width="7"
        indeterminate
        color="green"
      ></v-progress-circular>
    </v-row>
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
          <strong v-if="belum_terjawab!=0">
            {{belum_terjawab}} belum dijawab.
          </strong>
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
  </v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  watch: {
    currentSoal(v) {
      console.log("local storage", v);
      localStorage.setItem("last_soal_index", v);
    },
  },
  methods: {
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
        console.log("pantek");
        vm.calcSoalRemaining(vm);
        var jumlah_soal = vm.soal.length;
        if (vm.belum_terjawab == jumlah_soal) {
          localStorage.setItem("last_soal_index", 0);
        } else {
          vm.currentSoal = localStorage.getItem("last_soal_index", 0);
        }
      });
    },
    goToPendaftaran() {
      this.$router.push({ name: "Pendaftaran", params: { id: this.ujian_id } });
    },
    startCallBack(data) {},
    endCallBack(data) {
      this.getHasil();
    },
  },
  computed: {
    ...mapState(["soal", "durasi", "startTime", "endTime"]),
  },
  data() {
    return {
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
      next((vm) => {
        vm.initSoal(vm);
      });
    } else {
      next();
    }
  },
  created() {
    let vm = this;
    this.initData(vm);
  },
};
</script>

<style>
</style>
