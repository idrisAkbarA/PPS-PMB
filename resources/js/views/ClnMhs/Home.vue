<template>
  <v-container fluid>
    <div style="height:340px">
      <h1 class="text-white mt-6">
        Selamat Datang, <span v-if="user">{{ user.nama}}</span>
        <br>
        di Aplikasi Pendaftaran Pascasarjana
      </h1>
      <h4 class="text-white font-weight-light">
        Universitas Islam Negeri Sultan Syarif Kasim Riau
      </h4>
    </div>
    <v-alert
        v-if="activePeriode"
        text
        outlined
        color="primary"
        type="info"
    >Lihat panduan pendaftaran serta syarat dan ketentuan <a
          href="#"
          class="font-weight-black"
          style="text-decoration: none"
          @click="$router.push({ name: 'Panduan Pendaftaran' })"
        >DI SINI</a>. Harap baca dengan teliti!</v-alert>
    <v-alert
        v-if="activePeriode && ujian && ujian.some(f => checkPeriode(f))"
        text
        outlined
        color="deep-orange"
        type="warning"
    >Dimohon untuk menyelesaikan pendaftaran sebelum periode pendaftaran ditutup. Periode pendaftaran akan ditutup pada {{ $moment(activePeriode.akhir_periode).format("DD MMMM YYYY HH:mm") }}</v-alert>
    <div>
      <h4 class="font-weight-light">Riwayat pendaftaran anda</h4>
    </div>
    <v-container style="padding: 0px !important">
      <v-row v-if="isLoading">
        <v-progress-circular
          class="mx-auto mt-10"
          :size="100"
          width="7"
          indeterminate
          color="green"
        ></v-progress-circular>
      </v-row>
      <v-row v-if="!ujian && !isLoading">
        <v-col cols="12">
          <v-card
            class="bg-with-overlay-empty"
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
                    <h1 class="text-white">
                      Hmm.. Sepertinya anda belum mendaftar
                    </h1>
                    <v-btn
                      large
                      @click="buatPendaftaran"
                    >Silahkan Lakukan Pendaftaran</v-btn>
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
          v-for="(item, index) in ujian"
          :key="index"
          cols="12"
          md="6"
          lg="3"
        >
          <v-hover v-slot="{ hover }">

            <v-card
              :elevation="hover?12:5"
              @click="goToPendaftaran(item)"
              height="100%"
            >

              <v-card-subtitle
                class="pb-0"
                :class="setColor(item) + ' text-white'"
              >
                <p
                  class=""
                  v-if="index==0"
                >Pendaftaran terbaru anda</p>
                <h3 class="mb-0">
                  {{item.jurusan.nama}}
                </h3>
              </v-card-subtitle>
              <v-card-subtitle :class="setColor(item) + ' text-white'">Periode {{ item.periode.nama }}<br />Klik untuk melihat
                rincian</v-card-subtitle>
              <v-card-text>
                <v-container class="mt-4">
                  <p v-if="!checkPeriode(item)">Periode Sudah Berakhir</p>
                  <p v-else-if="!isBiodataFilled || !item.is_agree">Lengkapi biodata diri</p>
                  <p v-else-if="item.is_jalur_cumlaude == 1 && item.lunas_at != null && isBerkasFilled">
                    <span v-if="item.is_lulus_tka == null">
                      Mohon menunggu verifikasi
                    </span>
                    <span v-else-if="
                    item.is_lulus_tka == true && item.is_lulus_tkj == true
                  ">
                      Anda lulus tahap verifikasi
                    </span>
                    <span v-else>Maaf, anda tidak lulus tahap verifikasi</span>
                  </p>
                  <p v-else-if="item.lunas_at == null">
                    Mohon selesaikan pembayaran
                  </p>
                  <p v-else-if="!isBerkasFilled">Lengkapi berkas</p>
                  <p v-else-if="
                    item.is_lulus_tka == true && item.is_lulus_tkj == true
                  ">
                    Selamat anda lulus! silahkan cek jadwal wawancara
                  </p>
                  <v-row v-else-if="isStillUjian(item)">
                    <p>Waktu tersisa untuk menyelesaikan ujian TKA dan TKK</p>
                    <span>
                      <!-- :end-label="''" -->
                      <vue-countdown-timer
                        @start_callback="startCallBack('event started')"
                        @end_callback="endCallBack('event ended')"
                        :start-time="now"
                        :end-time="item.batas_ujian + ' 23:59:59'"
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
                  <p v-else-if="!item.is_lulus_tka || !item.is_lulus_tkj">Maaf, anda gagal ujian</p>
                  <!-- <p v-else-if="!item.is_lulus_tka">Maaf, anda gagal ujian</p> -->
                </v-container>
              </v-card-text>
            </v-card>
          </v-hover>

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
    <v-dialog
      max-width="400"
      v-model="dialogEmpty"
    >
      <v-card>
        <v-img
          class="mx-auto"
          max-width="350"
          :src="'/images/empty.png'"
        ></v-img>
        <v-container>
          <v-row>
            <div class="pa-3">
              <h3>Maaf, Periode pendaftaran belum dibuka...</h3>
              <p>
                Mohon menunggu pembukaan periode pendaftaran, atau hubungi admin pendaftaran.
              </p>
            </div>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
    <v-btn
      v-if="activePeriode && ujian && ujian.every(e => activePeriode.id != e.periode_id || (e.is_lulus_tka != null && e.is_lulus_tkj != null))"
      rounded
      class="floating-button"
      color="green darken-2"
      dark
      x-large
      elevation="20"
      @click="buatPendaftaran()"
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
    this.initAllDataClnMhs().then((response) => {});
  },
  watch: {
    user: {
      deep: true,
      handler: function (v) {
        this.checkBiodata(v);
      },
    },
  },
  methods: {
    ...mapActions(["getUser", "initAllDataClnMhs"]),
    ...mapMutations(["setUjianSelected"]),
    isVerifiedJalurCumlaude(item) {
      if (item.is_jalur_cumlaude) {
        if (item.is_lulus_tka || item.is_lulus_tkj) {
          return true;
        }
        return false;
      }
      return false;
    },
    buatPendaftaran() {
      if (!this.activePeriode) {
        this.dialogEmpty = true;
        return 0;
      }
      var isPeriodeIsInRange = this.isInRange(
        this.activePeriode.awal_periode,
        this.activePeriode.akhir_periode
      );
      if (!isPeriodeIsInRange) {
        this.dialogEmpty = true;
        return 0;
      }
      this.$router.push({ name: "Pendaftaran Baru" });
    },
    isInRange(start, end) {
      return this.$moment(this.now).isBetween(start, end);
    },
    checkPeriode(ujian) {
      var today = new Date();
      var batas_ujian = new Date();
      var akhir_periode = new Date(ujian.periode.akhir_periode);

      if (today > akhir_periode || !ujian.periode.is_active) {
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
      if (!this.isBiodataFilled || !ujian.is_agree) {
        return "purple darken";
      }
      // cek apakah sudah melakukan pembayaran
      // kalo null berarti belom bayar, card warna orange
      if (ujian.lunas_at == null) {
        return "deep-orange lighten-3";
      }
      // jika berkas belum lengkap card warna ungu
      if (!this.isBerkasFilled) {
        return "purple darken";
      }
      // cek apakah lulus ujian tka/tkj
      // jika gagal ujian tka/tkj, card merah
      if (ujian.is_lulus_tka == false || ujian.is_lulus_tkj == false) {
        return "red darken";
      }
      // jika lulus ujian tka & tkj
      // card warna hijau
      if (ujian.is_lulus_tka == true && ujian.is_lulus_tkj == true) {
        return "green darken-1";
      } else if (!isUjianInRange && !ujian.is_jalur_cumlaude) {
        return "red";
      } else {
        return "yellow darken-3";
      }
    },
    // cek biodata sudah terisi apa belum,
    // jika sudah terisi bernilai true,
    // jika masih ada yg null bernilai false
    checkBiodata(v) {
        if(!this.activePeriode){
            return;
        }
        const biodata = ["nama", "email", "hp", "wa", "alamat", "jenis_kelamin", "tgl_lahir", "tempat_lahir", "nik", "nilai_ipk"];
        const berkas = ["ijazah", "surat_rekomendasi", "kartu_keluarga", "ktp", "pas_photo", "transkip"];
        if(this.activePeriode.syarat_bhs_inggris || this.activePeriode.syarat_bhs_arab){
            berkas.push("sertifikat_bhs_inggris", "sertifikat_bhs_arab");
            biodata.push("nilai_bhs_inggris", "nilai_bhs_arab");
        }
        this.isBiodataFilled = Object.keys(v).filter(f => biodata.includes(f)).every((el) => {
            return v[el];
        });
        this.isBerkasFilled = Object.keys(v).filter(f => berkas.includes(f)).every((el) => {
            return v[el];
        });
        console.log(this.isBiodataFilled, this.isBerkasFilled);
    },
    link() {},
    goToPendaftaran(item) {
      this.setUjianSelected(item);
      this.$router.push(`daftar/${item.id}`);
    },
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
    ...mapState(["user", "ujian", "isLoading", "periode", "activePeriode"]),
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
      dialogEmpty: null,
      dialogTambah: null,
      item: null,
      form: {},
      isBiodataFilled: false,
      isBerkasFilled: false,
      isPeriode: false,
    };
  },
  components: {
    DaftarComponent,
  },
};
</script>

<style>
.floating-button {
  position: fixed;
  z-index: 2;
  bottom: 50px;
  right: 40px;
}
.bg-with-overlay-empty {
  background: rgb(0, 36, 15);
  background: linear-gradient(
      0deg,
      rgb(5, 94, 42) 0%,
      rgba(6, 76, 90, 0.377) 100%
    ),
    url("/images/bg.jpg");
  background-size: contain;
}
.bg-with-overlay {
  background: rgb(0, 36, 15);
  background:
  /* linear-gradient(
      0deg,
      rgb(5, 94, 42) 0%,
      rgba(6, 76, 90, 0.377) 100%
    ), */ url("/images/pasca1.jpg")
    no-repeat;
  background-size: contain;
}
</style>
