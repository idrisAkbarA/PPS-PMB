<template>
  <v-container>
    <v-row>
      <v-col cols="6">
        <v-card color="#80CBC4">
          <v-card-title>
            <v-icon left> mdi-school </v-icon>
            <span class="title font-weight-light">Periode aktif</span>
          </v-card-title>
          <v-card-text>
            <h6>Periode</h6>
            <h4 v-if="currentPeriode">
              {{ currentPeriode.nama ? currentPeriode.nama : "-" }}
            </h4>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="3">
        <v-card color="#C5E1A5">
          <v-card-title>
            <v-icon left> mdi-account-details </v-icon>
            <span class="title font-weight-light">Pendaftar</span>
          </v-card-title>
          <v-card-text>
            <h4 v-if="pendaftaran">{{ pendaftaran.length }}</h4>
            <h6>Pendaftar</h6>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="3">
        <v-card color="#A5D6A7">
          <v-card-title>
            <v-icon left> mdi-account-multiple-check </v-icon>
            <span class="title font-weight-light">Lulus</span>
          </v-card-title>
          <v-card-text>
            <h4 v-if="lulus">{{ lulus.length }}</h4>
            <h6>Pendaftar Lulus</h6>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  data() {
    return {
      isLoading: false,
      pendaftaran: null,
      lulus: null,
      snackbar: { show: false },
    };
  },
  computed: {
    ...mapState(["urlPendaftaran", "currentPeriode"]),
  },
  watch: {
    //
  },
  created() {
    this.checkPeriode();
    this.getPendaftaran();
    this.getLulusUjian();
  },
  methods: {
    ...mapActions(["getCurrentPeriode"]),
    ...mapMutations(["setCurrentPeriode"]),
    checkPeriode() {
      if (this.currentPeriode === null) {
        this.getCurrentPeriode();
      }
    },
    getPendaftaran() {
      axios
        .get(this.urlPendaftaran)
        .then((response) => {
          this.pendaftaran = response.data.pendaftaran;
        })
        .catch((err) => console.error(err));
    },
    getLulusUjian() {
      const params = {
        status: "Lulus",
      };
      axios
        .get(this.urlPendaftaran, {
          params: params,
        })
        .then((response) => {
          this.lulus = response.data.pendaftaran;
        })
        .catch((err) => console.error(err));
    },
  },
};
</script>

<style>
</style>
