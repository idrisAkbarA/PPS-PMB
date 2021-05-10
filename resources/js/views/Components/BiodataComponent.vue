<template>
  <div>
    <v-card width="100%">
      <v-card-title>
        Biodata
      </v-card-title>
      <v-card-subtitle>
        Isi atau perbarui biodata anda.
      </v-card-subtitle>
      <v-card-text>
        <v-container>
          <v-row>
            <v-text-field
              color="green"
              filled
              :loading="biodata.field0"
              :disabled="biodataDisabled.field0"
              @change="sendUser(user, 0)"
              prepend-inner-icon="mdi-account"
              label="Nama Lengkap"
              v-model="user.nama"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-select
              color="green"
              filled
              :loading="biodata.field7"
              :disabled="biodataDisabled.field7"
              @change="sendUser(user, 7)"
              prepend-inner-icon="mdi-account"
              label="Jenis Kelamin"
              :items="jk"
              v-model="user.jenis_kelamin"
            ></v-select>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              :loading="biodata.field8"
              :disabled="biodataDisabled.field8"
              @change="sendUser(user, 8)"
              prepend-inner-icon="mdi-city"
              label="Tempat Lahir"
              type="text"
              v-model="user.tempat_lahir"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-menu
              ref="menu"
              v-model="menu"
              :close-on-content-click="false"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  color="green"
                  filled
                  :loading="biodata.field9"
                  :disabled="biodataDisabled.field9"
                  @change="sendUser(user, 9)"
                  v-model="user.tgl_lahir"
                  label="Tanggal Lahir"
                  prepend-inner-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker
                color="green"
                ref="picker"
                v-model="user.tgl_lahir"
                :max="new Date().toISOString().substr(0, 10)"
                min="1950-01-01"
                @change="
                  save;
                  sendUser(user, 9);
                "
              ></v-date-picker>
            </v-menu>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              :loading="biodata.field10"
              :disabled="biodataDisabled.field10"
              @change="sendUser(user, 10)"
              prepend-inner-icon="mdi-card-bulleted"
              label="NIK"
              type="number"
              v-model="user.nik"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              :loading="biodata.field1"
              :disabled="biodataDisabled.field1"
              @change="sendUser(user, 1)"
              prepend-inner-icon="mdi-phone"
              label="No Telepon"
              type="number"
              v-model="user.hp"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-text-field
              :loading="biodata.field2"
              :disabled="biodataDisabled.field2"
              color="green"
              filled
              @change="sendUser(user, 2)"
              prepend-inner-icon="mdi-whatsapp"
              label="No Whatsapp"
              type="number"
              v-model="user.wa"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-textarea
              rows="1"
              auto-grow
              color="green"
              filled
              :loading="biodata.field3"
              :disabled="biodataDisabled.field3"
              @change="sendUser(user, 3)"
              prepend-inner-icon="mdi-map-marker"
              label="Alamat Rumah Lengkap"
              v-model="user.alamat"
            ></v-textarea>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              :loading="biodata.field4"
              :disabled="biodataDisabled.field4"
              @change="
                validationNilai(
                  { obj: user, id: 4 },
                  'inggris',
                  user.nilai_bhs_inggris
                )
              "
              prepend-inner-icon="mdi-attachment"
              type="number"
              label="Nilai Bahasa Inggris"
              v-model="user.nilai_bhs_inggris"
              :rules="ruleBahasaInggrisValidation"
            ></v-text-field>
          </v-row>
          <v-row>
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
          <v-row>
            <v-text-field
              color="green"
              type="number"
              filled
              :loading="biodata.field5"
              :disabled="biodataDisabled.field5"
              @change="
                validationNilai(
                  { obj: user, id: 5 },
                  'arab',
                  user.nilai_bhs_arab
                )
              "
              prepend-inner-icon="mdi-attachment"
              label="Nilai Bahasa Arab"
              :rules="ruleBahasaArabValidation"
              v-model="user.nilai_bhs_arab"
            ></v-text-field>
          </v-row>
          <v-row>
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
              required
              :loading="biodata.field6"
              :disabled="biodataDisabled.field6"
              color="green"
              filled
              @change="
                validationNilai({ obj: user, id: 6 }, 'ipk', user.nilai_ipk)
              "
              :rules="ruleIPKValidation"
              prepend-inner-icon="mdi-attachment"
              label="Nilai IPK"
              type="number"
              v-model="user.nilai_ipk"
            ></v-text-field>
            <!-- @change="sendUser(user,6)" -->
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
                  @click="link(user.kartu_keluarga)"
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
  </div>
