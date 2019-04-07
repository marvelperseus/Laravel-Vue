<template>
    <v-app id="patients">
        <v-content>
            <header-component v-bind:clinic="clinic"></header-component>
            <v-container fluid>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-card class="elevation-12 pa-5">
                            <v-card-text>
                                <p class="login-header">以下の内容を入力しログインしてください</p>
                                <v-form>
                                  <v-text-field
                                          prepend-icon="email"
                                          name="email"
                                          label="ご登録メールアドレス"
                                          type="email"
                                          v-model="email"
                                          v-validate="'required|email'"
                                          :error-messages="errors.collect('email')"
                                          data-vv-name="email"
                                  >
                                  </v-text-field>
                                  <v-subheader class="pa-0"><v-icon>mdi-cake-variant</v-icon>生年月日</v-subheader>
                                  <v-layout class="birthday">
                                    <v-flex xs12 md4>
                                      <v-select
                                        v-model="year"
                                        v-validate="'required'"
                                        :items="years"
                                        :error-messages="errors.collect('select')"
                                        label="年"
                                        data-vv-name="select"
                                        required
                                      ></v-select>
                                    </v-flex>
                                    <v-flex xs12 md4>
                                      <v-select
                                        v-model="month"
                                        v-validate="'required'"
                                        :items="months"
                                        :error-messages="errors.collect('select')"
                                        label="月"
                                        data-vv-name="select"
                                        required
                                      ></v-select>
                                    </v-flex>
                                    <v-flex xs12 md4>
                                      <v-select
                                        md1
                                        v-model="day"
                                        v-validate="'required'"
                                        :items="days"
                                        :error-messages="errors.collect('select')"
                                        label="日"
                                        data-vv-name="select"
                                        required
                                      ></v-select>
                                    </v-flex>
                                  </v-layout>
                                </v-form>
                            </v-card-text>
                            <v-card-actions class="justify-content-center">
                                <v-btn
                                        class="success"
                                        @click="onSignin()"
                                        :disabled="loading"
                                        :loading="loading">
                                    ログイン
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
            <footer-component v-bind:clinic="clinic"></footer-component>
        </v-content>
    </v-app>
</template>

<script>
  import Header from './Header.vue'
  import Footer from './Footer.vue'
  export default {
    name: 'LoginComponent',
    components: {
      'header-component': Header,
      'footer-component': Footer
    },
    props: ['appkey'],
    data: () => ({
      submitted: false,
      menu1: false,
      clinic: [],
      date:'',
      email: '',
      year: null,
      month: null,
      day: null,
      years: [
        '2019','2018','2017','2016','2015','2014','2013','2012','2011','2010','2009','2008',
        '2007','2006','2005','2004','2003','2002','2001','2000','1999','1998','1997','1996',
      ],
       months: [
        '1','2','3','4','5','6','7','8','9','10','11','12'
      ],
       days: [
        '1','2','3','4','5','6','7','8','9','10','11','12','13','14',
        '15','16','17','18','19','20','21','22','23','24','25','26',
        '27','28','29','30','31'
      ],
      dictionary: {
        attributes: {
          email: 'E-mail Address'
          // custom attributes
        },
        custom: {
          select: {
            required: 'Select field is required'
          }
        }
      }
    }),
    mounted () {
      this.$validator.localize('en', this.dictionary)
    },

    computed: {
      error () {
        return this.$store.getters.error
      },
      loading () {
        return this.$store.getters.loading
      },
      user () {
        return this.$store.getters.user
      },
    },
    created() {
      this.initialize()
    },

    methods: {
   
      onSignin() {
        this.date= this.year +'-' + this.month +'-'+ this.day;
         this.$store.dispatch('signUserIn', {email: this.email, birthday: this.date})
          .then(response => {
            console.log(response.statusText)
              this.$validator.validateAll().then(res => {
                if (res && response.statusText == 'OK'){
                  const values = location.pathname.split('/');
                  values.pop();
                   location.pathname = values.join('/') + '/steps';
                }
              })
            })
          .catch(() => {
            this.submitted = false
          })
      },
      async initialize() {
        await axios
          .get('/api/web/appointment/clinic/' + this.appkey)
          .then(response => (
            this.clinic = response.data
          ));
      }
    }
  }
</script>
<style>
    .login-header {
        color: #49a2bc;
        font-size: 14px;
        border-left: 4px solid #49a2bc;
        padding: 0;
        padding-left: 15px;
    }
    .birthday .md4 {
      padding: 0 1rem;
    }
</style>