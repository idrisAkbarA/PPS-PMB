<template>
  <v-app id="inspire">
    <v-navigation-drawer
      :src="'/images/drawer-bg.jpg'"
      v-model="drawer"
      app
      clipped
      :floating="true"
      :permanent="windowWidth <= 600 ? false : permanent"
      :expand-on-hover="windowWidth <= 600 ? false : expandOnHover"
      :mini-variant="windowWidth <= 600 ? false : miniVariant"
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
          <v-card-text>Aplikasi Beasiswa UIN Suska Riau</v-card-text>
        </v-card>
        <v-card
          v-if="windowWidth <= 600"
          class="d-flex justify-center pr-2 pl-2"
          flat
          tile
        >
          <v-card-text>
            Selamat datang {{ $route.params.petugas }}
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
            :to="`/admin/${$route.params.petugas}/dashboard`"
            router
            exact
          >
            <v-list-item-action>
              <v-icon>mdi-view-dashboard</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>Dashboard</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item
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
          </v-list-item>
          <v-subheader class="ml-2">PMB</v-subheader>
          <v-list-item
            v-for="(page, i) in PMBpages"
            :key="'PMB-' + i"
            :to="page.to"
            :two-line="page.subtitle ? true : false"
            router
            exact
          >
            <v-list-item-action>
              <v-icon>{{ page.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ page.title }}</v-list-item-title>
              <v-list-item-subtitle v-if="page.subtitle">{{
              page.subtitle
            }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-subheader class="ml-2">CAT</v-subheader>
          <v-list-item
            v-for="(page, i) in CATpages"
            :key="'CAT-' + i"
            :to="page.to"
            :two-line="page.subtitle ? true : false"
            router
            exact
          >
            <v-list-item-action>
              <v-icon>{{ page.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ page.title }}</v-list-item-title>
              <v-list-item-subtitle v-if="page.subtitle">{{
              page.subtitle
            }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </vue-scroll>
    </v-navigation-drawer>

    <v-app-bar
      app
      dense
      clipped-left
    >
      <v-app-bar-nav-icon @click.stop="toggleDrawer(windowWidth <= 600)"></v-app-bar-nav-icon>
      <div style="width: 100%; -webkit-app-region: drag">
        <v-toolbar-title>
          <span
            v-if="!$vuetify.breakpoint.mobile"
            class="font-weight-bold ml-4"
          ></span>
          <!-- Change this automaticly later usig VUEX -->
          <span>{{ $route.name }}</span>
        </v-toolbar-title>
      </div>
      <v-slide-y-transition>
        <v-btn
          small
          class="green darken-3"
          dark
          v-if="checkRoute('Kelola Petugas')"
          @click="setBottomSheetToTrue"
        >
          <v-icon> mdi-plus</v-icon> tambah petugas
        </v-btn>
      </v-slide-y-transition>
      <v-slide-y-transition>
        <v-btn
          small
          class="green darken-3"
          dark
          v-if="checkRoute('Kelola Soal')"
          @click="setBottomSheetToTrue"
        >
          <v-icon> mdi-plus</v-icon> tambah soal
        </v-btn>
      </v-slide-y-transition>
      <v-slide-y-transition>
        <v-btn
          small
          class="green darken-3"
          dark
          v-if="checkRoute('Kelola Periode')"
          @click="setBottomSheetToTrue"
        >
          <v-icon> mdi-plus</v-icon> tambah periode
        </v-btn>
      </v-slide-y-transition>
      <v-slide-y-transition>
        <v-btn
          small
          class="green darken-3"
          dark
          v-if="checkRoute('Kelola Jurusan')"
          @click="setBottomSheetToTrue"
        >
          <v-icon> mdi-plus</v-icon> tambah jurusan
        </v-btn>
      </v-slide-y-transition>
      <v-slide-y-transition>
        <v-btn
          small
          class="green darken-3"
          dark
          v-if="checkRoute('Kelola Temu Ramah') && currentPeriode"
          @click="setBottomSheetToTrue"
        >
          <v-icon> mdi-plus</v-icon> tambah jadwal
        </v-btn>
      </v-slide-y-transition>
      <v-slide-y-transition>
        <v-btn
          small
          class="green darken-3"
          dark
          v-if="checkRoute('Kelola Kategori')"
          @click="setBottomSheetToTrue"
        >
          <v-icon> mdi-plus</v-icon> tambah kategori
        </v-btn>
      </v-slide-y-transition>

      <v-btn
        v-if="windowWidth >= 600"
        small
        text
        @click="logout"
      >
        <v-icon>mdi-logout-variant</v-icon>keluar
      </v-btn>
    </v-app-bar>

    <v-main class="bg-pattern">
      <transition
        name="slide-fade"
        mode="out-in"
      >
        <router-view></router-view>
      </transition>

      <!-- <v-fade-transition mode="in" hide-on-leave="true">
        <router-view></router-view>
      </v-fade-transition>-->
    </v-main>
  </v-app>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  methods: {
    ...mapMutations(["toggleBottomSheet"]),
    ...mapActions(["getCurrentPeriode"]),
    setBottomSheetToTrue() {
      this.toggleBottomSheet(true);
    },
    toggleDrawer(bool) {
      if (!bool) {
        this.miniVariant = !this.miniVariant;
        this.expandOnHover = !this.expandOnHover;
      } else {
        this.drawer = !this.drawer;
      }
    },
    toggleBeasiswa() {
      this.toggleOpenBeasiswa(true);
    },
    checkRoute(name) {
      return this.$route.name == name;
    },
    logout() {
      axios
        .get("/api/logout-petugas")
        .then((response) => {
          window.location.replace("/");
        })
        .catch(() => {
          window.location.replace("/");
          console.log("Couldn't logout");
          // this.$router.push({ path: "/login" });
        });
    },
  },
  props: {
    source: String,
  },
  computed: {
    ...mapState(["currentPeriode"]),
    nama() {
      return this.$store.state.name;
    },
    PMBpages() {
      let petugas = this.$route.params.petugas;
      return [
        {
          icon: "mdi-school",
          title: "Kelola Periode",
          to: `/admin/${petugas}/kelola-periode`,
        },
        {
          icon: "mdi-clipboard-check-multiple",
          title: "Kelola Jurusan",
          to: `/admin/${petugas}/kelola-jurusan`,
        },
        {
          icon: "mdi-account-details",
          title: "Akun Pendaftar",
          to: `/admin/${petugas}/pendaftar`,
        },
        {
          icon: "mdi-book-multiple",
          title: "Pendaftaran",
          to: `/admin/${petugas}/kelola-pendaftaran`,
        },
        {
          icon: "mdi-book-plus",
          title: "Temu Ramah",
          to: `/admin/${petugas}/kelola-temu-ramah`,
        },
      ];
    },
    CATpages() {
      let petugas = this.$route.params.petugas;
      return [
        {
          icon: "mdi-file-document",
          title: "Kelola Soal",
          to: `/admin/${petugas}/kelola-soal`,
        },
        {
          icon: "mdi-file-compare",
          title: "Kelola Kategori",
          to: `/admin/${petugas}/kelola-kategori`,
        },
        {
          icon: "mdi-file-document",
          title: "Laporan Ujian",
          to: `/admin/${petugas}/laporan-ujian`,
        },
        {
          icon: "mdi-tools",
          title: "Setting Ujian",
          to: `/admin/${petugas}/setting-ujian`,
        },
        // {
        //   icon: "mdi-file-document",
        //   title: "Hasil Ujian",
        //   to: `/admin/${petugas}/kelola-kategori`
        // }
      ];
    },
  },
  data: () => ({
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
  }),
  mounted() {
    console.log(this.$route);
    console.log(this.$route.matched);
    this.getCurrentPeriode();
    // this.$vuetify.theme.dark = true;
  },
};
</script>

<style scoped>
.slide-fade-enter-active {
  transition: all 0.2s ease-out;
}
.slide-fade-leave-active {
  transition: all 0.2s ease-in;
}
.slide-fade-enter
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: scale(1.1);

  opacity: 0;
}
.slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: scale(0.9);

  opacity: 0;
}
/* The emerging W3C standard
   that is currently Firefox-only */
*::-webkit-scrollbar {
  width: 7px;

  margin: 10px;
  transition: 1s ease;
}

/* Track */

*::-webkit-scrollbar-track {
  background: transparent;
  /* background: #F9F9F9; */

  margin: 10px;
}

/* Handle */

*::-webkit-scrollbar-thumb {
  transition: 1s ease;
  background: #f79593;
  opacity: 0.5;
  border-radius: 25px;

  margin: 10px;
}

/* Handle on hover */

*::-webkit-scrollbar-thumb:hover {
  transition: 1s ease;
  background: #ff8481;
}
.bg-pattern {
  background: url("/images/pattern1.svg") repeat;
  background-size: 400px;
}
a {
  text-decoration: none !important;
}
</style>
