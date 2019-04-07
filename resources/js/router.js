import Vue from 'vue'
import VueRouter from 'vue-router'

import Account from './components/Account.vue'

import Clinic from './components/Clinic.vue'
import ClinicBase from './components/ClinicBase.vue'
import ClinicHours from './components/ClinicHours.vue'
import ClinicHolidays from './components/ClinicHolidays.vue'

import AppointmentSetting from './components/AppointmentSetting.vue'
import AppointmentSettingTable from './components/AppointmentSettingTable.vue'
import AppointmentSettingTreatments from './components/AppointmentSettingTreatments.vue'

import Patient from './components/Patient.vue'
import PatientNew from './components/PatientNew.vue'
import PatientEdit from './components/PatientEdit.vue'
import PatientIndex from './components/PatientIndex.vue'
import PatientImport from './components/PatientImport.vue'

import WebAppointmentSetting from './components/WebAppointmentSetting.vue'
import WebAppointmentSettingBase from './components/WebAppointmentSettingBase.vue'
import WebAppointmentSettingMail from './components/WebAppointmentSettingMail.vue'
import WebAppointmentSettingPage from './components/WebAppointmentSettingPage.vue'
import WebAppointmentSettingByPatient from './components/WebAppointmentSettingByPatient.vue'

import Mail from './components/Mail.vue'
import MailTemplate from './components/MailTemplate.vue'
import MailReminder from './components/MailReminder.vue'
import MailRecall from './components/MailRecall.vue'

import MailBox from './components/MailBox.vue'
import MailBoxTemporary from './components/MailBoxTemporary.vue'
import MailBoxInbox from './components/MailBoxInbox.vue'
import MailBoxOutbox from './components/MailBoxOutbox.vue'

import Appointment from './components/Appointment.vue'


Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    base: '/home/',
    routes: [
        {
            path: '/',
            component: Appointment
        },
        {
            path: '/account',
            component: Account
        },
        {
            path: '/clinic',
            component: Clinic,
            children: [
                {
                    path: 'base',
                    component: ClinicBase
                },
                {
                    path: 'hours',
                    component: ClinicHours
                },
                {
                    path: 'holidays',
                    component: ClinicHolidays
                }
            ]
        },
        {
            path: '/appointment-setting',
            component: AppointmentSetting,
            children: [
                {
                    path: 'table',
                    component: AppointmentSettingTable
                },
                {
                    path: 'treatments',
                    component: AppointmentSettingTreatments
                }
            ]
        },
        {
            path: '/patient',
            component: Patient,
            children: [
                {
                    path: 'new',
                    component: PatientNew
                },
                {
                    path: 'edit/:id',
                    component: PatientEdit
                },
                {
                    path: 'index',
                    component: PatientIndex
                },
                {
                    path: 'import',
                    component: PatientImport
                }
            ]
        },
        {
            path: '/web-appointment-setting',
            component: WebAppointmentSetting,
            children: [
                {
                    path: 'base',
                    component: WebAppointmentSettingBase
                },
                {
                    path: 'mail',
                    component: WebAppointmentSettingMail
                },
                {
                    path: 'page',
                    component: WebAppointmentSettingPage
                },
                {
                    path: 'by-patient',
                    component: WebAppointmentSettingByPatient
                }
            ]
        },
        {
            path: '/mail',
            component: Mail,
            children: [
                {
                    path: 'template',
                    component: MailTemplate
                },
                {
                    path: 'reminder',
                    component: MailReminder
                },
                {
                    path: 'recall',
                    component: MailRecall
                }
            ]
        },
        {
            path: '/mailbox',
            component: MailBox,
            children: [
                {
                    path: 'temporary',
                    component: MailBoxTemporary
                },
                {
                    path: 'inbox',
                    component: MailBoxInbox
                },
                {
                    path: 'outbox',
                    component: MailBoxOutbox
                }
            ]
        },









    ]
})

export default router
