<template>
  <v-sheet
    class="mx-auto"
    :width="width()"
    elevation="10"
  >
    <v-card>
      <v-card-title>Pendaftaran</v-card-title>
      <v-card-subtitle></v-card-subtitle>
    </v-card>
    <v-stepper
      v-if="ujianSelected && activePeriode"
      non-linear
      vertical
      v-model="stepper"
    >

    <v-stepper-step
        :editable="!isPembayaranLunas"
        color="green"
        :complete="stepper > 1"
        step="1"
        v-if="stepper == 1"
      >
        Pembayaran
        <!-- <strong
          class="text-red"
          v-if="isBiodataFilled?false:true"
        >Lengkapi biodata terlebih dahulu.</strong> -->
      </v-stepper-step>

      <v-stepper-content step="1">
        <div ref="kode_bayar">
          <v-card
            color="grey lighten-4"
            class="mb-5 ml-2 mt-2 mr-2"
            elevation="5"
          >
            <v-card-title>Lakukan Pembayaran</v-card-title>
            <v-card-subtitle>Lakukan pembayaran untuk dapat mengikuti ujian
              masuk</v-card-subtitle>
            <v-card-text>
              <v-btn
                block
                large
                dark
                :loading="isLoading"
                color="green darken-3"
                v-if="!kodePembayaran"
                @click="generateCode()"
              >Dapatkan Kode Pembayaran</v-btn>
              <div v-if="kodePembayaran && !isPembayaranLunas">
                <span> Segera membayar dengan kode berikut </span>
                <h1>{{ kodePembayaran }}</h1>
              </div>
              <div v-if="isPembayaranLunas">
                <h1>Pembayaran Berhasil!</h1>
                <span>Silahkan lengkapi berkas anda untuk diverifikasi oleh petugas.</span>
              </div>
            </v-card-text>
          </v-card>
        </div>
        <v-btn
          :loading="isLoading"
          v-if="kodePembayaran"
          color="green"
          class="text-white mb-12 ml-2 mr-2"
          text
          block
          @click="downloadKodeBayar"
        >
          <v-icon left>mdi-download</v-icon> Download Kode Bayar
        </v-btn>
        <v-btn
          :disabled="isPembayaranLunas ? false : true"
          color="green darken-2"
          class="text-white "
          @click="stepper = 2"
        >
          Selanjutnya
        </v-btn>
      </v-stepper-content>

      <v-stepper-step
        :complete="stepper > 2"
        :editable="ujianSelected.is_lulus_tka === null"
        color="green"
        step="2"
        :rules="[() => !!isPembayaranLunas]"
        v-if="stepper == 2"
      >
        Kelengkapan Berkas
      </v-stepper-step>
      <v-stepper-content step="2">
        <v-container>
          <v-row v-if="activePeriode.syarat_bhs_inggris">
            <v-text-field
              v-if="!user.sertifikat_bhs_inggris"
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Sertifikat TOEFL"
              @click="$refs.toefl.$refs.input.click()"
            ></v-text-field>
            <template v-else>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-text-field
                  color="green"
                  filled
                  prepend-inner-icon="mdi-attachment"
                  label="Ubah Sertifikat TOEFL"
                  readonly
                  @click="$refs.toefl.$refs.input.click()"
                ></v-text-field>
              </v-col>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-btn
                  block
                  x-large
                  dark
                  @click="link(user.sertifikat_bhs_inggris)"
                  color="green darken-2"
                >lihat File Anda
                </v-btn>
              </v-col>
            </template>
            <v-file-input
              @change="setSertifikatTOEFL()"
              hide-input
              ref="toefl"
              class="d-none"
              accept=".pdf"
              v-model="file.toefl"
            ></v-file-input>
          </v-row>
          <v-row v-if="activePeriode.syarat_bhs_arab">
            <v-text-field
              v-if="!user.sertifikat_bhs_arab"
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Sertifikat TOAFL"
              @click="$refs.toafl.$refs.input.click()"
            ></v-text-field>
            <template v-else>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-text-field
                  color="green"
                  filled
                  prepend-inner-icon="mdi-attachment"
                  label="Ubah Sertifikat TOAFL"
                  readonly
                  @click="$refs.toafl.$refs.input.click()"
                ></v-text-field>
              </v-col>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-btn
                  block
                  x-large
                  dark
                  @click="link(user.sertifikat_bhs_arab)"
                  color="green darken-2"
                >lihat File Anda
                </v-btn>
              </v-col>
            </template>
            <v-file-input
              @change="setSertifikatTOAFL()"
              hide-input
              ref="toafl"
              class="d-none"
              accept=".pdf"
              v-model="file.toafl"
            ></v-file-input>
          </v-row>
          <v-row>
            <v-text-field
              v-if="!user.ijazah"
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Scan Ijazah Terakhir"
              @click="$refs.ijazah.$refs.input.click()"
            ></v-text-field>
            <template v-else>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-text-field
                  color="green"
                  filled
                  prepend-inner-icon="mdi-attachment"
                  label="Ubah File Ijazah"
                  readonly
                  @click="$refs.ijazah.$refs.input.click()"
                ></v-text-field>
              </v-col>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-btn
                  block
                  x-large
                  dark
                  @click="link(user.ijazah)"
                  color="green darken-2"
                >lihat File Anda
                </v-btn>
              </v-col>
            </template>
            <v-file-input
              @change="setIjazah()"
              hide-input
              ref="ijazah"
              class="d-none"
              accept=".pdf"
              v-model="ijazahFile"
            ></v-file-input>
          </v-row>
          <v-row>
            <v-text-field
              v-if="!user.transkip"
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Transkip Nilai"
              @click="$refs.transkip.$refs.input.click()"
            ></v-text-field>
            <template v-else>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-text-field
                  color="green"
                  filled
                  prepend-inner-icon="mdi-attachment"
                  label="Ubah File Transkip Nilai"
                  readonly
                  @click="$refs.transkip.$refs.input.click()"
                ></v-text-field>
              </v-col>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-btn
                  block
                  x-large
                  dark
                  @click="link(user.transkip)"
                  color="green darken-2"
                >lihat File Anda
                </v-btn>
              </v-col>
            </template>
            <v-file-input
              @change="setTranskip()"
              hide-input
              ref="transkip"
              class="d-none"
              accept=".pdf"
              v-model="file.transkip"
            ></v-file-input>
          </v-row>
          <v-row>
            <v-text-field
              v-if="!user.surat_rekomendasi"
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Surat Rekomendasi"
              @click="$refs.rekomendasi.$refs.input.click()"
            ></v-text-field>
            <template v-else>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-text-field
                  color="green"
                  filled
                  prepend-inner-icon="mdi-attachment"
                  label="Ubah File Surat Rekomendasi"
                  readonly
                  @click="$refs.rekomendasi.$refs.input.click()"
                ></v-text-field>
              </v-col>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-btn
                  block
                  x-large
                  dark
                  @click="link(user.surat_rekomendasi)"
                  color="green darken-2"
                >lihat File Anda
                </v-btn>
              </v-col>
            </template>
            <v-file-input
              @change="setSuratRekomendasi()"
              hide-input
              ref="rekomendasi"
              class="d-none"
              accept=".pdf"
              v-model="file.surat_rekomendasi"
            ></v-file-input>
          </v-row>
          <v-row>
            <v-text-field
              v-if="!user.pas_photo"
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Upload Pas Foto"
              readonly
              @click="$refs.photoProfile.$refs.input.click()"
            ></v-text-field>
            <template v-else>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-text-field
                  color="green"
                  filled
                  prepend-inner-icon="mdi-attachment"
                  label="Ganti Pas Foto"
                  readonly
                  @click="$refs.photoProfile.$refs.input.click()"
                ></v-text-field>
              </v-col>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-btn
                  block
                  x-large
                  dark
                  @click="link(user.pas_photo)"
                  color="green darken-2"
                >lihat foto anda
                </v-btn>
              </v-col>
            </template>
            <!-- @change="updateUser(user)" -->
            <v-file-input
              @change="setPhoto()"
              hide-input
              ref="photoProfile"
              class="d-none"
              accept="image/*"
              v-model="photoFile"
            ></v-file-input>
          </v-row>
          <v-row>
            <v-text-field
              v-if="!user.ktp"
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Kartu Tanda Penduduk"
              @click="$refs.ktp.$refs.input.click()"
            ></v-text-field>
            <template v-else>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-text-field
                  color="green"
                  filled
                  prepend-inner-icon="mdi-attachment"
                  label="Ubah File Kartu Tanda Penduduk"
                  readonly
                  @click="$refs.ktp.$refs.input.click()"
                ></v-text-field>
              </v-col>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-btn
                  block
                  x-large
                  dark
                  @click="link(user.ktp)"
                  color="green darken-2"
                >lihat File Anda
                </v-btn>
              </v-col>
            </template>
            <v-file-input
              @change="setKTP()"
              hide-input
              ref="ktp"
              class="d-none"
              accept=".pdf"
              v-model="file.ktp"
            ></v-file-input>
          </v-row>
          <v-row>
            <v-text-field
              v-if="!user.kartu_keluarga"
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Kartu Keluarga"
              @click="$refs.kk.$refs.input.click()"
            ></v-text-field>
            <template v-else>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-text-field
                  color="green"
                  filled
                  prepend-inner-icon="mdi-attachment"
                  label="Ubah File Kartu Keluarga"
                  readonly
                  @click="$refs.kk.$refs.input.click()"
                ></v-text-field>
              </v-col>
              <v-col
                class="ml-1"
                style="padding: 0 !important"
              >
                <v-btn
                  block
                  x-large
                  dark
                  @click="link(user.ktp)"
                  color="green darken-2"
                >lihat File Anda
                </v-btn>
              </v-col>
            </template>
            <v-file-input
              @change="setKartuKeluarga()"
              hide-input
              ref="kk"
              class="d-none"
              accept=".pdf"
              v-model="file.kartu_keluarga"
            ></v-file-input>
          </v-row>
        </v-container>
        <v-btn
          :disabled="!isBerkasFilled"
          color="green darken-2"
          class="text-white"
          :loading="biodataLoading"
          @click="stepper = 3"
        >
          <!-- @click="stepper = 3" -->
          Selanjutnya
        </v-btn>
      </v-stepper-content>

      <v-stepper-step
        color="green"
        step="3"
        v-if="stepper == 3"
      >
        Verifikasi Berkas
        <strong v-if="!ujianSelected.lulus_at">Mohon menunggu verfikasi oleh Administrator.</strong>
      </v-stepper-step>

      <v-stepper-content step="3">
        <v-card v-if="ujianSelected.is_lulus_tka===null">
          <v-card-title>
            Menunggu Verifikasi
          </v-card-title>
          <v-card-text>
            Mohon menunggu pengecekan transkip nilai oleh administrator untuk verifikasi Cumlaude
          </v-card-text>
        </v-card>
        <v-card v-if="ujianSelected.is_lulus_tka==1">
          <v-card-title>
            Verifikasi Berhasil!
          </v-card-title>
          <v-card-text>
            Selamat anda lulus verifikasi cumlaude. silahkan melihat jadwal wawancara dan informasi selanjutnya pada web Pascasarjana pada link berikut <a href="https://pasca.uin-suska.ac.id/" target="_blank">https://pasca.uin-suska.ac.id/</a>
          </v-card-text>
        </v-card>
        <v-card v-if="ujianSelected.is_lulus_tka==0">
          <v-card-title>
            Verifikasi Gagal!
          </v-card-title>
          <v-card-text>
            Maaf anda tidak lulus verifikasi cumlaude, silahkan mendaftar dengan jalur reguler.
          </v-card-text>
          <v-card-actions>
            <v-btn
              class="text-white"
              color="green darken-3"
              @click="goToHome()"
            >
              kembali ke halaman utama
            </v-btn>
          </v-card-actions>
        </v-card>

      </v-stepper-content>
    </v-stepper>
    <v-card v-else-if="activePeriode.id != ujian.periode_id">
        <v-card-text>
            Maap periode telah ditutup.
        </v-card-text>
    </v-card>
    <v-card v-else>
      <v-card-text>
        <v-progress-circular indeterminate></v-progress-circular>
      </v-card-text>
    </v-card>
    <v-bottom-sheet
      eager
      overlay-color="green darken-4"
      v-model="loadingSheet.toggle"
      inset
      persistent
    >
      <v-card tile>
        <v-progress-linear
          :value="progress"
          class="my-0"
          :height="5"
        ></v-progress-linear>
        <v-list>
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>{{
                this.loadingSheet.message
              }}</v-list-item-title>
              <v-list-item-subtitle>{{
                this.progress + "%"
              }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card>
    </v-bottom-sheet>
  </v-sheet>
</template>

<script>
const FileDownload = require("js-file-download");
import { mapState, mapActions, mapMutations } from "vuex";
export default {
  methods: {
    ...mapMutations(["setUser", "setUser", "setJurusan", "setUjianSelected"]),
    ...mapActions(["initAllDataClnMhs"]),
    downloadKodeBayar: async function () {
      this.isLoading = true;
      const el = this.$refs.kode_bayar;
      console.log(el);
      // add option type to get the image version
      // if not provided the promise will return
      // the canvas.
      const options = {
        type: "blob",
      };
      await this.$html2canvas(el).then(function (canvas) {
        canvas.toBlob(function (blob) {
          // saveAs(blob, "wholePage.png");
          FileDownload(blob, "kode_bayar.png");
        });
      });

      this.isLoading = false;
    },
    goToHome() {
      this.$router.push({ name: "Home Calon Mahasiswa" });
    },
    initPendaftaran(vm) {
      // this method called if the page get reloaded or direct access via url
      // this method initialize the data that this page needed
      console.log(vm);
      const thePath = window.location.pathname;
      const getLastItem = (thePath) =>
        thePath.substring(thePath.lastIndexOf("/") + 1);
      var payload = { jurusan_id: getLastItem(thePath) };
      axios
        .post("/api/ujian/get-pendaftaran", payload)
        .then((response) => {
          vm.setUser(response.data.user);
          vm.setJurusan(response.data.jurusan);
          vm.setUjianSelected(response.data.ujian);
          vm.checkBiodata(vm.user);
          vm.setData(vm);
        })
        .catch((error) => {});
    },
    setData(ini) {
      ini.kodePembayaran = ini.ujianSelected.kode_bayar;
      if (ini.ujianSelected.is_lunas)
        ini.isPembayaranLunas = !!ini.ujianSelected.is_lunas;
        console.log(ini.isPembayaranLunas, ini.ujianSelected.is_lulus_tka);
      if (ini.isPembayaranLunas && !ini.isBerkasFilled) {
        ini.stepper = 2;
      }
      if (ini.isPembayaranLunas && ini.isBerkasFilled) {
        ini.stepper = 3;
      }
      if(ini.is_lulus_tka && ini.is_lulus_tkj) {
        ini.stepper = 4;
      }
        console.log('asdasda', ini.isBerkasFilled);
    },
    checkBiodata(v) {
        if(!this.activePeriode){
            return;
        }
        const biodata = ["nama", "email", "hp", "wa", "alamat", "jenis_kelamin", "tgl_lahir", "tempat_lahir", "nik", "nilai_ipk"];
        const berkas = ["ijazah", "surat_rekomendasi", "kartu_keluarga", "ktp", "pas_photo", "transkip"];
        if(this.activePeriode.syarat_bhs_inggris || this.activePeriode.syarat_bhs_arab){
            berkas.push("sertifikat_bhs_inggris", "sertifikat_bhs_arab");
            biodata.push("nilai_bhs_inggris", "nilai_bhs_arab");
        }
        this.isBiodataFilled = Object.keys(v).filter(f => biodata.includes(f)).every((el) => {
            return v[el];
        });
        this.isBerkasFilled = Object.keys(v).filter(f => berkas.includes(f)).every((el) => {
            return v[el];
        });
        console.log(this.isBiodataFilled, this.isBerkasFilled);
    },
    setSertifikatTOEFL() {
      var exts = ["pdf"];
      var isExtValid = this.checkFileType(this.file.toefl, _.clone(exts));
      if (!isExtValid) {
        var extensions = exts.join(", ");
        console.log(extensions);
        this.snackbar.message =
          "Maaf sepertinya file anda tidak dalam format yang sesuai (" +
          extensions +
          ")";
        this.snackbar.color = "red";
        this.snackbar.show = true;
        return 0;
      }
      this.progress = 0;
      this.loadingSheet.toggle = true;
      this.loadingSheet.message = "Mengupload Sertifikat TOEFL...";
      var data = new FormData();
      data.append("file", this.file.toefl);
      data.append("methodName", "saveToeflPath");
      data.append("periode_id", this.ujianSelected.periode_id);
      data.append("jurusan_id", this.ujianSelected.jurusan_id);
      this.upload(data, this).then((response) => {
        console.log(response.data);
        this.loadingSheet.message = "File berhasil di upload";
        this.setUser(response.data.user);
        setTimeout(() => {
          this.loadingSheet.toggle = false;
        }, 1500);
      });
    },
    setSertifikatTOAFL() {
      var exts = ["pdf"];
      var isExtValid = this.checkFileType(this.file.toafl, _.clone(exts));
      if (!isExtValid) {
        var extensions = exts.join(", ");
        console.log(extensions);
        this.snackbar.message =
          "Maaf sepertinya file anda tidak dalam format yang sesuai (" +
          extensions +
          ")";
        this.snackbar.color = "red";
        this.snackbar.show = true;
        return 0;
      }
      this.progress = 0;
      this.loadingSheet.toggle = true;
      this.loadingSheet.message = "Mengupload Sertifikat TOAFL...";
      var data = new FormData();
      data.append("file", this.file.toafl);
      data.append("methodName", "saveToaflPath");
      data.append("periode_id", this.ujianSelected.periode_id);
      data.append("jurusan_id", this.ujianSelected.jurusan_id);
      this.upload(data, this).then((response) => {
        console.log(response.data);
        this.loadingSheet.message = "File berhasil di upload";
        this.setUser(response.data.user);
        setTimeout(() => {
          this.loadingSheet.toggle = false;
        }, 1500);
      });
    },
    setSuratRekomendasi() {
      var exts = ["pdf"];
      var isExtValid = this.checkFileType(
        this.file.surat_rekomendasi,
        _.clone(exts)
      );
      if (!isExtValid) {
        var extensions = exts.join(", ");
        console.log(extensions);
        this.snackbar.message =
          "Maaf sepertinya file anda tidak dalam format yang sesuai (" +
          extensions +
          ")";
        this.snackbar.color = "red";
        this.snackbar.show = true;
        return 0;
      }
      this.progress = 0;
      this.loadingSheet.toggle = true;
      this.loadingSheet.message = "Mengupload Surat Rekomendasi...";
      var data = new FormData();
      data.append("file", this.file.surat_rekomendasi);
      data.append("methodName", "saveSuratRekomendasiPath");
      data.append("periode_id", this.ujianSelected.periode_id);
      data.append("jurusan_id", this.ujianSelected.jurusan_id);
      this.upload(data, this).then((response) => {
        console.log(response.data);
        this.loadingSheet.message = "File berhasil di upload";
        this.setUser(response.data.user);
        setTimeout(() => {
          this.loadingSheet.toggle = false;
        }, 1500);
      });
    },
    setKartuKeluarga() {
      var exts = ["pdf"];
      var isExtValid = this.checkFileType(
        this.file.kartu_keluarga,
        _.clone(exts)
      );
      if (!isExtValid) {
        var extensions = exts.join(", ");
        console.log(extensions);
        this.snackbar.message =
          "Maaf sepertinya file anda tidak dalam format yang sesuai (" +
          extensions +
          ")";
        this.snackbar.color = "red";
        this.snackbar.show = true;
        return 0;
      }
      this.progress = 0;
      this.loadingSheet.toggle = true;
      this.loadingSheet.message = "Mengupload Kartu Keluarga...";
      var data = new FormData();
      data.append("file", this.file.kartu_keluarga);
      data.append("methodName", "saveKartuKeluargaPath");
      data.append("periode_id", this.ujianSelected.periode_id);
      data.append("jurusan_id", this.ujianSelected.jurusan_id);
      this.upload(data, this).then((response) => {
        console.log(response.data);
        this.loadingSheet.message = "File berhasil di upload";
        this.setUser(response.data.user);
        setTimeout(() => {
          this.loadingSheet.toggle = false;
        }, 1500);
      });
    },
    setKTP() {
      var exts = ["pdf"];
      var isExtValid = this.checkFileType(this.file.ktp, _.clone(exts));
      if (!isExtValid) {
        var extensions = exts.join(", ");
        console.log(extensions);
        this.snackbar.message =
          "Maaf sepertinya file anda tidak dalam format yang sesuai (" +
          extensions +
          ")";
        this.snackbar.color = "red";
        this.snackbar.show = true;
        return 0;
      }
      this.progress = 0;
      this.loadingSheet.toggle = true;
      this.loadingSheet.message = "Mengupload KTP...";
      var data = new FormData();
      data.append("file", this.file.ktp);
      data.append("methodName", "saveKtpPath");
      data.append("periode_id", this.ujianSelected.periode_id);
      data.append("jurusan_id", this.ujianSelected.jurusan_id);
      this.upload(data, this).then((response) => {
        console.log(response.data);
        this.loadingSheet.message = "File berhasil di upload";
        this.setUser(response.data.user);
        setTimeout(() => {
          this.loadingSheet.toggle = false;
        }, 1500);
      });
    },
    setTranskip() {
      var exts = ["pdf"];
      var isExtValid = this.checkFileType(this.file.transkip, _.clone(exts));
      if (!isExtValid) {
        var extensions = exts.join(", ");
        console.log(extensions);
        this.snackbar.message =
          "Maaf sepertinya file anda tidak dalam format yang sesuai (" +
          extensions +
          ")";
        this.snackbar.color = "red";
        this.snackbar.show = true;
        return 0;
      }
      this.progress = 0;
      this.loadingSheet.toggle = true;
      this.loadingSheet.message = "Mengupload Transkip Nilai...";
      var data = new FormData();
      data.append("file", this.file.transkip);
      data.append("methodName", "saveTranskipPath");
      data.append("periode_id", this.ujianSelected.periode_id);
      data.append("jurusan_id", this.ujianSelected.jurusan_id);
      this.upload(data, this).then((response) => {
        console.log(response.data);
        this.loadingSheet.message = "File berhasil di upload";
        this.setUser(response.data.user);
        setTimeout(() => {
          this.loadingSheet.toggle = false;
        }, 1500);
      });
    },
    setIjazah() {
      var exts = ["pdf"];
      var isExtValid = this.checkFileType(this.ijazahFile, _.clone(exts));
      if (!isExtValid) {
        // console.log(exts);
        var extensions = exts.join(", ");
        console.log(extensions);
        this.snackbar.message =
          "Maaf sepertinya file anda tidak dalam format yang sesuai (" +
          extensions +
          ")";
        this.snackbar.color = "red";
        this.snackbar.show = true;
        return 0;
      }
      this.progress = 0;
      this.loadingSheet.toggle = true;
      this.loadingSheet.message = "Mengupload File Ijazah...";
      var data = new FormData();
      data.append("file", this.ijazahFile);
      data.append("methodName", "saveIjazahPath");
      data.append("periode_id", this.ujianSelected.periode_id);
      data.append("jurusan_id", this.ujianSelected.jurusan_id);
      this.upload(data, this).then((response) => {
        console.log(response.data);
        this.loadingSheet.message = "File berhasil di upload";
        this.setUser(response.data.user);
        setTimeout(() => {
          this.loadingSheet.toggle = false;
        }, 1500);
      });
    },
    setPhoto() {
      var exts = ["png", "jpg", "jpeg"];
      var isExtValid = this.checkFileType(this.photoFile, _.clone(exts));
      if (!isExtValid) {
        // console.log(exts);
        var extensions = exts.join(", ");
        console.log(extensions);
        this.snackbar.message =
          "Maaf sepertinya file anda tidak dalam format yang sesuai (" +
          extensions +
          ")";
        this.snackbar.color = "red";
        this.snackbar.show = true;
        return 0;
      }
      this.progress = 0;
      this.loadingSheet.toggle = true;
      this.loadingSheet.message = "Mengupload File Photo...";
      var data = new FormData();
      data.append("file", this.photoFile);
      data.append("methodName", "savePhotoPath");
      data.append("periode_id", this.ujianSelected.periode_id);
      data.append("jurusan_id", this.ujianSelected.jurusan_id);
      this.upload(data, this).then((response) => {
        console.log(response.data);
        this.loadingSheet.message = "File berhasil di upload";
        this.setUser(response.data.user);
        setTimeout(() => {
          this.loadingSheet.toggle = false;
        }, 1500);
      });
    },
    upload: async (data, ini) => {
      return axios({
        method: "post",
        url: "/api/user/store-file",
        onUploadProgress: (progressEvent) => {
          var percentCompleted = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          );
          ini.progress = percentCompleted;
        },
        data,
      });
    },
    generateCode() {
      // this method generate payment code
      this.isLoading = true;
      var payload = { ujian_id: this.ujianSelected.id };
      axios
        .post("/api/ujian/generate-pembayaran", payload)
        .then((response) => {
          console.log(response.data);
          this.isLoading = false;
          this.kodePembayaran = response.data.code;
          this.isJurusanEditable = false;
          this.loopCheckPembayaran();
        })
        .catch((error) => {});
    },
    async loopCheckPembayaran() {
      function sleep(ms) {
        return new Promise((res) => setTimeout(res, ms));
      }

      //check is still in the pendaftaran page,
      // if not then stop the loop
      console.log("route now:", this.$route.name);
      if (this.$route.name != "Daftar Cumlaude") {
        console.log("Check Stopped");
        return 0;
      }
      let myAsyncFunc = async function (ini) {
        console.log("Sleeping");
        await sleep(3000);
        console.log("Done");
        // console.log(ini);
        ini.checkPembayaran(ini.ujianSelected.id, ini).then((response) => {
          if (response.data.status) {
            ini.isPembayaranLunas = true;
            ini.setUjianSelected(response.data.ujian);
            return 0;
          }
          ini.loopCheckPembayaran();
        });
      };
      myAsyncFunc(this);
    },
    checkPembayaran: async (ujian_id, ini) => {
      var payload = { ujian_id };
      return new Promise((resolve, reject) => {
        axios
          .post("/api/ujian/check-pembayaran", payload)
          .then((response) => {
            if (response.data.status) ini.isPembayaranLunas = true;
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    checkVerifikasi: async (ujian_id, ini) => {
      var payload = { ujian_id };
      return new Promise((resolve, reject) => {
        axios
          .post("/api/ujian/check-pembayaran", payload)
          .then((response) => {
            if (response.data.status) ini.isPembayaranLunas = true;
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    width() {
      if (this.windowWidth <= 600) {
        return "100%";
      } else if (this.windowWidth <= 960) {
        return "70%";
      } else {
        return "50%";
      }
    },
  },
  computed: {
    ...mapState(["jurusan", "user", "periode", "ujianSelected", "activePeriode"]),
  },
  created() {
    var ini = this;
    if (!this.jurusan) {
      this.initAllDataClnMhs();
    }
    this.initPendaftaran(ini);
},
mounted() {
    if (this.user) {
      var ini = this;
      this.checkBiodata(this.user);
      this.setData(ini);
    }
  },
  watch: {
    user: {
      deep: true,
      handler: function (v) {
        if(this.activePeriode){
            this.checkBiodata(v);
        }
      },
    },
  },
  data() {
    return {
      stepper: 1,
      progress: 0,
      file: {},
      photoFile: null,
      ijazahFile: null,
      isLoading: false,
      biodataLoading: false,
      kodePembayaran: null,
      isPembayaranLunas: false,
      isBerkasFilled: false,
      loadingSheet: { toggle: false, message: null, loading: 0 },
    };
  },
};
</script>

<style>
</style>
