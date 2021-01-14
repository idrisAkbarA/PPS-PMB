<template>
  <v-container v-if="periode">
    <v-row>
      <v-col>
        <v-card
          dark
          :height="100*1.2"
          :width="200*1.2"
        >
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/100?random=5'"
          >
            <v-card-title>
              <span class="body 2">Periode Terbaru</span>
            </v-card-title>
            <v-card-text>
              <h1>{{periode.nama}}</h1>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
      <v-col>
        <v-card
          dark
          :height="100*1.2"
          :width="200*1.2"
        >
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/100?random=5'"
          >
            <v-card-title>
              <span class="body 2">Total Pendaftaran</span>
            </v-card-title>
            <v-card-text>
              <h1>{{total_pendaftaran}}</h1>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
      <v-col>
        <v-card
          dark
          :height="100*1.2"
          :width="200*1.2"
        >
          <v-img
            gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
            :src="'https://picsum.photos/200/100?random=5'"
          >
            <v-card-title>
              <span class="body 2">Total Lulus</span>
            </v-card-title>
            <v-card-text>
              <h1>{{total_lulus}}</h1>
            </v-card-text>
          </v-img>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState, mapMutations } from "vuex";
export default {
  created() {
    this.getData();
  },
  computed: {},
  methods: {
    getData() {
      axios
        .get("/api/ujian/dashboard")
        .then(response => {
          this.periode = response.data.periode;
          this.total_pendaftaran = response.data.total_pendaftaran;
          this.total_lulus = response.data.total_lulus;
        })
        .catch();
    }
  },
  data() {
    return {
      periode: null,
      total_pendaftaran: null,
      total_lulus: null
    };
  }
};
</script>

<style>
</style>
