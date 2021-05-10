<template>
  <v-app v-scroll="onScroll">
    <v-app-bar
      app
      flat
      :color="offsetTop > 0 ? 'green darken-4' : 'transparent'"
      hide-on-scroll
      dense
    >
      <v-app-bar-nav-icon
        v-if="windowWidth <= 600"
        @click.stop="toggleDrawer(windowWidth <= 600)"
      >
      </v-app-bar-nav-icon>
      <template
        v-if="offsetTop > 0"
        v-slot:img="{ props }"
      >
        <v-img
          v-bind="props"
          gradient="to top right, rgba(6, 76, 90, 0.377), rgba(5, 94, 42,.8)"
          :src="'/images/pasca1.jpg'"
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
      <!-- <v-btn
        v-if="windowWidth>=600"
        small
        dark
        text
        @click="dialogBiodata = true"
      >
        <v-icon left>mdi-bio</v-icon>Perbarui Biodata
      </v-btn> -->
      <v-btn
        v-if="windowWidth>=600"
        small
        dark
        text
        @click="dialogGantiPassword = true"
      >
        <v-icon left>mdi-lock</v-icon>ganti password
      </v-btn>
      <v-btn
        v-if="windowWidth>=600"
        small
        dark
        text
        @click="logout()"
      >
        <v-icon left>mdi-logout-variant</v-icon>keluar
      </v-btn>
      <!-- -->
    </v-app-bar>
    <v-navigation-drawer
      v-if="windowWidth<=600"
      :src="'/images/drawer-bg.jpg'"
      v-model="drawer"
      app
      clipped
      :floating="true"
      :permanent="false"
      :expand-on-hover="false"
      :mini-variant="false"
      dark
    >
      <vue-scroll :ops="scrollOps">

        <v-card
          v-if="windowWidth <= 600"
          class="d-flex justify-center pt-4 pr-2 pl-2"
          flat
          tile
        >
          <v-img
            max-width="70"
            :src="'/images/LogoUIN.png'"
          ></v-img>
          <v-card-text>Aplikasi PMB Pascasarjana</v-card-text>
        </v-card>
        <v-card
          v-if="windowWidth <= 600"
          class="d-flex justify-center pr-2 pl-2"
          flat
          tile
        >
          <v-card-text>
            Selamat datang <span v-if="user">{{ user.nama}}</span>
            <br />
            <v-btn
              class="mt-2"
              outlined
              color="green darken-2"
              small
              block
              @click="logout"
            >
              <v-icon>mdi-logout-variant</v-icon>keluar
            </v-btn>
          </v-card-text>
        </v-card>
        <v-list dense>
          <v-list-item
            @click="dialogGantiPassword=true"
            router
            exact
          >
            <v-list-item-action>
              <v-icon>mdi-lock</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Ganti Password</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <!-- <v-list-item
            @click="dialogBiodata=true"
            router
            exact
          >
            <v-list-item-action>
              <v-icon>mdi-bio</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Biodata</v-list-item-title>
              <v-list-item-subtitle>Isi atau perbarui biodata</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item> -->
          <!-- <v-list-item
            :to="`/admin/${$route.params.petugas}/kelola-petugas`"
            router
            exact
          >
            <v-list-item-action>
              <v-icon>mdi-account-multiple</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Petugas</v-list-item-title>
            </v-list-item-content>
          </v-list-item> -->
        </v-list>
      </vue-scroll>
    </v-navigation-drawer>
    <div class="ribbon"></div>

    <!-- Sizes your content based upon application components -->
    <v-main
      style="z-index: 2"
      class="bg-pattern"
    >
      <!-- Provides the application the proper gutter -->
      <v-container fluid>
        <!-- If using vue-router -->
        <router-view></router-view>
      </v-container>
    </v-main>
    <v-dialog
      width="600"
      v-model="dialogBiodata"
      v-if="user"
    >
      <biodata-component></biodata-component>
    </v-dialog>
    <v-dialog
      width="600"
      v-model="dialogGantiPassword"
      v-if="user"
    >
      <ganti-password-component :email="user.email"></ganti-password-component>
    </v-dialog>
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
<style>
a {
  text-decoration: none !important;
}
</style>

<script>
import { mapState } from "vuex";
import GantiPasswordComponent from "../Components/GantiPasswordComponent";
import BiodataComponent from "../Components/BiodataComponent";
export default {
  components: {
    GantiPasswordComponent,
    BiodataComponent,
  },
  computed: {
    ...mapState(["user"]),
  },
  data() {
    return {
      dialogGantiPassword: false,
      dialogBiodata: false,
      offsetTop: 0,
      drawer: false,
      permanent: true,
      miniVariant: true,
      expandOnHover: true,
      scrollOps: {
        scrollPanel: {
          easing: "easeInQuad",
          speed: 800,
          scrollingX: false,
        },
        bar: {
          background: "#FFEBEE",
        },
        vuescroll: {
          mode: "native",
          wheelScrollDuration: 0,
          locking: true,
        },
      },
    };
  },
  methods: {
    toggleDrawer(bool) {
      if (!bool) {
        this.miniVariant = !this.miniVariant;
        this.expandOnHover = !this.expandOnHover;
      } else {
        this.drawer = !this.drawer;
      }
    },
    onScroll(e) {
      this.offsetTop = e.target.scrollingElement.scrollTop;
      // console.log(this.offsetTop);
      // console.log(e);
    },
    logout() {
      axios
        .post("/api/logout")
        .then((response) => {
          console.log("logout", response.data);
          window.location.replace("/");
        })
        .catch(() => {
          window.location.replace("/");
          console.log("Couldn't logout");
          // this.$router.push({ path: "/login" });
        });
    },
  },
};
</script>

<style>
.bg-pattern {
  background: url("/images/pattern1.svg") repeat;
  background-size: 400px;
}
.ribbon {
  position: absolute;
  z-index: 1;
  background: rgb(0, 36, 15);
  background: linear-gradient(
      0deg,
      rgb(5, 94, 42) 0%,
      rgba(6, 90, 13, 0.377) 30%
    ),
    url("/images/pasca1.jpg") no-repeat;
  background-size: cover;
  background-position: center;
  /* background-size: contain; */
  /* background: url("/images/bg.jpg"); */
  /* background: #33691e; */
  width: 100%;
  height: 400px;
}
</style>
