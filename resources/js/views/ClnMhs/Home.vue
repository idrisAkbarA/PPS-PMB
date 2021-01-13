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
          @click="$router.push({name:'Panduan Pendaftaran'})"
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
        v-if="ujian && user"
        class="mb-10 mt-5"
      >
        <v-col
          v-for="(item,index) in ujian"
          :key="index"
          cols="12"
          md="6"
          lg="3"
        >
          <v-card
            elevation="10"
            @click="goToPendaftaran(item)"
          >
            <v-card-title :class="setColor(item) + ' text-white'">{{item.jurusan.nama}}</v-card-title>
            <v-card-subtitle :class="setColor(item) +' text-white'">Periode {{item.periode.nama}}<br>Klik untuk melihat rincian</v-card-subtitle>
            <v-card-text>
              <v-container class="mt-4">
                <p v-if="!checkPeriode(item)">Periode Sudah Berakhir</p>
                <p v-else-if="!isBiodataFilled">Lengkapi biodata diri</p>
                <p v-else-if="item.lunas_at == null">Mohon selesaikan pembayaran</p>
                <v-row v-else-if="isStillUjian(item)">
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
                <p v-else-if="item.is_lulus_tka == true && item.is_lulus_tkj == true">Silakan tentukan temu ramah</p>
                <p v-else>Maaf, anda gagal ujian</p>
                <!-- <p v-else-if="!item.is_lulus_tka">Maaf, anda gagal ujian</p> -->
              </v-container>
            </v-card-text>
          </v-card>
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
    <v-btn
      v-if="ujian"
      rounded
      class="floating-button"
      color="green darken-2"
      dark
      x-large
      elevation="20"
      @click="$router.push({name:'Pendaftaran Baru'})"
    >
      <v-icon left>mdi-plus</v-icon>Buat Pendaftaran Baru
    </v-btn>
  </v-container>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
import DaftarComponent from "../Components/DaftarComponent";
export default {
  created() {
    console.log(this.now);
    // this.getUser("cln_mahasiswa");
    this.initAllDataClnMhs().then(response => {});
  },
  watch: {
    user: {
      deep: true,
      handler: function(v) {
        this.checkBiodata(v);
      }
    }
  },
  methods: {
    ...mapActions(["getUser", "initAllDataClnMhs"]),
    ...mapMutations(["setUjianSelected"]),
    checkPeriode(ujian) {
      var today = new Date();
      var batas_ujian = new Date();
      var akhir_periode = new Date(ujian.periode.akhir_periode);

      if (today > akhir_periode) {
        return false;
        // return false;
      }
      return true;
    },
    // ngecek apakah ada ujian yang belum selesai dan masih dalam rentang uian
    isStillUjian(item) {
      var today = new Date();
      var batas_ujian = new Date(item.batas_ujian);
      var isUjianInRange = today <= batas_ujian;
      if (item.is_lulus_tka == false) return false;
      if (item.is_lulus_tkj == false) return false;
      if (!isUjianInRange) return false;
      return true;
      //   if (item.is_lulus_tka == null || item.is_lulus_tka == true) return true;
      //   if (item.is_lulus_tkj == null || item.is_lulus_tkj == true) return true;

      //   if (!isStillUjian) return false;

      //   if (
      //     (item.is_lulus_tka == null) ^ (item.is_lulus_tkj == null) &&
      //     isStillUjian
      //   ) {
      //     return true;
      //   }
      //   return false;
    },
    setColor(ujian) {
      // var today = new Date();
      // var akhir_periode = new Date(ujian.periode["akhir_periode"]);
      var today = new Date();
      var batas_ujian = new Date(ujian.batas_ujian);
      var isUjianInRange = today <= batas_ujian;
      var isPeriode = this.checkPeriode(ujian);
      // jika sudah lewat periode card warna biru
      if (!isPeriode) {
        return "grey darken-1";
      }
      // jika biodata belum lengkap card warna ungu
      if (!this.isBiodataFilled) {
        return "purple darken";
      }
      // cek apakah sudah melakukan pembayaran
      // kalo null berarti belom bayar, card warna orange
      if (ujian.lunas_at == null) {
        return "deep-orange lighten-3";
      }
      // cek apakah lulus ujian tka
      // jika gagal ujian tka, card merah
      if (ujian.is_lulus_tka == false) {
        return "red darken";
      }
      // cek apakah lulus ujian tkj
      // jika gagal ujian tkj, card merah
      if (ujian.is_lulus_tkj == false) {
        return "red darken";
      }
      // jika lulus ujian tka & tkj
      // card warna hijau
      if (ujian.is_lulus_tka == true && ujian.is_lulus_tkj == true) {
        return "green darken-1";
      } else if (!isUjianInRange) {
        return "red";
      } else {
        return "yellow darken-3";
      }
    },
    // cek biodata sudah terisi apa belum,
    // jika sudah terisi bernilai trus,
    // jika masih ada yg null bernilai false
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
    link() {},
    goToPendaftaran(item) {
      this.setUjianSelected(item);
      this.$router.push(`daftar/${item.id}`);
    },
    createUjian() {
      var periode_id = this.periode[0].id;
      axios.get("/api/ujian/init/" + periode_id).then(response => {
        console.log(response.data);
      });
    },
    countdown(date, index) {
      var timerProperty = "timer-" + index;
      this[timerProperty] = 0;
    },
    startCallBack: function(x) {
      console.log(x);
    },
    endCallBack: function(x) {
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
    }
  },
  computed: {
    ...mapState(["user", "ujian", "isLoading", "periode"]),
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
    }
  },
  data() {
    return {
      dialogTambah: null,
      item: null,
      form: {},
      isBiodataFilled: false,
      isPeriode: false
    };
  },
  components: {
    DaftarComponent
  }
};
</script>

<style>
.floating-button {
  position: fixed;
  z-index: 2;
  bottom: 50px;
  right: 40px;
}
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
