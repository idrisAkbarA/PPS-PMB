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
        :complete="ujianSelected ? true : false"
        v-if="stepper == 1"
      >
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
        <v-card v-if="ujianSelected.is_lulus_tka===true">
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
        <v-btn
          class="text-white"
          color="green darken-3"
          @click="stepper = 2"
        >
          Selanjutnya
        </v-btn>
      </v-stepper-content>

    </v-stepper>
  </v-sheet>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
export default {
  methods: {
    ...mapMutations(["setUser", "setUser", "setJurusan", "setUjianSelected"]),
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
      if (ini.ujianSelected.is_lulus_tka === null) {
      }
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
    };
  },
};
</script>

<style>
</style>