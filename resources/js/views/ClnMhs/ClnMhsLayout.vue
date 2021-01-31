<template>
  <v-app v-scroll="onScroll">
    <v-app-bar
      app
      flat
      :color="offsetTop > 0 ? 'green darken-4' : 'transparent'"
      hide-on-scroll
      dense
    >
      <template
        v-if="offsetTop > 0"
        v-slot:img="{ props }"
      >
        <v-img
          v-bind="props"
          gradient="to top right, rgba(6, 76, 90, 0.377), rgba(5, 94, 42,.8)"
          :src="'/images/bg.jpg'"
        ></v-img>
      </template>
      <v-avatar :tile="true">
        <img
          :src="'/images/LogoUIN.png'"
          alt="logo"
        />
      </v-avatar>
      <div style="width: 100%; -webkit-app-region: drag">
        <v-toolbar-title>
          <span class="font-weight-bold ml-4">
            <router-link
              :to="{ name: 'Home Calon Mahasiswa' }"
              style="color: white; text-decoration: none"
            >
              Pendaftaran Pascasarjana
            </router-link>
          </span>
        </v-toolbar-title>
      </div>
      <v-btn
        small
        dark
        text
        @click="logout()"
      >
        <v-icon left>mdi-logout-variant</v-icon>keluar
      </v-btn>
      <!-- -->
    </v-app-bar>
    <div class="ribbon"></div>

    <!-- Sizes your content based upon application components -->
    <v-main style="z-index: 2">
      <!-- Provides the application the proper gutter -->
      <v-container fluid>
        <!-- If using vue-router -->
        <router-view></router-view>
      </v-container>
    </v-main>
    <v-footer
      dark
      color="green darken-4"
      app
      absolute
    >
      Universitas Islam Negeri Sultan Syarif Kasim Riau
    </v-footer>
  </v-app>
</template>

<script>
export default {
  mounted() {
    console.log(this.$vuetify);
  },
  data() {
    return {
      offsetTop: 0
    };
  },
  methods: {
    onScroll(e) {
      this.offsetTop = e.target.scrollingElement.scrollTop;
      // console.log(this.offsetTop);
      // console.log(e);
    },
    logout() {
      axios
        .get("/api/logout")
        .then(response => {
          window.location.replace("/");
        })
        .catch(() => {
          window.location.replace("/");
          console.log("Couldn't logout");
          // this.$router.push({ path: "/login" });
        });
    }
  }
};
</script>

<style>
.ribbon {
  position: absolute;
  z-index: 1;
  background: rgb(0, 36, 15);
  background: linear-gradient(
      0deg,
      rgb(5, 94, 42) 0%,
      rgba(6, 76, 90, 0.377) 100%
    ),
    url("/images/bg.jpg");
  background-size: contain;
  /* background: url("/images/bg.jpg"); */
  /* background: #33691e; */
  width: 100%;
  height: 400px;
}
</style>
