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
              <p :class="error.color ? error.color : 'green--text'">
                {{ error.message }}</p>
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
          <v-row
            class="mt-3"
            align="center"
            dense
          >
            <v-col cols="3">
              <v-btn
                color="green"
                dark
                :loading="isLoading"
                @click="sendLink()"
              >Kirim Link Reset Password</v-btn>
                <!-- :loading="true" -->
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
    sendLink() {
      this.isLoading = true;
      axios
        .post("/api/forget-password", {
          email: this.email
        })
        .then(response => {
          // console.log(response.data)
          this.isLoading = false;
          if (response.data.status) {
            this.error = {
              message:
                "Link reset password berhasil terkirim. Cek emailmu sekarang!"
            };
          }
        })
        .catch(err => {
          // console.error(err);
          this.isLoading = false;
          this.error = {
            message: "Maaf email tidak dikenali",
            color: "red--text"
          };
        })
        // .then((this.isLoading = false));
    }
  },
  data() {
    return {
      isLoading: false,
      error: null,
      email: "",
      rule: [v => !!v || "Field ini wajib diisi"]
    };
  }
};
</script>