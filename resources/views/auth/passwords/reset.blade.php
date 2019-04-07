@extends('layouts.base')

@section('content')
    <template>
        <v-app id="password.reset">
            <v-content>
                <v-container fluid fill-height>
                    <v-layout align-center justify-center>
                        <v-flex xs12 sm8 md4>
                            <h1 class="text-xs-center"><span class="primary--text">RESET</span> PASSWORD</h1>
                            <p class="text-xs-center mb-4">パスワードの再設定を行います。</p>
                            <v-card class="pa-4">
                                <v-card-text>
                                    <v-form
                                            method="POST"
                                            action="{{ route('password.update') }}"
                                    >
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <v-text-field
                                                id="email"
                                                prepend-icon="person"
                                                name="email"
                                                label="E-mail"
                                                type="email"
                                                autofocus
                                                value="{{ $email ?? old('email') }}"
                                                @if ($errors->has('email'))
                                                error-messages="{{ $errors->first('email') }}"
                                                @endif
                                        >
                                        </v-text-field>
                                        <v-text-field
                                                id="password"
                                                prepend-icon="lock"
                                                name="password"
                                                label="Password"
                                                type="password"
                                                @if ($errors->has('password'))
                                                error-messages="{{ $errors->first('password') }}"
                                                @endif
                                        >
                                        </v-text-field>
                                        <v-text-field
                                                id="password_confirmation"
                                                prepend-icon="lock_open"
                                                name="password_confirmation"
                                                label="Confirm Password"
                                                type="password"
                                        >
                                        </v-text-field>
                                        <v-card-actions class="justify-center mt-4">
                                            <v-btn type="submit" color="accent">パスワードリセット</v-btn>
                                        </v-card-actions>
                                    </v-form>

                                    <v-card-actions class="justify-center mt-3">
                                        <v-btn
                                                href="{{ route('login') }}"
                                                flat
                                                color="secondary"
                                        >
                                            <v-icon class="mr-1">arrow_back</v-icon>ログインページへ
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
