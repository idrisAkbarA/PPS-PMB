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
          <v-row dense>
            <v-col>
              <v-text-field
                hide-details="auto"
                color="green"
                label="Email"
                v-model="username"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row dense>
            <v-col>
              <v-text-field
                hide-details="auto"
                color="green"
                label="Password"
                v-model="password"
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
  data() {
    return {
      username: "",
      password: "",
    };
  },
};
</script>

<style></style>
