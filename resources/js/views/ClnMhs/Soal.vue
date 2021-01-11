<template>
  <v-container style="height:calc(100vh - 80px); position:relative; overflow-y:hidden !important;">
    <v-row
      class="fill-height"
      v-if="windowWidth>995"
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
                >01:00:00</v-chip>
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
              v-model="radioGroup"
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
            >SOAL SEBELUMNYA</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              color="green darken-2"
              style="color: #ecf0f1"
            >RAGU - RAGU</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              color="green darken-2"
              style="color: #ecf0f1"
              @click="currentSoal+=1"
            >SOAL BERIKUTNYA</v-btn>
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
            class="mx-0 pa-0"
          >
            <v-btn
              class="ma-1 pa-0"
              tile
              v-for="(soal,index) in soal"
              :key="index+1"
              small
              @click="currentSoal=index"
              color="green darken-2"
              style="color: #ecf0f1;"
            >{{ index+1 }}</v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Mobile -->
    <v-row
      class="fill-height"
      v-if="windowWidth<996"
    >
      <v-col
        style="height: 80%;"
        cols="12"
        class="pa-0"
      >
        <v-card
          color="#ecf0f1"
          class="blue-grey--text text--darken-4"
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
                >SOAL SEBELUMNYA</v-btn>
              </v-col>
              <v-col cols="12">
                <v-btn
                  small
                  block
                  class="mx-auto ma-1"
                  color="green darken-2"
                  style="color: #ecf0f1"
                >RAGU - RAGU</v-btn>
              </v-col>
              <v-col cols="12">
                <v-btn
                  small
                  block
                  class="mx-auto ma-1"
                  color="green darken-2"
                  style="color: #ecf0f1"
                >SOAL BERIKUTNYA</v-btn>
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
              v-for="item in 500"
              :key="item"
              small
              color="green darken-2"
              style="color: #ecf0f1;"
            >{{ item }}</v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from "vuex";
export default {
  computed: {
    ...mapState(["soal"]),
  },
  data() {
    return {
      currentSoal: 0,
    };
  },
};
</script>

<style>
</style>