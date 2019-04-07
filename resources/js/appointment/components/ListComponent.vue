<template>
    <v-app id="patients">
        <v-content>
            <header-component v-bind:clinic="clinic"></header-component>
            <v-container fluid>
                <v-layout align-center justify-center>
                    <v-card>
                        <v-card-text>| ご予約の確認</v-card-text>
                        <v-card-text>
                            <v-data-table
                                    :headers="headers"
                                    :items="appointments"
                            >
                                <template v-slot:items="props">
                                    <td class="text-xs-right">{{ props.item.start }}</td>
                                    <td class="text-xs-right">{{ props.item.end }}</td>
                                    <td class="text-xs-right">{{ props.item.treatment }}</td>
                                    <td class="justify-center layout px-0">
                                        <v-btn outline color="primary" @click="changeBook()">変更</v-btn>
                                        <v-btn outline color="error">キャンセル</v-btn>
                                    </td>
                                </template>
                            </v-data-table>
                        </v-card-text>
                        <v-card-text>| 次の診療予約をとりたい</v-card-text>
                        <v-card-actions class="action-buttons p-3">

                            <v-btn
                                    @click="createBook()"
                                    color="accent"
                                    large
                            >
                                次回診療予約へ
                                <v-icon right>chevron_right</v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
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
    name: "ListComponent",
    components: {
      'header-component': Header,
      'footer-component': Footer
    },
    props: ['appkey'],
    data: () => ({
      clinic: [],
      appointments: [],
      headers: [
        {text: 'ご予約日時', value: 'start'},
        {text: '', value: 'end'},
        {text: '診療メニュー', value: 'treatment'},
        {text: '操作', value: 'name', sortable: false}
      ],
      user: null
    }),

    computed: {
      error() {
        return this.$store.getters.error
      },
      loading() {
        return this.$store.getters.loading
      }
    },
    created() { 
      const user = JSON.parse(localStorage.getItem('user'));
      console.log(user)
      if (!user) {
        const values = location.pathname.split('/');
        values.pop();
        location.pathname = values.join('/') + '/login';
      } else {
        this.user = user;
        this.initialize();
      }
    },
    methods: {
      async initialize() {
        await axios
          .get('/api/web/appointment/clinic/' + this.appkey)
          .then(response => (
            this.clinic = response.data
          ))
        await axios
          .get('/api/web/appointment/appointments/' + this.user.id)
          .then(response => (
            this.appointments = response.data
          ))
      },
      createBook () {
        const values = location.pathname.split('/');
        values.pop();
        location.pathname = values.join('/') + '/steps';
      },
      changeBook() {
         const values = location.pathname.split('/');
        values.pop();
        location.pathname = values.join('/') + '/update-form';
      }
    }
  }
</script>

<style scoped>
    .is-pre-wrap {
        white-space: pre-wrap;
    }

    @media only screen and (max-width: 768px) {
        .v-content-main-tag {
            padding-top: 56px !important;
        }

        .pa-5 {
            padding: 24px 0 48px 0 !important;
        }

        .v-stepper__content {
            padding: 24px 0 16px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons > *:not(:last-child) {
            margin-bottom: 16px;
        }

        .action-buttons > a {
            width: 94%;
        }
    }
</style>
