<template>
  <v-container>
    <p class="text-muted">
      Export data untuk kebutuhan sireg
    </p>
    <v-row>
      <v-card width="100%">
        <v-card-title>Pilih Periode</v-card-title>
        <v-card-subtitle>Pilih periode yang ingin di export</v-card-subtitle>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="6">
                <v-select
                  :items="periode"
                  label="Periode"
                  item-text="nama"
                  item-value="id"
                  v-model="selectedPeriode"
                ></v-select>
              </v-col>
              <v-col cols="6">
                <v-row>
                  <v-col cols="12">
                    <v-btn
                      :disabled="selectedPeriode?false:true"
                      :loading="downloadLoading"
                      @click="downloadExcel()"
                      block
                    >Download Excel</v-btn>
                  </v-col>
                  <v-col cols="12">
                    <v-btn
                      block
                      disabled
                    >Kirim Melalui API</v-btn>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
    </v-row>
  </v-container>
</template>
<script>
import { mapMutations, mapState } from "vuex";
const FileDownload = require("js-file-download");
export default {
  data() {
    return {
      periode: [],
      selectedPeriode: null,
      downloadLoading: false,
    };
  },
  computed: {
    ...mapState(["urlPeriode"]),
  },
  methods: {
    getPeriode() {
      this.isLoading = true;
      axios
        .get(this.urlPeriode)
        .then((response) => {
          this.periode = response.data;
        })
        .catch((err) => {
          console.error(err);
        })
        .then((this.isLoading = false));
    },
    downloadExcel() {
      var url = "/api/export/" + this.selectedPeriode;
      this.downloadLoading = true;
      axios
        .get(url, {
          responseType: "blob",
        })
        .then((response) => {
          FileDownload(response.data, "exportUser.xlsx");
          this.downloadLoading = false;
        });
    },
  },
  created() {
    this.getPeriode();
  },
};
</script>