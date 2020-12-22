<template>
  <v-container>
    Kelola Soal dan hal terkait
    <v-card>
      <v-tabs
        v-model="tab"
        background-color="green"
        centered
        dark
      >
        <v-tabs-slider></v-tabs-slider>

        <v-tab href="#tab-1">
          SOAL TKA

        </v-tab>

        <v-tab href="#tab-2">
          SOAL TKD

        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item value="tab-1">
          <v-card flat>
            <v-card-text>huy</v-card-text>
          </v-card>
        </v-tab-item>
        <v-tab-item value="tab-2">
          <v-card flat>
            <v-card-text>huy</v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      tab: null,
    };
  },
};
</script>

<style>
</style>