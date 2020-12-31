<template>
  <v-container fluid>
    <div style="height:340px">
      <h1 class="text-white mt-6">Selamat Datang,<br> <span v-if="user">{{user.nama}}</span> <br> di Aplikasi Pendaftaran Pascasarjana
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
      <v-row v-if="isLoading">
        <v-progress-circular
          class="mx-auto mt-10"
          :size="100"
          width="7"
          indeterminate
          color="green"
        ></v-progress-circular>
      </v-row>
      <v-row v-if="(!ujian && !isLoading)">
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
                    <h1 class="text-white">Hmm.. Sepertinya anda belum mendaftar</h1>
                    <v-btn
                      large
                      @click="$router.push({name:'Pendaftaran Baru'})"
                    >Daftar Sekarang</v-btn>
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
      <v-row
        v-if="ujian"
        class="mb-10 mt-5"
      >
        <v-col
          v-for="(item,index) in ujian"
          :key="index"
          cols="12"
          md="6"
          lg="3"
        >
          <v-card elevation="10">
            <v-card-title class="green darken-2 text-white">{{item.jurusan.nama}}</v-card-title>
            <v-card-subtitle class="green darken-2 text-white">Periode {{item.periode.nama}}<br>Klik untuk melihat rincian</v-card-subtitle>
            <v-card-text>
              <v-container class="mt-4">
                <v-row>
                  <p>Waktu tersisa untuk menyelesaikan ujian TKA dan TKJ</p>
                  <span>
                    <!-- :end-label="''" -->
                    <vue-countdown-timer
                      @start_callback="startCallBack('event started')"
                      @end_callback="endCallBack('event ended')"
                      :start-time="now"
                      :end-time="item.batas_ujian+' 23:59:59'"
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
                </v-row>
              </v-container>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col
          cols="12"
          md="6"
          lg="3"
          align-self="center"
        >

          <v-row justify="center">
            <v-btn
              fab
              color="green darken-2"
              dark
              class="mx-auto"
              @click="$router.push({name:'Pendaftaran Baru'})"
            >
              <!-- @click="createUjian()" -->
              <v-icon>mdi-plus</v-icon>
            </v-btn>
          </v-row>
        </v-col>
      </v-row>
    </v-container>
    <v-dialog
      scrollable
      v-model="dialogTambah"
      :width="width()"
    >
      <v-card color="grey lighten-5">
        <v-card-title>Pendaftaran Anda</v-card-title>
        <v-card-text>
          <daftar-component></daftar-component>

        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { mapState, mapActions } from "vuex";
import DaftarComponent from "../Components/DaftarComponent";
export default {
  created() {
    console.log(this.now);
    // this.getUser("cln_mahasiswa");
    this.initAllDataClnMhs();
  },
  methods: {
    ...mapActions(["getUser", "initAllDataClnMhs"]),
    link() {},

    createUjian() {
      var periode_id = this.periode[0].id;
      axios.get("/api/ujian/init/" + periode_id).then((response) => {
        console.log(response.data);
      });
    },
    countdown(date, index) {
      var timerProperty = "timer-" + index;
      this[timerProperty] = 0;
    },
    startCallBack: function (x) {
      console.log(x);
    },
    endCallBack: function (x) {
      console.log(x);
    },
    convertDate(date) {
      var result = new Date(date);
      console.log("end:", result.toString());
      return result.toDateString();
    },
    width() {
      if (this.windowWidth <= 600) {
        return "100%";
      } else if (this.windowWidth <= 960) {
        return "60%";
      } else {
        return "40%";
      }
    },
  },
  computed: {
    ...mapState(["user", "ujian", "isLoading", "periode"]),
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
  data() {
    return {
      dialogTambah: null,
      item: null,
      form: {},
    };
  },
  components: {
    DaftarComponent,
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
