<template>
  <v-container>
    <v-row
      align="center"
      justify="center"
    >

      <v-card
        class="mt-4"
        width="600"
      >
        <v-card-title>
          Ganti Password
        </v-card-title>
        <v-card-subtitle>
          <v-chip
            v-if="snackbar.message"
            :color="snackbar.color"
            class="text-white"
          >{{snackbar.message}}</v-chip>
        </v-card-subtitle>
        <v-card-text>
          <v-text-field
            v-model="passwordLama"
            @click:append="show1 = !show1"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            :type="show1 ? 'text' : 'password'"
            label="Password Lama"
            hint="Masukkan password lama anda"
          >
          </v-text-field>
          <v-text-field
            @click:append="show2 = !show2"
            :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
            v-model="passwordBaru"
            :type="show2 ? 'text' : 'password'"
            label="Password Baru"
            hint="Masukkan password baru"
          >
          </v-text-field>
        </v-card-text>
        <v-card-actions :class="windowWidth<=600?'small-screen':''">
          <v-btn
            @click="changePassword"
            class="text-white"
            color="green"
            :loading="loading"
          >Ganti Password</v-btn>
          <!-- <v-btn text>Lupa password lama?</v-btn> -->
        </v-card-actions>
      </v-card>
    </v-row>
  </v-container>
</template>
<style>
.small-screen {
  transform: scale(0.9);
}
</style>
<script>
export default {
  methods: {
    changePassword() {
      this.loading = true;
      var form = {
        username: this.$route.params.petugas,
        old_password: this.passwordLama,
        new_password: this.passwordBaru,
      };
      axios
        .put("/api/change-password", form)
        .then((response) => {
          this.snackbar.message = response.data.message;
          this.snackbar.color = response.data.status ? "green" : "red";
          this.snackbar.status = true;

          this.passwordBaru = null;
          this.passwordLama = null;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  data() {
    return {
      loading: false,
      snackbar: {
        status: false,
        message: null,
        color: "red",
      },
      show1: false,
      show2: false,
      passwordBaru: null,
      passwordLama: null,
    };
  },
};
</script>

<style>
</style>