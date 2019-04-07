@extends('layouts.base')

@section('content')
    <template>
        <v-app id="password.request">
            <v-content>
                <v-container fluid fill-height>
                    <v-layout align-center justify-center>
                        <v-flex xs12 sm8 md4>
                            <h1 class="text-xs-center"><span class="primary--text">Forgot</span> Your Password?</h1>
                            <p class="text-xs-center mb-4">アカウントのメールアドレスを入力してください。</p>
                            <v-card class="pa-4">
                                <v-card-text>
                                    <v-form
                                            method="POST"
                                            action="{{ route('password.email') }}"
                                    >
                                        @csrf
                                        <v-text-field
                                                id="email"
                                                prepend-icon="person"
                                                name="email"
                                                label="E-mail"
                                                type="email"
                                                autofocus
                                                value="{{ old('email') }}"
                                                @if ($errors->has('email'))
                                                error-messages="{{ $errors->first('email') }}"
                                                @endif
                                        >
                                        </v-text-field>
                                        <v-card-actions class="justify-center mt-4">
                                            <v-btn type="submit" color="accent">送信する</v-btn>
                                        </v-card-actions>
                                    </v-form>

                                    <v-card-actions class="justify-center mt-3">
                                        <v-btn
                                                href="{{ route('login') }}"
                                                flat
                                                color="secondary"
                                        >
                                            <v-icon class="mr-1">arrow_back</v-icon>ログインページへ戻る
                                        </v-btn>
                                    </v-card-actions>

                                </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-content>
        </v-app>
    </template>
@endsection
