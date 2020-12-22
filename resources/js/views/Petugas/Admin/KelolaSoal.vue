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
          <!-- ttka -->
          <v-card flat>
            <v-card-text>
              <v-container>
                <v-row class="pl-8 pr-8">
                  <v-text-field
                    prepend-inner-icon="mdi-magnify"
                    clearable
                    label="Pencarian"
                    color="#2C3E50"
                  ></v-text-field>
                </v-row>
                <v-card
                  v-for="n in 5"
                  :key="n"
                  color="rgba(46, 204, 113, 0.25)"
                  class="mt-5"
                >
                  <v-card-title>
                    <v-chip
                      color="#2C3E50"
                      dark
                    >
                      No. 1
                    </v-chip>
                    <v-spacer></v-spacer>
                    <v-btn
                      small
                      color="#2C3E50"
                      dark
                      class="rounded-0"
                    >
                      <v-icon small>mdi-pencil</v-icon>
                    </v-btn>
                    <v-btn
                      small
                      color="#2C3E50"
                      dark
                      class="rounded-0"
                    >
                      <v-icon small>mdi-delete</v-icon>
                    </v-btn>
                  </v-card-title>
                  <v-card-text class="black--text">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.Quam quis temporibus accusantium fuga incidunt. Sequi sed dolorem, adipisci,speriores possimus voluptates et illum aspernatur voluptatum nisi maiores inventore eos dicta.
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Labore, iusto maxime. Dicta odit est,
                    culpa enim saepe ullam? Quidem quis laborum veniam hic incidunt eveniet perspiciatis delectus mollitia eius maxime.
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    <v-radio-group hide-details="auto">
                      <v-radio
                        v-for="n in 5"
                        :key="n"
                        :value="n"
                        label="Lorem ipsum dolor sit amet consectetur adipisicing elit"
                        color="#2C3E50"
                      ></v-radio>
                    </v-radio-group>
                  </v-card-text>
                </v-card>
              </v-container>
            </v-card-text>
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