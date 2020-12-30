<template>
  <v-stepper
    alt-labels
    non-linear
    vertical
  >
    <v-stepper-header>
      <v-stepper-step
        editable
        class="text-white"
        step="1"
      >
        Pilih Jurusan
      </v-stepper-step>

      <v-divider></v-divider>

      <v-stepper-step
        editable
        step="2"
      >
        Biodata
      </v-stepper-step>

      <v-divider></v-divider>

      <v-stepper-step
        editable
        step="3"
      >
        Pembayaran
      </v-stepper-step>
      <v-divider></v-divider>

      <v-stepper-step
        editable
        step="4"
      >
        Ujian
      </v-stepper-step>
      <v-divider></v-divider>

      <v-stepper-step
        editable
        step="5"
      >
        Temu Ramah
      </v-stepper-step>
    </v-stepper-header>

    <v-stepper-items>
      <v-stepper-content step="1">
        <v-radio-group
          v-model="jurusanSelected"
          column
        >
          <v-radio
            v-for="item in jurusan"
            :key="item.id"
            :label="item.nama"
            :value="item.id"
          ></v-radio>
        </v-radio-group>
        <v-btn
          color="primary"
          @click="e1 = 2"
        >
          Continue
        </v-btn>

        <v-btn text>
          Cancel
        </v-btn>
      </v-stepper-content>

      <v-stepper-content step="2">
        <v-container>
          <v-row>
            <v-text-field
              color="green"
              filled
              prepend-inner-icon="mdi-account"
              label="Nama Lengkap"
              v-model="form.nama"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              prepend-inner-icon="mdi-phone"
              label="No Telepon"
              v-model="form.hp"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              prepend-inner-icon="mdi-whatsapp"
              label="No Whatsapp"
              v-model="form.wa"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-textarea
              rows="1"
              auto-grow
              color="green"
              filled
              prepend-inner-icon="mdi-map-marker"
              label="Alamat Rumah Lengkap"
              v-model="form.alamat"
            ></v-textarea>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Scan Ijazah"
              v-model="form.ijazah"
            ></v-text-field>
          </v-row>
          <v-row>
            <v-col
              class="mr-1"
              style="padding: 0 !important"
            >
              <v-text-field
                color="green"
                filled
                prepend-inner-icon="mdi-attachment"
                label="Nilai IPK"
                v-model="form.nilai_ipk"
              ></v-text-field>
            </v-col>
            <v-col
              class="ml-1"
              style="padding: 0 !important"
            >
              <v-text-field
                color="green"
                filled
                prepend-inner-icon="mdi-attachment"
                label="Nilai Bahasa"
                v-model="form.nilai_bhs"
              ></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-text-field
              color="green"
              filled
              prepend-inner-icon="mdi-attachment"
              label="Upload Pas Foto"
              v-model="form.nilai_bhs"
              @click="$refs.photoProfile.$refs.input.click()"
            ></v-text-field>
            <v-file-input
              hide-input
              ref="photoProfile"
              class="d-none"
              v-model="form.paspoto"
            ></v-file-input>
          </v-row>
        </v-container>

        <v-btn
          color="primary"
          @click="e1 = 3"
        >
          Continue
        </v-btn>

        <v-btn text>
          Cancel
        </v-btn>
      </v-stepper-content>

      <v-stepper-content step="3">
        <v-card
          class="mb-12"
          color="grey lighten-1"
          height="200px"
        ></v-card>

        <v-btn
          color="primary"
          @click="e1 = 1"
        >
          Continue
        </v-btn>

        <v-btn text>
          Cancel
        </v-btn>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>
</template>

<script>
export default {};
</script>

<style>
</style>