<template>
  <v-container
    fill-height
    class="ma-5"
  >
    <v-card
      class="mx-auto"
      flat
      :width="windowWidth<=600?'90%':'65%'"
    >
      <v-card-text>
        <v-container>
          <v-row dense>
            <v-col>
              <h2>Pendaftaran</h2>
            </v-col>
          </v-row>
          <v-row
            dense
            v-if="error"
          >
            <v-col>
              <p class="red--text">{{ error }}</p>
            </v-col>
          </v-row>
          <v-row dense>
            <v-col>
              <v-text-field
                :rules="rule"
                prepend-inner-icon="mdi-account-circle"
                hide-details="auto"
                color="green"
                label="Nama Lengkap"
                v-model="form.nama"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row dense>
            <v-col>
              <v-text-field
                :rules="rule"
                prepend-inner-icon="mdi-account-circle"
                hide-details="auto"
                color="green"
                label="Email"
                v-model="form.email"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row dense>
            <v-col>
              <v-text-field
                :rules="rule"
                prepend-inner-icon="mdi-lock"
                color="green"
                v-model="form.password"
                :type="show1 ? 'text' : 'password'"
                label="Password"
                @click:append="show1 = !show1"
                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row dense>
            <v-col>
              <v-text-field
                :rules="rule"
                prepend-inner-icon="mdi-lock"
                color="green"
                v-model="form.password2"
                :type="show1 ? 'text' : 'password'"
                label="Konfirmasi Password"
                @keyup.enter="submit"
                @click:append="show1 = !show1"
                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row
            class="mt-3"
            align="center"
            dense
          >
            <v-col cols="6">
              <v-btn
                :loading="loading"
                @click="submit"
                color="green"
                dark
              >Daftar</v-btn>
            </v-col>
            <v-col :cols="windowWidth<=600?12:6">
              <span>
                Sudah memiliki akun?
                <br v-if="windowWidth<=600">
                Login
                <a href="/login">di sini</a>.
              </span>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>

  </v-container>
</template>

<script>
import { mapMutations } from "vuex";

export default {
  props: {
    urlPendaftaran: String,
  },
  created() {
    var pathArray = window.location.pathname.split("/");
    this.loginUrl = pathArray[pathArray.length - 1];
  },
  methods: {
    ...mapMutations(["mutateUser"]),
    async submit() {
      this.loading = true;
      var isCSRFOkay = await this.getCSRF();
      if (isCSRFOkay) {
        this.register();
      } else {
        this.loading = false;
        console.log("Couldn't get CSRF Cookie");
        this.error = "Maaf terjadi kesalahan, coba lagi dalam beberapa saat";
      }
    },
    register() {
      const form = this.form;
      axios
        .post(this.urlPendaftaran, form)
        .then((response) => {
          if (response.data.status) {
            window.location.replace("user/cln-mhs/home");
          }
        })
        .catch((err) => {
          this.loading = false;
          if (err.response.status == 422) {
            this.error = err.response.data.errors;
            return;
          }
          this.error = "Maaf terjadi kesalahan, coba lagi dalam beberapa saat";
        });
    },
    getCSRF() {
      // return true if retrieved, false if didnt
      return axios
        .get("/sanctum/csrf-cookie")
        .then((response) => {
          return true;
        })
        .catch((err) => {
          return false;
        });
    },
  },
  data() {
    return {
      loginUrl: "",
      loading: false,
      error: null,
      show1: false,
      form: {},
      rule: [(v) => !!v || "Field ini wajib diisi"],
    };
  },
};
</script>

<style></style>
