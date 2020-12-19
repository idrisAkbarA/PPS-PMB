<template>
  <v-container
    fill-height
    class="ma-5"
  >
    <v-card
      class="mx-auto"
      flat
      width="65%"
    >
      <v-card-text>
        <v-container>

          <v-row dense>
            <v-col>
              <h2>Login</h2>
            </v-col>
          </v-row>
          <v-row
            dense
            v-if="error"
          >
            <v-col>
              <p class="red--text">{{error}}</p>
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
                v-model="email"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row dense>
            <v-col>
              <v-text-field
                :rules="rule"
                prepend-inner-icon="mdi-lock"
                color="green"
                v-model="password"
                :type="show1 ? 'text' : 'password'"
                @keyup.enter="loginServer()"
                label="Password"
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
            <v-col cols="3">
              <v-btn
                :loading="loading"
                @click="login()"
                color="green"
                dark
              >Login</v-btn>

            </v-col>
            <v-col>
              <span>
                Belum memiliki akun? Daftar <a href="/pendaftaran">disini</a>.
              </span>

            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  methods: {
    async login() {
      this.loading = true;
      var isCSRFOkay = await this.getCSRF();
      if (isCSRFOkay) {
        axios
          .post("/api/authenticate", {
            email: this.email,
            password: this.password,
          })
          .then((response) => {
            console.log(response.data);
            if (response.data.status != "Authenticated") {
              this.loading = false;
              return (this.error = "Maaf kata sandi/email anda salah");
            }

            console.log("logged in");
          })
          .catch((err) => {
            this.loading = false;
            this.error =
              "Maaf terjadi kesalahan, coba lagi dalam beberapa saat";
          });
      } else {
        this.loading = false;
        console.log("Couldn't get CSRF Cookie");
        this.error = "Maaf terjadi kesalahan, coba lagi dalam beberapa saat";
      }
    },

    getCSRF() {
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
      loading: false,
      error: null,
      show1: false,
      username: "",
      password: "",
      rule: [(v) => !!v || "Field ini wajib diisi"],
    };
  },
};
</script>

<style></style>