</template>

<script>
import { mapState, mapActions, mapMutations } from "vuex";
export default {
  computed: {
    ...mapState(["user"]),
  },
  methods: {
    ...mapMutations(["setUser"]),
    ...mapActions(["initAllDataClnMhs", "updateUser"]),
    link(url) {
      var a = "/" + url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
    },
    sendUser(user, id) {
      // id is biodara's property number, see at data property section
      console.log("im called");
      this.biodata["field" + id] = true;
      this.setFieldToDisabled(id, true);
      this.updateUser(user)
        .then((response) => {
          console.log(response);
          setTimeout(() => {
            this.setFieldToDisabled(id, false);
            this.biodata["field" + id] = false;
          }, 300);
        })
        .error((error) => {
          setTimeout(() => {
            console.log(error);
            this.setFieldToDisabled(id, false);
            this.biodata["field" + id] = false;
          }, 300);
        });
    },
    setFieldToDisabled(id, value) {
      Object.keys(this.biodataDisabled).forEach((key) => {
        if (key != "field" + id) {
          this.biodataDisabled[key] = value;
        }
      });
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
      data.append("periode_id", this.periodeID);
      data.append("jurusan_id", this.jurusanID);
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
      data.append("periode_id", this.periodeID);
      data.append("jurusan_id", this.jurusanID);
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
      data.append("periode_id", this.periodeID);
      data.append("jurusan_id", this.jurusanID);
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
      data.append("periode_id", this.periodeID);
      data.append("jurusan_id", this.jurusanID);
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
      data.append("periode_id", this.periodeID);
      data.append("jurusan_id", this.jurusanID);
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
      data.append("periode_id", this.periodeID);
      data.append("jurusan_id", this.jurusanID);
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
      data.append("periode_id", this.periodeID);
      data.append("jurusan_id", this.jurusanID);
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
      data.append("periode_id", this.periodeID);
      data.append("jurusan_id", this.jurusanID);
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
  },
  props: {
    periodeID: null,
    jurusanID: null,
  },
  data() {
    return {
      ruleIPKValidation: [
        (v) => !!v || "IPK wajib diisi",
        (v) => v <= 4 || "IPK hanya 0 - 4",
      ],
      ruleBahasaArabValidation: [(v) => !!v || "Nilai wajib diisi"],
      ruleBahasaInggrisValidation: [(v) => !!v || "Nilai wajib diisi"],
      loadingSheet: { toggle: false, message: null, loading: 0 },
      isLoading: false,
      progress: 0,
      photoFile: null,
      ijazahFile: null,
      file: {},
      menu: false,
      biodataLoading: false,
      biodata: {
        field0: false,
        field1: false,
        field2: false,
        field3: false,
        field4: false,
        field5: false,
        field6: false,
        field7: false,
        field8: false,
        field9: false,
        field10: false,
        field11: false,
        field12: false,
      },
      biodataDisabled: {
        field0: false,
        field1: false,
        field2: false,
        field3: false,
        field4: false,
        field5: false,
        field6: false,
        field7: false,
        field8: false,
        field9: false,
        field10: false,
        field11: false,
      },
      jk: ["Laki-laki", "Perempuan"],
    };
  },
};
</script>

<style>
</style>