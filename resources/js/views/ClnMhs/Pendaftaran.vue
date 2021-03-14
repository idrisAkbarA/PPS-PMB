<template>
  <!-- v-model="stepper" -->
  <v-sheet
    class="mx-auto"
    :width="width()"
    elevation="10"
  >
    <v-card>
      <v-card-title>Pendaftaran</v-card-title>
      <v-card-subtitle>Tahap {{ stepper }} dari 5</v-card-subtitle>
    </v-card>
    <v-card v-if="!ujianSelected">
      <v-card-text>
        <v-progress-circular indeterminate></v-progress-circular>
      </v-card-text>
    </v-card>
    <v-stepper
      non-linear
      vertical
      v-else
      v-model="stepper"
    >
      <v-stepper-step
        color="green"
        :editable="isJurusanEditable"
        step="1"
        :complete="jurusanSelected ? true : false"
        v-if="stepper == 1"
      >
        Pilih Jurusan
        <strong>Pilihlah program studi/konsentrasi yang anda inginkan.</strong>
      </v-stepper-step>

      <v-stepper-content step="1">
        <v-radio-group
          v-model="jurusanSelected"
          column
          @change="initUjian()"
        >
          <v-radio
            color="green"
            v-for="item in jurusan"
            :key="item.id"
            :label="item.nama"
            :value="item.id"
          ></v-radio>
        </v-radio-group>
        <v-btn
          class="text-white"
          color="green darken-3"
          :disabled="!jurusanSelected"
          @click="stepper = 2"
        >
          Selanjutnya
        </v-btn>
      </v-stepper-content>

      <v-stepper-step
        :editable="jurusanSelected ? true : false"
        color="green"
        :complete="stepper > 2"
        step="2"
        :rules="ruleBiodata"
        v-if="stepper == 2"
      >
        Isi Biodata dan Jalur Masuk
        <!-- <strong
          class="text-red"
          v-if="jurusanSelected?false:true"
        >Isi jurusan terlebih dahulu.</strong> -->
      </v-stepper-step>

      <v-stepper-content step="2">
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
          <v-row v-if="ujianSelected">
            <v-card color="grey lighten-4">
              <v-card-title>Pilih Jalur Masuk</v-card-title>
              <v-card-subtitle>
                Silahkan pilih jalur masuk sesuai yang anda inginkan.
              </v-card-subtitle>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-card :color="ujianSelected.is_jalur_cumlaude === true?'white':'grey lighten-3'">
                      <v-card-title>
                        Jalur Cumlaude <span v-if="ujianSelected.is_jalur_cumlaude === true"> - Pilihan anda</span>
                      </v-card-title>
                      <v-card-text>
                        Anda dapat melaksanakan pendaftaran tanpa melalui tes
                        ujian jika anda terbukti lulus dengan predikat Cumlaude.
                        <v-btn
                          :color="ujianSelected.is_jalur_cumlaude === true?'green':'grey'"
                          class="text-white"
                          block
                          @click="setCumlaude()"
                        >Daftar jalur cumlaude</v-btn>
                      </v-card-text>
                    </v-card>
                  </v-row>
                  <v-row class="mt-10">
                    <v-card :color="ujianSelected.is_jalur_cumlaude === false?'white':'grey lighten-3'">
                      <v-card-title>
                        Jalur Reguler <span v-if="ujianSelected.is_jalur_cumlaude === false">- Pilihan anda</span>
                      </v-card-title>
                      <v-card-text>
                        Anda dapat melaksanakan pendaftaran melalui tes
                        ujian dan sesi temu ramah akademik.
                        <v-btn
                          :color="ujianSelected.is_jalur_cumlaude === false?'green':'grey'"
                          class="text-white"
                          block
                          @click="setReguler()"
                        >Daftar jalur Reguler</v-btn>
                      </v-card-text>
                    </v-card>
                  </v-row>
                </v-container>
              </v-card-text>
            </v-card>
          </v-row>

          <v-row v-if="ujianSelected">
            <v-checkbox
              @click="setTnC()"
              v-model="ujianSelected.is_agree"
              color="green"
              label="Setuju dengan syarat dan ketentuan pendaftaran Pascasarjana UIN Suska Riau"
            ></v-checkbox>
          </v-row>
        </v-container>
        <v-btn
          :disabled="!isTnCAgreednBiodataFilled"
          color="green darken-2"
          class="text-white"
          :loading="biodataLoading"
          @click="selanjutnyaBio()"
        >
          <!-- @click="stepper = 3" -->
          Selanjutnya
        </v-btn>
      </v-stepper-content>

      <!-- :editable="isBiodataFilled?true:false" -->
      <v-stepper-step
        :editable="!isPembayaranLunas"
        color="green"
        :complete="stepper > 3"
        step="3"
        :rules="rulePembayaran"
        v-if="stepper == 3"
      >
        Pembayaran
        <!-- <strong
          class="text-red"
          v-if="isBiodataFilled?false:true"
        >Lengkapi biodata terlebih dahulu.</strong> -->
      </v-stepper-step>

      <v-stepper-content step="3">
        <v-card
          color="grey lighten-4"
          class="mb-12 ml-2 mt-2 mr-2"
          elevation="5"
        >
          <!-- v-if="!ujian.kode_bayar" -->
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
              <span>Silahkan melakukan ujian masuk pada tahap selanjutnya</span>
            </div>
          </v-card-text>
        </v-card>
        <v-btn
          :disabled="isPembayaranLunas ? false : true"
          color="green darken-2"
          class="text-white"
          @click="stepper = 4"
        >
          Selanjutnya
        </v-btn>
      </v-stepper-content>

      <!-- :editable="isPembayaranLunas?true:false" -->
      <v-stepper-step
        :complete="stepper > 4"
        :editable="!isLulusUjian"
        color="green"
        step="4"
        :rules="ruleUjian"
        v-if="stepper == 4"
      >
        Ujian
        <!-- <strong
          class="text-red"
          v-if="isPembayaranLunas?false:true"
        >Lakukan pembayaran terlebih dahulu.</strong> -->
        <strong v-if="isLulusUjian">Ujian lulus!</strong>
      </v-stepper-step>
      <v-stepper-content step="4">
        <v-card
          color="grey lighten-4"
          class="mb-12"
        >
          <v-card-title>Ujian Masuk</v-card-title>
          <v-card-subtitle>Lakukan ujian Tes Kemampuan Akademik (TKA) dan Tes Kemampuan
            Keilmuan (TKK)</v-card-subtitle>
          <v-card-text>
            <template v-if="
                ujianSelected.is_lulus_tkj != false &&
                ujianSelected.is_lulus_tka != false
              ">
              <template v-if="!ujianSelected.periode.jadwal_ujian || ujianSelected.periode.jadwal_ujian.length <1">
                <p>Waktu tersisa untuk menyelesaikan ujian TKA dan TKK</p>
                <span>
                  <vue-countdown-timer
                    @start_callback="startCallBack('event started')"
                    @end_callback="endCallBack('event ended')"
                    :start-time="now"
                    :end-time="ujianSelected.batas_ujian + ' 00:00:00'"
                    :interval="1000"
                    :start-label="'Until start:'"
                    label-position="begin"
                    :end-text="'Event ended!'"
                    :day-txt="'hari'"
                    :hour-txt="'jam'"
                    :minutes-txt="'menit'"
                    :seconds-txt="'detik'"
                  >
                  </vue-countdown-timer>
                </span>
              </template>
            </template>
            <template v-else>
              <h4 class="text-red">Maaf anda tidak lulus ujian masuk</h4>
              <label>Silahkan mengulangi pendaftaran</label>
            </template>
            <v-divider></v-divider>
            <template v-if="!ujianSelected.periode.jadwal_ujian || ujianSelected.periode.jadwal_ujian.length <1">
              <v-btn
                color="green darken-2"
                block
                class="text-white"
                :disabled="checkButtonMulaiUjian('tka')"
                @click="ujian('tka')"
              >Mulai Ujian TKA</v-btn>
              <div
                v-if="ujianSelected.is_lulus_tka"
                class="text-center"
              >
                <strong> Status Ujian TKA lulus.</strong>
              </div>
              <div
                v-if="ujianSelected.is_lulus_tka == false"
                class="text-center"
              >
                <strong> Status Ujian TKA gagal.</strong>
              </div>
              <v-divider></v-divider>
              <v-btn
                block
                color="green darken-2"
                class="text-white"
                :disabled="checkButtonMulaiUjian('tkj')"
                @click="ujian('tkj')"
              >Mulai Ujian TKK</v-btn>
              <div
                v-if="ujianSelected.is_lulus_tkj"
                class="text-center"
              >
                <strong> Status Ujian TKJ lulus.</strong>
              </div>
              <div
                v-if="ujianSelected.is_lulus_tkj == false"
                class="text-center"
              >
                <strong> Status Ujian TKJ gagal.</strong>
              </div>
            </template>
            <template v-else>
              <p class="title">Lakukan ujian pada tanggal berikut</p>
              <p>
                Anda dapat melakukan ujian jika berada dalam jadwal yang telah
                ditentukan.
              </p>
              <div
                v-for="(jadwal, index) in ujianSelected.periode.jadwal_ujian"
                :key="index"
              >
                <v-card>
                  <v-card-text>
                    <h5>
                      {{ parseDate(jadwal.start) }} pukul
                      {{ parseDateNTime(jadwal.start) }}
                    </h5>
                    <p>sampai</p>
                    <h5>
                      {{ parseDate(jadwal.end) }} pukul
                      {{ parseDateNTime(jadwal.end) }}
                    </h5>
                    <v-divider></v-divider>
                    <div>
                      <template v-if="
                          ujianSelected.is_lulus_tkj != false &&
                          ujianSelected.is_lulus_tka != false
                        ">
                        <template>
                          <p v-if="isInRange(jadwal.start, jadwal.end)">
                            Waktu tersisa untuk menyelesaikan ujian TKA dan TKK
                          </p>
                          <span>
                            <vue-countdown-timer
                              @start_callback="startCallBack('event started')"
                              @end_callback="endCallBack('event ended')"
                              :start-time="jadwal.start"
                              :end-time="jadwal.end"
                              :interval="1000"
                              :start-label="'Mulai ujian dalam:'"
                              label-position="begin"
                              :end-text="'Waktu ujian telah berlalu!'"
                              :day-txt="'hari'"
                              :hour-txt="'jam'"
                              :minutes-txt="'menit'"
                              :seconds-txt="'detik'"
                            >
                            </vue-countdown-timer>
                          </span>
                        </template>
                      </template>
                      <template v-else>
                        <h4 class="text-red">
                          Maaf anda tidak lulus ujian masuk
                        </h4>
                        <label>Silahkan mengulangi pendaftaran</label>
                      </template>
                      <v-divider></v-divider>
                      <v-card
                        flat
                        class="pt-5 pb-5"
                      >
                        <v-overlay
                          absolute
                          :value="!isInRange(jadwal.start, jadwal.end)"
                        >
                          <v-card class="px-2">
                            Maaf, anda belum berada dalam rentang jadwal
                          </v-card>
                        </v-overlay>
                        <v-btn
                          color="green darken-2"
                          block
                          class="text-white"
                          :disabled="checkButtonMulaiUjian('tka')"
                          @click="ujian('tka')"
                        >Mulai Ujian TKA</v-btn>
                        <div
                          v-if="ujianSelected.is_lulus_tka"
                          class="text-center"
                        >
                          <strong> Status Ujian TKA lulus.</strong>
                        </div>
                        <div
                          v-if="ujianSelected.is_lulus_tka == false"
                          class="text-center"
                        >
                          <strong> Status Ujian TKA gagal.</strong>
                        </div>
                        <v-divider></v-divider>
                        <v-btn
                          block
                          color="green darken-2"
                          class="text-white"
                          :disabled="checkButtonMulaiUjian('tkj')"
                          @click="ujian('tkj')"
                        >Mulai Ujian TKK</v-btn>
                        <div
                          v-if="ujianSelected.is_lulus_tkj"
                          class="text-center"
                        >
                          <strong> Status Ujian TKJ lulus.</strong>
                        </div>
                        <div
                          v-if="ujianSelected.is_lulus_tkj == false"
                          class="text-center"
                        >
                          <strong> Status Ujian TKJ gagal.</strong>
                        </div>
                      </v-card>
                    </div>
                  </v-card-text>
                </v-card>
                <p
                  class="mt-5"
                  v-if="
                    ujianSelected.periode.jadwal_ujian.length > 1 &&
                    index + 1 != ujianSelected.periode.jadwal_ujian.length
                  "
                >
                  atau
                </p>
              </div>
            </template>
          </v-card-text>
        </v-card>
      </v-stepper-content>
      <v-stepper-step
        :editable="isLulusUjian ? true : false"
        color="green"
        step="5"
        :rules="ruleTemuRamah"
        v-if="stepper == 5"
      >
        Temu Ramah
        <!-- <strong
          class="text-red"
          v-if="isLulusUjian?false:true"
        >Anda dapat masuk pada tahap Temu Ramah setelah lulus ujian TKA dan TKJ.</strong> -->
      </v-stepper-step>
      <v-stepper-content step="5">
        <template v-if="jadwalTR != null">
          <v-card
            v-if="jadwalTR.length > 0"
            color="grey lighten-4"
          >
            <v-card-title>Pilih Jadwal Temu Ramah</v-card-title>
            <v-card-text>
              <v-card
                class="mb-2"
                :color="
                  !(jadwalSelected == jadwal.id) ? 'grey lighten-2' : 'white'
                "
                outlined
                v-for="(jadwal, index) in jadwalTR"
                :key="index"
              >
                <v-card-title>{{ parseDate(jadwal.tanggal) }}</v-card-title>
                <v-card-subtitle>
                  Dosen {{ jadwal.nama_dosen }} <br />
                  Kuota {{ calcQuota(jadwal) }}
                </v-card-subtitle>
                <v-card-text>
                  <div v-if="jadwalSelected == jadwal.id">
                    <strong>Tanggal temu ramah anda sudah ditetapkan</strong>
                    Silahkan datang pada waktu yang ditentukan untuk melakukan
                    temu ramah
                  </div>
                  <v-btn
                    v-if="jadwalSelected != jadwal.id"
                    block
                    color="green"
                    large
                    class="text-white"
                    :disabled="jadwalSelected ? true : false"
                    @click="setJadwal(jadwal)"
                  >{{
                      jadwalSelected
                        ? "Anda telah memiliki jadwal"
                        : "Pilih tanggal ini"
                    }}</v-btn>
                </v-card-text>
              </v-card>
            </v-card-text>
          </v-card>
          <v-card v-else>
            <v-card-title>
              Maaf belum ada jadwal, mohon menunggu atau menghubungi admin
            </v-card-title>
          </v-card>
        </template>
        <v-card v-else>
          <v-progress-circular
            class="mx-auto"
            indeterminate
          ></v-progress-circular>
        </v-card>
        <!-- class="mb-12" -->
        <!-- <v-btn
          color="green"

        >
          Selanjutnya
        </v-btn> -->
      </v-stepper-content>
    </v-stepper>
    <v-bottom-sheet
      eager
      overlay-color="green darken-4"
      v-model="loadingSheet.toggle"
      inset
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
    <!-- <v-dialog
      width="400"
      v-model="dialogJalur"
    >
      <v-card color="grey lighten-4">
        <v-card-title>Pilih Jalur Masuk</v-card-title>
        <v-card-subtitle>
          Silahkan pilih jalur masuk sesuai yang anda inginkan.
        </v-card-subtitle>
        <v-card-text>
          <v-container>
            <v-row>
              <v-card>
                <v-card-title>
                  Jalur Cumlaude
                </v-card-title>
                <v-card-text>
                  Anda dapat melaksanakan pendaftaran tanpa melalui tes
                  ujian jika anda terbukti lulus dengan predikat Cumlaude.
                  <v-btn
                    color="green"
                    class="text-white"
                    block
                  >Daftar jalur cumlaude</v-btn>
                </v-card-text>
              </v-card>
            </v-row>
            <v-row class="mt-10">
              <v-card>
                <v-card-title>
                  Jalur Requler
                </v-card-title>
                <v-card-text>
                  Anda dapat melaksanakan pendaftaran melalui tes
                  ujian dan sesi temu ramah akademik.
                  <v-btn
                    color="green"
                    class="text-white"
                    block
                  >Daftar jalur Reguler</v-btn>
                </v-card-text>
              </v-card>
            </v-row>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog> -->
  </v-sheet>
