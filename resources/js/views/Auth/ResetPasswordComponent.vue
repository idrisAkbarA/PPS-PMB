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
              <h2>Reset Password</h2>
            </v-col>
          </v-row>
          <v-row
            dense
            v-if="error"
          >
            <v-col>
              <p :class="error.color ? error.color : 'green--text'">{{ error.message }}</p>
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
                prepend-inner-icon="mdi-account-circle"
                hide-details="auto"
                color="green"
                label="Password Baru"
                v-model="password"
                :type="show1 ? 'text' : 'password'"
                @click:append="show1 = !show1"
                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
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
                label="Konfirmasi Password Baru"
                v-model="password_confirmation"
                :type="show2 ? 'text' : 'password'"
                @click:append="show2 = !show2"
                :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
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
                :loading="isLoading"
                color="green"
                dark
                @click="resetPassword()"
              >Reset Password</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
export default {
  created() {
    const thePath = window.location.pathname;
    const segments = thePath.split("/");
    this.token = segments[segments.length - 1];
  },
  methods: {
    resetPassword() {
      this.isLoading = true;
      axios
        .post("/api/reset-password", {
          email: this.email,
          password: this.password,
          token: this.token,
          password_confirmation: this.password_confirmation
        })
        .then((response, error) => {
          // console.log('error',error);
          // console.log('response',response);
          if (response.data.status) {
            this.error = {
              message: response.data.message,
            };
            this.isLoading = false;
            window.location.replace("/login");
          }
        })
        .catch(err => {
          console.log("errornya", err);
          this.error = {
            message: "Data tidak valid!!",
            color: "red--text"
          };
          this.isLoading = false;
        });
    }
  },
  data() {
    return {
      token: null,
      isLoading: false,
      error: null,
      show1: false,
      show2: false,
      email: "",
      password: "",
      password_confirmation: "",
      rule: [v => !!v || "Field ini wajib diisi"]
    };
  }
};
</script>