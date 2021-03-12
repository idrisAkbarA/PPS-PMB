<template>
  <v-sheet
    class="mx-auto"
    :width="width()"
    elevation="10"
  >
    <v-card>
      <v-card-title>Pendaftaran</v-card-title>
      <v-card-subtitle></v-card-subtitle>
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
      <!-- :editable="isJurusanEditable" -->
      <v-stepper-step
        color="green"
        step="1"
        v-if="stepper == 1"
      >
        <!-- :complete="ujianSelected ? true : false" -->
        Menunggu Verifikasi
        <strong>Mohon menunggu verfikasi oleh Administrator.</strong>
      </v-stepper-step>

      <v-stepper-content step="1">
        <v-card v-if="ujianSelected.is_lulus_tka===null">
          <v-card-title>
            Menunggu Verifikasi
          </v-card-title>
          <v-card-text>
            Mohon menunggu pengecekan transkip nilai oleh administrator untuk verifikasi Cumlaude
          </v-card-text>
          <v-card-actions>
            <v-btn
              class="text-white"
              color="green darken-3"
              disabled
              @click="stepper = 2"
            >
              Selanjutnya
            </v-btn>
          </v-card-actions>
        </v-card>
        <v-card v-if="ujianSelected.is_lulus_tka==1">
          <v-card-title>
            Verifikasi Berhasil!
          </v-card-title>
          <v-card-text>
            Selamat anda lulus verifikasi cumlaude, silahkan lakukan tahap pembayaran pendaftaran dengan klik tombol 'selanjutnya'.
          </v-card-text>
          <v-card-actions>
            <v-btn
              class="text-white"
              color="green darken-3"
              @click="stepper = 2"
            >
              Selanjutnya
            </v-btn>
          </v-card-actions>
        </v-card>
        <v-card v-if="ujianSelected.is_lulus_tka==0">
          <v-card-title>
            Verifikasi Gagal!
          </v-card-title>
          <v-card-text>
            Maaf anda tidak lulus verifikasi cumlaude, silahkan mendaftar dengan jalur reguler.
          </v-card-text>
          <v-card-actions>
            <v-btn
              class="text-white"
              color="green darken-3"
              @click="goToHome()"
            >
              kembali ke halaman utama
            </v-btn>
          </v-card-actions>
        </v-card>

      </v-stepper-content>
      <v-stepper-step
        :editable="!isPembayaranLunas"
        color="green"
        :complete="stepper > 2"
        step="3"
        v-if="stepper == 2"
      >
        Pembayaran
        <!-- <strong
          class="text-red"
          v-if="isBiodataFilled?false:true"
        >Lengkapi biodata terlebih dahulu.</strong> -->
      </v-stepper-step>

      <v-stepper-content step="2">
        <v-card
          color="grey lighten-4"
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
              :loading="isLoading"
              color="green darken-3"
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
      </v-stepper-content>
    </v-stepper>
  </v-sheet>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
export default {
  methods: {
    ...mapMutations(["setUser", "setUser", "setJurusan", "setUjianSelected"]),
    goToHome() {
      this.$router.push({ name: "Home Calon Mahasiswa" });
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
    setData(ini) {
      ini.kodePembayaran = ini.ujianSelected.kode_bayar;
      if (ini.ujianSelected.lunas_at)
        ini.isPembayaranLunas = ini.ujianSelected.lunas_at;
      if (ini.ujianSelected.is_lulus_tka == null) {
      }
      if (ini.ujianSelected.is_lulus_tka == 1) {
        ini.stepper = 2;
      }
    },
    generateCode() {
      // this method generate payment code
      this.isLoading = true;
      var payload = { ujian_id: this.ujianSelected.id };
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
    async loopCheckPembayaran() {
      function sleep(ms) {
        return new Promise((res) => setTimeout(res, ms));
      }

      //check is still in the pendaftaran page,
      // if not then stop the loop
      console.log("route now:", this.$route.name);
      if (this.$route.name != "Daftar Cumlaude") {
        console.log("Check Stopped");
        return 0;
      }
      let myAsyncFunc = async function (ini) {
        console.log("Sleeping");
        await sleep(3000);
        console.log("Done");
        // console.log(ini);
        ini.checkPembayaran(ini.ujianSelected.id, ini).then((response) => {
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
  },

  created() {
    var ini = this;
    this.initPendaftaran(ini);
  },
  data() {
    return {
      stepper: 1,
      isLoading: false,
      kodePembayaran: null,
      isPembayaranLunas: false,
    };
  },
};
</script>

<style>
</style>