</template>

<script>
import _ from "lodash";
import { mapState, mapActions, mapMutations } from "vuex";
export default {
  // first check if this page reloaded before or accessed directly via url
  beforeRouteEnter(to, from, next) {
    if (from.name == null || from.name == "Soal") {
      next((vm) => {
        console.log(vm.initPendaftaran(vm));
      });
    } else {
      next();
    }
  },
  created() {
    this.sendUser = _.debounce(this.sendUser, 500);
    if (!this.jurusan) {
      this.initAllDataClnMhs();
    }
  },
  mounted() {
    if (this.user) {
      var ini = this;
      this.checkBiodata(this.user);
      this.setData(ini);
      if (this.stepper == 3) {
        this.loopCheckPembayaran();
      }
    }
  },
  methods: {
    ...mapMutations(["setUser", "setUser", "setJurusan", "setUjianSelected"]),
    ...mapActions(["initAllDataClnMhs", "updateUser", "getSoal"]),
    validationNilai(obj, field, value) {
      console.log("IM VALIDATION");
      // field: arab, inggris, ipk
      if (field == "ipk") {
        var isValid = true;
        for (let index = 0; index < this.ruleIPKValidation.length; index++) {
          const element = this.ruleIPKValidation[index];
          console.log("loop", element(value));
          if (element(value) != true) {
            isValid = false;
          }
        }
        console.log("response", isValid);
        if (isValid) {
          this.sendUser(obj.obj, obj.id);
        }
        return 0;
      }
      if (field == "arab") {
        var isValid = true;
        for (
          let index = 0;
          index < this.ruleBahasaArabValidation.length;
          index++
        ) {
          const element = this.ruleBahasaArabValidation[index];
          console.log("loop", element(value));
          if (element(value) != true) {
            isValid = false;
          }
        }
        console.log("response", isValid);
        if (isValid) {
          this.sendUser(obj.obj, obj.id);
        }
        return 0;
      }
      if (field == "inggris") {
        var isValid = true;
        for (
          let index = 0;
          index < this.ruleBahasaInggrisValidation.length;
          index++
        ) {
          const element = this.ruleBahasaInggrisValidation[index];
          console.log("loop", element(value));
          if (element(value) != true) {
            isValid = false;
          }
        }
        console.log("response", isValid);
        if (isValid) {
          this.sendUser(obj.obj, obj.id);
        }
        return 0;
      }
    },
    sendUser(user, id) {
      // id is biodara's property number, see at data property section
      console.log("im called");
      this.biodata["field" + id] = true;
      this.setFieldToDisabled(id, true);
      this.updateUser(user)
        .then((response) => {
          setTimeout(() => {
            this.setFieldToDisabled(id, false);
            this.biodata["field" + id] = false;
          }, 300);
        })
        .error((error) => {
          setTimeout(() => {
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
    selanjutnyaBio() {
      console.log("cumlaude", this.ujianSelected.is_jalur_cumlaude);
      if (this.ujianSelected.is_jalur_cumlaude) {
        this.$router.push({
          name: "Daftar Cumlaude",
          params: { id: this.ujianSelected.id },
        });
      } else {
        this.stepper = 3;
      }
    },
    setCumlaude() {
      this.ujianSelected.is_jalur_cumlaude = true;
      this.biodataLoading = true;
      axios
        .put("/api/ujian/" + this.ujianSelected.id, this.ujianSelected)
        .then((response) => {
          this.biodataLoading = false;
          console.log(response.data);
        });
    },
    setReguler() {
      this.ujianSelected.is_jalur_cumlaude = false;
      this.biodataLoading = true;
      axios
        .put("/api/ujian/" + this.ujianSelected.id, this.ujianSelected)
        .then((response) => {
          this.biodataLoading = false;
          console.log(response.data);
        });
    },
    setTnC() {
      console.log(this.ujianSelected);
      this.biodataLoading = true;
      axios
        .put("/api/ujian/" + this.ujianSelected.id, this.ujianSelected)
        .then((response) => {
          this.biodataLoading = false;
          console.log(response.data);
        });
    },
    checkButtonMulaiUjian(type) {
      if (type == "tka") {
        if (this.ujianSelected.is_lulus_tkj == false) return true;
      }
      if (type == "tkj") {
        if (this.ujianSelected.is_lulus_tka == false) return true;
      }
      if (this.ujianSelected["is_lulus_" + type] == false) return true;
      if (this.ujianSelected["is_lulus_" + type] == true) return true;
    },
    setJadwal(jadwal) {
      jadwal.ids_cln_mhs.push(this.user.id);
      axios
        .put(`/api/temu-ramah/${jadwal.id}`, jadwal)
        .then((response) => {
          var ini = this;
          this.jadwalTR = response.data.temuRamah;
          this.getTemuRamah(ini);
        })
        .catch((error) => {});
    },
    ujian(type) {
      // console.log("ID", this.ujianSelected.id);
      var ujian_id = this.ujianSelected.id;
      var soal_id = this.ujianSelected.soal_id;
      var payload = { ujian_id, type, soal_id };
      console.log(payload);
      this.getSoal(payload).then((response) => {
        this.$router.push({
          name: "Soal",
          params: { type, ujian_id, soal_id: this.ujianSelected.soal_id },
        });
      });
    },
    calcQuota(jadwal) {
      return jadwal.ids_cln_mhs.length + "/" + jadwal.quota;
    },
    setData(ini) {
      // this method set the data after initial data get fetched
      if (ini.ujianSelected.is_jalur_cumlaude) {
        ini.$router.push({
          name: "Daftar Cumlaude",
          params: { id: ini.ujianSelected.id },
        });
      }
      ini.jurusanSelected = ini.ujianSelected.jurusan_id;
      ini.ujian_id = ini.ujianSelected.id;
      ini.kodePembayaran = ini.ujianSelected.kode_bayar;
      ini.getTemuRamah(ini);
      if (ini.kodePembayaran) {
        ini.isJurusanEditable = false;
      }
      if (ini.ujianSelected.lunas_at)
        ini.isPembayaranLunas = ini.ujianSelected.lunas_at;
      ini.isLulusUjian = ini.ujianSelected.lulus_at ? true : false;
      // set stepper position
      if (ini.jurusanSelected != null) ini.stepper = 2;
      if (
        ini.isBiodataFilled != false &&
        ini.jurusanSelected != null &&
        ini.ujianSelected.is_agree
      )
        ini.stepper = 3;
      if (ini.isPembayaranLunas != false) ini.stepper = 4;
      if (ini.isLulusUjian != false) ini.stepper = 5;
      //update rule for biodata's fields
      var batasIPK = ini.ujianSelected.periode.syarat_ipk;
      var batasArab = ini.ujianSelected.periode.syarat_bhs_arab;
      var batasInggris = ini.ujianSelected.periode.syarat_bhs_inggris;
      ini.ruleIPKValidation.push(
        (v) =>
          v >= batasIPK ||
          `Maaf, syarat minimal ipk untuk mendaftar adalah ${batasIPK}`
      );
      ini.ruleBahasaArabValidation.push(
        (v) =>
          v >= batasArab ||
          `Maaf, syarat minimal Bahasa Arab untuk mendaftar adalah ${batasArab}`
      );
      ini.ruleBahasaInggrisValidation.push(
        (v) =>
          v >= batasInggris ||
          `Maaf, syarat minimal Bahasa Inggris untuk mendaftar adalah ${batasInggris}`
      );
      console.log("islulus", ini.isLulusUjian);
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
          vm.setData(vm);
        })
        .catch((error) => {});
    },
    getTemuRamah(ini) {
      var payload = { periode: ini.ujianSelected.periode_id };
      var user_id = ini.ujianSelected.user_cln_mhs_id;
      axios
        .get("/api/temu-ramah", payload)
        .then((response) => {
          // check if is there a date have been booked already
          response.data.temuRamah.forEach((element) => {
            element.ids_cln_mhs.every((id) => {
              if (id == user_id) {
                ini.jadwalSelected = element.id;
                return false;
              }
              return true;
            });
          });
          ini.jadwalTR = response.data.temuRamah;
          console.log("temu ramah", ini.jadwalTR);
        })
        .catch((error) => {});
    },

    checkBiodata(v) {
      Object.keys(v).every((element) => {
        if (element == "email_verified_at") {
          return true;
        }
        if (element == "is_verified") {
          return true;
        }
        if (v[element] == null || v[element] == "") {
          this.isBiodataFilled = false;
          return false;
        }
        this.isBiodataFilled = true;
        return true;
      });
      console.log(this.isBiodataFilled);
    },
    generateCode() {
      // this method generate payment code
      this.isLoading = true;
      var payload = { ujian_id: this.ujian_id };
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
    initUjian() {
      var periode_id = this.activePeriode.id;
      var jurusan_id = this.jurusanSelected;
      var payload = { periode_id, jurusan_id };
      if (this.ujian_id) payload["ujian_id"] = this.ujian_id;
      axios.post("/api/ujian/init", payload).then((response) => {
        this.ujian_id = response.data.ujian_id;
        console.log(response.data);
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
    async loopCheckPembayaran() {
      function sleep(ms) {
        return new Promise((res) => setTimeout(res, ms));
      }

      //check is still in the pendaftaran page,
      // if not then stop the loop
      console.log("route now:", this.$route.name);
      if (this.$route.name != "Pendaftaran") {
        console.log("Check Stopped");
        return 0;
      }
      let myAsyncFunc = async function (ini) {
        console.log("Sleeping");
        await sleep(3000);
        console.log("Done");
        // console.log(ini);
        ini.checkPembayaran(ini.ujian_id, ini).then((response) => {
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
    isInRange(start, end) {
      return this.$moment(this.now).isBetween(start, end);
    },
    parseDate(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },
    parseDateNTime(date) {
      return this.$moment(date, "YYYY-MM-DD h:m:s").format("hh:mm");
    },
    startCallBack(data) {},
    endCallBack(data) {},
    link(url) {
      var a = "/" + url;
      var link = a.replace(" ", "%20");
      window.open(link, "_blank");
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
    save(date) {
      this.$refs.menu.save(date);
    },
  },
  computed: {
    ...mapState(["jurusan", "user", "periode", "ujianSelected"]),
    now: function () {
      var today = new Date();
      var date =
        today.getFullYear() +
        "-" +
        (today.getMonth() + 1) +
        "-" +
        today.getDate();
      var time =
        today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
      var dateTime = date + " " + time;
      return dateTime;
    },
    isTnCAgreednBiodataFilled() {
      if (this.ujianSelected) {
        return (
          this.isBiodataFilled &&
          this.ujianSelected.is_agree &&
          this.ujianSelected.is_jalur_cumlaude !== null
        );
      } else {
        return false;
      }
    },
    PhotoFileName: function () {},
  },
  watch: {
    user: {
      deep: true,
      handler: function (v) {
        this.checkBiodata(v);
      },
    },
    menu(val) {
      val && setTimeout(() => (this.$refs.picker.activePicker = "YEAR"));
    },
    stepper(v) {
      // check if in pembayaran step,
      // if it is then call check pembayaran
      // if (v == 3) {
      //   this.loopCheckPembayaran();
      // }
    },
  },
  data() {
    return {
      dialogJalur: true,
      snackbar: { show: false },
      menu: false,
      date: null,
      pilihJurusanLoading: false,
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
      jadwalSelected: null,
      jadwalTR: null,
      isLoading: false,
      kodePembayaran: null,
      progress: 0,
      file: {},
      photoFile: null,
      ijazahFile: null,
      loadingSheet: { toggle: false, message: null, loading: 0 },
      isPembayaranEditable: true,
      isJurusanEditable: true,
      isPembayaranLunas: false,
      isLulusUjian: false,
      isBiodataFilled: false,
      ruleTemuRamah: [() => this.isLulusUjian != false],
      ruleUjian: [() => this.isPembayaranLunas != false],
      rulePembayaran: [
        () => this.isBiodataFilled != false && this.jurusanSelected != null,
      ],
      ruleBiodata: [() => this.jurusanSelected != null],
      ruleIPKValidation: [
        (v) => !!v || "IPK wajib diisi",
        (v) => v <= 4 || "IPK hanya 0 - 4",
      ],
      ruleBahasaArabValidation: [(v) => !!v || "Nilai wajib diisi"],
      ruleBahasaInggrisValidation: [(v) => !!v || "Nilai wajib diisi"],
      ujian_id: null,
      jurusanSelected: null,
      stepper: 1,
      form: {},
    };
  },
};
</script>

<style>
</style>
